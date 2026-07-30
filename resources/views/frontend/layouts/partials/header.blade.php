@php $nav = site_nav($doktor ?? null); @endphp

@php
    $sosyalTop = array_filter($doktor['sosyal'] ?? [], fn ($u) => filled($u));
@endphp
<div class="topbar">
    <div class="container">
        <div class="topbar-left">
            @if(!empty($doktor['adres']))
                <span>📍 {{ \Illuminate\Support\Str::limit($doktor['adres'], 48) }}</span>
            @elseif(!empty($doktor['il']))
                <span>📍 {{ trim(($doktor['ilce'] ?? '').' / '.($doktor['il'] ?? ''), ' /') }}</span>
            @endif
        </div>
        <div class="topbar-right">
            @if(!empty($doktor['telefon']))
                <a href="tel:{{ $doktor['telefon_raw'] ?? '' }}">☎ {{ $doktor['telefon'] }}</a>
            @endif
            @if(!empty($doktor['e_posta']))
                <a href="mailto:{{ $doktor['e_posta'] }}">✉ {{ $doktor['e_posta'] }}</a>
            @endif
            @if(($doktor['whatsapp_goster'] ?? true) && !empty($doktor['whatsapp']))
                <a href="https://wa.me/{{ $doktor['whatsapp'] }}" target="_blank" rel="noopener">WhatsApp</a>
            @endif
            @foreach (array_slice($sosyalTop, 0, 4, true) as $ad => $url)
                <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $ad }}">{{ strtoupper(mb_substr((string)$ad, 0, 2)) }}</a>
            @endforeach
        </div>
    </div>
</div>

<header class="site-header" id="site-header">
    <div class="header-inner">
        <a href="{{ route('frontend.anasayfa') }}" class="brand {{ !empty($doktor['logo']) ? 'has-logo' : '' }}">
            @if(!empty($doktor['logo']))
                <img src="{{ $doktor['logo'] }}" alt="{{ trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')) }}" class="brand-logo">
            @else
                <span class="brand-mark">{{ mb_substr($doktor['ad_soyad'] ?? 'H', 0, 1) }}</span>
            @endif
            <span class="brand-text">
                <strong>{{ $doktor['unvan'] ?? '' }} {{ $doktor['ad_soyad'] ?? 'Hekim' }}</strong>
                <span>{{ $doktor['uzmanlik'] ?? 'Klinik' }}</span>
            </span>
        </a>

        <nav class="nav-desktop" aria-label="Ana menü">
            @foreach ($nav as $item)
                @php
                    $children = $item['children'] ?? [];
                    $active = !empty($item['match']) && request()->routeIs($item['match']);
                    if (! $active && $children) {
                        foreach ($children as $ch) {
                            if (!empty($ch['match']) && request()->routeIs($ch['match'])) { $active = true; break; }
                        }
                    }
                @endphp
                @if(!empty($children))
                    <div class="nav-item has-dropdown {{ $active ? 'active' : '' }}">
                        <a href="{{ $item['href'] }}"
                           class="{{ $active ? 'active' : '' }}"
                           @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>
                            {{ $item['label'] }}
                            <span class="nav-caret" aria-hidden="true">▾</span>
                        </a>
                        <div class="nav-dropdown" role="menu">
                            @foreach($children as $child)
                                @php $cActive = !empty($child['match']) && request()->routeIs($child['match']); @endphp
                                <a href="{{ $child['href'] }}"
                                   class="{{ $cActive ? 'active' : '' }}"
                                   role="menuitem"
                                   @if(!empty($child['external'])) target="_blank" rel="noopener" @endif>
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['href'] }}"
                       class="{{ $active ? 'active' : '' }}"
                       @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="header-actions">
            @if($doktor['hekim_girisi_goster'] ?? true)
                <a href="{{ route('panel.giris') }}" class="btn btn-dark-outline btn-sm hidden sm:inline-flex">Hekim Girişi</a>
            @endif
            <a href="{{ route('frontend.randevu') }}" class="btn btn-primary btn-sm">Randevu Al</a>
            <button type="button" class="menu-toggle" id="mobile-menu-btn" aria-label="Menüyü aç">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="mobile-nav" id="mobile-menu">
        @foreach ($nav as $item)
            @php
                $children = $item['children'] ?? [];
                $active = !empty($item['match']) && request()->routeIs($item['match']);
            @endphp
            <a href="{{ $item['href'] }}"
               class="{{ $active ? 'active' : '' }}"
               @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>
                {{ $item['label'] }}
            </a>
            @foreach($children as $child)
                @php $cActive = !empty($child['match']) && request()->routeIs($child['match']); @endphp
                <a href="{{ $child['href'] }}"
                   class="mobile-nav-child {{ $cActive ? 'active' : '' }}"
                   @if(!empty($child['external'])) target="_blank" rel="noopener" @endif>
                    {{ $child['label'] }}
                </a>
            @endforeach
        @endforeach
        <a href="{{ route('frontend.randevu') }}" class="btn btn-primary" style="margin:.5rem 0 0;width:100%">Online Randevu</a>
    </div>
</header>
