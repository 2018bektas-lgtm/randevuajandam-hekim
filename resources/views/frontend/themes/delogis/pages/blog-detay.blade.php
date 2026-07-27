@extends(theme_layout())

@php
    $y = $yazi ?? $blog ?? [];
    $title = $y['baslik'] ?? $y['title'] ?? 'Yazı';
    $icerik = $y['icerik'] ?? $y['content'] ?? $y['ozet'] ?? '';
@endphp

@section('baslik', $title.' | Blog')
@section('meta_aciklama', \Illuminate\Support\Str::limit(strip_tags((string)$icerik), 160))

@section('icerik')
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $img = $y['image'] ?? $y['kapak'] ?? $y['resim'] ?? null;
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => $title, 'crumb' => 'Blog'])

<section class="blog-details">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="blog-details__left">
                    @if($img)
                        <div class="blog-details__img">
                            <img src="{{ $img }}" alt="{{ $title }}">
                        </div>
                    @endif
                    <div class="blog-details__content">
                        <h3 class="blog-details__title">{{ $title }}</h3>
                        <div class="blog-details__text-1 dg-prose">
                            {!! $icerik !!}
                        </div>
                    </div>
                    <div style="margin-top:28px">
                        <a href="{{ route('frontend.blog') }}" class="thm-btn thm-btn--two">← Tüm yazılar</a>
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
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
