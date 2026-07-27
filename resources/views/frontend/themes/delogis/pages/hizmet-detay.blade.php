@extends(theme_layout())

@php
    /**
     * Delogis services-details (anxiety-grief.html vb.)
     * Sol sidebar: hizmet listesi + CTA
     * Sağ: görsel, içerik, süreç, randevu bandı
     * Fiyat gösterilmez.
     */
    $h = $hizmet ?? [];
    $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
    $hDesc = $h['aciklama'] ?? $h['kisa'] ?? $h['icerik'] ?? '';
    $img = $h['image'] ?? $h['resim'] ?? $h['kapak'] ?? null;
    $curSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
    $sure = $h['sure'] ?? $h['duration'] ?? null;

    $tumHizmetler = collect($doktor['hizmetler'] ?? [])
        ->filter(fn ($x) => is_array($x) && (filled($x['baslik'] ?? null) || filled($x['ad'] ?? null)))
        ->values();

    $telefon = $doktor['telefon'] ?? $doktor['iletisim']['telefon'] ?? null;
    $telHref = $telefon ? ('tel:'.preg_replace('/\s+/', '', (string) $telefon)) : null;
@endphp

@section('baslik', $hAd.' | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', \Illuminate\Support\Str::limit(strip_tags((string) $hDesc), 160))

@section('icerik')
@include('frontend.themes.delogis.partials.page-header', ['title' => $hAd, 'crumb' => 'Hizmetler'])

{{-- services-details (anxiety-grief.html düzeni: sidebar sol, içerik sağ) --}}
<section class="services-details">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="services-details__sidebar">
                    <div class="services-details__services-list">
                        <ul class="services-details__services list-unstyled">
                            @forelse ($tumHizmetler as $item)
                                @php
                                    $iAd = $item['baslik'] ?? $item['ad'] ?? 'Hizmet';
                                    $iSlug = $item['slug'] ?? \Illuminate\Support\Str::slug($iAd);
                                    $iHref = route('frontend.hizmet.detay', $iSlug ?: ($item['id'] ?? ''));
                                    $isActive = ($iSlug === $curSlug) || ((string) ($item['id'] ?? '') === (string) ($h['id'] ?? ''));
                                @endphp
                                <li class="{{ $isActive ? 'active' : '' }}">
                                    <a href="{{ $iHref }}">{{ $iAd }}</a>
                                </li>
                            @empty
                                <li class="active"><a href="{{ route('frontend.hizmetler') }}">Hizmetler</a></li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="banner-one">
                        <div class="banner-one__bg" aria-hidden="true"></div>
                        <h3 class="banner-one__title">
                            Benzer bir
                            <br> durum mu yaşıyorsunuz?
                            <br> Bize ulaşın
                        </h3>
                        <div class="banner-one__btn-box">
                            <a href="{{ route('frontend.iletisim') }}" class="banner-one__btn thm-btn">İletişim</a>
                        </div>
                    </div>

                    <div class="services-details__get-started" style="margin-top:24px">
                        <h3 class="services-details__get-started-title">Randevu alın</h3>
                        <p class="services-details__get-started-text">Online randevu ile size uygun saati seçin.</p>
                        <a href="{{ route('frontend.randevu') }}" class="thm-btn">Randevu Al</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="services-details__right">
                    @if($img)
                        <div class="services-details__img">
                            <img src="{{ $img }}" alt="{{ $hAd }}">
                        </div>
                    @endif

                    <h3 class="services-details__title-1">{{ $hAd }}</h3>

                    <div class="services-details__text-1 dg-prose">
                        {!! $hDesc !!}
                    </div>

                    @if(filled($sure))
                        <ul class="list-unstyled services-details__points" style="margin-top:20px">
                            <li>
                                <div class="icon"><i class="fa fa-clock"></i></div>
                                <div class="text"><p>Süre: {{ $sure }}</p></div>
                            </li>
                        </ul>
                    @endif

                    <h3 class="services-details__title-3">Süreç nasıl işler?</h3>
                    <ul class="services-details__process list-unstyled">
                        <li>
                            <div class="icon">
                                <span class="icon-form"></span>
                                <div class="services-details__process-count"></div>
                            </div>
                            <h3>Randevu alın</h3>
                            <p>Size uygun gün ve saati<br>online seçin.</p>
                        </li>
                        <li>
                            <div class="icon">
                                <span class="icon-psychologist-2"></span>
                                <div class="services-details__process-count"></div>
                            </div>
                            <h3>Görüşme</h3>
                            <p>Planlanan saatte<br>görüşmenizi gerçekleştirin.</p>
                        </li>
                        <li>
                            <div class="icon">
                                <span class="icon-self-improvement"></span>
                                <div class="services-details__process-count"></div>
                            </div>
                            <h3>Takip</h3>
                            <p>Gerekiyorsa sonraki<br>adımları birlikte planlayın.</p>
                        </li>
                    </ul>

                    <div class="services-details__book">
                        <div class="services-details__book-top">
                            <div class="icon">
                                <span class="icon-phone-call"></span>
                            </div>
                            <div class="content">
                                <p>Sorunuz mu var?</p>
                                @if($telHref)
                                    <h4><a href="{{ $telHref }}">{{ $telefon }}</a></h4>
                                @else
                                    <h4><a href="{{ route('frontend.iletisim') }}">İletişime geçin</a></h4>
                                @endif
                            </div>
                        </div>
                        <div class="services-details__book-title-and-btn">
                            <h3 class="services-details__book-title">
                                Randevunuzu
                                <br> şimdi alın
                            </h3>
                            <div class="services-details__book-btn-box">
                                <a href="{{ route('frontend.randevu') }}" class="services-details__book-btn thm-btn">Randevu Al</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
