@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $limit = max(1, (int) ($ayar['hizmet_limiti'] ?? 4));
    $hizmetler = collect($doktor['hizmetler'] ?? [])
        ->filter(fn ($h) => is_array($h) && (filled($h['baslik'] ?? null) || filled($h['ad'] ?? null)))
        ->take($limit)
        ->values();
    $kucuk = $ayar['kucuk_baslik'] ?? 'Hizmetler';
    $baslik = $ayar['ana_baslik'] ?? ($doktor['hizmetler_baslik'] ?? 'Sunduğum hizmetler');
    $aciklama = $ayar['aciklama'] ?? ($doktor['hizmetler_alt'] ?? '');
    $btn = $ayar['buton_metin'] ?? 'Tüm hizmetler';
@endphp
@if($hizmetler->isNotEmpty())

@if($v === 1)
<section class="services-one" id="hizmetler">
    <div class="services-one__bg" style="background-image: url({{ $dg }}/images/backgrounds/services-one-bg.png);"></div>
    <div class="container">
        <div class="services-one__top">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                </div>
                @if(filled($aciklama))
                <div class="col-xl-6 col-lg-6">
                    <p class="services-one__right-text">{{ decode_text($aciklama) }}</p>
                </div>
                @endif
            </div>
        </div>
        <div class="services-one__bottom">
            <div class="row">
                @foreach ($hizmetler as $idx => $h)
                    @php
                        $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                        $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                        $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                        $ikon = $h['ikon'] ?? $icons[$idx % count($icons)];
                    @endphp
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="{{ ($idx + 1) * 100 }}ms">
                        <div class="services-one__single">
                            <div class="services-one__content">
                                <div class="services-one__icon"><span class="{{ $ikon }}"></span></div>
                                <h3 class="services-one__title"><a href="{{ $href }}">{{ decode_text($hAd) }}</a></h3>
                                <p class="services-one__text">{{ plain_text($h['aciklama'] ?? $h['ozet'] ?? '', 90) }}</p>
                            </div>
                            <div class="services-one__btn-box">
                                <a href="{{ $href }}" class="services-one__btn"><span class="icon-right-arrow"></span>İncele</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="text-center" style="margin-top:30px">
            <a href="{{ route('frontend.hizmetler') }}" class="thm-btn">{{ $btn }}</a>
        </div>
    </div>
</section>

@elseif($v === 2)
<section class="services-two" id="hizmetler">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
            <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
        </div>
        <div class="row">
            @foreach ($hizmetler as $idx => $h)
                @php
                    $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                    $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                    $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                    $hImg = $media($h['image'] ?? $h['resim'] ?? $h['kapak'] ?? null) ?: $dg.'/images/services/services-2-'.(($idx % 6) + 1).'.jpg';
                    $ikon = $h['ikon'] ?? $icons[$idx % count($icons)];
                @endphp
                <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="{{ ($idx + 1) * 100 }}ms">
                    <div class="services-two__single">
                        <div class="services-two__img-box">
                            <div class="services-two__img"><img src="{{ $hImg }}" alt="{{ $hAd }}"></div>
                            <div class="services-two__icon"><span class="{{ $ikon }}"></span></div>
                        </div>
                        <div class="services-two__content">
                            <div class="services-two__title-box">
                                <h3 class="services-two__title"><a href="{{ $href }}">{{ decode_text($hAd) }}</a></h3>
                                <p class="services-two__text">{{ plain_text($h['aciklama'] ?? $h['ozet'] ?? '', 90) }}</p>
                            </div>
                            <div class="services-two__btn-box">
                                <a href="{{ $href }}"><span class="icon-right-arrow"></span>İncele</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center" style="margin-top:30px">
            <a href="{{ route('frontend.hizmetler') }}" class="thm-btn">{{ $btn }}</a>
        </div>
    </div>
</section>

@elseif($v === 4)
<section class="services-four" id="hizmetler">
    <div class="container">
        <div class="section-title section-title--home-four text-center">
            <span class="section-title__tagline"><span class="section-title__tagline__icon"><i class="delogis-icons-two-brain"></i></span>{{ decode_text($kucuk) }}</span>
            <h2 class="section-title__title">{{ decode_text($baslik) }}</h2>
        </div>
        <div class="row gutter-y-30">
            @foreach ($hizmetler as $idx => $h)
                @php
                    $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                    $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                    $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                    $hImg = $media($h['image'] ?? $h['resim'] ?? $h['kapak'] ?? null) ?: $dg.'/images/services/services-4-'.(($idx % 6) + 1).'.jpg';
                    $ikon = $h['ikon'] ?? $iconsTwo[$idx % count($iconsTwo)];
                @endphp
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ $idx * 100 }}ms">
                    <div class="services-four__single">
                        <div class="services-four__single__content">
                            <div class="services-four__single__shape"></div>
                            <div class="services-four__single__icon"><span class="{{ $ikon }}"></span></div>
                            <h3 class="services-four__single__title"><a href="{{ $href }}">{{ decode_text($hAd) }}</a></h3>
                            <p class="services-four__single__text">{{ plain_text($h['aciklama'] ?? $h['ozet'] ?? '', 110) }}</p>
                            <a href="{{ $href }}" class="services-four__single__rm">İncele</a>
                        </div>
                        <div class="services-four__single__bottom">
                            <div class="services-four__single__img"><img src="{{ $hImg }}" alt="{{ $hAd }}"></div>
                            <a href="{{ $href }}" class="services-four__single__btn"><i class="delogis-icons-two-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@elseif($v === 5)
<section class="services-five" id="hizmetler">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-title section-title--home-five text-left">
                    <span class="section-title__tagline"><span class="section-title__tagline__icon"><i class="delogis-icons-two-brain"></i></span>{{ decode_text($kucuk) }}</span>
                    <h2 class="section-title__title">{{ decode_text($baslik) }}</h2>
                </div>
            </div>
            @if(filled($aciklama))
            <div class="col-lg-6"><p class="services-five__text">{{ decode_text($aciklama) }}</p></div>
            @endif
        </div>
        <div class="row gutter-y-30">
            @foreach ($hizmetler as $idx => $h)
                @php
                    $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                    $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                    $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                    $hImg = $media($h['image'] ?? $h['resim'] ?? $h['kapak'] ?? null) ?: $dg.'/images/services/services-5-'.(($idx % 4) + 1).'.jpg';
                    $ikon = $h['ikon'] ?? $iconsTwo[$idx % count($iconsTwo)];
                @endphp
                <div class="col-xl-3 col-md-6 wow fadeInUp" data-wow-delay="{{ $idx * 100 }}ms">
                    <div class="services-five__single">
                        <div class="services-five__single__content">
                            <div class="services-five__single__icon"><span class="{{ $ikon }}"></span></div>
                            <h3 class="services-five__single__title"><a href="{{ $href }}">{{ decode_text($hAd) }}</a></h3>
                            <p class="services-five__single__text">{{ plain_text($h['aciklama'] ?? $h['ozet'] ?? '', 90) }}</p>
                        </div>
                        <div class="services-five__single__img">
                            <img src="{{ $hImg }}" alt="{{ $hAd }}">
                            <a href="{{ $href }}" class="services-five__single__btn">
                                <span class="services-five__single__btn__front"><span class="delogis-icons-two-right-arrow"></span></span>
                                <span class="services-five__single__btn__back"><span class="delogis-icons-two-right-arrow"></span>İncele</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@else
<section class="services-three" id="hizmetler">
    <div class="services-three__bg-box">
        <div class="services-three__bg" style="background-image: url({{ $dg }}/images/backgrounds/services-three-bg.png);"></div>
    </div>
    <div class="container">
        <div class="services-three__top">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                </div>
                @if(filled($aciklama))
                <div class="col-xl-6 col-lg-6">
                    <p class="services-three__text">{{ decode_text($aciklama) }}</p>
                </div>
                @endif
            </div>
        </div>
        <div class="services-three__bottom">
            <div class="row">
                @foreach ($hizmetler as $idx => $h)
                    @php
                        $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                        $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                        $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                        $hImg = $media($h['image'] ?? $h['resim'] ?? $h['kapak'] ?? null) ?: $dg.'/images/services/services-3-'.(($idx % 4) + 1).'.jpg';
                        $ikon = $h['ikon'] ?? $icons[$idx % count($icons)];
                    @endphp
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="{{ ($idx + 1) * 100 }}ms">
                        <div class="services-three__single">
                            <div class="services-three__img-box">
                                <div class="services-three__img"><img src="{{ $hImg }}" alt="{{ $hAd }}"></div>
                                <div class="services-three__icon"><span class="{{ $ikon }}"></span></div>
                            </div>
                            <div class="services-three__content">
                                <div class="services-three__content-inner">
                                    <div class="services-three__content-top">
                                        <h3 class="services-three__title"><a href="{{ $href }}">{{ decode_text($hAd) }}</a></h3>
                                        <p class="services-three__text">{{ plain_text($h['aciklama'] ?? $h['ozet'] ?? '', 90) }}</p>
                                    </div>
                                    <div class="services-three__btn-box">
                                        <a href="{{ $href }}" class="services-three__btn"><span class="icon-right-arrow"></span>İncele</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="text-center" style="margin-top:30px">
            <a href="{{ route('frontend.hizmetler') }}" class="thm-btn">{{ $btn }}</a>
        </div>
    </div>
</section>
@endif
@endif
