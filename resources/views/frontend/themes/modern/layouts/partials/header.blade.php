@php
    if (! isset($nav) || ! is_array($nav)) {
        $nav = function_exists('site_nav') ? site_nav(isset($doktor) && is_array($doktor) ? $doktor : null) : [];
    }
    $sosyal = is_array($doktor['sosyal'] ?? null) ? $doktor['sosyal'] : [];
@endphp

{{-- MediPlus topbar --}}
<div class="mp-topbar">
    <div class="mp-topbar-inner">
        <div class="mp-topbar-left">
            @if(!empty($doktor['telefon']))
                <a href="tel:{{ $doktor['telefon_raw'] ?? '' }}">
                    <span aria-hidden="true">☎</span> {{ $doktor['telefon'] }}
                </a>
            @endif
            @if(!empty($doktor['e_posta']))
                <a href="mailto:{{ $doktor['e_posta'] }}">
                    <span aria-hidden="true">✉</span> {{ $doktor['e_posta'] }}
                </a>
            @endif
            @if(!empty($doktor['adres']))
                <span style="opacity:.8;display:none" class="mp-topbar-addr">📍 {{ \Illuminate\Support\Str::limit($doktor['adres'], 42) }}</span>
            @endif
        </div>
        <div class="mp-topbar-social">
            @foreach (['instagram' => 'IG', 'facebook' => 'FB', 'twitter' => 'X', 'linkedin' => 'IN', 'youtube' => 'YT'] as $key => $label)
                @if(!empty($sosyal[$key]))
                    <a href="{{ $sosyal[$key] }}" target="_blank" rel="noopener" title="{{ ucfirst($key) }}">{{ $label }}</a>
                @endif
            @endforeach
            <a href="{{ route('frontend.randevu') }}" class="mp-btn mp-btn-primary" style="padding:7px 14px;font-size:.75rem;margin-left:6px">Randevu Al</a>
        </div>
    </div>
</div>

<header class="mp-header" id="site-header">
    <div class="mp-header-inner">
        <a href="{{ route('frontend.anasayfa') }}" class="mp-brand">
            @if(!empty($doktor['logo']))
                <img src="{{ $doktor['logo'] }}" alt="{{ $doktor['ad_soyad'] ?? 'Klinik' }}">
            @else
                <span class="mp-brand-mark">{{ mb_strtoupper(mb_substr($doktor['ad_soyad'] ?? 'H', 0, 1)) }}</span>
            @endif
            <span>
                <strong>{{ trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')) }}</strong>
                <small>{{ $doktor['uzmanlik'] ?? ($doktor['klinik_adi'] ?? 'Medikal Klinik') }}</small>
            </span>
        </a>

        <nav class="mp-nav" aria-label="Ana menü">
            @foreach ($nav as $item)
                @php $active = !empty($item['match']) && request()->routeIs($item['match']); @endphp
                <a href="{{ $item['href'] }}" class="{{ $active ? 'is-active' : '' }}"
                   @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="mp-header-actions">
            <a href="{{ route('frontend.randevu') }}" class="mp-btn mp-btn-primary" style="display:none" id="mp-header-cta-desktop">Randevu Al</a>
            <button type="button" class="mp-menu-btn" id="mobile-menu-btn" aria-label="Menü" aria-expanded="false">☰</button>
        </div>
<style>
@media (min-width:992px){ #mp-header-cta-desktop{display:inline-flex!important} .mp-topbar .mp-btn{display:none} }
@media (max-width:991px){ .mp-topbar-social .mp-btn{display:inline-flex} }
</style>
    </div>
    <div class="mp-mobile" id="mobile-menu" hidden>
        @foreach ($nav as $item)
            <a href="{{ $item['href'] }}" @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
        @endforeach
        <a href="{{ route('frontend.randevu') }}" class="mp-btn mp-btn-primary" style="margin-top:10px;text-align:center">Randevu Al</a>
    </div>
</header>
<script>
(function () {
    var btn = document.getElementById('mobile-menu-btn');
    var menu = document.getElementById('mobile-menu');
    if (!btn || !menu) return;
    btn.addEventListener('click', function () {
        var open = menu.classList.toggle('is-open');
        menu.hidden = !open;
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
})();
</script>
