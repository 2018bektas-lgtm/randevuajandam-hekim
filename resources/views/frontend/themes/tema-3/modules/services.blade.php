{{--
    Hizmetler — tema-1
    Kaynak: $doktor['hizmetler'] (API'den gelen kart listesi)
--}}
@php
    $limit = max(1, (int) ($ayar['hizmet_limiti'] ?? 6));
    $hizmetler = collect($doktor['hizmetler'] ?? [])->take($limit);
@endphp

@if($hizmetler->isNotEmpty())
<div class="our-services">
    <div class="container">
        <div class="row section-row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Hizmetlerim' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['bolum_baslik'] ?? 'Sunduğum Hizmetler' }}</h2>
                    @if(!empty($ayar['aciklama']))
                        <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($hizmetler as $h)
                @php
                    $baslik = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                    $gorsel = $h['image'] ?? $h['gorsel'] ?? null;
                    $href = ! empty($h['slug']) ? route('frontend.hizmet.detay', $h['slug']) : '#';
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="service-item wow fadeInUp" data-wow-delay="{{ 0.2 + $loop->index * 0.15 }}s">
                        <div class="service-image">
                            <a href="{{ $href }}" data-cursor-text="İncele">
                                @if($gorsel)
                                    <figure class="image-anime">
                                        <img src="{{ $gorsel }}" alt="{{ $baslik }}">
                                    </figure>
                                @else
                                    <span class="service-image-fallback" aria-hidden="true"></span>
                                @endif
                            </a>
                        </div>
                        <div class="service-content">
                            <h3>{{ $baslik }}</h3>
                        </div>
                        @if(!empty($h['slug']))
                            <div class="service-btn">
                                <a href="{{ $href }}" class="readmore-btn">Detayları gör</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@push('head')
<style>
.service-content h2,
.service-content h3 { color: var(--white-color); }
.service-content h2 a,
.service-content h3 a { color: var(--white-color); text-decoration: none; }
.service-image-fallback {
    display: block;
    aspect-ratio: 1 / 0.97;
    border-radius: 20px;
    background: linear-gradient(160deg, var(--primary-color) 0%, var(--accent-color) 100%);
}
</style>
@endpush
