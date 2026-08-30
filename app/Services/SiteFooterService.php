<?php

namespace App\Services;

/**
 * Tema bazlı footer tasarımı + footer ayarları.
 *
 * Katalog: config/footer_tasarimlari.php
 * Ayarlar: site_options (footer_*), tasarım seçimi footer_tasarim_{grup}
 *
 * Frontend tarafında ayarlar $doktor['footer_ayarlari'] üzerinden gelir
 * (SiteSettingsService::frontendBundle önbelleği); yoksa DB'den okunur.
 */
class SiteFooterService
{
    public function __construct(protected SiteSettingsService $settings) {}

    /* ------------------------------------------------------------------
     | Katalog
     |------------------------------------------------------------------ */

    /** Aktif tema paketinin footer grubu (hipno | delogis) */
    public function grup(?string $themeId = null): string
    {
        $pack = theme_pack_id($themeId);
        $map = (array) config('footer_tasarimlari.gruplar', []);

        return (string) ($map[$pack] ?? $map[$themeId ?? ''] ?? 'hipno');
    }

    /** @return array<string, array> Gruba ait tasarımlar */
    public function tasarimlar(?string $themeId = null): array
    {
        $grup = $this->grup($themeId);

        return (array) config('footer_tasarimlari.tasarimlar.'.$grup, []);
    }

    public function varsayilanTasarim(?string $themeId = null): string
    {
        $grup = $this->grup($themeId);
        $vars = (string) config('footer_tasarimlari.varsayilan.'.$grup, '');
        $tasarimlar = $this->tasarimlar($themeId);

        if ($vars !== '' && isset($tasarimlar[$vars])) {
            return $vars;
        }

        return (string) (array_key_first($tasarimlar) ?: 'zarif');
    }

    /** Seçim anahtarı — tema grubu bazında saklanır */
    public function secimAnahtari(?string $themeId = null): string
    {
        return 'footer_tasarim_'.$this->grup($themeId);
    }

    /**
     * Aktif tasarım kodu. $ayarlar verilirse DB'ye gidilmez.
     *
     * @param  array<string, mixed>|null  $ayarlar
     */
    public function aktifTasarim(?string $themeId = null, ?array $ayarlar = null): string
    {
        $tasarimlar = $this->tasarimlar($themeId);
        $kod = is_array($ayarlar)
            ? (string) ($ayarlar['tasarim'] ?? '')
            : (string) $this->settings->option($this->secimAnahtari($themeId), '');

        return isset($tasarimlar[$kod]) ? $kod : $this->varsayilanTasarim($themeId);
    }

    /** @return array{kod: string, ad: string, view: string, ton: string, destek: array} */
    public function aktifTasarimMeta(?string $themeId = null, ?array $ayarlar = null): array
    {
        $kod = $this->aktifTasarim($themeId, $ayarlar);
        $meta = (array) ($this->tasarimlar($themeId)[$kod] ?? []);

        return array_merge([
            'ad' => $kod,
            'view' => 'ortak.zarif',
            'ton' => 'acik',
            'destek' => [],
            'onizleme' => [],
        ], $meta, ['kod' => $kod]);
    }

    /** Blade yolu: frontend.partials.footer.{view} */
    public function viewName(?string $themeId = null, ?array $ayarlar = null): string
    {
        $meta = $this->aktifTasarimMeta($themeId, $ayarlar);
        $name = 'frontend.partials.footer.'.$meta['view'];

        return view()->exists($name) ? $name : 'frontend.partials.footer.ortak.zarif';
    }

    /* ------------------------------------------------------------------
     | Ayarlar
     |------------------------------------------------------------------ */

    /** @return array<string, array> Ayar tanımları */
    public function ayarTanimlari(): array
    {
        return (array) config('footer_tasarimlari.ayarlar', []);
    }

    /**
     * DB'den okunmuş ayar seti (bundle'a konur, panelde de kullanılır).
     *
     * @return array<string, mixed>
     */
    public function ayarlar(?string $themeId = null): array
    {
        $out = [];
        foreach ($this->ayarTanimlari() as $key => $tanim) {
            $vars = $tanim['varsayilan'] ?? '';
            $out[$key] = match ($tanim['tip'] ?? 'metin') {
                'bool' => $this->settings->boolOption($key, (bool) $vars),
                'sayi' => (int) ($this->settings->option($key, $vars) ?: $vars),
                default => (string) ($this->settings->option($key, $vars) ?? $vars),
            };
        }

        $out['tasarim'] = $this->aktifTasarim($themeId, null);
        $out['grup'] = $this->grup($themeId);
        $out['footer_logo_url'] = $this->settings->mediaUrl((string) ($out['footer_logo'] ?? '')) ?: '';

        return $out;
    }

    /**
     * Frontend'de $doktor içinden gelen ayarlar; yoksa DB.
     *
     * @param  array<string, mixed>|null  $doktor
     * @return array<string, mixed>
     */
    public function ayarlariCoz(?array $doktor = null): array
    {
        $bundled = $doktor['footer_ayarlari'] ?? null;
        if (is_array($bundled) && $bundled !== []) {
            $tanim = $this->ayarTanimlari();
            $out = [];
            foreach ($tanim as $key => $t) {
                $vars = $t['varsayilan'] ?? '';
                $val = $bundled[$key] ?? $vars;
                $out[$key] = match ($t['tip'] ?? 'metin') {
                    'bool' => (bool) $val,
                    'sayi' => (int) ($val ?: $vars),
                    default => (string) $val,
                };
            }
            $out['tasarim'] = (string) ($bundled['tasarim'] ?? '');
            $out['grup'] = (string) ($bundled['grup'] ?? '');
            $out['footer_logo_url'] = (string) ($bundled['footer_logo_url'] ?? '');

            return $out;
        }

        return $this->ayarlar($doktor ? current_theme_id($doktor) : null);
    }

    /* ------------------------------------------------------------------
     | Görünüm verisi
     |------------------------------------------------------------------ */

    /**
     * Tüm footer tasarımlarının ortak veri sözleşmesi.
     *
     * @param  array<string, mixed>  $doktor
     * @return array<string, mixed>
     */
    public function verisi(array $doktor): array
    {
        $ayar = $this->ayarlariCoz($doktor);
        $themeId = current_theme_id($doktor);
        $meta = $this->aktifTasarimMeta($themeId, $ayar);
        $destek = (array) ($meta['destek'] ?? []);

        $unvan = trim(decode_text((string) ($doktor['unvan'] ?? '')));
        $adSoyad = trim(decode_text((string) ($doktor['ad_soyad'] ?? 'Hekim')));
        $tamAd = trim($unvan.' '.$adSoyad);

        // --- Telefon ---
        $telefon = trim((string) ($doktor['telefon'] ?? ''));
        $telefonRaw = (string) ($doktor['telefon_raw'] ?? preg_replace('/[^0-9+]/', '', $telefon));
        $telDigits = (string) preg_replace('/\D+/', '', $telefonRaw);
        $telefonGecerli = $telefon !== ''
            && strlen($telDigits) >= 10
            && ! preg_match('/^0*5320{5,}$/', $telDigits)
            && ! preg_match('/^0+$/', $telDigits);

        // --- Diğer iletişim ---
        $eposta = trim((string) ($doktor['e_posta'] ?? ''));
        $adres = trim(decode_text((string) ($doktor['adres'] ?? '')));
        if ($adres === '') {
            $adres = trim(decode_text(trim((string) ($doktor['ilce'] ?? '')).' '.trim((string) ($doktor['il'] ?? ''))));
        } elseif (! empty($doktor['il'])) {
            $il = trim(decode_text((string) $doktor['il']));
            if ($il !== '' && ! str_contains(mb_strtolower($adres), mb_strtolower($il))) {
                $adres = $adres.', '.$il;
            }
        }

        // --- Logo ---
        $logoTip = (string) ($ayar['footer_logo_tip'] ?? 'site');
        $logoUrl = match ($logoTip) {
            'ozel' => (string) ($ayar['footer_logo_url'] ?? ''),
            'site' => (string) ($doktor['logo'] ?? ''),
            default => '',
        };
        if ($logoTip === 'site' && $logoUrl === '') {
            $logoTip = 'yazi';
        }
        if ($logoTip === 'ozel' && $logoUrl === '') {
            $logoTip = (string) ($doktor['logo'] ?? '') !== '' ? 'site' : 'yazi';
            $logoUrl = $logoTip === 'site' ? (string) $doktor['logo'] : '';
        }

        // --- Metinler ---
        $aciklama = trim((string) ($ayar['footer_aciklama'] ?? ''));
        if ($aciklama === '') {
            $aciklama = plain_text(
                $doktor['footer_metin'] ?? $doktor['kisa_bio'] ?? $doktor['slogan'] ?? '',
                180
            );
        }
        $telif = trim((string) ($ayar['footer_telif'] ?? ''));
        if ($telif === '') {
            $telif = '© '.date('Y').' '.$tamAd.' · Tüm hakları saklıdır.';
        } else {
            $telif = strtr($telif, ['{yil}' => date('Y'), '{ad}' => $tamAd]);
        }

        // --- Sosyal ---
        $sosyal = [];
        if ($this->destekli($destek, 'sosyal') && ($ayar['footer_sosyal_goster'] ?? true)) {
            foreach ((array) ($doktor['sosyal'] ?? []) as $platform => $url) {
                $url = trim((string) $url);
                $ikon = $this->sosyalIkon((string) $platform);
                if ($url === '' || $ikon === null) {
                    continue;
                }
                $sosyal[] = ['platform' => (string) $platform, 'url' => $url, 'ikon' => $ikon, 'ad' => ucfirst((string) $platform)];
            }
        }

        // --- WhatsApp ---
        $wpNum = (string) preg_replace('/\D+/', '', (string) ($doktor['whatsapp'] ?? ($telefonGecerli ? $telefonRaw : '')));
        if (strlen($wpNum) < 10) {
            $wpNum = '';
        }

        // --- Linkler ---
        $nav = ($this->destekli($destek, 'kesfet') && ($ayar['footer_kesfet_goster'] ?? true))
            ? (function_exists('site_footer_nav') ? site_footer_nav($doktor) : [])
            : [];
        $sayfalar = ($ayar['footer_sayfalar_goster'] ?? true) ? site_footer_pages() : [];

        return [
            'ayar' => $ayar,
            'meta' => $meta,
            'tasarim' => $meta['kod'],
            'ton' => $meta['ton'] ?? 'acik',

            'unvan' => $unvan,
            'ad_soyad' => $adSoyad,
            'tam_ad' => $tamAd,
            'uzmanlik' => trim(decode_text((string) ($doktor['uzmanlik'] ?? ''))),
            'slogan' => trim(decode_text((string) ($doktor['slogan'] ?? 'Hazır olduğunuzda buradayız'))),

            'logo_tip' => $logoTip,
            'logo_url' => $logoUrl,
            'logo_yukseklik' => max(20, min(140, (int) ($ayar['footer_logo_yukseklik'] ?? 52))),

            'aciklama' => $aciklama,
            'telif' => $telif,
            'baslik_kesfet' => (string) ($ayar['footer_baslik_kesfet'] ?: 'Keşfet'),
            'baslik_iletisim' => (string) ($ayar['footer_baslik_iletisim'] ?: 'İletişim'),
            'baslik_sosyal' => (string) ($ayar['footer_baslik_sosyal'] ?: 'Bizi takip edin'),
            'cta_baslik' => (string) ($ayar['footer_cta_baslik'] ?: 'Randevu almaya hazır mısınız?'),

            'telefon' => $telefon,
            'telefon_raw' => $telefonRaw,
            'telefon_gecerli' => $telefonGecerli,
            'eposta' => $eposta,
            'adres' => $adres,
            'saatler' => ($this->destekli($destek, 'saatler') && ($ayar['footer_saatler_goster'] ?? true))
                ? $this->calismaSaatleri($doktor)
                : '',

            'sosyal' => $sosyal,
            'whatsapp' => $wpNum,
            'nav' => $nav,
            'sayfalar' => $sayfalar,

            // Blok görünürlükleri — hem tasarım desteği hem panel ayarı
            'goster' => [
                'cta' => $this->destekli($destek, 'cta') && (bool) ($ayar['footer_cta_goster'] ?? true),
                'hakkinda' => $this->destekli($destek, 'hakkinda') && (bool) ($ayar['footer_hakkinda_goster'] ?? true) && $aciklama !== '',
                'kesfet' => $nav !== [],
                'iletisim' => $this->destekli($destek, 'iletisim') && (bool) ($ayar['footer_iletisim_goster'] ?? true),
                'sosyal' => $sosyal !== [],
                'randevu' => $this->destekli($destek, 'randevu') && (bool) ($ayar['footer_randevu_goster'] ?? true),
                'sayfalar' => $sayfalar !== [],
                'marka' => (bool) ($ayar['footer_marka_goster'] ?? true),
            ],
        ];
    }

    /** @param array<int, string> $destek */
    protected function destekli(array $destek, string $blok): bool
    {
        return $destek === [] || in_array($blok, $destek, true);
    }

    protected function sosyalIkon(string $platform): ?string
    {
        return match (mb_strtolower($platform)) {
            'instagram' => 'fab fa-instagram',
            'facebook' => 'fab fa-facebook-f',
            'twitter', 'x' => 'fab fa-twitter',
            'youtube' => 'fab fa-youtube',
            'linkedin' => 'fab fa-linkedin-in',
            'tiktok' => 'fab fa-tiktok',
            'pinterest' => 'fab fa-pinterest-p',
            'whatsapp' => 'fab fa-whatsapp',
            'telegram' => 'fab fa-telegram',
            default => null,
        };
    }

    /**
     * Çalışma saatleri özeti — API formatlarını tek satıra indirger.
     *
     * @param  array<string, mixed>  $doktor
     */
    public function calismaSaatleri(array $doktor): string
    {
        $ozet = trim(decode_text((string) ($doktor['calisma_saatleri_ozet'] ?? '')));
        if ($ozet !== '') {
            return $ozet;
        }

        $cs = is_array($doktor['calisma_saatleri'] ?? null) ? $doktor['calisma_saatleri'] : [];
        if ($cs === []) {
            return '';
        }

        $acik = [];
        foreach ($cs as $gun => $saat) {
            if (is_array($saat)) {
                if (! (bool) ($saat['aktif_mi'] ?? $saat['aktif'] ?? true)) {
                    continue;
                }
                $bas = substr((string) ($saat['mesai_baslangic'] ?? $saat['baslangic'] ?? ''), 0, 5);
                $bit = substr((string) ($saat['mesai_bitis'] ?? $saat['bitis'] ?? ''), 0, 5);
                $saat = ($bas !== '' && $bit !== '') ? ($bas.' – '.$bit) : '';
            }
            $saat = trim(decode_text((string) $saat));
            if ($saat === '' || mb_strtolower($saat) === 'kapalı' || $saat === '-') {
                continue;
            }
            $acik[decode_text((string) $gun)] = $saat;
        }

        if ($acik === []) {
            return '';
        }

        $uniq = array_values(array_unique(array_values($acik)));
        $gunler = array_keys($acik);
        if (count($uniq) === 1 && count($gunler) >= 2) {
            return $gunler[0].' – '.$gunler[count($gunler) - 1].' '.$uniq[0];
        }

        $parts = [];
        foreach ($acik as $g => $s) {
            $parts[] = $g.': '.$s;
            if (count($parts) >= 3) {
                break;
            }
        }

        return implode(' · ', $parts);
    }
}
