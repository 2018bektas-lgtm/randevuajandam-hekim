{{-- CTA — Hipno cta-box --}}
@php
    $resim = filled($ayar['resim'] ?? null)
        ? (function_exists('media_url') ? media_url($ayar['resim']) : $ayar['resim'])
        : asset('vendor/hipno/images/cta-img.png');

    /*
     * Arkaplan resmi panelde tanimliydi ama blade onu HIC okumuyordu:
     * hekim gorseli yukluyor, kaydediliyor, sayfada hicbir sey degismiyordu.
     */
    $arkaplan = filled($ayar['arkaplan_resmi'] ?? null)
        ? (function_exists('media_url') ? media_url($ayar['arkaplan_resmi']) : $ayar['arkaplan_resmi'])
        : null;
@endphp

<div class="cta-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cta-box"@if($arkaplan) style="background-image:url('{{ $arkaplan }}');background-size:cover;background-position:center"@endif>
                    <div class="cta-box-image">
                        <img src="{{ $resim }}" alt="{{ trim((string) ($doktor['unvan'] ?? '').' '.(string) ($doktor['ad_soyad'] ?? 'Hekim')) }}" loading="lazy" decoding="async">
                    </div>
                    <div class="cta-box-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Randevu' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['baslik'] ?? 'İlk adımı bugün atın' }}</h2>
                            @if(!empty($ayar['aciklama']))
                                <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                            @endif
                        </div>
                        <div class="cta-box-btn wow fadeInUp">
                            <a href="{{ route('frontend.anasayfa') }}#randevu-al" class="btn-default">{{ $ayar['buton_metin'] ?? 'Randevu Al' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
