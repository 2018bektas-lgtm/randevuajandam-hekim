@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $kucuk = $ayar['kucuk_baslik'] ?? 'Randevu';
    $baslik = $ayar['ana_baslik'] ?? 'Online randevu alın';
    $aciklama = $ayar['aciklama'] ?? '';
    $kutu = $ayar['kutu_baslik'] ?? 'İlk seans için arayın';
@endphp
<section class="contact-one" id="randevu">
    <div class="contact-one__shape-1 float-bob-x">
        <img src="{{ $dg }}/images/shapes/contact-one-shape-1.png" alt="" loading="lazy" decoding="async">
    </div>
    <div class="contact-one__shape-2">
        <img src="{{ $dg }}/images/shapes/contact-one-shape-2.png" alt="" loading="lazy" decoding="async">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-5">
                <div class="contact-one__left">
                    <div class="contact-one__contact-box">
                        <div class="contact-one__contact-box-bg" style="background-image: url({{ $dg }}/images/backgrounds/contact-one-contact-box-bg.png);"></div>
                        <div class="contact-one__icon"><span class="icon-phone"></span></div>
                        <p class="contact-one__sub-title">{{ decode_text($kucuk) }}</p>
                        <h3 class="contact-one__title">{{ decode_text($kutu) }}</h3>
                        @if($tel)
                            <p class="contact-one__nummber"><a href="tel:{{ $telRaw }}">{{ $tel }}</a></p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-lg-7">
                <div class="contact-one__right">
                    <div class="section-title text-left">
                        <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
                        <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
                    </div>
                    @if(filled($aciklama))
                        <p style="margin-bottom:20px">{{ decode_text($aciklama) }}</p>
                    @endif
                    <div class="contact-one__form-box ra-wizard-embed-wrap">
                        @include('frontend.partials.randevu-wizard', ['raEmbed' => true, 'ayar' => []])
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('head')
<style>
.contact-one .ra-wizard-section{padding:0;background:transparent}
.contact-one .ra-wizard-card{max-width:none;margin:0;padding:0;box-shadow:none;border-radius:0;overflow:visible}
</style>
@endpush
