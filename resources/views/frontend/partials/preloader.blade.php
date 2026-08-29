{{--
    Ortak preloader (tema-1 / tema-2 / tema-3).
    Panelden yüklenen site logosu varsa loader görseli olarak kullanılır.
    Logo yoksa (veya görsel yüklenemezse) hekim adının baş harfleriyle
    monogram loader gösterilir — sabit hipno loader.svg kullanılmaz.
--}}
@php
    $plDoktor  = is_array($doktor ?? null) ? $doktor : [];
    $plLogo    = $plDoktor['logo'] ?? null;
    $plUnvan   = trim((string) ($plDoktor['unvan'] ?? ''));
    $plAdSoyad = trim((string) ($plDoktor['ad_soyad'] ?? '')) ?: 'Hekim';
    $plTamAd   = trim($plUnvan.' '.$plAdSoyad);
    $plHarfler = collect(preg_split('/\s+/u', $plAdSoyad) ?: [])
        ->filter()
        ->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1), 'UTF-8'))
        ->implode('');
@endphp

<div class="preloader{{ filled($plLogo) ? '' : ' pl-no-logo' }}">
    <div class="loading-container">
        <div class="loading"></div>
        <div id="loading-icon">
            @if(filled($plLogo))
                <img src="{{ $plLogo }}" alt="{{ $plTamAd }}" class="pl-logo"
                     onerror="this.remove();document.querySelector('.preloader')?.classList.add('pl-no-logo');">
            @endif
            <span class="pl-monogram" aria-hidden="true">{{ $plHarfler !== '' ? $plHarfler : 'R' }}</span>
        </div>
    </div>
</div>

<style>
/* Loader görseli: panelden yüklenen logo (Site Ayarları → Logo) */
#loading-icon .pl-logo{
    display:block;
    max-width:66px;
    max-height:66px;
    width:auto;
    height:auto;
    object-fit:contain;
}
/* Logo yokken: monogram tasarımı */
#loading-icon .pl-monogram{display:none}
.preloader.pl-no-logo #loading-icon .pl-monogram{
    display:block;
    font-family:var(--display,'Marcellus',serif);
    font-size:1.75rem;
    line-height:1;
    letter-spacing:.1em;
    text-indent:.1em;
    color:var(--white-color,#fff);
    white-space:nowrap;
}
.preloader.pl-no-logo .loading{
    border-color:transparent var(--accent-color,#9B9A84) transparent var(--accent-color,#9B9A84);
}
</style>
