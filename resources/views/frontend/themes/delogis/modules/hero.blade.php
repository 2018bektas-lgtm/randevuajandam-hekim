@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $ayarSlayt = collect($ayar['slaytlar'] ?? [])
        ->filter(fn ($s) => is_array($s) && (! empty($s['resim']) || ! empty($s['ikon']) || filled($s['baslik'] ?? null)))
        ->values();
    $dbSlider = collect($doktor['slider'] ?? [])
        ->filter(fn ($s) => is_array($s) && (! empty($s['image']) || filled($s['baslik'] ?? null)))
        ->values();

    $slides = [];
    if ($ayarSlayt->isNotEmpty()) {
        foreach ($ayarSlayt as $s) {
            $slides[] = [
                'image' => $media($s['resim'] ?? $s['ikon'] ?? null),
                'baslik' => $s['baslik'] ?? ($ayar['ana_baslik'] ?? $ad),
                'metin' => $s['metin'] ?? ($ayar['aciklama'] ?? ''),
            ];
        }
    } elseif ($dbSlider->isNotEmpty()) {
        foreach ($dbSlider as $s) {
            $slides[] = [
                'image' => $s['image'] ?? $s['thumb'] ?? null,
                'baslik' => $s['baslik'] ?? ($ayar['ana_baslik'] ?? $ad),
                'metin' => $s['aciklama'] ?? $s['metin'] ?? ($ayar['aciklama'] ?? ''),
            ];
        }
    } else {
        $slides[] = [
            'image' => $media($ayar['arkaplan_resmi'] ?? null) ?: $photo,
            'baslik' => $ayar['ana_baslik'] ?? $ad,
            'metin' => $ayar['aciklama'] ?? '',
        ];
    }

    $ust = $ayar['ust_baslik'] ?? 'Kaliteli terapi burada başlar';
    $cta = $ayar['cta_metin'] ?? 'Randevu Al';
    $cta2 = $ayar['cta2_metin'] ?? 'Hakkımda';
    $yt = trim((string) ($ayar['youtube_id'] ?? ''));
    $defaults = [
        1 => $dg.'/images/backgrounds/slider-1-1.jpg',
        2 => $dg.'/images/backgrounds/slider-2-1.jpg',
        3 => $dg.'/images/backgrounds/main-slider-three-bg.jpg',
        4 => $dg.'/images/backgrounds/slider-4-1.jpg',
        5 => $dg.'/images/backgrounds/slider-5-1.jpg',
    ];
    $fallbackBg = $defaults[$v] ?? $defaults[1];
@endphp

@include('frontend.themes.delogis.modules.partials.hero-'.$v, compact('slides', 'ust', 'cta', 'cta2', 'yt', 'fallbackBg', 'dg', 'ad', 'photo', 'titleHtml', 'doktor', 'ayar'))
