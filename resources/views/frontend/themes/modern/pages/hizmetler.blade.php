@extends(theme_layout())

@section('baslik', 'Hizmetler | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', ($doktor['uzmanlik'] ?? 'Hekimlik').' alanında sunduğum hizmetler.')

@section('icerik')
<section class="mp-page-hero">
    <div class="mp-container">
        <div class="mp-breadcrumb">
            <a href="{{ route('frontend.anasayfa') }}">Ana Sayfa</a>
            <span>/</span>
            <span>Hizmetler</span>
        </div>
        <h1>Hizmet & tedavi alanları</h1>
        <p>{{ $doktor['uzmanlik'] ?? 'Uzmanlık alanım' }} kapsamında randevu alabileceğiniz aktif hizmetler.</p>
    </div>
</section>

<section class="mp-section mp-page">
    <div class="mp-container">
        <div class="mp-svc-grid">
            @forelse (($doktor['hizmetler'] ?? []) as $hizmet)
                @php $hSlug = $hizmet['slug'] ?? \Illuminate\Support\Str::slug($hizmet['baslik'] ?? $hizmet['ad'] ?? ''); @endphp
                <a href="{{ route('frontend.hizmet.detay', $hSlug) }}" class="mp-svc-card" id="{{ $hSlug }}">
                    @if(!empty($hizmet['image']))
                        <img src="{{ $hizmet['image'] }}" alt="" style="width:100%;height:140px;object-fit:cover;border-radius:6px;margin-bottom:14px" loading="lazy">
                    @else
                        <div class="mp-svc-icon">✚</div>
                    @endif
                    <h3>{{ $hizmet['baslik'] ?? $hizmet['ad'] ?? 'Hizmet' }}</h3>
                    <p>{{ $hizmet['kisa'] ?: \Illuminate\Support\Str::limit(strip_tags((string)($hizmet['aciklama'] ?? '')), 140) }}</p>
                    <div class="mp-svc-meta">
                        @if(!empty($hizmet['sure']))
                            <span class="mp-chip">{{ $hizmet['sure'] }}</span>
                        @endif
                        @if(!empty($hizmet['fiyat']))
                            <span class="mp-chip">{{ $hizmet['fiyat'] }}</span>
                        @endif
                    </div>
                    <span class="mp-svc-link">Detay & randevu →</span>
                </a>
            @empty
                <p class="mp-book-empty">Henüz hizmet eklenmemiş.</p>
            @endforelse
        </div>
        <div style="text-align:center;margin-top:32px">
            <a href="{{ route('frontend.randevu') }}" class="mp-btn mp-btn-primary mp-btn-lg">Randevu Al</a>
        </div>
    </div>
</section>
@endsection
