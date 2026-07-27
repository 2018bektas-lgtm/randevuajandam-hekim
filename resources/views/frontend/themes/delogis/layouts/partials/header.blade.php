@php
    if (! isset($nav) || ! is_array($nav)) {
        $nav = function_exists('site_nav') ? site_nav(isset($doktor) && is_array($doktor) ? $doktor : null) : [];
    }
    // Menüde "Randevu Al" yok — sağda sabit buton var
    $nav = collect($nav)
        ->filter(function ($item) {
            $label = mb_strtolower(trim((string) ($item['label'] ?? '')));
            $href = (string) ($item['href'] ?? '');

            return ! in_array($label, ['randevu al', 'randevu'], true)
                && ! str_contains($href, '/randevu');
        })
        ->values()
        ->all();
    $logo = $doktor['logo'] ?? null;
    $adSoyad = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
@endphp
<header class="main-header-three">
    <nav class="main-menu main-menu-three">
        <div class="main-menu-three__wrapper">
            <div class="container">
                <div class="main-menu-three__wrapper-inner dg-header-bar">
                    <div class="main-menu-three__left">
                        <div class="main-menu-three__logo">
                            <a href="{{ route('frontend.anasayfa') }}">
                                @if($logo)
                                    <img src="{{ $logo }}" alt="{{ $adSoyad }}" class="dg-header-logo-img">
                                @else
                                    <span class="dg-header-logo-text">{{ $adSoyad }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="main-menu-three__main-menu-box">
                            <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                            <ul class="main-menu__list">
                                @foreach ($nav as $item)
                                    @php $active = ! empty($item['match']) && request()->routeIs($item['match']); @endphp
                                    <li class="{{ $active ? 'current' : '' }}">
                                        <a href="{{ $item['href'] }}"
                                           @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="main-menu-three__right">
                        <div class="dg-header-cta">
                            <a href="{{ route('frontend.randevu') }}" class="dg-header-cta__btn">
                                <span class="dg-header-cta__icon" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <path d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                </span>
                                <span class="dg-header-cta__label">Randevu Al</span>
                                <span class="dg-header-cta__arrow" aria-hidden="true">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="stricky-header stricked-menu main-menu main-menu-three">
    <div class="sticky-header__content"></div>
</div>
