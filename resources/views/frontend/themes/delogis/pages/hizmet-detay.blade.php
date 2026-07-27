@extends(theme_layout())

@php
    $h = $hizmet ?? [];
    $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
    $hDesc = $h['aciklama'] ?? $h['kisa'] ?? '';
@endphp

@section('baslik', $hAd.' | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', \Illuminate\Support\Str::limit(strip_tags((string)$hDesc), 160))

@section('icerik')
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $img = $h['image'] ?? $h['resim'] ?? $dg.'/images/resources/services-details-img-1.jpg';
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => $hAd, 'crumb' => 'Hizmetler'])

<section class="services-details">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="services-details__left">
                    <div class="services-details__img">
                        <img src="{{ $img }}" alt="{{ $hAd }}">
                    </div>
                    <h3 class="services-details__title-1">{{ $hAd }}</h3>
                    <div class="services-details__text-1 dg-prose">
                        {!! $hDesc !!}
                    </div>
                    @if(!empty($h['sure']) || !empty($h['fiyat']))
                        <ul class="list-unstyled services-details__points" style="margin-top:20px">
                            @if(!empty($h['sure']))
                                <li><div class="icon"><i class="fa fa-clock"></i></div><div class="text"><p>Süre: {{ $h['sure'] }}</p></div></li>
                            @endif
                            @if(!empty($h['fiyat']))
                                <li><div class="icon"><i class="fa fa-tag"></i></div><div class="text"><p>Ücret: {{ $h['fiyat'] }}</p></div></li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="services-details__sidebar">
                    <div class="services-details__services-box">
                        <h3 class="services-details__services-title">Hızlı erişim</h3>
                        <ul class="services-details__services-list list-unstyled">
                            <li><a href="{{ route('frontend.hizmetler') }}">Tüm hizmetler</a></li>
                            <li><a href="{{ route('frontend.randevu') }}">Randevu Al</a></li>
                            <li><a href="{{ route('frontend.iletisim') }}">İletişim</a></li>
                            <li><a href="{{ route('frontend.hakkimda') }}">Hakkımda</a></li>
                        </ul>
                    </div>
                    <div class="services-details__get-started" style="margin-top:24px">
                        <h3 class="services-details__get-started-title">Randevu alın</h3>
                        <p class="services-details__get-started-text">Online randevu ile size uygun saati seçin.</p>
                        <a href="{{ route('frontend.randevu') }}" class="thm-btn">Randevu Al</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
