<?php

/**
 * Hekim (bireysel doktor) web sitesi — tam tasarım tema paketleri.
 *
 * Klinik temaları ayrı uygulamadadır (randevuajandam-klinik); buraya karışmaz.
 *
 * Aktif katalog (numaralı):
 *   - tema-1  → Hipno Klasik (statik hero)
 *   - tema-2  → Hipno Slider (swiper hero)
 *   - tema-3  → Hipno Video  (fullscreen video hero)
 *   - tema-4  → Delogis Home 1
 *   - tema-5  → Delogis Home 2
 *   - tema-6  → Delogis Home 3
 *   - tema-7  → Delogis Home 4
 *   - tema-8  → Delogis Home 5
 *   - tema-9  → Delogis Home Boxed (Home 1 + boxed-wrapper)
 *
 * Her tema klasörü:
 *   resources/views/frontend/themes/{tema-N}/
 *     layouts/app.blade.php
 *     layouts/partials/head|header|footer|script.blade.php
 *     modules/*.blade.php   ← modüler builder (bkz. config/tema_modulleri.php)
 *     pages/anasayfa.blade.php  (ve diğer sayfalar)
 *
 * public/css/themes/{id}.css
 */
return [
    'default' => 'tema-1',

    /** Web sitesi paketinde premium temalar açık mı? */
    'premium_unlocked' => (bool) env('THEMES_PREMIUM_UNLOCKED', true),

    /** Hekim temaları — klinik katalogu kullanılmaz */
    'audience' => 'hekim',

    'catalog' => [
        'tema-1' => [
            'ad' => 'tema-1 · Hipno Klasik',
            'aciklama' => 'Psikoloji ve danışmanlık için premium tasarım. Statik hero + parallax arka plan.',
            'renk' => '#9B9A84',
            'font_sans' => 'Sora',
            'font_display' => 'Marcellus',
            'google_fonts' => 'Marcellus&family=Sora:wght@100..800',
            'preview' => ['#262626', '#9B9A84', '#F9F9F9'],
            'premium' => false,
            'layout' => 'tema-1',
            'audience' => 'hekim',
        ],
        'tema-2' => [
            'ad' => 'tema-2 · Hipno Slider',
            'aciklama' => 'Hero bölümünde swiper slider — 2-5 slayt arası çoklu tanıtım.',
            'renk' => '#9B9A84',
            'font_sans' => 'Sora',
            'font_display' => 'Marcellus',
            'google_fonts' => 'Marcellus&family=Sora:wght@100..800',
            'preview' => ['#262626', '#9B9A84', '#F9F9F9'],
            'premium' => false,
            'layout' => 'tema-2',
            'audience' => 'hekim',
        ],
        'tema-3' => [
            'ad' => 'tema-3 · Hipno Video',
            'aciklama' => 'Hero bölümünde tam ekran arka plan videosu — sinemasal, dikkat çekici.',
            'renk' => '#9B9A84',
            'font_sans' => 'Sora',
            'font_display' => 'Marcellus',
            'google_fonts' => 'Marcellus&family=Sora:wght@100..800',
            'preview' => ['#262626', '#9B9A84', '#F9F9F9'],
            'premium' => false,
            'layout' => 'tema-3',
            'audience' => 'hekim',
        ],
        'tema-4' => [
            'ad' => 'tema-4 · Delogis Home 1',
            'aciklama' => 'Tam genişlik hero slider, hakkımda + hizmet ikon kartları. Terracotta palet.',
            'renk' => '#976147',
            'font_sans' => 'Lexend',
            'font_display' => 'Castoro',
            'google_fonts' => 'Lexend:wght@300;400;500;600;700;800&family=Castoro:ital@0;1',
            'preview' => ['#1a1414', '#976147', '#f2edea'],
            'premium' => false,
            'layout' => 'delogis',
            'audience' => 'hekim',
            'home_variant' => 1,
        ],
        'tema-5' => [
            'ad' => 'tema-5 · Delogis Home 2',
            'aciklama' => 'Gölgeli hero slider, görselli hizmet kartları, SSS ve sayaçlar. Altın palet.',
            'renk' => '#B9905D',
            'font_sans' => 'Lexend',
            'font_display' => 'Castoro',
            'google_fonts' => 'Lexend:wght@300;400;500;600;700;800&family=Castoro:ital@0;1',
            'preview' => ['#293B46', '#B9905D', '#F6F2ED'],
            'premium' => false,
            'layout' => 'delogis',
            'audience' => 'hekim',
            'home_variant' => 2,
        ],
        'tema-6' => [
            'ad' => 'tema-6 · Delogis Home 3',
            'aciklama' => 'Yan görselli slider, özellik şeridi, koyu hizmet kartları. Terracotta palet.',
            'renk' => '#976147',
            'font_sans' => 'Lexend',
            'font_display' => 'Castoro',
            'google_fonts' => 'Lexend:wght@300;400;500;600;700;800&family=Castoro:ital@0;1',
            'preview' => ['#1a1414', '#976147', '#f2edea'],
            'premium' => false,
            'layout' => 'delogis',
            'audience' => 'hekim',
            'home_variant' => 3,
        ],
        'tema-7' => [
            'ad' => 'tema-7 · Delogis Home 4',
            'aciklama' => 'Ortalanmış video hero, modern kartlar. Gül palet.',
            'renk' => '#BA5353',
            'font_sans' => 'Lexend',
            'font_display' => 'Castoro',
            'google_fonts' => 'Lexend:wght@300;400;500;600;700;800&family=Castoro:ital@0;1',
            'preview' => ['#2C1C1C', '#BA5353', '#F3E9E9'],
            'premium' => false,
            'layout' => 'delogis',
            'audience' => 'hekim',
            'home_variant' => 4,
        ],
        'tema-8' => [
            'ad' => 'tema-8 · Delogis Home 5',
            'aciklama' => 'Split hero + video popup, dikey hizmet kartları. Altın palet.',
            'renk' => '#B9905D',
            'font_sans' => 'Lexend',
            'font_display' => 'Castoro',
            'google_fonts' => 'Lexend:wght@300;400;500;600;700;800&family=Castoro:ital@0;1',
            'preview' => ['#293B46', '#B9905D', '#F6F2ED'],
            'premium' => false,
            'layout' => 'delogis',
            'audience' => 'hekim',
            'home_variant' => 5,
        ],
        'tema-9' => [
            'ad' => 'tema-9 · Delogis Boxed',
            'aciklama' => 'Home 1 içeriği, ortalanmış kutu (boxed-wrapper) yerleşim.',
            'renk' => '#976147',
            'font_sans' => 'Lexend',
            'font_display' => 'Castoro',
            'google_fonts' => 'Lexend:wght@300;400;500;600;700;800&family=Castoro:ital@0;1',
            'preview' => ['#1a1414', '#976147', '#f2edea'],
            'premium' => false,
            'layout' => 'delogis',
            'audience' => 'hekim',
            'home_variant' => 1,
            'boxed' => true,
        ],
    ],
];
