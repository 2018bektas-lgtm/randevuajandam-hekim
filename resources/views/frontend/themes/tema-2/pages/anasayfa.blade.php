{{--
    Anasayfa — tema-2 (Hipno Slider, modüler render)
--}}
@extends(theme_layout())

@section('baslik', trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim').' | '.($doktor['uzmanlik'] ?? 'Klinik').(!empty($doktor['il']) ? ' · '.$doktor['il'] : '')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? '')

@section('icerik')
    @php
        $modulListesi = app(\App\Services\SiteBuilderService::class)->renderIcinModuller('tema-2');
    @endphp

    @foreach($modulListesi as $modul)
        @include($modul['blade'], ['ayar' => $modul['ayar'], 'doktor' => $doktor])
    @endforeach
@endsection
