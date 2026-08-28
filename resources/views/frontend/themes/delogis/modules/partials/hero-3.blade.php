<section class="main-slider-three">
    <div class="main-slider__carousel owl-carousel owl-theme thm-owl__carousel"
         data-owl-options='{"loop": {{ count($slides) > 1 ? 'true' : 'false' }}, "items": 1, "navText": ["<span class=\"icon-left-arrow\"></span>","<span class=\"icon-right-arrow\"></span>"], "margin": 0, "dots": false, "nav": true, "animateOut": "fadeOut", "animateIn": "fadeIn", "active": true, "smartSpeed": 1000, "autoplay": {{ count($slides) > 1 ? 'true' : 'false' }}, "autoplayTimeout": 7000, "autoplayHoverPause": false}'>
        @foreach ($slides as $i => $slide)
            @php
                $bg = $dg.'/images/backgrounds/main-slider-three-bg.jpg';
                $sideImg = $slide['image'] ?: $photo;
                $title = decode_text($slide['baslik'] ?? $ad);
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
                        @if($ust !== '')
                            <div class="main-slider-three__sub-title-box">
                                <div class="main-slider-three__shape-1" style="background-image: url({{ $dg }}/images/shapes/main-slider-three-shape-1.png);"></div>
                                <p class="main-slider-three__sub-title">{{ decode_text($ust) }}</p>
                            </div>
                        @endif
                        <h2 class="main-slider-three__title">{!! nl2br($titleHtml($title)) !!}</h2>
                        <div class="main-slider-three__btn-founder-box">
                            <a href="{{ route('frontend.randevu') }}" class="main-slider-two__btn-one thm-btn">{{ $cta }}</a>
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
