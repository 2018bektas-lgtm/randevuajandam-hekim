@php
    $decodeEntities = static function ($value): string {
        $decoded = (string) $value;
        for ($i = 0; $i < 3; $i++) {
            $next = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if ($next === $decoded) {
                break;
            }
            $decoded = $next;
        }

        return $decoded;
    };
    $seo = $doktor['seo'] ?? [];
    $seoTitle = trim($decodeEntities($seo['meta_baslik'] ?? ''));
    $seoDesc = trim($decodeEntities($seo['meta_aciklama'] ?? ''));
    $seoKw = trim($decodeEntities($seo['meta_anahtar'] ?? ''));
    $defaultTitle = trim($decodeEntities($doktor['unvan'] ?? '').' '.$decodeEntities($doktor['ad_soyad'] ?? 'Hekim').' | '.$decodeEntities($doktor['uzmanlik'] ?? 'Klinik'));
    if (! empty($doktor['site_baslik_ek'])) {
        $defaultTitle .= ' '.$decodeEntities($doktor['site_baslik_ek']);
    }
    $pageTitle = trim($decodeEntities($__env->yieldContent('baslik'))) ?: ($seoTitle !== '' ? $seoTitle : $defaultTitle);
    $pageDesc = trim($decodeEntities($__env->yieldContent('meta_aciklama'))) ?: ($seoDesc !== '' ? $seoDesc : $decodeEntities($doktor['kisa_bio'] ?? ''));
    $temaMeta = resolve_site_theme($doktor['tema_id'] ?? ($doktor['tema']['id'] ?? 'delogis'));
    // index3 orijinal vurgu: #976147 (delogis.css --delogis-base)
    $theme = '#976147';
    $palette = function_exists('theme_palette') ? theme_palette($theme) : ['500' => $theme, '600' => '#7a4e39'];
    $ogImage = $doktor['logo'] ?? $doktor['profil_resmi'] ?? ($doktor['slider'][0]['image'] ?? null);
    $canonical = url()->current();
    $r = 151; $g = 97; $b = 71;
@endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('frontend.layouts.partials.tracking')
@include('frontend.layouts.partials.recaptcha')
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDesc }}">
@if($seoKw !== '')
<meta name="keywords" content="{{ $seoKw }}">
@endif
<meta name="theme-color" content="{{ $theme }}">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDesc }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="tr_TR">
<meta property="og:url" content="{{ $canonical }}">
@if(!empty($ogImage))
<meta property="og:image" content="{{ $ogImage }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="{{ $ogImage }}">
@else
<meta name="twitter:card" content="summary">
@endif
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDesc }}">
@if(!empty($doktor['favicon']))
<link rel="icon" href="{{ $doktor['favicon'] }}">
@else
<link rel="icon" href="{{ asset('favicon.ico') }}">
@endif

@include('frontend.themes.delogis.layouts.partials.assets-css')
<link rel="stylesheet" href="{{ rtrim((string) request()->getBasePath(), '/') }}/css/themes/delogis.css?v=19">
<style>
/* Orijinal delogis.css ile aynı — panel tema_renk ile bozulmasın */
:root {
  --brand-500: #976147;
  --brand-600: #7a4e39;
  --delogis-base: #976147;
  --delogis-base-rgb: 151, 97, 71;
  --delogis-black: #1a1414;
  --delogis-primary: #f2edea;
}
</style>
@stack('head')
