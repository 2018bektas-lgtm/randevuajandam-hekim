@extends(theme_layout())

@section('baslik', 'Hizmetler | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', ($doktor['uzmanlik'] ?? 'Hekimlik').' alanında sunduğum hizmetler.')

@section('icerik')
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $hizmetler = collect($doktor['hizmetler'] ?? [])
        ->filter(fn ($h) => is_array($h) && (filled($h['baslik'] ?? null) || filled($h['ad'] ?? null) || filled($h['id'] ?? null)))
        ->values();
    $icons = ['icon-account', 'icon-in-love', 'icon-mental-health', 'icon-psychology', 'icon-brain', 'icon-help'];
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'Hizmetler', 'crumb' => 'Hizmetler'])

<section class="services-three">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">{{ $doktor['uzmanlik'] ?? 'Uzmanlık' }}</span>
            <h2 class="section-title__title">{{ filled($doktor['hizmetler_baslik'] ?? null) ? $doktor['hizmetler_baslik'] : 'Sunduğumuz hizmetler' }}</h2>
            @if(filled($doktor['hizmetler_alt'] ?? null))
                <p>{{ $doktor['hizmetler_alt'] }}</p>
            @endif
        </div>

        @if($hizmetler->isEmpty())
            <div class="text-center" style="padding:40px 0">
                <p>Henüz yayınlanmış hizmet bulunamadı.</p>
                <a href="{{ route('frontend.randevu') }}" class="thm-btn" style="margin-top:16px">Randevu Al</a>
            </div>
        @else
            <div class="row">
                @foreach ($hizmetler as $idx => $h)
                    @php
                        $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                        $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                        $hDesc = \Illuminate\Support\Str::limit(strip_tags((string)($h['kisa'] ?? $h['aciklama'] ?? '')), 140);
                        $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                        $img = $h['image'] ?? $h['resim'] ?? null;
                    @endphp
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ ($idx % 3 + 1) * 100 }}ms">
                        <div class="services-three__single">
                            @if($img)
                                <div class="services-three__img" style="margin-bottom:18px;border-radius:12px;overflow:hidden">
                                    <img src="{{ $img }}" alt="{{ $hAd }}" style="width:100%;height:180px;object-fit:cover">
                                </div>
                            @else
                                <div class="services-three__icon">
                                    <span class="{{ $icons[$idx % count($icons)] }}"></span>
                                </div>
                            @endif
                            <h3 class="services-three__title"><a href="{{ $href }}">{{ $hAd }}</a></h3>
                            <p class="services-three__text">{{ $hDesc !== '' ? $hDesc : 'Detay ve randevu için tıklayın.' }}</p>
                            <div class="services-three__btn-box">
                                <a href="{{ $href }}">İncele <span class="icon-right-arrow"></span></a>
                            </div>
                            @if(!empty($h['sure']) || !empty($h['fiyat']))
                                <p style="margin-top:12px;font-size:13px;color:var(--delogis-gray,#8A969E)">
                                    @if(!empty($h['sure'])) {{ $h['sure'] }} @endif
                                    @if(!empty($h['sure']) && !empty($h['fiyat'])) · @endif
                                    @if(!empty($h['fiyat'])) {{ $h['fiyat'] }} @endif
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center" style="margin-top:36px">
                <a href="{{ route('frontend.randevu') }}" class="thm-btn">Randevu Al</a>
            </div>
        @endif
    </div>
</section>
@endsection
