<section class="main-slider-four">
    <div class="main-slider-four__carousel owl-carousel owl-theme thm-owl__carousel"
         data-owl-options='{"loop": {{ count($slides) > 1 ? 'true' : 'false' }}, "items": 1, "navText": ["<span class=\"delogis-icons-two-left-arrow\"></span>","<span class=\"delogis-icons-two-right-arrow\"></span>"], "margin": 0, "dots": false, "nav": true, "animateOut": "fadeOut", "animateIn": "fadeIn", "active": true, "smartSpeed": 1000, "autoplay": {{ count($slides) > 1 ? 'true' : 'false' }}, "autoplayTimeout": 7000, "autoplayHoverPause": false}'>
        @foreach ($slides as $slide)
            @php
                $bg = $slide['image'] ?: $fallbackBg;
                $title = decode_text($slide['baslik'] ?? $ad);
                $text = decode_text($slide['metin'] ?? '');
            @endphp
            <div class="item">
                <div class="main-slider-four__item">
                    <div class="main-slider-four__bg" style="background-image: url({{ $bg }});">
                        <div class="main-slider-four__bg__color"></div>
                        <div class="main-slider-four__bg__color"></div>
                        <div class="main-slider-four__bg__color"></div>
                        <div class="main-slider-four__bg__color"></div>
                        <div class="main-slider-four__bg__color"></div>
                        <div class="main-slider-four__bg__color"></div>
                    </div>
                    <div class="main-slider-four__overlay"></div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="main-slider-four__content">
                                    <h2 class="main-slider-four__title">{!! nl2br(e($title)) !!}</h2>
                                    @if($text !== '')
                                        <p class="main-slider-four__text">{{ $text }}</p>
                                    @endif
                                    <div class="main-slider-four__btn">
                                        <a href="{{ route('frontend.randevu') }}" class="thm-btn thm-btn--two">{{ $cta }}</a>
                                        @if($yt !== '')
                                            <a href="https://www.youtube.com/watch?v={{ $yt }}" class="video-popup">
                                                <span class="delogis-icons-two-play"></span>
                                                <i class="ripple"></i>
                                            </a>
                                        @endif
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
