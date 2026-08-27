{{-- Hizmetler — Hipno our-services --}}
@php
    $limit = max(1, (int) ($ayar['hizmet_limiti'] ?? 6));
    $hizmetler = collect($doktor['hizmetler'] ?? [])->take($limit);
@endphp

@if($hizmetler->isNotEmpty())
<div class="our-services">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-6 col-md-9">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Hizmetler' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['bolum_baslik'] ?? 'Sunduğum Hizmetler' }}</h2>
                </div>
            </div>
            <div class="col-lg-6 col-md-3">
                <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                    <a href="{{ route('frontend.hizmetler') }}" class="btn-default">{{ $ayar['buton_metin'] ?? 'Tüm hizmetler' }}</a>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($hizmetler as $h)
                @php
                    $baslik = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                    $gorsel = $h['image'] ?? $h['gorsel'] ?? null;
                    $href = ! empty($h['slug']) ? route('frontend.hizmet.detay', $h['slug']) : route('frontend.hizmetler');
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="service-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                        <div class="service-image">
                            <a href="{{ $href }}" data-cursor-text="İncele">
                                <figure class="image-anime">
                                    <img src="{{ $gorsel ?: asset('vendor/hipno/images/service-image-'.(($loop->index % 6) + 1).'.jpg') }}" alt="{{ $baslik }}">
                                </figure>
                            </a>
                        </div>
                        <div class="service-content">
                            <h3>{{ $baslik }}</h3>
                        </div>
                        <div class="service-btn">
                            <a href="{{ $href }}" class="readmore-btn">Detaylar</a>
                        </div>
                    </div>
                </div>
            @endforeach
            @if(!empty($ayar['alt_cta']))
                <div class="col-lg-12">
                    <div class="service-get-quote-text wow fadeInUp">
                        <p>{!! $ayar['alt_cta'] !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
