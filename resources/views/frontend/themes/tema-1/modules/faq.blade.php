{{-- S.S.S. — Hipno our-faqs --}}
@php
    $limit = max(1, (int) ($ayar['sss_limiti'] ?? 6));
    $sorular = collect($doktor['sss'] ?? [])->take($limit);
    $tel = $doktor['telefon'] ?? null;
    $telRaw = $doktor['telefon_raw'] ?? preg_replace('/[^0-9+]/', '', (string) $tel);
@endphp

@if($sorular->isNotEmpty())
<div class="our-faqs parallaxie">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="our-faqs-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'S.S.S' }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Sıkça sorulan sorular' }}</h2>
                    </div>
                    <div class="faq-cta-box">
                        <div class="customer-images">
                            @foreach(['customer-img-1.jpg','customer-img-2.jpg','customer-img-3.jpg','customer-img-4.jpg'] as $ci)
                                <div class="customer-img">
                                    <figure class="image-anime reveal">
                                        <img src="{{ asset('vendor/hipno/images/'.$ci) }}" alt="">
                                    </figure>
                                </div>
                            @endforeach
                        </div>
                        <div class="faq-cta-box-content wow fadeInUp" data-wow-delay="0.2s">
                            <h3>{{ $ayar['cta_baslik'] ?? 'Hâlâ sorunuz mu var?' }}</h3>
                            @if($tel)
                                <a href="tel:{{ $telRaw }}" class="btn-faqs">{{ $tel }}</a>
                            @else
                                <a href="{{ route('frontend.iletisim') }}" class="btn-faqs">İletişime geçin</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="faq-accordion" id="modFaqAccordion">
                    @foreach($sorular as $s)
                        @php $id = 'modFaq'.$loop->iteration; @endphp
                        <div class="accordion-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                            <h2 class="accordion-header" id="{{ $id }}-h">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $id }}"
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $id }}">
                                    <span>{{ $loop->iteration }}.</span> {{ $s['soru'] ?? '' }}
                                </button>
                            </h2>
                            <div id="{{ $id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                 aria-labelledby="{{ $id }}-h" data-bs-parent="#modFaqAccordion">
                                <div class="accordion-body">
                                    <p>{!! nl2br(e($s['cevap'] ?? '')) !!}</p>
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
