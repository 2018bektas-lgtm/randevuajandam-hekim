@php
    $footerNav = ! empty($doktor['menu']) && is_array($doktor['menu'])
        ? collect($doktor['menu'])->filter(fn ($i) => ($i['key'] ?? '') !== 'anasayfa')->map(fn ($item) => [
            'href' => nav_href($item),
            'label' => $item['label'] ?? '',
        ])->values()->all()
        : [
            ['href' => route('frontend.hakkimda'), 'label' => 'Hakkımda'],
            ['href' => route('frontend.hizmetler'), 'label' => 'Hizmetler'],
            ['href' => route('frontend.blog'), 'label' => 'Blog'],
            ['href' => route('frontend.iletisim'), 'label' => 'İletişim'],
        ];
    $cs = $doktor['calisma_saatleri'] ?? [];
@endphp
<footer class="mp-footer">
    <div class="mp-footer-grid">
        <div>
            <div class="mp-footer-brand">{{ trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Klinik')) }}</div>
            <p>{{ $doktor['footer_metin'] ?? $doktor['kisa_bio'] ?? ($doktor['slogan'] ?? 'Güvenilir, kişiye özel sağlık hizmeti.') }}</p>
            @if(!empty($doktor['telefon_raw']))
                <a href="tel:{{ $doktor['telefon_raw'] }}" class="mp-btn mp-btn-primary" style="margin-top:8px">{{ $doktor['telefon'] }}</a>
            @endif
        </div>
        <div>
            <h4>Hızlı linkler</h4>
            <div class="mp-footer-links">
                @foreach ($footerNav as $item)
                    <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                @endforeach
                <a href="{{ route('frontend.randevu') }}">Randevu Al</a>
            </div>
        </div>
        <div>
            <h4>İletişim</h4>
            <ul class="mp-footer-contact" style="margin:0;padding:0">
                @if(!empty($doktor['telefon']))
                    <li>☎ <a href="tel:{{ $doktor['telefon_raw'] ?? '' }}">{{ $doktor['telefon'] }}</a></li>
                @endif
                @if(!empty($doktor['e_posta']))
                    <li>✉ <a href="mailto:{{ $doktor['e_posta'] }}">{{ $doktor['e_posta'] }}</a></li>
                @endif
                @if(!empty($doktor['adres']))
                    <li>📍 {{ $doktor['adres'] }}</li>
                @endif
                @if(!empty($cs) && is_array($cs))
                    @php
                        $first = collect($cs)->filter(fn ($v) => is_string($v) && stripos($v, 'kapal') === false)->take(2);
                    @endphp
                    @foreach ($first as $gun => $saat)
                        <li>◷ {{ $gun }}: {{ $saat }}</li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    <div class="mp-footer-bar">
        © {{ date('Y') }} {{ trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? '')) }} · MediPlus Modern tema
    </div>
</footer>
