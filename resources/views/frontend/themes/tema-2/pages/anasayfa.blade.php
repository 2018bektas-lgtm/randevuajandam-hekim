{{--
    Anasayfa — tema-2 (Hipno Slider, modüler render)
--}}
@extends(theme_layout())

@section('baslik', trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim').' | '.($doktor['uzmanlik'] ?? 'Klinik').(!empty($doktor['il']) ? ' · '.$doktor['il'] : '')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? '')

@section('icerik')
    @php
        $temaId = current_theme_id(is_array($doktor ?? null) ? $doktor : null);
        $modulListesi = app(\App\Services\SiteBuilderService::class)->renderIcinModuller($temaId);
    @endphp

    @foreach($modulListesi as $modul)
        @include($modul['blade'], ['ayar' => $modul['ayar'], 'doktor' => $doktor])
    @endforeach
@endsection
