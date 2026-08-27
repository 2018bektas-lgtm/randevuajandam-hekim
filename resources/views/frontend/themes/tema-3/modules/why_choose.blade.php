{{-- Neden Ben — Hipno why-choose-us --}}
@php
    $sebepler = collect($ayar['sebepler'] ?? [])->filter(fn ($s) => is_array($s) && ! empty($s['baslik']))->take(4)->values();
    $coz = static function ($path) {
        if (! filled($path)) {
            return null;
        }
        $url = function_exists('media_url') ? media_url((string) $path) : (string) $path;

        return filled($url) ? $url : (string) $path;
    };
    $foto1 = $coz($ayar['resim'] ?? null) ?: $coz($doktor['profil_resmi'] ?? null) ?: asset('vendor/hipno/images/why-choose-img-1.jpg');
    $foto2 = $coz($ayar['resim_2'] ?? null) ?: asset('vendor/hipno/images/why-choose-img-2.jpg');
@endphp

@if($sebepler->isNotEmpty())
<div class="why-choose-us">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="why-choose-us-box">
                    <div class="why-choose-image">
                        <figure class="image-anime reveal">
                            <img src="{{ $foto1 }}" alt="">
                        </figure>
                    </div>
                    <div class="why-choose-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Neden Ben?' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'Farkımı yaratan yaklaşımlar' }}</h2>
                            @if(!empty($ayar['aciklama']))
                                <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $ayar['aciklama'] }}</p>
                            @endif
                        </div>
                        <div class="why-choose-list">
                            @foreach($sebepler as $s)
                                @php
                                    $fa = (string) ($s['ikon'] ?? 'fa-star');
                                    if (! str_starts_with($fa, 'fa-')) {
                                        $fa = 'fa-star';
                                    }
                                @endphp
                                <div class="why-choose-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                                    <div class="icon-box">
                                        <i class="fa-solid {{ $fa }}"></i>
                                    </div>
                                    <div class="why-choose-item-content">
                                        <h3>{{ $s['baslik'] }}</h3>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="why-choose-body">
                            <div class="why-choose-body-image">
                                <figure class="image-anime reveal">
                                    <img src="{{ $foto2 }}" alt="">
                                </figure>
                            </div>
                            <div class="why-choose-body-content wow fadeInUp">
                                <h3>{{ $ayar['govde_baslik'] ?? 'Ruh sağlığınız için yanınızdayım' }}</h3>
                                @if(!empty($ayar['govde_metin']))
                                    <p>{{ $ayar['govde_metin'] }}</p>
                                @endif
                                <a href="{{ route('frontend.iletisim') }}" class="btn-default">{{ $ayar['buton_metin'] ?? 'İletişim' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@push('head')
<style>
.why-choose-item .icon-box i{position:relative;z-index:1;font-size:16px;color:#fff;line-height:1}
.why-choose-item:hover .icon-box i{color:#fff}
.why-choose-image .contact-circle-img{display:none!important}
</style>
@endpush
