{{--
    Ne Yapıyorum? / Yaklaşımım — tema-1
--}}
@php
    $ogeler = collect($ayar['ogeler'] ?? [])->filter(fn ($s) => is_array($s) && !empty($s['baslik']))->take(4)->values();
@endphp

@if($ogeler->isNotEmpty())
<div class="what-we-do">
    <div class="container">
        <div class="row section-row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Yaklaşımım' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Danışanlarıma sunduğum destek' }}</h2>
                    @if(!empty($ayar['aciklama']))
                        <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($ogeler as $o)
                <div class="col-lg-{{ 12 / max(1, $ogeler->count()) }} col-md-6">
                    <div class="what-we-do-item wow fadeInUp" data-wow-delay="{{ 0.2 + $loop->index * 0.15 }}s">
                        <div class="icon-box">
                            @php
                                $fa = (string) ($o['ikon'] ?? 'fa-check');
                                if (! str_starts_with($fa, 'fa-')) {
                                    $fa = 'fa-check';
                                }
                            @endphp
                            <i class="fa-solid {{ $fa }}" style="font-size:1.8rem;color:var(--accent-color)"></i>
                        </div>
                        <h3>{{ $o['baslik'] }}</h3>
                        <p>{{ $o['metin'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
