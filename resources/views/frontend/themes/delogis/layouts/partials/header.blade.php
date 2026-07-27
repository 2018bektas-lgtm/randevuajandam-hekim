@php
    if (! isset($nav) || ! is_array($nav)) {
        $nav = function_exists('site_nav') ? site_nav(isset($doktor) && is_array($doktor) ? $doktor : null) : [];
    }
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $logo = $doktor['logo'] ?? null;
    $adSoyad = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
@endphp
<header class="main-header-three">
    <nav class="main-menu main-menu-three">
        <div class="main-menu-three__wrapper">
            <div class="main-menu-three__wrapper-inner">
                <div class="main-menu-three__left">
                    <div class="main-menu-three__logo">
                        <a href="{{ route('frontend.anasayfa') }}">
                            @if($logo)
                                <img src="{{ $logo }}" alt="{{ $adSoyad }}" style="max-height:56px;width:auto">
                            @else
                                <span style="font-family:Castoro,serif;font-size:1.35rem;font-weight:600;color:var(--delogis-black,#293B46)">{{ $adSoyad }}</span>
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
                            <li>
                                <a href="{{ route('frontend.randevu') }}">Randevu Al</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="main-menu-three__right">
                    <div class="main-menu-three__call">
                        <div class="main-menu-three__call-content">
                            <a href="{{ route('frontend.randevu') }}" class="thm-btn main-menu-three__randevu-btn">Randevu Al</a>
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
