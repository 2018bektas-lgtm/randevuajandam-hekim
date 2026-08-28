@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $kartlar = collect($ayar['kartlar'] ?? [])->filter(fn ($k) => is_array($k) && filled($k['baslik'] ?? null))->take(3)->values();
    if ($kartlar->isEmpty()) {
        $kartlar = collect($doktor['ozellikler'] ?? [])->filter(fn ($o) => is_array($o) && filled($o['baslik'] ?? null))->take(3)->values()
            ->map(fn ($o, $i) => [
                'ikon' => $icons[$i % count($icons)],
                'baslik' => $o['baslik'],
                'metin' => $o['aciklama'] ?? $o['metin'] ?? '',
                'resim' => $o['image'] ?? $o['resim'] ?? null,
            ]);
    }
@endphp
@if($kartlar->isNotEmpty())

@if($v === 1)
<section class="feature-one">
    <div class="container">
        <div class="row">
            @foreach ($kartlar as $idx => $k)
                @php
                    $href = route('frontend.hizmetler');
                    $kImg = $media($k['resim'] ?? null) ?: $dg.'/images/resources/feature-1-'.(($idx % 3) + 1).'.jpg';
                    $ikon = $k['ikon'] ?? $icons[$idx % count($icons)];
                    if ($ikon && ! str_starts_with($ikon, 'icon-') && ! str_starts_with($ikon, 'fa') && ! str_starts_with($ikon, 'delogis')) {
                        $ikon = $icons[$idx % count($icons)];
                    }
                @endphp
                <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="{{ ($idx + 1) * 100 }}ms">
                    <div class="feature-one__single">
                        <div class="feature-one__img-box">
                            <div class="feature-one__img"><img src="{{ $kImg }}" alt="{{ $k['baslik'] }}"></div>
                            <div class="feature-one__hover-content">
                                <div class="feature-one__hover-icon"><span class="{{ $ikon }}"></span></div>
                                <div class="feature-one__hover-content-inner">
                                    <div class="feature-one__shape-1">
                                        <img src="{{ $dg }}/images/shapes/feature-one-shape-1.png" alt="">
                                    </div>
                                    <h3 class="feature-one__hover-title"><a href="{{ $href }}">{{ decode_text($k['baslik']) }}</a></h3>
                                    @if(!empty($k['metin']))
                                        <p class="feature-one__hover-text">{{ decode_text($k['metin']) }}</p>
                                    @endif
                                </div>
                                <div class="feature-one__hover-arrow-box">
                                    <a href="{{ $href }}" class="feature-one__hover-arrow"><span class="icon-right-arrow"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="feature-one__content">
                            <div class="feature-one__icon"><span class="{{ $ikon }}"></span></div>
                            <h3 class="feature-one__title"><a href="{{ $href }}">{{ decode_text($k['baslik']) }}</a></h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@elseif($v === 5)
<section class="feature-six">
    <div class="container">
        <div class="row gutter-y-30">
            @foreach ($kartlar as $idx => $k)
                @php $ikon = $k['ikon'] ?? $iconsTwo[$idx % count($iconsTwo)]; @endphp
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="{{ $idx * 100 }}ms">
                    <div class="feature-six__single">
                        <div class="feature-six__single__icon"><span class="{{ $ikon }}"></span></div>
                        <h3 class="feature-six__single__title">{{ decode_text($k['baslik']) }}</h3>
                        @if(!empty($k['metin']))
                            <p>{{ decode_text($k['metin']) }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@else
<section class="feature-two">
    <div class="container">
        <div class="row">
            @foreach ($kartlar as $idx => $k)
                @php
                    $kImg = $media($k['resim'] ?? null) ?: $dg.'/images/resources/feature-2-'.(($idx % 3) + 1).'.jpg';
                    $ikon = $k['ikon'] ?? $icons[$idx % count($icons)];
                @endphp
                <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="{{ ($idx + 1) * 100 }}ms">
                    <div class="feature-two__single">
                        <div class="feature-two__img"><img src="{{ $kImg }}" alt="{{ $k['baslik'] }}"></div>
                        <div class="feature-two__content">
                            <div class="feature-two__icon"><span class="{{ $ikon }}"></span></div>
                            <h3 class="feature-two__title">{{ decode_text($k['baslik']) }}</h3>
                            @if(!empty($k['metin']))
                                <p>{{ decode_text($k['metin']) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif
