@extends(theme_layout())

@section('baslik', 'İletişim | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))

@section('icerik')
@php
    $photo    = $doktor['profil_resmi'] ?? null;
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $sosyal   = $doktor['sosyal'] ?? [];
    $sosyalIkonlar = [
        'instagram' => 'fa-brands fa-instagram',
        'facebook'  => 'fa-brands fa-facebook-f',
        'twitter'   => 'fa-brands fa-x-twitter',
        'youtube'   => 'fa-brands fa-youtube',
        'linkedin'  => 'fa-brands fa-linkedin-in',
        'tiktok'    => 'fa-brands fa-tiktok',
        'whatsapp'  => 'fa-brands fa-whatsapp',
    ];
@endphp

@include('frontend.themes.tema-1.partials.page-banner', [
    'kod' => 'iletisim',
    'baslik' => 'İletişim',
    'breadcrumb' => [['label' => 'İletişim', 'aktif' => true]],
])

<div class="our-appointment">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="our-appointment-box">
                    {{-- Map / Contact Info --}}
                    <div class="appointment-form">
                        @if(!empty($doktor['maps_embed']))
                            <div style="border-radius:.5rem;overflow:hidden;height:100%;min-height:350px">
                                {!! $doktor['maps_embed'] !!}
                            </div>
                        @else
                            <div style="padding:2rem;background:var(--primary-color);border-radius:.5rem;height:100%;display:flex;flex-direction:column;justify-content:center;gap:1.5rem">
                                @if(!empty($doktor['adres']))
                                <div style="display:flex;gap:1rem;align-items:flex-start">
                                    <i class="fa-solid fa-location-dot" style="color:var(--accent-color);font-size:1.2rem;margin-top:3px;flex-shrink:0"></i>
                                    <div>
                                        <span style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:.2rem">Adres</span>
                                        <span style="color:#fff">{{ $doktor['adres'] }}@if(!empty($doktor['il'])), {{ $doktor['il'] }}@endif</span>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($doktor['e_posta']))
                                <div style="display:flex;gap:1rem;align-items:flex-start">
                                    <i class="fa-solid fa-envelope" style="color:var(--accent-color);font-size:1.2rem;margin-top:3px;flex-shrink:0"></i>
                                    <div>
                                        <span style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:.2rem">E-posta</span>
                                        <a href="mailto:{{ $doktor['e_posta'] }}" style="color:#fff">{{ $doktor['e_posta'] }}</a>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($doktor['calisma_saatleri_text']))
                                <div style="display:flex;gap:1rem;align-items:flex-start">
                                    <i class="fa-solid fa-clock" style="color:var(--accent-color);font-size:1.2rem;margin-top:3px;flex-shrink:0"></i>
                                    <div>
                                        <span style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:.2rem">Çalışma Saatleri</span>
                                        <span style="color:#fff">{{ $doktor['calisma_saatleri_text'] }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Contact Details --}}
                    <div class="our-appointment-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">iletişim</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Bizimle iletişime geçin</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">
                                {{ $doktor['kisa_bio'] ?? 'Sorularınız ve randevu için bize ulaşabilirsiniz.' }}
                            </p>
                        </div>
                        <div class="appointment-content-body">
                            @if(!empty($doktor['telefon']))
                            <div class="appointment-item wow fadeInUp" data-wow-delay="0.4s">
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
                            <div class="appointment-item wow fadeInUp" data-wow-delay="0.5s">
                                <div class="icon-box">
                                    <i class="fa-solid fa-envelope" style="font-size:1.4rem;color:var(--accent-color)"></i>
                                </div>
                                <div class="appointment-item-content">
                                    <h3>E-posta</h3>
                                    <p><a href="mailto:{{ $doktor['e_posta'] }}" style="color:inherit">{{ $doktor['e_posta'] }}</a></p>
                                </div>
                            </div>
                            @endif
                            @if(!empty($doktor['adres']))
                            <div class="appointment-item wow fadeInUp" data-wow-delay="0.6s">
                                <div class="icon-box">
                                    <i class="fa-solid fa-location-dot" style="font-size:1.4rem;color:var(--accent-color)"></i>
                                </div>
                                <div class="appointment-item-content">
                                    <h3>Adres</h3>
                                    <p>{{ $doktor['adres'] }}@if(!empty($doktor['il'])), {{ $doktor['il'] }}@endif</p>
                                </div>
                            </div>
                            @endif

                            @if(!empty(array_filter($sosyal)))
                            <div class="wow fadeInUp" data-wow-delay="0.7s" style="margin-top:1.5rem">
                                <h3 style="color:var(--primary-color);font-size:1rem;margin-bottom:.75rem">Sosyal Medya</h3>
                                <div style="display:flex;gap:.75rem;flex-wrap:wrap">
                                    @foreach ($sosyal as $platform => $url)
                                        @if(!empty($url) && isset($sosyalIkonlar[$platform]))
                                        <a href="{{ $url }}" target="_blank" rel="noopener"
                                           style="width:40px;height:40px;border-radius:50%;background:var(--primary-color);color:#fff;display:flex;align-items:center;justify-content:center;text-decoration:none;transition:background .2s"
                                           onmouseover="this.style.background='var(--accent-color)'" onmouseout="this.style.background='var(--primary-color)'"
                                           aria-label="{{ ucfirst($platform) }}">
                                            <i class="{{ $sosyalIkonlar[$platform] }}"></i>
                                        </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="wow fadeInUp" data-wow-delay="0.8s" style="margin-top:2rem">
                                <a href="{{ route('frontend.randevu') }}" class="btn-default">Online Randevu Al</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
