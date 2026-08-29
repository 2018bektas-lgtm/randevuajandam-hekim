@extends(theme_layout())

@section('baslik', 'Blog | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', 'Yazılar ve güncellemeler')

@section('icerik')
@php
    /**
     * Delogis blog-6.html
     * section.blog-one + .blog-four__single kartlar
     */
    $bloglar = collect($doktor['bloglar'] ?? $yazilar ?? [])
        ->filter(fn ($b) => is_array($b) && filled($b['baslik'] ?? $b['title'] ?? null))
        ->values();
    $months = ['', 'Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'Blog', 'crumb' => 'Blog'])

{{-- blog-6.html --}}
<section class="blog-one">
    <div class="container">
        @if($bloglar->isEmpty())
            <div class="text-center" style="padding:48px 0">
                <p>Henüz blog yazısı yok.</p>
            </div>
        @else
            <div class="row gutter-y-30">
                @foreach ($bloglar as $idx => $b)
                    @php
                        $bTitle = $b['baslik'] ?? $b['title'] ?? 'Yazı';
                        $bSlug = $b['slug'] ?? \Illuminate\Support\Str::slug($bTitle);
                        $bImg = $b['image'] ?? $b['kapak'] ?? $b['resim'] ?? null;
                        if (filled($bImg) && function_exists('media_url') && ! preg_match('#^(https?:)?//#i', (string) $bImg)) {
                            $bImg = media_url((string) $bImg) ?: $bImg;
                        }
                        $href = route('frontend.blog.detay', $bSlug);
                        $day = '—';
                        $mon = '';
                        $rawDate = $b['tarih'] ?? $b['created_at'] ?? $b['yayin_tarihi'] ?? null;
                        if ($rawDate) {
                            try {
                                $dt = \Illuminate\Support\Carbon::parse($rawDate);
                                $day = $dt->format('d');
                                $mon = $months[(int) $dt->format('n')] ?? $dt->format('M');
                            } catch (\Throwable) {
                                $day = \Illuminate\Support\Str::limit((string) $rawDate, 2, '');
                            }
                        }
                    @endphp
                    {{-- Blog Start (blog-6) --}}
                    <div class="col-lg-4 col-md-6 wow fadeInUp dg-card-col" data-wow-delay="{{ ($idx % 3) * 100 }}ms">
                        <div class="blog-four__single dg-card">
                            <div class="blog-four__single__image dg-card__media">
                                @if($bImg)
                                    <img src="{{ $bImg }}" alt="{{ $bTitle }}" loading="lazy" decoding="async">
                                @else
                                    <div class="dg-card__img--empty" aria-hidden="true"></div>
                                @endif
                                <a href="{{ $href }}" class="blog-four__single__image__link" aria-label="{{ $bTitle }}"></a>
                            </div>
                            <div class="blog-four__single__content dg-card__body">
                                <div class="blog-four__single__date"><span>{{ $day }}</span>{{ $mon }}</div>
                                <h3 class="blog-four__single__title">
                                    <a href="{{ $href }}">{{ $bTitle }}</a>
                                </h3>
                            </div>
                            <a class="blog-four__single__rm" href="{{ $href }}">
                                Devamını oku<span class="delogis-icons-two-right-arrow"></span>
                            </a>
                        </div>
                    </div>
                    {{-- Blog End --}}
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
