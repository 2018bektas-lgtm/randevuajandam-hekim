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
    $unvan = trim((string) ($doktor['unvan'] ?? ''));
    $adSoyad = trim((string) ($doktor['ad_soyad'] ?? 'Hekim'));
    $doktorAd = trim($unvan.' '.$adSoyad);
    $telefon = $doktor['telefon'] ?? null;
    $telefonRaw = $doktor['telefon_raw'] ?? preg_replace('/[^0-9+]/', '', (string) $telefon);
    $telDigits = preg_replace('/\D+/', '', (string) $telefonRaw);
    $telefonGecerli = filled($telefon)
        && strlen($telDigits) >= 10
        && ! preg_match('/^0*5320{5,}$/', $telDigits)
        && ! preg_match('/^0+$/', $telDigits);
    $eposta = $doktor['e_posta'] ?? null;
    $adres = $doktor['adres'] ?? null;
    $il = $doktor['il'] ?? null;
    $adresSatir = trim(implode(', ', array_filter([$adres, $il])));
    $footerNav = function_exists('site_footer_nav')
        ? site_footer_nav(is_array($doktor ?? null) ? $doktor : null)
        : array_slice($nav ?? [], 0, 5);
    $wpNum = preg_replace('/[^0-9]/', '', (string) ($doktor['whatsapp'] ?? ($telefonGecerli ? $telefonRaw : '')));
    $hasSosyal = ! empty(array_filter($sosyal));
    $slogan = $doktor['slogan'] ?? 'Hazır olduğunuzda buradayız';
@endphp

<div class="footer-cta-box">
    <div class="container">
        <div class="row align-items-center gy-3">
            <div class="col-lg-6">
                <div class="footer-cta-content">
                    <div class="section-title">
                        <h2 class="text-anime-style-2" data-cursor="-opaque"></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="footer-cta-btn wow fadeInUp">
                    @if($telefonGecerli)
                        <a href="tel:{{ $telefonRaw }}" class="btn-default btn-phone">
                            <i class="fa-solid fa-phone-volume"></i> {{ $telefon }}
                        </a>
                    @endif
                    <a href="{{ route('frontend.randevu') }}" class="btn-default btn-comment">
                        <i class="fa-solid fa-calendar-check"></i> Randevu Al
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-logo">
                    <a href="{{ route('frontend.anasayfa') }}" class="footer-brand">
                        @if(!empty($doktor['logo']))
                            <img src="{{ $doktor['logo'] }}" alt="{{ $doktorAd }}">
                        @else
                            <span class="footer-wordmark">
                                @if($unvan !== '')
                                    <span class="footer-wordmark-unvan">{{ $unvan }}</span>
                                @endif
                                <span class="footer-wordmark-ad">{{ $adSoyad }}</span>
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="about-footer">
                    <div class="section-title">
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $slogan }}</h2>
                    </div>
                    <div class="footer-contact-box">
                        @if($telefonGecerli)
                            <div class="footer-contact-item">
                                <p>Telefon</p>
                                <h3><a href="tel:{{ $telefonRaw }}">{{ $telefon }}</a></h3>
                            </div>
                        @endif
                        @if($eposta)
                            <div class="footer-contact-item">
                                <p>E-posta</p>
                                <h3><a href="mailto:{{ $eposta }}">{{ $eposta }}</a></h3>
                            </div>
                        @endif
                        @if($adresSatir !== '')
                            <div class="footer-contact-item">
                                <p>Adres</p>
                                <h3>{{ $adresSatir }}</h3>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="footer-social-links">
                    <h3>Bizi takip edin</h3>
                    <p>{{ $doktor['uzmanlik'] ?? 'Ruh sağlığı' }} yolculuğunuzda güncel içerik ve duyurular.</p>
                    @if($hasSosyal || $wpNum)
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
                            @if($wpNum && empty($sosyal['whatsapp']))
                                <li>
                                    <a href="https://wa.me/{{ $wpNum }}" target="_blank" rel="noopener" aria-label="WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-copyright">
            <div class="row align-items-center gy-3">
                <div class="col-md-7">
                    <div class="footer-menu">
                        <ul>
                            @foreach ($footerNav as $item)
                                <li>
                                    <a href="{{ $item['href'] ?? (function_exists('nav_href') ? nav_href($item) : '#') }}"
                                       @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="footer-copyright-text">
                        <p>© {{ date('Y') }} {{ $adSoyad }}. Tüm hakları saklıdır.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

@if($wpNum)
<a href="https://wa.me/{{ $wpNum }}" target="_blank" rel="noopener"
   class="wa-float" aria-label="WhatsApp ile iletişim">
    <i class="fa-brands fa-whatsapp"></i>
</a>
@endif

<style>
.footer-brand{display:inline-flex;align-items:center;text-decoration:none}
.footer-brand img{max-height:48px;width:auto}
.footer-wordmark{display:flex;flex-direction:column;line-height:1.15}
.footer-wordmark-unvan{font-family:var(--font);font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--accent-color);font-weight:600}
.footer-wordmark-ad{font-family:var(--display);font-size:1.65rem;color:var(--primary-color);font-weight:400}
.footer-contact-item h3 a{color:inherit;text-decoration:none;word-break:break-word}
.footer-contact-item h3 a:hover{color:var(--accent-color)}
.footer-contact-box{flex-wrap:wrap;align-items:flex-start}
.footer-contact-item{min-width:160px;max-width:280px}
.footer-menu ul{display:flex;flex-wrap:wrap;gap:.35rem 1.5rem}
.footer-menu ul li{margin-right:0!important}
.footer-cta-btn{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:12px}
.footer-cta-btn .btn-default.btn-comment{margin-left:0}
.wa-float{position:fixed;bottom:24px;right:24px;z-index:9999;width:52px;height:52px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 22px rgba(37,211,102,.38);transition:transform .2s}
.wa-float i{color:#fff;font-size:26px;line-height:1}
.wa-float:hover{transform:scale(1.08);color:#fff}
@media (max-width:991px){
    .footer-cta-btn{justify-content:center}
    .footer-copyright-text{text-align:left!important}
}
@media (max-width:640px){
    .footer-cta-btn .btn-default{width:100%;text-align:center}
    .footer-wordmark-ad{font-size:1.35rem}
    .wa-float{bottom:18px;right:18px}
}
</style>
