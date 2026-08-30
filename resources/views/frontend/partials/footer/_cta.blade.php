{{-- Üst randevu şeridi — $f --}}
@if($f['goster']['cta'])
    <div class="ftr-cta">
        <div class="container">
            <div class="ftr-cta__inner">
                <h2 class="ftr-cta__baslik">{{ $f['cta_baslik'] }}</h2>
                <div class="ftr-cta__btns">
                    @if($f['telefon_gecerli'])
                        <a href="tel:{{ $f['telefon_raw'] }}" class="ftr-btn ftr-btn--ghost">
                            <i class="fas fa-phone-volume" aria-hidden="true"></i>
                            <span>{{ $f['telefon'] }}</span>
                        </a>
                    @endif
                    <a href="{{ route('frontend.randevu') }}" class="ftr-btn ftr-btn--solid">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i>
                        <span>Randevu Al</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
