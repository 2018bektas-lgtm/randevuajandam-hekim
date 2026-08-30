{{--
    Footer dispatcher — TEMA BAZLI tasarım seçimi.

    Aktif temanın footer grubu (config/footer_tasarimlari.gruplar) + hekimin
    panelden seçtiği tasarım (site_options.footer_tasarim_{grup}) birlikte
    hangi blade'in render edileceğini belirler.

    Değişkenler:
      $doktor  — site içerik dizisi
      $waGoster — WhatsApp yüzen butonu bu tema için basılsın mı (varsayılan: false)
--}}
@php
    $footerService = app(\App\Services\SiteFooterService::class);
    $f = $footerService->verisi(is_array($doktor ?? null) ? $doktor : []);
    $footerView = $footerService->viewName(null, $f['ayar']);
    $waGoster = $waGoster ?? false;
@endphp

@include($footerView, ['f' => $f, 'doktor' => $doktor ?? []])

@if($waGoster)
    @include('frontend.partials.footer._wa', ['f' => $f, 'doktor' => $doktor ?? []])
@endif
