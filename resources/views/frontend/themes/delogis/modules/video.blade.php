@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $baslik = $ayar['baslik'] ?? 'Hikayenizi dinlemeye hazırım';
    $btn = $ayar['buton_metin'] ?? 'İletişim';
    $yt = trim((string) ($ayar['youtube_id'] ?? ''));
    $bg = $media($ayar['arkaplan_resmi'] ?? null) ?: $dg.'/images/backgrounds/video-bg.jpg';
@endphp
<section class="video-section">
    <div class="video-section__bg jarallax" data-jarallax data-speed="0.2" data-imgposition="50% 0%" style="background-image: url({{ $bg }});"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="video-section__content">
                    <div class="video-section__shape" style="background-image: url({{ $dg }}/images/shapes/video-shape.png);"></div>
                    <h3 class="video-section__title">{{ decode_text($baslik) }}</h3>
                    <a href="{{ route('frontend.iletisim') }}" class="thm-btn thm-btn--two">{{ $btn }}</a>
                </div>
            </div>
            @if($yt !== '')
            <div class="col-lg-4 d-flex align-items-center">
                <div class="video-section__btn">
                    <a href="https://www.youtube.com/watch?v={{ $yt }}" class="video-popup">
                        <span class="delogis-icons-two-play"></span>
                        <i class="ripple"></i>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
