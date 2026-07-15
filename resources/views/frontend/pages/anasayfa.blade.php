@extends(theme_layout())

@section('baslik', trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim').' | '.($doktor['uzmanlik'] ?? 'Klinik').(!empty($doktor['il']) ? ' · '.$doktor['il'] : '')))
@section('meta_aciklama', $doktor['kisa_bio'] ?? '')

@section('icerik')
@php
    $slider = $doktor['slider'] ?? [];
    $photo = $doktor['profil_resmi']
        ?? 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=1000&q=80';
    $bolum = $doktor['anasayfa_bolumler'] ?? [];
    $basliklar = $doktor['bolum_basliklar'] ?? [];
    $sira = $doktor['anasayfa_sira'] ?? [
        'slider', 'istatistik', 'ozellikler', 'hakkimda', 'hizmetler',
        'surec', 'galeri', 'yorumlar', 'blog', 'cta',
    ];
    // slider her zaman en üstte veya sira listesindeki yerinde
    if (! in_array('slider', $sira, true)) {
        array_unshift($sira, 'slider');
    }
@endphp

@foreach ($sira as $sectionKey)
    @if(!($bolum[$sectionKey] ?? true))
        @continue
    @endif

    @switch($sectionKey)
        @case('slider')
            @php
                // yilmazkiran tarzı: 2 kolon hero + float kart + stats
                if (empty($slider)) {
                    $statsFallback = collect($doktor['istatistikler'] ?? [])->take(3)->map(fn ($s) => [
                        'sayi' => (int) preg_replace('/\D/', '', (string) ($s['deger'] ?? 0)),
                        'suffix' => $s['suffix'] ?? '',
                        'etiket' => $s['etiket'] ?? '',
                    ])->filter(fn ($s) => ($s['etiket'] ?? '') !== '')->values()->all();
                    $slider = [[
                        'baslik' => trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')),
                        'baslik_vurgulu' => $doktor['uzmanlik'] ?? 'Güvenilir hekimlik',
                        'alt' => $doktor['kisa_bio'] ?? $doktor['slogan'] ?? 'Kişiye özel değerlendirme ve modern yaklaşım.',
                        'etiket' => $doktor['vitrin_badge'] ?? 'Uzman hekim',
                        'badge' => $doktor['il'] ?? null,
                        'image' => $photo,
                        'thumb' => $photo,
                        'cta' => 'Online Randevu',
                        'cta_url' => route('frontend.randevu'),
                        'cta2' => 'Hakkımda',
                        'cta2_url' => route('frontend.hakkimda'),
                        'istatistikler' => $statsFallback,
                        'float_1_baslik' => $doktor['uzmanlik'] ?? 'Uzmanlık',
                        'float_1_aciklama' => 'Alanında deneyim',
                        'float_2_baslik' => 'Online randevu',
                        'float_2_aciklama' => 'Hızlı planlama',
                    ]];
                }
                $slideTotal = count($slider);
                $defaultStats = collect($doktor['istatistikler'] ?? [])->take(3)->map(fn ($s) => [
                    'sayi' => (int) preg_replace('/\D/', '', (string) ($s['deger'] ?? 0)),
                    'suffix' => $s['suffix'] ?? '',
                    'etiket' => $s['etiket'] ?? '',
                ])->filter(fn ($s) => ($s['etiket'] ?? '') !== '')->values()->all();
            @endphp
            <section class="yk-hero" aria-label="Ana slider">
                <div class="swiper yk-hero-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($slider as $i => $slide)
                            @php
                                $meta = is_array($slide['meta'] ?? null) ? $slide['meta'] : [];
                                $baslikVurgulu = $slide['baslik_vurgulu'] ?? ($meta['baslik_vurgulu'] ?? null);
                                $stats = $slide['istatistikler'] ?? ($meta['istatistikler'] ?? $defaultStats);
                                $f1b = $slide['float_1_baslik'] ?? ($meta['float_1_baslik'] ?? null);
                                $f1a = $slide['float_1_aciklama'] ?? ($meta['float_1_aciklama'] ?? null);
                                $f2b = $slide['float_2_baslik'] ?? ($meta['float_2_baslik'] ?? null);
                                $f2a = $slide['float_2_aciklama'] ?? ($meta['float_2_aciklama'] ?? null);
                                if (! $f1b && ! empty($doktor['uzmanlik'])) {
                                    $f1b = $doktor['uzmanlik'];
                                    $f1a = $f1a ?: 'Uzmanlık alanı';
                                }
                                if (! $f2b) {
                                    $f2b = 'Randevu';
                                    $f2a = $f2a ?: 'Online talep';
                                }
                            @endphp
                            <div class="swiper-slide">
                                <div class="yk-hero-slide">
                                    <div class="container yk-hero-grid">
                                        <div class="yk-hero-content">
                                            @if(!empty($slide['etiket']) || !empty($slide['badge']))
                                                <div class="yk-hero-badge">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l2.4 7.2H22l-6 4.8 2.3 7L12 16.8 5.7 21l2.3-7-6-4.8h7.6L12 2z"/></svg>
                                                    {{ $slide['etiket'] ?? $slide['badge'] }}
                                                    @if(!empty($slide['etiket']) && !empty($slide['badge']))
                                                        <span class="yk-hero-badge-sep">·</span> {{ $slide['badge'] }}
                                                    @endif
                                                </div>
                                            @endif

                                            <h1 class="yk-hero-title">
                                                {{ $slide['baslik'] ?? '' }}
                                                @if($baslikVurgulu)
                                                    <br><span>{{ $baslikVurgulu }}</span>
                                                @endif
                                            </h1>

                                            @if(!empty($slide['alt']))
                                                <p class="yk-hero-desc">{{ $slide['alt'] }}</p>
                                            @endif

                                            <div class="yk-hero-buttons">
                                                @if(!empty($slide['cta']) && !empty($slide['cta_url']))
                                                    <a href="{{ $slide['cta_url'] }}" class="yk-btn yk-btn-gold"
                                                       @if(\Illuminate\Support\Str::startsWith($slide['cta_url'], ['http://','https://'])) target="_blank" rel="noopener" @endif>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                                                        {{ $slide['cta'] }}
                                                    </a>
                                                @endif
                                                @if(!empty($slide['cta2']) && !empty($slide['cta2_url']))
                                                    <a href="{{ $slide['cta2_url'] }}" class="yk-btn yk-btn-outline"
                                                       @if(\Illuminate\Support\Str::startsWith($slide['cta2_url'], ['http://','https://'])) target="_blank" rel="noopener" @endif>
                                                        {{ $slide['cta2'] }}
                                                    </a>
                                                @endif
                                            </div>

                                            @if(!empty($stats) && is_array($stats))
                                                <div class="yk-hero-stats">
                                                    @foreach (array_slice($stats, 0, 3) as $ist)
                                                        <div class="yk-hero-stat">
                                                            <div class="yk-hero-stat-number"
                                                                 data-count="{{ (int) ($ist['sayi'] ?? $ist['deger'] ?? 0) }}"
                                                                 data-suffix="{{ $ist['suffix'] ?? '' }}">0</div>
                                                            <div class="yk-hero-stat-label">{{ $ist['etiket'] ?? '' }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="yk-hero-visual">
                                            <div class="yk-hero-image-wrap">
                                                <div class="yk-hero-image">
                                                    <img src="{{ $slide['image'] ?? $photo }}" alt="{{ $slide['baslik'] ?? '' }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                                </div>
                                                <div class="yk-hero-image-border"></div>

                                                @if($f1b)
                                                    <div class="yk-float-card yk-float-1">
                                                        <div class="yk-float-icon">
                                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l2.4 7.2H22l-6 4.8 2.3 7L12 16.8 5.7 21l2.3-7-6-4.8h7.6L12 2z"/></svg>
                                                        </div>
                                                        <div class="yk-float-text">
                                                            <strong>{{ $f1b }}</strong>
                                                            @if($f1a)<span>{{ $f1a }}</span>@endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($f2b)
                                                    <div class="yk-float-card yk-float-2">
                                                        <div class="yk-float-icon">
                                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                        </div>
                                                        <div class="yk-float-text">
                                                            <strong>{{ $f2b }}</strong>
                                                            @if($f2a)<span>{{ $f2a }}</span>@endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($slideTotal > 1)
                        <div class="yk-hero-nav">
                            <button type="button" class="yk-hero-prev" aria-label="Önceki">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                            </button>
                            <button type="button" class="yk-hero-next" aria-label="Sonraki">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                            </button>
                        </div>
                        <div class="yk-hero-pagination"></div>
                    @endif
                </div>
            </section>
            @break

        @case('istatistik')
            @if(!empty($doktor['istatistikler']))
            <section class="stats-bar">
                <div class="container">
                    <div class="stats-panel">
                        @foreach (($doktor['istatistikler'] ?? []) as $stat)
                            <div class="stat-item">
                                <div class="stat-value"
                                     data-counter="{{ $stat['deger'] }}"
                                     data-suffix="{{ $stat['suffix'] ?? '' }}">0</div>
                                <div class="stat-label">{{ $stat['etiket'] }}</div>
                                <div class="stat-desc">{{ $stat['aciklama'] ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
            @break

        @case('ozellikler')
            @if(!empty($doktor['ozellikler']))
            <section class="section-tight">
                <div class="container">
                    @if(!empty($basliklar['ozellikler']['baslik']) || !empty($basliklar['ozellikler']['alt']))
                        <div class="section-head reveal" style="margin-bottom:1.25rem">
                            <div>
                                @if(!empty($basliklar['ozellikler']['baslik']))
                                    <h2 class="section-title" style="font-size:1.75rem">{{ $basliklar['ozellikler']['baslik'] }}</h2>
                                @endif
                                @if(!empty($basliklar['ozellikler']['alt']))
                                    <p class="section-sub">{{ $basliklar['ozellikler']['alt'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="features reveal">
                        @foreach ($doktor['ozellikler'] as $i => $oz)
                            <div class="feature">
                                <div class="feature-icon">0{{ $i + 1 }}</div>
                                <h3>{{ $oz['baslik'] }}</h3>
                                <p>{{ $oz['aciklama'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
            @break

        @case('hakkimda')
            <section class="section bg-white">
                <div class="container two-col reveal">
                    <div class="media-frame">
                        <img src="{{ $photo }}" alt="{{ $doktor['ad_soyad'] ?? 'Hekim' }}">
                        <div class="media-badge">
                            <strong>{{ $doktor['unvan'] ?? '' }} {{ $doktor['ad_soyad'] ?? '' }}</strong>
                            <span>{{ $doktor['uzmanlik'] ?? '' }}@if(!empty($doktor['il'])) · {{ $doktor['il'] }}@endif</span>
                        </div>
                    </div>
                    <div>
                        <span class="eyebrow">Hakkımda</span>
                        <h2 class="section-title">{{ $basliklar['hakkimda']['baslik'] ?? $doktor['slogan'] ?? 'Uzman hekimlik, güvenilir yaklaşım' }}</h2>
                        @if(!empty($basliklar['hakkimda']['alt']))
                            <p class="section-sub">{{ $basliklar['hakkimda']['alt'] }}</p>
                        @endif
                        @if(!empty($doktor['branslar']))
                            <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin:1rem 0">
                                @foreach ($doktor['branslar'] as $br)
                                    <span class="chip">{{ $br }}</span>
                                @endforeach
                            </div>
                        @endif
                        <div class="prose mt-2">
                            <p>{{ $doktor['bio'] ?? $doktor['kisa_bio'] ?? '' }}</p>
                        </div>
                        <div class="hero-actions mt-3">
                            <a href="{{ route('frontend.hakkimda') }}" class="btn btn-primary">Detaylı Özgeçmiş</a>
                            <a href="{{ route('frontend.randevu') }}" class="btn btn-dark-outline">Randevu Planla</a>
                        </div>
                    </div>
                </div>
            </section>
            @break

        @case('hizmetler')
            @if(!empty($doktor['hizmetler']))
            <section class="section">
                <div class="container">
                    <div class="section-head reveal">
                        <div>
                            <span class="eyebrow">Hizmetler</span>
                            <h2 class="section-title">{{ $doktor['hizmetler_baslik'] ?: ($basliklar['hizmetler']['baslik'] ?? 'Sunduğum hizmetler') }}</h2>
                            <p class="section-sub">{{ $doktor['hizmetler_alt'] ?: ($basliklar['hizmetler']['alt'] ?? (($doktor['uzmanlik'] ?? 'Uzmanlık alanım').' kapsamında randevu alabileceğiniz aktif hizmetler.')) }}</p>
                        </div>
                        <div style="display:flex;gap:.5rem;align-items:center">
                            <button type="button" class="swiper-button-prev svc-prev" aria-label="Önceki"></button>
                            <button type="button" class="swiper-button-next svc-next" aria-label="Sonraki"></button>
                            <a href="{{ route('frontend.hizmetler') }}" class="link-more">Tümü →</a>
                        </div>
                    </div>

                    <div class="swiper services-swiper reveal">
                        <div class="swiper-wrapper">
                            @foreach ($doktor['hizmetler'] as $hizmet)
                                @php $hSlug = $hizmet['slug'] ?? \Illuminate\Support\Str::slug($hizmet['baslik'] ?? ''); @endphp
                                <div class="swiper-slide">
                                    <article class="card service-card">
                                        <a href="{{ route('frontend.hizmet.detay', $hSlug) }}" style="display:block">
                                            <img src="{{ $hizmet['image'] }}" alt="{{ $hizmet['baslik'] }}" loading="lazy">
                                        </a>
                                        <div class="card-pad" style="flex:1;display:flex;flex-direction:column">
                                            <h3><a href="{{ route('frontend.hizmet.detay', $hSlug) }}" style="color:inherit;text-decoration:none">{{ $hizmet['baslik'] }}</a></h3>
                                            <p class="text-muted" style="margin:0;font-size:.92rem">{{ $hizmet['kisa'] }}</p>
                                            <div class="service-meta">
                                                @if(!empty($hizmet['sure']))
                                                    <span class="chip">{{ $hizmet['sure'] }}</span>
                                                @endif
                                                @if(!empty($hizmet['fiyat']))
                                                    <span class="chip chip-gold">{{ $hizmet['fiyat'] }}</span>
                                                @endif
                                            </div>
                                            @if(!empty($hizmet['madde']))
                                                <ul class="service-list" style="margin-bottom:1rem">
                                                    @foreach (array_slice($hizmet['madde'], 0, 3) as $m)
                                                        <li>{{ $m }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            <a href="{{ route('frontend.hizmet.detay', $hSlug) }}" class="link-more" style="margin-top:auto">Detay & randevu →</a>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            @endif
            @break

        @case('surec')
            @if(!empty($doktor['surec']))
            <section class="section bg-white">
                <div class="container">
                    <div class="section-head reveal">
                        <div>
                            <span class="eyebrow">Süreç</span>
                            <h2 class="section-title">{{ $basliklar['surec']['baslik'] ?? 'Randevudan takibe adım adım' }}</h2>
                            @if(!empty($basliklar['surec']['alt']))
                                <p class="section-sub">{{ $basliklar['surec']['alt'] }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="process reveal">
                        @foreach ($doktor['surec'] as $adim)
                            <div class="process-step">
                                <div class="num">{{ $adim['adim'] }}</div>
                                <h3>{{ $adim['baslik'] }}</h3>
                                <p>{{ $adim['aciklama'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
            @break

        @case('galeri')
            @if(!empty($doktor['galeri']))
            <section class="section">
                <div class="container">
                    <div class="section-head reveal">
                        <div>
                            <span class="eyebrow">Klinik</span>
                            <h2 class="section-title">{{ $basliklar['galeri']['baslik'] ?? 'Galeri' }}</h2>
                        </div>
                        <a href="{{ route('frontend.galeri') }}" class="link-more">Tüm galeri →</a>
                    </div>
                    <div class="gallery-grid reveal">
                        @foreach (array_slice($doktor['galeri'], 0, 6) as $g)
                            <figure class="gallery-item">
                                <img src="{{ $g['image'] }}" alt="{{ $g['baslik'] }}" loading="lazy">
                                <figcaption>
                                    <span>{{ $g['etiket'] ?? 'Klinik' }}</span>
                                    <strong>{{ $g['baslik'] }}</strong>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
            @break

        @case('yorumlar')
            @if(!empty($doktor['yorumlar']))
            <section class="section bg-white">
                <div class="container">
                    <div class="section-head reveal">
                        <div>
                            <span class="eyebrow">Görüşler</span>
                            <h2 class="section-title">{{ $basliklar['yorumlar']['baslik'] ?? 'Danışan değerlendirmeleri' }}</h2>
                        </div>
                    </div>
                    <div class="swiper reviews-swiper reveal">
                        <div class="swiper-wrapper">
                            @foreach ($doktor['yorumlar'] as $y)
                                <div class="swiper-slide">
                                    <article class="card" style="padding:1.5rem;height:100%">
                                        <div style="color:#C96A2B;letter-spacing:2px;margin-bottom:.5rem">
                                            {!! str_repeat('★', max(1, min(5, (int)($y['puan'] ?? 5)))) !!}
                                        </div>
                                        <p style="margin:0 0 1rem;line-height:1.7;color:#374151">“{{ $y['metin'] }}”</p>
                                        <strong>{{ $y['ad'] }}</strong>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            @endif
            @break

        @case('blog')
            @if(!empty($doktor['bloglar']))
            <section class="section">
                <div class="container">
                    <div class="section-head reveal">
                        <div>
                            <span class="eyebrow">Blog</span>
                            <h2 class="section-title">{{ $basliklar['blog']['baslik'] ?? 'Sağlık yazıları' }}</h2>
                        </div>
                        <a href="{{ route('frontend.blog') }}" class="link-more">Tüm yazılar →</a>
                    </div>
                    <div class="blog-grid reveal">
                        @foreach (array_slice($doktor['bloglar'], 0, 3) as $yazi)
                            <article class="card blog-card">
                                <a href="{{ route('frontend.blog.detay', $yazi['slug']) }}">
                                    <img src="{{ $yazi['image'] }}" alt="{{ $yazi['baslik'] }}" loading="lazy">
                                </a>
                                <div class="card-pad">
                                    <div class="blog-meta">
                                        <span>{{ $yazi['tarih'] ?? '' }}</span>
                                        <span>{{ $yazi['okuma'] ?? '' }}</span>
                                    </div>
                                    <h3><a href="{{ route('frontend.blog.detay', $yazi['slug']) }}">{{ $yazi['baslik'] }}</a></h3>
                                    <p class="text-muted">{{ $yazi['ozet'] ?? '' }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
            @break

        @case('cta')
            <section class="section">
                <div class="container">
                    <div class="cta-band reveal">
                        <div>
                            <span class="eyebrow" style="color:#FCD34D">Randevu</span>
                            <h2 class="section-title" style="color:#fff;margin:0">
                                {{ $doktor['cta_baslik'] ?: ($basliklar['cta']['baslik'] ?? (($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? '').' ile görüşmek ister misiniz?')) }}
                            </h2>
                            <p style="color:rgba(255,255,255,.78);margin:.75rem 0 0;max-width:36rem">
                                {{ $doktor['cta_metin'] ?: ($basliklar['cta']['alt'] ?? (($doktor['adres'] ?? '').(!empty($doktor['telefon']) ? ' · '.$doktor['telefon'] : ''))) }}
                            </p>
                        </div>
                        <div class="hero-actions">
                            <a href="{{ route('frontend.randevu') }}" class="btn btn-gold">Online Randevu Al</a>
                            <a href="tel:{{ $doktor['telefon_raw'] ?? '' }}" class="btn btn-outline">Hemen Ara</a>
                        </div>
                    </div>
                </div>
            </section>
            @break
    @endswitch
@endforeach
@endsection
