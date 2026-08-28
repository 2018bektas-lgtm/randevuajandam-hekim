@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $kucuk = $ayar['kucuk_baslik'] ?? 'Uzmanlık';
    $baslik = $ayar['ana_baslik'] ?? 'Kişiye özel terapi seansları';
    $yt = trim((string) ($ayar['youtube_id'] ?? ''));
    $img = $media($ayar['resim'] ?? null) ?: $photo ?: $dg.'/images/resources/get-one-img-1.png';
    $maddeler = collect($ayar['maddeler'] ?? [])->filter(fn ($s) => is_array($s) && filled($s['baslik'] ?? null))->take(4)->values();
@endphp
<section class="get-one">
    <div class="get-one__bg" style="background-image: url({{ $dg }}/images/backgrounds/get-one-bg.jpg);"></div>
    <div class="get-one__img-box">
        <div class="get-one__img"><img src="{{ $img }}" alt="{{ $ad }}"></div>
        @if($yt !== '')
        <div class="get-one__video-link">
            <a href="https://www.youtube.com/watch?v={{ $yt }}" class="video-popup">
                <div class="get-one__video-icon">
                    <span class="fa fa-play"></span>
                    <i class="ripple"></i>
                </div>
            </a>
        </div>
        @endif
    </div>
    <div class="get-one__shape-1 float-bob-y"><img src="{{ $dg }}/images/shapes/get-one-shape-1.png" alt=""></div>
    <div class="get-one__shape-2 float-bob-x"><img src="{{ $dg }}/images/shapes/get-one-shape-2.png" alt=""></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-8">
                <div class="get-one__left">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                    @if($maddeler->isNotEmpty())
                    <div class="get-one__points-list">
                        <div class="row">
                            @foreach ($maddeler as $m)
                                @php $ikon = $m['ikon'] ?? 'icon-woman'; @endphp
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="get-one__points-single">
                                        <div class="get-one__points-icon"><span class="{{ $ikon }}"></span></div>
                                        <div class="get-one__points-title"><h4>{{ decode_text($m['baslik']) }}</h4></div>
                                        @if(!empty($m['metin']))
                                            <div class="get-one__points-text-box">
                                                <p class="get-one__points-text">{{ decode_text($m['metin']) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
