@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $kucuk = $ayar['kucuk_baslik'] ?? 'Rakamlarla';
    $baslik = $ayar['ana_baslik'] ?? 'Her yeni süreçte hazırım';
    $cta = $ayar['cta_metin'] ?? 'Hikayenizi dinlemeye hazırım';
    $btn = $ayar['buton_metin'] ?? 'Randevu Al';
    $stats = collect($doktor['istatistikler'] ?? [])->filter(fn ($s) => is_array($s) && filled($s['etiket'] ?? null))->take(3)->values();
    if ($stats->isEmpty()) {
        $stats = collect([
            ['deger' => $ayar['sayac_1_sayi'] ?? 200, 'etiket' => $ayar['sayac_1_etiket'] ?? 'Mutlu danışan', 'ikon' => 'icon-checking'],
            ['deger' => $ayar['sayac_2_sayi'] ?? 97, 'etiket' => $ayar['sayac_2_etiket'] ?? 'Memnuniyet %', 'ikon' => 'icon-recommend'],
            ['deger' => $ayar['sayac_3_sayi'] ?? 12, 'etiket' => $ayar['sayac_3_etiket'] ?? 'Yıllık deneyim', 'ikon' => 'icon-consulting'],
        ]);
    }
    $statIcons = ['icon-checking', 'icon-recommend', 'icon-consulting'];
@endphp
<section class="counter-one">
    <div class="counter-one__bg jarallax" data-jarallax data-speed="0.2" data-imgposition="50% 0%" style="background-image: url({{ $dg }}/images/backgrounds/counter-one-bg.jpg);"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <div class="counter-one__left">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="counter-one__right">
                    <ul class="counter-one__count-box list-unstyled">
                        @foreach ($stats as $idx => $stat)
                            @php
                                $num = (int) preg_replace('/\D+/', '', (string) ($stat['deger'] ?? $stat['sayi'] ?? 0));
                                $ikon = $stat['ikon'] ?? $statIcons[$idx % 3];
                            @endphp
                            <li>
                                <div class="counter-one__icon"><span class="{{ $ikon }}"></span></div>
                                <div class="counter-one__count count-box">
                                    <h3 class="count-text" data-stop="{{ $num }}" data-speed="1500">{{ $num }}</h3>
                                </div>
                                <p class="counter-one__text">{{ decode_text($stat['etiket'] ?? '') }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="counter-one__bottom">
            <div class="counter-one__text"><p>{{ decode_text($cta) }}</p></div>
            <div class="counter-one__btn-box">
                <a href="{{ route('frontend.randevu') }}" class="counter-one__btn thm-btn">{{ $btn }}</a>
            </div>
        </div>
    </div>
</section>
