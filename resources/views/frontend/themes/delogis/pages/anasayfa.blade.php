@extends(theme_layout())

@section('baslik', trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim').' | '.($doktor['uzmanlik'] ?? 'Klinik')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? $doktor['slogan'] ?? '')

@section('icerik')
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $photo = function_exists('doctor_photo')
        ? doctor_photo($doktor ?? null, null)
        : ($doktor['profil_resmi'] ?? null);
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
    $galeri = collect($doktor['galeri'] ?? [])
        ->filter(fn ($g) => is_array($g) && (! empty($g['image']) || ! empty($g['resim']) || ! empty($g['url'])))
        ->take(6)
        ->values();
    $istatistikler = collect($doktor['istatistikler'] ?? [])
        ->filter(fn ($s) => is_array($s) && filled($s['etiket'] ?? null))
        ->values();
    $ozellikler = collect($doktor['ozellikler'] ?? [])
        ->filter(fn ($o) => is_array($o) && filled($o['baslik'] ?? null))
        ->take(6)
        ->values();
    $surec = collect($doktor['surec'] ?? [])
        ->filter(fn ($a) => is_array($a) && filled($a['baslik'] ?? null))
        ->values();
    $ad = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $bolum = $doktor['anasayfa_bolumler'] ?? [];
    $basliklar = $doktor['bolum_basliklar'] ?? [];
    $show = fn (string $key) => (bool) ($bolum[$key] ?? true);
    $icons = ['icon-heart', 'icon-self-confidence', 'icon-family', 'icon-account', 'icon-mental-health', 'icon-psychology'];
    $kisaBio = plain_text($doktor['kisa_bio'] ?? $doktor['bio'] ?? $doktor['biyografi'] ?? $doktor['slogan'] ?? '', 280);
    $mezuniyet = collect($doktor['mezuniyet'] ?? [])->filter()->take(8)->values();
    $monthsHome = ['', 'Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];

    // Panel sırası (mobil/desktop aynı dikey akış)
    $defaultSira = [
        'slider', 'istatistik', 'ozellikler', 'hakkimda', 'hizmetler',
        'surec', 'galeri', 'yorumlar', 'blog', 'cta',
    ];
    $sira = $doktor['anasayfa_sira'] ?? $defaultSira;
    if (! is_array($sira) || $sira === []) {
        $sira = $defaultSira;
    }
    // Eksik ana sayfa modüllerini sona ekle (panelde yoksa bile tüm bloklar mevcut olsun)
    foreach ($defaultSira as $key) {
        if (! in_array($key, $sira, true)) {
            $sira[] = $key;
        }
    }
@endphp

@foreach ($sira as $sectionKey)
    @if(! $show($sectionKey))
        @continue
    @endif

    @switch($sectionKey)

        {{-- ── Slider ── --}}
        @case('slider')
            @if(count($slider) > 0)
            <section class="main-slider-three">
                <div class="main-slider__carousel owl-carousel owl-theme thm-owl__carousel"
                     data-owl-options='{"loop": {{ count($slider) > 1 ? 'true' : 'false' }}, "items": 1, "navText": ["<span class=\"icon-left-arrow\"></span>","<span class=\"icon-right-arrow\"></span>"], "margin": 0, "dots": false, "nav": true, "animateOut": "fadeOut", "animateIn": "fadeIn", "active": true, "smartSpeed": 1000, "autoplay": {{ count($slider) > 1 ? 'true' : 'false' }}, "autoplayTimeout": 7000, "autoplayHoverPause": false}'>
                    @foreach ($slider as $i => $slide)
                        @php
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
            @break

        {{-- ── İstatistik ── --}}
        @case('istatistik')
            @if($istatistikler->isNotEmpty())
            <section class="counter-one" style="padding:48px 0">
                <div class="container">
                    <div class="row">
                        @foreach ($istatistikler->take(4) as $stat)
                            <div class="col-6 col-md-3 text-center" style="margin-bottom:20px">
                                <h3 class="counter-one__count" style="font-size:2rem;font-weight:800;color:var(--delogis-base,#C96A2B);margin:0">
                                    {{ $stat['deger'] ?? $stat['sayi'] ?? '0' }}{{ $stat['suffix'] ?? '' }}
                                </h3>
                                <p style="margin:.35rem 0 0;font-size:.85rem;font-weight:600;color:#374151">{{ $stat['etiket'] ?? '' }}</p>
                                @if(!empty($stat['aciklama']))
                                    <p style="margin:.2rem 0 0;font-size:.75rem;color:#6b7280">{{ $stat['aciklama'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
            @break

        {{-- ── Özellikler ── --}}
        @case('ozellikler')
            @if($ozellikler->isNotEmpty())
            <section class="feature-one" style="padding:60px 0">
                <div class="container">
                    <div class="section-title text-center">
                        <span class="section-title__tagline">Özellikler</span>
                        <h2 class="section-title__title">{{ $basliklar['ozellikler']['baslik'] ?? 'Neden tercih edilmeli?' }}</h2>
                        @if(!empty($basliklar['ozellikler']['alt']))
                            <p class="section-title__text">{{ $basliklar['ozellikler']['alt'] }}</p>
                        @endif
                    </div>
                    <div class="row">
                        @foreach ($ozellikler as $idx => $oz)
                            <div class="col-xl-4 col-md-6" style="margin-bottom:24px">
                                <div class="feature-two__single" style="padding:24px;height:100%;background:#fff;border:1px solid #e5e7eb;border-radius:12px">
                                    <div class="feature-two__icon" style="margin-bottom:12px">
                                        <span class="{{ $icons[$idx % count($icons)] }}" style="font-size:1.75rem"></span>
                                    </div>
                                    <h3 style="font-size:1.1rem;margin:0 0 8px">{{ decode_text($oz['baslik'] ?? '') }}</h3>
                                    <p style="margin:0;font-size:.9rem;color:#6b7280;line-height:1.6">{{ plain_text($oz['aciklama'] ?? '', 140) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
            @break

        {{-- ── Hakkımda ── --}}
        @case('hakkimda')
            @if($photo || $kisaBio !== '')
            <section class="about-three" id="hakkimda">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-7">
                            <div class="about-three__left">
                                <div class="section-title text-left">
                                    <span class="section-title__tagline">{{ $doktor['uzmanlik'] ?? 'Hoş geldiniz' }}</span>
                                    <h2 class="section-title__title">{{ $basliklar['hakkimda']['baslik'] ?? $ad }}</h2>
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
            @break

        {{-- ── Hizmetler (tek blok — çift vitrin yok) ── --}}
        @case('hizmetler')
            @if($hizmetler->isNotEmpty())
            <section class="blog-one services-as-blog" id="hizmetler">
                <div class="container">
                    <div class="section-title text-center">
                        <span class="section-title__tagline">Hizmetler</span>
                        <h2 class="section-title__title">
                            {{ filled($doktor['hizmetler_baslik'] ?? null)
                                ? $doktor['hizmetler_baslik']
                                : ($basliklar['hizmetler']['baslik'] ?? 'Sunduğumuz hizmetler') }}
                        </h2>
                        @if(filled($doktor['hizmetler_alt'] ?? null) || !empty($basliklar['hizmetler']['alt']))
                            <p class="section-title__text">{{ $doktor['hizmetler_alt'] ?? $basliklar['hizmetler']['alt'] ?? '' }}</p>
                        @endif
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
            @break

        {{-- ── Süreç ── --}}
        @case('surec')
            @if($surec->isNotEmpty())
            <section class="process-one" style="padding:60px 0;background:#f8fafc">
                <div class="container">
                    <div class="section-title text-center">
                        <span class="section-title__tagline">Süreç</span>
                        <h2 class="section-title__title">{{ $basliklar['surec']['baslik'] ?? 'Randevudan takibe' }}</h2>
                        @if(!empty($basliklar['surec']['alt']))
                            <p class="section-title__text">{{ $basliklar['surec']['alt'] }}</p>
                        @endif
                    </div>
                    <div class="row">
                        @foreach ($surec as $idx => $adim)
                            <div class="col-md-6 col-lg-3" style="margin-bottom:24px">
                                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:22px;height:100%">
                                    <div style="width:40px;height:40px;border-radius:10px;background:#C96A2B;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:12px">
                                        {{ $adim['adim'] ?? ($idx + 1) }}
                                    </div>
                                    <h3 style="font-size:1rem;margin:0 0 8px">{{ decode_text($adim['baslik'] ?? '') }}</h3>
                                    <p style="margin:0;font-size:.88rem;color:#6b7280;line-height:1.55">{{ plain_text($adim['aciklama'] ?? '', 120) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
            @break

        {{-- ── Galeri ── --}}
        @case('galeri')
            @if($galeri->isNotEmpty())
            <section class="gallery-one" style="padding:60px 0">
                <div class="container">
                    <div class="section-title text-center">
                        <span class="section-title__tagline">Galeri</span>
                        <h2 class="section-title__title">{{ $basliklar['galeri']['baslik'] ?? 'Klinik görselleri' }}</h2>
                    </div>
                    <div class="row gutter-y-30">
                        @foreach ($galeri as $g)
                            @php
                                $gImg = $g['image'] ?? $g['resim'] ?? $g['url'] ?? null;
                                $gTitle = decode_text($g['baslik'] ?? $g['etiket'] ?? 'Galeri');
                            @endphp
                            @if($gImg)
                            <div class="col-6 col-md-4">
                                <a href="{{ route('frontend.galeri') }}" class="dg-card" style="display:block;overflow:hidden;border-radius:12px">
                                    <div class="dg-card__media" style="aspect-ratio:4/3">
                                        <img src="{{ $gImg }}" alt="{{ $gTitle }}" loading="lazy" style="width:100%;height:100%;object-fit:cover">
                                    </div>
                                </a>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="text-center" style="margin-top:28px">
                        <a href="{{ route('frontend.galeri') }}" class="thm-btn">Tüm galeri</a>
                    </div>
                </div>
            </section>
            @endif
            @break

        {{-- ── Yorumlar ── --}}
        @case('yorumlar')
            @if($yorumlar->isNotEmpty())
            <section class="testimonial-three">
                <div class="container">
                    <div class="section-title text-center">
                        <span class="section-title__tagline">Yorumlar</span>
                        <h2 class="section-title__title">{{ $basliklar['yorumlar']['baslik'] ?? 'Danışan deneyimleri' }}</h2>
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
            @break

        {{-- ── Blog ── --}}
        @case('blog')
            @if($bloglar->isNotEmpty())
            <section class="blog-one">
                <div class="container">
                    <div class="section-title text-center">
                        <span class="section-title__tagline">Blog</span>
                        <h2 class="section-title__title">{{ $basliklar['blog']['baslik'] ?? 'Son yazılar' }}</h2>
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
            @break

        {{-- ── CTA ── --}}
        @case('cta')
            <section class="cta-one">
                <div class="cta-one__shape-1 float-bob-x">
                    <img src="{{ $dg }}/images/shapes/cta-one-shape-1.png" alt="">
                </div>
                <div class="container">
                    <div class="cta-one__inner">
                        <p class="cta-one__text">
                            {{ filled($doktor['cta_baslik'] ?? null)
                                ? $doktor['cta_baslik']
                                : ($basliklar['cta']['baslik'] ?? 'Size uygun saatte online randevu alın') }}
                        </p>
                        @if(filled($doktor['cta_metin'] ?? null) || !empty($basliklar['cta']['alt']))
                            <p style="color:rgba(255,255,255,.8);margin:.5rem 0 0;font-size:.9rem">
                                {{ $doktor['cta_metin'] ?? $basliklar['cta']['alt'] ?? '' }}
                            </p>
                        @endif
                        <div class="cta-one__btn-box">
                            <a href="{{ route('frontend.randevu') }}" class="cta-one__btn thm-btn">Randevu Al</a>
                        </div>
                    </div>
                </div>
            </section>
            @break

    @endswitch
@endforeach
@endsection
