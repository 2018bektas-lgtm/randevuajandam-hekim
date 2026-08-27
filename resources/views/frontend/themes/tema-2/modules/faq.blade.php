{{--
    S.S.S. — tema-1
    Kaynak: $doktor['sss'] (accordion listesi)
--}}
@php
    $limit = max(1, (int) ($ayar['sss_limiti'] ?? 6));
    $sorular = collect($doktor['sss'] ?? [])->take($limit);
@endphp

@if($sorular->isNotEmpty())
<div class="our-faqs">
    <div class="container">
        <div class="row section-row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'S.S.S' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Sıkça sorulan sorular' }}</h2>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="faq-accordion accordion" id="modFaqAccordion">
                    @foreach($sorular as $s)
                        @php $id = 'modFaq'.$loop->iteration; @endphp
                        <div class="accordion-item wow fadeInUp" data-wow-delay="{{ 0.2 + $loop->index * 0.1 }}s">
                            <h2 class="accordion-header" id="{{ $id }}-h">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $id }}"
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $id }}">
                                    {{ $s['soru'] ?? '' }}
                                </button>
                            </h2>
                            <div id="{{ $id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                 aria-labelledby="{{ $id }}-h" data-bs-parent="#modFaqAccordion">
                                <div class="accordion-body">
                                    {!! nl2br(e($s['cevap'] ?? '')) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
