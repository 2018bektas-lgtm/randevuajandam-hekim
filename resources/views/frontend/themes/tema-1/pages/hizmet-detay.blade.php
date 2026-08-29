@extends(theme_layout())

@section('baslik', ($hizmet['baslik'] ?? 'Hizmet Detayı').' | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', $hizmet['kisa'] ?? '')

@section('icerik')
@php
    $photo = $doktor['profil_resmi'] ?? null;
    $digerHizmetler = collect($doktor['hizmetler'] ?? [])
        ->filter(fn($h) => ($h['slug'] ?? '') !== ($hizmet['slug'] ?? ''))
        ->take(5)
        ->values();
@endphp

@include('frontend.themes.tema-1.partials.page-banner', [
    'kod' => 'hizmet-detay',
    'baslik' => $hizmet['baslik'] ?? 'Hizmet Detayı',
    'bgOverride' => $hizmet['image'] ?? null,
    'breadcrumb' => [
        ['label' => 'Hizmetler', 'href' => route('frontend.hizmetler')],
        ['label' => $hizmet['baslik'] ?? '', 'aktif' => true],
    ],
])

<div class="our-services">
    <div class="container">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-lg-4">
                @if($digerHizmetler->isNotEmpty())
                <div class="wow fadeInUp" style="background:var(--secondary-color);padding:2rem;margin-bottom:2rem;border-radius:.5rem">
                    <h3 style="font-family:var(--accent-font);color:var(--primary-color);margin-bottom:1.25rem;font-size:1.25rem">Diğer Hizmetler</h3>
                    <ul style="list-style:none;padding:0;margin:0">
                        @foreach ($digerHizmetler as $dh)
                        @php $dhSlug = $dh['slug'] ?? \Illuminate\Support\Str::slug($dh['baslik'] ?? ''); @endphp
                        <li style="border-bottom:1px solid var(--divider-color);padding:.75rem 0">
                            <a href="{{ route('frontend.hizmet.detay', $dhSlug) }}"
                               style="color:var(--primary-color);text-decoration:none;font-size:.95rem;display:flex;justify-content:space-between;align-items:center">
                                {{ $dh['baslik'] }}
                                <i class="fa-solid fa-angle-right" style="color:var(--accent-color)"></i>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="wow fadeInUp" data-wow-delay="0.2s" style="background:var(--primary-color);padding:2rem;border-radius:.5rem;text-align:center">
                    <h3 style="font-family:var(--accent-font);color:#fff;margin-bottom:1rem;font-size:1.25rem">Randevu Al</h3>
                    <p style="color:rgba(255,255,255,.7);font-size:.9rem;margin-bottom:1.5rem">{{ $hizmet['baslik'] ?? 'Bu hizmet' }} için hemen randevu oluşturun.</p>
                    <a href="{{ route('frontend.randevu') }}" class="btn-default" style="display:block;text-align:center">Randevu Oluştur</a>
                    @if(!empty($doktor['telefon']))
                    <p style="color:rgba(255,255,255,.5);margin-top:1rem;font-size:.85rem">veya arayın: <a href="tel:{{ $doktor['telefon_raw'] ?? '' }}" style="color:var(--accent-color)">{{ $doktor['telefon'] }}</a></p>
                    @endif
                </div>
            </div>

            {{-- Main content --}}
            <div class="col-lg-8">
                @if(!empty($hizmet['image']))
                <figure class="image-anime" style="margin-bottom:2rem;border-radius:.5rem;overflow:hidden">
                    <img src="{{ $hizmet['image'] }}" alt="{{ $hizmet['baslik'] }}" style="width:100%;height:400px;object-fit:cover" loading="lazy" decoding="async">
                </figure>
                @endif

                <div class="section-title" style="text-align:left;margin-bottom:2rem">
                    <h3 class="wow fadeInUp">hizmet detayı</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $hizmet['baslik'] ?? '' }}</h2>
                </div>

                @if(!empty($hizmet['sure']))
                <div class="wow fadeInUp" style="display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap">
                    <div style="background:var(--secondary-color);padding:.75rem 1.25rem;border-radius:.5rem">
                        <span style="font-size:.8rem;color:var(--text-color);display:block;margin-bottom:.2rem">Süre</span>
                        <strong style="color:var(--primary-color)">{{ $hizmet['sure'] }}</strong>
                    </div>
                </div>
                @endif

                @if(!empty($hizmet['aciklama']))
                <div class="wow fadeInUp" data-wow-delay="0.2s" style="color:var(--text-color);line-height:1.8;margin-bottom:2rem;font-size:1rem">
                    {!! nl2br(e($hizmet['aciklama'])) !!}
                </div>
                @elseif(!empty($hizmet['kisa']))
                <div class="wow fadeInUp" data-wow-delay="0.2s" style="color:var(--text-color);line-height:1.8;margin-bottom:2rem">
                    <p>{{ $hizmet['kisa'] }}</p>
                </div>
                @endif

                @if(!empty($hizmet['madde']))
                <div class="wow fadeInUp" data-wow-delay="0.3s" style="margin-bottom:2rem">
                    <h3 style="font-family:var(--accent-font);color:var(--primary-color);margin-bottom:1rem;font-size:1.2rem">Neler İçeriyor?</h3>
                    <ul class="why-choose-list" style="display:block">
                        @foreach ($hizmet['madde'] as $madde)
                        <li class="why-choose-item" style="display:flex;gap:.75rem;align-items:flex-start;margin-bottom:.75rem;padding:0;border:none;background:none;box-shadow:none">
                            <i class="fa-solid fa-check-circle" style="color:var(--accent-color);margin-top:3px;flex-shrink:0"></i>
                            <span style="color:var(--text-color)">{{ $madde }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="wow fadeInUp" data-wow-delay="0.4s">
                    <a href="{{ route('frontend.randevu') }}" class="btn-default">Bu Hizmet İçin Randevu Al</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
