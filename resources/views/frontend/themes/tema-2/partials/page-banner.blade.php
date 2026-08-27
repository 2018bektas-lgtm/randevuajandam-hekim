{{--
    Sayfa banner (page-header) — tema-1.

    @param string $kod    Sayfa kodu (hakkimda, hizmetler, blog, ...)
    @param string $baslik Fallback başlık (config varsayılan yoksa)
    @param array  $breadcrumb  [['label' => 'X', 'href' => '/y']]
    @param string|null $bgOverride  Sayfa-özel arkaplan (örn. blog detay için yazı görseli)

    SayfaIcerikService'ten banner_baslik/banner_alt/banner_gorsel çekilir.
    Görsel yoksa: bgOverride > profil_resmi fallback.
--}}
@php
    $__ayar = app(\App\Services\SayfaIcerikService::class)->sayfaAyarlari($kod ?? '');
    $__baslik = filled($__ayar['banner_baslik'] ?? null) ? $__ayar['banner_baslik'] : ($baslik ?? '');
    $__alt = $__ayar['banner_alt'] ?? null;
    $__bg = filled($bgOverride ?? null)
        ? $bgOverride
        : (filled($__ayar['banner_gorsel'] ?? null) ? $__ayar['banner_gorsel'] : ($doktor['profil_resmi'] ?? null));
@endphp

<div class="page-header parallaxie"@if($__bg) style="background-image:url('{{ $__bg }}')"@endif>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="page-header-box">
                    <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $__baslik }}</h1>
                    @if($__alt)
                        <p class="wow fadeInUp" data-wow-delay="0.15s" style="color:#fff;opacity:.9;font-size:1.05rem;margin-top:.75rem;max-width:640px">{{ $__alt }}</p>
                    @endif
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('frontend.anasayfa') }}">Anasayfa</a></li>
                            @foreach($breadcrumb ?? [] as $crumb)
                                <li class="breadcrumb-item {{ ($crumb['aktif'] ?? false) ? 'active' : '' }}">
                                    @if(($crumb['aktif'] ?? false) || empty($crumb['href']))
                                        {{ $crumb['label'] }}
                                    @else
                                        <a href="{{ $crumb['href'] }}">{{ $crumb['label'] }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
