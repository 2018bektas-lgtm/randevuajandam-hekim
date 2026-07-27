@extends(theme_layout())

@section('baslik', trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim').' | '.($doktor['uzmanlik'] ?? 'Klinik')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? $doktor['slogan'] ?? '')

@section('icerik')
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $photo = function_exists('doctor_photo')
        ? doctor_photo($doktor ?? null, null)
        : ($doktor['profil_resmi'] ?? null);
    // Yalnızca panel/API’den gelen gerçek slaytlar (sahte slayt YOK)
    $slider = collect($doktor['slider'] ?? [])
        ->filter(fn ($s) => is_array($s) && (! empty($s['image']) || filled($s['baslik'] ?? null)))
        ->values()
        ->all();
    $hizmetler = collect($doktor['hizmetler'] ?? [])
        ->filter(fn ($h) => is_array($h) && (filled($h['baslik'] ?? null) || filled($h['ad'] ?? null)))
        ->values()
        ->take(6);
    $bloglar = collect($doktor['bloglar'] ?? [])
        ->filter(fn ($b) => is_array($b) && filled($b['baslik'] ?? $b['title'] ?? null))
        ->take(3);
    $yorumlar = collect($doktor['yorumlar'] ?? [])
        ->filter(fn ($y) => is_array($y) && filled($y['yorum'] ?? $y['metin'] ?? $y['content'] ?? null))
        ->take(4);
    $stats = collect($doktor['istatistikler'] ?? [])
        ->filter(fn ($s) => is_array($s) && (int) preg_replace('/\D/', '', (string) ($s['deger'] ?? 0)) > 0)
        ->take(4);
    $ad = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $bolum = $doktor['anasayfa_bolumler'] ?? [];
    $show = fn (string $key) => (bool) ($bolum[$key] ?? true);
    $icons = ['icon-heart', 'icon-self-confidence', 'icon-family', 'icon-account', 'icon-mental-health', 'icon-psychology'];
    $kisaBio = \Illuminate\Support\Str::limit(strip_tags((string) ($doktor['kisa_bio'] ?? $doktor['bio'] ?? $doktor['biyografi'] ?? $doktor['slogan'] ?? '')), 280);
    $mezuniyet = collect($doktor['mezuniyet'] ?? [])->filter()->take(8)->values();
@endphp

{{-- 1) Slider: sadece panelde slayt varsa (index3 main-slider-three) --}}
@if($show('slider') && count($slider) > 0)
<section class="main-slider-three">
    <div class="main-slider__carousel owl-carousel owl-theme thm-owl__carousel"
         data-owl-options='{"loop": {{ count($slider) > 1 ? 'true' : 'false' }}, "items": 1, "navText": ["<span class=\"icon-left-arrow\"></span>","<span class=\"icon-right-arrow\"></span>"], "margin": 0, "dots": false, "nav": true, "animateOut": "fadeOut", "animateIn": "fadeIn", "active": true, "smartSpeed": 1000, "autoplay": {{ count($slider) > 1 ? 'true' : 'false' }}, "autoplayTimeout": 7000, "autoplayHoverPause": false}'>
        @foreach ($slider as $i => $slide)
            @php
                $img = $slide['image'] ?? $slide['thumb'] ?? $photo;
                $title = $slide['baslik'] ?? $ad;
                $vurgulu = $slide['baslik_vurgulu'] ?? null;
                $etiket = $slide['etiket'] ?? ($slide['badge'] ?? ($doktor['vitrin_badge'] ?? $doktor['uzmanlik'] ?? null));
                $cta = $slide['cta'] ?? 'Randevu Al';
                $ctaUrl = $slide['cta_url'] ?? route('frontend.randevu');
                if ($ctaUrl === '/randevu') { $ctaUrl = route('frontend.randevu'); }
                $bg = $slide['bg'] ?? ($dg.'/images/backgrounds/main-slider-three-bg.jpg');
            @endphp
            <div class="item main-slider-three__slide-{{ ($i % 3) + 1 }}">
                <div class="main-slider-three__bg" style="background-image: url({{ $bg }});"></div>
                <div class="main-slider-three__shape-3 img-bounce">
                    <img src="{{ $dg }}/images/shapes/main-slider-three-shape-3.png" alt="">
                </div>
                @if($img)
                    <div class="main-slider-three__img">
                        <img src="{{ $img }}" alt="{{ $title }}">
                    </div>
                @endif
                <div class="main-slider-three__star-one zoominout">
                    <img src="{{ $dg }}/images/shapes/main-slider-three-star-1.png" alt="">
                </div>
                <div class="main-slider-three__star-two img-bounce">
                    <img src="{{ $dg }}/images/shapes/main-slider-three-star-2.png" alt="">
                </div>
                <div class="container">
                    <div class="main-slider-three__content">
                        @if($etiket)
                            <div class="main-slider-three__sub-title-box">
                                <div class="main-slider-three__shape-1" style="background-image: url({{ $dg }}/images/shapes/main-slider-three-shape-1.png);"></div>
                                <p class="main-slider-three__sub-title">{{ $etiket }}</p>
                            </div>
                        @endif
                        <h2 class="main-slider-three__title">
                            {{ $title }}
                            @if($vurgulu)
                                <br><span>{{ $vurgulu }}</span>
                            @endif
                        </h2>
                        <div class="main-slider-three__btn-founder-box">
                            <a href="{{ $ctaUrl }}" class="main-slider-two__btn-one thm-btn">{{ $cta }}</a>
                            <div class="main-slider-three__founder-box">
                                <h4 class="main-slider-three__founder-name">{{ $ad }}</h4>
                                <p class="main-slider-three__founder-sub-title">{{ $doktor['uzmanlik'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- 2) Hizmet vitrin kartları (feature-two) — sadece hizmet varsa --}}
@if($show('hizmetler') && $hizmetler->isNotEmpty())
<section class="feature-two">
    <div class="container">
        <div class="row">
            @foreach ($hizmetler->take(3) as $idx => $h)
                @php
                    $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                    $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                    $hDesc = \Illuminate\Support\Str::limit(strip_tags((string)($h['kisa'] ?? $h['aciklama'] ?? '')), 90);
                    $hImg = $h['image'] ?? $h['resim'] ?? null;
                    $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                @endphp
                <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="{{ ($idx + 1) * 100 }}ms">
                    <div class="feature-two__single">
                        <div class="feature-two__img-box">
                            @if($hImg)
                                <div class="feature-two__img">
                                    <img src="{{ $hImg }}" alt="{{ $hAd }}">
                                </div>
                            @else
                                <div class="feature-two__img feature-two__img--placeholder" style="min-height:260px;background:var(--delogis-extra,#F6F2ED);display:flex;align-items:center;justify-content:center">
                                    <span class="{{ $icons[$idx % count($icons)] }}" style="font-size:48px;color:var(--delogis-base,#B9905D)"></span>
                                </div>
                            @endif
                            <div class="feature-two__title-box">
                                <h3><a href="{{ $href }}">{{ $hAd }}</a></h3>
                                <div class="feature-two__icon">
                                    <span class="{{ $icons[$idx % count($icons)] }}"></span>
                                </div>
                            </div>
                            <div class="feature-two__hover-title-box">
                                <h3><a href="{{ $href }}">{{ $hAd }}</a></h3>
                                <p class="feature-two__hover-text">{{ $hDesc !== '' ? $hDesc : 'Detay ve randevu için tıklayın.' }}</p>
                                <div class="feature-two__hover-icon">
                                    <span class="{{ $icons[$idx % count($icons)] }}"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 3) About three (index3) — foto veya metin varsa --}}
@if($show('hakkimda') && ($photo || $kisaBio !== ''))
<section class="about-three">
    <div class="container">
        <div class="row">
            <div class="col-xl-7">
                <div class="about-three__left">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ $doktor['uzmanlik'] ?? 'Hoş geldiniz' }}</span>
                        <h2 class="section-title__title">{{ $doktor['bolum_basliklar']['hakkimda']['baslik'] ?? $ad }}</h2>
                    </div>
                    @if($kisaBio !== '')
                        <p class="about-three__text">{{ $kisaBio }}</p>
                    @endif
                    @if($mezuniyet->isNotEmpty())
                        <div class="about-three__points-box">
                            <ul class="about-three__points-list list-unstyled">
                                @foreach ($mezuniyet->take(4) as $m)
                                    <li>
                                        <div class="icon"><span class="fa fa-check"></span></div>
                                        <div class="text"><p>{{ is_string($m) ? $m : \Illuminate\Support\Str::limit(strip_tags((string)$m), 80) }}</p></div>
                                    </li>
                                @endforeach
                            </ul>
                            @if($mezuniyet->count() > 4)
                                <ul class="about-three__points-list list-unstyled">
                                    @foreach ($mezuniyet->slice(4) as $m)
                                        <li>
                                            <div class="icon"><span class="fa fa-check"></span></div>
                                            <div class="text"><p>{{ is_string($m) ? $m : \Illuminate\Support\Str::limit(strip_tags((string)$m), 80) }}</p></div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                    <div class="about-three__btn-box">
                        <a href="{{ route('frontend.randevu') }}" class="about-three__btn thm-btn">Randevu Al</a>
                        <a href="{{ route('frontend.hakkimda') }}" class="thm-btn thm-btn--two" style="margin-left:10px">Hakkımda</a>
                    </div>
                </div>
            </div>
            @if($photo)
                <div class="col-xl-5">
                    <div class="about-three__right">
                        <div class="about-three__img-box">
                            <div class="about-three__img">
                                <img src="{{ $photo }}" alt="{{ $ad }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- 4) Services three --}}
@if($show('hizmetler') && $hizmetler->isNotEmpty())
<section class="services-three" id="hizmetler">
    <div class="services-three__bg-box">
        <div class="services-three__bg" style="background-image: url({{ $dg }}/images/backgrounds/services-three-bg.png);"></div>
    </div>
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">Hizmetler</span>
            <h2 class="section-title__title">{{ filled($doktor['hizmetler_baslik'] ?? null) ? $doktor['hizmetler_baslik'] : 'Sunduğumuz hizmetler' }}</h2>
        </div>
        <div class="row">
            @foreach ($hizmetler as $idx => $h)
                @php
                    $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                    $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                    $hDesc = \Illuminate\Support\Str::limit(strip_tags((string)($h['kisa'] ?? $h['aciklama'] ?? '')), 100);
                    $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                @endphp
                <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ ($idx % 3 + 1) * 100 }}ms">
                    <div class="services-three__single">
                        <div class="services-three__icon">
                            <span class="{{ $icons[$idx % count($icons)] }}"></span>
                        </div>
                        <h3 class="services-three__title"><a href="{{ $href }}">{{ $hAd }}</a></h3>
                        <p class="services-three__text">{{ $hDesc !== '' ? $hDesc : 'Detay için tıklayın.' }}</p>
                        <div class="services-three__btn-box">
                            <a href="{{ $href }}"><span class="icon-right-arrow"></span> İncele</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center" style="margin-top:30px">
            <a href="{{ route('frontend.hizmetler') }}" class="thm-btn">Tüm hizmetler</a>
        </div>
    </div>
</section>
@endif

{{-- 5) Counter — sadece gerçek istatistik --}}
@if($show('istatistik') && $stats->isNotEmpty())
<section class="counter-two">
    <div class="container">
        <div class="row">
            @foreach ($stats as $st)
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="counter-two__single">
                        <div class="counter-two__count-box">
                            <h3 class="odometer" data-count="{{ (int) preg_replace('/\D/', '', (string)($st['deger'] ?? 0)) }}">00</h3>
                            <span class="counter-two__plus">{{ $st['suffix'] ?? '' }}</span>
                        </div>
                        <p class="counter-two__text">{{ $st['etiket'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 6) Testimonials --}}
@if($show('yorumlar') && $yorumlar->isNotEmpty())
<section class="testimonial-three">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">Yorumlar</span>
            <h2 class="section-title__title">Danışan deneyimleri</h2>
        </div>
        <div class="row">
            @foreach ($yorumlar as $y)
                <div class="col-xl-6 col-lg-6">
                    <div class="testimonial-three__single" style="margin-bottom:24px">
                        <p class="testimonial-three__text">“{{ \Illuminate\Support\Str::limit(strip_tags((string)($y['yorum'] ?? $y['metin'] ?? $y['content'] ?? '')), 180) }}”</p>
                        <div class="testimonial-three__client-info">
                            <h4 class="testimonial-three__client-name">{{ $y['hasta_adi'] ?? $y['ad'] ?? 'Danışan' }}</h4>
                            <p class="testimonial-three__client-sub-title">
                                @if(!empty($y['puan'])) {{ $y['puan'] }}/5 · @endif
                                Değerlendirme
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 7) Blog — sadece kapak/başlık olan yazılar; sahte blog görseli yok --}}
@if($show('blog') && $bloglar->isNotEmpty())
<section class="blog-two">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">Blog</span>
            <h2 class="section-title__title">Son yazılar</h2>
        </div>
        <div class="row">
            @foreach ($bloglar as $b)
                @php
                    $bTitle = $b['baslik'] ?? $b['title'] ?? 'Yazı';
                    $bSlug = $b['slug'] ?? \Illuminate\Support\Str::slug($bTitle);
                    $bImg = $b['image'] ?? $b['kapak'] ?? $b['resim'] ?? null;
                    $href = route('frontend.blog.detay', $bSlug);
                @endphp
                <div class="col-xl-4 col-lg-4 wow fadeInUp">
                    <div class="blog-two__single">
                        @if($bImg)
                            <div class="blog-two__img">
                                <img src="{{ $bImg }}" alt="{{ $bTitle }}">
                                <a href="{{ $href }}"><span class="blog-two__plus"></span></a>
                            </div>
                        @endif
                        <div class="blog-two__content">
                            <h3 class="blog-two__title"><a href="{{ $href }}">{{ $bTitle }}</a></h3>
                            <p class="blog-two__text">{{ \Illuminate\Support\Str::limit(strip_tags((string)($b['ozet'] ?? $b['icerik'] ?? '')), 100) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center" style="margin-top:24px">
            <a href="{{ route('frontend.blog') }}" class="thm-btn">Tüm yazılar</a>
        </div>
    </div>
</section>
@endif

{{-- 8) CTA --}}
@if($show('cta'))
<section class="cta-one">
    <div class="cta-one__shape-1 float-bob-x">
        <img src="{{ $dg }}/images/shapes/cta-one-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="cta-one__inner">
            <p class="cta-one__text">Size uygun saatte online randevu alın</p>
            <div class="cta-one__btn-box">
                <a href="{{ route('frontend.randevu') }}" class="cta-one__btn thm-btn">Randevu Al</a>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
