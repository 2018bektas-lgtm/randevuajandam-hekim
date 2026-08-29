{{-- Öne çıkan yazılar — Hipno case-study --}}
@php
    $limit = max(1, (int) ($ayar['blog_limiti'] ?? 3));
    $yazilar = collect($doktor['bloglar'] ?? [])->take($limit);
@endphp

@if($yazilar->isNotEmpty())
<div class="case-study">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-5">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Öne çıkanlar' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Öne Çıkan Makalelerim' }}</h2>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                    <a href="{{ route('frontend.blog') }}" class="btn-default">{{ $ayar['buton_metin'] ?? 'Tüm yazılar' }}</a>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($yazilar as $y)
                @php $href = ! empty($y['slug']) ? route('frontend.blog.detay', $y['slug']) : route('frontend.blog'); @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="case-study-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                        <div class="case-study-image">
                            <a href="{{ $href }}" data-cursor-text="İncele">
                                <figure>
                                    <img src="{{ $y['image'] ?? asset('vendor/hipno/images/case-study-img-'.(($loop->index % 3) + 1).'.jpg') }}" alt="{{ $y['baslik'] ?? '' }}" loading="lazy" decoding="async">
                                </figure>
                            </a>
                        </div>
                        <div class="case-study-content">
                            <h3><a href="{{ $href }}">{{ $y['baslik'] ?? 'Yazı' }}</a></h3>
                            @if(!empty($y['ozet']))
                                <p>{{ \Illuminate\Support\Str::limit($y['ozet'], 110) }}</p>
                            @endif
                        </div>
                        <div class="case-study-btn">
                            <a href="{{ $href }}" class="readmore-btn">Devamını oku</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
