{{--
    Nasıl Çalışır? / Süreç Adımları — tema-1
--}}
@php
    $adimlar = collect($ayar['adimlar'] ?? [])->filter(fn ($s) => is_array($s) && !empty($s['baslik']))->take(4)->values();
@endphp

@if($adimlar->isNotEmpty())
<div class="how-it-work">
    <div class="container">
        <div class="row section-row">
            <div class="col-lg-6">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ $ayar['kucuk_baslik'] ?? 'Süreç' }}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $ayar['ana_baslik'] ?? 'İlk seansa kadar süreç' }}</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                    <a href="{{ route('frontend.randevu') }}" class="btn-default">Randevu Al</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="how-work-step-box">
                    @foreach($adimlar as $a)
                        <div class="how-work-step-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                            <div class="how-work-step-no">
                                <h3>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</h3>
                            </div>
                            <div class="how-work-step-content">
                                <h3>{{ $a['baslik'] }}</h3>
                                @if(!empty($a['metin']))
                                    <p>{{ $a['metin'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@push('head')
<style>
.how-work-step-content { max-width: 260px; }
.how-work-step-content h3 { color: var(--primary-color); }
.how-work-step-content p { color: var(--text-color); }
@media (max-width: 991px) {
    .how-work-step-content { max-width: 100%; }
}
</style>
@endpush
