@extends(theme_layout())

@section('baslik', 'Blog | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))

@section('icerik')
@php $photo = $doktor['profil_resmi'] ?? null; @endphp

<div class="page-header parallaxie"@if($photo) style="background-image:url('{{ $photo }}')"@endif>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="page-header-box">
                    <h1 class="text-anime-style-2" data-cursor="-opaque">Blog</h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('frontend.anasayfa') }}">Anasayfa</a></li>
                            <li class="breadcrumb-item active">Blog</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="our-blog">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-8">
                <div class="section-title">
                    <h3 class="wow fadeInUp">sağlık yazıları</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Uzman görüşleri ve sağlık rehberi</h2>
                </div>
            </div>
        </div>
        @if(!empty($doktor['bloglar']))
        <div class="row">
            @foreach ($doktor['bloglar'] as $i => $yazi)
            <div class="col-lg-4 col-md-6">
                <div class="post-item wow fadeInUp" data-wow-delay="{{ ($i % 3) * 0.2 }}s">
                    <div class="post-featured-image">
                        <figure>
                            <a href="{{ route('frontend.blog.detay', $yazi['slug']) }}" class="image-anime" data-cursor-text="Oku">
                                <img src="{{ $yazi['image'] ?? asset('vendor/hipno/images/post-1.jpg') }}" alt="{{ $yazi['baslik'] }}" loading="{{ $i < 6 ? 'eager' : 'lazy' }}">
                            </a>
                        </figure>
                    </div>
                    <div class="post-item-body">
                        @if(!empty($yazi['tarih']) || !empty($yazi['kategori']))
                        <div style="display:flex;gap:.75rem;margin-bottom:.5rem;font-size:.82rem;color:var(--accent-color)">
                            @if(!empty($yazi['tarih']))<span>{{ $yazi['tarih'] }}</span>@endif
                            @if(!empty($yazi['okuma']))<span>· {{ $yazi['okuma'] }}</span>@endif
                        </div>
                        @endif
                        <div class="post-item-content">
                            <h3><a href="{{ route('frontend.blog.detay', $yazi['slug']) }}">{{ $yazi['baslik'] }}</a></h3>
                            @if(!empty($yazi['ozet']))
                            <p style="color:var(--text-color);font-size:.9rem;line-height:1.6;margin-top:.5rem">{{ Str::limit($yazi['ozet'], 120) }}</p>
                            @endif
                        </div>
                        <div class="post-item-btn">
                            <a href="{{ route('frontend.blog.detay', $yazi['slug']) }}" class="readmore-btn">devamını oku</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col-lg-12 text-center wow fadeInUp" style="padding:3rem 0">
                <p style="color:var(--text-color)">Henüz yazı yayınlanmamış.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
