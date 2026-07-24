@extends(theme_layout())

@section('baslik', trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim').' | '.($doktor['uzmanlik'] ?? 'Klinik').(!empty($doktor['il']) ? ' · '.$doktor['il'] : '')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? '')

@section('icerik')
@php
    $photo    = $doktor['profil_resmi'] ?? ($doktor['slider'][0]['image'] ?? null);
    $bolum    = $doktor['anasayfa_bolumler'] ?? [];
    $basliklar = $doktor['bolum_basliklar'] ?? [];
    $sira = $doktor['anasayfa_sira'] ?? [
        'slider', 'hakkimda', 'hizmetler', 'ozellikler', 'surec', 'yorumlar', 'sss', 'blog', 'cta'
    ];
    if (!in_array('slider', $sira, true)) array_unshift($sira, 'slider');
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
@endphp

@foreach ($sira as $sectionKey)
    @if(!($bolum[$sectionKey] ?? true))
        @continue
    @endif
    @switch($sectionKey)

        @case('slider')
        {{-- ===== HERO ===== --}}
        <div class="hero parallaxie"@if($photo) style="background-image:url('{{ $photo }}')"@endif>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="hero-content">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">{{ $doktor['uzmanlik'] ?? 'Uzman Hekim' }}</h3>
                                <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $doktorAd }}</h1>
                                @if(!empty($doktor['kisa_bio']) || !empty($doktor['slogan']))
                                <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $doktor['kisa_bio'] ?? $doktor['slogan'] }}</p>
                                @endif
                            </div>
                            <div class="hero-content-body">
                                <div class="hero-btn wow fadeInUp" data-wow-delay="0.4s">
                                    <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                                    <a href="{{ route('frontend.hakkimda') }}" class="btn-default btn-highlighted">Hakkımda</a>
                                </div>
                                @if(!empty($doktor['istatistikler']))
                                <div class="hero-client-box">
                                    <div class="hero-client-content">
                                        @php $ist0 = $doktor['istatistikler'][0] ?? null; @endphp
                                        @if($ist0)
                                        <p><span class="counter">{{ preg_replace('/\D/', '', $ist0['deger'] ?? '0') }}</span>{{ $ist0['suffix'] ?? '' }}+ <span>{{ $ist0['etiket'] }}</span></p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if(!empty($doktor['branslar']))
                        <div class="hero-list wow fadeInUp" data-wow-delay="0.6s">
                            <ul>
                                @foreach (array_slice($doktor['branslar'], 0, 5) as $brans)
                                <li>{{ $brans }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @break

        @case('hakkimda')
        {{-- ===== ABOUT US ===== --}}
        <div class="about-us">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-us-images">
                            <div class="about-img-1">
                                <figure class="image-anime">
                                    <img src="{{ $photo ?? asset('vendor/hipno/images/about-img-1.jpg') }}" alt="{{ $doktorAd }}">
                                </figure>
                            </div>
                            @if(!empty($doktor['profil_resmi2']))
                            <div class="about-img-2">
                                <figure class="image-anime">
                                    <img src="{{ $doktor['profil_resmi2'] }}" alt="{{ $doktorAd }}">
                                </figure>
                            </div>
                            @endif
                            @if(!empty($doktor['istatistikler']))
                            <div class="about-customer-box">
                                <div class="about-customer-rating">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="about-customer-content">
                                    @php $ist1 = $doktor['istatistikler'][0] ?? null; @endphp
                                    @if($ist1)
                                    <p>{{ $ist1['etiket'] }} <span class="counter">{{ preg_replace('/\D/', '', $ist1['deger'] ?? '0') }}</span>{{ $ist1['suffix'] ?? '' }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-us-content">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">Hakkımda</h3>
                                <h2 class="text-anime-style-2" data-cursor="-opaque">
                                    {{ $basliklar['hakkimda']['baslik'] ?? $doktor['slogan'] ?? ('Uzman '.$doktor['uzmanlik'].' hizmetinde') }}
                                </h2>
                                @if(!empty($basliklar['hakkimda']['alt']))
                                <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $basliklar['hakkimda']['alt'] }}</p>
                                @elseif(!empty($doktor['kisa_bio']))
                                <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $doktor['kisa_bio'] }}</p>
                                @endif
                            </div>
                            @if(!empty($doktor['ozellikler']))
                            <div class="about-vision-mission">
                                @foreach (array_slice($doktor['ozellikler'], 0, 2) as $oz)
                                <div class="vision-mission-content wow fadeInUp" data-wow-delay="0.4s">
                                    <h3>{{ $oz['baslik'] }}</h3>
                                    <p>{{ $oz['aciklama'] }}</p>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            <div class="about-us-content-btn wow fadeInUp" data-wow-delay="0.6s">
                                <a href="{{ route('frontend.hakkimda') }}" class="btn-default">Daha Fazla</a>
                                <a href="{{ route('frontend.randevu') }}" class="btn-default btn-highlighted">Randevu Al</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @break

        @case('hizmetler')
        @if(!empty($doktor['hizmetler']))
        {{-- ===== SERVICES ===== --}}
        <div class="our-services">
            <div class="container">
                <div class="row section-row align-items-center">
                    <div class="col-lg-6 col-md-9">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">hizmetler</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">
                                {{ $basliklar['hizmetler']['baslik'] ?? 'Kapsamlı sağlık hizmetleri' }}
                            </h2>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-3">
                        <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                            <a href="{{ route('frontend.hizmetler') }}" class="btn-default">Tüm Hizmetler</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach (array_slice($doktor['hizmetler'], 0, 6) as $i => $hizmet)
                    @php $hSlug = $hizmet['slug'] ?? \Illuminate\Support\Str::slug($hizmet['baslik'] ?? ''); @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="service-item wow fadeInUp" data-wow-delay="{{ $i * 0.2 }}s">
                            <div class="service-image">
                                <a href="{{ route('frontend.hizmet.detay', $hSlug) }}" data-cursor-text="Görüntüle">
                                    <figure class="image-anime">
                                        <img src="{{ $hizmet['image'] ?? asset('vendor/hipno/images/service-image-1.jpg') }}" alt="{{ $hizmet['baslik'] }}" loading="lazy">
                                    </figure>
                                </a>
                            </div>
                            <div class="service-content">
                                <h3>{{ $hizmet['baslik'] }}</h3>
                            </div>
                            <div class="service-btn">
                                <a href="{{ route('frontend.hizmet.detay', $hSlug) }}" class="readmore-btn">detaylar</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if(count($doktor['hizmetler']) > 6)
                    <div class="col-lg-12">
                        <div class="service-get-quote-text wow fadeInUp">
                            <p>Tüm hizmetleri görmek için <a href="{{ route('frontend.hizmetler') }}">buraya tıklayın</a></p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @break

        @case('ozellikler')
        @if(!empty($doktor['ozellikler']))
        {{-- ===== WHY CHOOSE US ===== --}}
        <div class="why-choose-us">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="why-choose-us-box">
                            <div class="why-choose-image">
                                <figure class="image-anime reveal">
                                    <img src="{{ $photo ?? asset('vendor/hipno/images/why-choose-img-1.jpg') }}" alt="{{ $doktorAd }}">
                                </figure>
                                <div class="contact-circle-img">
                                    <img src="{{ asset('vendor/hipno/images/contact-circle-img.svg') }}" alt="">
                                </div>
                            </div>
                            <div class="why-choose-content">
                                <div class="section-title">
                                    <h3 class="wow fadeInUp">neden biz</h3>
                                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                                        {{ $basliklar['ozellikler']['baslik'] ?? 'Güvenilir bakım, kalıcı değişim' }}
                                    </h2>
                                    @if(!empty($basliklar['ozellikler']['alt']))
                                    <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $basliklar['ozellikler']['alt'] }}</p>
                                    @endif
                                </div>
                                <div class="why-choose-list">
                                    @foreach (array_slice($doktor['ozellikler'], 0, 4) as $i => $oz)
                                    <div class="why-choose-item wow fadeInUp" data-wow-delay="{{ $i * 0.2 }}s">
                                        <div class="icon-box">
                                            <img src="{{ asset('vendor/hipno/images/icon-why-choose-'.($i+1).'.svg') }}" alt="" onerror="this.style.display='none'">
                                        </div>
                                        <div class="why-choose-item-content">
                                            <h3>{{ $oz['baslik'] }}</h3>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="why-choose-body">
                                    <div class="why-choose-body-content wow fadeInUp">
                                        @php $oz5 = $doktor['ozellikler'][4] ?? null; @endphp
                                        <h3>{{ $oz5['baslik'] ?? ($doktorAd.' ile çalışmak') }}</h3>
                                        <p>{{ $oz5['aciklama'] ?? ($doktor['kisa_bio'] ?? '') }}</p>
                                        <a href="{{ route('frontend.iletisim') }}" class="btn-default">İletişime Geç</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @break

        @case('surec')
        @if(!empty($doktor['surec']))
        {{-- ===== HOW IT WORKS ===== --}}
        <div class="how-it-work">
            <div class="container">
                <div class="row section-row align-items-center">
                    <div class="col-lg-6">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">süreç</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">
                                {{ $basliklar['surec']['baslik'] ?? 'Randevudan tedaviye adım adım' }}
                            </h2>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                            <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="how-work-step-box">
                            @foreach ($doktor['surec'] as $i => $adim)
                            <div class="how-work-step-item wow fadeInUp" data-wow-delay="{{ $i * 0.2 }}s">
                                <div class="how-work-step-no">
                                    <h3>{{ str_pad($adim['adim'] ?? ($i+1), 2, '0', STR_PAD_LEFT) }}</h3>
                                </div>
                                <div class="how-work-step-content">
                                    <h3>{{ str_pad($adim['adim'] ?? ($i+1), 2, '0', STR_PAD_LEFT) }}. {{ $adim['baslik'] }}</h3>
                                    <p>{{ $adim['aciklama'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @break

        @case('istatistik')
        @if(!empty($doktor['istatistikler']))
        {{-- ===== STATS (video section layout) ===== --}}
        <div class="what-we-do">
            <div class="container">
                <div class="row section-row align-items-center">
                    <div class="col-lg-6">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">istatistikler</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Deneyim ve başarı rakamlarla</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="intro-video-box" style="background:var(--primary-color);padding:3rem 2rem;border-radius:1rem">
                            <div class="intro-video-counter">
                                @foreach ($doktor['istatistikler'] as $ist)
                                <div class="video-counter-item">
                                    <h2><span class="counter">{{ preg_replace('/\D/', '', $ist['deger'] ?? '0') }}</span>{{ $ist['suffix'] ?? '' }}</h2>
                                    <p>{{ $ist['etiket'] }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @break

        @case('galeri')
        @if(!empty($doktor['galeri']))
        {{-- ===== GALLERY (case-study style) ===== --}}
        <div class="case-study">
            <div class="container">
                <div class="row section-row align-items-center">
                    <div class="col-lg-5">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">galeri</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $basliklar['galeri']['baslik'] ?? 'Klinik ve çalışmalardan kareler' }}</h2>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                            <a href="{{ route('frontend.galeri') }}" class="btn-default">Tüm Galeri</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach (array_slice($doktor['galeri'], 0, 3) as $i => $g)
                    <div class="col-lg-4 col-md-6">
                        <div class="case-study-item wow fadeInUp" data-wow-delay="{{ $i * 0.2 }}s">
                            <div class="case-study-image">
                                <a href="{{ $g['image'] }}" class="popup-image" data-cursor-text="Büyüt">
                                    <figure>
                                        <img src="{{ $g['image'] }}" alt="{{ $g['baslik'] ?? '' }}" loading="lazy">
                                    </figure>
                                </a>
                            </div>
                            @if(!empty($g['baslik']))
                            <div class="case-study-content">
                                <h3>{{ $g['baslik'] }}</h3>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @break

        @case('yorumlar')
        @if(!empty($doktor['yorumlar']))
        {{-- ===== TESTIMONIALS ===== --}}
        <div class="our-testimonial">
            <div class="container">
                <div class="row section-row align-items-center">
                    <div class="col-lg-5">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">görüşler</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">
                                {{ $basliklar['yorumlar']['baslik'] ?? 'Danışan değerlendirmeleri' }}
                            </h2>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="section-btn">
                            <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-4">
                        <div class="testimonial-review-box">
                            <div class="about-customer-rating">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="about-customer-content">
                                <p>Danışan Değerlendirmesi <span class="counter">{{ count($doktor['yorumlar']) }}</span>+</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="testimonial-slider">
                            <div class="swiper">
                                <div class="swiper-wrapper" data-cursor-text="Sürükle">
                                    @foreach ($doktor['yorumlar'] as $yorum)
                                    <div class="swiper-slide">
                                        <div class="testimonial-item">
                                            @if(!empty($yorum['resim']))
                                            <div class="testimonial-slider-image">
                                                <figure class="image-anime">
                                                    <img src="{{ $yorum['resim'] }}" alt="{{ $yorum['ad'] }}" loading="lazy">
                                                </figure>
                                            </div>
                                            @endif
                                            <div class="testimonial-slider-content">
                                                <div class="testimonial-rating">
                                                    @for ($s = 0; $s < max(1, min(5, (int)($yorum['puan'] ?? 5))); $s++)
                                                    <i class="fa-solid fa-star"></i>
                                                    @endfor
                                                </div>
                                                <div class="testimonial-content">
                                                    <p>"{{ $yorum['metin'] }}"</p>
                                                </div>
                                                <div class="author-content">
                                                    <h3>{{ $yorum['ad'] }}</h3>
                                                    @if(!empty($yorum['unvan']))
                                                    <p>{{ $yorum['unvan'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="testimonial-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @break

        @case('sss')
        @if(!empty($doktor['sss']))
        {{-- ===== FAQs ===== --}}
        <div class="our-faqs parallaxie">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="our-faqs-content">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">SSS</h3>
                                <h2 class="text-anime-style-2" data-cursor="-opaque">Sık sorulan sorular</h2>
                            </div>
                            <div class="faq-cta-box">
                                <div class="faq-cta-box-content wow fadeInUp" data-wow-delay="0.2s">
                                    <h3>Başka sorunuz mu var?</h3>
                                    @if(!empty($doktor['telefon']))
                                    <a href="tel:{{ $doktor['telefon_raw'] ?? preg_replace('/[^0-9+]/', '', $doktor['telefon']) }}" class="btn-faqs">{{ $doktor['telefon'] }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="faq-accordion" id="homeFaqAccordion">
                            @foreach (array_slice($doktor['sss'], 0, 5) as $i => $faq)
                            <div class="accordion-item wow fadeInUp" data-wow-delay="{{ $i * 0.2 }}s">
                                <h2 class="accordion-header" id="homeFaqH{{ $i }}">
                                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#homeFaqC{{ $i }}"
                                            aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                                        <span>{{ $i + 1 }}.</span> {{ $faq['soru'] }}
                                    </button>
                                </h2>
                                <div id="homeFaqC{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                     data-bs-parent="#homeFaqAccordion">
                                    <div class="accordion-body">
                                        <p>{{ $faq['cevap'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @break

        @case('blog')
        @if(!empty($doktor['bloglar']))
        {{-- ===== BLOG ===== --}}
        <div class="our-blog">
            <div class="container">
                <div class="row section-row align-items-center">
                    <div class="col-lg-6">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">son yazılar</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">
                                {{ $basliklar['blog']['baslik'] ?? 'Sağlık ve yaşam yazıları' }}
                            </h2>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                            <a href="{{ route('frontend.blog') }}" class="btn-default">Tüm Yazılar</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach (array_slice($doktor['bloglar'], 0, 3) as $i => $yazi)
                    <div class="col-lg-4 col-md-6">
                        <div class="post-item wow fadeInUp" data-wow-delay="{{ $i * 0.2 }}s">
                            <div class="post-featured-image">
                                <figure>
                                    <a href="{{ route('frontend.blog.detay', $yazi['slug']) }}" class="image-anime" data-cursor-text="Oku">
                                        <img src="{{ $yazi['image'] ?? asset('vendor/hipno/images/post-1.jpg') }}" alt="{{ $yazi['baslik'] }}" loading="lazy">
                                    </a>
                                </figure>
                            </div>
                            <div class="post-item-body">
                                <div class="post-item-content">
                                    <h3><a href="{{ route('frontend.blog.detay', $yazi['slug']) }}">{{ $yazi['baslik'] }}</a></h3>
                                </div>
                                <div class="post-item-btn">
                                    <a href="{{ route('frontend.blog.detay', $yazi['slug']) }}" class="readmore-btn">devamını oku</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @break

        @case('cta')
        {{-- ===== CTA + APPOINTMENT ===== --}}
        <div class="cta-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cta-box">
                            <div class="cta-box-image">
                                <img src="{{ asset('vendor/hipno/images/cta-img.png') }}" alt="" onerror="this.style.display='none'">
                            </div>
                            <div class="cta-box-content">
                                <div class="section-title">
                                    <h3 class="wow fadeInUp">randevu</h3>
                                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                                        {{ $doktor['cta_baslik'] ?? $basliklar['cta']['baslik'] ?? ($doktorAd.' ile görüşmek ister misiniz?') }}
                                    </h2>
                                    <p class="wow fadeInUp" data-wow-delay="0.2s">
                                        {{ $doktor['cta_metin'] ?? $basliklar['cta']['alt'] ?? 'Randevunuzu hemen oluşturun, sağlığınız için ilk adımı atın.' }}
                                    </p>
                                </div>
                                <div class="cta-box-btn wow fadeInUp">
                                    <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Appointment info --}}
        <div class="our-appointment">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="our-appointment-box">
                            <div class="appointment-form">
                                <div style="padding:2rem;text-align:center">
                                    <h3 style="font-family:var(--accent-font);color:#fff;margin-bottom:1rem">Online Randevu Sistemi</h3>
                                    <p style="color:rgba(255,255,255,.7);margin-bottom:1.5rem">Hızlı ve kolay randevu için online sistemimizi kullanabilirsiniz.</p>
                                    <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Oluştur</a>
                                </div>
                            </div>
                            <div class="our-appointment-content">
                                <div class="section-title">
                                    <h3 class="wow fadeInUp">iletişim</h3>
                                    <h2 class="text-anime-style-2" data-cursor="-opaque">Randevu bilgileri</h2>
                                </div>
                                <div class="appointment-content-body">
                                    @if(!empty($doktor['telefon']))
                                    <div class="appointment-item wow fadeInUp" data-wow-delay="0.4s">
                                        <div class="icon-box">
                                            <img src="{{ asset('vendor/hipno/images/icon-appointment-item-1.svg') }}" alt="" onerror="this.style.display='none'">
                                        </div>
                                        <div class="appointment-item-content">
                                            <h3>Telefon</h3>
                                            <p>{{ $doktor['telefon'] }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    @if(!empty($doktor['calisma_saatleri_text']) || !empty($doktor['calisma_saatleri']))
                                    <div class="appointment-item wow fadeInUp" data-wow-delay="0.6s">
                                        <div class="icon-box">
                                            <img src="{{ asset('vendor/hipno/images/icon-appointment-item-2.svg') }}" alt="" onerror="this.style.display='none'">
                                        </div>
                                        <div class="appointment-item-content">
                                            <h3>Çalışma Saatleri</h3>
                                            <p>{{ $doktor['calisma_saatleri_text'] ?? 'Pzt - Cts: 09:00 - 18:00' }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @break

    @endswitch
@endforeach
@endsection
