{{-- Randevu — Hipno our-appointment kutusu + sihirbaz (tam genişlik) --}}
@php
    $tel = $doktor['telefon'] ?? null;
    $telRaw = $doktor['telefon_raw'] ?? preg_replace('/[^0-9+]/', '', (string) $tel);

    /*
     * Calisma saatleri: once panel ayari, sonra hekimin GERCEK calisma
     * saatleri ozeti (API'den geliyor).
     *
     * Eskiden burada sabit 'Pzt - Cmt 09:00 - 21:00' yaziliydi; hekim baska
     * saatlerde calissa bile hastaya bu gosteriliyordu. Yanlis bilgi
     * gostermektense hic gostermemek dogru: ikisi de yoksa blok gizlenir.
     */
    $calismaSaatleri = trim((string) ($ayar['calisma_saatleri'] ?? ''));
    if ($calismaSaatleri === '') {
        $calismaSaatleri = trim((string) ($doktor['calisma_saatleri_ozet'] ?? ''));
    }
@endphp

<div class="our-appointment">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="our-appointment-box is-wizard">
                    <div class="our-appointment-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Randevu' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Hemen randevu alın' }}</h2>
                            @if(!empty($ayar['aciklama']))
                                <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                            @endif
                        </div>
                        <div class="appointment-content-body">
                            @if($tel)
                                <div class="appointment-item wow fadeInUp" data-wow-delay="0.4s">
                                    <div class="icon-box">
                                        <img src="{{ asset('vendor/hipno/images/icon-appointment-item-1.svg') }}" alt="" aria-hidden="true" loading="lazy" decoding="async">
                                    </div>
                                    <div class="appointment-item-content">
                                        <h3>Telefon</h3>
                                        <p><a href="tel:{{ $telRaw }}" style="color:inherit">{{ $tel }}</a></p>
                                    </div>
                                </div>
                            @endif
                            @if($calismaSaatleri !== '')
                            <div class="appointment-item wow fadeInUp" data-wow-delay="0.6s">
                                <div class="icon-box">
                                    <img src="{{ asset('vendor/hipno/images/icon-appointment-item-2.svg') }}" alt="" aria-hidden="true" loading="lazy" decoding="async">
                                </div>
                                <div class="appointment-item-content">
                                    <h3>{{ $ayar['saat_baslik'] ?? 'Çalışma saatleri' }}</h3>
                                    <p>{{ $calismaSaatleri }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="appointment-form ra-wizard-embed-wrap">
                        @include('frontend.partials.randevu-wizard', ['raEmbed' => true, 'ayar' => []])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('head')
<style>
.our-appointment-box.is-wizard{flex-direction:column;align-items:stretch;gap:28px;padding:56px 48px;overflow:visible}
.our-appointment-box.is-wizard .appointment-form,
.our-appointment-box.is-wizard .our-appointment-content{width:100%}
.our-appointment-box.is-wizard .our-appointment-content{display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:24px}
.our-appointment-box.is-wizard .our-appointment-content .section-title{margin-bottom:0;max-width:520px}
.our-appointment-box.is-wizard .appointment-content-body{display:flex;flex-wrap:wrap;gap:24px 36px}
.our-appointment-box.is-wizard .appointment-item{border-bottom:none;margin:0;padding:0}
.our-appointment-box .ra-wizard-section{padding:0;background:transparent}
.our-appointment-box .ra-wizard-card{max-width:none;margin:0;padding:0;box-shadow:none;border-radius:0;overflow:visible}
.our-appointment-box .ra-progress{margin-bottom:28px;padding:0}
.our-appointment-box .ra-days{grid-template-columns:repeat(7,minmax(0,1fr))}
@media (max-width: 991px){
    .our-appointment-box.is-wizard{padding:32px 20px}
    .our-appointment-content,.appointment-form{width:100%}
}
</style>
@endpush
