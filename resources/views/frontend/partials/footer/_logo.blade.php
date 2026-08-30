{{--
    Footer logo alanı — panel: Site Ayarları → Footer → Logo alanı.
    $f  : SiteFooterService::verisi()
    $hiza : 'sol' | 'orta'
--}}
@php $hiza = $hiza ?? 'sol'; @endphp

@if($f['logo_tip'] !== 'gizli')
    <div class="ftr-logo ftr-logo--{{ $hiza }}">
        <a href="{{ route('frontend.anasayfa') }}" class="ftr-logo__link" aria-label="{{ $f['tam_ad'] }}">
            @if($f['logo_tip'] !== 'yazi' && $f['logo_url'] !== '')
                <img src="{{ $f['logo_url'] }}" alt="{{ $f['tam_ad'] }}"
                     style="height:{{ $f['logo_yukseklik'] }}px" loading="lazy" decoding="async">
            @else
                <span class="ftr-wordmark">
                    @if($f['unvan'] !== '')
                        <span class="ftr-wordmark__unvan">{{ $f['unvan'] }}</span>
                    @endif
                    <span class="ftr-wordmark__ad">{{ $f['ad_soyad'] }}</span>
                </span>
            @endif
        </a>
    </div>
@endif
