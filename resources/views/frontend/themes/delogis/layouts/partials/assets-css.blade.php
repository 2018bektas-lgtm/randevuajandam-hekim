@php
    // Kök-relative: APP_URL/asset() hatalarında bile aynı host'tan yüklensin
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
@endphp
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&family=Castoro:ital@0;1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ $dg }}/vendors/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/animate/animate.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/animate/custom-animate.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/fontawesome/css/all.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/jarallax/jarallax.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/jquery-magnific-popup/jquery.magnific-popup.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/nouislider/nouislider.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/nouislider/nouislider.pips.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/odometer/odometer.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/swiper/swiper.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/delogis-icons/style.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/tiny-slider/tiny-slider.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/reey-font/stylesheet.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/alagambe-font/stylesheet.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/owl-carousel/owl.carousel.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/owl-carousel/owl.theme.default.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/bxslider/jquery.bxslider.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/bootstrap-select/css/bootstrap-select.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/vegas/vegas.min.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="{{ $dg }}/vendors/timepicker/timePicker.css">
{{-- index3.html: yalnızca delogis.css (color-1/2 ekleme — orijinal palet) --}}
<link rel="stylesheet" href="{{ $dg }}/css/delogis.css">
{{-- Randevu sihirbazı stilleri --}}
<link rel="stylesheet" href="{{ rtrim((string) request()->getBasePath(), '/') }}/css/themes/modern.css?v=dg2">
<style>
/* Orijinal index3 renkleri (delogis.css :root ile aynı) — panel rengi override etmesin */
body.theme-delogis,
body.theme-pack-delogis {
  --delogis-base: #976147;
  --delogis-base-rgb: 151, 97, 71;
  --delogis-black: #1a1414;
  --delogis-black-rgb: 26, 20, 20;
  --delogis-primary: #f2edea;
  --delogis-primary-rgb: 242, 237, 234;
  --delogis-gray: #736b6b;
  --delogis-bdr-color: #ded6d3;
  --mp-blue: #976147;
  --mp-blue-dark: #7a4e39;
}
body.theme-delogis .mp-btn-primary,
body.theme-pack-delogis .mp-btn-primary {
  background: var(--delogis-base) !important;
  border-color: var(--delogis-base) !important;
}
body.theme-delogis .mp-topbar,
body.theme-delogis .mp-header,
body.theme-delogis .mp-footer { display: none !important; }
</style>
