@extends(theme_layout())

@section('baslik', 'Hizmetler | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', ($doktor['uzmanlik'] ?? 'Hekimlik').' alanında sunduğum hizmetler.')

@section('icerik')
@php
    /**
     * Blog-6 ile aynı tasarım: section.blog-one + blog-four__single kartlar
     */
    $hizmetler = collect($doktor['hizmetler'] ?? [])
        ->filter(fn ($h) => is_array($h) && (filled($h['baslik'] ?? null) || filled($h['ad'] ?? null) || filled($h['id'] ?? null)))
        ->values();
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'Hizmetler', 'crumb' => 'Hizmetler'])

{{-- blog-6 kart stili --}}
<section class="blog-one services-as-blog">
    <div class="container">
        @if($hizmetler->isEmpty())
            <div class="text-center" style="padding:48px 0">
                <p>Henüz yayınlanmış hizmet bulunamadı.</p>
                <a href="{{ route('frontend.randevu') }}" class="thm-btn" style="margin-top:16px">Randevu Al</a>
            </div>
        @else
            <div class="row gutter-y-30">
                @foreach ($hizmetler as $idx => $h)
                    @php
                        $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                        $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                        $href = route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? ''));
                        $img = $h['image'] ?? $h['resim'] ?? $h['kapak'] ?? null;
                        if (filled($img) && function_exists('media_url') && ! preg_match('#^(https?:)?//#i', (string) $img)) {
                            $img = media_url((string) $img) ?: $img;
                        }
                        $sure = $h['sure'] ?? $h['duration'] ?? null;
                        // Blog tarih rozeti yerine süre / sıra
                        $badgeTop = '—';
                        $badgeBottom = 'Hiz';
                        if (filled($sure)) {
                            $sureStr = (string) $sure;
                            if (preg_match('/(\d+)/', $sureStr, $m)) {
                                $badgeTop = $m[1];
                                $badgeBottom = stripos($sureStr, 'saat') !== false ? 'Saat' : 'Dk';
                            } else {
                                $badgeTop = \Illuminate\Support\Str::limit($sureStr, 3, '');
                                $badgeBottom = '';
                            }
                        } else {
                            $badgeTop = str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT);
                            $badgeBottom = 'Hiz';
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6 wow fadeInUp dg-card-col" data-wow-delay="{{ ($idx % 3) * 100 }}ms">
                        <div class="blog-four__single dg-card">
                            <div class="blog-four__single__image dg-card__media">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $hAd }}" loading="lazy" decoding="async">
                                @else
                                    <div class="dg-card__img--empty" aria-hidden="true"></div>
                                @endif
                                <a href="{{ $href }}" class="blog-four__single__image__link" aria-label="{{ $hAd }}"></a>
                            </div>
                            <div class="blog-four__single__content dg-card__body">
                                <div class="blog-four__single__date">
                                    <span>{{ $badgeTop }}</span>{{ $badgeBottom }}
                                </div>
                                <h3 class="blog-four__single__title">
                                    <a href="{{ $href }}">{{ $hAd }}</a>
                                </h3>
                            </div>
                            <a class="blog-four__single__rm" href="{{ $href }}">
                                İncele<span class="delogis-icons-two-right-arrow"></span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center" style="margin-top:36px">
                <a href="{{ route('frontend.randevu') }}" class="thm-btn">Randevu Al</a>
            </div>
        @endif
    </div>
</section>
@endsection
