@extends(theme_layout())

@section('baslik', ($yazi['baslik'] ?? 'Blog').' | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', $yazi['ozet'] ?? '')

@section('icerik')
@php
    $photo = $doktor['profil_resmi'] ?? null;
    $sonYazilar = collect($doktor['bloglar'] ?? [])
        ->filter(fn($b) => ($b['slug'] ?? '') !== ($yazi['slug'] ?? ''))
        ->take(3)
        ->values();
@endphp

@include('frontend.themes.tema-1.partials.page-banner', [
    'kod' => 'blog-detay',
    'baslik' => $yazi['baslik'] ?? 'Blog',
    'bgOverride' => $yazi['image'] ?? null,
    'breadcrumb' => [
        ['label' => 'Blog', 'href' => route('frontend.blog')],
        ['label' => Str::limit($yazi['baslik'] ?? '', 40), 'aktif' => true],
    ],
])

<div class="our-services">
    <div class="container">
        <div class="row">
            {{-- Main Article --}}
            <div class="col-lg-8">
                @if(!empty($yazi['image']))
                <figure class="image-anime" style="margin-bottom:2rem;border-radius:.5rem;overflow:hidden">
                    <img src="{{ $yazi['image'] }}" alt="{{ $yazi['baslik'] }}" style="width:100%;max-height:450px;object-fit:cover" loading="lazy" decoding="async">
                </figure>
                @endif

                @if(!empty($yazi['tarih']) || !empty($yazi['kategori']))
                <div class="wow fadeInUp" style="display:flex;gap:1rem;margin-bottom:1.5rem;font-size:.85rem;color:var(--accent-color)">
                    @if(!empty($yazi['tarih']))<span><i class="fa-regular fa-calendar"></i> {{ $yazi['tarih'] }}</span>@endif
                    @if(!empty($yazi['okuma']))<span><i class="fa-regular fa-clock"></i> {{ $yazi['okuma'] }}</span>@endif
                    @if(!empty($yazi['kategori']))<span><i class="fa-solid fa-tag"></i> {{ $yazi['kategori'] }}</span>@endif
                </div>
                @endif

                <div class="section-title" style="text-align:left;margin-bottom:2rem">
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $yazi['baslik'] }}</h2>
                </div>

                <div class="wow fadeInUp" data-wow-delay="0.2s"
                     style="color:var(--text-color);line-height:1.85;font-size:1.05rem">
                    @if(!empty($yazi['icerik']))
                        {!! $yazi['icerik'] !!}
                    @elseif(!empty($yazi['ozet']))
                        <p>{{ $yazi['ozet'] }}</p>
                    @endif
                </div>

                <div class="wow fadeInUp" data-wow-delay="0.3s" style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--divider-color)">
                    <a href="{{ route('frontend.blog') }}" class="readmore-btn">
                        <i class="fa-solid fa-arrow-left" style="margin-right:.4rem"></i> Tüm Yazılar
                    </a>
                    <a href="{{ route('frontend.randevu') }}" class="btn-default" style="margin-left:1rem">Randevu Al</a>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Doctor Card --}}
                <div class="wow fadeInUp" style="background:var(--secondary-color);padding:2rem;border-radius:.5rem;text-align:center;margin-bottom:2rem">
                    @if($photo)
                    <img src="{{ $photo }}" alt="{{ trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? '')) }}"
                         style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 1rem" loading="lazy" decoding="async">
                    @endif
                    <h4 style="font-family:var(--accent-font);color:var(--primary-color);margin-bottom:.25rem">
                        {{ trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')) }}
                    </h4>
                    <p style="color:var(--accent-color);font-size:.85rem;margin-bottom:1rem">{{ $doktor['uzmanlik'] ?? '' }}</p>
                    <a href="{{ route('frontend.randevu') }}" class="btn-default" style="display:block">Randevu Al</a>
                </div>

                {{-- Recent Posts --}}
                @if($sonYazilar->isNotEmpty())
                <div class="wow fadeInUp" data-wow-delay="0.2s" style="background:var(--secondary-color);padding:2rem;border-radius:.5rem">
                    <h3 style="font-family:var(--accent-font);color:var(--primary-color);margin-bottom:1.25rem;font-size:1.1rem">Son Yazılar</h3>
                    @foreach ($sonYazilar as $sy)
                    <div style="display:flex;gap:1rem;margin-bottom:1.25rem;padding-bottom:1.25rem;border-bottom:1px solid var(--divider-color)">
                        @if(!empty($sy['image']))
                        <img src="{{ $sy['image'] }}" alt="{{ $sy['baslik'] }}"
                             style="width:70px;height:60px;object-fit:cover;border-radius:.25rem;flex-shrink:0" loading="lazy" decoding="async">
                        @endif
                        <div>
                            <h4 style="font-size:.88rem;color:var(--primary-color);margin-bottom:.25rem;line-height:1.4">
                                <a href="{{ route('frontend.blog.detay', $sy['slug']) }}" style="color:inherit;text-decoration:none">{{ $sy['baslik'] }}</a>
                            </h4>
                            @if(!empty($sy['tarih']))
                            <span style="font-size:.78rem;color:var(--accent-color)">{{ $sy['tarih'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
