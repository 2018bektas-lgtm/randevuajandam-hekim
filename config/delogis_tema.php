<?php

/**
 * Delogis (tema-4..9) katalogu.
 *
 * Blade paketi: resources/views/frontend/themes/delogis/
 * Kaynak HTML: html-hekim-tema/tema-2/bracketweb.com/delogis-html/main-html/
 *
 *   tema-4  Home 1          index.html
 *   tema-5  Home 2          index2.html
 *   tema-6  Home 3          index3.html
 *   tema-7  Home 4          index4.html
 *   tema-8  Home 5          index5.html
 *   tema-9  Home Boxed      index-boxed.html  (Home 1 + body.boxed-wrapper)
 */

$delogisPaletler = [
    'terracotta' => [
        'ad' => 'Terracotta (varsayılan)',
        'primary' => '#1a1414',
        'accent' => '#976147',
        'bg' => '#f2edea',
        'text' => '#1a1414',
        'text_light' => '#FFFFFF',
        'black' => '#1a1414',
        'extra' => '#f2edea',
        'gray' => '#736b6b',
        'bdr' => '#ded6d3',
    ],
    'altin' => [
        'ad' => 'Altın & Deniz',
        'primary' => '#293B46',
        'accent' => '#B9905D',
        'bg' => '#F6F2ED',
        'text' => '#293B46',
        'text_light' => '#FFFFFF',
        'black' => '#293B46',
        'extra' => '#F6F2ED',
        'gray' => '#8A969E',
        'bdr' => '#EBE2D8',
    ],
    'gul' => [
        'ad' => 'Gül & Koyu',
        'primary' => '#2C1C1C',
        'accent' => '#BA5353',
        'bg' => '#F3E9E9',
        'text' => '#2C1C1C',
        'text_light' => '#FFFFFF',
        'black' => '#2C1C1C',
        'extra' => '#F3E9E9',
        'gray' => '#9E8A8A',
        'bdr' => '#E3D7D7',
    ],
];

$delogisModuller = [
    'hero' => [
        'ad' => 'Hero (Slider)',
        'kategori' => 'ust',
        'sira' => 10,
        'aktif_varsayilan' => true,
        'silinebilir' => false,
        'aciklama' => 'Ana sayfa üst slider. Boş bırakırsanız profil fotoğrafı ve varsayılan metinler kullanılır.',
        'alanlar' => [
            'ust_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Kaliteli terapi burada başlar'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık (H1)', 'varsayilan' => 'Yeni bir hayata birlikte başlayalım'],
            'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama (boş = gizlenir)', 'varsayilan' => ''],
            'cta_metin' => ['tip' => 'metin', 'label' => 'Birincil buton', 'varsayilan' => 'Randevu Al'],
            'cta2_metin' => ['tip' => 'metin', 'label' => 'İkincil buton', 'varsayilan' => 'Hakkımda'],
            'youtube_id' => ['tip' => 'metin', 'label' => 'YouTube video ID (Home 4/5, opsiyonel)', 'varsayilan' => ''],
            'arkaplan_resmi' => ['tip' => 'resim', 'label' => 'Varsayılan arkaplan (slayt yoksa)', 'varsayilan' => null],
            'slaytlar' => ['tip' => 'resim_baslik_metin', 'label' => 'Slaytlar (resim + başlık + metin)', 'varsayilan' => []],
        ],
    ],
    'about' => [
        'ad' => 'Hakkımda',
        'kategori' => 'orta',
        'sira' => 20,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Fotoğraf + deneyim + maddeler.',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Hakkımda'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Deneyim ve empati ile birlikte'],
            'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama (boş = hakkımda metnim kullanılır)', 'varsayilan' => ''],
            'deneyim_sayi' => ['tip' => 'sayi', 'label' => 'Deneyim yılı (0 = gizle)', 'varsayilan' => 0],
            'deneyim_etiket' => ['tip' => 'metin', 'label' => 'Deneyim etiketi', 'varsayilan' => "Yıllık\nDeneyim"],
            'maddeler' => ['tip' => 'liste', 'label' => 'Maddeler (boş = yaklaşımım kullanılır)', 'varsayilan' => ''],
            'resim' => ['tip' => 'resim', 'label' => 'Fotoğraf', 'varsayilan' => null],
            'buton_metin' => ['tip' => 'metin', 'label' => 'Buton', 'varsayilan' => 'Randevu Al'],
        ],
    ],
    'features' => [
        'ad' => 'Öne çıkanlar',
        'kategori' => 'orta',
        'sira' => 30,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => '3 kartlık özellik / vurgu şeridi.',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Öne çıkanlar'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Size özel terapi yaklaşımları'],
            'kartlar' => ['tip' => 'ikon_baslik_metin', 'label' => 'Kartlar (max 3)', 'varsayilan' => [
                ['ikon' => 'icon-philosophy', 'baslik' => 'Bireysel terapi', 'metin' => 'Kişiye özel, kanıta dayalı bireysel seanslar.'],
                ['ikon' => 'icon-psychology', 'baslik' => 'Çocuk ve ergen', 'metin' => 'Gelişim dönemine uygun güvenli alan.'],
                ['ikon' => 'icon-somatise', 'baslik' => 'Çift ve aile', 'metin' => 'İlişki dinamiklerini birlikte çalışırız.'],
            ]],
        ],
    ],
    'services' => [
        'ad' => 'Hizmetler',
        'kategori' => 'orta',
        'sira' => 40,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Hekim panelindeki hizmetler kart olarak listelenir.',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Hizmetler'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Sunduğum hizmetler'],
            'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Her danışanın ihtiyacına özel programlar.'],
            'hizmet_limiti' => ['tip' => 'sayi', 'label' => 'Anasayfada gösterilecek sayı', 'varsayilan' => 4],
            'hizmet_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Hizmetler', 'varsayilan' => 'hizmetler'],
            'buton_metin' => ['tip' => 'metin', 'label' => 'Tüm hizmetler butonu', 'varsayilan' => 'Tüm hizmetler'],
        ],
    ],
    'get_started' => [
        'ad' => 'Kimler için?',
        'kategori' => 'orta',
        'sira' => 50,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Home 1 “Get personal therapy” bloğu — 4 hedef kitle.',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Uzmanlık'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Kişiye özel terapi seansları'],
            'youtube_id' => ['tip' => 'metin', 'label' => 'YouTube video ID (opsiyonel)', 'varsayilan' => ''],
            'resim' => ['tip' => 'resim', 'label' => 'Yan görsel', 'varsayilan' => null],
            'maddeler' => ['tip' => 'ikon_baslik_metin', 'label' => 'Hedef kitle (max 4)', 'varsayilan' => [
                ['ikon' => 'icon-woman', 'baslik' => 'Yetişkinler', 'metin' => 'Bireysel süreçte yanınızdayım.'],
                ['ikon' => 'icon-girl-face-with-two-small-pony-tails', 'baslik' => 'Bireysel', 'metin' => 'Size özel seans planı.'],
                ['ikon' => 'icon-family-1', 'baslik' => 'Aileler', 'metin' => 'Aile içi iletişimi güçlendiririz.'],
                ['ikon' => 'icon-suitcase', 'baslik' => 'Kurumsal', 'metin' => 'İş ve performans odaklı destek.'],
            ]],
        ],
    ],
    'why_choose' => [
        'ad' => 'Neden ben?',
        'kategori' => 'orta',
        'sira' => 60,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'İkon + başlık maddeleri.',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Neden ben?'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Farkımı yaratan yaklaşımlar'],
            'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Kanıta dayalı, kişiye özel ve şefkatli bir süreç.'],
            'resim' => ['tip' => 'resim', 'label' => 'Fotoğraf (bazı home varyantlarında)', 'varsayilan' => null],
            'buton_metin' => ['tip' => 'metin', 'label' => 'Buton', 'varsayilan' => 'Hakkımda'],
            'sebepler' => ['tip' => 'ikon_baslik_metin', 'label' => 'Sebepler', 'varsayilan' => [
                ['ikon' => 'icon-psychologist', 'baslik' => 'Uzman yaklaşım', 'metin' => 'Alanında deneyimli klinik pratik.'],
                ['ikon' => 'icon-online-business', 'baslik' => 'Online seans', 'metin' => 'Yüz yüze ve çevrimiçi seçenekler.'],
                ['ikon' => 'icon-rating', 'baslik' => 'Güvenli alan', 'metin' => 'Gizlilik ve yargısız dinleme.'],
            ]],
        ],
    ],
    'counters' => [
        'ad' => 'Sayılar',
        'kategori' => 'orta',
        'sira' => 70,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Sayaç şeridi (Home 2/3).',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Rakamlarla'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Her yeni süreçte hazırım'],
            'cta_metin' => ['tip' => 'metin', 'label' => 'Alt bant metin', 'varsayilan' => 'Hikayenizi dinlemeye hazırım'],
            'buton_metin' => ['tip' => 'metin', 'label' => 'Buton', 'varsayilan' => 'Randevu Al'],
            'sayac_1_sayi' => ['tip' => 'sayi', 'label' => 'Sayaç 1 (boş = gerçek istatistiklerim)', 'varsayilan' => ''],
            'sayac_1_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 1 etiket', 'varsayilan' => ''],
            'sayac_2_sayi' => ['tip' => 'sayi', 'label' => 'Sayaç 2', 'varsayilan' => ''],
            'sayac_2_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 2 etiket', 'varsayilan' => ''],
            'sayac_3_sayi' => ['tip' => 'sayi', 'label' => 'Sayaç 3', 'varsayilan' => ''],
            'sayac_3_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 3 etiket', 'varsayilan' => ''],
        ],
    ],
    'cta' => [
        'ad' => 'Randevu bandı',
        'kategori' => 'orta',
        'sira' => 80,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Tam genişlik çağrı bandı.',
        'alanlar' => [
            'baslik' => ['tip' => 'metin', 'label' => 'Mesaj', 'varsayilan' => 'Hikayenizi dinlemeye hazırım'],
            'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Alt açıklama (opsiyonel)', 'varsayilan' => ''],
            'buton_metin' => ['tip' => 'metin', 'label' => 'Buton', 'varsayilan' => 'Randevu Al'],
        ],
    ],
    'testimonial' => [
        'ad' => 'Danışan yorumları',
        'kategori' => 'alt',
        'sira' => 90,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Onaylı yorumlar.',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Yorumlar'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Danışan deneyimleri'],
            'yorum_limiti' => ['tip' => 'sayi', 'label' => 'Yorum sayısı', 'varsayilan' => 6],
            'yorum_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Yorumlar', 'varsayilan' => 'yorumlar'],
        ],
    ],
    'cases' => [
        'ad' => 'Galeri',
        'kategori' => 'orta',
        'sira' => 85,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Galeri / vaka görselleri.',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Galeri'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Klinik görselleri'],
            'limit' => ['tip' => 'sayi', 'label' => 'Görsel sayısı', 'varsayilan' => 6],
        ],
    ],
    'faq' => [
        'ad' => 'S.S.S.',
        'kategori' => 'alt',
        'sira' => 100,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Accordion sorular (Home 2).',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'S.S.S.'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Sıkça sorulan sorular'],
            'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Sol açıklama', 'varsayilan' => 'Seans süreci, gizlilik ve randevu hakkında merak edilenler.'],
            'sss_limiti' => ['tip' => 'sayi', 'label' => 'Soru sayısı', 'varsayilan' => 6],
            'sss_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: S.S.S.', 'varsayilan' => 'sss'],
            'resim' => ['tip' => 'resim', 'label' => 'Sol görsel', 'varsayilan' => null],
        ],
    ],
    'blog' => [
        'ad' => 'Son yazılar',
        'kategori' => 'alt',
        'sira' => 110,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Blog kartları.',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Blog'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Son yazılarım'],
            'blog_limiti' => ['tip' => 'sayi', 'label' => 'Yazı sayısı', 'varsayilan' => 3],
            'blog_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Blog', 'varsayilan' => 'bloglar'],
        ],
    ],
    'appointment' => [
        'ad' => 'Randevu',
        'kategori' => 'alt',
        'sira' => 120,
        'aktif_varsayilan' => true,
        'silinebilir' => false,
        'aciklama' => 'İletişim kutusu + randevu sihirbazı.',
        'alanlar' => [
            'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük üst başlık', 'varsayilan' => 'Randevu'],
            'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana başlık', 'varsayilan' => 'Online randevu alın'],
            'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Takvimden size uygun saati seçin. Kayıt zorunlu değildir.'],
            'kutu_baslik' => ['tip' => 'metin', 'label' => 'Sol kutu başlık', 'varsayilan' => 'İlk seans için arayın'],
        ],
    ],
    'video' => [
        'ad' => 'Video bandı',
        'kategori' => 'orta',
        'sira' => 75,
        'aktif_varsayilan' => true,
        'silinebilir' => true,
        'aciklama' => 'Home 5 tam genişlik video CTA.',
        'alanlar' => [
            'baslik' => ['tip' => 'metin', 'label' => 'Başlık', 'varsayilan' => 'Hikayenizi dinlemeye hazırım'],
            'buton_metin' => ['tip' => 'metin', 'label' => 'Buton', 'varsayilan' => 'İletişim'],
            'youtube_id' => ['tip' => 'metin', 'label' => 'YouTube video ID', 'varsayilan' => ''],
            'arkaplan_resmi' => ['tip' => 'resim', 'label' => 'Arkaplan', 'varsayilan' => null],
        ],
    ],
];

$compose = static function (array $spec) use ($delogisPaletler, $delogisModuller): array {
    $moduller = [];
    foreach ($spec['moduller'] as $kod => $sira) {
        if (! isset($delogisModuller[$kod])) {
            continue;
        }
        $m = $delogisModuller[$kod];
        $m['sira'] = (int) $sira;
        $moduller[$kod] = $m;
    }

    return [
        'ad' => $spec['ad'],
        'aciklama' => $spec['aciklama'],
        'onizleme' => $spec['onizleme'] ?? '/themes/delogis/images/home-showcase/home-1-1.png',
        'kaynak_html' => $spec['kaynak_html'],
        'onerilen_uzmanlik' => ['psikoloji', 'psikiyatri', 'danışmanlık', 'terapi'],
        'renk_paletleri' => $delogisPaletler,
        'varsayilan_palet' => $spec['palet'],
        'moduller' => $moduller,
    ];
};

return [
    'tema-4' => $compose([
        'ad' => 'Delogis Home 1',
        'aciklama' => 'Tam genişlik hero slider, hakkımda, ikon hizmetler, kimler için, CTA.',
        'kaynak_html' => 'tema-2/bracketweb.com/delogis-html/main-html/index.html',
        'onizleme' => '/themes/delogis/images/home-showcase/home-1-1.png',
        'palet' => 'terracotta',
        'moduller' => [
            'hero' => 10,
            'about' => 20,
            'features' => 30,
            'services' => 40,
            'get_started' => 50,
            'cta' => 60,
            'testimonial' => 70,
            'cases' => 80,
            'why_choose' => 90,
            'appointment' => 100,
            'blog' => 110,
        ],
    ]),
    'tema-5' => $compose([
        'ad' => 'Delogis Home 2',
        'aciklama' => 'Gölgeli hero, görselli hizmetler, SSS, sayaçlar.',
        'kaynak_html' => 'tema-2/bracketweb.com/delogis-html/main-html/index2.html',
        'onizleme' => '/themes/delogis/images/home-showcase/home-1-2.png',
        'palet' => 'altin',
        'moduller' => [
            'hero' => 10,
            'about' => 20,
            'services' => 30,
            'why_choose' => 40,
            'testimonial' => 50,
            'counters' => 60,
            'cases' => 70,
            'cta' => 80,
            'faq' => 90,
            'blog' => 100,
            'appointment' => 110,
        ],
    ]),
    'tema-6' => $compose([
        'ad' => 'Delogis Home 3',
        'aciklama' => 'Yan görselli slider, özellik şeridi, koyu hizmet kartları.',
        'kaynak_html' => 'tema-2/bracketweb.com/delogis-html/main-html/index3.html',
        'onizleme' => '/themes/delogis/images/home-showcase/home-1-3.png',
        'palet' => 'terracotta',
        'moduller' => [
            'hero' => 10,
            'features' => 20,
            'about' => 30,
            'services' => 40,
            'counters' => 50,
            'testimonial' => 60,
            'cases' => 70,
            'why_choose' => 80,
            'blog' => 90,
            'cta' => 100,
            'appointment' => 110,
        ],
    ]),
    'tema-7' => $compose([
        'ad' => 'Delogis Home 4',
        'aciklama' => 'Ortalanmış video hero, modern hizmet kartları.',
        'kaynak_html' => 'tema-2/bracketweb.com/delogis-html/main-html/index4.html',
        'onizleme' => '/themes/delogis/images/home-showcase/home-1-4.png',
        'palet' => 'gul',
        'moduller' => [
            'hero' => 10,
            'about' => 20,
            'services' => 30,
            'why_choose' => 40,
            'testimonial' => 50,
            'features' => 60,
            'cta' => 70,
            'cases' => 80,
            'appointment' => 90,
            'blog' => 100,
        ],
    ]),
    'tema-8' => $compose([
        'ad' => 'Delogis Home 5',
        'aciklama' => 'Split hero + video, dikey hizmet kartları, video bandı.',
        'kaynak_html' => 'tema-2/bracketweb.com/delogis-html/main-html/index5.html',
        'onizleme' => '/themes/delogis/images/home-showcase/home-1-5.png',
        'palet' => 'altin',
        'moduller' => [
            'hero' => 10,
            'features' => 20,
            'about' => 30,
            'services' => 40,
            'cases' => 50,
            'testimonial' => 60,
            'video' => 70,
            'why_choose' => 80,
            'blog' => 90,
            'cta' => 100,
            'appointment' => 110,
        ],
    ]),
    'tema-9' => $compose([
        'ad' => 'Delogis Boxed',
        'aciklama' => 'Home 1 içeriği, ortalanmış kutu yerleşim (boxed-wrapper).',
        'kaynak_html' => 'tema-2/bracketweb.com/delogis-html/main-html/index-boxed.html',
        'onizleme' => '/themes/delogis/images/home-showcase/home-boxed.png',
        'palet' => 'terracotta',
        'moduller' => [
            'hero' => 10,
            'about' => 20,
            'features' => 30,
            'services' => 40,
            'get_started' => 50,
            'cta' => 60,
            'testimonial' => 70,
            'cases' => 80,
            'why_choose' => 90,
            'appointment' => 100,
            'blog' => 110,
        ],
    ]),
];
