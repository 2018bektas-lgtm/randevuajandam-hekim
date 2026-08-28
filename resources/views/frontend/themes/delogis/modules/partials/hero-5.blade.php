<section class="main-slider-five">
    <div class="main-slider-five__carousel owl-carousel owl-theme thm-owl__carousel" data-owl-options='{"items": 1, "margin": 0, "smartSpeed": 700, "loop": {{ count($slides) > 1 ? 'true' : 'false' }}, "autoplay": {{ count($slides) > 1 ? 'true' : 'false' }}, "animateOut": "fadeOut", "animateIn": "fadeIn", "nav": false, "URLhashListener": true, "dots": true, "navText": ["<span class=\"delogis-icons-two-left-arrow\"></span>","<span class=\"delogis-icons-two-right-arrow\"></span>"], "autoplayTimeout": 7000, "autoplayHoverPause": false}'>
        @foreach ($slides as $i => $slide)
            @php
                $side = $slide['image'] ?: $photo ?: $fallbackBg;
                $title = decode_text($slide['baslik'] ?? $ad);
                $text = decode_text($slide['metin'] ?? '');
            @endphp
            <div class="item" data-hash="item{{ $i + 1 }}">
                <div class="main-slider-five__item">
                    <div class="main-slider-five__bg" style="background-image: url({{ $dg }}/images/backgrounds/slider-5-bg.jpg);"></div>
                    <div class="main-slider-five__overlay"></div>
                    <div class="main-slider-five__shape" style="background-image: url({{ $dg }}/images/shapes/slider-5-shape-1.png);"></div>
                    <div class="main-slider-five__image" style="background-image: url({{ $side }});">
                        @if($yt !== '')
                            <a href="https://www.youtube.com/watch?v={{ $yt }}" class="video-popup">
                                <span class="delogis-icons-two-play"></span>
                                <i class="ripple"></i>
                            </a>
                        @endif
                    </div>
                    <div class="main-slider-five__shape-two" style="background-image: url({{ $dg }}/images/shapes/slider-5-shape-2.png);"></div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-left">
                                <div class="main-slider-five__content">
                                    <div class="main-slider-five__sub-title-wrapper">
                                        <h5 class="main-slider-five__sub-title">{{ decode_text($ust) }}</h5>
                                    </div>
                                    <div class="main-slider-five__title-wrapper">
                                        <h2 class="main-slider-five__title">{!! nl2br(e($title)) !!}</h2>
                                    </div>
                                    @if($text !== '')
                                        <div class="main-slider-five__text-wrapper">
                                            <p class="main-slider-five__text">{{ $text }}</p>
                                        </div>
                                    @endif
                                    <div class="main-slider-five__btn">
                                        <a href="{{ route('frontend.randevu') }}" class="thm-btn thm-btn--two">{{ $cta }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
