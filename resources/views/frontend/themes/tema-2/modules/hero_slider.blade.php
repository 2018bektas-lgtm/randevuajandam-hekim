{{--
    Hero Slider (Swiper carousel) — tema-2
    @param array $ayar   slaytlar, ust_baslik, cta_metin, otomatik_gecis_sn
    @param array $doktor API'den gelen doktor verisi (profil_resmi fallback için)
--}}
@php
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $slaytlar = collect($ayar['slaytlar'] ?? [])
        ->filter(fn ($s) => is_array($s) && !empty($s['baslik']))
        ->take(5)
        ->values();
    $fallbackImg = $doktor['profil_resmi'] ?? null;
    $gecisMs = max(0, (int) ($ayar['otomatik_gecis_sn'] ?? 6)) * 1000;
@endphp

@if($slaytlar->isNotEmpty())
<div class="hero hero-slider-layout">
    <div class="swiper heroSwiper">
        <div class="swiper-wrapper">
            @foreach($slaytlar as $s)
                @php
                    $bg = filled($s['resim'] ?? null) ? $s['resim'] : (filled($s['ikon'] ?? null) ? $s['ikon'] : $fallbackImg);
                @endphp
                <div class="swiper-slide">
                    <div class="hero-slide">
                        @if($bg)
                            <div class="hero-slider-image">
                                <img src="{{ $bg }}" alt="{{ $s['baslik'] }}">
                            </div>
                        @endif
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <div class="hero-content">
                                        <div class="section-title">
                                            <h3 class="wow fadeInUp">{{ $ayar['ust_baslik'] ?? '' }}</h3>
                                            <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $s['baslik'] }}</h1>
                                            @if(!empty($s['metin']))
                                                <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $s['metin'] }}</p>
                                            @endif
                                        </div>
                                        <div class="hero-content-body">
                                            <div class="hero-btn wow fadeInUp" data-wow-delay="0.4s">
                                                <a href="{{ route('frontend.randevu') }}" class="btn-default">{{ $ayar['cta_metin'] ?? 'Randevu Al' }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="hero-pagination swiper-pagination"></div>
    </div>
</div>

@push('scripts')
<script>
new Swiper('.heroSwiper', {
    loop: {{ $slaytlar->count() > 1 ? 'true' : 'false' }},
    @if($gecisMs > 0)
    autoplay: { delay: {{ $gecisMs }}, disableOnInteraction: false },
    @endif
    pagination: { el: '.hero-pagination', clickable: true },
    effect: 'fade',
    fadeEffect: { crossFade: true },
});
</script>
@endpush
@endif
