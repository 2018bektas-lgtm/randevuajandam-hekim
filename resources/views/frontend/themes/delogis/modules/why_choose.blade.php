@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $kucuk = $ayar['kucuk_baslik'] ?? 'Neden ben?';
    $baslik = $ayar['ana_baslik'] ?? 'Farkımı yaratan yaklaşımlar';
    $aciklama = $ayar['aciklama'] ?? '';
    $btn = $ayar['buton_metin'] ?? 'Hakkımda';
    $img = $media($ayar['resim'] ?? null) ?: $photo;
    $sebepler = collect($ayar['sebepler'] ?? [])->filter(fn ($s) => is_array($s) && filled($s['baslik'] ?? null))->values();
@endphp
@if($sebepler->isNotEmpty() || filled($baslik))

@if($v === 1)
<section class="why-choose-one">
    <div class="container">
        <div class="row">
            <div class="col-xl-4">
                <div class="why-choose-one__left">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="why-choose-one__right">
                    <ul class="why-choose-one__list list-unstyled">
                        @foreach ($sebepler->take(3) as $idx => $s)
                            @php
                                $ikon = $s['ikon'] ?? $icons[$idx % count($icons)];
                                if ($ikon && str_starts_with((string) $ikon, 'fa-')) {
                                    $ikon = $icons[$idx % count($icons)];
                                }
                            @endphp
                            <li>
                                <div class="why-choose-one__single">
                                    <div class="icon"><span class="{{ $ikon }}"></span></div>
                                    <div class="title"><h3><a href="{{ route('frontend.hakkimda') }}">{{ decode_text($s['baslik']) }}</a></h3></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@elseif($v === 2)
<section class="why-choose-two">
    <div class="why-choose-two__bg" style="background-image: url({{ $dg }}/images/backgrounds/why-choose-two-bg.png);"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="why-choose-two__left">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                    @if(filled($aciklama))<p>{{ decode_text($aciklama) }}</p>@endif
                    <ul class="list-unstyled">
                        @foreach ($sebepler as $s)
                            <li><span class="fa fa-check"></span> {{ decode_text($s['baslik']) }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('frontend.hakkimda') }}" class="thm-btn">{{ $btn }}</a>
                </div>
            </div>
            @if($img)
            <div class="col-xl-6">
                <div class="why-choose-two__right">
                    <div class="why-choose-two__img"><img src="{{ $img }}" alt="{{ $ad }}"></div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@elseif($v === 5)
<section class="why-choose-five">
    <div class="container">
        <div class="row">
            @if($img)
            <div class="col-lg-6">
                <div class="why-choose-five__image wow slideInLeft" data-wow-delay="100ms">
                    <img src="{{ $img }}" alt="{{ $ad }}">
                    @if($tel)
                    <div class="why-choose-five__info wow fadeInUp" data-wow-delay="200ms">
                        <div class="why-choose-five__info__icon"><span class="delogis-icons-two-phone-call"></span></div>
                        <span class="why-choose-five__info__title">Telefon</span>
                        <h5 class="why-choose-five__info__text"><a href="tel:{{ $telRaw }}">{{ $tel }}</a></h5>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            <div class="{{ $img ? 'col-lg-6' : 'col-12' }} wow fadeInUp">
                <div class="why-choose-five__content">
                    <div class="section-title section-title--home-five text-left">
                        <span class="section-title__tagline"><span class="section-title__tagline__icon"><i class="delogis-icons-two-brain"></i></span>{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{{ decode_text($baslik) }}</h2>
                    </div>
                    <ul class="why-choose-five__box list-unstyled">
                        @foreach ($sebepler->take(2) as $idx => $s)
                            @php $ikon = $s['ikon'] ?? $iconsTwo[$idx % count($iconsTwo)]; @endphp
                            <li>
                                <div class="why-choose-five__box__icon"><span class="{{ $ikon }}"></span></div>
                                <h3 class="why-choose-five__box__title">{{ decode_text($s['baslik']) }}</h3>
                            </li>
                        @endforeach
                    </ul>
                    @if(filled($aciklama))
                        <p class="why-choose-five__content__text">{{ decode_text($aciklama) }}</p>
                    @endif
                    <a href="{{ route('frontend.hakkimda') }}" class="thm-btn thm-btn--two">{{ $btn }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

@elseif($v === 4)
<section class="why-choose-four">
    <div class="container">
        <div class="row">
            <div class="{{ $img ? 'col-lg-6' : 'col-12' }}">
                <div class="section-title section-title--home-four text-left">
                    <span class="section-title__tagline"><span class="section-title__tagline__icon"><i class="delogis-icons-two-brain"></i></span>{{ decode_text($kucuk) }}</span>
                    <h2 class="section-title__title">{{ decode_text($baslik) }}</h2>
                </div>
                @if(filled($aciklama))<p>{{ decode_text($aciklama) }}</p>@endif
                <ul class="list-unstyled">
                    @foreach ($sebepler as $s)
                        <li><span class="fa fa-check"></span> {{ decode_text($s['baslik']) }}@if(!empty($s['metin'])) — {{ decode_text($s['metin']) }}@endif</li>
                    @endforeach
                </ul>
                <a href="{{ route('frontend.hakkimda') }}" class="thm-btn thm-btn--two">{{ $btn }}</a>
            </div>
            @if($img)
            <div class="col-lg-6">
                <img src="{{ $img }}" alt="{{ $ad }}">
            </div>
            @endif
        </div>
    </div>
</section>

@else
<section class="why-choose-three">
    <div class="container">
        <div class="row">
            @if($img)
            <div class="col-xl-6">
                <div class="why-choose-three__left">
                    <div class="why-choose-three__img-box">
                        <div class="why-choose-three__img"><img src="{{ $img }}" alt="{{ $ad }}"></div>
                    </div>
                </div>
            </div>
            @endif
            <div class="{{ $img ? 'col-xl-6' : 'col-12' }}">
                <div class="why-choose-three__right">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                    @if(filled($aciklama))<p>{{ decode_text($aciklama) }}</p>@endif
                    <ul class="list-unstyled">
                        @foreach ($sebepler as $s)
                            <li>
                                <div class="icon"><span class="fa fa-check"></span></div>
                                <div class="text">
                                    <h4>{{ decode_text($s['baslik']) }}</h4>
                                    @if(!empty($s['metin']))<p>{{ decode_text($s['metin']) }}</p>@endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('frontend.hakkimda') }}" class="thm-btn">{{ $btn }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endif
