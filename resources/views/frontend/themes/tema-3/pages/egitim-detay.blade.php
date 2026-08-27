@extends(theme_layout())

@section('baslik', ($egitim['baslik'] ?? 'Eğitim Detayı').' | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', $egitim['kisa_aciklama'] ?? '')

@section('icerik')
@php
    $photo = $doktor['profil_resmi'] ?? null;
    $digerEgitimler = collect($doktor['egitimler'] ?? [])
        ->filter(fn($e) => ($e['slug'] ?? '') !== ($egitim['slug'] ?? ''))
        ->take(4)
        ->values();
@endphp

@include('frontend.themes.tema-3.partials.page-banner', [
    'kod' => 'egitim-detay',
    'baslik' => $egitim['baslik'] ?? 'Eğitim Detayı',
    'bgOverride' => $egitim['image'] ?? null,
    'breadcrumb' => [
        ['label' => 'Eğitimler', 'href' => route('frontend.egitimler')],
        ['label' => Str::limit($egitim['baslik'] ?? '', 40), 'aktif' => true],
    ],
])

<div class="our-services">
    <div class="container">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="wow fadeInUp" style="background:var(--secondary-color);padding:2rem;border-radius:.5rem;margin-bottom:2rem">
                    <h3 style="font-family:var(--accent-font);color:var(--primary-color);margin-bottom:1.5rem;font-size:1.1rem">Eğitim Bilgileri</h3>
                    @if(!empty($egitim['tarih']))
                    <div style="display:flex;gap:.75rem;margin-bottom:1rem;align-items:center">
                        <i class="fa-regular fa-calendar" style="color:var(--accent-color);width:18px"></i>
                        <div>
                            <span style="font-size:.78rem;color:var(--text-color)">Tarih</span>
                            <p style="margin:0;color:var(--primary-color);font-weight:600">{{ $egitim['tarih'] }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($egitim['sure']))
                    <div style="display:flex;gap:.75rem;margin-bottom:1rem;align-items:center">
                        <i class="fa-regular fa-clock" style="color:var(--accent-color);width:18px"></i>
                        <div>
                            <span style="font-size:.78rem;color:var(--text-color)">Süre</span>
                            <p style="margin:0;color:var(--primary-color);font-weight:600">{{ $egitim['sure'] }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($egitim['lokasyon']))
                    <div style="display:flex;gap:.75rem;margin-bottom:1rem;align-items:center">
                        <i class="fa-solid fa-location-dot" style="color:var(--accent-color);width:18px"></i>
                        <div>
                            <span style="font-size:.78rem;color:var(--text-color)">Lokasyon</span>
                            <p style="margin:0;color:var(--primary-color);font-weight:600">{{ $egitim['lokasyon'] }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                @if($digerEgitimler->isNotEmpty())
                <div class="wow fadeInUp" data-wow-delay="0.2s" style="background:var(--secondary-color);padding:2rem;border-radius:.5rem">
                    <h3 style="font-family:var(--accent-font);color:var(--primary-color);margin-bottom:1.25rem;font-size:1.1rem">Diğer Eğitimler</h3>
                    @foreach ($digerEgitimler as $de)
                    @php $deSlug = $de['slug'] ?? \Illuminate\Support\Str::slug($de['baslik'] ?? ''); @endphp
                    <div style="border-bottom:1px solid var(--divider-color);padding:.75rem 0">
                        <a href="{{ route('frontend.egitim.detay', $deSlug) }}"
                           style="color:var(--primary-color);text-decoration:none;font-size:.9rem;display:flex;justify-content:space-between;align-items:center">
                            {{ $de['baslik'] }}
                            <i class="fa-solid fa-angle-right" style="color:var(--accent-color)"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Main --}}
            <div class="col-lg-8">
                @if(!empty($egitim['image']))
                <figure class="image-anime" style="margin-bottom:2rem;border-radius:.5rem;overflow:hidden">
                    <img src="{{ $egitim['image'] }}" alt="{{ $egitim['baslik'] }}" style="width:100%;max-height:420px;object-fit:cover">
                </figure>
                @endif

                <div class="section-title" style="text-align:left;margin-bottom:2rem">
                    <h3 class="wow fadeInUp">eğitim detayı</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $egitim['baslik'] ?? '' }}</h2>
                </div>

                <div class="wow fadeInUp" data-wow-delay="0.2s"
                     style="color:var(--text-color);line-height:1.85;font-size:1.05rem;margin-bottom:2rem">
                    @if(!empty($egitim['aciklama']))
                        {!! nl2br(e($egitim['aciklama'])) !!}
                    @elseif(!empty($egitim['kisa_aciklama']))
                        <p>{{ $egitim['kisa_aciklama'] }}</p>
                    @endif
                </div>

                @if(!empty($egitim['konular']))
                <div class="wow fadeInUp" data-wow-delay="0.3s" style="margin-bottom:2rem">
                    <h3 style="font-family:var(--accent-font);color:var(--primary-color);font-size:1.2rem;margin-bottom:1rem">Eğitim Konuları</h3>
                    <ul style="list-style:none;padding:0">
                        @foreach ($egitim['konular'] as $konu)
                        <li style="display:flex;gap:.75rem;align-items:flex-start;margin-bottom:.75rem;padding:.75rem;background:var(--secondary-color);border-radius:.4rem">
                            <i class="fa-solid fa-check-circle" style="color:var(--accent-color);margin-top:2px;flex-shrink:0"></i>
                            <span style="color:var(--primary-color)">{{ $konu }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="wow fadeInUp" data-wow-delay="0.4s" style="display:flex;gap:1rem;flex-wrap:wrap">
                    <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                    <a href="{{ route('frontend.egitimler') }}" class="btn-default btn-highlighted">Tüm Eğitimler</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
