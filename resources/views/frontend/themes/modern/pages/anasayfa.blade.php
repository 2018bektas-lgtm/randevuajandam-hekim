@extends(theme_layout())

@section('baslik', trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim').' | '.($doktor['uzmanlik'] ?? 'Klinik')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? $doktor['slogan'] ?? '')

@section('icerik')
@php
    $photo = function_exists('doctor_photo')
        ? doctor_photo($doktor ?? null, 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?auto=format&fit=crop&w=1200&q=80')
        : ($doktor['profil_resmi'] ?? 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?auto=format&fit=crop&w=1200&q=80');
    $slider = collect($doktor['slider'] ?? [])->filter(fn ($s) => is_array($s))->values()->all();
    // Panel/API slider yoksa en az bir slayt üret
    if ($slider === []) {
        $slider = [[
            'baslik' => 'Güvenebileceğiniz medikal hizmet sunuyoruz',
            'baslik_vurgulu' => $doktor['uzmanlik'] ?? null,
            'alt' => $doktor['kisa_bio'] ?? $doktor['slogan'] ?? '',
            'etiket' => $doktor['vitrin_badge'] ?? ($doktor['uzmanlik'] ?? 'Medikal Klinik'),
            'image' => $photo,
            'cta' => 'Randevu Al',
            'cta_url' => route('frontend.randevu'),
            'cta2' => 'Hizmetlerimiz',
            'cta2_url' => route('frontend.hizmetler'),
        ]];
    }
    $hizmetler = collect($doktor['hizmetler'] ?? [])
        ->filter(fn ($h) => is_array($h) && (filled($h['baslik'] ?? null) || filled($h['ad'] ?? null) || filled($h['id'] ?? null)))
        ->values()
        ->take(6);
    $bloglar = collect($doktor['bloglar'] ?? [])->take(3);
    $yorumlar = collect($doktor['yorumlar'] ?? [])->take(3);
    $galeri = collect($doktor['galeri'] ?? [])->take(6);
    $stats = collect($doktor['istatistikler'] ?? [])->take(4);
    $cs = $doktor['calisma_saatleri'] ?? [];
    $bolum = $doktor['anasayfa_bolumler'] ?? [];
    $ozellikler = collect($doktor['ozellikler'] ?? [])->take(3);
    if ($ozellikler->isEmpty()) {
        $ozellikler = collect([
            ['baslik' => 'Güvenilir yaklaşım', 'aciklama' => 'Kişiye özel değerlendirme ve güncel tıbbi yaklaşım.'],
            ['baslik' => 'Kolay randevu', 'aciklama' => 'Online randevu ile size uygun saati saniyeler içinde seçin.'],
            ['baslik' => 'Platform senkron', 'aciklama' => 'Randevu ve içerikler Randevu Ajandam ile senkron yönetilir.'],
        ]);
    }
    $ad = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $show = fn (string $key) => (bool) ($bolum[$key] ?? true);
@endphp

{{-- Hero slider (panel slider + API fallback) --}}
@if($show('slider'))
<section class="mp-hero" aria-label="Ana slider">
    <div class="swiper mp-hero-swiper">
        <div class="swiper-wrapper">
            @foreach ($slider as $slide)
                @php
                    $meta = is_array($slide['meta'] ?? null) ? $slide['meta'] : [];
                    $img = $slide['image'] ?? $slide['thumb'] ?? $photo;
                    $title = $slide['baslik'] ?? $ad;
                    $vurgulu = $slide['baslik_vurgulu'] ?? ($meta['baslik_vurgulu'] ?? null);
                    $alt = $slide['alt'] ?? '';
                    $etiket = $slide['etiket'] ?? ($slide['badge'] ?? ($doktor['vitrin_badge'] ?? null));
                    $cta = $slide['cta'] ?? 'Randevu Al';
                    $ctaUrl = $slide['cta_url'] ?? route('frontend.randevu');
                    $cta2 = $slide['cta2'] ?? null;
                    $cta2Url = $slide['cta2_url'] ?? null;
                    if ($ctaUrl === '/randevu') { $ctaUrl = route('frontend.randevu'); }
                    if ($cta2Url === '/hizmetler') { $cta2Url = route('frontend.hizmetler'); }
                    if ($cta2Url === '/hakkimda') { $cta2Url = route('frontend.hakkimda'); }
                    if ($cta2Url === '/iletisim') { $cta2Url = route('frontend.iletisim'); }
                @endphp
                <div class="swiper-slide">
                    <div class="mp-hero-slide">
                        <div class="mp-hero-bg" style="background-image:url('{{ $img }}')"></div>
                        <div class="mp-hero-overlay"></div>
                        <div class="mp-hero-inner">
                            @if($etiket)
                                <p class="mp-hero-eyebrow">{{ $etiket }}</p>
                            @endif
                            <h1>
                                {{ $title }}
                                @if($vurgulu)
                                    <em>{{ $vurgulu }}</em>
                                @endif
                            </h1>
                            @if($alt !== '' && $alt !== null)
                                <p class="mp-hero-lead">{{ \Illuminate\Support\Str::limit(strip_tags((string) $alt), 200) }}</p>
                            @endif
                            <div class="mp-hero-actions">
                                @if($cta && $ctaUrl)
                                    <a href="{{ $ctaUrl }}" class="mp-btn mp-btn-primary mp-btn-lg"
                                       @if(\Illuminate\Support\Str::startsWith((string)$ctaUrl, ['http://','https://'])) target="_blank" rel="noopener" @endif>
                                        {{ $cta }}
                                    </a>
                                @endif
                                @if($cta2 && $cta2Url)
                                    <a href="{{ $cta2Url }}" class="mp-btn mp-btn-white mp-btn-lg"
                                       @if(\Illuminate\Support\Str::startsWith((string)$cta2Url, ['http://','https://'])) target="_blank" rel="noopener" @endif>
                                        {{ $cta2 }}
                                    </a>
                                @elseif(!$cta2)
                                    <a href="{{ route('frontend.hizmetler') }}" class="mp-btn mp-btn-white mp-btn-lg">Hizmetlerimiz</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if(count($slider) > 1)
            <div class="mp-hero-pagination"></div>
            <button type="button" class="mp-hero-prev" aria-label="Önceki">‹</button>
            <button type="button" class="mp-hero-next" aria-label="Sonraki">›</button>
        @endif
    </div>
</section>
@endif

{{-- Feature strip --}}
<div class="mp-features">
    <div class="mp-feature">
        <div class="mp-feature-label">İletişim</div>
        <h3>Acil / Telefon</h3>
        <p>
            @if(!empty($doktor['telefon']))
                Bize <strong>{{ $doktor['telefon'] }}</strong> numarasından ulaşabilirsiniz.
            @else
                Randevu veya sorularınız için iletişime geçin.
            @endif
        </p>
        @if(!empty($doktor['telefon_raw']))
            <a href="tel:{{ $doktor['telefon_raw'] }}">Hemen ara →</a>
        @else
            <a href="{{ route('frontend.iletisim') }}">İletişim →</a>
        @endif
    </div>
    <div class="mp-feature">
        <div class="mp-feature-label">Program</div>
        <h3>Çalışma saatleri</h3>
        <ul>
            @forelse (collect($cs)->take(3) as $gun => $saat)
                <li><span>{{ $gun }}</span><span>{{ $saat }}</span></li>
            @empty
                <li><span>Randevu ile</span><span>Açık</span></li>
            @endforelse
        </ul>
        <a href="{{ route('frontend.randevu') }}" style="color:#fff;opacity:.95">Randevu planla →</a>
    </div>
    <div class="mp-feature">
        <div class="mp-feature-label">Konum</div>
        <h3>{{ $doktor['klinik_adi'] ?? 'Muayenehane' }}</h3>
        <p>{{ $doktor['adres'] ?? (($doktor['ilce'] ?? '').' '.($doktor['il'] ?? '')) ?: 'Adres bilgisi yakında.' }}</p>
        <a href="{{ route('frontend.iletisim') }}">Yol tarifi / harita →</a>
    </div>
</div>

{{-- Why / features --}}
<section class="mp-section">
    <div class="mp-container">
        <div class="mp-section-head">
            <span class="mp-eyebrow">Neden biz?</span>
            <h2>Sizin ve aileniz için her zaman hazırız</h2>
            <p>{{ $doktor['slogan'] ?? 'Kişiye özel plan, şeffaf iletişim ve kolay online randevu.' }}</p>
        </div>
        <div class="mp-why">
            <div class="mp-why-list">
                @foreach ($ozellikler as $i => $oz)
                    <div class="mp-why-item">
                        <div class="mp-why-icon">{{ ['✚','💊','🩺'][$i % 3] }}</div>
                        <div>
                            <h4>{{ $oz['baslik'] ?? 'Özellik' }}</h4>
                            <p>{{ $oz['aciklama'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mp-doctor-card" style="box-shadow:none;border:0;background:transparent">
                <div class="mp-about-photo">
                    <img src="{{ $photo }}" alt="{{ $ad }}">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats --}}
@if($show('istatistik') && $stats->isNotEmpty())
<section class="mp-stats">
    <div class="mp-stats-grid">
        @foreach ($stats as $st)
            <div class="mp-stat">
                <strong>{{ $st['deger'] ?? '' }}{{ $st['suffix'] ?? '' }}</strong>
                <span>{{ $st['etiket'] ?? '' }}</span>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- About --}}
<section class="mp-section mp-section-alt">
    <div class="mp-container">
        <div class="mp-about-grid">
            <div class="mp-about-photo">
                <img src="{{ $photo }}" alt="{{ $ad }}">
            </div>
            <div>
                <div class="mp-section-head" style="text-align:left;margin:0 0 20px;max-width:none">
                    <span class="mp-eyebrow">Hakkımızda</span>
                    <h2 style="text-align:left">{{ $doktor['bolum_basliklar']['hakkimda']['baslik'] ?? 'Kimiz ve nasıl çalışıyoruz?' }}</h2>
                </div>
                <p style="color:var(--muted);line-height:1.7;font-size:.95rem">
                    {{ $doktor['kisa_bio'] ?? strip_tags((string)($doktor['bio'] ?? '')) }}
                </p>
                @php
                    $checks = collect($doktor['bio_uzun'] ?? [])->take(4);
                    if ($checks->isEmpty() && !empty($doktor['mezuniyet']) && is_array($doktor['mezuniyet'])) {
                        $checks = collect($doktor['mezuniyet'])->take(4);
                    }
                @endphp
                @if($checks->isNotEmpty())
                    <ul class="mp-about-check">
                        @foreach ($checks as $c)
                            <li>{{ is_string($c) ? $c : (\Illuminate\Support\Str::limit(strip_tags((string)$c), 90)) }}</li>
                        @endforeach
                    </ul>
                @endif
                <div style="margin-top:22px;display:flex;flex-wrap:wrap;gap:10px">
                    <a href="{{ route('frontend.hakkimda') }}" class="mp-btn mp-btn-primary">Devamını oku</a>
                    <a href="{{ route('frontend.randevu') }}" class="mp-btn mp-btn-outline">Randevu al</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Emergency band --}}
@if(!empty($doktor['telefon']))
<section class="mp-emergency">
    <div class="mp-emergency-inner">
        <div>
            <h2>Acil destek mi lazım? Ara: {{ $doktor['telefon'] }}</h2>
            <p>Randevu, bilgi veya yönlendirme için bize ulaşın. Online randevu her zaman açık.</p>
        </div>
        <a href="tel:{{ $doktor['telefon_raw'] ?? '' }}" class="mp-btn mp-btn-white mp-btn-lg">Hemen Ara</a>
    </div>
</section>
@endif

{{-- Services (veri varsa her zaman göster; bölüm bayrağı yalnızca boşken gizler) --}}
@if($hizmetler->isNotEmpty())
<section class="mp-section" id="hizmetler">
    <div class="mp-container">
        <div class="mp-section-head">
            <span class="mp-eyebrow">Hizmetler</span>
            <h2>{{ filled($doktor['hizmetler_baslik'] ?? null) ? $doktor['hizmetler_baslik'] : 'Sağlığınız için sunduğumuz hizmetler' }}</h2>
            <p>{{ filled($doktor['hizmetler_alt'] ?? null) ? $doktor['hizmetler_alt'] : 'Aktif hizmetler platform paneli ile senkron listelenir.' }}</p>
        </div>
        <div class="mp-svc-grid">
            @foreach ($hizmetler as $h)
                @php
                    $hAd = $h['baslik'] ?? $h['ad'] ?? 'Hizmet';
                    $hSlug = $h['slug'] ?? \Illuminate\Support\Str::slug($hAd);
                    $hDesc = \Illuminate\Support\Str::limit(strip_tags((string)($h['kisa'] ?? $h['aciklama'] ?? '')), 120);
                @endphp
                <a href="{{ route('frontend.hizmet.detay', $hSlug ?: ($h['id'] ?? '')) }}" class="mp-svc-card">
                    @if(!empty($h['image']))
                        <img src="{{ $h['image'] }}" alt="{{ $hAd }}" class="mp-svc-thumb" loading="lazy">
                    @else
                        <div class="mp-svc-icon">✚</div>
                    @endif
                    <h3>{{ $hAd }}</h3>
                    @if($hDesc !== '')
                        <p>{{ $hDesc }}</p>
                    @else
                        <p>Detay ve randevu için tıklayın.</p>
                    @endif
                    <div class="mp-svc-meta">
                        @if(!empty($h['sure']))
                            <span class="mp-chip">⏱ {{ $h['sure'] }}</span>
                        @endif
                        @if(!empty($h['fiyat']))
                            <span class="mp-chip">{{ $h['fiyat'] }}</span>
                        @endif
                    </div>
                    <span class="mp-svc-link">Detay &amp; randevu →</span>
                </a>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:28px">
            <a href="{{ route('frontend.hizmetler') }}" class="mp-btn mp-btn-outline">Tüm hizmetler</a>
            <a href="{{ route('frontend.randevu') }}" class="mp-btn mp-btn-primary" style="margin-left:8px">Randevu Al</a>
        </div>
    </div>
</section>
@endif

{{-- Doctor --}}
<section class="mp-section">
    <div class="mp-container">
        <div class="mp-section-head">
            <span class="mp-eyebrow">Hekim</span>
            <h2>Uzman hekimle tanışın</h2>
            <p>{{ $doktor['uzmanlik'] ?? 'Kişiye özel sağlık planı' }}</p>
        </div>
        <div class="mp-doctor-card">
            <div class="mp-doctor-photo">
                <img src="{{ $photo }}" alt="{{ $ad }}">
            </div>
            <div class="mp-doctor-body">
                <div class="mp-eyebrow">{{ $doktor['uzmanlik'] ?? 'Uzman hekim' }}</div>
                <h2>{{ $ad }}</h2>
                <p>{{ $doktor['kisa_bio'] ?? '' }}</p>
                @if(!empty($doktor['branslar']) && is_array($doktor['branslar']))
                    <div class="mp-svc-meta" style="margin:14px 0">
                        @foreach (array_slice($doktor['branslar'], 0, 5) as $b)
                            <span class="mp-chip">{{ is_string($b) ? $b : ($b['ad'] ?? '') }}</span>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('frontend.randevu') }}" class="mp-btn mp-btn-primary">Randevu Al</a>
            </div>
        </div>
    </div>
</section>

{{-- Gallery --}}
@if($show('galeri') && $galeri->isNotEmpty())
<section class="mp-section mp-section-alt">
    <div class="mp-container">
        <div class="mp-section-head">
            <span class="mp-eyebrow">Galeri</span>
            <h2>Klinik ve ortamdan kareler</h2>
            <p>Panelden yüklenen galeri görselleri.</p>
        </div>
        <div class="mp-gal-grid">
            @foreach ($galeri as $g)
                <figure class="mp-gal-item">
                    <img src="{{ $g['image'] ?? '' }}" alt="{{ $g['baslik'] ?? 'Galeri' }}" loading="lazy">
                    @if(!empty($g['baslik']))
                        <figcaption>{{ $g['baslik'] }}</figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:24px">
            <a href="{{ route('frontend.galeri') }}" class="mp-btn mp-btn-outline">Tüm galeri</a>
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
@if($show('yorumlar') && $yorumlar->isNotEmpty())
<section class="mp-section">
    <div class="mp-container">
        <div class="mp-section-head">
            <span class="mp-eyebrow">Yorumlar</span>
            <h2>Hastalarımız ne diyor?</h2>
            <p>Onaylanmış değerlendirmeler platformdan senkron gelir.</p>
        </div>
        <div class="mp-testimonials">
            @foreach ($yorumlar as $y)
                <div class="mp-testi">
                    <div class="mp-testi-stars">
                        @for ($i = 0; $i < max(1, min(5, (int)($y['puan'] ?? 5))); $i++)★@endfor
                    </div>
                    <p>“{{ \Illuminate\Support\Str::limit(strip_tags((string)($y['metin'] ?? '')), 160) }}”</p>
                    <strong>{{ $y['ad'] ?? 'Hasta' }}</strong>
                    <small>{{ $y['hizmet'] ?? 'Değerlendirme' }}</small>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Blog --}}
@if($show('blog') && $bloglar->isNotEmpty())
<section class="mp-section mp-section-alt">
    <div class="mp-container">
        <div class="mp-section-head">
            <span class="mp-eyebrow">Blog</span>
            <h2>Güncel yazılar ve bilgilendirmeler</h2>
            <p>Hekim panelinden yayınlanan içerikler.</p>
        </div>
        <div class="mp-blog-grid">
            @foreach ($bloglar as $b)
                <a href="{{ route('frontend.blog.detay', $b['slug'] ?? '') }}" class="mp-blog-card">
                    <img src="{{ $b['image'] ?? 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=800&q=80' }}" alt="" loading="lazy">
                    <div class="mp-blog-body">
                        @if(!empty($b['tarih']))
                            <div class="mp-blog-date">{{ $b['tarih'] }}</div>
                        @endif
                        <h3>{{ $b['baslik'] ?? '' }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags((string)($b['ozet'] ?? '')), 100) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:28px">
            <a href="{{ route('frontend.blog') }}" class="mp-btn mp-btn-outline">Tüm yazılar</a>
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="mp-cta-band">
    <div class="mp-cta-card">
        <span class="mp-eyebrow" style="color:var(--mp-blue);font-weight:600;font-size:.8rem;letter-spacing:.06em;text-transform:uppercase">Randevu</span>
        <h2>{{ $doktor['cta_baslik'] ?? 'Randevunuzu şimdi planlayın' }}</h2>
        <p>{{ $doktor['cta_metin'] ?? 'Hizmet seçin, müsait saati belirleyin — kayıt zorunlu değil.' }}</p>
        <a href="{{ route('frontend.randevu') }}" class="mp-btn mp-btn-primary mp-btn-lg">Randevu Al</a>
    </div>
</section>
@endsection
