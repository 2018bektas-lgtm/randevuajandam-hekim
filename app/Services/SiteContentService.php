<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Doctor site frontend content = ana platform (api/) hekim verisi.
 * API key + secret ile bağlı hekimin profil / hizmet / blog / galeri / SSS / yorumları.
 * Demo config sadece API kapalıyken fallback.
 */
class SiteContentService
{
    public function __construct(protected PlatformApiClient $api) {}

    public function doktor(): array
    {
        if (! $this->api->isConfigured()) {
            return array_merge($this->emptySkeleton(), config('doktor', []), [
                'api_synced' => false,
                'api_error' => 'API anahtarı yapılandırılmamış (.env RANDEVU_API_KEY / SECRET).',
            ]);
        }

        try {
            $data = Cache::remember($this->cacheKey(), 20, function () {
                $profile = $this->api->publicGet('/profile')['data'] ?? [];
                $content = $this->api->publicGet('/site-content')['data'] ?? [];
                $services = $this->api->publicGet('/services')['data'] ?? [];
                $educations = $this->api->publicGet('/educations')['data'] ?? [];

                if (empty($profile) || (empty($profile['id']) && empty($profile['ad_soyad']))) {
                    throw new \RuntimeException('API profil boş döndü.');
                }

                return $this->fromApi(
                    $profile,
                    $content,
                    is_array($services) ? $services : [],
                    is_array($educations) ? $educations : []
                );
            });

            // Local SQLite site ayarları (slider, menü, bölümler...) — API'den bağımsız
            return $this->applyLocalSettings($data);
        } catch (Throwable $e) {
            Log::warning('doktorsitesi API profile failed: '.$e->getMessage());

            // Canlı sitede demo dermatoloji göstermeyelim — hata iskeleti
            return $this->applyLocalSettings(array_merge($this->emptySkeleton(), [
                'api_synced' => false,
                'api_error' => $e->getMessage(),
                'ad_soyad' => 'Hekim',
                'unvan' => '',
                'uzmanlik' => '',
                'kisa_bio' => 'Ana sunucu verisi şu an alınamadı. API ve veritabanı bağlantısını kontrol edin.',
                'bio' => 'Ana sunucu verisi şu an alınamadı.',
                'slogan' => 'Bağlantı bekleniyor',
            ]));
        }
    }

    /**
     * Apply doctor-site local SQLite settings on top of API hekim data.
     * Kaynak: site_options + site_menu_items + site_slider_slides + site_homepage_sections
     */
    protected function applyLocalSettings(array $out): array
    {
        try {
            $settings = app(SiteSettingsService::class)->frontendBundle();
        } catch (Throwable) {
            return $out;
        }

        $genel = $settings['genel'] ?? [];
        $menu = $settings['menu'] ?? [];
        $slider = $settings['slider'] ?? [];
        $anasayfa = $settings['anasayfa'] ?? [];
        $seo = $settings['seo'] ?? [];
        $iletisim = $settings['iletisim'] ?? [];

        $out['site_settings'] = $settings;

        if (! empty($genel['slogan_override'])) {
            $out['slogan'] = $genel['slogan_override'];
        }
        if (! empty($genel['footer_metin'])) {
            $out['footer_metin'] = $genel['footer_metin'];
        }
        if (! empty($genel['tema_renk'])) {
            $out['tema_renk'] = $genel['tema_renk'];
        }
        $temaId = (string) ($genel['tema_id'] ?? config('themes.default', 'klasik'));
        $tema = resolve_site_theme($temaId);
        $out['tema_id'] = $tema['id'];
        $out['tema'] = $tema;
        // Tema seçiliyse ve özel renk kaydı yoksa temanın varsayılan rengi
        if (empty($genel['tema_renk']) && ! empty($tema['renk'])) {
            $out['tema_renk'] = $tema['renk'];
        }
        if (! empty($genel['site_baslik_ek'])) {
            $out['site_baslik_ek'] = $genel['site_baslik_ek'];
        }
        if (! empty($genel['vitrin_badge'])) {
            $out['vitrin_badge'] = $genel['vitrin_badge'];
        }
        $out['logo'] = $genel['logo_url'] ?? null;
        $out['favicon'] = $genel['favicon_url'] ?? null;
        $out['whatsapp_goster'] = (bool) ($genel['whatsapp_goster'] ?? true);
        $out['hekim_girisi_goster'] = (bool) ($genel['hekim_girisi_goster'] ?? true);

        // Menü — aktif + sira
        if (! empty($menu['items']) && is_array($menu['items'])) {
            $out['menu'] = collect($menu['items'])
                ->filter(fn ($i) => ! empty($i['aktif']))
                ->sortBy('sira')
                ->values()
                ->all();
        }

        // Slider: yalnızca panelden eklenen slaytlar (otomatik API üretimi yok)
        if (! empty($slider['slides']) && is_array($slider['slides'])) {
            $out['slider'] = array_values(array_filter(
                $slider['slides'],
                fn ($s) => ! empty($s['baslik']) || ! empty($s['image'])
            ));
        } else {
            $out['slider'] = [];
        }

        // Ana sayfa bölümleri: görünürlük + sıralama + özel başlıklar
        $defaultBolumler = [
            'slider' => true, 'istatistik' => true, 'ozellikler' => true, 'hakkimda' => true,
            'hizmetler' => true, 'surec' => true, 'galeri' => true, 'yorumlar' => true,
            'blog' => true, 'cta' => true,
        ];
        $out['anasayfa_bolumler'] = array_merge($defaultBolumler, $anasayfa['bolumler'] ?? []);
        $out['anasayfa_sira'] = ! empty($anasayfa['sira']) && is_array($anasayfa['sira'])
            ? array_values($anasayfa['sira'])
            : array_keys($defaultBolumler);

        $basliklar = $anasayfa['basliklar'] ?? [];
        if (! empty($basliklar['hakkimda']['baslik'])) {
            $out['slogan'] = $basliklar['hakkimda']['baslik'];
        }
        $out['hizmetler_baslik'] = $basliklar['hizmetler']['baslik'] ?? ($anasayfa['hizmetler_baslik'] ?? '');
        $out['hizmetler_alt'] = $basliklar['hizmetler']['alt'] ?? ($anasayfa['hizmetler_alt'] ?? '');
        $out['cta_baslik'] = $basliklar['cta']['baslik'] ?? ($anasayfa['cta_baslik'] ?? '');
        $out['cta_metin'] = $basliklar['cta']['alt'] ?? ($anasayfa['cta_metin'] ?? '');
        $out['bolum_basliklar'] = $basliklar;

        $out['seo'] = $seo;
        $out['iletisim_sayfa'] = $iletisim;

        return $out;
    }

    public function forgetCache(): void
    {
        Cache::forget('doktorsitesi.profile.v2');
        Cache::forget('doktorsitesi.profile.v3');
        Cache::forget('doktorsitesi.profile.v4');
        // Also clear keyed cache if key present
        try {
            $key = (string) config('randevu_api.api_key', '');
            if ($key !== '') {
                Cache::forget('doktorsitesi.profile.v4.'.md5($key));
                Cache::forget('doktorsitesi.profile.v5.'.md5($key));
            }
        } catch (Throwable) {
            // ignore
        }
    }

    protected function cacheKey(): string
    {
        $key = (string) config('randevu_api.api_key', 'none');

        return 'doktorsitesi.profile.v6.'.md5($key);
    }

    protected function emptySkeleton(): array
    {
        return [
            'unvan' => '',
            'ad_soyad' => '',
            'uzmanlik' => '',
            'branslar' => [],
            'klinik_adi' => '',
            'slogan' => '',
            'kisa_bio' => '',
            'bio' => '',
            'bio_html' => '',
            'bio_uzun' => [],
            'mezuniyet' => [],
            'telefon' => '',
            'telefon_raw' => '',
            'whatsapp' => '',
            'e_posta' => '',
            'adres' => '',
            'il' => '',
            'ilce' => '',
            'profil_resmi' => null,
            'maps_embed' => '',
            'calisma_saatleri' => [],
            'sosyal' => [],
            'istatistikler' => [],
            'slider' => [],
            'hizmetler' => [],
            'bloglar' => [],
            'sss' => [],
            'galeri' => [],
            'yorumlar' => [],
            'oncesi_sonrasi' => [],
            'ozellikler' => [],
            'surec' => [],
            'ekip' => [],
            'egitim' => [], // özgeçmiş timeline (demo)
            'egitimler' => [], // kurs/webinar ürünleri
            'online_gorusme' => false,
            'api_synced' => false,
        ];
    }

    protected function fromApi(array $profile, array $content, array $services, array $educations = []): array
    {
        $out = $this->emptySkeleton();
        $out['api_synced'] = true;
        $out['id'] = $profile['id'] ?? null;

        $out['ad_soyad'] = (string) ($profile['ad_soyad'] ?? '');
        $out['unvan'] = (string) ($profile['unvan'] ?? '');
        $out['uzmanlik'] = (string) ($profile['uzmanlik_alani'] ?? '');
        $out['telefon'] = (string) ($profile['telefon'] ?? '');
        $out['e_posta'] = (string) ($profile['e_posta'] ?? '');
        $out['adres'] = (string) ($profile['adres'] ?? '');
        $out['klinik_adi'] = (string) ($profile['klinik_adi'] ?? '');
        $out['il'] = (string) ($profile['il'] ?? '');
        $out['ilce'] = (string) ($profile['ilce'] ?? '');
        $out['online_gorusme'] = (bool) ($profile['online_gorusme'] ?? false);
        $out['randevuya_acik_mi'] = (bool) ($profile['randevuya_acik_mi'] ?? true);

        if ($out['klinik_adi'] === '' && $out['ad_soyad'] !== '') {
            $out['klinik_adi'] = trim($out['unvan'].' '.$out['ad_soyad']).' Muayenehanesi';
        }

        // Bio
        if (filled($profile['biyografi'] ?? null)) {
            $bioHtml = (string) $profile['biyografi'];
            $bioText = trim(strip_tags($bioHtml));
            $out['bio'] = $bioText;
            $out['bio_html'] = $bioHtml;
            $out['kisa_bio'] = Str::limit($bioText, 220);
            $parts = preg_split('/\n\s*\n/', $bioText) ?: [$bioText];
            $out['bio_uzun'] = array_values(array_filter(array_map('trim', $parts)));
        }

        // Phone / WhatsApp
        if ($out['telefon'] !== '') {
            $raw = preg_replace('/\D+/', '', $out['telefon']) ?: '';
            $out['telefon_raw'] = $raw;
            $wa = ltrim($raw, '0');
            if (! str_starts_with($wa, '90') && strlen($wa) === 10) {
                $wa = '90'.$wa;
            }
            $out['whatsapp'] = $wa;
        }

        // Photo
        if (! empty($profile['profil_resmi'])) {
            $out['profil_resmi'] = media_url($profile['profil_resmi']);
        }

        // Mezuniyet
        if (! empty($profile['mezuniyet']) && is_array($profile['mezuniyet'])) {
            $out['mezuniyet'] = array_values(array_filter($profile['mezuniyet'], fn ($x) => filled($x)));
        }

        // Branşlar
        if (! empty($profile['branslar'])) {
            $out['branslar'] = collect($profile['branslar'])->map(function ($b) {
                if (is_string($b)) {
                    return $b;
                }
                $b = (array) $b;

                return $b['ad'] ?? '';
            })->filter()->values()->all();
            if ($out['uzmanlik'] === '' && count($out['branslar'])) {
                $out['uzmanlik'] = implode(', ', $out['branslar']);
            }
        }

        // Çalışma saatleri
        if (! empty($profile['calisma_saatleri']) && is_array($profile['calisma_saatleri'])) {
            $out['calisma_saatleri'] = $profile['calisma_saatleri'];
        }

        // Sosyal
        if (! empty($profile['sosyal']) && is_array($profile['sosyal'])) {
            $out['sosyal'] = array_filter($profile['sosyal'], fn ($v) => filled($v));
        }

        if (isset($profile['ortalama_puan']) && $profile['ortalama_puan'] !== null) {
            $out['ortalama_puan'] = (float) $profile['ortalama_puan'];
        }

        // Map
        if (! empty($profile['enlem']) && ! empty($profile['boylam'])) {
            $out['enlem'] = $profile['enlem'];
            $out['boylam'] = $profile['boylam'];
            $out['maps_embed'] = 'https://maps.google.com/maps?q='.urlencode($profile['enlem'].','.$profile['boylam']).'&z=15&output=embed';
        } elseif ($out['adres'] !== '') {
            $out['maps_embed'] = 'https://maps.google.com/maps?q='.urlencode($out['adres']).'&z=15&output=embed';
        }

        $out['slogan'] = ($out['uzmanlik'] !== '' ? $out['uzmanlik'] : 'Sağlık')
            .' alanında güvenilir, kişiye özel hekimlik';

        // Hizmetler (ana sunucu)
        $fallbackImgs = [
            'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1516549655169-df83a0774514?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?auto=format&fit=crop&w=900&q=80',
        ];
        $out['hizmetler'] = collect($services)->values()->map(function ($h, $i) use ($fallbackImgs) {
            $h = is_array($h) ? $h : (array) $h;
            $img = $h['resim'] ?? $h['image'] ?? null;
            $baslik = (string) ($h['ad'] ?? $h['baslik'] ?? 'Hizmet');
            $slug = $h['slug'] ?? null;
            if (! filled($slug)) {
                $slug = Str::slug($baslik) ?: ('hizmet-'.($h['id'] ?? $i));
            }

            return [
                'id' => $h['id'] ?? null,
                'baslik' => $baslik,
                'kisa' => Str::limit(strip_tags((string) ($h['aciklama'] ?? '')), 120),
                'aciklama' => $h['aciklama'] ?? '',
                'sure' => isset($h['sure']) ? ((int) $h['sure']).' dk' : null,
                'fiyat' => isset($h['fiyat']) && $h['fiyat'] !== null && $h['fiyat'] !== ''
                    ? number_format((float) $h['fiyat'], 0, ',', '.').' ₺'
                    : null,
                'slug' => $slug,
                'image' => $img ? media_url($img) : $fallbackImgs[$i % count($fallbackImgs)],
                'madde' => $this->extractBullets((string) ($h['aciklama'] ?? '')),
            ];
        })->values()->all();

        // Blog
        if (! empty($content['bloglar']) && is_array($content['bloglar'])) {
            $out['bloglar'] = collect($content['bloglar'])->map(function ($b) {
                $b = is_array($b) ? $b : (array) $b;
                $icerik = $b['icerik'] ?? '';
                $plain = strip_tags((string) $icerik);

                return [
                    'slug' => $b['slug'] ?? ('yazi-'.($b['id'] ?? uniqid())),
                    'baslik' => $b['baslik'] ?? '',
                    'ozet' => $b['ozet'] ?? Str::limit($plain, 160),
                    'tarih' => $b['tarih'] ?? '',
                    'okuma' => max(3, (int) ceil(max(1, str_word_count($plain)) / 180)).' dk',
                    'kategori' => 'Blog',
                    'image' => ! empty($b['resim'])
                        ? media_url($b['resim'])
                        : 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=1000&q=80',
                    'icerik' => array_values(array_filter(array_map('trim', preg_split('/\n\s*\n/', $plain) ?: [$plain]))),
                    'icerik_html' => $icerik,
                ];
            })->values()->all();
        }

        // Eğitimler (kurs / webinar) — site-content veya /educations
        $eduSrc = ! empty($content['egitimler']) && is_array($content['egitimler'])
            ? $content['egitimler']
            : $educations;
        if (! empty($eduSrc) && is_array($eduSrc)) {
            $out['egitimler'] = collect($eduSrc)->map(function ($e) {
                $e = is_array($e) ? $e : (array) $e;
                $kapak = $e['kapak'] ?? $e['image'] ?? null;

                return [
                    'id' => $e['id'] ?? null,
                    'slug' => $e['slug'] ?? ('egitim-'.($e['id'] ?? uniqid())),
                    'baslik' => $e['baslik'] ?? '',
                    'ozet' => $e['ozet'] ?? '',
                    'icerik' => $e['icerik'] ?? '',
                    'tip' => $e['tip'] ?? 'online',
                    'baslangic_label' => $e['baslangic_label'] ?? '',
                    'mekan' => $e['mekan'] ?? '',
                    'fiyat' => $e['fiyat'] ?? null,
                    'fiyat_label' => $e['fiyat_label'] ?? null,
                    'odeme_notu' => $e['odeme_notu'] ?? null,
                    'basvuru_acik' => (bool) ($e['basvuru_acik'] ?? true),
                    'form_alanlari' => $e['form_alanlari'] ?? [],
                    'meta_baslik' => $e['meta_baslik'] ?? null,
                    'meta_aciklama' => $e['meta_aciklama'] ?? null,
                    'meta_anahtar_kelimeler' => $e['meta_anahtar_kelimeler'] ?? null,
                    'image' => $kapak ? media_url($kapak) : 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80',
                ];
            })->filter(fn ($e) => $e['baslik'] !== '')->values()->all();
        }

        // FAQ
        if (! empty($content['faqs']) && is_array($content['faqs'])) {
            $out['sss'] = collect($content['faqs'])->map(fn ($f) => [
                'soru' => is_array($f) ? ($f['soru'] ?? '') : ($f->soru ?? ''),
                'cevap' => is_array($f) ? ($f['cevap'] ?? '') : ($f->cevap ?? ''),
            ])->filter(fn ($f) => $f['soru'] !== '')->values()->all();
        }

        // Galeri
        if (! empty($content['galeri']) && is_array($content['galeri'])) {
            $out['galeri'] = collect($content['galeri'])->map(fn ($g) => [
                'baslik' => is_array($g) ? ($g['baslik'] ?? 'Galeri') : 'Galeri',
                'etiket' => 'Klinik',
                'image' => is_array($g) ? media_url($g['image'] ?? null) : null,
            ])->filter(fn ($g) => ! empty($g['image']))->values()->all();
        }

        // Yorumlar
        if (! empty($content['yorumlar']) && is_array($content['yorumlar'])) {
            $out['yorumlar'] = collect($content['yorumlar'])->map(fn ($y) => [
                'ad' => is_array($y) ? ($y['ad'] ?? 'Hasta') : 'Hasta',
                'metin' => is_array($y) ? ($y['metin'] ?? '') : '',
                'puan' => is_array($y) ? (int) ($y['puan'] ?? 5) : 5,
                'hizmet' => 'Değerlendirme',
            ])->filter(fn ($y) => $y['metin'] !== '')->values()->all();
        }

        $out['slider'] = $this->buildSlider($out);
        $out['istatistikler'] = $this->buildStats($out);
        $out['surec'] = [
            ['adim' => '01', 'baslik' => 'Online randevu', 'aciklama' => 'Uygun gün ve saati seçerek randevu talebinizi oluşturun.'],
            ['adim' => '02', 'baslik' => 'Onay & bilgilendirme', 'aciklama' => 'Talebiniz incelenir; gerekirse hekim onayından sonra bilgilendirilirsiniz.'],
            ['adim' => '03', 'baslik' => 'Muayene / değerlendirme', 'aciklama' => 'Şikayetiniz ve öykünüz dinlenir, gerekli tetkikler planlanır.'],
            ['adim' => '04', 'baslik' => 'Tedavi & takip', 'aciklama' => 'Kişiye özel plan ve kontrol randevuları ile süreciniz yönetilir.'],
        ];
        $out['ozellikler'] = [
            ['baslik' => 'Ana platform ile senkron', 'aciklama' => 'Bilgiler, randevu ve içerikler Randevu Ajandam hekim panelinden yönetilir.'],
            ['baslik' => 'Kişiye özel plan', 'aciklama' => 'Her danışan için yaş, risk ve şikayete göre özelleştirilmiş değerlendirme.'],
            ['baslik' => 'Kolay randevu', 'aciklama' => 'Online randevu ile size uygun saatte planlama.'],
        ];

        return $out;
    }

    protected function extractBullets(string $text): array
    {
        $text = strip_tags($text);
        if ($text === '') {
            return [];
        }
        $parts = preg_split('/[\n•\-\;]+/', $text) ?: [];
        $parts = array_values(array_filter(array_map('trim', $parts), fn ($p) => mb_strlen($p) > 12));

        return array_slice($parts, 0, 4);
    }

    protected function buildSlider(array $out): array
    {
        $name = trim(($out['unvan'] ?? '').' '.($out['ad_soyad'] ?? 'Hekim'));
        $uz = $out['uzmanlik'] ?? 'Hekimlik';
        $il = trim(($out['ilce'] ?? '').(! empty($out['il']) ? ' / '.$out['il'] : ''), ' /');
        $photo = $out['profil_resmi']
            ?? 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=800&q=80';
        $clinicImg = $out['galeri'][0]['image']
            ?? 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=2000&q=85';
        $svcImg = $out['hizmetler'][0]['image']
            ?? 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=2000&q=85';

        $slides = [[
            'no' => '01',
            'baslik' => $name,
            'alt' => ($out['kisa_bio'] ?: $uz).($il !== '' ? ' · '.$il : ''),
            'cta' => 'Online Randevu',
            'cta_url' => '/randevu',
            'cta2' => 'Hakkımda',
            'cta2_url' => '/hakkimda',
            'image' => $clinicImg,
            'thumb' => $photo,
            'badge' => $il !== '' ? $il : 'Muayenehane',
            'etiket' => 'Hekim',
            'meta' => array_values(array_filter([$uz, $out['telefon'] ?: null, $out['e_posta'] ?: null])),
        ]];

        if (! empty($out['hizmetler'][0])) {
            $first = $out['hizmetler'][0];
            $slides[] = [
                'no' => '02',
                'baslik' => $first['baslik'],
                'alt' => $first['kisa'] ?: 'Uzman değerlendirme ve kişiye özel planlama.',
                'cta' => 'Hizmetleri Gör',
                'cta_url' => '/hizmetler',
                'cta2' => 'Randevu Al',
                'cta2_url' => '/randevu',
                'image' => $svcImg,
                'thumb' => $first['image'] ?? $photo,
                'badge' => $first['sure'] ?? 'Hizmet',
                'etiket' => 'Hizmetler',
                'meta' => array_values(array_filter([
                    $first['sure'] ?? null,
                    $first['fiyat'] ?? null,
                    count($out['hizmetler']).' aktif hizmet',
                ])),
            ];
        }

        $slides[] = [
            'no' => str_pad((string) (count($slides) + 1), 2, '0', STR_PAD_LEFT),
            'baslik' => 'Online randevu ile kolay planlama',
            'alt' => 'Ana platform randevu sistemi ile entegre; talebiniz hekim paneline anında düşer.',
            'cta' => 'Randevu Oluştur',
            'cta_url' => '/randevu',
            'cta2' => 'İletişim',
            'cta2_url' => '/iletisim',
            'image' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=2000&q=85',
            'thumb' => $photo,
            'badge' => 'Entegrasyon',
            'etiket' => 'Randevu',
            'meta' => array_values(array_filter(['Hızlı talep', $out['telefon'] ?: null])),
        ];

        return $slides;
    }

    protected function buildStats(array $out): array
    {
        $hizmetSay = count($out['hizmetler'] ?? []);
        $blogSay = count($out['bloglar'] ?? []);
        $yorumSay = count($out['yorumlar'] ?? []);
        $puan = $out['ortalama_puan'] ?? null;

        $yil = 0;
        if (preg_match('/(\d+)\s*yıl/ui', (string) ($out['bio'] ?? ''), $m)) {
            $yil = (int) $m[1];
        }

        return array_values(array_filter([
            $yil > 0 ? ['deger' => $yil, 'suffix' => '+', 'etiket' => 'Yıllık Deneyim', 'aciklama' => 'Klinik pratik'] : null,
            ['deger' => max($hizmetSay, 0), 'suffix' => '', 'etiket' => 'Aktif Hizmet', 'aciklama' => 'Ana platform'],
            ['deger' => max($blogSay, 0), 'suffix' => '', 'etiket' => 'Blog Yazısı', 'aciklama' => 'Bilgilendirici içerik'],
            [
                'deger' => $puan !== null ? round((float) $puan, 1) : max($yorumSay, 0),
                'suffix' => '',
                'etiket' => $puan !== null ? 'Ortalama Puan' : 'Onaylı Yorum',
                'aciklama' => 'Danışan geri bildirimi',
            ],
        ]));
    }
}
