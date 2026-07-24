@php
    $sosyal = $doktor['sosyal'] ?? [];
    $hasSosyal = !empty(array_filter($sosyal));
    $sosyalIkonlar = [
        'instagram' => 'fa-brands fa-instagram',
        'facebook'  => 'fa-brands fa-facebook-f',
        'twitter'   => 'fa-brands fa-x-twitter',
        'youtube'   => 'fa-brands fa-youtube',
        'linkedin'  => 'fa-brands fa-linkedin-in',
        'tiktok'    => 'fa-brands fa-tiktok',
        'pinterest' => 'fa-brands fa-pinterest-p',
        'whatsapp'  => 'fa-brands fa-whatsapp',
    ];
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
@endphp

<header class="main-header">
    <div class="header-sticky" id="main-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ route('frontend.anasayfa') }}">
                    @if(!empty($doktor['logo']))
                        <img src="{{ $doktor['logo'] }}" alt="{{ $doktorAd }}" style="max-height:44px;width:auto">
                    @else
                        <span style="font-family:var(--accent-font,'Marcellus',serif);color:#fff;font-size:1.25rem;line-height:1.2">
                            {{ $doktorAd }}
                        </span>
                    @endif
                </a>

                <div class="collapse navbar-collapse main-menu">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            @foreach ($nav as $item)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ nav_href($item) }}">{{ $item['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="header-btn d-inline-flex">
                        <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                    </div>
                </div>

                <div class="navbar-toggle"></div>
            </div>
        </nav>
        <div class="responsive-menu"></div>
    </div>
</header>
