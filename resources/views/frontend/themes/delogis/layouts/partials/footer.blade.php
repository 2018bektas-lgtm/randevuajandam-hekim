@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $adSoyad = decode_text(trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')));
    $logo = $doktor['logo'] ?? null;
    $tel = $doktor['telefon'] ?? null;
    $telRaw = $doktor['telefon_raw'] ?? preg_replace('/\D+/', '', (string) $tel);
    $eposta = $doktor['e_posta'] ?? null;
    $adres = decode_text($doktor['adres'] ?? trim(($doktor['ilce'] ?? '').' '.($doktor['il'] ?? '')));
    $bio = plain_text($doktor['footer_metin'] ?? $doktor['kisa_bio'] ?? $doktor['slogan'] ?? 'Güvenilir, kişiye özel sağlık hizmeti.', 160);
    $sosyal = array_filter($doktor['sosyal'] ?? [], fn ($u) => filled($u));

    // Menü — header ile aynı kaynak
    $footerNav = collect(function_exists('site_nav') ? site_nav(is_array($doktor ?? null) ? $doktor : null) : [])
        ->filter(fn ($i) => ($i['match'] ?? '') !== 'frontend.anasayfa')
        ->take(7)
        ->values();

    // Çalışma saatleri metni
    $cs = $doktor['calisma_saatleri'] ?? [];
    $csText = 'Randevu ile';
    if (is_array($cs) && $cs !== []) {
        $parts = [];
        foreach ($cs as $gun => $saat) {
            if (is_array($saat)) {
                $saat = implode(' - ', array_filter($saat));
            }
            $saat = trim((string) $saat);
            if ($saat === '' || $saat === '-') {
                continue;
            }
            if (is_string($gun) && ! is_numeric($gun)) {
                $parts[] = decode_text($gun).': '.decode_text($saat);
            } else {
                $parts[] = decode_text($saat);
            }
            if (count($parts) >= 2) {
                break;
            }
        }
        if ($parts !== []) {
            $csText = implode(' · ', $parts);
        }
    }
@endphp
{{-- index3 site-footer birebir yapı --}}
<footer class="site-footer">
    <div class="site-footer__shape-1 float-bob-y">
        <img src="{{ $dg }}/images/shapes/site-footer-shape-1.png" alt="">
    </div>
    <div class="site-footer__top">
        <div class="container">
            <div class="site-footer__top-inner">
                <div class="site-footer__top-left">
                    <div class="site-footer__top-icon">
                        <span class="icon-business-people"></span>
                    </div>
                    <div class="site-footer__top-content">
                        <h3>
                            Çalışma saatleri:
                            <span>{{ $csText }}</span>
                        </h3>
                    </div>
                </div>
                <div class="site-footer__top-right">
                    @if(file_exists(public_path('themes/delogis/images/shapes/site-footer-social-shape.png')))
                        <div class="site-footer__social-shape-1 float-bob-y">
                            <img src="{{ $dg }}/images/shapes/site-footer-social-shape.png" alt="" class="zoom-fade">
                        </div>
                    @endif
                    @if(count($sosyal))
                        <div class="site-footer__social-title">
                            <p>Takip edin:</p>
                        </div>
                        <div class="site-footer__social">
                            @foreach ($sosyal as $key => $url)
                                @php
                                    $icon = match (strtolower((string) $key)) {
                                        'twitter', 'x' => 'fab fa-twitter',
                                        'facebook' => 'fab fa-facebook',
                                        'instagram' => 'fab fa-instagram',
                                        'linkedin' => 'fab fa-linkedin-in',
                                        'youtube' => 'fab fa-youtube',
                                        'pinterest' => 'fab fa-pinterest-p',
                                        default => 'fas fa-link',
                                    };
                                @endphp
                                <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $key }}"><i class="{{ $icon }}"></i></a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="site-footer__middle">
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                    <div class="footer-widget__column footer-widget__about">
                        <div class="footer-widget__logo">
                            <a href="{{ route('frontend.anasayfa') }}">
                                @if($logo)
                                    <img src="{{ $logo }}" alt="{{ $adSoyad }}">
                                @else
                                    <span class="dg-footer-logo-text">{{ $adSoyad }}</span>
                                @endif
                            </a>
                        </div>
                        <p class="footer-widget__about-text">{{ $bio }}</p>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                    <div class="footer-widget__column footer-widget__link">
                        <div class="footer-widget__title-box">
                            <h3 class="footer-widget__title">Keşfet</h3>
                        </div>
                        <ul class="footer-widget__link-list list-unstyled">
                            @foreach ($footerNav as $item)
                                <li>
                                    <a href="{{ $item['href'] }}"
                                       @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
                                </li>
                            @endforeach
                            <li><a href="{{ route('frontend.randevu') }}">Randevu Al</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                    <div class="footer-widget__column footer-widget__Contact">
                        <div class="footer-widget__title-box">
                            <h3 class="footer-widget__title">İletişim</h3>
                        </div>
                        <ul class="footer-widget__Contact-list list-unstyled">
                            @if($adres !== '')
                                <li>
                                    <div class="icon">
                                        <span class="fas fa-map-marker"></span>
                                    </div>
                                    <div class="text">
                                        <span>Adres</span>
                                        <p>{{ $adres }}</p>
                                    </div>
                                </li>
                            @endif
                            @if($eposta)
                                <li>
                                    <div class="icon">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                    <div class="text">
                                        <span>E-posta</span>
                                        <p><a href="mailto:{{ $eposta }}">{{ $eposta }}</a></p>
                                    </div>
                                </li>
                            @endif
                            @if($tel)
                                <li>
                                    <div class="icon">
                                        <span class="fas fa-phone-square"></span>
                                    </div>
                                    <div class="text">
                                        <span>Telefon</span>
                                        <p><a href="tel:{{ $telRaw }}">{{ $tel }}</a></p>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                    <div class="footer-widget__column footer-widget__newsletter">
                        <div class="footer-widget__title-box">
                            <h3 class="footer-widget__title">Randevu</h3>
                        </div>
                        <p class="footer-widget__newsletter-text">
                            Online randevu alın; size en uygun gün ve saati seçin.
                        </p>
                        <form class="footer-widget__newsletter-form" action="{{ route('frontend.randevu') }}" method="get">
                            <div class="footer-widget__newsletter-input-box">
                                <input type="text" placeholder="Randevu için tıklayın" readonly onclick="window.location.href='{{ route('frontend.randevu') }}'" style="cursor:pointer">
                                <button type="submit" class="footer-widget__newsletter-btn">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                        <div style="margin-top:16px">
                            <a href="{{ route('frontend.randevu') }}" class="thm-btn">Randevu Al</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="site-footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-footer__bottom-inner">
                        <p class="site-footer__bottom-text">
                            © {{ date('Y') }}
                            <a href="{{ route('frontend.anasayfa') }}">{{ $adSoyad }}</a>
                            · Tüm hakları saklıdır.
                            ·
                            <a href="https://randevuajandam.com" target="_blank" rel="noopener">Randevu Ajandam</a>
                            ile hazırlanmıştır.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
