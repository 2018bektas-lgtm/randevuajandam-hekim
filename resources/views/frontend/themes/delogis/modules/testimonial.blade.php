@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $limit = max(1, (int) ($ayar['yorum_limiti'] ?? 6));
    $yorumlar = collect($doktor['yorumlar'] ?? [])
        ->filter(fn ($y) => is_array($y) && filled($y['yorum'] ?? $y['metin'] ?? $y['content'] ?? null))
        ->take($limit)
        ->values();
    $kucuk = $ayar['kucuk_baslik'] ?? 'Yorumlar';
    $baslik = $ayar['ana_baslik'] ?? 'Danışan deneyimleri';
    $avg = $yorumlar->avg(fn ($y) => (float) ($y['puan'] ?? 5)) ?: 5;
@endphp
@if($yorumlar->isNotEmpty())

@if($v === 1)
<section class="testimonial-one">
    <div class="container">
        <div class="row">
            <div class="col-xl-4">
                <div class="testimonial-one__left">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                    <div class="testimonial-one__ratting-box">
                        <p class="testimonial-one__ratting-title">{{ number_format($avg, 1) }} ortalama puan</p>
                        <div class="testimonial-one__ratting">
                            @for($i = 0; $i < 5; $i++)<i class="fa fa-star"></i>@endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="testimonial-one__right">
                    <div class="testimonial-one__carousel owl-carousel owl-theme thm-owl__carousel" data-owl-options='{"loop": {{ $yorumlar->count() > 1 ? 'true' : 'false' }}, "autoplay": true, "margin": 30, "nav": true, "dots": false, "smartSpeed": 500, "autoplayTimeout": 10000, "navText": ["<span class=\"icon-left-arrow\"></span>","<span class=\"icon-right-arrow\"></span>"], "responsive": {"0": {"items": 1}, "1200": {"items": 1}}}'>
                        @foreach ($yorumlar as $y)
                            <div class="item">
                                <div class="testimonial-one__single">
                                    <div class="testimonial-one__img">
                                        <img src="{{ $y['foto'] ?? $y['avatar'] ?? $dg.'/images/testimonial/testimonial-1-1.jpg' }}" alt="{{ $y['ad'] ?? 'Danisan' }}" loading="lazy" decoding="async">
                                        <div class="testimonial-one__quote">
                                            <img src="{{ $dg }}/images/icon/icon-quote.png" alt="" loading="lazy" decoding="async">
                                        </div>
                                    </div>
                                    <div class="testimonial-one__content">
                                        <p class="testimonial-one__text">{{ plain_text($y['yorum'] ?? $y['metin'] ?? $y['content'] ?? '', 280) }}</p>
                                        <div class="testimonial-one__client-name">
                                            <h4>{{ decode_text($y['hasta_adi'] ?? $y['ad'] ?? 'Danışan') }} .<span>Danışan</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@else
<section class="testimonial-three">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
            <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
        </div>
        <div class="row">
            @foreach ($yorumlar as $y)
                <div class="col-xl-6 col-lg-6">
                    <div class="testimonial-three__single" style="margin-bottom:24px">
                        <p class="testimonial-three__text">“{{ plain_text($y['yorum'] ?? $y['metin'] ?? $y['content'] ?? '', 180) }}”</p>
                        <div class="testimonial-three__client-info">
                            <h4 class="testimonial-three__client-name">{{ decode_text($y['hasta_adi'] ?? $y['ad'] ?? 'Danışan') }}</h4>
                            <p class="testimonial-three__client-sub-title">
                                @if(!empty($y['puan'])) {{ $y['puan'] }}/5 · @endif
                                Değerlendirme
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif
