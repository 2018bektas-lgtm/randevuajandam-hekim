{{--
    CTA (Randevu bandi) — tema-1
--}}
@php
    $bg = $ayar['arkaplan_resmi'] ?? null;
@endphp

<div class="cta-section"@if($bg) style="background-image:url('{{ $bg }}');background-size:cover;background-position:center"@endif>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cta-box">
                    <div class="cta-box-content">
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['baslik'] ?? 'İlk adımı bugün atın' }}</h2>
                        @if(!empty($ayar['aciklama']))
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                        @endif
                        <div class="wow fadeInUp" data-wow-delay="0.4s" style="margin-top:1.5rem">
                            <a href="{{ route('frontend.randevu') }}" class="btn-default">{{ $ayar['buton_metin'] ?? 'Randevu Al' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
