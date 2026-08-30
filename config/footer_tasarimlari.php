<?php

/**
 * Footer tasarım registry — TEMA BAZLI.
 *
 * Her tema paketi (theme_pack_id) bir footer grubuna bağlıdır:
 *   tema-1 / tema-2 / tema-3  → "hipno"
 *   delogis (tema-4..9)       → "delogis"
 *
 * Her grubun kendi footer tasarım listesi vardır; hekim panelden
 * (Site Ayarları → Footer) yalnızca aktif temasının tasarımlarını görür.
 * Seçim tema grubu bazında saklanır: site_options["footer_tasarim_{grup}"].
 * Böylece hekim tema değiştirip geri döndüğünde seçimi korunur.
 *
 * Blade karşılıkları: view => "frontend.partials.footer.{view}"
 *
 * "ortak.*" tasarımları her iki grupta da çalışır: markup nötr, renkler
 * head.blade.php'de tanımlı CSS değişkenlerinden (--primary-color,
 * --accent-color, --secondary-color, --text-color) gelir.
 *
 * destek[] → tasarımın hangi blokları kullandığı. Panelde desteklenmeyen
 * ayarlar pasifleştirilir.
 */
return [

    /** Tema paketi → footer grubu */
    'gruplar' => [
        'tema-1' => 'hipno',
        'tema-2' => 'hipno',
        'tema-3' => 'hipno',
        'delogis' => 'delogis',
    ],

    /** Grup varsayılan tasarımı (hekim seçim yapmadıysa) */
    'varsayilan' => [
        'hipno' => 'zarif',
        'delogis' => 'genis',
    ],

    /**
     * Panel ayarları + varsayılanları.
     * Anahtarlar site_options tablosunda bu adlarla saklanır.
     */
    'ayarlar' => [
        // Logo alanı
        'footer_logo_tip' => ['tip' => 'secim', 'varsayilan' => 'site', 'secenekler' => [
            'site' => 'Site logosu (Genel ayarlardaki)',
            'ozel' => 'Footer icin ozel logo',
            'yazi' => 'Yazi olarak unvan + ad',
            'gizli' => 'Logo alani gizli',
        ]],
        'footer_logo' => ['tip' => 'resim', 'varsayilan' => ''],
        'footer_logo_yukseklik' => ['tip' => 'sayi', 'varsayilan' => 52],

        // Metinler
        'footer_aciklama' => ['tip' => 'uzun_metin', 'varsayilan' => ''],
        'footer_telif' => ['tip' => 'metin', 'varsayilan' => ''],
        'footer_baslik_kesfet' => ['tip' => 'metin', 'varsayilan' => 'Keşfet'],
        'footer_baslik_iletisim' => ['tip' => 'metin', 'varsayilan' => 'İletişim'],
        'footer_baslik_sosyal' => ['tip' => 'metin', 'varsayilan' => 'Bizi takip edin'],
        'footer_cta_baslik' => ['tip' => 'metin', 'varsayilan' => 'Randevu almaya hazır mısınız?'],

        // Bloklar
        'footer_cta_goster' => ['tip' => 'bool', 'varsayilan' => true],
        'footer_hakkinda_goster' => ['tip' => 'bool', 'varsayilan' => true],
        'footer_kesfet_goster' => ['tip' => 'bool', 'varsayilan' => true],
        'footer_iletisim_goster' => ['tip' => 'bool', 'varsayilan' => true],
        'footer_saatler_goster' => ['tip' => 'bool', 'varsayilan' => true],
        'footer_sosyal_goster' => ['tip' => 'bool', 'varsayilan' => true],
        'footer_randevu_goster' => ['tip' => 'bool', 'varsayilan' => true],
        'footer_sayfalar_goster' => ['tip' => 'bool', 'varsayilan' => true],
        'footer_marka_goster' => ['tip' => 'bool', 'varsayilan' => true],
    ],

    /** Grup → tasarımlar */
    'tasarimlar' => [

        // ==========================================================
        // HIPNO grubu (tema-1 · tema-2 · tema-3)
        // ==========================================================
        'hipno' => [
            'zarif' => [
                'ad' => 'Zarif',
                'aciklama' => 'Üstte randevu şeridi, geniş logo + slogan, kart görünümlü iletişim bilgileri ve sosyal kolonu.',
                'view' => 'ortak.zarif',
                'ton' => 'acik',
                'onizleme' => ['cta', 'logo', 'uc-kolon', 'alt'],
                'destek' => ['cta', 'hakkinda', 'kesfet', 'iletisim', 'saatler', 'sosyal', 'sayfalar', 'marka'],
            ],
            'sutunlu' => [
                'ad' => '4 Sütun',
                'aciklama' => 'Kurumsal düzen: logo + tanıtım, keşfet linkleri, iletişim ve randevu sütunu.',
                'view' => 'ortak.sutunlu',
                'ton' => 'acik',
                'onizleme' => ['dort-kolon', 'alt'],
                'destek' => ['cta', 'hakkinda', 'kesfet', 'iletisim', 'saatler', 'sosyal', 'randevu', 'sayfalar', 'marka'],
            ],
            'koyu' => [
                'ad' => 'Koyu Vitrin',
                'aciklama' => 'Koyu zemin, vurgu renkli detaylar. Ortalanmış logo, 3 sütun bilgi, pill sosyal ikonlar.',
                'view' => 'ortak.koyu',
                'ton' => 'koyu',
                'onizleme' => ['logo-orta', 'uc-kolon', 'alt'],
                'destek' => ['cta', 'hakkinda', 'kesfet', 'iletisim', 'saatler', 'sosyal', 'randevu', 'sayfalar', 'marka'],
            ],
            'merkezi' => [
                'ad' => 'Merkezî Minimal',
                'aciklama' => 'Tek kolon, ortalanmış logo + slogan, yatay menü ve sosyal satırı. Sade siteler için.',
                'view' => 'ortak.merkezi',
                'ton' => 'acik',
                'onizleme' => ['logo-orta', 'tek-kolon', 'alt'],
                'destek' => ['cta', 'hakkinda', 'kesfet', 'iletisim', 'sosyal', 'sayfalar', 'marka'],
            ],
            'klasik' => [
                'ad' => 'Hipno Klasik',
                'aciklama' => 'Temanın orijinal footer’ı — büyük slogan + yatay iletişim satırı.',
                'view' => 'hipno.klasik',
                'ton' => 'acik',
                'onizleme' => ['cta', 'logo', 'iki-kolon', 'alt'],
                'destek' => ['cta', 'kesfet', 'iletisim', 'sosyal', 'sayfalar', 'marka'],
            ],
        ],

        // ==========================================================
        // DELOGIS grubu (tema-4 … tema-9)
        // ==========================================================
        'delogis' => [
            'genis' => [
                'ad' => 'Delogis Geniş',
                'aciklama' => 'Temanın orijinal footer’ı — çalışma saatleri şeridi, 4 sütun ve randevu kutusu.',
                'view' => 'delogis.genis',
                'ton' => 'koyu',
                'onizleme' => ['cta', 'dort-kolon', 'alt'],
                'destek' => ['cta', 'hakkinda', 'kesfet', 'iletisim', 'saatler', 'sosyal', 'randevu', 'sayfalar', 'marka'],
            ],
            'zarif' => [
                'ad' => 'Zarif',
                'aciklama' => 'Üstte randevu şeridi, geniş logo + slogan, kart görünümlü iletişim bilgileri.',
                'view' => 'ortak.zarif',
                'ton' => 'acik',
                'onizleme' => ['cta', 'logo', 'uc-kolon', 'alt'],
                'destek' => ['cta', 'hakkinda', 'kesfet', 'iletisim', 'saatler', 'sosyal', 'sayfalar', 'marka'],
            ],
            'sutunlu' => [
                'ad' => '4 Sütun',
                'aciklama' => 'Logo + tanıtım, keşfet linkleri, iletişim ve randevu sütunu.',
                'view' => 'ortak.sutunlu',
                'ton' => 'acik',
                'onizleme' => ['dort-kolon', 'alt'],
                'destek' => ['cta', 'hakkinda', 'kesfet', 'iletisim', 'saatler', 'sosyal', 'randevu', 'sayfalar', 'marka'],
            ],
            'koyu' => [
                'ad' => 'Koyu Vitrin',
                'aciklama' => 'Koyu zemin, ortalanmış logo, 3 sütun bilgi ve pill sosyal ikonlar.',
                'view' => 'ortak.koyu',
                'ton' => 'koyu',
                'onizleme' => ['logo-orta', 'uc-kolon', 'alt'],
                'destek' => ['cta', 'hakkinda', 'kesfet', 'iletisim', 'saatler', 'sosyal', 'randevu', 'sayfalar', 'marka'],
            ],
            'merkezi' => [
                'ad' => 'Merkezî Minimal',
                'aciklama' => 'Tek kolon, ortalanmış logo + slogan, yatay menü ve sosyal satırı.',
                'view' => 'ortak.merkezi',
                'ton' => 'acik',
                'onizleme' => ['logo-orta', 'tek-kolon', 'alt'],
                'destek' => ['cta', 'hakkinda', 'kesfet', 'iletisim', 'sosyal', 'sayfalar', 'marka'],
            ],
        ],
    ],
];
