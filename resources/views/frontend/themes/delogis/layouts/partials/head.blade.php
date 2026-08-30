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
    try {
        $aktifPalet = app(\App\Services\SiteBuilderService::class)->aktifPalet();
    } catch (\Throwable $e) {
        $aktifPalet = [
            'primary' => '#1a1414',
            'accent' => $temaMeta['renk'] ?? '#976147',
            'bg' => '#f2edea',
            'text' => '#1a1414',
            'text_light' => '#FFFFFF',
            'black' => '#1a1414',
            'gray' => '#736b6b',
            'bdr' => '#ded6d3',
        ];
    }
    $theme = $aktifPalet['accent'] ?? ($temaMeta['renk'] ?? '#976147');
    $palette = function_exists('theme_palette') ? theme_palette($theme) : ['500' => $theme, '600' => '#7a4e39'];
    $baseRgb = function_exists('hex_to_rgb') ? hex_to_rgb($theme) : [151, 97, 71];
    $blackHex = $aktifPalet['black'] ?? ($aktifPalet['primary'] ?? '#1a1414');
    $blackRgb = function_exists('hex_to_rgb') ? hex_to_rgb($blackHex) : [26, 20, 20];
    $bgHex = $aktifPalet['bg'] ?? '#f2edea';
    $bgRgb = function_exists('hex_to_rgb') ? hex_to_rgb($bgHex) : [242, 237, 234];
    $ogImage = $doktor['logo'] ?? $doktor['profil_resmi'] ?? ($doktor['slider'][0]['image'] ?? null);
    $canonical = url()->current();
@endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('frontend.layouts.partials.tracking')
@include('frontend.layouts.partials.recaptcha')
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDesc }}">
@php
    $pageKwYield = trim($decodeEntities($__env->yieldContent('meta_anahtar')));
    $keywordsOut = $pageKwYield !== '' ? $pageKwYield : $seoKw;
@endphp
@if($keywordsOut !== '')
<meta name="keywords" content="{{ $keywordsOut }}">
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
{{-- Footer tasarim katmani: config/footer_tasarimlari.php --}}
<link rel="stylesheet" href="{{ rtrim((string) request()->getBasePath(), '/') }}/css/footer-tasarim.css?v=1">
<style>
:root {
  --brand-500: {{ $theme }};
  --brand-600: {{ $palette['700'] ?? '#7a4e39' }};
  --primary-color: {{ $aktifPalet['primary'] ?? $blackHex }};
  --accent-color: {{ $theme }};
  --secondary-color: {{ $bgHex }};
  --text-color: {{ $aktifPalet['text'] ?? $blackHex }};
  --text-light: {{ $aktifPalet['text_light'] ?? '#FFFFFF' }};
  --delogis-base: {{ $theme }};
  --delogis-base-rgb: {{ implode(', ', $baseRgb) }};
  --delogis-black: {{ $blackHex }};
  --delogis-black-rgb: {{ implode(', ', $blackRgb) }};
  --delogis-primary: {{ $bgHex }};
  --delogis-primary-rgb: {{ implode(', ', $bgRgb) }};
  --delogis-gray: {{ $aktifPalet['gray'] ?? '#736b6b' }};
  --delogis-bdr-color: {{ $aktifPalet['bdr'] ?? '#ded6d3' }};
  --delogis-extra: {{ $aktifPalet['extra'] ?? $bgHex }};
}
</style>
@stack('head')
