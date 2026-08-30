{{-- İletişim satırları — $f, $stil: 'liste' | 'kart' --}}
@php $stil = $stil ?? 'liste'; @endphp

@if($f['goster']['iletisim'])
    <ul class="ftr-iletisim ftr-iletisim--{{ $stil }}">
        @if($f['adres'] !== '')
            <li>
                <span class="ftr-iletisim__ikon"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>
                <span class="ftr-iletisim__govde">
                    <span class="ftr-iletisim__etiket">Adres</span>
                    <span class="ftr-iletisim__deger">{{ $f['adres'] }}</span>
                </span>
            </li>
        @endif
        @if($f['telefon_gecerli'])
            <li>
                <span class="ftr-iletisim__ikon"><i class="fas fa-phone" aria-hidden="true"></i></span>
                <span class="ftr-iletisim__govde">
                    <span class="ftr-iletisim__etiket">Telefon</span>
                    <a class="ftr-iletisim__deger" href="tel:{{ $f['telefon_raw'] }}">{{ $f['telefon'] }}</a>
                </span>
            </li>
        @endif
        @if($f['eposta'] !== '')
            <li>
                <span class="ftr-iletisim__ikon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                <span class="ftr-iletisim__govde">
                    <span class="ftr-iletisim__etiket">E-posta</span>
                    <a class="ftr-iletisim__deger" href="mailto:{{ $f['eposta'] }}">{{ $f['eposta'] }}</a>
                </span>
            </li>
        @endif
        @if($f['saatler'] !== '')
            <li>
                <span class="ftr-iletisim__ikon"><i class="far fa-clock" aria-hidden="true"></i></span>
                <span class="ftr-iletisim__govde">
                    <span class="ftr-iletisim__etiket">Çalışma saatleri</span>
                    <span class="ftr-iletisim__deger">{{ $f['saatler'] }}</span>
                </span>
            </li>
        @endif
    </ul>
@endif
