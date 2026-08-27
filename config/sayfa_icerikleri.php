<?php

/**
 * Frontend sayfa banner ve icerik ayarlari.
 *
 * Her sayfa icin: banner_baslik, banner_alt, banner_gorsel (opsiyonel).
 * Bazi sayfalarda ek alanlar: giris_metin, kisa_aciklama vb.
 *
 * Panel: Site Ayarlari > Sayfa Basliklari & Bannerlar
 * Kayit: site_options.value (key='sayfa_ayarlari', JSON)
 */
return [
    'anasayfa' => [
        'label' => 'Ana Sayfa',
        'aciklama' => 'Ana sayfa modülleri (Sayfa Builder) buradan yönetilmez; sadece SEO ve tarayıcı sekme başlığı.',
        'alanlar' => [
            'seo_baslik' => ['tip' => 'metin', 'label' => 'Tarayıcı sekme başlığı', 'varsayilan' => ''],
        ],
        'link' => 'panel.sayfa-builder.index', // buraya yönlendir
    ],
    'hakkimda' => [
        'label' => 'Hakkımda',
        'aciklama' => 'Hakkımda sayfasının başlık bandı ve tanıtım metni.',
        'alanlar' => [
            'banner_baslik' => ['tip' => 'metin', 'label' => 'Banner Başlığı', 'varsayilan' => 'Hakkımda'],
            'banner_alt' => ['tip' => 'uzun_metin', 'label' => 'Banner Altı Kısa Metin', 'varsayilan' => 'Deneyim, eğitim ve yaklaşımım hakkında bilmek istedikleriniz.'],
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Banner Arkaplan Görseli (opsiyonel — profil resmi kullanılır)', 'varsayilan' => null],
        ],
    ],
    'hizmetler' => [
        'label' => 'Hizmetler',
        'aciklama' => 'Hizmetler listeleme sayfası.',
        'alanlar' => [
            'banner_baslik' => ['tip' => 'metin', 'label' => 'Banner Başlığı', 'varsayilan' => 'Hizmetlerim'],
            'banner_alt' => ['tip' => 'uzun_metin', 'label' => 'Banner Altı Kısa Metin', 'varsayilan' => 'Sunduğum destek ve terapi programları.'],
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Banner Arkaplan Görseli', 'varsayilan' => null],
            'kisa_giris' => ['tip' => 'uzun_metin', 'label' => 'Sayfa Girişi (kartların üstünde)', 'varsayilan' => null],
        ],
    ],
    'hizmet-detay' => [
        'label' => 'Hizmet Detay',
        'aciklama' => 'Her hizmet detay sayfası — banner ortak ayardır, hizmet içeriği panelden gelir.',
        'alanlar' => [
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Ortak Banner Görseli (her hizmet detayında)', 'varsayilan' => null],
        ],
    ],
    'blog' => [
        'label' => 'Blog',
        'aciklama' => 'Blog listeleme sayfası.',
        'alanlar' => [
            'banner_baslik' => ['tip' => 'metin', 'label' => 'Banner Başlığı', 'varsayilan' => 'Blog'],
            'banner_alt' => ['tip' => 'uzun_metin', 'label' => 'Banner Altı Kısa Metin', 'varsayilan' => 'Uzmanlık alanımla ilgili yazılarım.'],
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Banner Arkaplan Görseli', 'varsayilan' => null],
        ],
    ],
    'blog-detay' => [
        'label' => 'Blog Detay',
        'aciklama' => 'Her blog detay sayfası — banner ortak ayardır.',
        'alanlar' => [
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Ortak Banner Görseli (yazı kapağı yoksa)', 'varsayilan' => null],
        ],
    ],
    'egitimler' => [
        'label' => 'Eğitimler',
        'aciklama' => 'Eğitim listeleme sayfası.',
        'alanlar' => [
            'banner_baslik' => ['tip' => 'metin', 'label' => 'Banner Başlığı', 'varsayilan' => 'Eğitimlerim'],
            'banner_alt' => ['tip' => 'uzun_metin', 'label' => 'Banner Altı Kısa Metin', 'varsayilan' => 'Katılabileceğiniz eğitim ve atölyeler.'],
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Banner Arkaplan Görseli', 'varsayilan' => null],
        ],
    ],
    'egitim-detay' => [
        'label' => 'Eğitim Detay',
        'aciklama' => 'Her eğitim detay sayfası — banner ortak ayar.',
        'alanlar' => [
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Ortak Banner Görseli', 'varsayilan' => null],
        ],
    ],
    'galeri' => [
        'label' => 'Galeri',
        'aciklama' => 'Fotoğraf galerisi sayfası.',
        'alanlar' => [
            'banner_baslik' => ['tip' => 'metin', 'label' => 'Banner Başlığı', 'varsayilan' => 'Galeri'],
            'banner_alt' => ['tip' => 'uzun_metin', 'label' => 'Banner Altı Kısa Metin', 'varsayilan' => 'Çalışma ortamımdan kareler.'],
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Banner Arkaplan Görseli', 'varsayilan' => null],
        ],
    ],
    'sss' => [
        'label' => 'S.S.S.',
        'aciklama' => 'Sıkça Sorulan Sorular sayfası.',
        'alanlar' => [
            'banner_baslik' => ['tip' => 'metin', 'label' => 'Banner Başlığı', 'varsayilan' => 'Sıkça Sorulan Sorular'],
            'banner_alt' => ['tip' => 'uzun_metin', 'label' => 'Banner Altı Kısa Metin', 'varsayilan' => 'Danışanlarımın en çok merak ettikleri.'],
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Banner Arkaplan Görseli', 'varsayilan' => null],
        ],
    ],
    'iletisim' => [
        'label' => 'İletişim',
        'aciklama' => 'İletişim sayfası.',
        'alanlar' => [
            'banner_baslik' => ['tip' => 'metin', 'label' => 'Banner Başlığı', 'varsayilan' => 'İletişim'],
            'banner_alt' => ['tip' => 'uzun_metin', 'label' => 'Banner Altı Kısa Metin', 'varsayilan' => 'Sorularınız ve randevu için bize ulaşın.'],
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Banner Arkaplan Görseli', 'varsayilan' => null],
        ],
    ],
    'randevu' => [
        'label' => 'Randevu',
        'aciklama' => 'Randevu wizard sayfası.',
        'alanlar' => [
            'banner_baslik' => ['tip' => 'metin', 'label' => 'Banner Başlığı', 'varsayilan' => 'Online Randevu'],
            'banner_alt' => ['tip' => 'uzun_metin', 'label' => 'Banner Altı Kısa Metin', 'varsayilan' => 'Hizmet, gün ve saat seçin — birkaç saniyede randevunuzu oluşturun.'],
            'banner_gorsel' => ['tip' => 'resim', 'label' => 'Banner Arkaplan Görseli', 'varsayilan' => null],
        ],
    ],
];
