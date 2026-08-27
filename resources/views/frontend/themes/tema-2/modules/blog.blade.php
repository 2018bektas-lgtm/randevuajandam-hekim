{{-- Blog — Hipno our-blog / post-item --}}
@php
    $limit = max(1, (int) ($ayar['blog_limiti'] ?? 3));
    $yazilar = collect($doktor['bloglar'] ?? [])->take($limit);
@endphp

@if($yazilar->isNotEmpty())
<div class="our-blog">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Blog' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Son yazılarım' }}</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                    <a href="{{ route('frontend.blog') }}" class="btn-default">{{ $ayar['buton_metin'] ?? 'Tüm yazılar' }}</a>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($yazilar as $y)
                @php $href = ! empty($y['slug']) ? route('frontend.blog.detay', $y['slug']) : route('frontend.blog'); @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="post-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                        <div class="post-featured-image">
                            <figure>
                                <a href="{{ $href }}" class="image-anime" data-cursor-text="İncele">
                                    <img src="{{ $y['image'] ?? asset('vendor/hipno/images/post-'.(($loop->index % 6) + 1).'.jpg') }}" alt="{{ $y['baslik'] ?? '' }}">
                                </a>
                            </figure>
                        </div>
                        <div class="post-item-body">
                            <div class="post-item-content">
                                <h3><a href="{{ $href }}">{{ $y['baslik'] ?? 'Yazı' }}</a></h3>
                            </div>
                            <div class="post-item-btn">
                                <a href="{{ $href }}" class="readmore-btn">Devamını oku</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
