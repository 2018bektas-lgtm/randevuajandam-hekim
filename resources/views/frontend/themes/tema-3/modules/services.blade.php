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
                <div class="col-lg-4 col-md-6">
                    <div class="service-item wow fadeInUp" data-wow-delay="{{ 0.2 + $loop->index * 0.15 }}s">
                        @if(!empty($h['image']) || !empty($h['gorsel']))
                            <div class="service-image">
                                <figure class="image-anime">
                                    <img src="{{ $h['image'] ?? $h['gorsel'] }}" alt="{{ $h['baslik'] ?? $h['ad'] ?? 'Hizmet' }}">
                                </figure>
                            </div>
                        @endif
                        <div class="service-content">
                            <h2>
                                <a href="{{ !empty($h['slug']) ? route('frontend.hizmet-detay', $h['slug']) : '#' }}">
                                    {{ $h['baslik'] ?? $h['ad'] ?? 'Hizmet' }}
                                </a>
                            </h2>
                            @if(!empty($h['ozet']) || !empty($h['aciklama']))
                                <p>{{ \Illuminate\Support\Str::limit($h['ozet'] ?? $h['aciklama'], 120) }}</p>
                            @endif
                            @if(!empty($h['slug']))
                                <a href="{{ route('frontend.hizmet-detay', $h['slug']) }}" class="readmore-btn">
                                    Detayları gör <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
