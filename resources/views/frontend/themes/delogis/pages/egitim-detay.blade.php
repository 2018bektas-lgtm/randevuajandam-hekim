@extends(theme_layout())

@php
    $e = $egitim ?? [];
    $ad = $e['baslik'] ?? 'Eğitim';
    $icerik = $e['aciklama'] ?? $e['icerik'] ?? $e['ozet'] ?? '';
@endphp

@section('baslik', $ad.' | Eğitim')
@section('meta_aciklama', \Illuminate\Support\Str::limit(strip_tags((string)$icerik), 160))

@section('icerik')
@include('frontend.themes.delogis.partials.page-header', ['title' => $ad, 'crumb' => 'Eğitimler'])

<section class="services-details">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                @if(!empty($e['image']))
                    <div class="services-details__img" style="margin-bottom:24px">
                        <img src="{{ $e['image'] }}" alt="{{ $ad }}" loading="lazy" decoding="async">
                    </div>
                @endif
                <h3 class="services-details__title-1">{{ $ad }}</h3>
                <div class="dg-prose">{!! $icerik !!}</div>
                @if(!empty($e['baslangic_label']))
                    <ul class="list-unstyled about-four__points" style="margin-top:20px">
                        <li><div class="icon"><i class="fa fa-calendar"></i></div><div class="text"><p>{{ $e['baslangic_label'] }}</p></div></li>
                    </ul>
                @endif
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="services-details__sidebar">
                    <div class="services-details__get-started">
                        <h3 class="services-details__get-started-title">İletişim</h3>
                        <p class="services-details__get-started-text">Başvuru ve bilgi için randevu / iletişim formunu kullanın.</p>
                        <a href="{{ route('frontend.iletisim') }}" class="thm-btn">İletişim</a>
                        <a href="{{ route('frontend.egitimler') }}" class="thm-btn thm-btn--two" style="margin-top:10px;display:inline-block">Tüm eğitimler</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
