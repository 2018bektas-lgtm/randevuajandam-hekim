@extends(theme_layout())

@section('baslik', 'Eğitimler | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', 'Kurs, webinar ve eğitim programları.')

@section('icerik')
@php
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $egitimler = collect($doktor['egitimler'] ?? [])->values();
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'Eğitimler', 'crumb' => 'Eğitimler'])

<section class="services-three">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">Eğitim</span>
            <h2 class="section-title__title">Kurs ve programlar</h2>
        </div>
        @if($egitimler->isEmpty())
            <div class="text-center" style="padding:40px 0"><p>Henüz eğitim ilanı yok.</p></div>
        @else
            <div class="row">
                @foreach ($egitimler as $idx => $e)
                    @php
                        $ad = $e['baslik'] ?? 'Eğitim';
                        $slug = $e['slug'] ?? $e['id'] ?? '';
                        $href = route('frontend.egitim.detay', $slug);
                        $img = $e['image'] ?? null;
                    @endphp
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp dg-card-col" data-wow-delay="{{ ($idx % 3 + 1) * 100 }}ms">
                        <div class="services-three__single dg-card">
                            <div class="dg-card__media dg-card__img">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $ad }}">
                                @else
                                    <div class="dg-card__img--empty"><span class="icon-help"></span></div>
                                @endif
                            </div>
                            <div class="dg-card__body">
                                <h3 class="services-three__title"><a href="{{ $href }}">{{ $ad }}</a></h3>
                                <p class="services-three__text">{{ \Illuminate\Support\Str::limit(strip_tags((string)($e['ozet'] ?? '')), 120) }}</p>
                                <div class="services-three__btn-box">
                                    <a href="{{ $href }}">Detay <span class="icon-right-arrow"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
