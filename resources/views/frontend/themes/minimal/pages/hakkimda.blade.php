@extends(theme_layout())

@section('baslik', 'Hakkımda | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? $doktor['bio'] ?? '')

@section('icerik')
<div class="th-min-page">
@php
    $photo = $doktor['profil_resmi']
        ?? 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=1000&q=80';
    $mezuniyet = $doktor['mezuniyet'] ?? [];
    $egitim = $doktor['egitim'] ?? [];
@endphp

<section class="page-hero th-min-page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('frontend.anasayfa') }}">Ana Sayfa</a>
            <span>/</span>
            <span>Hakkımda</span>
        </div>
        <h1>{{ $doktor['unvan'] ?? '' }} {{ $doktor['ad_soyad'] ?? '' }}</h1>
        <p>
            {{ $doktor['uzmanlik'] ?? '' }}
            @if(!empty($doktor['klinik_adi'])) · {{ $doktor['klinik_adi'] }} @endif
            @if(!empty($doktor['ilce']) || !empty($doktor['il']))
                · {{ trim(($doktor['ilce'] ?? '').'/'.($doktor['il'] ?? ''), '/') }}
            @endif
        </p>
    </div>
</section>

<section class="section th-min-section">
    <div class="container two-col">
        <div class="media-frame">
            <img src="{{ $photo }}" alt="{{ $doktor['ad_soyad'] ?? 'Hekim' }}">
        </div>
        <div class="prose">
            <span class="eyebrow">Özgeçmiş</span>
            <h2>{{ $doktor['slogan'] ?? 'Uzman hekimlik yaklaşımı' }}</h2>
            @if(!empty($doktor['branslar']))
                <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin:1rem 0 1.25rem">
                    @foreach ($doktor['branslar'] as $br)
                        <span class="chip">{{ $br }}</span>
                    @endforeach
                </div>
            @endif
            @if(!empty($doktor['bio_html']))
                {!! $doktor['bio_html'] !!}
            @else
                <p>{{ $doktor['bio'] ?? $doktor['kisa_bio'] ?? '' }}</p>
                @foreach (($doktor['bio_uzun'] ?? []) as $par)
                    @if($par !== ($doktor['bio'] ?? ''))
                        <p>{{ $par }}</p>
                    @endif
                @endforeach
            @endif
            <a href="{{ route('frontend.randevu') }}" class="btn btn-primary" style="margin-top:1rem">Randevu Al</a>
        </div>
    </div>
</section>

@if(count($mezuniyet) || count($egitim) || !empty($doktor['istatistikler']))
<section class="section bg-white">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Kariyer</span>
                <h2 class="section-title">Eğitim & öne çıkanlar</h2>
            </div>
        </div>
        <div class="two-col" style="align-items:start">
            <div class="timeline">
                @forelse ($mezuniyet as $i => $item)
                    <div class="timeline-item">
                        <div class="year">{{ str_pad((string)($i+1), 2, '0', STR_PAD_LEFT) }}</div>
                        <h3>Eğitim</h3>
                        <p>{{ $item }}</p>
                    </div>
                @empty
                    @foreach ($egitim as $e)
                        <div class="timeline-item">
                            <div class="year">{{ $e['yil'] ?? '' }}</div>
                            <h3>{{ $e['baslik'] ?? '' }}</h3>
                            <p>{{ $e['aciklama'] ?? '' }}</p>
                        </div>
                    @endforeach
                @endforelse
                @if(empty($mezuniyet) && empty($egitim))
                    <div class="timeline-item">
                        <div class="year">—</div>
                        <h3>{{ $doktor['uzmanlik'] ?? 'Uzmanlık' }}</h3>
                        <p>Detaylı eğitim bilgileri yakında eklenecektir.</p>
                    </div>
                @endif
            </div>
            <div class="grid-2">
                @foreach (($doktor['istatistikler'] ?? []) as $stat)
                    <div class="card card-pad" style="text-align:center">
                        <div class="stat-value" style="font-size:2.2rem">{{ $stat['deger'] }}{{ $stat['suffix'] ?? '' }}</div>
                        <div class="stat-label">{{ $stat['etiket'] }}</div>
                        <div class="stat-desc">{{ $stat['aciklama'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="section th-min-section">
    <div class="container">
        <div class="cta-band">
            <div>
                <h2 class="section-title" style="color:#fff;margin:0">Muayene için randevu oluşturun</h2>
                <p style="color:rgba(255,255,255,.78);margin:.75rem 0 0">{{ $doktor['adres'] ?? '' }}</p>
            </div>
            <a href="{{ route('frontend.randevu') }}" class="btn btn-gold">Online Randevu</a>
        </div>
    </div>
</section>
</div>
@endsection
