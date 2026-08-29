@extends(theme_layout())

@php
    /**
     * Delogis blog-details.html
     * section.blog-details + sidebar (Latest Posts + Categories)
     */
    $y = $yazi ?? $blog ?? [];
    $title = decode_text($y['baslik'] ?? $y['title'] ?? 'Yazı');
    $rawIcerik = $y['icerik_html'] ?? $y['icerik'] ?? $y['content'] ?? $y['ozet'] ?? '';
    $icerik = is_array($rawIcerik)
        ? implode('', array_map(fn ($p) => '<p>'.e(decode_text($p)).'</p>', $rawIcerik))
        : safe_html($rawIcerik);   // {!! $icerik !!} ile ham basiliyor
    $img = $y['image'] ?? $y['kapak'] ?? $y['resim'] ?? null;
    $author = decode_text($y['yazar'] ?? trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')));
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

    $allPosts = collect($doktor['bloglar'] ?? [])
        ->filter(fn ($b) => is_array($b) && filled($b['baslik'] ?? $b['title'] ?? null))
        ->values();
    $curSlug = $y['slug'] ?? null;
    $curIdx = $allPosts->search(fn ($b) => ($b['slug'] ?? null) === $curSlug);
    $prevPost = ($curIdx !== false && $curIdx > 0) ? $allPosts[$curIdx - 1] : null;
    $nextPost = ($curIdx !== false && $curIdx < $allPosts->count() - 1) ? $allPosts[$curIdx + 1] : null;
    $digerYazilar = $allPosts
        ->filter(fn ($b) => ($b['slug'] ?? null) !== $curSlug)
        ->take(3)
        ->values();
@endphp

@section('baslik', $title.' | Blog')
@section('meta_aciklama', \Illuminate\Support\Str::limit(strip_tags((string) $icerik), 160))

@section('icerik')
@include('frontend.themes.delogis.partials.page-header', ['title' => $title, 'crumb' => 'Blog'])

{{-- blog-details.html --}}
<section class="blog-details">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="blog-details__left">
                    @if($img)
                        <div class="blog-details__img">
                            <img src="{{ $img }}" alt="{{ $title }}" loading="lazy" decoding="async">
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
                                    <span><i class="fas fa-user-circle"></i>{{ $author }}</span>
                                </li>
                            @endif
                            @if($dateLabel)
                                <li>
                                    <span><i class="fas fa-clock"></i>{{ $dateLabel }}</span>
                                </li>
                            @endif
                        </ul>

                        <h3 class="blog-details__title">{{ $title }}</h3>

                        <div class="blog-details__text-1 dg-prose">
                            {!! $icerik !!}
                        </div>

                        <div class="blog-details__bottom">
                            <p class="blog-details__tags">
                                <span>Etiketler</span>
                                <a href="{{ route('frontend.blog') }}">Blog</a>
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

                        @if($prevPost || $nextPost)
                            <div class="blog-details__pagenation">
                                @if($prevPost)
                                    @php
                                        $pTitle = $prevPost['baslik'] ?? $prevPost['title'] ?? 'Yazı';
                                        $pSlug = $prevPost['slug'] ?? \Illuminate\Support\Str::slug($pTitle);
                                        $pImg = $prevPost['image'] ?? $prevPost['kapak'] ?? $prevPost['resim'] ?? null;
                                        $pDate = $prevPost['tarih'] ?? $prevPost['created_at'] ?? null;
                                        $pDateStr = '';
                                        if ($pDate) {
                                            try { $pDateStr = \Illuminate\Support\Carbon::parse($pDate)->format('d M, Y'); } catch (\Throwable) {}
                                        }
                                    @endphp
                                    <div class="blog-details__pagenation-left">
                                        @if($pImg)
                                            <div class="blog-details__pagenation-left-img">
                                                <img src="{{ $pImg }}" alt="{{ $pTitle }}" loading="lazy" decoding="async">
                                            </div>
                                        @endif
                                        <div class="blog-details__pagenation-left-content">
                                            @if($pDateStr)
                                                <p class="blog-details__pagenation-left-date"><i class="fas fa-clock"></i> {{ $pDateStr }}</p>
                                            @endif
                                            <h4 class="blog-details__pagenation-left-title">
                                                <a href="{{ route('frontend.blog.detay', $pSlug) }}">{{ \Illuminate\Support\Str::limit($pTitle, 48) }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                @endif
                                @if($nextPost)
                                    @php
                                        $nTitle = $nextPost['baslik'] ?? $nextPost['title'] ?? 'Yazı';
                                        $nSlug = $nextPost['slug'] ?? \Illuminate\Support\Str::slug($nTitle);
                                        $nImg = $nextPost['image'] ?? $nextPost['kapak'] ?? $nextPost['resim'] ?? null;
                                        $nDate = $nextPost['tarih'] ?? $nextPost['created_at'] ?? null;
                                        $nDateStr = '';
                                        if ($nDate) {
                                            try { $nDateStr = \Illuminate\Support\Carbon::parse($nDate)->format('d M, Y'); } catch (\Throwable) {}
                                        }
                                    @endphp
                                    <div class="blog-details__pagenation-right">
                                        <div class="blog-details__pagenation-right-content">
                                            @if($nDateStr)
                                                <p class="blog-details__pagenation-right-date"><i class="fas fa-clock"></i> {{ $nDateStr }}</p>
                                            @endif
                                            <h4 class="blog-details__pagenation-right-title">
                                                <a href="{{ route('frontend.blog.detay', $nSlug) }}">{{ \Illuminate\Support\Str::limit($nTitle, 48) }}</a>
                                            </h4>
                                        </div>
                                        @if($nImg)
                                            <div class="blog-details__pagenation-right-img">
                                                <img src="{{ $nImg }}" alt="{{ $nTitle }}" loading="lazy" decoding="async">
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="sidebar">
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
                                                <img src="{{ $pImg }}" alt="{{ $pTitle }}" loading="lazy" decoding="async">
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

                    <div class="sidebar__single sidebar__category">
                        <h3 class="sidebar__title">Bağlantılar</h3>
                        <ul class="sidebar__category-list list-unstyled">
                            <li><a href="{{ route('frontend.blog') }}">Blog</a></li>
                            <li><a href="{{ route('frontend.hizmetler') }}">Hizmetler</a></li>
                            <li><a href="{{ route('frontend.randevu') }}">Randevu Al</a></li>
                            <li><a href="{{ route('frontend.iletisim') }}">İletişim</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
