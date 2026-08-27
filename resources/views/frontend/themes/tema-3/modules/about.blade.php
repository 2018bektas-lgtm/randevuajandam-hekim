{{-- Hakkımda — tek foto --}}
@php
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $coz = static function ($path) {
        if (! filled($path)) {
            return null;
        }
        $url = function_exists('media_url') ? media_url((string) $path) : (string) $path;

        return filled($url) ? $url : (string) $path;
    };
    $img = $coz($ayar['resim_1'] ?? null)
        ?: $coz($doktor['profil_resmi'] ?? null)
        ?: $coz($doktor['foto'] ?? null);
    $misyonMadde = collect(preg_split("/\r\n|\n|\r/", (string) ($ayar['misyon_maddeler'] ?? '')))->map(fn ($s) => trim($s))->filter()->values();
@endphp

<div class="about-us">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                @if($img)
                    <div class="about-us-images is-single">
                        <div class="about-img-1">
                            <figure class="image-anime">
                                <img src="{{ $img }}" alt="{{ $doktorAd }}">
                            </figure>
                        </div>
                    </div>
                @endif
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
                        @if(!empty($ayar['vizyon_metin']))
                            <div class="vision-mission-content wow fadeInUp" data-wow-delay="0.4s">
                                <h3>{{ $ayar['vizyon_baslik'] ?? 'Vizyonum' }}</h3>
                                <p>{{ $ayar['vizyon_metin'] }}</p>
                            </div>
                        @endif
                        @if(!empty($ayar['misyon_metin']) || $misyonMadde->isNotEmpty())
                            <div class="vision-mission-content wow fadeInUp" data-wow-delay="0.4s">
                                <h3>{{ $ayar['misyon_baslik'] ?? 'Misyonum' }}</h3>
                                @if($misyonMadde->isNotEmpty())
                                    <ul>
                                        @foreach($misyonMadde as $m)
                                            <li>{{ $m }}</li>
                                        @endforeach
                                    </ul>
                                @elseif(!empty($ayar['misyon_metin']))
                                    <p>{{ $ayar['misyon_metin'] }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="about-us-content-btn wow fadeInUp" data-wow-delay="0.6s">
                        <a href="{{ route('frontend.hakkimda') }}" class="btn-default">{{ $ayar['buton_1'] ?? 'Daha fazla' }}</a>
                        <a href="{{ route('frontend.iletisim') }}" class="btn-default btn-highlighted">{{ $ayar['buton_2'] ?? 'İletişim' }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('head')
<style>
.about-us-images.is-single{display:block!important;position:relative;flex-wrap:nowrap}
.about-us-images.is-single .about-img-1{width:100%!important;max-width:480px}
.about-us-images.is-single .about-img-1 img{aspect-ratio:3/4;width:100%;object-fit:cover;border-radius:20px}
.about-us-images.is-single .about-img-2,
.about-us-images.is-single .about-customer-box{display:none!important}
</style>
@endpush
