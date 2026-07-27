@php
    $title = $title ?? 'Sayfa';
    $crumb = $crumb ?? $title;
@endphp
{{-- Arka plan görseli yok: estetik solid/gradient (orijinal #976147 / #1a1414) — yapı aynı --}}
<section class="page-header page-header--solid">
    <div class="page-header-bg page-header-bg--solid" aria-hidden="true"></div>
    <div class="container">
        <div class="page-header__inner">
            <h2>{{ $title }}</h2>
            <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.anasayfa') }}">Ana Sayfa</a></li>
                <li><span>/</span></li>
                <li>{{ $crumb }}</li>
            </ul>
        </div>
    </div>
</section>
