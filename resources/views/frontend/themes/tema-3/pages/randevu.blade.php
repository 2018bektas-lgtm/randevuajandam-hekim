@extends(theme_layout())

@section('baslik', 'Randevu Al | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', 'Online randevu: hizmet, gün ve saat seçin, birkaç saniyede randevunuzu oluşturun.')

@section('icerik')
@include(theme_view_name('partials.page-banner'), [
    'kod' => 'randevu',
    'baslik' => 'Online Randevu',
    'breadcrumb' => [['label' => 'Randevu', 'aktif' => true]],
])
@include('frontend.partials.randevu-wizard')
@endsection
