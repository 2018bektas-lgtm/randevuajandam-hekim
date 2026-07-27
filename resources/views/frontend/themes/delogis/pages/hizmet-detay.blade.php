@extends(theme_layout())

@php
    /**
     * Blog-details ile aynı tasarım: blog-details layout + sidebar
     * Fiyat gösterilmez.
     */
    $h = $hizmet ?? [];
    $hAd = decode_text($h['baslik'] ?? $h['ad'] ?? 'Hizmet');
    $hDesc = decode_text($h['aciklama'] ?? $h['kisa'] ?? $h['icerik'] ?? '');
    $img = $h['image'] ?? $h['resim'] ?? $h['kapak'] ?? null;
    $sure = decode_text($h['sure'] ?? $h['duration'] ?? null);
    $curSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);

    $tumHizmetler = collect($doktor['hizmetler'] ?? [])
        ->filter(fn ($x) => is_array($x) && (filled($x['baslik'] ?? null) || filled($x['ad'] ?? null)))
        ->values();

    $digerHizmetler = $tumHizmetler
        ->filter(function ($x) use ($curSlug, $h) {
            $s = $x['slug'] ?? \Illuminate\Support\Str::slug($x['baslik'] ?? $x['ad'] ?? '');

            return $s !== $curSlug && (string) ($x['id'] ?? '') !== (string) ($h['id'] ?? '');
        })
        ->take(4)
        ->values();

    $author = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
@endphp

@section('baslik', $hAd.' | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', \Illuminate\Support\Str::limit(strip_tags((string) $hDesc), 160))

@section('icerik')
@include('frontend.themes.delogis.partials.page-header', ['title' => $hAd, 'crumb' => 'Hizmetler'])

{{-- blog-details.html düzeni --}}
<section class="blog-details services-as-blog-details">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="blog-details__left">
                    @if($img)
                        <div class="blog-details__img">
                            <img src="{{ $img }}" alt="{{ $hAd }}">
                            @if(filled($sure))
                                <div class="blog-details__date">
                                    <p>
                                        @php
                                            $sureStr = (string) $sure;
                                            $num = preg_match('/(\d+)/', $sureStr, $m) ? $m[1] : $sureStr;
                                            $unit = stripos($sureStr, 'saat') !== false ? 'Saat' : (preg_match('/\d/', $sureStr) ? 'Dk' : '');
                                        @endphp
                                        {{ $num }}@if($unit)<br>{{ $unit }}@endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="blog-details__content">
                        <ul class="blog-details__meta list-unstyled">
                            @if($author !== '')
                                <li>
                                    <span><i class="fas fa-user-circle"></i>{{ $author }}</span>
                                </li>
                            @endif
                            @if(filled($sure))
                                <li>
                                    <span><i class="fas fa-clock"></i>{{ $sure }}</span>
                                </li>
                            @endif
                        </ul>

                        <h3 class="blog-details__title">{{ $hAd }}</h3>

                        <div class="blog-details__text-1 dg-prose">
                            {!! $hDesc !!}
                        </div>

                        <div class="blog-details__bottom">
                            <p class="blog-details__tags">
                                <span>Hizmet</span>
                                <a href="{{ route('frontend.hizmetler') }}">Tüm hizmetler</a>
                                <a href="{{ route('frontend.randevu') }}">Randevu Al</a>
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
                    @if($digerHizmetler->isNotEmpty())
                        <div class="sidebar__single sidebar__post">
                            <h3 class="sidebar__title">Diğer hizmetler</h3>
                            <ul class="sidebar__post-list list-unstyled">
                                @foreach ($digerHizmetler as $p)
                                    @php
                                        $pTitle = $p['baslik'] ?? $p['ad'] ?? 'Hizmet';
                                        $pSlug = $p['slug'] ?? \Illuminate\Support\Str::slug($pTitle);
                                        $pImg = $p['image'] ?? $p['resim'] ?? $p['kapak'] ?? null;
                                        $pHref = route('frontend.hizmet.detay', $pSlug ?: ($p['id'] ?? ''));
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

                    <div class="sidebar__single sidebar__category">
                        <h3 class="sidebar__title">Bağlantılar</h3>
                        <ul class="sidebar__category-list list-unstyled">
                            <li><a href="{{ route('frontend.hizmetler') }}">Tüm hizmetler</a></li>
                            <li><a href="{{ route('frontend.randevu') }}">Randevu Al</a></li>
                            <li><a href="{{ route('frontend.blog') }}">Blog</a></li>
                            <li><a href="{{ route('frontend.iletisim') }}">İletişim</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
