@extends(theme_layout())

@section('baslik', 'Randevu Al | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', 'Hizmet seçin, müsait gün ve saati belirleyin. Kayıt zorunlu değildir.')

@section('icerik')
@include('frontend.themes.delogis.partials.page-header', [
    'title' => 'Randevu Al',
    'crumb' => 'Randevu',
])
<section class="contact-page" style="padding:60px 0 80px">
    <div class="container">
        @include('frontend.partials.randevu-wizard', ['raEmbed' => true, 'ayar' => []])
    </div>
</section>
@endsection
