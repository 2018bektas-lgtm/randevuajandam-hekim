@extends(theme_layout())

@section('baslik', 'Eğitimler | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))

@section('icerik')
@php $photo = $doktor['profil_resmi'] ?? null; @endphp

@include('frontend.themes.tema-3.partials.page-banner', [
    'kod' => 'egitimler',
    'baslik' => 'Eğitimler',
    'breadcrumb' => [['label' => 'Eğitimler', 'aktif' => true]],
])

<div class="our-blog">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-8">
                <div class="section-title">
                    <h3 class="wow fadeInUp">eğitim & seminerler</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Mesleki gelişim ve eğitim faaliyetleri</h2>
                </div>
            </div>
        </div>
        @if(!empty($doktor['egitimler']))
        <div class="row">
            @foreach ($doktor['egitimler'] as $i => $egitim)
            @php $eSlug = $egitim['slug'] ?? \Illuminate\Support\Str::slug($egitim['baslik'] ?? ''); @endphp
            <div class="col-lg-4 col-md-6">
                <div class="post-item wow fadeInUp" data-wow-delay="{{ ($i % 3) * 0.2 }}s">
                    <div class="post-featured-image">
                        <figure>
                            <a href="{{ route('frontend.egitim.detay', $eSlug) }}" class="image-anime" data-cursor-text="Görüntüle">
                                <img src="{{ $egitim['image'] ?? asset('vendor/hipno/images/service-image-1.jpg') }}" alt="{{ $egitim['baslik'] }}" loading="{{ $i < 6 ? 'eager' : 'lazy' }}">
                            </a>
                        </figure>
                    </div>
                    <div class="post-item-body">
                        @if(!empty($egitim['tarih']) || !empty($egitim['sure']))
                        <div style="display:flex;gap:.75rem;margin-bottom:.5rem;font-size:.82rem;color:var(--accent-color)">
                            @if(!empty($egitim['tarih']))<span><i class="fa-regular fa-calendar"></i> {{ $egitim['tarih'] }}</span>@endif
                            @if(!empty($egitim['sure']))<span><i class="fa-regular fa-clock"></i> {{ $egitim['sure'] }}</span>@endif
                        </div>
                        @endif
                        <div class="post-item-content">
                            <h3><a href="{{ route('frontend.egitim.detay', $eSlug) }}">{{ $egitim['baslik'] }}</a></h3>
                            @if(!empty($egitim['kisa_aciklama']))
                            <p style="color:var(--text-color);font-size:.9rem;line-height:1.6;margin-top:.5rem">{{ Str::limit($egitim['kisa_aciklama'], 110) }}</p>
                            @endif
                        </div>
                        <div class="post-item-btn">
                            <a href="{{ route('frontend.egitim.detay', $eSlug) }}" class="readmore-btn">detaylar</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col-lg-12 text-center wow fadeInUp" style="padding:3rem 0">
                <p style="color:var(--text-color)">Henüz eğitim eklenmemiş.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
