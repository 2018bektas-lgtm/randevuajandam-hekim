{{--
    Hero (Statik) — tema-1
    @param array $ayar   config/tema_modulleri.php > tema-1 > hero_static > alanlar
    @param array $doktor Ana platform API verisi (profil_resmi, ad_soyad vs.)
--}}
@php
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $arkaplan = $ayar['arkaplan_resmi'] ?: ($doktor['profil_resmi'] ?? null);
    $uzmanliklar = collect(preg_split("/\r\n|\n|\r/", (string) ($ayar['uzmanlik_listesi'] ?? '')))
        ->map(fn ($s) => trim($s))
        ->filter()
        ->take(6)
        ->values();
@endphp

<div class="hero parallaxie"@if($arkaplan) style="background-image:url('{{ $arkaplan }}')"@endif>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="hero-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $ayar['ust_baslik'] ?? '' }}</h3>
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? $doktorAd }}</h1>
                        @if(!empty($ayar['aciklama']))
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                        @endif
                    </div>
                    <div class="hero-content-body">
                        <div class="hero-btn wow fadeInUp" data-wow-delay="0.4s">
                            <a href="{{ route('frontend.randevu') }}" class="btn-default">{{ $ayar['cta_metin'] ?? 'Randevu Al' }}</a>
                        </div>
                        @if(!empty($ayar['sosyal_kanit_goster']) && !empty($ayar['sosyal_kanit_sayi']))
                            <div class="hero-client-box">
                                <div class="hero-client-content">
                                    <p><span class="counter">{{ (int) $ayar['sosyal_kanit_sayi'] }}</span>+ <span>{{ $ayar['sosyal_kanit_metin'] ?? 'Danışan' }}</span></p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if($uzmanliklar->isNotEmpty())
                    <div class="hero-list wow fadeInUp" data-wow-delay="0.6s">
                        <ul>
                            @foreach($uzmanliklar as $u)
                                <li>{{ $u }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
