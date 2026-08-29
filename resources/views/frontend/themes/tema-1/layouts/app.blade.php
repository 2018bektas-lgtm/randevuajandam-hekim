<!DOCTYPE html>
<html lang="tr">
<head>
    @include('frontend.themes.tema-1.layouts.partials.head')
</head>
@php
    $bodyTema = current_theme_id($doktor ?? null);
    $nav = site_nav(is_array($doktor ?? null) ? $doktor : null);
@endphp
<body class="theme-{{ $bodyTema }} layout-{{ theme_pack_id($bodyTema) }}">
    @include('frontend.partials.erisilebilirlik')

    @include('frontend.layouts.partials.tracking-body')

    @include('frontend.partials.preloader', ['doktor' => $doktor ?? []])

    @include('frontend.themes.tema-1.layouts.partials.header', ['doktor' => $doktor ?? [], 'nav' => $nav])

    <main id="ana-icerik" tabindex="-1">
        @yield('icerik')
    </main>

    @include('frontend.themes.tema-1.layouts.partials.footer', ['doktor' => $doktor ?? [], 'nav' => $nav])
    @include('frontend.themes.tema-1.layouts.partials.script', ['doktor' => $doktor ?? []])
</body>
</html>
