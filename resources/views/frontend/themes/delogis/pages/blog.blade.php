@extends(theme_layout())

@section('baslik', 'Blog | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', 'Yazılar ve güncellemeler')

@section('icerik')
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $bloglar = collect($doktor['bloglar'] ?? $yazilar ?? [])->values();
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'Blog', 'crumb' => 'Blog'])

<section class="blog-page">
    <div class="container">
        @if($bloglar->isEmpty())
            <div class="text-center" style="padding:48px 0">
                <p>Henüz blog yazısı yok.</p>
            </div>
        @else
            <div class="row">
                @foreach ($bloglar as $b)
                    @php
                        $bTitle = $b['baslik'] ?? $b['title'] ?? 'Yazı';
                        $bSlug = $b['slug'] ?? \Illuminate\Support\Str::slug($bTitle);
                        $bImg = $b['image'] ?? $b['kapak'] ?? $b['resim'] ?? $dg.'/images/blog/blog-1-1.jpg';
                        $bOzet = \Illuminate\Support\Str::limit(strip_tags((string)($b['ozet'] ?? $b['icerik'] ?? $b['content'] ?? '')), 120);
                        $href = route('frontend.blog.detay', $bSlug);
                        $tarih = $b['tarih'] ?? $b['created_at'] ?? null;
                    @endphp
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp">
                        <div class="blog-two__single">
                            <div class="blog-two__img">
                                <img src="{{ $bImg }}" alt="{{ $bTitle }}">
                                <a href="{{ $href }}"><span class="blog-two__plus"></span></a>
                            </div>
                            <div class="blog-two__content">
                                @if($tarih)
                                    <p class="blog-two__date" style="font-size:12px;opacity:.7;margin-bottom:8px">{{ \Illuminate\Support\Str::limit((string)$tarih, 32) }}</p>
                                @endif
                                <h3 class="blog-two__title"><a href="{{ $href }}">{{ $bTitle }}</a></h3>
                                <p class="blog-two__text">{{ $bOzet }}</p>
                                <a href="{{ $href }}" class="blog-two__read-more" style="font-weight:600;color:var(--delogis-base,#B9905D)">Devamını oku →</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
