@extends(theme_layout())

@section('baslik', 'Hizmetler | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', 'Sunduğumuz tüm hizmetleri inceleyin.')

@section('icerik')
@php
    $photo    = $doktor['profil_resmi'] ?? null;
    $hizmetler = $doktor['hizmetler'] ?? [];
@endphp

@include('frontend.themes.tema-1.partials.page-banner', [
    'kod' => 'hizmetler',
    'baslik' => 'Hizmetlerim',
    'breadcrumb' => [['label' => 'Hizmetler', 'aktif' => true]],
])

<div class="our-services">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-8">
                <div class="section-title">
                    <h3 class="wow fadeInUp">hizmetler</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                        {{ $doktor['hizmetler_baslik'] ?? 'Sunduğumuz sağlık hizmetleri' }}
                    </h2>
                    @if(!empty($doktor['hizmetler_alt']))
                    <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $doktor['hizmetler_alt'] }}</p>
                    @endif
                </div>
            </div>
        </div>
        @if(!empty($hizmetler))
        <div class="row">
            @foreach ($hizmetler as $i => $hizmet)
            @php $hSlug = $hizmet['slug'] ?? \Illuminate\Support\Str::slug($hizmet['baslik'] ?? ''); @endphp
            <div class="col-lg-4 col-md-6">
                <div class="service-item wow fadeInUp" data-wow-delay="{{ ($i % 3) * 0.2 }}s">
                    <div class="service-image">
                        <a href="{{ route('frontend.hizmet.detay', $hSlug) }}" data-cursor-text="Görüntüle">
                            <figure class="image-anime">
                                <img src="{{ $hizmet['image'] ?? asset('vendor/hipno/images/service-image-1.jpg') }}" alt="{{ $hizmet['baslik'] }}" loading="lazy">
                            </figure>
                        </a>
                    </div>
                    <div class="service-content">
                        <h3>{{ $hizmet['baslik'] }}</h3>
                        @if(!empty($hizmet['kisa']))
                        <p style="color:var(--text-color);font-size:.95rem;margin-top:.5rem">{{ $hizmet['kisa'] }}</p>
                        @endif
                        @if(!empty($hizmet['sure']))
                        <div style="display:flex;gap:.5rem;margin-top:.5rem;flex-wrap:wrap">
                            <span style="font-size:.8rem;padding:.2rem .6rem;background:var(--secondary-color);border-radius:3px">{{ $hizmet['sure'] }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="service-btn">
                        <a href="{{ route('frontend.hizmet.detay', $hSlug) }}" class="readmore-btn">detaylar</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col-lg-12 text-center wow fadeInUp" style="padding:3rem 0">
                <p style="color:var(--text-color)">Henüz hizmet eklenmemiş.</p>
                <a href="{{ route('frontend.randevu') }}" class="btn-default" style="margin-top:1rem">Randevu Al</a>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="cta-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cta-box">
                    <div class="cta-box-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">randevu</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Randevunuzu hemen oluşturun</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">İhtiyacınıza en uygun hizmeti seçin ve randevunuzu kolayca planlayın.</p>
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
