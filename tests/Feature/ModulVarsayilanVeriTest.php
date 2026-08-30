<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Anasayfa modülleri: uydurma veri VARSAYILAN olarak da gelmemeli.
 *
 * ── Neden bu test ayrıca gerekliydi ──────────────────────────────────────
 * ModulDinamikVeriTest modül blade'ini doğrudan `$ayar = []` ile çiziyordu
 * ve yeşil geçiyordu. Fakat üretimde `$ayar` boş gelmiyor:
 * SiteBuilderService, config/tema_modulleri.php içindeki `varsayilan`
 * değerlerini her render'da kaydedilmiş ayarların üstüne merge ediyor
 * (SiteBuilderService::moduller() → array_merge($varsayilan, $ozel)).
 *
 * Sonuç: $ayar['calisma_saatleri'] asla boş kalmıyordu; blade'deki
 *   $ayar['calisma_saatleri'] ?? $doktor['calisma_saatleri_ozet']
 * zinciri hiç devreye girmiyordu (?? yalnızca null'da çalışır) ve
 * dilhanbitaraf.com canlıda hâlâ sabit "Pzt - Cmt 09:00 - 21:00"
 * gösteriyordu. Test blade'i doğruluyordu, boru hattını değil.
 *
 * Bu test ikisini birden kapatır:
 *  1) config'te uydurma veri varsayılanı kalmadığını,
 *  2) varsayılanlar MERGE EDİLDİKTEN sonra bile modülün uydurma veri
 *     basmadığını.
 *
 * Not: başlık/buton varsayılanları ("Randevu", "Randevu Al") uydurma veri
 * sayılmaz; onlar panelden değiştirilebilen arayüz metinleridir. Uydurma
 * veri = hekim hakkında doğrulanamaz OLGUSAL iddia (çalışma saati, danışan
 * sayısı, memnuniyet oranı, sunulmayan hizmet).
 */
class ModulVarsayilanVeriTest extends TestCase
{
    use RefreshDatabase;

    /** Hekim hakkında olgusal iddia içeren, panelden gelmeyen metinler. */
    private const UYDURMA = [
        'Pzt - Cmt 09:00 - 21:00',
        'mutlu danışan',
        'Mutlu Danışan',
        'Mutlu danışan',
        'memnuniyet',
        'yıllık deneyim',
        'tedavi programı',
        'Çift Terapisi',
        'Aile Terapisi',
        'Bireysel, çift ve aile danışmanlığı',
    ];

    /**
     * config/tema_modulleri.php içindeki tüm alan varsayılanlarını düz bir
     * listeye çıkarır.
     *
     * @return array<int, array{tema: string, modul: string, alan: string, deger: string}>
     */
    private function tumVarsayilanlar(): array
    {
        $out = [];

        foreach ((array) config('tema_modulleri.temalar', []) as $temaId => $tema) {
            foreach ((array) ($tema['moduller'] ?? []) as $modulKod => $modul) {
                foreach ((array) ($modul['alanlar'] ?? []) as $alanKod => $alan) {
                    $deger = $alan['varsayilan'] ?? null;
                    if (! is_scalar($deger)) {
                        continue;
                    }
                    $out[] = [
                        'tema' => (string) $temaId,
                        'modul' => (string) $modulKod,
                        'alan' => (string) $alanKod,
                        'deger' => (string) $deger,
                    ];
                }
            }
        }

        return $out;
    }

    public function test_config_varsayilanlari_uydurma_veri_icermez(): void
    {
        $varsayilanlar = $this->tumVarsayilanlar();
        $this->assertNotEmpty($varsayilanlar, 'Config okunamadi.');

        $kirli = [];
        foreach ($varsayilanlar as $v) {
            foreach (self::UYDURMA as $metin) {
                if ($v['deger'] !== '' && str_contains($v['deger'], $metin)) {
                    $kirli[] = sprintf(
                        '%s > %s > %s = "%s"',
                        $v['tema'], $v['modul'], $v['alan'], $v['deger']
                    );
                }
            }
        }

        $this->assertSame([], $kirli, sprintf(
            "Uydurma veri hala varsayilan olarak geliyor:\n  - %s",
            implode("\n  - ", $kirli)
        ));
    }

    /**
     * Sosyal kanıt varsayılan olarak açık gelmemeli: açık gelirse hekimin
     * hiç vermediği bir danışan sayısı sitede yayınlanır.
     */
    public function test_sosyal_kanit_varsayilan_kapali(): void
    {
        $bakildi = false;

        foreach ($this->tumVarsayilanlar() as $v) {
            if ($v['alan'] !== 'sosyal_kanit_goster') {
                continue;
            }
            $bakildi = true;
            $this->assertSame(
                '0',
                $v['deger'],
                "{$v['tema']} > {$v['modul']}: sosyal kanit varsayilan acik."
            );
        }

        $this->assertTrue($bakildi, 'sosyal_kanit_goster alani bulunamadi.');
    }

    // ------------------------------------------------------------------
    // Asıl koruma: varsayılanlar merge edildikten sonra render
    // ------------------------------------------------------------------

    /**
     * SiteBuilderService::moduller() ile aynı merge'ü yapar: hekim hiçbir
     * şey kaydetmemişken $ayar ne oluyorsa o.
     *
     * @return array<string, mixed>
     */
    private function ayarlariUret(string $temaId, string $modulKod): array
    {
        $alanlar = (array) config(
            "tema_modulleri.temalar.{$temaId}.moduller.{$modulKod}.alanlar", []
        );

        $ayar = [];
        foreach ($alanlar as $alanKod => $alan) {
            $ayar[$alanKod] = $alan['varsayilan'] ?? null;
        }

        return array_merge($ayar, []);
    }

    /**
     * @param  array<string, mixed>  $ek
     * @return array<string, mixed>
     */
    private function doktor(array $ek = []): array
    {
        return array_merge([
            'id' => 1,
            'ad_soyad' => 'Ayse Yilmaz',
            'unvan' => 'Uzm. Psk.',
            'telefon' => '0 (532) 111 22 33',
            'telefon_raw' => '05321112233',
            'hizmetler' => [],
            'bloglar' => [],
            'galeri' => [],
            'slider' => [],
            'istatistikler' => [],
            'yorumlar' => [],
            'sss' => [],
            'ozellikler' => [],
        ], $ek);
    }

    /**
     * Veri sağlayıcılar uygulama önyüklenmeden çalışır; `config()` burada
     * yoktur. Bu yüzden config dosyası doğrudan okunur.
     *
     * @return array<string, mixed>
     */
    private static function temalarHam(): array
    {
        static $temalar = null;

        if ($temalar === null) {
            $config = require dirname(__DIR__, 2).'/config/tema_modulleri.php';
            $temalar = (array) ($config['temalar'] ?? []);
        }

        return $temalar;
    }

    /**
     * Config'te tanımlı her tema/modül çifti.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function modulSaglayici(): array
    {
        $out = [];

        foreach (self::temalarHam() as $temaId => $tema) {
            foreach (array_keys((array) ($tema['moduller'] ?? [])) as $modulKod) {
                $out["{$temaId}/{$modulKod}"] = [(string) $temaId, (string) $modulKod];
            }
        }

        return $out;
    }

    /**
     * SiteBuilderService::modulBlade() ile aynı çözüm: önce layout paketi
     * (tema-4…9 → delogis), yoksa tema-id klasörü. Üretim metodu protected
     * olduğu için burada birebir kopyalanıyor.
     */
    private function modulBlade(string $temaId, string $kod): string
    {
        $pack = function_exists('theme_pack_id') ? theme_pack_id($temaId) : $temaId;

        foreach (array_unique([
            "frontend.themes.{$pack}.modules.{$kod}",
            "frontend.themes.{$temaId}.modules.{$kod}",
        ]) as $ad) {
            if (view()->exists($ad)) {
                return $ad;
            }
        }

        return "frontend.themes.{$pack}.modules.{$kod}";
    }

    #[DataProvider('modulSaglayici')]
    public function test_varsayilanlarla_render_uydurma_veri_basmaz(string $tema, string $modul): void
    {
        $blade = $this->modulBlade($tema, $modul);

        if (! view()->exists($blade)) {
            $this->markTestSkipped("Blade yok: {$blade}");
        }

        $html = view($blade, [
            'doktor' => array_merge($this->doktor(), ['tema_id' => $tema]),
            'ayar' => $this->ayarlariUret($tema, $modul),
        ])->render();

        foreach (self::UYDURMA as $metin) {
            $this->assertStringNotContainsString(
                $metin,
                $html,
                "{$tema}/{$modul}: varsayilanlarla render'da uydurma veri: {$metin}"
            );
        }
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function calismaSaatiTemasi(): array
    {
        $out = [];

        foreach (self::temalarHam() as $temaId => $tema) {
            $alanlar = (array) ($tema['moduller']['appointment']['alanlar'] ?? []);
            if (array_key_exists('calisma_saatleri', $alanlar)) {
                $out[(string) $temaId] = [(string) $temaId];
            }
        }

        return $out;
    }

    /**
     * Panel ayarı boş bırakıldığında hekimin gerçek çalışma saatleri
     * gösterilmeli. Eskiden `??` zinciri boş metinde devreye girmediği için
     * bu çalışmıyordu.
     */
    #[DataProvider('calismaSaatiTemasi')]
    public function test_bos_ayarda_gercek_calisma_saati_gosterilir(string $tema): void
    {
        $ayar = $this->ayarlariUret($tema, 'appointment');

        $this->assertArrayHasKey('calisma_saatleri', $ayar);
        $this->assertSame('', (string) $ayar['calisma_saatleri'], 'Varsayilan bos olmali.');

        $html = view("frontend.themes.{$tema}.modules.appointment", [
            'doktor' => array_merge($this->doktor([
                'calisma_saatleri_ozet' => 'Hafta içi 10:00 – 18:00',
            ]), ['tema_id' => $tema]),
            'ayar' => $ayar,
        ])->render();

        $this->assertStringContainsString(
            'Hafta içi 10:00 – 18:00',
            $html,
            "{$tema}: bos panel ayarinda gercek calisma saati gosterilmiyor."
        );
    }

    /**
     * Uzmanlık listesi boşsa hekimin gerçek hizmetleri kullanılmalı.
     */
    public function test_bos_uzmanlik_listesi_gercek_hizmetlere_duser(): void
    {
        $ayar = $this->ayarlariUret('tema-1', 'hero_static');

        $html = view('frontend.themes.tema-1.modules.hero_static', [
            'doktor' => array_merge($this->doktor([
                'hizmetler' => [
                    ['baslik' => 'Sınav Kaygısı Danışmanlığı'],
                    ['baslik' => 'EMDR Terapisi'],
                ],
            ]), ['tema_id' => 'tema-1']),
            'ayar' => $ayar,
        ])->render();

        $this->assertStringContainsString('Sınav Kaygısı Danışmanlığı', $html);
        $this->assertStringContainsString('EMDR Terapisi', $html);
    }
}
