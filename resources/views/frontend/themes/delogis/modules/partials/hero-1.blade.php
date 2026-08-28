<section class="main-slider">
    <div class="main-slider__carousel owl-carousel owl-theme thm-owl__carousel"
         data-owl-options='{"loop": {{ count($slides) > 1 ? 'true' : 'false' }}, "items": 1, "navText": ["<span class=\"icon-left-arrow\"></span>","<span class=\"icon-right-arrow\"></span>"], "margin": 0, "dots": true, "nav": true, "animateOut": "slideOutDown", "animateIn": "fadeIn", "active": true, "smartSpeed": 1000, "autoplay": {{ count($slides) > 1 ? 'true' : 'false' }}, "autoplayTimeout": 7000, "autoplayHoverPause": false}'>
        @foreach ($slides as $i => $slide)
            @php
                $bg = $slide['image'] ?: $fallbackBg;
                $title = decode_text($slide['baslik'] ?? $ad);
            @endphp
            <div class="item main-slider__slide-{{ ($i % 3) + 1 }}">
                <div class="main-slider__bg" style="background-image: url({{ $bg }});"></div>
                <div class="container">
                    <div class="main-slider__content">
                        <p class="main-slider__sub-title">{{ decode_text($ust) }}</p>
                        <h2 class="main-slider__title">{!! nl2br($titleHtml($title)) !!}</h2>
                        <div class="main-slider__btn-box">
                            <a href="{{ route('frontend.randevu') }}" class="main-slider__btn-one thm-btn">{{ $cta }}</a>
                            <a href="{{ route('frontend.hakkimda') }}" class="main-slider__btn-two thm-btn">{{ $cta2 }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
