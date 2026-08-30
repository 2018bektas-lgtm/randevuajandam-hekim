{{--
    Footer tasarımı: "4 Sütun"
    Marka / Keşfet / İletişim / Randevu sütunları.
--}}
<footer class="ftr ftr--sutunlu ftr--{{ $f['ton'] }}">
    @include('frontend.partials.footer._cta', ['f' => $f])

    <div class="ftr-govde">
        <div class="container">
            <div class="ftr-grid ftr-grid--4">
                <div class="ftr-kol ftr-kol--marka">
                    @include('frontend.partials.footer._logo', ['f' => $f, 'hiza' => 'sol'])
                    @if($f['goster']['hakkinda'])
                        <p class="ftr-aciklama">{{ $f['aciklama'] }}</p>
                    @endif
                    @include('frontend.partials.footer._sosyal', ['f' => $f, 'stil' => 'daire'])
                </div>

                @if($f['goster']['kesfet'])
                    <div class="ftr-kol ftr-kol--nav">
                        <h3 class="ftr-baslik">{{ $f['baslik_kesfet'] }}</h3>
                        <ul class="ftr-nav">
                            @foreach($f['nav'] as $item)
                                <li>
                                    <a href="{{ $item['href'] }}"
                                       @if(!empty($item['external'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
                                </li>
                            @endforeach
                            <li><a href="{{ route('frontend.randevu') }}">Randevu Al</a></li>
                        </ul>
                    </div>
                @endif

                @if($f['goster']['iletisim'])
                    <div class="ftr-kol ftr-kol--iletisim">
                        <h3 class="ftr-baslik">{{ $f['baslik_iletisim'] }}</h3>
                        @include('frontend.partials.footer._iletisim', ['f' => $f, 'stil' => 'liste'])
                    </div>
                @endif

                @if($f['goster']['randevu'])
                    <div class="ftr-kol ftr-kol--randevu">
                        <h3 class="ftr-baslik">Randevu</h3>
                        <div class="ftr-randevu">
                            <p>Online randevu alın; size en uygun gün ve saati seçin.</p>
                            <a href="{{ route('frontend.randevu') }}" class="ftr-btn ftr-btn--solid ftr-btn--blok">
                                <i class="fas fa-calendar-check" aria-hidden="true"></i>
                                <span>Randevu Al</span>
                            </a>
                            @if($f['telefon_gecerli'])
                                <a href="tel:{{ $f['telefon_raw'] }}" class="ftr-randevu__tel">
                                    <i class="fas fa-phone-volume" aria-hidden="true"></i>
                                    {{ $f['telefon'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            @include('frontend.partials.footer._alt', ['f' => $f])
        </div>
    </div>
</footer>
