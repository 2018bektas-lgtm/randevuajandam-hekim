{{--
    Randevu Formu (sayfa altı CTA) — tema-1
    Ana wizard'a link verir; wizard için ayrı route var.
--}}
@php
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
@endphp

<div class="our-appointment">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="our-appointment-box">
                    <div class="appointment-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Randevu' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Hemen randevu alın' }}</h2>
                            @if(!empty($ayar['aciklama']))
                                <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                            @endif
                        </div>

                        <div class="appointment-content-body">
                            @if(!empty($doktor['telefon']))
                                <div class="appointment-item wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="icon-box">
                                        <i class="fa-solid fa-phone" style="font-size:1.4rem;color:var(--accent-color)"></i>
                                    </div>
                                    <div class="appointment-item-content">
                                        <h3>Telefon</h3>
                                        <p><a href="tel:{{ $doktor['telefon_raw'] ?? preg_replace('/[^0-9+]/', '', $doktor['telefon']) }}" style="color:inherit">{{ $doktor['telefon'] }}</a></p>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($doktor['e_posta']))
                                <div class="appointment-item wow fadeInUp" data-wow-delay="0.4s">
                                    <div class="icon-box">
                                        <i class="fa-solid fa-envelope" style="font-size:1.4rem;color:var(--accent-color)"></i>
                                    </div>
                                    <div class="appointment-item-content">
                                        <h3>E-posta</h3>
                                        <p><a href="mailto:{{ $doktor['e_posta'] }}" style="color:inherit">{{ $doktor['e_posta'] }}</a></p>
                                    </div>
                                </div>
                            @endif
                            <div class="wow fadeInUp" data-wow-delay="0.5s" style="margin-top:1.5rem">
                                <a href="{{ route('frontend.randevu') }}" class="btn-default">Online Randevu Al</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
