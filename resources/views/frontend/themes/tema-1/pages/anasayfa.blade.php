{{--
    Anasayfa — tema-1 (modüler render)

    Modüller sırası + aktiflik + özel ayarlar DB'de (site_homepage_sections).
    Config kataloğu: config/tema_modulleri.php
    Render mantığı: App\Services\SiteBuilderService::renderIcinModuller()

    Hekim panelinden düzenleme: /panel/site/anasayfa-duzenle
--}}
@extends(theme_layout())

@section('baslik', trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim').' | '.($doktor['uzmanlik'] ?? 'Klinik').(!empty($doktor['il']) ? ' · '.$doktor['il'] : '')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? '')

@section('icerik')
    @php
        /** @var \Illuminate\Support\Collection $modulListesi */
        $temaId = current_theme_id(is_array($doktor ?? null) ? $doktor : null);
        $modulListesi = app(\App\Services\SiteBuilderService::class)->renderIcinModuller($temaId);
    @endphp

    @foreach($modulListesi as $modul)
        @include($modul['blade'], ['ayar' => $modul['ayar'], 'doktor' => $doktor])
    @endforeach
@endsection
