@extends(theme_layout())

@section('baslik', 'Hakkımda | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? '')

@section('icerik')
@php
    $photo    = $doktor['profil_resmi'] ?? null;
    $unvan    = trim((string) ($doktor['unvan'] ?? ''));
    $adSoyad  = trim((string) ($doktor['ad_soyad'] ?? 'Hekim'));
    $doktorAd = trim($unvan.' '.$adSoyad);
    $uzmanlik = trim((string) ($doktor['uzmanlik'] ?? ''));
    $kisaBio  = trim((string) ($doktor['kisa_bio'] ?? ''));
    $branslar = array_values(array_filter((array) ($doktor['branslar'] ?? [])));
    $mezuniyet = array_values(array_filter((array) ($doktor['mezuniyet'] ?? [])));

    // Biyografi: tek blok metin yerine düzgün paragraflar
    $bioParagraflar = array_values(array_filter((array) ($doktor['bio_uzun'] ?? [])));
    if ($bioParagraflar === [] && filled($doktor['bio'] ?? null)) {
        $bioParagraflar = array_values(array_filter(array_map(
            'trim',
            preg_split('/\n\s*\n/', (string) $doktor['bio']) ?: []
        )));
    }
    // Tanıtımda kısa bio gösterildiyse özgeçmişte aynı metni tekrar etme
    if ($kisaBio !== '' && count($bioParagraflar) === 1 && trim($bioParagraflar[0]) === $kisaBio) {
        $bioParagraflar = [];
    }

    $yaklasim = array_values(array_filter(
        (array) ($doktor['ozellikler'] ?? []),
        fn ($o) => is_array($o) && filled($o['baslik'] ?? null)
    ));
    $calismaOzet = trim((string) ($doktor['calisma_saatleri_ozet'] ?? ''));
    $konum = trim(implode(', ', array_filter([$doktor['ilce'] ?? null, $doktor['il'] ?? null])));
    $klinik = trim((string) ($doktor['klinik_adi'] ?? ''));
@endphp

@include('frontend.themes.tema-2.partials.page-banner', [
    'kod' => 'hakkimda',
    'baslik' => 'Hakkımda',
    'breadcrumb' => [['label' => 'Hakkımda', 'aktif' => true]],
])

<div class="hakkimda-page">

{{-- Tanıtım --}}
<div class="about-us">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-us-images is-single">
                    <div class="about-img-1">
                        <figure class="image-anime">
                            <img src="{{ $photo ?? asset('vendor/hipno/images/about-img-1.jpg') }}" alt="{{ $doktorAd }}" loading="lazy" decoding="async">
                        </figure>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-us-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $doktorAd }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">
                            {{ $uzmanlik !== '' ? $uzmanlik : 'Uzman Hekim' }}
                            @if($konum !== '') — {{ $konum }}@endif
                        </h2>
                        @if($kisaBio !== '')
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $kisaBio }}</p>
                        @endif
                    </div>

                    @if($branslar !== [])
                    <div class="hk-etiketler wow fadeInUp" data-wow-delay="0.3s">
                        <span class="hk-etiket-baslik">Uzmanlık alanları</span>
                        <div class="hk-etiket-liste">
                            @foreach($branslar as $br)
                                <span class="hk-etiket">{{ $br }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($klinik !== '' || $calismaOzet !== '')
                    <ul class="hk-hizli-bilgi wow fadeInUp" data-wow-delay="0.4s">
                        @if($klinik !== '')
                            <li><span>Muayenehane</span><strong>{{ $klinik }}</strong></li>
                        @endif
                        @if($calismaOzet !== '')
                            <li><span>Çalışma saatleri</span><strong>{{ $calismaOzet }}</strong></li>
                        @endif
                    </ul>
                    @endif

                    <div class="about-us-content-btn wow fadeInUp" data-wow-delay="0.6s">
                        <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                        <a href="{{ route('frontend.iletisim') }}" class="btn-default btn-highlighted">İletişim</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Özgeçmiş + Eğitim --}}
@if($bioParagraflar !== [] || $mezuniyet !== [])
<div class="hk-bolum hk-bolum-ozgecmis">
    <div class="container">
        <div class="row gy-5">
            @if($bioParagraflar !== [])
            <div class="{{ $mezuniyet !== [] ? 'col-lg-7' : 'col-lg-9' }}">
                <div class="section-title hk-sol-baslik">
                    <h3 class="wow fadeInUp">Özgeçmiş</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Kısaca ben</h2>
                </div>
                <div class="hk-metin wow fadeInUp" data-wow-delay="0.2s">
                    @foreach($bioParagraflar as $p)
                        <p>{{ $p }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @if($mezuniyet !== [])
            <div class="{{ $bioParagraflar !== [] ? 'col-lg-5' : 'col-lg-8' }}">
                <div class="section-title hk-sol-baslik">
                    <h3 class="wow fadeInUp">Eğitim</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Eğitim ve deneyim</h2>
                </div>
                <ul class="hk-egitim wow fadeInUp" data-wow-delay="0.2s">
                    @foreach($mezuniyet as $m)
                        <li>{{ is_array($m) ? trim(implode(' · ', array_filter($m))) : $m }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

{{-- Çalışma yaklaşımı — başlık + açıklama birlikte --}}
@if($yaklasim !== [])
<div class="hk-bolum hk-bolum-yaklasim">
    <div class="container">
        <div class="section-title text-center">
            <h3 class="wow fadeInUp">Yaklaşımım</h3>
            <h2 class="text-anime-style-2" data-cursor="-opaque">Görüşmelerimde neye önem veriyorum</h2>
        </div>
        <div class="hk-yaklasim-liste">
            @foreach($yaklasim as $i => $oz)
                <article class="hk-yaklasim-kart wow fadeInUp" data-wow-delay="{{ $i * 0.15 }}s">
                    <span class="hk-yaklasim-no">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $oz['baslik'] }}</h3>
                    @if(filled($oz['aciklama'] ?? null))
                        <p>{{ $oz['aciklama'] }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Sayılarla --}}
@if(!empty($doktor['istatistikler']))
<div class="what-we-do">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="intro-video-box" style="border-radius:.75rem;overflow:hidden">
                    <div class="intro-video-counter">
                        @foreach ($doktor['istatistikler'] as $ist)
                        <div class="video-counter-item">
                            <h2><span class="counter">{{ preg_replace('/\D/', '', $ist['deger'] ?? '0') }}</span>{{ $ist['suffix'] ?? '' }}</h2>
                            <p>{{ $ist['etiket'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- CTA --}}
<div class="cta-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cta-box">
                    <div class="cta-box-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">randevu</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Online randevu alın</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Hemen randevu oluşturarak uzman değerlendirmesinden yararlanın.</p>
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

</div>
@endsection

@push('head')
<style>
.about-us-images.is-single{display:block!important;position:relative}
.about-us-images.is-single .about-img-1{width:100%!important;max-width:480px}
.about-us-images.is-single .about-img-1 img{aspect-ratio:3/4;width:100%;object-fit:cover;border-radius:20px}
.about-us-images.is-single .about-img-2,
.about-us-images.is-single .about-customer-box{display:none!important}

/* ---- Hakkımda ---- */
.hakkimda-page .hk-bolum{padding:0 0 90px}
.hakkimda-page .hk-bolum-yaklasim{padding-top:10px}
.hakkimda-page .hk-sol-baslik{text-align:left;margin-bottom:24px}

.hakkimda-page .hk-etiketler{margin:0 0 26px}
.hakkimda-page .hk-etiket-baslik{
    display:block;font-family:var(--font);font-size:.72rem;font-weight:700;
    letter-spacing:.12em;text-transform:uppercase;color:var(--accent-color);margin-bottom:10px;
}
.hakkimda-page .hk-etiket-liste{display:flex;flex-wrap:wrap;gap:8px}
.hakkimda-page .hk-etiket{
    display:inline-flex;align-items:center;padding:.4rem .9rem;border-radius:999px;
    border:1px solid rgba(0,0,0,.09);background:var(--secondary-color,#F9F9F9);
    font-family:var(--font);font-size:.82rem;font-weight:600;color:var(--primary-color);
}

.hakkimda-page .hk-hizli-bilgi{list-style:none;margin:0 0 28px;padding:0;display:grid;gap:12px}
.hakkimda-page .hk-hizli-bilgi li{
    display:flex;flex-direction:column;gap:2px;padding-left:14px;
    border-left:2px solid var(--accent-color);
}
.hakkimda-page .hk-hizli-bilgi span{
    font-family:var(--font);font-size:.7rem;font-weight:700;letter-spacing:.1em;
    text-transform:uppercase;color:#9ca3af;
}
.hakkimda-page .hk-hizli-bilgi strong{
    font-family:var(--display);font-size:1.02rem;font-weight:400;color:var(--primary-color);
}

.hakkimda-page .hk-metin p{
    color:var(--text-color);line-height:1.85;font-size:1.02rem;margin:0 0 1.1rem;
}
.hakkimda-page .hk-metin p:last-child{margin-bottom:0}

.hakkimda-page .hk-egitim{list-style:none;margin:0;padding:0;display:grid;gap:14px}
.hakkimda-page .hk-egitim li{
    position:relative;padding:16px 18px 16px 44px;border-radius:14px;
    background:var(--secondary-color,#F9F9F9);border:1px solid rgba(0,0,0,.05);
    font-family:var(--font);font-size:.95rem;line-height:1.55;color:var(--primary-color);
}
.hakkimda-page .hk-egitim li::before{
    content:'';position:absolute;left:20px;top:23px;width:8px;height:8px;
    border-radius:50%;background:var(--accent-color);
}

.hakkimda-page .hk-yaklasim-liste{
    display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;
}
.hakkimda-page .hk-yaklasim-kart{
    padding:32px 28px;border-radius:18px;background:#fff;
    border:1px solid rgba(0,0,0,.07);transition:transform .3s ease,box-shadow .3s ease;
}
.hakkimda-page .hk-yaklasim-kart:hover{transform:translateY(-4px);box-shadow:0 22px 50px rgba(0,0,0,.07)}
.hakkimda-page .hk-yaklasim-no{
    display:block;font-family:var(--display);font-size:1.6rem;line-height:1;
    color:var(--accent-color);margin-bottom:14px;
}
.hakkimda-page .hk-yaklasim-kart h3{
    font-family:var(--display);font-size:1.22rem;font-weight:400;
    color:var(--primary-color);margin:0 0 10px;line-height:1.3;
}
.hakkimda-page .hk-yaklasim-kart p{
    margin:0;color:var(--text-color);font-size:.95rem;line-height:1.7;
}

@media (max-width:991px){
    .hakkimda-page .hk-bolum{padding-bottom:60px}
    .hakkimda-page .about-us-images.is-single .about-img-1{margin:0 auto 32px}
}
</style>
@endpush
