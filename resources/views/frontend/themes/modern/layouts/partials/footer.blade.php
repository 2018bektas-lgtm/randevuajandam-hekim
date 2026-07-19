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
    $sosyal = array_filter($doktor['sosyal'] ?? [], fn ($u) => filled($u));
    $map = $doktor['maps_embed'] ?? null;
    $tel = $doktor['telefon'] ?? null;
    $telRaw = $doktor['telefon_raw'] ?? preg_replace('/\D+/', '', (string) $tel);
    $eposta = $doktor['e_posta'] ?? null;
    $adres = $doktor['adres'] ?? null;
    $ilceIl = trim(($doktor['ilce'] ?? '').(! empty($doktor['il']) ? ' / '.$doktor['il'] : ''), ' /');
    $whatsapp = $doktor['whatsapp'] ?? null;
    $klinik = $doktor['klinik_adi'] ?? null;
    $adSoyad = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
@endphp
<footer class="mp-footer">
    <div class="mp-footer-grid mp-footer-grid-4">
        {{-- Marka + sosyal --}}
        <div>
            <div class="mp-footer-brand">{{ $adSoyad }}</div>
            @if($klinik)
                <p class="mp-footer-klinik">{{ $klinik }}</p>
            @endif
            @if(!empty($doktor['uzmanlik']))
                <p class="mp-footer-uzmanlik">{{ $doktor['uzmanlik'] }}</p>
            @endif
            @if(count($sosyal))
                <div class="mp-footer-social">
                    @foreach ($sosyal as $ad => $url)
                        @php
                            $label = match (strtolower((string) $ad)) {
                                'instagram' => 'IG',
                                'facebook' => 'FB',
                                'twitter', 'x' => 'X',
                                'linkedin' => 'IN',
                                'youtube' => 'YT',
                                'web_sitesi', 'website' => 'WEB',
                                default => strtoupper(mb_substr((string) $ad, 0, 2)),
                            };
                        @endphp
                        <a href="{{ $url }}" target="_blank" rel="noopener" title="{{ $ad }}" aria-label="{{ $ad }}">{{ $label }}</a>
                    @endforeach
                </div>
            @endif
            <a href="{{ route('frontend.randevu') }}" class="mp-btn mp-btn-primary" style="margin-top:14px">Randevu Al</a>
        </div>

        {{-- Hızlı linkler --}}
        <div>
            <h4>Hızlı linkler</h4>
            <div class="mp-footer-links">
                @foreach ($footerNav as $item)
                    <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                @endforeach
                <a href="{{ route('frontend.randevu') }}">Randevu Al</a>
            </div>
        </div>

        {{-- Tam iletişim --}}
        <div>
            <h4>İletişim</h4>
            <ul class="mp-footer-contact">
                @if($tel)
                    <li>
                        <span class="mp-fc-icon" aria-hidden="true">☎</span>
                        <div>
                            <small>Telefon</small>
                            <a href="tel:{{ $telRaw }}">{{ $tel }}</a>
                        </div>
                    </li>
                @endif
                @if($whatsapp && ($doktor['whatsapp_goster'] ?? true))
                    <li>
                        <span class="mp-fc-icon" aria-hidden="true">💬</span>
                        <div>
                            <small>WhatsApp</small>
                            <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp ile yazın</a>
                        </div>
                    </li>
                @endif
                @if($eposta)
                    <li>
                        <span class="mp-fc-icon" aria-hidden="true">✉</span>
                        <div>
                            <small>E-posta</small>
                            <a href="mailto:{{ $eposta }}">{{ $eposta }}</a>
                        </div>
                    </li>
                @endif
                @if($adres || $ilceIl)
                    <li>
                        <span class="mp-fc-icon" aria-hidden="true">📍</span>
                        <div>
                            <small>Adres</small>
                            <span>{{ $adres ?: $ilceIl }}</span>
                            @if($adres && $ilceIl)
                                <span class="mp-fc-sub">{{ $ilceIl }}</span>
                            @endif
                        </div>
                    </li>
                @endif
                @if(!empty($cs) && is_array($cs))
                    <li>
                        <span class="mp-fc-icon" aria-hidden="true">◷</span>
                        <div>
                            <small>Çalışma saatleri</small>
                            @foreach (collect($cs)->take(4) as $gun => $saat)
                                <span class="mp-fc-hours"><em>{{ $gun }}</em> {{ $saat }}</span>
                            @endforeach
                        </div>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Harita (ana platform konum) --}}
        <div>
            <h4>Konum</h4>
            @if($map)
                <div class="mp-footer-map">
                    <iframe
                        src="{{ $map }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Harita — {{ $adSoyad }}"
                        allowfullscreen></iframe>
                </div>
                @if($adres)
                    <p class="mp-footer-map-cap">{{ $adres }}</p>
                @endif
            @else
                <p class="mp-footer-map-empty">
                    @if($adres)
                        {{ $adres }}
                        @if($ilceIl)<br>{{ $ilceIl }}@endif
                    @else
                        Konum bilgisi platform profilinden gelecektir.
                    @endif
                </p>
                @if($adres)
                    <a class="mp-footer-map-link" href="https://maps.google.com/?q={{ urlencode($adres) }}" target="_blank" rel="noopener">Google Maps’te aç →</a>
                @endif
            @endif
        </div>
    </div>
    <div class="mp-footer-bar">
        <div class="mp-footer-bar-inner">
            <span>© {{ date('Y') }} {{ $klinik ?: $adSoyad }}. Tüm hakları saklıdır.</span>
            <span class="mp-footer-bar-contact">
                @if($tel)<a href="tel:{{ $telRaw }}">{{ $tel }}</a>@endif
                @if($tel && $eposta)<span>·</span>@endif
                @if($eposta)<a href="mailto:{{ $eposta }}">{{ $eposta }}</a>@endif
            </span>
        </div>
    </div>
</footer>

@if(($doktor['whatsapp_goster'] ?? true) && !empty($whatsapp))
<a class="wa-float" href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M20.52 3.48A11.86 11.86 0 0012.04 0C5.5 0 .2 5.3.2 11.84c0 2.09.55 4.13 1.6 5.93L0 24l6.4-1.67a11.8 11.8 0 005.64 1.44h.01c6.54 0 11.84-5.3 11.84-11.84 0-3.16-1.23-6.13-3.47-8.45zM12.05 21.5h-.01a9.65 9.65 0 01-4.91-1.35l-.35-.21-3.8 1 1.01-3.7-.23-.38a9.65 9.65 0 01-1.48-5.14c0-5.34 4.35-9.69 9.7-9.69 2.59 0 5.02 1.01 6.85 2.84a9.63 9.63 0 012.84 6.85c0 5.34-4.35 9.68-9.62 9.68zm5.32-7.25c-.29-.15-1.72-.85-1.99-.95-.27-.1-.46-.15-.66.15-.19.29-.76.95-.93 1.14-.17.2-.34.22-.63.07-.29-.15-1.22-.45-2.33-1.43-.86-.77-1.44-1.72-1.61-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.34.43-.51.15-.17.19-.29.29-.48.1-.2.05-.36-.02-.51-.07-.15-.66-1.59-.9-2.18-.24-.58-.48-.5-.66-.51h-.56c-.2 0-.51.07-.78.36-.27.29-1.02.99-1.02 2.42 0 1.43 1.05 2.81 1.19 3 .15.2 2.07 3.16 5.02 4.43.7.3 1.25.48 1.68.62.7.22 1.34.19 1.84.12.56-.08 1.72-.7 1.97-1.38.24-.68.24-1.26.17-1.38-.07-.12-.26-.2-.55-.34z"/>
    </svg>
</a>
@endif
