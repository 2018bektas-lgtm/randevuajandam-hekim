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
    $temaMeta = resolve_site_theme($doktor['tema_id'] ?? ($doktor['tema']['id'] ?? null));
    $theme = $doktor['tema_renk'] ?? ($temaMeta['renk'] ?? '#0d9488');
    $palette = theme_palette($theme);
    $temaCssId = $temaMeta['id'] ?? 'klasik';
    $googleFonts = $temaMeta['google_fonts'] ?? 'Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Inter:wght@400;500;600;700;800';
    $ogImage = $doktor['logo']
        ?? $doktor['profil_resmi']
        ?? ($doktor['slider'][0]['image'] ?? null);
    $canonical = url()->current();
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Physician',
        'name' => trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')),
        'description' => $pageDesc,
        'url' => url('/'),
        'telephone' => $doktor['telefon'] ?? null,
        'email' => $doktor['e_posta'] ?? null,
        'image' => $ogImage,
        'medicalSpecialty' => $doktor['uzmanlik'] ?? null,
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $doktor['adres'] ?? null,
            'addressLocality' => $doktor['ilce'] ?? null,
            'addressRegion' => $doktor['il'] ?? null,
            'addressCountry' => 'TR',
        ]),
    ];
    if (! empty($doktor['sosyal']) && is_array($doktor['sosyal'])) {
        $sameAs = array_values(array_filter($doktor['sosyal']));
        if ($sameAs) {
            $schema['sameAs'] = $sameAs;
        }
    }
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
<link rel="icon" href="{{ $doktor['favicon'] }}" type="{{ str_ends_with(strtolower($doktor['favicon']), '.svg') ? 'image/svg+xml' : (str_ends_with(strtolower($doktor['favicon']), '.ico') ? 'image/x-icon' : 'image/png') }}">
<link rel="shortcut icon" href="{{ $doktor['favicon'] }}">
<link rel="apple-touch-icon" href="{{ $doktor['favicon'] }}">
@else
<link rel="icon" href="{{ asset('favicon.ico') }}">
@endif

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family={{ $googleFonts }}&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<link rel="stylesheet" href="{{ asset('css/site.css') }}?v=2">
<link rel="stylesheet" href="{{ asset('css/themes/'.$temaCssId.'.css') }}?v=5">
<style>
:root {
  --brand-50: {{ $palette['50'] }};
  --brand-100: {{ $palette['100'] }};
  --brand-200: {{ $palette['200'] }};
  --brand-400: {{ $palette['400'] }};
  --brand-500: {{ $palette['500'] }};
  --brand-600: {{ $palette['600'] }};
  --brand-700: {{ $palette['700'] }};
  --brand-800: {{ $palette['800'] }};
  --brand-900: {{ $palette['900'] }};
  --font: "{{ $temaMeta['font_sans'] ?? 'Inter' }}", system-ui, sans-serif;
  --display: "{{ $temaMeta['font_display'] ?? 'Cormorant Garamond' }}", "Times New Roman", serif;
}
</style>
<script type="application/ld+json">{!! json_encode(array_filter($schema, fn ($v) => $v !== null && $v !== ''), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

@stack('head')
