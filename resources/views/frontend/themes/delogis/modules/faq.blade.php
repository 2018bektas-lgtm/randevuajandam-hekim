@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $limit = max(1, (int) ($ayar['sss_limiti'] ?? 6));
    $sss = collect($doktor['sss'] ?? $doktor['sorular'] ?? [])
        ->filter(fn ($s) => is_array($s) && filled($s['soru'] ?? $s['baslik'] ?? null))
        ->take($limit)
        ->values();
    $kucuk = $ayar['kucuk_baslik'] ?? 'S.S.S.';
    $baslik = $ayar['ana_baslik'] ?? 'Sıkça sorulan sorular';
    $aciklama = $ayar['aciklama'] ?? '';
    $img = $media($ayar['resim'] ?? null) ?: $photo ?: $dg.'/images/resources/faq-one-img.jpg';
@endphp
@if($sss->isNotEmpty())
<section class="faq-one">
    <div class="faq-one__bg" style="background-image: url({{ $dg }}/images/backgrounds/faq-one-bg.png);"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="faq-one__left">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                    <div class="faq-one__img-and-points">
                        <div class="faq-one__img"><img src="{{ $img }}" alt=""></div>
                        @if(filled($aciklama))
                        <ul class="faq-one__points list-unstyled">
                            <li>
                                <div class="icon"><span class="icon-right-arrow"></span></div>
                                <div class="content">
                                    <h3>{{ decode_text($kucuk) }}</h3>
                                    <p>{{ decode_text($aciklama) }}</p>
                                </div>
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="faq-one__right">
                    <div class="accrodion-grp" data-grp-name="faq-one-accrodion">
                        @foreach ($sss as $i => $s)
                            <div class="accrodion {{ $i === 1 ? 'active' : '' }}">
                                <div class="accrodion-title">
                                    <h4>{{ decode_text($s['soru'] ?? $s['baslik'] ?? '') }}</h4>
                                </div>
                                <div class="accrodion-content">
                                    <div class="inner">
                                        <p>{{ plain_text($s['cevap'] ?? $s['metin'] ?? $s['aciklama'] ?? '', 400) }}</p>
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
@endif
