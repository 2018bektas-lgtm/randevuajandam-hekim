@extends(theme_layout())

@section('baslik', 'Galeri | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))

@section('icerik')
@php $photo = $doktor['profil_resmi'] ?? null; @endphp

<div class="page-header parallaxie"@if($photo) style="background-image:url('{{ $photo }}')"@endif>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="page-header-box">
                    <h1 class="text-anime-style-2" data-cursor="-opaque">Galeri</h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('frontend.anasayfa') }}">Anasayfa</a></li>
                            <li class="breadcrumb-item active">Galeri</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="case-study">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <h3 class="wow fadeInUp">galeri</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Klinik ve çalışmalardan</h2>
                </div>
            </div>
        </div>
        @if(!empty($doktor['galeri']))
        <div class="row">
            @foreach ($doktor['galeri'] as $i => $g)
            <div class="col-lg-4 col-md-6">
                <div class="case-study-item wow fadeInUp" data-wow-delay="{{ ($i % 3) * 0.2 }}s">
                    <div class="case-study-image">
                        <a href="{{ $g['image'] }}" class="popup-image" data-cursor-text="Büyüt">
                            <figure>
                                <img src="{{ $g['image'] }}" alt="{{ $g['baslik'] ?? '' }}" loading="lazy">
                            </figure>
                        </a>
                    </div>
                    @if(!empty($g['baslik']))
                    <div class="case-study-content">
                        <h3>{{ $g['baslik'] }}</h3>
                        @if(!empty($g['etiket']))
                        <p style="color:var(--accent-color);font-size:.85rem;margin-top:.25rem">{{ $g['etiket'] }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col-lg-12 text-center wow fadeInUp" style="padding:3rem 0">
                <p style="color:var(--text-color)">Henüz galeri eklenmemiş.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    if ($.fn.magnificPopup) {
        $('.popup-image').magnificPopup({
            type: 'image',
            gallery: { enabled: true },
            zoom: { enabled: true, duration: 300 }
        });
    }
});
</script>
@endpush
