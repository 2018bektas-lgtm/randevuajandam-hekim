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
    $ad = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $bolum = $doktor['anasayfa_bolumler'] ?? [];
    $show = fn (string $key) => (bool) ($bolum[$key] ?? true);
    $icons = ['icon-heart', 'icon-self-confidence', 'icon-family', 'icon-account', 'icon-mental-health', 'icon-psychology'];
    $kisaBio = plain_text($doktor['kisa_bio'] ?? $doktor['bio'] ?? $doktor['biyografi'] ?? $doktor['slogan'] ?? '', 280);
    $mezuniyet = collect($doktor['mezuniyet'] ?? [])->filter()->take(8)->values();
@endphp

{{-- 1) Slider: sadece panelde slayt varsa (index3 main-slider-three) --}}
@if($show('slider') && count($slider) > 0)
<section class="main-slider-three">
    <div class="main-slider__carousel owl-carousel owl-theme thm-owl__carousel"
         data-owl-options='{"loop": {{ count($slider) > 1 ? 'true' : 'false' }}, "items": 1, "navText": ["<span class=\"icon-left-arrow\"></span>","<span class=\"icon-right-arrow\"></span>"], "margin": 0, "dots": false, "nav": true, "animateOut": "fadeOut", "animateIn": "fadeIn", "active": true, "smartSpeed": 1000, "autoplay": {{ count($slider) > 1 ? 'true' : 'false' }}, "autoplayTimeout": 7000, "autoplayHoverPause": false}'>
        @foreach ($slider as $i => $slide)
            @php
                // index3 HTML birebir: bg tema dekoratif, image sağda __img (tema CSS)
                $bg = $dg.'/images/backgrounds/main-slider-three-bg.jpg';
                $sideImg = $slide['image'] ?? $slide['thumb'] ?? null;
                $title = decode_text($slide['baslik'] ?? $ad);
                $vurgulu = decode_text($slide['baslik_vurgulu'] ?? '');
                $etiket = decode_text($slide['etiket'] ?? ($slide['badge'] ?? ($doktor['vitrin_badge'] ?? $doktor['uzmanlik'] ?? '')));
                $cta = decode_text($slide['cta'] ?? 'Randevu Al');
                $ctaUrl = $slide['cta_url'] ?? route('frontend.randevu');
                if ($ctaUrl === '/randevu') { $ctaUrl = route('frontend.randevu'); }
            @endphp
            <div class="item main-slider-three__slide-{{ ($i % 3) + 1 }}">
                <div class="main-slider-three__bg" style="background-image: url({{ $bg }});"></div>
                <div class="main-slider-three__shape-3 img-bounce">
                    <img src="{{ $dg }}/images/shapes/main-slider-three-shape-3.png" alt="">
                </div>
                @if($sideImg)
                    <div class="main-slider-three__img">
                        <img src="{{ $sideImg }}" alt="{{ $title }}">
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
                        @if($etiket !== '')
                            <div class="main-slider-three__sub-title-box">
                                <div class="main-slider-three__shape-1" style="background-image: url({{ $dg }}/images/shapes/main-slider-three-shape-1.png);"></div>
                                <p class="main-slider-three__sub-title">{{ $etiket }}</p>
                            </div>
                        @endif
                        <h2 class="main-slider-three__title">
                            {{ $title }}
                            @if($vurgulu !== '')
                                <br><span>{{ $vurgulu }}</span>
                            @endif
                        </h2>
                        <div class="main-slider-three__btn-founder-box">
                            <a href="{{ $ctaUrl }}" class="main-slider-two__btn-one thm-btn">{{ $cta }}</a>
                            <div class="main-slider-three__founder-box">
                                <h4 class="main-slider-three__founder-name">{{ decode_text($ad) }}</h4>
                                <p class="main-slider-three__founder-sub-title">{{ decode_text($doktor['uzmanlik'] ?? '') }}</p>
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
                    $hDesc = plain_text($h['kisa'] ?? $h['aciklama'] ?? '', 90);
                    $hImg = $h['image'] ?? $h['resim'] ?? null;
                    $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                @endphp
                <div class="col-xl-4 col-lg-4 wow fadeInUp dg-card-col" data-wow-delay="{{ ($idx + 1) * 100 }}ms">
                    <div class="feature-two__single">
                        <div class="feature-two__img-box">
                            @if($hImg)
                                <div class="feature-two__img">
                                    <img src="{{ $hImg }}" alt="{{ $hAd }}">
                                </div>
                            @else
                                <div class="feature-two__img dg-card__img--empty">
                                    <span class="{{ $icons[$idx % count($icons)] }}"></span>
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
                                        <div class="text"><p>{{ plain_text(is_string($m) ? $m : (string) $m, 80) }}</p></div>
                                    </li>
                                @endforeach
                            </ul>
                            @if($mezuniyet->count() > 4)
                                <ul class="about-three__points-list list-unstyled">
                                    @foreach ($mezuniyet->slice(4) as $m)
                                        <li>
                                            <div class="icon"><span class="fa fa-check"></span></div>
                                            <div class="text"><p>{{ plain_text(is_string($m) ? $m : (string) $m, 80) }}</p></div>
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

{{-- 4) Hizmetler — blog-6 (blog-four__single) ile aynı kart tasarımı --}}
@if($show('hizmetler') && $hizmetler->isNotEmpty())
<section class="blog-one services-as-blog" id="hizmetler">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">Hizmetler</span>
            <h2 class="section-title__title">{{ filled($doktor['hizmetler_baslik'] ?? null) ? $doktor['hizmetler_baslik'] : 'Sunduğumuz hizmetler' }}</h2>
        </div>
        <div class="row gutter-y-30">
            @foreach ($hizmetler as $idx => $h)
                @php
                    $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                    $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                    $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                    $hImg = $h['image'] ?? $h['resim'] ?? $h['kapak'] ?? null;
                    $sure = $h['sure'] ?? $h['duration'] ?? null;
                    $badgeTop = str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT);
                    $badgeBottom = 'Hiz';
                    if (filled($sure) && preg_match('/(\d+)/', (string) $sure, $m)) {
                        $badgeTop = $m[1];
                        $badgeBottom = stripos((string) $sure, 'saat') !== false ? 'Saat' : 'Dk';
                    }
                @endphp
                <div class="col-lg-4 col-md-6 wow fadeInUp dg-card-col" data-wow-delay="{{ ($idx % 3) * 100 }}ms">
                    <div class="blog-four__single dg-card">
                        <div class="blog-four__single__image dg-card__media">
                            @if($hImg)
                                <img src="{{ $hImg }}" alt="{{ $hAd }}">
                            @else
                                <div class="dg-card__img--empty" aria-hidden="true"></div>
                            @endif
                            <a href="{{ $href }}" class="blog-four__single__image__link" aria-label="{{ $hAd }}"></a>
                        </div>
                        <div class="blog-four__single__content dg-card__body">
                            <div class="blog-four__single__date"><span>{{ $badgeTop }}</span>{{ $badgeBottom }}</div>
                            <h3 class="blog-four__single__title">
                                <a href="{{ $href }}">{{ $hAd }}</a>
                            </h3>
                        </div>
                        <a class="blog-four__single__rm" href="{{ $href }}">
                            İncele<span class="delogis-icons-two-right-arrow"></span>
                        </a>
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

{{-- 5) Testimonials --}}
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
                        <p class="testimonial-three__text">“{{ plain_text($y['yorum'] ?? $y['metin'] ?? $y['content'] ?? '', 180) }}”</p>
                        <div class="testimonial-three__client-info">
                            <h4 class="testimonial-three__client-name">{{ decode_text($y['hasta_adi'] ?? $y['ad'] ?? 'Danışan') }}</h4>
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

{{-- 7) Blog — blog-6.html kartları (blog-four__single); sahte görsel yok --}}
@if($show('blog') && $bloglar->isNotEmpty())
@php
    $monthsHome = ['', 'Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
@endphp
<section class="blog-one">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">Blog</span>
            <h2 class="section-title__title">Son yazılar</h2>
        </div>
        <div class="row gutter-y-30">
            @foreach ($bloglar as $idx => $b)
                @php
                    $bTitle = $b['baslik'] ?? $b['title'] ?? 'Yazı';
                    $bSlug = $b['slug'] ?? \Illuminate\Support\Str::slug($bTitle);
                    $bImg = $b['image'] ?? $b['kapak'] ?? $b['resim'] ?? null;
                    $href = route('frontend.blog.detay', $bSlug);
                    $day = '—';
                    $mon = '';
                    $rawDate = $b['tarih'] ?? $b['created_at'] ?? $b['yayin_tarihi'] ?? null;
                    if ($rawDate) {
                        try {
                            $dt = \Illuminate\Support\Carbon::parse($rawDate);
                            $day = $dt->format('d');
                            $mon = $monthsHome[(int) $dt->format('n')] ?? $dt->format('M');
                        } catch (\Throwable) {
                        }
                    }
                @endphp
                <div class="col-lg-4 col-md-6 wow fadeInUp dg-card-col" data-wow-delay="{{ ($idx % 3) * 100 }}ms">
                    <div class="blog-four__single dg-card">
                        <div class="blog-four__single__image dg-card__media">
                            @if($bImg)
                                <img src="{{ $bImg }}" alt="{{ $bTitle }}">
                            @else
                                <div class="dg-card__img--empty" aria-hidden="true"></div>
                            @endif
                            <a href="{{ $href }}" class="blog-four__single__image__link" aria-label="{{ $bTitle }}"></a>
                        </div>
                        <div class="blog-four__single__content dg-card__body">
                            <div class="blog-four__single__date"><span>{{ $day }}</span>{{ $mon }}</div>
                            <h3 class="blog-four__single__title">
                                <a href="{{ $href }}">{{ $bTitle }}</a>
                            </h3>
                        </div>
                        <a class="blog-four__single__rm" href="{{ $href }}">
                            Devamını oku<span class="delogis-icons-two-right-arrow"></span>
                        </a>
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
