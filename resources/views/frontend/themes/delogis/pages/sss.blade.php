@extends(theme_layout())

@section('baslik', 'Sıkça Sorulan Sorular | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', 'Sıkça sorulan sorular')

@section('icerik')
@php
    /** Delogis faq.html — faq-search-box + faq-page (sol CTA + sağ accordion) */
    $dg = rtrim((string) request()->getBasePath(), '/').'/themes/delogis';
    $sss = collect($doktor['sss'] ?? $doktor['faqs'] ?? [])
        ->filter(fn ($item) => is_array($item) && filled($item['soru'] ?? $item['question'] ?? $item['baslik'] ?? null))
        ->values();
    $tel = $doktor['telefon'] ?? null;
    $telRaw = $doktor['telefon_raw'] ?? preg_replace('/\D+/', '', (string) $tel);
    $eposta = $doktor['e_posta'] ?? null;
@endphp

@include('frontend.themes.delogis.partials.page-header', ['title' => 'S.S.S.', 'crumb' => 'S.S.S.'])

{{-- faq.html: faq-search-box --}}
<section class="faq-search-box">
    <div class="container">
        <div class="faq-search-box__inner">
            <div class="faq-search-box__shape float-bob-x">
                <img src="{{ $dg }}/images/shapes/faq-search-shape-1.png" alt="">
            </div>
            <div class="faq-search-box__left">
                <h3 class="faq-search-box__title">Sıkça Sorulan Sorular</h3>
                <p class="faq-search-box__text">
                    Aklınıza takılan soruların yanıtlarını burada bulabilirsiniz. Aradığınızı bulamazsanız bize ulaşın.
                </p>
                @if($sss->isNotEmpty())
                    <form class="faq-search-box__form" id="dg-faq-search" action="#" onsubmit="return false;">
                        <div class="faq-search-box__form-input">
                            <input type="search" id="dg-faq-q" placeholder="Soru ara…" autocomplete="off" aria-label="Soru ara">
                            <button type="submit" aria-label="Ara"><i class="icon-magnifying-glass"></i></button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- faq.html: faq-page --}}
<section class="faq-page">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="faq-page__left">
                    <div class="faq-page__content">
                        <div class="faq-page__content-bg" aria-hidden="true"></div>
                        <div class="faq-page__call-icon">
                            <span class="icon-phone-call"></span>
                        </div>
                        <h4 class="faq-page__content-title">
                            Hâlâ sorunuz
                            <br> mu var?
                        </h4>
                        <div class="faq-page__call">
                            @if($tel)
                                <p class="faq-page__call-sub-title">Bizi arayın</p>
                                <h5 class="faq-page__call-number">
                                    <a href="tel:{{ $telRaw }}">{{ $tel }}</a>
                                </h5>
                            @elseif($eposta)
                                <p class="faq-page__call-sub-title">E-posta</p>
                                <h5 class="faq-page__call-number">
                                    <a href="mailto:{{ $eposta }}">{{ $eposta }}</a>
                                </h5>
                            @else
                                <p class="faq-page__call-sub-title">İletişim</p>
                                <h5 class="faq-page__call-number">
                                    <a href="{{ route('frontend.iletisim') }}">Bize yazın</a>
                                </h5>
                            @endif
                        </div>
                        <div class="faq-page__cta" style="margin-top:22px">
                            <a href="{{ route('frontend.randevu') }}" class="thm-btn">Randevu Al</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="faq-page__right">
                    @if($sss->isEmpty())
                        <div class="text-center" style="padding:48px 20px">
                            <p>Henüz SSS eklenmemiş.</p>
                            <a href="{{ route('frontend.iletisim') }}" class="thm-btn" style="margin-top:16px">İletişime geçin</a>
                        </div>
                    @else
                        <div class="accrodion-grp faq-one-accrodion" data-grp-name="faq-one-accrodion">
                            @foreach ($sss as $i => $item)
                                @php
                                    $q = decode_text($item['soru'] ?? $item['question'] ?? $item['baslik'] ?? 'Soru');
                                    $a = decode_text($item['cevap'] ?? $item['answer'] ?? $item['icerik'] ?? '');
                                    $search = mb_strtolower($q.' '.$a);
                                @endphp
                                <div class="accrodion {{ $i === 0 ? 'active' : '' }}" data-search="{{ e($search) }}">
                                    <div class="accrodion-title">
                                        <h4>{{ $q }}</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>{!! nl2br(e(strip_tags($a))) !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p id="dg-faq-empty" class="text-center" style="display:none;padding:24px 0;color:var(--delogis-gray,#736b6b)">
                            Aramanıza uygun soru bulunamadı.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@if($sss->isNotEmpty())
@push('scripts')
<script>
(function () {
    var input = document.getElementById('dg-faq-q');
    if (!input) return;
    var empty = document.getElementById('dg-faq-empty');
    input.addEventListener('input', function () {
        var q = (this.value || '').trim().toLowerCase();
        var visible = 0;
        document.querySelectorAll('.faq-page__right .accrodion').forEach(function (el) {
            var ok = !q || (el.getAttribute('data-search') || '').indexOf(q) !== -1;
            el.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });
        if (empty) empty.style.display = visible ? 'none' : 'block';
    });
})();
</script>
@endpush
@endif
