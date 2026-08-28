@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $limit = max(1, (int) ($ayar['limit'] ?? 6));
    $galeri = collect($doktor['galeri'] ?? [])
        ->filter(fn ($g) => is_array($g) && (! empty($g['image']) || ! empty($g['resim']) || ! empty($g['url'])))
        ->take($limit)
        ->values();
    $kucuk = $ayar['kucuk_baslik'] ?? 'Galeri';
    $baslik = $ayar['ana_baslik'] ?? 'Klinik görselleri';
@endphp
@if($galeri->isNotEmpty())
<section class="cases-one" id="galeri">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
            <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
        </div>
        <div class="row gutter-y-30">
            @foreach ($galeri as $g)
                @php
                    $gImg = $media($g['image'] ?? $g['resim'] ?? $g['url'] ?? null);
                    $gTitle = decode_text($g['baslik'] ?? $g['etiket'] ?? 'Galeri');
                @endphp
                @if($gImg)
                <div class="col-6 col-md-4">
                    <a href="{{ route('frontend.galeri') }}" class="cases-one__single" style="display:block;overflow:hidden;border-radius:12px">
                        <div class="cases-one__img" style="aspect-ratio:4/3">
                            <img src="{{ $gImg }}" alt="{{ $gTitle }}" loading="lazy" style="width:100%;height:100%;object-fit:cover">
                        </div>
                    </a>
                </div>
                @endif
            @endforeach
        </div>
        <div class="text-center" style="margin-top:28px">
            <a href="{{ route('frontend.galeri') }}" class="thm-btn">Tüm galeri</a>
        </div>
    </div>
</section>
@endif
