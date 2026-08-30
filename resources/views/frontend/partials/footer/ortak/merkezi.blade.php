{{--
    Footer tasarımı: "Merkezî Minimal"
    Tek kolon, ortalanmış logo + slogan, yatay menü, iletişim çipleri, sosyal satırı.
--}}
<footer class="ftr ftr--merkezi ftr--{{ $f['ton'] }}">
    @include('frontend.partials.footer._cta', ['f' => $f])

    <div class="ftr-govde">
        <div class="container">
            <div class="ftr-tepe">
                @include('frontend.partials.footer._logo', ['f' => $f, 'hiza' => 'orta'])
                @if($f['slogan'] !== '')
                    <h2 class="ftr-slogan ftr-slogan--orta">{{ $f['slogan'] }}</h2>
                @endif
                @if($f['goster']['hakkinda'])
                    <p class="ftr-aciklama ftr-aciklama--orta">{{ $f['aciklama'] }}</p>
                @endif
            </div>

            @if($f['goster']['kesfet'])
                <nav class="ftr-yatay-nav" aria-label="{{ $f['baslik_kesfet'] }}">
                    @foreach($f['nav'] as $item)
                        <a href="{{ $item['href'] }}"
                           @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
                    @endforeach
                </nav>
            @endif

            @if($f['goster']['iletisim'])
                <div class="ftr-cipler">
                    @if($f['telefon_gecerli'])
                        <a class="ftr-cip" href="tel:{{ $f['telefon_raw'] }}">
                            <i class="fas fa-phone" aria-hidden="true"></i>{{ $f['telefon'] }}
                        </a>
                    @endif
                    @if($f['eposta'] !== '')
                        <a class="ftr-cip" href="mailto:{{ $f['eposta'] }}">
                            <i class="fas fa-envelope" aria-hidden="true"></i>{{ $f['eposta'] }}
                        </a>
                    @endif
                    @if($f['adres'] !== '')
                        <span class="ftr-cip">
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>{{ $f['adres'] }}
                        </span>
                    @endif
                </div>
            @endif

            <div class="ftr-sosyal-satir">
                @include('frontend.partials.footer._sosyal', ['f' => $f, 'stil' => 'daire'])
            </div>

            @include('frontend.partials.footer._alt', ['f' => $f])
        </div>
    </div>
</footer>
