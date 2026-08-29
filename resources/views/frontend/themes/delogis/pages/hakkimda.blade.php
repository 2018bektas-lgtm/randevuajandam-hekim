@extends(theme_layout())

@section('baslik', 'Hakkımda | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? $doktor['bio'] ?? '')

@section('icerik')
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $photo = function_exists('doctor_photo')
        ? doctor_photo($doktor ?? null, null)
        : ($doktor['profil_resmi'] ?? null);
    $ad = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $bio = $doktor['bio'] ?? $doktor['biyografi'] ?? $doktor['kisa_bio'] ?? '';
    $mezuniyet = collect($doktor['mezuniyet'] ?? [])->filter()->take(8);
    $ozellikler = collect($doktor['ozellikler'] ?? [])->take(4);
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'Hakkımda', 'crumb' => 'Hakkımda'])

<section class="about-four">
    <div class="container">
        <div class="row">
            @if($photo)
            <div class="col-xl-6 col-lg-5">
                <div class="about-four__left">
                    <div class="about-four__img">
                        <img src="{{ $photo }}" alt="{{ $ad }}" loading="lazy" decoding="async">
                    </div>
                </div>
            </div>
            @endif
            <div class="{{ $photo ? 'col-xl-6 col-lg-7' : 'col-xl-12' }}">
                <div class="about-four__right">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ $doktor['uzmanlik'] ?? 'Uzman hekim' }}</span>
                        <h2 class="section-title__title">{{ $ad }}</h2>
                    </div>
                    @if(!empty($doktor['kisa_bio']))
                        <p class="about-four__text">{{ strip_tags((string) $doktor['kisa_bio']) }}</p>
                    @endif
                    <div class="dg-prose">
                        {!! $bio !!}
                    </div>
                    @if($mezuniyet->isNotEmpty())
                        <ul class="list-unstyled about-four__points" style="margin-top:24px">
                            @foreach ($mezuniyet as $m)
                                <li>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <div class="text"><p>{{ is_string($m) ? $m : \Illuminate\Support\Str::limit(strip_tags((string)$m), 120) }}</p></div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="about-four__btn-box" style="margin-top:28px">
                        <a href="{{ route('frontend.randevu') }}" class="thm-btn">Randevu Al</a>
                        <a href="{{ route('frontend.iletisim') }}" class="thm-btn thm-btn--two" style="margin-left:10px">İletişim</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($ozellikler->isNotEmpty())
<section class="feature-three" style="padding-top:0">
    <div class="container">
        <div class="row">
            @foreach ($ozellikler as $i => $oz)
                <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="{{ ($i+1)*100 }}ms">
                    <div class="feature-three__single">
                        <div class="feature-three__icon">
                            <span class="icon-mental-health"></span>
                        </div>
                        <h3 class="feature-three__title">{{ $oz['baslik'] ?? 'Özellik' }}</h3>
                        <p class="feature-three__text">{{ $oz['aciklama'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="cta-one">
    <div class="container">
        <div class="cta-one__inner">
            <p class="cta-one__text">Size uygun saatte online randevu alın</p>
            <div class="cta-one__btn-box">
                <a href="{{ route('frontend.randevu') }}" class="cta-one__btn thm-btn">Randevu Al</a>
            </div>
        </div>
    </div>
</section>
@endsection
