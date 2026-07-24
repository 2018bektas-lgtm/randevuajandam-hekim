@php
    $sosyal = $doktor['sosyal'] ?? [];
    $sosyalIkonlar = [
        'instagram' => 'fa-brands fa-instagram',
        'facebook'  => 'fa-brands fa-facebook-f',
        'twitter'   => 'fa-brands fa-x-twitter',
        'youtube'   => 'fa-brands fa-youtube',
        'linkedin'  => 'fa-brands fa-linkedin-in',
        'tiktok'    => 'fa-brands fa-tiktok',
        'pinterest' => 'fa-brands fa-pinterest-p',
        'whatsapp'  => 'fa-brands fa-whatsapp',
    ];
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $telefon = $doktor['telefon'] ?? null;
    $telefonRaw = $doktor['telefon_raw'] ?? preg_replace('/[^0-9+]/', '', $telefon ?? '');
    $eposta = $doktor['e_posta'] ?? null;
    $adres = $doktor['adres'] ?? null;
    $footerNav = array_slice($nav ?? [], 0, 5);
    $wpNum = $doktor['whatsapp'] ?? $telefonRaw;
@endphp

@if($telefon || $eposta)
<div class="footer-cta-box">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="footer-cta-content">
                    <div class="section-title">
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Ücretsiz danışma için arayın</h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="footer-cta-btn wow fadeInUp">
                    @if($telefon)
                    <a href="tel:{{ $telefonRaw }}" class="btn-default btn-phone">
                        <i class="fa-solid fa-phone-volume"></i> {{ $telefon }}
                    </a>
                    @endif
                    @if($eposta)
                    <a href="mailto:{{ $eposta }}" class="btn-default btn-comment">
                        <i class="fa-solid fa-envelope"></i> E-posta Gönder
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-logo">
                    @if(!empty($doktor['logo']))
                        <img src="{{ $doktor['logo'] }}" alt="{{ $doktorAd }}" style="max-height:50px;width:auto">
                    @else
                        <span style="font-family:var(--accent-font,'Marcellus',serif);color:#fff;font-size:1.6rem">
                            {{ $doktorAd }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-lg-8">
                <div class="about-footer">
                    <div class="section-title">
                        <h2 class="text-anime-style-2" data-cursor="-opaque">
                            {{ $doktor['slogan'] ?? 'Sağlığınız için buradayız' }}
                        </h2>
                    </div>
                    <div class="footer-contact-box">
                        @if($telefon)
                        <div class="footer-contact-item">
                            <p>Telefon</p>
                            <h3><a href="tel:{{ $telefonRaw }}" style="color:inherit">{{ $telefon }}</a></h3>
                        </div>
                        @endif
                        @if($eposta)
                        <div class="footer-contact-item">
                            <p>E-posta</p>
                            <h3><a href="mailto:{{ $eposta }}" style="color:inherit">{{ $eposta }}</a></h3>
                        </div>
                        @endif
                        @if($adres)
                        <div class="footer-contact-item">
                            <p>Adres</p>
                            <h3>{{ $adres }}@if(!empty($doktor['il'])), {{ $doktor['il'] }}@endif</h3>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="footer-social-links">
                    <h3>Takip Edin</h3>
                    <p>{{ $doktor['uzmanlik'] ?? 'Sağlık' }} alanında güncel bilgiler için bizi takip edin</p>
                    @php $hasSosyal = !empty(array_filter($sosyal)); @endphp
                    @if($hasSosyal)
                    <ul>
                        @foreach ($sosyal as $platform => $url)
                            @if(!empty($url) && isset($sosyalIkonlar[$platform]))
                            <li>
                                <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($platform) }}">
                                    <i class="{{ $sosyalIkonlar[$platform] }}"></i>
                                </a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-copyright">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="footer-menu">
                        <ul>
                            @foreach ($footerNav as $item)
                            <li><a href="{{ nav_href($item) }}">{{ $item['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="footer-copyright-text">
                        <p>Copyright &copy; {{ date('Y') }} {{ $doktorAd }}. Tüm hakları saklıdır.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

@if(!empty($wpNum))
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wpNum) }}" target="_blank" rel="noopener"
   class="wa-float" aria-label="WhatsApp ile iletişim"
   style="position:fixed;bottom:24px;right:24px;z-index:9999;width:52px;height:52px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(37,211,102,.4);transition:transform .2s"
   onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
    <i class="fa-brands fa-whatsapp" style="color:#fff;font-size:26px"></i>
</a>
@endif
