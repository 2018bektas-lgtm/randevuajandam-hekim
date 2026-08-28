@php extract(delogis_modul_ctx($ayar ?? [], $doktor ?? [])); @endphp
@php
    $limit = max(1, (int) ($ayar['blog_limiti'] ?? 3));
    $bloglar = collect($doktor['bloglar'] ?? [])
        ->filter(fn ($b) => is_array($b) && filled($b['baslik'] ?? $b['title'] ?? null))
        ->take($limit)
        ->values();
    $kucuk = $ayar['kucuk_baslik'] ?? 'Blog';
    $baslik = $ayar['ana_baslik'] ?? 'Son yazılarım';
    $monthsHome = ['', 'Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
@endphp
@if($bloglar->isNotEmpty())
<section class="blog-one">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">{{ decode_text($kucuk) }}</span>
            <h2 class="section-title__title">{!! $titleHtml($baslik) !!}</h2>
        </div>
        <div class="row gutter-y-30">
            @foreach ($bloglar as $idx => $b)
                @php
                    $bTitle = $b['baslik'] ?? $b['title'] ?? 'Yazı';
                    $bSlug = $b['slug'] ?? \Illuminate\Support\Str::slug($bTitle);
                    $bImg = $media($b['image'] ?? $b['kapak'] ?? $b['resim'] ?? null);
                    $href = route('frontend.blog.detay', $bSlug);
                    $day = '—';
                    $mon = '';
                    $rawDate = $b['tarih'] ?? $b['created_at'] ?? $b['yayin_tarihi'] ?? null;
                    if ($rawDate) {
                        try {
                            $dt = \Illuminate\Support\Carbon::parse($rawDate);
                            $day = $dt->format('d');
                            $mon = $monthsHome[(int) $dt->format('n')] ?? $dt->format('M');
                        } catch (\Throwable) {
                        }
                    }
                @endphp
                <div class="col-lg-4 col-md-6 wow fadeInUp dg-card-col" data-wow-delay="{{ ($idx % 3) * 100 }}ms">
                    <div class="blog-four__single dg-card">
                        <div class="blog-four__single__image dg-card__media">
                            @if($bImg)
                                <img src="{{ $bImg }}" alt="{{ $bTitle }}">
                            @else
                                <div class="dg-card__img--empty" aria-hidden="true"></div>
                            @endif
                            <a href="{{ $href }}" class="blog-four__single__image__link" aria-label="{{ $bTitle }}"></a>
                        </div>
                        <div class="blog-four__single__content dg-card__body">
                            <div class="blog-four__single__date"><span>{{ $day }}</span>{{ $mon }}</div>
                            <h3 class="blog-four__single__title"><a href="{{ $href }}">{{ decode_text($bTitle) }}</a></h3>
                        </div>
                        <a class="blog-four__single__rm" href="{{ $href }}">
                            Devamını oku<span class="delogis-icons-two-right-arrow"></span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center" style="margin-top:24px">
            <a href="{{ route('frontend.blog') }}" class="thm-btn">Tüm yazılar</a>
        </div>
    </div>
</section>
@endif
