{{-- Danışan yorumları — Hipno our-testimonial slider --}}
@php
    $limit = max(1, (int) ($ayar['yorum_limiti'] ?? 8));
    $yorumlar = collect($doktor['yorumlar'] ?? [])->filter(function ($y) {
        $metin = is_array($y) ? ($y['yorum'] ?? $y['metin'] ?? '') : '';

        return trim((string) $metin) !== '';
    })->take($limit)->values();
    $logo = $doktor['logo'] ?? asset('vendor/hipno/images/footer-logo.svg');
    $sayi = $yorumlar->count();
@endphp

@if($yorumlar->isNotEmpty())
<div class="our-testimonial">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-5">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Yorumlar' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Danışanlarımın hikayeleri' }}</h2>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="section-btn">
                    <a href="{{ route('frontend.iletisim') }}" class="btn-default">{{ $ayar['buton_metin'] ?? 'İletişim' }}</a>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="testimonial-review-box">
                    <div class="testimonial-site-logo">
                        <img src="{{ $logo }}" alt="">
                    </div>
                    <div class="about-customer-rating">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                    </div>
                    <div class="about-customer-content">
                        <p>Danışan yorumu <span class="counter">{{ max($sayi, 1) }}</span></p>
                    </div>
                    <div class="customer-images">
                        @foreach(['customer-img-1.jpg','customer-img-2.jpg','customer-img-3.jpg','customer-img-4.jpg'] as $ci)
                            <div class="customer-img">
                                <figure class="image-anime reveal">
                                    <img src="{{ asset('vendor/hipno/images/'.$ci) }}" alt="">
                                </figure>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="testimonial-slider">
                    <div class="swiper">
                        <div class="swiper-wrapper" data-cursor-text="Kaydır">
                            @foreach($yorumlar as $y)
                                @php
                                    $metin = $y['yorum'] ?? $y['metin'] ?? '';
                                    $ad = $y['ad'] ?? $y['maskeli_ad'] ?? 'Danışan';
                                    $puan = max(1, min(5, (int) ($y['puan'] ?? 5)));
                                @endphp
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-slider-image">
                                            <figure class="image-anime">
                                                <img src="{{ $y['foto'] ?? asset('vendor/hipno/images/testimonial-img-1.jpg') }}" alt="{{ $ad }}">
                                            </figure>
                                        </div>
                                        <div class="testimonial-slider-content">
                                            <div class="testimonial-rating">
                                                @for($i = 0; $i < $puan; $i++)
                                                    <i class="fa-solid fa-star"></i>
                                                @endfor
                                            </div>
                                            <div class="testimonial-content">
                                                <p>“{{ $metin }}”</p>
                                            </div>
                                            <div class="author-content">
                                                <h3>{{ $ad }}</h3>
                                                @if(!empty($y['hizmet']))
                                                    <p>{{ $y['hizmet'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="testimonial-pagination swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
