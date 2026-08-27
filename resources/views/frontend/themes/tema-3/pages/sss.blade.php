@extends(theme_layout())

@section('baslik', 'Sık Sorulan Sorular | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))

@section('icerik')
@php $photo = $doktor['profil_resmi'] ?? null; @endphp

@include('frontend.themes.tema-3.partials.page-banner', [
    'kod' => 'sss',
    'baslik' => 'Sıkça Sorulan Sorular',
    'breadcrumb' => [['label' => 'SSS', 'aktif' => true]],
])

<div class="our-faqs parallaxie">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="our-faqs-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">SSS</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Merak ettiğiniz sorular</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            {{ $doktor['kisa_bio'] ?? 'Aklınızdaki tüm soruları yanıtlamak için buradayız.' }}
                        </p>
                    </div>
                    <div class="faq-cta-box">
                        <div class="faq-cta-box-content wow fadeInUp" data-wow-delay="0.4s">
                            <h3>Cevap bulamadınız mı?</h3>
                            @if(!empty($doktor['telefon']))
                            <a href="tel:{{ $doktor['telefon_raw'] ?? preg_replace('/[^0-9+]/', '', $doktor['telefon']) }}" class="btn-faqs">
                                {{ $doktor['telefon'] }}
                            </a>
                            @else
                            <a href="{{ route('frontend.iletisim') }}" class="btn-faqs">İletişime Geçin</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                @if(!empty($doktor['sss']))
                <div class="faq-accordion" id="sssAccordion">
                    @foreach ($doktor['sss'] as $i => $faq)
                    <div class="accordion-item wow fadeInUp" data-wow-delay="{{ ($i % 5) * 0.1 }}s">
                        <h2 class="accordion-header" id="sssH{{ $i }}">
                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#sssC{{ $i }}"
                                    aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                                <span>{{ $i + 1 }}.</span> {{ $faq['soru'] }}
                            </button>
                        </h2>
                        <div id="sssC{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                             data-bs-parent="#sssAccordion">
                            <div class="accordion-body">
                                <p>{{ $faq['cevap'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="wow fadeInUp" style="padding:2rem 0;color:var(--text-color)">
                    <p>Henüz soru eklenmemiş. Bize doğrudan ulaşabilirsiniz.</p>
                    <a href="{{ route('frontend.iletisim') }}" class="btn-default" style="margin-top:1rem">İletişim</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
