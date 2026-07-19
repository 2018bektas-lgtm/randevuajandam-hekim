<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
(function () {
    'use strict';

    // Mobile menu
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    btn?.addEventListener('click', () => menu?.classList.toggle('open'));

    // Sticky header
    const header = document.getElementById('site-header');
    const onScroll = () => header?.classList.toggle('is-scrolled', window.scrollY > 8);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    function initSwiper(el, options) {
        if (!el || typeof Swiper === 'undefined') return null;
        try {
            return new Swiper(el, options);
        } catch (err) {
            console.warn('Swiper init skipped:', err);
            return null;
        }
    }

    // ---------- YILMAZKIRAN-STYLE HERO SLIDER ----------
    const heroEl = document.querySelector('.yk-hero-swiper');
    if (heroEl) {
        const slides = heroEl.querySelectorAll('.swiper-slide');
        const multi = slides.length > 1;
        const nextEl = heroEl.querySelector('.yk-hero-next');
        const prevEl = heroEl.querySelector('.yk-hero-prev');
        const pagEl = heroEl.querySelector('.yk-hero-pagination');

        const animateCounters = (root) => {
            if (!root) return;
            root.querySelectorAll('.yk-hero-stat-number[data-count]').forEach((el) => {
                const target = parseInt(el.getAttribute('data-count') || '0', 10);
                const suffix = el.getAttribute('data-suffix') || '';
                const duration = 1800;
                let startTime = null;
                const step = (ts) => {
                    if (!startTime) startTime = ts;
                    const p = Math.min((ts - startTime) / duration, 1);
                    el.textContent = Math.floor(p * target).toLocaleString('tr-TR') + suffix;
                    if (p < 1) requestAnimationFrame(step);
                };
                el.textContent = '0' + suffix;
                requestAnimationFrame(step);
            });
        };

        const heroOpts = {
            // loop tek slaytta / eksik elemanlarda hata üretebilir
            loop: multi,
            speed: 800,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            grabCursor: multi,
            allowTouchMove: true,
            keyboard: { enabled: true },
            on: {
                init(sw) {
                    const active = sw.slides[sw.activeIndex];
                    animateCounters(active);
                },
                slideChangeTransitionEnd(sw) {
                    const active = sw.slides[sw.activeIndex];
                    animateCounters(active);
                },
            },
        };

        if (multi) {
            heroOpts.autoplay = {
                delay: 6000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            };
            if (nextEl && prevEl) {
                heroOpts.navigation = { nextEl, prevEl };
            }
            if (pagEl) {
                heroOpts.pagination = { el: pagEl, clickable: true };
            }
        }

        initSwiper(heroEl, heroOpts);
    }

    // ---------- MEDIPLUS MODERN HERO SLIDER ----------
    const mpHeroEl = document.querySelector('.mp-hero-swiper');
    if (mpHeroEl) {
        const slides = mpHeroEl.querySelectorAll('.swiper-slide');
        const multi = slides.length > 1;
        const nextEl = mpHeroEl.querySelector('.mp-hero-next');
        const prevEl = mpHeroEl.querySelector('.mp-hero-prev');
        const pagEl = mpHeroEl.querySelector('.mp-hero-pagination');
        const mpOpts = {
            loop: multi,
            speed: 700,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            grabCursor: multi,
            allowTouchMove: true,
            keyboard: { enabled: true },
        };
        if (multi) {
            mpOpts.autoplay = {
                delay: 5500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            };
            if (nextEl && prevEl) {
                mpOpts.navigation = { nextEl, prevEl };
            }
            if (pagEl) {
                mpOpts.pagination = { el: pagEl, clickable: true };
            }
        }
        initSwiper(mpHeroEl, mpOpts);
    }

    // Services slider
    const servicesEl = document.querySelector('.services-swiper');
    if (servicesEl) {
        const section = servicesEl.closest('section') || document;
        const svcNext = section.querySelector('.svc-next');
        const svcPrev = section.querySelector('.svc-prev');
        const svcOpts = {
            slidesPerView: 1.12,
            spaceBetween: 16,
            grabCursor: true,
            breakpoints: {
                640: { slidesPerView: 1.55 },
                900: { slidesPerView: 2.35 },
                1100: { slidesPerView: 3 },
            },
        };
        if (svcNext && svcPrev) {
            svcOpts.navigation = { nextEl: svcNext, prevEl: svcPrev };
        }
        initSwiper(servicesEl, svcOpts);
    }

    // Testimonials slider
    const testimonialsEl = document.querySelector('.testimonials-swiper');
    if (testimonialsEl) {
        const section = testimonialsEl.closest('section') || document;
        const pag = section.querySelector('.testimonials-pagination');
        const tCount = testimonialsEl.querySelectorAll('.swiper-slide').length;
        const tOpts = {
            slidesPerView: 1,
            spaceBetween: 16,
            loop: tCount > 2,
            autoplay: tCount > 1 ? { delay: 4500, disableOnInteraction: false } : false,
            breakpoints: {
                720: { slidesPerView: Math.min(2, tCount) },
                1024: { slidesPerView: Math.min(3, tCount) },
            },
        };
        if (pag) {
            tOpts.pagination = { el: pag, clickable: true };
        }
        initSwiper(testimonialsEl, tOpts);
    }

    // Reviews slider
    const reviewsEl = document.querySelector('.reviews-swiper');
    if (reviewsEl) {
        const rCount = reviewsEl.querySelectorAll('.swiper-slide').length;
        initSwiper(reviewsEl, {
            slidesPerView: 1,
            spaceBetween: 16,
            grabCursor: true,
            loop: rCount > 2,
            breakpoints: {
                720: { slidesPerView: Math.min(2, rCount) },
                1024: { slidesPerView: Math.min(3, rCount) },
            },
        });
    }

    // BA slider
    const baEl = document.querySelector('.ba-swiper');
    if (baEl) {
        const section = baEl.closest('section') || document;
        const pag = section.querySelector('.ba-pagination');
        const baOpts = {
            slidesPerView: 1,
            spaceBetween: 18,
            breakpoints: { 800: { slidesPerView: 2 } },
        };
        if (pag) {
            baOpts.pagination = { el: pag, clickable: true };
        }
        initSwiper(baEl, baOpts);
    }

    // Counters
    const animateCounter = (el) => {
        const target = parseFloat(el.dataset.counter);
        if (Number.isNaN(target)) return;
        const suffix = el.dataset.suffix || '';
        const isFloat = String(el.dataset.counter).includes('.');
        const duration = 1400;
        const start = performance.now();
        const step = (now) => {
            const p = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - p, 3);
            const val = target * eased;
            el.textContent = (isFloat ? val.toFixed(1) : Math.floor(val).toLocaleString('tr-TR')) + suffix;
            if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('visible');
            entry.target.querySelectorAll('[data-counter]:not([data-done])').forEach((el) => {
                el.dataset.done = '1';
                animateCounter(el);
            });
            if (entry.target.matches('[data-counter]') && !entry.target.dataset.done) {
                entry.target.dataset.done = '1';
                animateCounter(entry.target);
            }
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.18 });

    document.querySelectorAll('.reveal, .stats-panel').forEach((el) => observer.observe(el));
})();
</script>
@stack('scripts')
