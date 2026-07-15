@extends(theme_layout())

@section('baslik', 'Eğitimler | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', 'Kurs, webinar ve eğitim programları.')

@section('icerik')
<div class="th-modern-page">
<section class="page-hero th-modern-page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('frontend.anasayfa') }}">Ana Sayfa</a>
            <span>/</span>
            <span>Eğitimler</span>
        </div>
        <h1>Eğitimler</h1>
        <p>Kurs, webinar ve workshop programları. Başvuru formundan kayıt olabilirsiniz; ücret hekim üzerinden alınır.</p>
    </div>
</section>

<section class="section th-modern-section">
    <div class="container grid-2">
        @forelse (($doktor['egitimler'] ?? []) as $e)
            <article class="card service-card">
                <a href="{{ route('frontend.egitim.detay', $e['slug']) }}" style="display:block;color:inherit;text-decoration:none">
                    <img src="{{ $e['image'] }}" alt="{{ $e['baslik'] }}" loading="lazy">
                </a>
                <div class="card-pad">
                    <span class="chip">{{ $e['tip'] ?? 'eğitim' }}</span>
                    <h3 style="margin-top:.5rem">
                        <a href="{{ route('frontend.egitim.detay', $e['slug']) }}" style="color:inherit;text-decoration:none">{{ $e['baslik'] }}</a>
                    </h3>
                    <p class="text-muted" style="margin:0">{{ \Illuminate\Support\Str::limit(strip_tags((string)($e['ozet'] ?? '')), 140) }}</p>
                    <div class="service-meta">
                        @if(!empty($e['baslangic_label']))
                            <span class="chip">{{ $e['baslangic_label'] }}</span>
                        @endif
                        @if(!empty($e['fiyat_label']))
                            <span class="chip chip-gold">{{ $e['fiyat_label'] }}</span>
                        @else
                            <span class="chip">Bilgi / ücretsiz</span>
                        @endif
                    </div>
                    <div style="margin-top:1rem">
                        <a href="{{ route('frontend.egitim.detay', $e['slug']) }}" class="btn btn-primary btn-sm">Detay & Başvuru</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="card card-pad" style="grid-column:1/-1;text-align:center">
                <p class="text-muted">Henüz yayınlanmış eğitim bulunmuyor.</p>
            </div>
        @endforelse
    </div>
</section>
</div>
@endsection
