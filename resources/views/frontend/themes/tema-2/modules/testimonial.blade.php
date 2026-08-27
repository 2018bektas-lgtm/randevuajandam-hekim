{{--
    Danışan Yorumları — kayan slider
--}}
@php
    $limit = max(1, (int) ($ayar['yorum_limiti'] ?? 8));
    $yorumlar = collect($doktor['yorumlar'] ?? [])->filter(function ($y) {
        $metin = is_array($y) ? ($y['yorum'] ?? $y['metin'] ?? '') : '';

        return trim((string) $metin) !== '';
    })->take($limit)->values();
@endphp

@if($yorumlar->isNotEmpty())
<div class="our-testimonial">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="testimonial-review-box wow fadeInUp">
                    <div class="about-customer-rating">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                    </div>
                    <div class="section-title" style="margin-top:1.25rem">
                        <h3>{{ $ayar['kucuk_baslik'] ?? 'Danışan Yorumları' }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Danışanlarımın hikayeleri' }}</h2>
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
                                    <div class="testimonial-item is-text">
                                        <div class="testimonial-slider-content" style="width:100%">
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
                                                @if(!empty($y['hizmet']) || !empty($y['tarih']))
                                                    <p>{{ $y['hizmet'] ?? $y['tarih'] }}</p>
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

@push('head')
<style>
.testimonial-slider{position:relative}
.testimonial-slider .swiper{padding-bottom:48px}
.testimonial-item.is-text{display:block}
</style>
@endpush
