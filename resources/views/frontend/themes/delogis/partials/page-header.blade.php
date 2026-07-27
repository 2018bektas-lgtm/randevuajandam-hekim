@php
    /**
     * İç sayfa hero (page-header) — Delogis orijinali:
     * assets/images/backgrounds/page-header-bg.jpg
     * Bizde: public/themes/delogis/images/backgrounds/page-header-bg.jpg
     * Dinamik: $bg veya $doktor['page_header_bg'] / site_options page_header_bg
     */
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $title = $title ?? 'Sayfa';
    $crumb = $crumb ?? $title;
    $bg = $bg
        ?? ($doktor['page_header_bg'] ?? null)
        ?? ($doktor['site_settings']['genel']['page_header_bg'] ?? null)
        ?? ($dg.'/images/backgrounds/page-header-bg.jpg');
@endphp
<section class="page-header">
    <div class="page-header-bg" style="background-image: url({{ $bg }})"></div>
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
