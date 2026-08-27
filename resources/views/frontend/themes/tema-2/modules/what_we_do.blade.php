{{-- Ne Yapıyorum — Hipno intro-video-box + sayaçlar --}}
@php
    $videoUrl = trim((string) ($ayar['video_url'] ?? ''));
    $youtubeId = trim((string) ($ayar['youtube_id'] ?? ''));
    $poster = filled($ayar['poster'] ?? null)
        ? (function_exists('media_url') ? media_url($ayar['poster']) : $ayar['poster'])
        : asset('vendor/hipno/images/hero-bg.jpg');
    $varsayilanVideo = asset('vendor/hipno/videos/intro-bg-video.mp4');
    $sayaclar = [
        ['sayi' => $ayar['sayac_1_sayi'] ?? '200', 'ek' => $ayar['sayac_1_ek'] ?? 'k', 'etiket' => $ayar['sayac_1_etiket'] ?? 'mutlu danışan'],
        ['sayi' => $ayar['sayac_2_sayi'] ?? '97', 'ek' => $ayar['sayac_2_ek'] ?? '%', 'etiket' => $ayar['sayac_2_etiket'] ?? 'memnuniyet'],
        ['sayi' => $ayar['sayac_3_sayi'] ?? '12', 'ek' => $ayar['sayac_3_ek'] ?? '+', 'etiket' => $ayar['sayac_3_etiket'] ?? 'yıllık deneyim'],
        ['sayi' => $ayar['sayac_4_sayi'] ?? '40', 'ek' => $ayar['sayac_4_ek'] ?? '+', 'etiket' => $ayar['sayac_4_etiket'] ?? 'tedavi programı'],
    ];
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
                    <div class="intro-video-counter">
                        @foreach($sayaclar as $s)
                            <div class="video-counter-item">
                                <h2><span class="counter">{{ $s['sayi'] }}</span>{{ $s['ek'] }}</h2>
                                <p>{{ $s['etiket'] }}</p>
                            </div>
                        @endforeach
                    </div>
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
