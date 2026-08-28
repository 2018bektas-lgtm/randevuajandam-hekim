@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $img = $media($ayar['resim'] ?? null) ?: $photo;
    $kucuk = $ayar['kucuk_baslik'] ?? 'Hakkımda';
    $baslik = $ayar['ana_baslik'] ?? $ad;
    $aciklama = $ayar['aciklama'] ?? plain_text($doktor['kisa_bio'] ?? $doktor['bio'] ?? '', 280);
    $maddeler = collect(preg_split("/\r\n|\n|\r/", (string) ($ayar['maddeler'] ?? '')))->map(fn ($s) => trim($s))->filter()->values();
    $deneyim = (int) ($ayar['deneyim_sayi'] ?? 0);
    $deneyimEtiket = $ayar['deneyim_etiket'] ?? "Yıllık\nDeneyim";
    $btn = $ayar['buton_metin'] ?? 'Randevu Al';
@endphp
@if($img || filled($aciklama) || filled($baslik))

@if($v === 1)
<section class="about-one" id="hakkimda">
    <div class="container">
        <div class="row">
            @if($img)
            <div class="col-xl-4 col-lg-5">
                <div class="about-one__left">
                    <div class="about-one__img">
                        <img src="{{ $img }}" alt="{{ $ad }}">
                        <div class="about-one__curved-circle-box">
                            <div class="curved-circle">
                                <span class="curved-circle--item">{{ decode_text($doktor['uzmanlik'] ?? 'Danışmanlık') }}</span>
                            </div>
                            <div class="about-one__curved-circle-icon">
                                <img src="{{ $dg }}/images/icon/about-one-curved-circle-icon.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="{{ $img ? 'col-xl-8 col-lg-7' : 'col-12' }}">
                <div class="about-one__right">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                    <div class="about-one__experience-and-points">
                        @if($deneyim > 0)
                        <div class="about-one__experience-box">
                            <div class="about-one__experience-icon"><span class="icon-experience"></span></div>
                            <div class="about-one__experience count-box">
                                <h3 class="count-text" data-stop="{{ $deneyim }}" data-speed="1500">{{ $deneyim }}</h3>
                                <p>{!! nl2br(e($deneyimEtiket)) !!}</p>
                            </div>
                        </div>
                        @endif
                        <div class="about-one__experience-points-box">
                            @if(filled($aciklama))
                                <p class="about-one__experience-points-text">{{ $aciklama }}</p>
                            @endif
                            @if($maddeler->isNotEmpty())
                            <div class="about-one__experience-points">
                                @foreach ($maddeler->chunk(max(1, (int) ceil($maddeler->count() / 2))) as $chunk)
                                    <ul class="about-one__experience-points-list list-unstyled">
                                        @foreach ($chunk as $m)
                                            <li>
                                                <div class="icon"><span class="fa fa-check"></span></div>
                                                <div class="text"><p>{{ $m }}</p></div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="about-one__sign-and-btn">
                        <div class="about-one__sign">
                            <h5>{{ decode_text($ad) }}</h5>
                            <p>{{ decode_text($doktor['uzmanlik'] ?? '') }}</p>
                        </div>
                        <div class="about-one__btn-box">
                            <a href="{{ route('frontend.randevu') }}" class="about-one__btn thm-btn">{{ $btn }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@elseif($v === 2)
<section class="about-two" id="hakkimda">
    <div class="container">
        <div class="row">
            @if($img)
            <div class="col-xl-5 col-lg-5">
                <div class="about-two__left">
                    <div class="about-two__img-box">
                        <div class="about-two__img"><img src="{{ $img }}" alt="{{ $ad }}"></div>
                    </div>
                </div>
            </div>
            @endif
            <div class="{{ $img ? 'col-xl-7 col-lg-7' : 'col-12' }}">
                <div class="about-two__right">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                    @if(filled($aciklama))<p>{{ $aciklama }}</p>@endif
                    @if($maddeler->isNotEmpty())
                        <ul class="list-unstyled">
                            @foreach ($maddeler as $m)
                                <li><span class="fa fa-check"></span> {{ $m }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ route('frontend.randevu') }}" class="thm-btn">{{ $btn }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

@elseif($v === 4)
<section class="about-five" id="hakkimda">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about-five__content">
                    <div class="section-title section-title--home-four text-left">
                        <span class="section-title__tagline"><span class="section-title__tagline__icon"><i class="delogis-icons-two-brain"></i></span>{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{{ decode_text($baslik) }}</h2>
                    </div>
                    @if(filled($aciklama))<p>{{ $aciklama }}</p>@endif
                    @if($maddeler->isNotEmpty())
                        <ul class="list-unstyled">
                            @foreach ($maddeler as $m)
                                <li><span class="fa fa-check"></span> {{ $m }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ route('frontend.randevu') }}" class="thm-btn thm-btn--two">{{ $btn }}</a>
                </div>
            </div>
            @if($img)
            <div class="col-lg-6">
                <div class="about-five__image"><img src="{{ $img }}" alt="{{ $ad }}"></div>
            </div>
            @endif
        </div>
    </div>
</section>

@elseif($v === 5)
<section class="about-six" id="hakkimda">
    <div class="container">
        <div class="row">
            @if($img)
            <div class="col-lg-6">
                <div class="about-six__image"><img src="{{ $img }}" alt="{{ $ad }}"></div>
            </div>
            @endif
            <div class="{{ $img ? 'col-lg-6' : 'col-12' }}">
                <div class="about-six__content">
                    <div class="section-title section-title--home-five text-left">
                        <span class="section-title__tagline"><span class="section-title__tagline__icon"><i class="delogis-icons-two-brain"></i></span>{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{{ decode_text($baslik) }}</h2>
                    </div>
                    @if(filled($aciklama))<p>{{ $aciklama }}</p>@endif
                    @if($maddeler->isNotEmpty())
                        <ul class="list-unstyled">
                            @foreach ($maddeler as $m)
                                <li><span class="fa fa-check"></span> {{ $m }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ route('frontend.randevu') }}" class="thm-btn thm-btn--two">{{ $btn }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

@else
<section class="about-three" id="hakkimda">
    <div class="container">
        <div class="row">
            <div class="{{ $img ? 'col-xl-7' : 'col-12' }}">
                <div class="about-three__left">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                    @if(filled($aciklama))
                        <p class="about-three__text">{{ $aciklama }}</p>
                    @endif
                    @if($maddeler->isNotEmpty())
                        <div class="about-three__points-box">
                            <ul class="about-three__points-list list-unstyled">
                                @foreach ($maddeler->take(4) as $m)
                                    <li>
                                        <div class="icon"><span class="fa fa-check"></span></div>
                                        <div class="text"><p>{{ $m }}</p></div>
                                    </li>
                                @endforeach
                            </ul>
                            @if($maddeler->count() > 4)
                                <ul class="about-three__points-list list-unstyled">
                                    @foreach ($maddeler->slice(4) as $m)
                                        <li>
                                            <div class="icon"><span class="fa fa-check"></span></div>
                                            <div class="text"><p>{{ $m }}</p></div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                    <div class="about-three__btn-box">
                        <a href="{{ route('frontend.randevu') }}" class="about-three__btn thm-btn">{{ $btn }}</a>
                        <a href="{{ route('frontend.hakkimda') }}" class="thm-btn thm-btn--two" style="margin-left:10px">Hakkımda</a>
                    </div>
                </div>
            </div>
            @if($img)
                <div class="col-xl-5">
                    <div class="about-three__right">
                        <div class="about-three__img-box">
                            <div class="about-three__img"><img src="{{ $img }}" alt="{{ $ad }}"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endif
@endif
