<script src="{{ asset('vendor/hipno/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/validator.min.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/SmoothScroll.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/parallaxie.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/gsap.min.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/magiccursor.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/SplitText.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/ScrollTrigger.min.js') }}"></script>
<script src="{{ asset('vendor/hipno/js/wow.min.js') }}"></script>
<script>
(function($) {
    /*
     * Preloader.
     *
     * Eskiden yalnizca `window.load` bekleniyordu: TUM gorseller ve 15 JS
     * dosyasi inene kadar sayfa tam ekran ortulu kaliyor, kullanici hazir
     * icerigi goremiyor ve LCP olcumu bozuluyordu. Ayrica tek bir varlik
     * yuklenemezse preloader HIC kapanmiyordu.
     *
     * Simdi: DOM hazir olunca kisa bir gecikmeyle kapat, `window.load` daha
     * once gelirse hemen kapat, her kosulda 2.5 sn'lik guvenlik zaman asimi
     * uygula. Hareket azaltma tercihi varsa animasyonsuz gizle.
     */
    var raPreloaderKapandi = false;
    function raPreloaderKapat() {
        if (raPreloaderKapandi) { return; }
        raPreloaderKapandi = true;

        var azaltilmisHareket = window.matchMedia
            && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (azaltilmisHareket) {
            $('.preloader').hide();
        } else {
            $('.preloader').fadeOut(400);
        }
    }

    $(document).ready(function() { setTimeout(raPreloaderKapat, 200); });
    $(window).on('load', raPreloaderKapat);
    setTimeout(raPreloaderKapat, 2500);   // guvenlik agi

    $(document).ready(function() {
        // Mobile nav
        $('#menu').slicknav({
            prependTo: '.responsive-menu',
            closeOnClick: true,
            allowParentLinks: true,
            label: ''
        });

        // Sticky header
        $(window).on('scroll.stickyheader', function() {
            if ($(this).scrollTop() > 80) {
                $('.header-sticky').addClass('active');
            } else {
                $('.header-sticky').removeClass('active');
            }
        });

        // WOW
        new WOW({ offset: 100, mobile: false }).init();

        // CounterUp
        if ($.fn.counterUp) {
            $('.counter').counterUp({ delay: 10, time: 2000 });
        }

        // Parallax
        if ($.fn.parallaxie) {
            $('.parallaxie').parallaxie({ speed: 0.5, offset: 0 });
        }

        // Testimonial swiper
        if ($('.testimonial-slider .swiper').length) {
            new Swiper('.testimonial-slider .swiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                pagination: { el: '.testimonial-pagination', clickable: true },
                autoplay: { delay: 5000, disableOnInteraction: false }
            });
        }

        // Company logos swiper
        if ($('.testimonial-company-slider .swiper').length) {
            new Swiper('.testimonial-company-slider .swiper', {
                slidesPerView: 3,
                spaceBetween: 30,
                loop: true,
                autoplay: { delay: 2000, disableOnInteraction: false },
                breakpoints: {
                    576: { slidesPerView: 3 },
                    768: { slidesPerView: 4 },
                    992: { slidesPerView: 5 }
                }
            });
        }

        // Gallery lightbox
        if ($.fn.magnificPopup) {
            $('.popup-image').magnificPopup({
                type: 'image',
                gallery: { enabled: true },
                zoom: { enabled: true, duration: 300 }
            });
            $('.popup-video').magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });
        }

        // Navbar toggle click
        $('.navbar-toggle').on('click', function() {
            $('.main-menu').toggleClass('show-menu');
        });

        // GSAP Text + Image animations
        if (typeof gsap !== 'undefined') {
            if (typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
            }
            if (typeof SplitText !== 'undefined') {
                gsap.registerPlugin(SplitText);
                document.querySelectorAll('.text-anime-style-2').forEach(function(el) {
                    try {
                        var split = new SplitText(el, { type: 'words,chars' });
                        gsap.fromTo(split.chars,
                            { opacity: 0, y: 25 },
                            {
                                opacity: 1, y: 0,
                                stagger: 0.022,
                                duration: 0.55,
                                ease: 'power2.out',
                                scrollTrigger: {
                                    trigger: el,
                                    start: 'top 88%'
                                }
                            }
                        );
                    } catch(e) {}
                });
            }

            // image-anime reveal
            document.querySelectorAll('.image-anime:not(.reveal-done)').forEach(function(el) {
                el.classList.add('reveal-done');
                gsap.fromTo(el,
                    { clipPath: 'inset(0 100% 0 0)', opacity: 0 },
                    {
                        clipPath: 'inset(0 0% 0 0)', opacity: 1,
                        duration: 1.0,
                        ease: 'power3.inOut',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top 88%'
                        }
                    }
                );
            });
        }

        /*
         * Guvenlik agi: GSAP yuklenmediyse ya da animasyon calismadiysa
         * `.reveal` (CSS'te visibility:hidden) ogeleri kalici gorunmez kalir.
         * 2.5 sn sonra hala gizliyse zorla goster — icerik kaybolmasin.
         */
        setTimeout(function () {
            document.querySelectorAll('.reveal').forEach(function (el) {
                if (getComputedStyle(el).visibility === 'hidden') {
                    el.style.visibility = 'visible';
                }
            });
            document.querySelectorAll('.image-anime').forEach(function (el) {
                var s = getComputedStyle(el);
                if (parseFloat(s.opacity) === 0) {
                    el.style.opacity = '1';
                    el.style.clipPath = 'none';
                }
            });
        }, 2500);
    });
})(jQuery);
</script>
@stack('scripts')
