{{--
    Footer tasarımı: "Koyu Vitrin"
    Koyu zemin, ortalanmış marka bloğu, 3 sütun bilgi, pill sosyal ikonlar.
--}}
<footer class="ftr ftr--koyuvitrin ftr--koyu">
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
                @include('frontend.partials.footer._sosyal', ['f' => $f, 'stil' => 'pill'])
            </div>

            <div class="ftr-grid ftr-grid--3">
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
                            <p>Uygun gün ve saati seçerek online randevu oluşturun.</p>
                            <a href="{{ route('frontend.randevu') }}" class="ftr-btn ftr-btn--solid ftr-btn--blok">
                                <i class="fas fa-calendar-check" aria-hidden="true"></i>
                                <span>Randevu Al</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            @include('frontend.partials.footer._alt', ['f' => $f])
        </div>
    </div>
</footer>
