{{--
    Neden Ben? — tema-1
--}}
@php
    $sebepler = collect($ayar['sebepler'] ?? [])->filter(fn ($s) => is_array($s) && !empty($s['baslik']))->take(4)->values();
@endphp

@if($sebepler->isNotEmpty())
<div class="why-choose-us">
    <div class="container">
        <div class="row section-row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Neden Ben?' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Farkımı yaratan yaklaşımlar' }}</h2>
                    @if(!empty($ayar['aciklama']))
                        <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($sebepler as $s)
                <div class="col-lg-3 col-md-6">
                    <div class="why-choose-item wow fadeInUp" data-wow-delay="{{ 0.2 + $loop->index * 0.15 }}s">
                        <div class="icon-box">
                            @php
                                $fa = (string) ($s['ikon'] ?? 'fa-star');
                                if (! str_starts_with($fa, 'fa-')) {
                                    $fa = 'fa-star';
                                }
                            @endphp
                            <i class="fa-solid {{ $fa }}" style="font-size:2rem;color:var(--accent-color)"></i>
                        </div>
                        <div class="why-choose-content">
                            <h3>{{ $s['baslik'] }}</h3>
                            <p>{{ $s['metin'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
