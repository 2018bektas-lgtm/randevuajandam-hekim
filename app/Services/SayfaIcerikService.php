<?php

namespace App\Services;

/**
 * Frontend sayfa banner + kisa icerik ayarlari.
 *
 * Depolama: site_options tablosunda key='sayfa_ayarlari' JSON.
 * Katalog: config/sayfa_icerikleri.php
 *
 * Kullanim (blade):
 *   $sayfa = app(SayfaIcerikService::class)->sayfaAyarlari('hakkimda');
 *   $sayfa['banner_baslik'], $sayfa['banner_alt'], $sayfa['banner_gorsel']
 */
class SayfaIcerikService
{
    protected const OPTION_KEY = 'sayfa_ayarlari';

    public function __construct(protected SiteSettingsService $settings) {}

    /**
     * Butun sayfa ayarlari (kayitli + varsayilanlar birlesik).
     *
     * @return array<string, array<string, mixed>>
     */
    public function tumu(): array
    {
        $kayitli = $this->kayitliVeri();
        $sayfalar = (array) config('sayfa_icerikleri', []);

        $out = [];
        foreach ($sayfalar as $sayfaKod => $tanim) {
            $varsayilan = [];
            foreach ((array) ($tanim['alanlar'] ?? []) as $alanKod => $alan) {
                $varsayilan[$alanKod] = $alan['varsayilan'] ?? null;
            }
            $out[$sayfaKod] = array_merge($varsayilan, (array) ($kayitli[$sayfaKod] ?? []));
        }

        return $out;
    }

    /**
     * Belirli bir sayfa icin ayarlari doner (varsayilanlar + kayitli birlesik).
     *
     * @return array<string, mixed>
     */
    public function sayfaAyarlari(string $sayfaKod): array
    {
        return $this->tumu()[$sayfaKod] ?? [];
    }

    /**
     * Tek bir sayfa icin degerleri kaydet (mevcutlarla merge).
     *
     * @param  array<string, mixed>  $degerler
     */
    public function sayfaKaydet(string $sayfaKod, array $degerler): void
    {
        $tanim = (array) config("sayfa_icerikleri.$sayfaKod", []);
        if ($tanim === []) {
            return;
        }

        $filtreli = [];
        foreach ((array) ($tanim['alanlar'] ?? []) as $alanKod => $alan) {
            if (! array_key_exists($alanKod, $degerler)) {
                continue;
            }
            $val = $degerler[$alanKod];
            $filtreli[$alanKod] = match ($alan['tip'] ?? 'metin') {
                'sayi' => (int) $val,
                'uzun_metin', 'metin', 'resim' => is_string($val) ? trim($val) : $val,
                default => $val,
            };
        }

        $hepsi = $this->kayitliVeri();
        $hepsi[$sayfaKod] = array_merge((array) ($hepsi[$sayfaKod] ?? []), $filtreli);
        $this->settings->setOption(self::OPTION_KEY, json_encode($hepsi, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function kayitliVeri(): array
    {
        $raw = $this->settings->option(self::OPTION_KEY, null);
        if (! is_string($raw) || $raw === '') {
            return [];
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }
}
