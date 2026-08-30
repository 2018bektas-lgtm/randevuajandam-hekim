{{--
    Footer tasarımı: "Delogis Geniş"
    Temanın orijinal site-footer markup'ı — veriler $f'ten.
    Bu tasarım yalnızca delogis paketinin CSS'i yüklüyken doğru görünür.
--}}
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
@endphp

<footer class="site-footer">
    <div class="site-footer__shape-1 float-bob-y">
        <img src="{{ $dg }}/images/shapes/site-footer-shape-1.png" alt="" loading="lazy" decoding="async">
    </div>

    @if($f['goster']['cta'] || $f['goster']['sosyal'])
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
                                <span>{{ $f['saatler'] !== '' ? $f['saatler'] : 'Randevu ile' }}</span>
                            </h3>
                        </div>
                    </div>
                    <div class="site-footer__top-right">
                        @if(file_exists(public_path('themes/delogis/images/shapes/site-footer-social-shape.png')))
                            <div class="site-footer__social-shape-1 float-bob-y">
                                <img src="{{ $dg }}/images/shapes/site-footer-social-shape.png" alt="" class="zoom-fade" loading="lazy" decoding="async">
                            </div>
                        @endif
                        @if($f['goster']['sosyal'])
                            <div class="site-footer__social-title">
                                <p>{{ $f['baslik_sosyal'] }}:</p>
                            </div>
                            <div class="site-footer__social">
                                @foreach($f['sosyal'] as $s)
                                    <a href="{{ $s['url'] }}" target="_blank" rel="noopener" aria-label="{{ $s['ad'] }}">
                                        <i class="{{ $s['ikon'] }}"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container">
        <div class="site-footer__middle">
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                    <div class="footer-widget__column footer-widget__about">
                        <div class="footer-widget__logo">
                            @include('frontend.partials.footer._logo', ['f' => $f, 'hiza' => 'sol'])
                        </div>
                        @if($f['goster']['hakkinda'])
                            <p class="footer-widget__about-text">{{ $f['aciklama'] }}</p>
                        @endif
                    </div>
                </div>

                @if($f['goster']['kesfet'])
                    <div class="col-xl-2 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                        <div class="footer-widget__column footer-widget__link">
                            <div class="footer-widget__title-box">
                                <h3 class="footer-widget__title">{{ $f['baslik_kesfet'] }}</h3>
                            </div>
                            <ul class="footer-widget__link-list list-unstyled">
                                @foreach($f['nav'] as $item)
                                    <li>
                                        <a href="{{ $item['href'] }}"
                                           @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
                                    </li>
                                @endforeach
                                <li><a href="{{ route('frontend.randevu') }}">Randevu Al</a></li>
                            </ul>
                        </div>
                    </div>
                @endif

                @if($f['goster']['iletisim'])
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                        <div class="footer-widget__column footer-widget__Contact">
                            <div class="footer-widget__title-box">
                                <h3 class="footer-widget__title">{{ $f['baslik_iletisim'] }}</h3>
                            </div>
                            <ul class="footer-widget__Contact-list list-unstyled">
                                @if($f['adres'] !== '')
                                    <li>
                                        <div class="icon"><span class="fas fa-map-marker"></span></div>
                                        <div class="text">
                                            <span>Adres</span>
                                            <p>{{ $f['adres'] }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if($f['eposta'] !== '')
                                    <li>
                                        <div class="icon"><span class="fas fa-envelope"></span></div>
                                        <div class="text">
                                            <span>E-posta</span>
                                            <p><a href="mailto:{{ $f['eposta'] }}">{{ $f['eposta'] }}</a></p>
                                        </div>
                                    </li>
                                @endif
                                @if($f['telefon_gecerli'])
                                    <li>
                                        <div class="icon"><span class="fas fa-phone-square"></span></div>
                                        <div class="text">
                                            <span>Telefon</span>
                                            <p><a href="tel:{{ $f['telefon_raw'] }}">{{ $f['telefon'] }}</a></p>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif

                @if($f['goster']['randevu'])
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
                                    <input type="text" placeholder="Randevu için tıklayın" readonly
                                           onclick="window.location.href='{{ route('frontend.randevu') }}'" style="cursor:pointer">
                                    <button type="submit" class="footer-widget__newsletter-btn" aria-label="Randevu sayfası">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                            <div style="margin-top:16px">
                                <a href="{{ route('frontend.randevu') }}" class="thm-btn">Randevu Al</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="site-footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-footer__bottom-inner">
                        <p class="site-footer__bottom-text">
                            {{ $f['telif'] }}
                            @if($f['goster']['marka'])
                                ·
                                <a href="https://randevuajandam.com" target="_blank" rel="noopener">Randevu Ajandam</a>
                                ile hazırlanmıştır.
                            @endif
                            @if($f['goster']['sayfalar'])
                                <br>
                                @foreach($f['sayfalar'] as $i => $fp)
                                    @if($i > 0) · @endif
                                    <a href="{{ $fp['href'] }}">{{ $fp['baslik'] }}</a>
                                @endforeach
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
