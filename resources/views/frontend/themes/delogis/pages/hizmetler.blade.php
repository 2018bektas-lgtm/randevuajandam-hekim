@extends(theme_layout())

@section('baslik', 'Hizmetler | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', ($doktor['uzmanlik'] ?? 'Hekimlik').' alanında sunduğum hizmetler.')

@section('icerik')
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $hizmetler = collect($doktor['hizmetler'] ?? [])
        ->filter(fn ($h) => is_array($h) && (filled($h['baslik'] ?? null) || filled($h['ad'] ?? null) || filled($h['id'] ?? null)))
        ->values();
    $icons = ['icon-heart', 'icon-self-confidence', 'icon-family', 'icon-account', 'icon-mental-health', 'icon-psychology'];
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'Hizmetler', 'crumb' => 'Hizmetler'])

{{-- services.html → services-page + services-two__single --}}
<section class="services-page">
    <div class="container">
        @if($hizmetler->isEmpty())
            <div class="text-center" style="padding:48px 0">
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
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp dg-card-col" data-wow-delay="{{ ($idx % 3 + 1) * 100 }}ms">
                        <div class="services-two__single dg-card">
                            <div class="services-two__img-box dg-card__media">
                                @if($img)
                                    <div class="services-two__img dg-card__img">
                                        <img src="{{ $img }}" alt="{{ $hAd }}">
                                    </div>
                                @else
                                    <div class="services-two__img dg-card__img dg-card__img--empty">
                                        <span class="{{ $icons[$idx % count($icons)] }}"></span>
                                    </div>
                                @endif
                                <div class="services-two__icon">
                                    <span class="{{ $icons[$idx % count($icons)] }}"></span>
                                </div>
                            </div>
                            <div class="services-two__content dg-card__body">
                                <div class="services-two__title-box">
                                    <h3 class="services-two__title"><a href="{{ $href }}">{{ $hAd }}</a></h3>
                                    <p class="services-two__text">{{ $hDesc !== '' ? $hDesc : 'Detay ve randevu için tıklayın.' }}</p>
                                </div>
                                <div class="services-two__btn-box">
                                    <a href="{{ $href }}"><span class="icon-right-arrow"></span> İncele</a>
                                </div>
                            </div>
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
