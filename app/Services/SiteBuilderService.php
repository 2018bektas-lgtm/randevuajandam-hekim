<?php

namespace App\Services;

use App\Models\SiteHomepageSection;
use Illuminate\Support\Collection;

/**
 * Tema bazli anasayfa modülü orkestrasyonu.
 *
 * Görevler:
 *  - Aktif tema id + renk paleti çözümlemesi (site_options üzerinden)
 *  - İlk kurulumda default modül setini seed etme (config/tema_modulleri.php)
 *  - Render için sıralı+aktif modül dizisini + ozel_ayarlar birleştirilmiş halde döndürme
 *  - Modül düzenleme sırasında ozel_ayarlar merge
 */
class SiteBuilderService
{
    public function __construct(protected SiteSettingsService $settings) {}

    /**
     * Aktif tema id — site_options'tan; yoksa config default.
     */
    public function aktifTemaId(): string
    {
        $temaId = (string) $this->settings->option('tema_id', config('tema_modulleri.default_tema', 'tema-1'));

        return $this->temaVarMi($temaId) ? $temaId : (string) config('tema_modulleri.default_tema', 'tema-1');
    }

    public function temaVarMi(string $temaId): bool
    {
        return array_key_exists($temaId, (array) config('tema_modulleri.temalar', []));
    }

    /**
     * Aktif renk paleti (kod + değerler). Hekim özel palet ayarladıysa onu döner.
     *
     * @return array{kod: string, ad: string, primary: string, accent: string, bg: string, text: string, text_light: string}
     */
    public function aktifPalet(?string $temaId = null): array
    {
        $temaId = $temaId ?: $this->aktifTemaId();
        $tema = (array) config("tema_modulleri.temalar.$temaId", []);
        $paletler = (array) ($tema['renk_paletleri'] ?? []);
        $varsayilan = (string) ($tema['varsayilan_palet'] ?? array_key_first($paletler));

        $secilenKod = (string) $this->settings->option('renk_palet_kod', $varsayilan);

        // Özel palet: JSON string olarak site_options'ta
        if ($secilenKod === 'ozel') {
            $ozel = $this->settings->option('renk_palet_ozel', null);
            if (is_string($ozel) && $ozel !== '') {
                $decoded = json_decode($ozel, true);
                if (is_array($decoded)) {
                    return array_merge(
                        ['kod' => 'ozel', 'ad' => 'Özel'],
                        $this->paletDefaults($paletler[$varsayilan] ?? []),
                        $decoded
                    );
                }
            }
        }

        $palet = $paletler[$secilenKod] ?? $paletler[$varsayilan] ?? [];

        return array_merge(['kod' => $secilenKod], $this->paletDefaults($palet));
    }

    /**
     * @param  array<string, mixed>  $palet
     * @return array<string, mixed>
     */
    protected function paletDefaults(array $palet): array
    {
        return array_merge([
            'ad' => 'Varsayılan',
            'primary' => '#262626',
            'accent' => '#9B9A84',
            'bg' => '#F9F9F9',
            'text' => '#333333',
            'text_light' => '#FFFFFF',
        ], $palet);
    }

    /**
     * Belirtilen tema için henüz DB'de kayıt yoksa default modül setini seed eder.
     * Idempotent — mevcut kayıtlara dokunmaz.
     */
    public function defaultSetiOlustur(string $temaId): int
    {
        if (! $this->temaVarMi($temaId)) {
            return 0;
        }

        $moduller = (array) config("tema_modulleri.temalar.$temaId.moduller", []);
        $eklenen = 0;

        foreach ($moduller as $kod => $tanim) {
            $mevcut = SiteHomepageSection::query()
                ->where('tema_id', $temaId)
                ->where('key', $kod)
                ->first();
            if ($mevcut) {
                continue;
            }

            // Alanların varsayılan değerlerini topla
            $varsayilanAyarlar = [];
            foreach ((array) ($tanim['alanlar'] ?? []) as $alanKod => $alan) {
                $varsayilanAyarlar[$alanKod] = $alan['varsayilan'] ?? null;
            }

            SiteHomepageSection::create([
                'key' => $kod,
                'tema_id' => $temaId,
                'label' => $tanim['ad'] ?? $kod,
                'baslik' => null,
                'alt_metin' => null,
                'ozel_ayarlar' => $varsayilanAyarlar,
                'aktif' => (bool) ($tanim['aktif_varsayilan'] ?? true),
                'sira' => (int) ($tanim['sira'] ?? 999),
            ]);
            $eklenen++;
        }

        $this->settings->forgetCache();

        return $eklenen;
    }

    /**
     * Render için aktif tema modüllerini + ayarlarını birleştirilmiş collection döner.
     * Her öğe: ['kod', 'ad', 'sira', 'blade', 'ayar' => [...merge]]
     */
    public function renderIcinModuller(?string $temaId = null): Collection
    {
        $temaId = $temaId ?: $this->aktifTemaId();
        $moduller = (array) config("tema_modulleri.temalar.$temaId.moduller", []);

        // DB'de kayıt yoksa otomatik seed (ilk kurulum)
        if (SiteHomepageSection::where('tema_id', $temaId)->doesntExist()) {
            $this->defaultSetiOlustur($temaId);
        }

        $dbKayitlari = SiteHomepageSection::aktifModuller($temaId)->keyBy('key');

        $out = collect();
        foreach ($dbKayitlari as $kayit) {
            $kod = $kayit->key;
            $tanim = $moduller[$kod] ?? null;
            if (! $tanim) {
                continue; // Config'ten silinmiş modül
            }

            // Varsayılan + kayıt ayarları merge
            $varsayilan = [];
            foreach ((array) ($tanim['alanlar'] ?? []) as $alanKod => $alan) {
                $varsayilan[$alanKod] = $alan['varsayilan'] ?? null;
            }
            $ayar = array_merge($varsayilan, (array) $kayit->ozel_ayarlar);

            $out->push([
                'kod' => $kod,
                'ad' => $tanim['ad'] ?? $kod,
                'sira' => (int) $kayit->sira,
                'blade' => "frontend.themes.$temaId.modules.$kod",
                'ayar' => $ayar,
            ]);
        }

        return $out->sortBy('sira')->values();
    }

    /**
     * Builder UI için: config tanımı + DB kaydı birleşik liste.
     * Aktif olsun olmasın tema'nın tüm modülleri görünür (kapalıysa toggle kapalı gelir).
     */
    public function builderIcinModuller(?string $temaId = null): Collection
    {
        $temaId = $temaId ?: $this->aktifTemaId();
        $moduller = (array) config("tema_modulleri.temalar.$temaId.moduller", []);

        if (SiteHomepageSection::where('tema_id', $temaId)->doesntExist()) {
            $this->defaultSetiOlustur($temaId);
        }

        $dbKayitlari = SiteHomepageSection::query()
            ->where('tema_id', $temaId)
            ->orderBy('sira')
            ->get()
            ->keyBy('key');

        $out = collect();
        foreach ($moduller as $kod => $tanim) {
            $kayit = $dbKayitlari[$kod] ?? null;
            $out->push([
                'kod' => $kod,
                'tanim' => $tanim,
                'aktif' => $kayit ? (bool) $kayit->aktif : (bool) ($tanim['aktif_varsayilan'] ?? true),
                'sira' => $kayit ? (int) $kayit->sira : (int) ($tanim['sira'] ?? 999),
                'ayar' => (array) ($kayit->ozel_ayarlar ?? []),
                'silinebilir' => (bool) ($tanim['silinebilir'] ?? true),
            ]);
        }

        return $out->sortBy('sira')->values();
    }

    public function modulKaydet(string $temaId, string $kod, array $ayar): SiteHomepageSection
    {
        $tanim = (array) config("tema_modulleri.temalar.$temaId.moduller.$kod", []);
        if ($tanim === []) {
            throw new \InvalidArgumentException("Bilinmeyen modül: $temaId / $kod");
        }

        $kayit = SiteHomepageSection::firstOrNew([
            'tema_id' => $temaId,
            'key' => $kod,
        ]);
        $kayit->label = $tanim['ad'] ?? $kod;
        $kayit->sira = $kayit->sira ?? (int) ($tanim['sira'] ?? 999);
        $kayit->aktif = $kayit->aktif ?? (bool) ($tanim['aktif_varsayilan'] ?? true);
        $kayit->ozel_ayarlar = array_merge((array) $kayit->ozel_ayarlar, $ayar);
        $kayit->save();

        $this->settings->forgetCache();

        return $kayit;
    }

    /**
     * Toplu sıra + aktif güncelleme (drag-drop kaydet).
     *
     * @param  array<int, array{kod: string, aktif: bool, sira: int}>  $liste
     */
    public function siraAktifKaydet(string $temaId, array $liste): void
    {
        foreach ($liste as $item) {
            $kod = (string) ($item['kod'] ?? '');
            if ($kod === '') {
                continue;
            }
            SiteHomepageSection::query()
                ->where('tema_id', $temaId)
                ->where('key', $kod)
                ->update([
                    'aktif' => (bool) ($item['aktif'] ?? false),
                    'sira' => (int) ($item['sira'] ?? 999),
                ]);
        }
        $this->settings->forgetCache();
    }

    public function temaSec(string $temaId): void
    {
        if (! $this->temaVarMi($temaId)) {
            throw new \InvalidArgumentException("Tanımsız tema: $temaId");
        }
        $this->settings->setOption('tema_id', $temaId);
        $this->defaultSetiOlustur($temaId);
    }

    public function paletSec(string $paletKod, ?array $ozelDegerler = null): void
    {
        $temaId = $this->aktifTemaId();
        $paletler = (array) config("tema_modulleri.temalar.$temaId.renk_paletleri", []);

        if ($paletKod === 'ozel' && is_array($ozelDegerler)) {
            $this->settings->setOption('renk_palet_kod', 'ozel');
            $this->settings->setOption('renk_palet_ozel', json_encode($ozelDegerler, JSON_UNESCAPED_UNICODE));
            $this->senkronVurguRengi($ozelDegerler['accent'] ?? null);
            return;
        }

        if (! isset($paletler[$paletKod])) {
            throw new \InvalidArgumentException("Bilinmeyen palet: $paletKod");
        }
        $this->settings->setOption('renk_palet_kod', $paletKod);
        $this->settings->setOption('renk_palet_ozel', '');
        $this->senkronVurguRengi($paletler[$paletKod]['accent'] ?? null);
    }

    /**
     * Site Ayarları "vurgu rengi" ile palet accent'ini tek kaynağa indir.
     */
    public function vurguRenginiAyarla(string $hex): void
    {
        $hex = strtoupper(trim($hex));
        if (! preg_match('/^#[0-9A-F]{6}$/', $hex)) {
            return;
        }

        $mevcut = $this->aktifPalet();
        $this->paletSec('ozel', [
            'primary' => $mevcut['primary'] ?? '#262626',
            'accent' => $hex,
            'bg' => $mevcut['bg'] ?? '#F9F9F9',
            'text' => $mevcut['text'] ?? '#333333',
            'text_light' => $mevcut['text_light'] ?? '#FFFFFF',
        ]);
    }

    protected function senkronVurguRengi(?string $accent): void
    {
        $accent = is_string($accent) ? trim($accent) : '';
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $accent)) {
            $this->settings->setOption('tema_renk', $accent);
        }
    }
}
