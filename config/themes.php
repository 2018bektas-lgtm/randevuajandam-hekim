<?php

/**
 * Tam tasarım tema paketleri.
 *
 * Her tema klasörü (layout anahtarı = klasör adı):
 *   resources/views/frontend/themes/{pack}/
 *     layouts/app.blade.php
 *     layouts/partials/head|header|footer|script.blade.php
 *     pages/anasayfa.blade.php  (ve diğer sayfalar)
 *
 * public/css/themes/{id}.css    ← renk + layout CSS
 * public/vendor/hipno/          ← tema asset'leri
 *
 * PHP: theme_layout() | theme_view('pages.x') | theme_pack_id()
 */
return [
    'default' => 'tema-1',

    'premium_unlocked' => (bool) env('THEMES_PREMIUM_UNLOCKED', true),

    'catalog' => [
        'tema-1' => [
            'ad'          => 'Hipno',
            'aciklama'    => 'Psikoloji ve danışmanlık temasından uyarlanmış premium tasarım. Koyu zemin, altın vurgu, serif başlıklar.',
            'renk'        => '#9B9A84',
            'font_sans'   => 'Sora',
            'font_display' => 'Marcellus',
            'google_fonts' => 'Marcellus&family=Sora:wght@100..800',
            'preview'     => ['#262626', '#9B9A84', '#F9F9F9'],
            'premium'     => false,
            'layout'      => 'tema-1',
        ],
        'delogis' => [
            'ad' => 'Delogis Klinik',
            'aciklama' => 'Delogis Home 3: slider hero, feature kartlar, premium klinik dili (orijinal şablon).',
            'renk' => '#B9905D',
            'font_sans' => 'Lexend',
            'font_display' => 'Castoro',
            'google_fonts' => 'Lexend:wght@300;400;500;600;700;800&family=Castoro:ital@0;1',
            'preview' => ['#B9905D', '#F6F2ED', '#293B46'],
            'premium' => true,
            'layout' => 'delogis',
        ],
    ],
];
