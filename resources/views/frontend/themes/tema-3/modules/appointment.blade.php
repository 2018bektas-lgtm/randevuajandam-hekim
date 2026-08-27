{{-- Randevu — Hipno our-appointment kutusu + sihirbaz --}}
@php
    $tel = $doktor['telefon'] ?? null;
    $telRaw = $doktor['telefon_raw'] ?? preg_replace('/[^0-9+]/', '', (string) $tel);
@endphp

<div class="our-appointment">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="our-appointment-box">
                    <div class="appointment-form ra-wizard-embed-wrap">
                        @include('frontend.partials.randevu-wizard', ['raEmbed' => true, 'ayar' => []])
                    </div>
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
                                        <img src="{{ asset('vendor/hipno/images/icon-appointment-item-1.svg') }}" alt="">
                                    </div>
                                    <div class="appointment-item-content">
                                        <h3>Telefon</h3>
                                        <p><a href="tel:{{ $telRaw }}" style="color:inherit">{{ $tel }}</a></p>
                                    </div>
                                </div>
                            @endif
                            <div class="appointment-item wow fadeInUp" data-wow-delay="0.6s">
                                <div class="icon-box">
                                    <img src="{{ asset('vendor/hipno/images/icon-appointment-item-2.svg') }}" alt="">
                                </div>
                                <div class="appointment-item-content">
                                    <h3>{{ $ayar['saat_baslik'] ?? 'Çalışma saatleri' }}</h3>
                                    <p>{{ $ayar['calisma_saatleri'] ?? 'Pzt - Cmt 09:00 - 21:00' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('head')
<style>
.our-appointment-box .ra-wizard-section{padding:0;background:transparent}
.our-appointment-box .ra-wizard-card{max-width:none;margin:0;padding:0;box-shadow:none;border-radius:0}
.our-appointment-box .ra-progress{margin-bottom:28px;padding:0}
@media (max-width: 991px){
    .our-appointment-box{padding:40px 24px}
    .our-appointment-content,.appointment-form{width:100%}
}
</style>
@endpush
