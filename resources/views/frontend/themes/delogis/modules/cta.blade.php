@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $baslik = $ayar['baslik'] ?? 'Hikayenizi dinlemeye hazırım';
    $aciklama = $ayar['aciklama'] ?? '';
    $btn = $ayar['buton_metin'] ?? 'Randevu Al';
@endphp

@if($v === 2)
<section class="cta-two">
    <div class="container">
        <div class="cta-two__inner">
            <div class="cta-two__left">
                <h3 class="cta-two__title">{{ decode_text($baslik) }}</h3>
                @if(filled($aciklama))<p>{{ decode_text($aciklama) }}</p>@endif
            </div>
            <div class="cta-two__btn-box">
                <a href="{{ route('frontend.randevu') }}" class="thm-btn">{{ $btn }}</a>
            </div>
        </div>
    </div>
</section>
@elseif($v === 4)
<section class="cta-three">
    <div class="container">
        <div class="cta-three__inner">
            <h3>{{ decode_text($baslik) }}</h3>
            @if(filled($aciklama))<p>{{ decode_text($aciklama) }}</p>@endif
            <a href="{{ route('frontend.randevu') }}" class="thm-btn thm-btn--two">{{ $btn }}</a>
        </div>
    </div>
</section>
@else
<section class="cta-one">
    <div class="cta-one__shape-1 float-bob-x">
        <img src="{{ $dg }}/images/shapes/cta-one-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="cta-one__inner">
            <p class="cta-one__text">{{ decode_text($baslik) }}</p>
            @if(filled($aciklama))
                <p style="color:rgba(255,255,255,.8);margin:.5rem 0 0;font-size:.9rem">{{ decode_text($aciklama) }}</p>
            @endif
            <div class="cta-one__btn-box">
                <a href="{{ route('frontend.randevu') }}" class="cta-one__btn thm-btn">{{ $btn }}</a>
            </div>
        </div>
    </div>
</section>
@endif
