@extends(theme_layout())

@section('baslik', 'Galeri | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', 'Klinik ve çalışma galerisi')

@section('icerik')
@php
    $galeri = collect($doktor['galeri'] ?? [])->filter(fn ($g) => is_array($g) || is_string($g))->values();
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'Galeri', 'crumb' => 'Galeri'])

<section class="gallery-page">
    <div class="container">
        @if($galeri->isEmpty())
            <div class="text-center" style="padding:48px 0">
                <p>Henüz galeri görseli eklenmemiş.</p>
            </div>
        @else
            <div class="row">
                @foreach ($galeri as $idx => $g)
                    @php
                        $src = is_string($g) ? $g : ($g['url'] ?? $g['image'] ?? $g['resim'] ?? $g['path'] ?? '');
                        $alt = is_array($g) ? ($g['baslik'] ?? $g['alt'] ?? 'Galeri') : 'Galeri';
                        if ($src === '') continue;
                    @endphp
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="{{ ($idx % 3 + 1) * 100 }}ms">
                        <div class="gallery-page__single">
                            <div class="gallery-page__img">
                                <img src="{{ $src }}" alt="{{ $alt }}" style="width:100%;height:260px;object-fit:cover;border-radius:12px" loading="lazy" decoding="async">
                                <div class="gallery-page__icon">
                                    <a class="img-popup" href="{{ $src }}"><span class="icon-plus-1"></span></a>
                                </div>
                            </div>
                            @if($alt && $alt !== 'Galeri')
                                <h4 style="margin-top:12px;font-size:1rem">{{ $alt }}</h4>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
