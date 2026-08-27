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
    @include('frontend.layouts.partials.tracking-body')

    <div class="preloader">
        <div class="loading-container">
            <div class="loading"></div>
            <div id="loading-icon">
                <img src="{{ asset('vendor/hipno/images/loader.svg') }}" alt="" onerror="this.style.display='none'">
            </div>
        </div>
    </div>

    @include('frontend.themes.tema-1.layouts.partials.header', ['doktor' => $doktor ?? [], 'nav' => $nav])

    <main>
        @yield('icerik')
    </main>

    @include('frontend.themes.tema-1.layouts.partials.footer', ['doktor' => $doktor ?? [], 'nav' => $nav])
    @include('frontend.themes.tema-1.layouts.partials.script', ['doktor' => $doktor ?? []])
</body>
</html>
