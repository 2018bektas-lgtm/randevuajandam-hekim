@extends(theme_layout())

@section('baslik', 'Hakkımda | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? '')

@section('icerik')
@php
    $photo    = $doktor['profil_resmi'] ?? null;
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
@endphp

@include('frontend.themes.tema-1.partials.page-banner', [
    'kod' => 'hakkimda',
    'baslik' => 'Hakkımda',
    'breadcrumb' => [['label' => 'Hakkımda', 'aktif' => true]],
])

{{-- About Section --}}
<div class="about-us">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-us-images">
                    <div class="about-img-1">
                        <figure class="image-anime">
                            <img src="{{ $photo ?? asset('vendor/hipno/images/about-img-1.jpg') }}" alt="{{ $doktorAd }}">
                        </figure>
                    </div>
                    @if(!empty($doktor['profil_resmi2']))
                    <div class="about-img-2">
                        <figure class="image-anime">
                            <img src="{{ $doktor['profil_resmi2'] }}" alt="{{ $doktorAd }}">
                        </figure>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-us-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $doktorAd }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">
                            {{ $doktor['uzmanlik'] ?? 'Uzman Hekim' }}
                            @if(!empty($doktor['il'])) — {{ $doktor['il'] }}@endif
                        </h2>
                        @if(!empty($doktor['kisa_bio']))
                        <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $doktor['kisa_bio'] }}</p>
                        @endif
                    </div>
                    @if(!empty($doktor['branslar']))
                    <div class="about-vision-mission">
                        <div class="vision-mission-content wow fadeInUp" data-wow-delay="0.4s">
                            <h3>Uzmanlık Alanları</h3>
                            <ul>
                                @foreach ($doktor['branslar'] as $br)
                                <li>{{ $br }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                    <div class="about-us-content-btn wow fadeInUp" data-wow-delay="0.6s">
                        <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                        <a href="{{ route('frontend.iletisim') }}" class="btn-default btn-highlighted">İletişim</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Full Bio --}}
@if(!empty($doktor['bio']))
<div class="our-services" style="padding-top:0">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="section-title" style="text-align:left">
                    <h3 class="wow fadeInUp">Özgeçmiş</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Eğitim ve deneyim</h2>
                </div>
                <div class="wow fadeInUp" data-wow-delay="0.2s" style="color:var(--text-color);line-height:1.8;font-size:1.05rem">
                    {!! nl2br(e($doktor['bio'])) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Özelliklerin / Why Choose Us --}}
@if(!empty($doktor['ozellikler']))
<div class="why-choose-us">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="why-choose-us-box">
                    <div class="why-choose-image">
                        <figure class="image-anime reveal">
                            <img src="{{ $photo ?? asset('vendor/hipno/images/why-choose-img-1.jpg') }}" alt="{{ $doktorAd }}">
                        </figure>
                    </div>
                    <div class="why-choose-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">yaklaşımım</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Neden benimle çalışmalısınız?</h2>
                        </div>
                        <div class="why-choose-list">
                            @foreach (array_slice($doktor['ozellikler'], 0, 4) as $i => $oz)
                            <div class="why-choose-item wow fadeInUp" data-wow-delay="{{ $i * 0.2 }}s">
                                <div class="icon-box">
                                    <img src="{{ asset('vendor/hipno/images/icon-why-choose-'.($i+1).'.svg') }}" alt="" onerror="this.style.display='none'">
                                </div>
                                <div class="why-choose-item-content">
                                    <h3>{{ $oz['baslik'] }}</h3>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Stats --}}
@if(!empty($doktor['istatistikler']))
<div class="what-we-do">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="intro-video-box" style="border-radius:.75rem;overflow:hidden">
                    <div class="intro-video-counter">
                        @foreach ($doktor['istatistikler'] as $ist)
                        <div class="video-counter-item">
                            <h2><span class="counter">{{ preg_replace('/\D/', '', $ist['deger'] ?? '0') }}</span>{{ $ist['suffix'] ?? '' }}</h2>
                            <p>{{ $ist['etiket'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- CTA --}}
<div class="cta-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cta-box">
                    <div class="cta-box-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">randevu</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Online randevu alın</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Hemen randevu oluşturarak uzman değerlendirmesinden yararlanın.</p>
                        </div>
                        <div class="cta-box-btn wow fadeInUp">
                            <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
