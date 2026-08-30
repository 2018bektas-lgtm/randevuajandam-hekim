{{-- Footer alt şeridi: telif + yasal sayfalar + marka. $f --}}
<div class="ftr-alt">
    <p class="ftr-alt__telif">{{ $f['telif'] }}</p>

    <div class="ftr-alt__linkler">
        @if($f['goster']['sayfalar'])
            @foreach($f['sayfalar'] as $sayfa)
                <a href="{{ $sayfa['href'] }}">{{ $sayfa['baslik'] }}</a>
            @endforeach
        @endif
        @if($f['goster']['marka'])
            <a href="https://randevuajandam.com" target="_blank" rel="noopener" class="ftr-alt__marka">
                Randevu Ajandam
            </a>
        @endif
    </div>
</div>
