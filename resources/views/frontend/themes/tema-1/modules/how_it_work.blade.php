{{--
    Nasıl Çalışır? / Süreç Adımları — tema-1
--}}
@php
    $adimlar = collect($ayar['adimlar'] ?? [])->filter(fn ($s) => is_array($s) && !empty($s['baslik']))->take(4)->values();
@endphp

@if($adimlar->isNotEmpty())
<div class="how-it-work">
    <div class="container">
        <div class="row section-row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Süreç' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'İlk seansa kadar süreç' }}</h2>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($adimlar as $a)
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-work-item wow fadeInUp" data-wow-delay="{{ 0.2 + $loop->index * 0.15 }}s">
                        <div class="step-number" style="font-size:2.5rem;font-weight:700;color:var(--accent-color)">
                            {{ $a['ikon'] ?? str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <h3>{{ $a['baslik'] }}</h3>
                        <p>{{ $a['metin'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
