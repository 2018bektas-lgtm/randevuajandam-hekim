{{--
    Öne Çıkan Yazılar (Case Study formatinda) — tema-1
    Kaynak: $doktor['bloglar']
--}}
@php
    $limit = max(1, (int) ($ayar['blog_limiti'] ?? 3));
    $yazilar = collect($doktor['bloglar'] ?? [])->take($limit);
@endphp

@if($yazilar->isNotEmpty())
<div class="case-study">
    <div class="container">
        <div class="row section-row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Öne Çıkan Makalelerim' }}</h2>
                    @if(!empty($ayar['aciklama']))
                        <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($yazilar as $y)
                <div class="col-lg-4 col-md-6">
                    <div class="case-study-item wow fadeInUp" data-wow-delay="{{ 0.2 + $loop->index * 0.15 }}s">
                        @if(!empty($y['image']))
                            <div class="case-study-image">
                                <figure class="image-anime">
                                    <img src="{{ $y['image'] }}" alt="{{ $y['baslik'] ?? '' }}">
                                </figure>
                            </div>
                        @endif
                        <div class="case-study-content">
                            <h3>
                                <a href="{{ !empty($y['slug']) ? route('frontend.blog.detay', $y['slug']) : '#' }}">
                                    {{ $y['baslik'] ?? 'Yazı' }}
                                </a>
                            </h3>
                            @if(!empty($y['ozet']))
                                <p>{{ \Illuminate\Support\Str::limit($y['ozet'], 100) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
