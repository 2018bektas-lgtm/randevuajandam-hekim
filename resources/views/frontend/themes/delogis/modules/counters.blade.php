@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $kucuk = $ayar['kucuk_baslik'] ?? 'Rakamlarla';
    $baslik = $ayar['ana_baslik'] ?? 'Her yeni süreçte hazırım';
    $cta = $ayar['cta_metin'] ?? 'Hikayenizi dinlemeye hazırım';
    $btn = $ayar['buton_metin'] ?? 'Randevu Al';
    /*
     * Sayaclar: panel > hekimin gercek verisi > HIC GOSTERME.
     *
     * Eskiden veri yoksa 200 "Mutlu danisan", 97 "Memnuniyet %",
     * 12 "Yillik deneyim" sabit degerleri basiliyordu. Bunlar hekimin hic
     * vermedigi, uydurulmus iddialardi ve saglik alanindaki bir sitede
     * gercek gibi gorunuyordu. Artik uydurma sayi basilmiyor; veri yoksa
     * bolum tamamen gizleniyor.
     */
    $stats = collect();
    for ($i = 1; $i <= 3; $i++) {
        $sayi = trim((string) ($ayar["sayac_{$i}_sayi"] ?? ''));
        if ($sayi === '') {
            continue;
        }
        $stats->push([
            'deger' => $sayi,
            'etiket' => (string) ($ayar["sayac_{$i}_etiket"] ?? ''),
            'ikon' => 'icon-checking',
        ]);
    }

    if ($stats->isEmpty()) {
        $stats = collect($doktor['istatistikler'] ?? [])
            ->filter(fn ($s) => is_array($s) && filled($s['etiket'] ?? null))
            ->take(3)
            ->values();
    }
    $statIcons = ['icon-checking', 'icon-recommend', 'icon-consulting'];
@endphp
@if($stats->isNotEmpty())
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
@endif
