{{--
    Hakkımda — tema-1
--}}
@php
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $img1 = $ayar['resim_1'] ?: ($doktor['profil_resmi'] ?? null);
    $img2 = $ayar['resim_2'] ?? null;
    $puan = max(1, min(5, (int) ($ayar['danisan_puani'] ?? 5)));
@endphp

<div class="about-us">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-us-images">
                    @if($img1)
                        <div class="about-img-1">
                            <figure class="image-anime reveal">
                                <img src="{{ $img1 }}" alt="{{ $doktorAd }}">
                            </figure>
                        </div>
                    @endif
                    @if($img2)
                        <div class="about-img-2">
                            <figure class="image-anime reveal">
                                <img src="{{ $img2 }}" alt="{{ $doktorAd }}">
                            </figure>
                        </div>
                    @endif
                    <div class="about-customer-box">
                        <div class="about-customer-rating">
                            @for($i = 0; $i < $puan; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                        </div>
                        <div class="about-customer-content">
                            <p>Danışan Memnuniyeti</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-us-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Hakkımda' }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? $doktorAd }}</h2>
                        @if(!empty($ayar['aciklama']))
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                        @endif
                    </div>
                    <div class="about-vision-mission">
                        @if(!empty($ayar['misyon_metin']))
                            <div class="vision-mission-item wow fadeInUp" data-wow-delay="0.4s">
                                <h4>{{ $ayar['misyon_baslik'] ?? 'Misyonum' }}</h4>
                                <p>{{ $ayar['misyon_metin'] }}</p>
                            </div>
                        @endif
                        @if(!empty($ayar['vizyon_metin']))
                            <div class="vision-mission-item wow fadeInUp" data-wow-delay="0.6s">
                                <h4>{{ $ayar['vizyon_baslik'] ?? 'Vizyonum' }}</h4>
                                <p>{{ $ayar['vizyon_metin'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
