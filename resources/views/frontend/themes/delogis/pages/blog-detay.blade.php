@extends(theme_layout())

@php
    /** blog-details.html → section.blog-details */
    $y = $yazi ?? $blog ?? [];
    $title = $y['baslik'] ?? $y['title'] ?? 'Yazı';
    $icerik = $y['icerik'] ?? $y['content'] ?? $y['ozet'] ?? '';
    $img = $y['image'] ?? $y['kapak'] ?? $y['resim'] ?? null;
    $author = $y['yazar'] ?? trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $rawDate = $y['tarih'] ?? $y['created_at'] ?? $y['yayin_tarihi'] ?? null;
    $day = '—';
    $mon = '';
    $dateLabel = '';
    $months = ['', 'Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
    if ($rawDate) {
        try {
            $dt = \Illuminate\Support\Carbon::parse($rawDate);
            $day = $dt->format('d');
            $mon = $months[(int) $dt->format('n')] ?? $dt->format('M');
            $dateLabel = $dt->format('d.m.Y');
        } catch (\Throwable) {
            $dateLabel = (string) $rawDate;
        }
    }
    $digerYazilar = collect($doktor['bloglar'] ?? [])
        ->filter(function ($b) use ($y) {
            if (! is_array($b)) {
                return false;
            }
            $slug = $b['slug'] ?? null;
            $cur = $y['slug'] ?? null;

            return $slug && $cur ? $slug !== $cur : true;
        })
        ->take(4)
        ->values();
@endphp

@section('baslik', $title.' | Blog')
@section('meta_aciklama', \Illuminate\Support\Str::limit(strip_tags((string) $icerik), 160))

@section('icerik')
@include('frontend.themes.delogis.partials.page-header', ['title' => $title, 'crumb' => 'Blog'])

<section class="blog-details">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="blog-details__left">
                    @if($img)
                        <div class="blog-details__img">
                            <img src="{{ $img }}" alt="{{ $title }}">
                            @if($day !== '—' || $mon !== '')
                                <div class="blog-details__date">
                                    <p>{{ $day }}<br>{{ $mon }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="blog-details__content">
                        <ul class="blog-details__meta list-unstyled">
                            @if($author)
                                <li>
                                    <span><i class="fas fa-user-circle"></i> {{ $author }}</span>
                                </li>
                            @endif
                            @if($dateLabel)
                                <li>
                                    <span><i class="fas fa-clock"></i> {{ $dateLabel }}</span>
                                </li>
                            @endif
                        </ul>
                        <h3 class="blog-details__title">{{ $title }}</h3>
                        <div class="blog-details__text-1 dg-prose">
                            {!! $icerik !!}
                        </div>
                        <div class="blog-details__bottom" style="margin-top:28px">
                            <p class="blog-details__tags">
                                <a href="{{ route('frontend.blog') }}">← Tüm yazılar</a>
                            </p>
                            @php $sosyal = array_filter($doktor['sosyal'] ?? [], fn ($u) => filled($u)); @endphp
                            @if(count($sosyal))
                                <div class="blog-details__social-list">
                                    @foreach ($sosyal as $key => $url)
                                        @php
                                            $icon = match (strtolower((string) $key)) {
                                                'twitter', 'x' => 'fab fa-twitter',
                                                'facebook' => 'fab fa-facebook',
                                                'instagram' => 'fab fa-instagram',
                                                'linkedin' => 'fab fa-linkedin-in',
                                                default => 'fas fa-link',
                                            };
                                        @endphp
                                        <a href="{{ $url }}" target="_blank" rel="noopener"><i class="{{ $icon }}"></i></a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="sidebar">
                    <div class="sidebar__single sidebar__category">
                        <h3 class="sidebar__title">Bağlantılar</h3>
                        <ul class="sidebar__category-list list-unstyled">
                            <li><a href="{{ route('frontend.blog') }}">Blog</a></li>
                            <li><a href="{{ route('frontend.hizmetler') }}">Hizmetler</a></li>
                            <li><a href="{{ route('frontend.randevu') }}">Randevu Al</a></li>
                            <li><a href="{{ route('frontend.iletisim') }}">İletişim</a></li>
                        </ul>
                    </div>
                    @if($digerYazilar->isNotEmpty())
                        <div class="sidebar__single sidebar__post">
                            <h3 class="sidebar__title">Son yazılar</h3>
                            <ul class="sidebar__post-list list-unstyled">
                                @foreach ($digerYazilar as $p)
                                    @php
                                        $pTitle = $p['baslik'] ?? $p['title'] ?? 'Yazı';
                                        $pSlug = $p['slug'] ?? \Illuminate\Support\Str::slug($pTitle);
                                        $pImg = $p['image'] ?? $p['kapak'] ?? $p['resim'] ?? null;
                                        $pHref = route('frontend.blog.detay', $pSlug);
                                    @endphp
                                    <li>
                                        @if($pImg)
                                            <div class="sidebar__post-image">
                                                <img src="{{ $pImg }}" alt="{{ $pTitle }}">
                                            </div>
                                        @endif
                                        <div class="sidebar__post-content">
                                            <h3>
                                                <a href="{{ $pHref }}">{{ \Illuminate\Support\Str::limit($pTitle, 60) }}</a>
                                            </h3>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
