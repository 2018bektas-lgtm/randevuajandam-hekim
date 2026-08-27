{{--
    Danışan Yorumları — tema-1
    Kaynak: $doktor['yorumlar'] (onaylanmis)
--}}
@php
    $limit = max(1, (int) ($ayar['yorum_limiti'] ?? 6));
    $yorumlar = collect($doktor['yorumlar'] ?? [])->take($limit);
@endphp

@if($yorumlar->isNotEmpty())
<div class="our-testimonial">
    <div class="container">
        <div class="row section-row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Danışan Yorumları' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Danışanlarımın hikayeleri' }}</h2>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($yorumlar as $y)
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-item wow fadeInUp" data-wow-delay="{{ 0.2 + $loop->index * 0.15 }}s">
                        <div class="testimonial-rating">
                            @for($i = 0; $i < (int) ($y['puan'] ?? 5); $i++)
                                <i class="fa-solid fa-star" style="color:var(--accent-color)"></i>
                            @endfor
                        </div>
                        <div class="testimonial-content">
                            <p>"{{ $y['yorum'] ?? $y['metin'] ?? '' }}"</p>
                        </div>
                        <div class="author-content" style="margin-top:1rem">
                            <h3 style="font-size:1rem">{{ $y['ad'] ?? $y['maskeli_ad'] ?? 'Danışan' }}</h3>
                            @if(!empty($y['tarih']))
                                <span style="font-size:.85rem;opacity:.7">{{ $y['tarih'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
