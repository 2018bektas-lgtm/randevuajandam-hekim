<?php

/**
 * Hekim (bireysel doktor) web sitesi — tam tasarım tema paketleri.
 *
 * Klinik temaları ayrı uygulamadadır (randevuajandam-klinik); buraya karışmaz.
 *
 * Hekim için aktif katalog (yalnızca bu ikisi):
 *   - tema-1  → Hipno
 *   - delogis → Delogis Klinik
 *
 * Her tema klasörü (layout anahtarı = klasör adı):
 *   resources/views/frontend/themes/{pack}/
 *     layouts/app.blade.php
 *     layouts/partials/head|header|footer|script.blade.php
 *     pages/anasayfa.blade.php  (ve diğer sayfalar)
 *
 * public/css/themes/{id}.css
 * public/themes/{pack}/          ← asset paketleri (delogis vb.)
 *
 * PHP: theme_layout() | theme_view('pages.x') | theme_pack_id()
 */
return [
    'default' => 'tema-1',

    /** Web sitesi paketinde premium temalar açık mı? */
    'premium_unlocked' => (bool) env('THEMES_PREMIUM_UNLOCKED', true),

    /** Hekim temaları — klinik katalogu kullanılmaz */
    'audience' => 'hekim',

    'catalog' => [
        'tema-1' => [
            'ad' => 'Hipno',
            'aciklama' => 'Psikoloji ve danışmanlık için premium tasarım. Koyu zemin, altın vurgu, serif başlıklar.',
            'renk' => '#9B9A84',
            'font_sans' => 'Sora',
            'font_display' => 'Marcellus',
            'google_fonts' => 'Marcellus&family=Sora:wght@100..800',
            'preview' => ['#262626', '#9B9A84', '#F9F9F9'],
            'premium' => false,
            'layout' => 'tema-1',
            'audience' => 'hekim',
        ],
        'delogis' => [
            'ad' => 'Delogis',
            'aciklama' => 'Delogis Home 3 (index3): orijinal palet #976147 / #1a1414. Klinik dilde modern vitrin.',
            'renk' => '#976147',
            'font_sans' => 'Lexend',
            'font_display' => 'Castoro',
            'google_fonts' => 'Lexend:wght@300;400;500;600;700;800&family=Castoro:ital@0;1',
            'preview' => ['#976147', '#f2edea', '#1a1414'],
            'premium' => true,
            'layout' => 'delogis',
            'audience' => 'hekim',
        ],
    ],
];
