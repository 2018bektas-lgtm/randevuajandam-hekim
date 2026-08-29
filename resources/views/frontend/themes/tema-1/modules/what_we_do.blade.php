{{-- Ne Yapıyorum — Hipno intro-video-box + sayaçlar --}}
@php
    $videoUrl = trim((string) ($ayar['video_url'] ?? ''));
    $youtubeId = trim((string) ($ayar['youtube_id'] ?? ''));
    $poster = filled($ayar['poster'] ?? null)
        ? (function_exists('media_url') ? media_url($ayar['poster']) : $ayar['poster'])
        : asset('vendor/hipno/images/hero-bg.jpg');
    $varsayilanVideo = asset('vendor/hipno/videos/intro-bg-video.mp4');
    /*
     * Sayaclar.
     *
     * ESKIDEN: "200k mutlu danisan", "%97 memnuniyet", "12+ yillik deneyim",
     * "40+ tedavi programi" SABIT yaziliydi. Bunlar hekimin hic vermedigi,
     * uydurulmus iddialardi ve saglik alaninda bir sitede gercek gibi
     * gosteriliyordu.
     *
     * SIMDI: once panelde girilen degerler, yoksa hekimin GERCEK verisinden
     * uretilen istatistikler ($doktor['istatistikler'] — hizmet sayisi, blog
     * sayisi, ortalama puan, biyografiden cikarilan deneyim yili).
     * Hicbiri yoksa sayac bolumu HIC gosterilmez; uydurma sayi basilmaz.
     */
    $sayaclar = [];

    for ($i = 1; $i <= 4; $i++) {
        $sayi = trim((string) ($ayar["sayac_{$i}_sayi"] ?? ''));
        if ($sayi === '') {
            continue;
        }
        $sayaclar[] = [
            'sayi' => $sayi,
            'ek' => (string) ($ayar["sayac_{$i}_ek"] ?? ''),
            'etiket' => (string) ($ayar["sayac_{$i}_etiket"] ?? ''),
        ];
    }

    if ($sayaclar === []) {
        $sayaclar = collect($doktor['istatistikler'] ?? [])
            ->filter(fn ($s) => is_array($s) && filled($s['deger'] ?? null))
            ->take(4)
            ->map(fn ($s) => [
                'sayi' => (string) $s['deger'],
                'ek' => (string) ($s['suffix'] ?? ''),
                'etiket' => (string) ($s['etiket'] ?? ''),
            ])
            ->values()
            ->all();
    }
@endphp

<div class="what-we-do">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Yaklaşımım' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Danışanlarıma sunduğum destek' }}</h2>
                </div>
            </div>
            <div class="col-lg-6">
                @if(!empty($ayar['aciklama']))
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.2s">
                        <p>{{ $ayar['aciklama'] }}</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="intro-video-box">
                    <div class="intro-bg-video">
                        @if($videoUrl !== '')
                            <video autoplay muted loop playsinline poster="{{ $poster }}">
                                <source src="{{ $videoUrl }}" type="video/mp4">
                            </video>
                        @elseif($youtubeId !== '')
                            <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeId }}&controls=0&showinfo=0"
                                    allow="autoplay; encrypted-media" style="position:absolute;inset:0;width:100%;height:100%;border:0;pointer-events:none"></iframe>
                        @else
                            <video autoplay muted loop playsinline poster="{{ $poster }}">
                                <source src="{{ $varsayilanVideo }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                    @if($youtubeId !== '' || $videoUrl !== '')
                        <div class="video-play-button">
                            <a href="{{ $youtubeId !== '' ? 'https://www.youtube.com/watch?v='.$youtubeId : $videoUrl }}" class="popup-video" data-cursor-text="Oynat">
                                <i class="fa-solid fa-play"></i>
                            </a>
                        </div>
                    @endif
                    @if($sayaclar !== [])
                    <div class="intro-video-counter">
                        @foreach($sayaclar as $s)
                            <div class="video-counter-item">
                                <h2><span class="counter">{{ $s['sayi'] }}</span>{{ $s['ek'] }}</h2>
                                <p>{{ $s['etiket'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('head')
<style>
.intro-bg-video{position:relative;overflow:hidden}
.intro-bg-video video,.intro-bg-video iframe{width:100%;height:100%;object-fit:cover}
</style>
@endpush
