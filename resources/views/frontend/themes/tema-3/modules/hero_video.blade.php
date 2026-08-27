{{--
    Hero Video (fullscreen bg) — tema-3
    @param array $ayar   video_url (mp4), video_youtube_id, başlıklar, cta
--}}
@php
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $videoUrl = trim((string) ($ayar['video_url'] ?? ''));
    $youtubeId = trim((string) ($ayar['video_youtube_id'] ?? ''));
@endphp

<div class="hero hero-video">
    @if($videoUrl !== '')
        <div class="hero-bg-video">
            <video autoplay muted loop playsinline id="heroBgVideo">
                <source src="{{ $videoUrl }}" type="video/mp4">
            </video>
        </div>
    @elseif($youtubeId !== '')
        <div class="hero-bg-video hero-bg-youtube">
            <iframe
                src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeId }}&controls=0&showinfo=0&modestbranding=1"
                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen
                style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:120vw;height:120vh;pointer-events:none;border:none">
            </iframe>
        </div>
    @elseif($doktor['profil_resmi'] ?? null)
        {{-- Video yoksa profil resmi fallback --}}
        <div class="hero-bg-video" style="background-image:url('{{ $doktor['profil_resmi'] }}');background-size:cover;background-position:center;position:absolute;inset:0;filter:brightness(.5)"></div>
    @endif

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="hero-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $ayar['ust_baslik'] ?? '' }}</h3>
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? $doktorAd }}</h1>
                        @if(!empty($ayar['aciklama']))
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                        @endif
                    </div>
                    <div class="hero-content-body">
                        <div class="hero-btn wow fadeInUp" data-wow-delay="0.4s">
                            <a href="{{ route('frontend.randevu') }}" class="btn-default">{{ $ayar['cta_metin'] ?? 'Randevu Al' }}</a>
                        </div>
                        @if(!empty($ayar['sosyal_kanit_goster']) && !empty($ayar['sosyal_kanit_sayi']))
                            <div class="hero-client-box">
                                <div class="hero-client-content">
                                    <p><span class="counter">{{ (int) $ayar['sosyal_kanit_sayi'] }}</span>+ <span>{{ $ayar['sosyal_kanit_metin'] ?? 'Danışan' }}</span></p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('head')
<style>
.hero-video { position: relative; overflow: hidden; min-height: 100vh; }
.hero-video .hero-bg-video { position: absolute; inset: 0; z-index: 0; }
.hero-video .hero-bg-video video { width: 100%; height: 100%; object-fit: cover; }
.hero-video .container { position: relative; z-index: 1; padding-top: 20vh; padding-bottom: 20vh; }
.hero-video .hero-content { color: #fff; }
.hero-video .hero-content h1, .hero-video .hero-content h3, .hero-video .hero-content p { color: #fff; }
.hero-video::after { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,.35); z-index: 0; }
.hero-video .container { z-index: 2; }
</style>
@endpush
