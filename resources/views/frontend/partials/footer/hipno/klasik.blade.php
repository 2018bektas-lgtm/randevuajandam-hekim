{{--
    Footer tasarımı: "Hipno Klasik"
    Temanın orijinal footer markup'ı (tema-1/2/3 CSS sınıfları) — veriler $f'ten.
--}}
@if($f['goster']['cta'])
    <div class="footer-cta-box">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-lg-6">
                    <div class="footer-cta-content">
                        <div class="section-title">
                            <h2 data-cursor="-opaque">{{ $f['cta_baslik'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="footer-cta-btn wow fadeInUp">
                        @if($f['telefon_gecerli'])
                            <a href="tel:{{ $f['telefon_raw'] }}" class="btn-default btn-phone">
                                <i class="fas fa-phone-volume"></i> {{ $f['telefon'] }}
                            </a>
                        @endif
                        <a href="{{ route('frontend.randevu') }}" class="btn-default btn-comment">
                            <i class="fas fa-calendar-check"></i> Randevu Al
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<footer class="main-footer ftr-klasik">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-logo">
                    @include('frontend.partials.footer._logo', ['f' => $f, 'hiza' => 'sol'])
                </div>
            </div>

            <div class="col-lg-8">
                <div class="about-footer">
                    <div class="section-title">
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $f['slogan'] }}</h2>
                    </div>
                    <div class="footer-contact-box">
                        @if($f['goster']['iletisim'] && $f['telefon_gecerli'])
                            <div class="footer-contact-item">
                                <p>Telefon</p>
                                <h3><a href="tel:{{ $f['telefon_raw'] }}">{{ $f['telefon'] }}</a></h3>
                            </div>
                        @endif
                        @if($f['goster']['iletisim'] && $f['eposta'] !== '')
                            <div class="footer-contact-item">
                                <p>E-posta</p>
                                <h3><a href="mailto:{{ $f['eposta'] }}">{{ $f['eposta'] }}</a></h3>
                            </div>
                        @endif
                        @if($f['goster']['iletisim'] && $f['adres'] !== '')
                            <div class="footer-contact-item">
                                <p>Adres</p>
                                <h3>{{ $f['adres'] }}</h3>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($f['goster']['sosyal'])
                    <div class="footer-social-links">
                        <h3>{{ $f['baslik_sosyal'] }}</h3>
                        <p>{{ $f['uzmanlik'] !== '' ? $f['uzmanlik'] : 'Ruh sağlığı' }} yolculuğunuzda güncel içerik ve duyurular.</p>
                        <ul>
                            @foreach($f['sosyal'] as $s)
                                <li>
                                    <a href="{{ $s['url'] }}" target="_blank" rel="noopener" aria-label="{{ $s['ad'] }}">
                                        <i class="{{ $s['ikon'] }}"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <div class="footer-copyright">
            <div class="row align-items-center gy-3">
                <div class="col-md-7">
                    <div class="footer-menu">
                        <ul>
                            @foreach($f['nav'] as $item)
                                <li>
                                    <a href="{{ $item['href'] }}"
                                       @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
                                </li>
                            @endforeach
                            @if($f['goster']['sayfalar'])
                                @foreach($f['sayfalar'] as $sayfa)
                                    <li><a href="{{ $sayfa['href'] }}">{{ $sayfa['baslik'] }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="footer-copyright-text">
                        <p>
                            {{ $f['telif'] }}
                            @if($f['goster']['marka'])
                                <a href="https://randevuajandam.com" target="_blank" rel="noopener">Randevu Ajandam</a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
