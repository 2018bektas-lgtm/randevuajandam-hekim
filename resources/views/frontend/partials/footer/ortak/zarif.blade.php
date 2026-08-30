{{--
    Footer tasarımı: "Zarif"
    Üst randevu şeridi + geniş marka bloğu + 3 sütun (tanıtım / keşfet / iletişim).
    Değişken: $f (SiteFooterService::verisi), $doktor
--}}
<footer class="ftr ftr--zarif ftr--{{ $f['ton'] }}">
    @include('frontend.partials.footer._cta', ['f' => $f])

    <div class="ftr-govde">
        <div class="container">
            <div class="ftr-marka">
                @include('frontend.partials.footer._logo', ['f' => $f, 'hiza' => 'sol'])
                @if($f['slogan'] !== '')
                    <h2 class="ftr-slogan">{{ $f['slogan'] }}</h2>
                @endif
            </div>

            <div class="ftr-grid ftr-grid--zarif">
                <div class="ftr-kol ftr-kol--marka">
                    @if($f['goster']['hakkinda'])
                        <p class="ftr-aciklama">{{ $f['aciklama'] }}</p>
                    @endif
                    @if($f['goster']['sosyal'])
                        <h3 class="ftr-baslik ftr-baslik--sm">{{ $f['baslik_sosyal'] }}</h3>
                        @include('frontend.partials.footer._sosyal', ['f' => $f, 'stil' => 'daire'])
                    @endif
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
                        </ul>
                    </div>
                @endif

                @if($f['goster']['iletisim'])
                    <div class="ftr-kol ftr-kol--iletisim">
                        <h3 class="ftr-baslik">{{ $f['baslik_iletisim'] }}</h3>
                        @include('frontend.partials.footer._iletisim', ['f' => $f, 'stil' => 'kart'])
                    </div>
                @endif
            </div>

            @include('frontend.partials.footer._alt', ['f' => $f])
        </div>
    </div>
</footer>
