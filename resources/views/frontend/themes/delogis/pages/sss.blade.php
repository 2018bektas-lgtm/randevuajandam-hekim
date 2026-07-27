@extends(theme_layout())

@section('baslik', 'Sıkça Sorulan Sorular | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', 'Sıkça sorulan sorular')

@section('icerik')
@php
    $sss = collect($doktor['sss'] ?? $doktor['faqs'] ?? [])->values();
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'S.S.S.', 'crumb' => 'S.S.S.'])

<section class="faq-page">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">Yardım</span>
            <h2 class="section-title__title">Sıkça sorulan sorular</h2>
        </div>

        @if($sss->isEmpty())
            <div class="text-center" style="padding:40px 0">
                <p>Henüz SSS eklenmemiş.</p>
                <a href="{{ route('frontend.iletisim') }}" class="thm-btn" style="margin-top:12px">İletişime geçin</a>
            </div>
        @else
            <div class="faq-page__single">
                <div class="accrodion-grp" data-grp-name="faq-one-accrodion">
                    @foreach ($sss as $i => $item)
                        @php
                            $q = $item['soru'] ?? $item['question'] ?? $item['baslik'] ?? 'Soru';
                            $a = $item['cevap'] ?? $item['answer'] ?? $item['icerik'] ?? '';
                        @endphp
                        <div class="accrodion {{ $i === 0 ? 'active' : '' }}">
                            <div class="accrodion-title">
                                <h4>{{ $q }}</h4>
                            </div>
                            <div class="accrodion-content">
                                <div class="inner">
                                    <p>{!! nl2br(e(strip_tags((string)$a))) !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="text-center" style="margin-top:40px">
            <a href="{{ route('frontend.randevu') }}" class="thm-btn">Randevu Al</a>
        </div>
    </div>
</section>
@endsection
