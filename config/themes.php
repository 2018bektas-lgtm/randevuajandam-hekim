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
 *   - (tema-4..9 sonraki sprint'lerde: Delogis Home 01..05 + Home Boxed)
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
    ],
];
