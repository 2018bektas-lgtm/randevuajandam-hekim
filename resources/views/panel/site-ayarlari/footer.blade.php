@extends('panel.layouts.app')
@section('baslik', 'Site Ayarları · Footer')
@section('sayfa_baslik', 'Site Ayarları · Footer')

@section('icerik')
@include('panel.site-ayarlari._shell')

@php
    $pageGroups = $pageGroups ?? ['system' => $pageOptions ?? [], 'pages' => []];

    $tasarimlar = $tasarimlar ?? [];
    $aktifTasarim = $aktifTasarim ?? '';
    $fa = $footerAyar ?? [];
    $tema = $tema ?? ['id' => 'tema-1', 'ad' => 'Tema'];
    $footerGrup = $footerGrup ?? 'hipno';
    $logoSecenekleri = $logoSecenekleri ?? [];
    $logoTip = $fa['footer_logo_tip'] ?? 'site';

    // Aktif tasarımın desteklediği bloklar → desteklenmeyen ayarlar pasifleşir
    $destek = (array) ($tasarimlar[$aktifTasarim]['destek'] ?? []);

    $bloklar = [
        'footer_cta_goster' => ['ad' => 'Randevu şeridi', 'desc' => 'Footer üstünde başlık + telefon/randevu butonları', 'blok' => 'cta'],
        'footer_hakkinda_goster' => ['ad' => 'Tanıtım metni', 'desc' => 'Logo altındaki kısa açıklama paragrafı', 'blok' => 'hakkinda'],
        'footer_kesfet_goster' => ['ad' => 'Keşfet linkleri', 'desc' => 'Aşağıdaki footer link listesi', 'blok' => 'kesfet'],
        'footer_iletisim_goster' => ['ad' => 'İletişim bilgileri', 'desc' => 'Adres, telefon, e-posta', 'blok' => 'iletisim'],
        'footer_saatler_goster' => ['ad' => 'Çalışma saatleri', 'desc' => 'İletişim bloğunda saat özeti', 'blok' => 'saatler'],
        'footer_sosyal_goster' => ['ad' => 'Sosyal medya ikonları', 'desc' => 'Profil ayarlarındaki hesaplar', 'blok' => 'sosyal'],
        'footer_randevu_goster' => ['ad' => 'Randevu sütunu', 'desc' => 'Sağdaki randevu kutusu / butonu', 'blok' => 'randevu'],
        'footer_sayfalar_goster' => ['ad' => 'Yasal sayfa linkleri', 'desc' => 'Sayfalar → “Footer’da göster” işaretli sayfalar', 'blok' => 'sayfalar'],
        'footer_marka_goster' => ['ad' => '“Randevu Ajandam” ibaresi', 'desc' => 'Alt şeritteki platform bağlantısı', 'blok' => 'marka'],
    ];
@endphp

{{-- ============================================================
     1) TEMA BAZLI FOOTER TASARIMI + AYARLARI
     ============================================================ --}}
<form method="POST" action="{{ route('panel.site-ayarlari.footer.tasarim') }}"
      enctype="multipart/form-data" class="sa-wrap mb-5">
    @csrf

    <div class="sa-card mb-4">
        <div class="sa-card-head !items-center">
            <div class="min-w-0">
                <h3>Footer tasarımı</h3>
                <p class="sa-hint">
                    Tasarımlar <strong>temaya göre</strong> listelenir — şu an aktif tema:
                    <strong>{{ $tema['ad'] ?? $tema['id'] }}</strong> ({{ $footerGrup }} paketi).
                    Tema değiştirirseniz o temanın kendi footer tasarımlarını seçersiniz; her temanın seçimi ayrı saklanır.
                </p>
            </div>
            <span class="sa-badge shrink-0">{{ count($tasarimlar) }} tasarım</span>
        </div>

        <div class="sa-card-body">
            <div class="ft-kartlar">
                @foreach($tasarimlar as $kod => $t)
                    @php $secili = $kod === $aktifTasarim; @endphp
                    <label class="ft-kart {{ $secili ? 'is-on' : '' }}">
                        <input type="radio" name="footer_tasarim" value="{{ $kod }}" @checked($secili) class="ft-kart__radio">

                        {{-- Mini yerleşim önizlemesi --}}
                        <span class="ft-onizleme ft-onizleme--{{ $t['ton'] ?? 'acik' }}">
                            @foreach((array) ($t['onizleme'] ?? []) as $blok)
                                @switch($blok)
                                    @case('cta')
                                        <span class="ft-p ft-p--cta"><i></i><b></b></span>
                                        @break
                                    @case('logo')
                                        <span class="ft-p ft-p--logo"><i></i></span>
                                        @break
                                    @case('logo-orta')
                                        <span class="ft-p ft-p--logo-orta"><i></i><u></u></span>
                                        @break
                                    @case('iki-kolon')
                                        <span class="ft-p ft-p--kol ft-p--k2"><s></s><s></s></span>
                                        @break
                                    @case('uc-kolon')
                                        <span class="ft-p ft-p--kol ft-p--k3"><s></s><s></s><s></s></span>
                                        @break
                                    @case('dort-kolon')
                                        <span class="ft-p ft-p--kol ft-p--k4"><s></s><s></s><s></s><s></s></span>
                                        @break
                                    @case('tek-kolon')
                                        <span class="ft-p ft-p--tek"><u></u><u></u></span>
                                        @break
                                    @case('alt')
                                        <span class="ft-p ft-p--alt"></span>
                                        @break
                                @endswitch
                            @endforeach
                        </span>

                        <span class="ft-kart__govde">
                            <strong>{{ $t['ad'] ?? $kod }}</strong>
                            <span class="ft-kart__desc">{{ $t['aciklama'] ?? '' }}</span>
                        </span>
                        <span class="ft-kart__tik" aria-hidden="true">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <div class="sa-layout">
        {{-- Logo alanı + metinler --}}
        <div class="space-y-4">
            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3>Logo alanı</h3>
                        <p class="sa-hint">Footer’da hangi marka görselinin kullanılacağı.</p>
                    </div>
                    <span class="sa-badge">Marka</span>
                </div>
                <div class="sa-card-body">
                    <div class="sa-field">
                        <label class="sa-label">Logo kaynağı</label>
                        <select name="footer_logo_tip" id="footerLogoTip" class="sa-input sa-select">
                            @foreach($logoSecenekleri as $deger => $etiket)
                                <option value="{{ $deger }}" @selected($logoTip === $deger)>{{ $etiket }}</option>
                            @endforeach
                        </select>
                        <p class="sa-help">
                            “Site logosu” seçiliyse
                            <a href="{{ route('panel.site-ayarlari.genel') }}" class="font-semibold text-brand-600 underline">Genel</a>
                            sekmesindeki logo kullanılır{{ $siteLogoUrl ? '' : ' — şu an yüklü site logosu yok, yazı görünür' }}.
                        </p>
                    </div>

                    <div class="sa-field" id="footerLogoOzelAlan" style="{{ $logoTip === 'ozel' ? '' : 'display:none' }}">
                        <div class="sa-upload">
                            <label class="sa-label">Footer’a özel logo</label>
                            <div class="sa-upload-preview" id="ftLogoBox">
                                @if(!empty($fa['footer_logo_url']))
                                    <img src="{{ $fa['footer_logo_url'] }}" alt="Footer logo" id="ftLogoImg" class="sa-upload-img sa-upload-img-logo">
                                @else
                                    <div class="sa-upload-ph" id="ftLogoPh">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Footer logosu yok</span>
                                    </div>
                                    <img src="" alt="" id="ftLogoImg" class="sa-upload-img sa-upload-img-logo hidden">
                                @endif
                            </div>
                            <input type="file" name="footer_logo" accept="image/png,image/jpeg,image/webp,image/gif"
                                   class="sa-file" onchange="saPreviewFile(this, 'ftLogoImg', 'ftLogoPh')">
                            <p class="sa-help">Max 4 MB · koyu zeminli tasarımlarda açık renkli logo kullanın.</p>
                            @if(!empty($fa['footer_logo_url']))
                                <label class="inline-flex items-center gap-2 mt-2 text-xs font-semibold text-red-600 cursor-pointer">
                                    <input type="checkbox" name="footer_logo_sil" value="1" class="rounded border-slate-300 text-red-600">
                                    Footer logosunu sil
                                </label>
                            @endif
                        </div>
                    </div>

                    <div class="sa-field" id="footerLogoBoyutAlan" style="{{ in_array($logoTip, ['site', 'ozel'], true) ? '' : 'display:none' }}">
                        <label class="sa-label">Logo yüksekliği (px)</label>
                        <input type="number" name="footer_logo_yukseklik" min="20" max="140"
                               value="{{ (int) ($fa['footer_logo_yukseklik'] ?? 52) }}" class="sa-input">
                        <p class="sa-help">20–140 px arası. Varsayılan 52.</p>
                    </div>
                </div>
            </div>

            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3>Footer metinleri</h3>
                        <p class="sa-hint">Boş bıraktığınız alanlar otomatik doldurulur.</p>
                    </div>
                    <span class="sa-badge">İçerik</span>
                </div>
                <div class="sa-card-body">
                    <div class="sa-field">
                        <label class="sa-label">Tanıtım metni</label>
                        <textarea name="footer_aciklama" rows="3" class="sa-textarea" maxlength="400"
                                  placeholder="Boş bırakırsanız kısa biyografiniz kullanılır">{{ $fa['footer_aciklama'] ?? '' }}</textarea>
                    </div>
                    <div class="sa-field">
                        <label class="sa-label">Randevu şeridi başlığı</label>
                        <input type="text" name="footer_cta_baslik" class="sa-input"
                               value="{{ $fa['footer_cta_baslik'] ?? '' }}" placeholder="Randevu almaya hazır mısınız?">
                    </div>
                    <div class="sa-grid-2">
                        <div class="sa-field">
                            <label class="sa-label">“Keşfet” başlığı</label>
                            <input type="text" name="footer_baslik_kesfet" class="sa-input"
                                   value="{{ $fa['footer_baslik_kesfet'] ?? '' }}" placeholder="Keşfet">
                        </div>
                        <div class="sa-field">
                            <label class="sa-label">“İletişim” başlığı</label>
                            <input type="text" name="footer_baslik_iletisim" class="sa-input"
                                   value="{{ $fa['footer_baslik_iletisim'] ?? '' }}" placeholder="İletişim">
                        </div>
                        <div class="sa-field">
                            <label class="sa-label">Sosyal medya başlığı</label>
                            <input type="text" name="footer_baslik_sosyal" class="sa-input"
                                   value="{{ $fa['footer_baslik_sosyal'] ?? '' }}" placeholder="Bizi takip edin">
                        </div>
                        <div class="sa-field">
                            <label class="sa-label">Telif satırı</label>
                            <input type="text" name="footer_telif" class="sa-input" maxlength="200"
                                   value="{{ $fa['footer_telif'] ?? '' }}" placeholder="© {yil} {ad} · Tüm hakları saklıdır.">
                            <p class="sa-help"><code>{yil}</code> ve <code>{ad}</code> otomatik değiştirilir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bloklar --}}
        <div class="space-y-4">
            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3>Görünen bloklar</h3>
                        <p class="sa-hint">Seçtiğiniz tasarımın desteklemediği bloklar pasif görünür.</p>
                    </div>
                    <span class="sa-badge">Görünüm</span>
                </div>
                <div class="sa-card-body space-y-2.5">
                    @foreach($bloklar as $key => $b)
                        @php
                            $destekli = $destek === [] || in_array($b['blok'], $destek, true);
                            $acik = (bool) ($fa[$key] ?? true);
                        @endphp
                        <label class="sa-toggle-card {{ $acik && $destekli ? 'is-on' : '' }} {{ $destekli ? '' : 'ft-pasif' }}">
                            {{-- Pasif anahtarlar form ile gönderilmez; kayıtlı değerleri
                                 sıfırlanmasın diye yalnızca aktif olanlar işaretlenir. --}}
                            @if($destekli)
                                <input type="hidden" name="footer_bloklar[]" value="{{ $key }}">
                            @endif
                            <span class="flex-1 min-w-0">
                                <strong>{{ $b['ad'] }}</strong>
                                <span class="desc">{{ $destekli ? $b['desc'] : 'Bu tasarımda kullanılmıyor' }}</span>
                            </span>
                            <span class="sa-switch">
                                <input type="checkbox" name="{{ $key }}" value="1" @checked($acik) @disabled(! $destekli)>
                                <span></span>
                            </span>
                        </label>
                    @endforeach
                </div>
                <div class="sa-card-foot">
                    <p class="sa-hint m-0">Kaydettikten sonra sitede anında görünür.</p>
                    <button type="submit" class="sa-btn sa-btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Tasarımı kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- ============================================================
     2) FOOTER LİNKLERİ (tasarımdan bağımsız içerik)
     ============================================================ --}}
<div class="sa-wrap">
    <div class="sa-layout sa-layout-wide">
        <div class="sa-card">
            <div class="sa-card-head !items-center">
                <div class="min-w-0">
                    <h3>Footer linkleri</h3>
                    <p class="sa-hint">
                        Sitede footer “Keşfet / Hızlı linkler” sütununda görünen bağlantılar.
                        Header menüsünden <strong>bağımsızdır</strong>. Sürükleyerek sıralayın.
                        Yasal sayfalar (KVKK vb.) için ayrıca
                        <a href="{{ route('panel.site-ayarlari.sayfalar') }}" class="font-semibold text-brand-600 underline">Sayfalar → Footer’da göster</a>
                        kullanın. Footer metni:
                        <a href="{{ route('panel.site-ayarlari.genel') }}" class="font-semibold text-brand-600 underline">Genel</a>.
                    </p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="sa-badge">{{ $items->count() }} satır</span>
                    <form method="POST" action="{{ route('panel.site-ayarlari.footer.ekle') }}">
                        @csrf
                        <button type="submit" class="sa-btn sa-btn-primary sa-btn-sm">+ Link ekle</button>
                    </form>
                </div>
            </div>

            <div class="sa-card-body !pt-3">
                @if($items->isEmpty())
                    <div class="sa-empty">
                        <strong>Henüz footer linki yok</strong>
                        “+ Link ekle” ile başlayın.
                    </div>
                @else
                    <form method="POST" action="{{ route('panel.site-ayarlari.footer.kaydet') }}" id="footerForm">
                        @csrf
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                                    <tr>
                                        <th class="px-2 py-2.5 w-10 text-center">⋮⋮</th>
                                        <th class="px-2 py-2.5 w-10">#</th>
                                        <th class="px-3 py-2.5 min-w-[140px]">Etiket</th>
                                        <th class="px-3 py-2.5 min-w-[140px]">Bağlantı tipi</th>
                                        <th class="px-3 py-2.5 min-w-[200px]">Gideceği yer</th>
                                        <th class="px-3 py-2.5 w-20 text-center">Aktif</th>
                                        <th class="px-3 py-2.5 w-16 text-right">Sil</th>
                                    </tr>
                                </thead>
                                <tbody id="footerSortable" class="divide-y divide-slate-100 bg-white">
                                    @foreach($items as $item)
                                        @php
                                            $hasUrl = filled($item->url);
                                            $currentRoute = $item->route ?: 'frontend.anasayfa';
                                            $idx = $loop->index;
                                        @endphp
                                        <tr class="footer-tr {{ $item->aktif ? '' : 'opacity-60' }} hover:bg-slate-50/80" data-id="{{ $item->id }}">
                                            <td class="px-2 py-2 text-center align-middle">
                                                <button type="button" class="sa-drag inline-flex text-slate-400 hover:text-brand-600" title="Sürükle" aria-label="Sürükle">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="6" r="1.5"/><circle cx="15" cy="6" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="9" cy="18" r="1.5"/><circle cx="15" cy="18" r="1.5"/></svg>
                                                </button>
                                                <input type="hidden" name="id[]" value="{{ $item->id }}">
                                            </td>
                                            <td class="px-2 py-2 align-middle">
                                                <span class="sa-order !m-0">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td class="px-3 py-2 align-middle">
                                                <input type="text" name="label[]" value="{{ $item->label }}"
                                                       class="sa-input !py-2 !text-xs !rounded-lg" placeholder="Footer’da görünen ad">
                                            </td>
                                            <td class="px-3 py-2 align-middle">
                                                <select name="link_type[]" class="sa-input sa-select footer-link-type !py-2 !text-xs !rounded-lg" data-row="{{ $idx }}">
                                                    <option value="route" @selected(! $hasUrl)>Site sayfası</option>
                                                    <option value="url" @selected($hasUrl)>Harici URL</option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 align-middle">
                                                <div class="footer-route-wrap" data-row="{{ $idx }}" style="{{ $hasUrl ? 'display:none' : '' }}">
                                                    <select name="route[]" class="sa-input sa-select !py-2 !text-xs !rounded-lg">
                                                        <optgroup label="Sistem sayfaları">
                                                            @foreach($pageGroups['system'] ?? [] as $route => $pageLabel)
                                                                <option value="{{ $route }}" @selected($currentRoute === $route)>{{ $pageLabel }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                        @if(! empty($pageGroups['pages']))
                                                            <optgroup label="Özel sayfalar">
                                                                @foreach($pageGroups['pages'] as $route => $pageLabel)
                                                                    <option value="{{ $route }}" @selected($currentRoute === $route)>{{ $pageLabel }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="footer-url-wrap" data-row="{{ $idx }}" style="{{ $hasUrl ? '' : 'display:none' }}">
                                                    <input type="text" name="url[]" value="{{ $item->url }}"
                                                           class="sa-input !py-2 !text-xs !rounded-lg font-mono" placeholder="https://...">
                                                </div>
                                            </td>
                                            <td class="px-3 py-2 text-center align-middle">
                                                <label class="sa-switch !mx-auto" title="Footer’da göster">
                                                    <input type="checkbox" name="aktif[{{ $idx }}]" value="1" class="toggle-aktif"
                                                           data-id="{{ $item->id }}" data-type="footer" @checked($item->aktif)>
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td class="px-3 py-2 text-right align-middle">
                                                <button type="submit" form="footer-del-{{ $item->id }}"
                                                        class="text-[11px] font-bold text-red-600 hover:underline"
                                                        onclick="return confirm('Bu footer linki silinsin mi?');">Sil</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="sa-actions !mt-5">
                            <p class="sa-hint m-0">Değişiklikler kaydedilmeden sitede görünmez (sıra ve aktif anında kaydolur).</p>
                            <button type="submit" class="sa-btn sa-btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Footer’ı kaydet
                            </button>
                        </div>
                    </form>

                    @foreach($items as $item)
                        <form id="footer-del-{{ $item->id }}" method="POST" action="{{ route('panel.site-ayarlari.footer.sil', $item->id) }}" class="hidden">
                            @csrf
                        </form>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* ——— Footer tasarım kartları ——— */
    .ft-kartlar {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: .85rem;
    }
    .ft-kart {
        position: relative;
        display: flex; flex-direction: column; gap: .7rem;
        padding: .8rem .8rem .9rem;
        border: 1.5px solid #E8EAED; border-radius: 1rem;
        background: #fff; cursor: pointer;
        transition: border-color .15s, box-shadow .15s, transform .15s;
    }
    .ft-kart:hover { border-color: rgba(201,106,43,.45); transform: translateY(-2px); }
    .ft-kart.is-on { border-color: #C96A2B; box-shadow: 0 6px 22px rgba(201,106,43,.14); }
    .ft-kart__radio { position: absolute; opacity: 0; pointer-events: none; }
    .ft-kart__govde { display: block; min-width: 0; }
    .ft-kart__govde strong { display: block; font-size: .8125rem; font-weight: 700; color: #111827; }
    .ft-kart__desc { display: block; margin-top: .2rem; font-size: .68rem; line-height: 1.5; color: #6B7280; }
    .ft-kart__tik {
        position: absolute; top: .6rem; right: .6rem;
        width: 1.25rem; height: 1.25rem; border-radius: 999px;
        display: inline-flex; align-items: center; justify-content: center;
        background: #C96A2B; color: #fff; opacity: 0; transform: scale(.7);
        transition: opacity .15s, transform .15s;
    }
    .ft-kart.is-on .ft-kart__tik { opacity: 1; transform: scale(1); }

    /* Mini yerleşim önizlemesi */
    .ft-onizleme {
        display: flex; flex-direction: column; gap: 4px;
        padding: 8px; border-radius: .7rem; min-height: 92px;
        background: #F4F5F7; border: 1px solid #E8EAED;
        --ft-c: #C7CBD1; --ft-a: #C96A2B;
    }
    .ft-onizleme--koyu { background: #262626; border-color: #333; --ft-c: #55534f; }
    .ft-p { display: flex; align-items: center; gap: 4px; }
    .ft-p--cta { background: #262626; border-radius: 4px; padding: 5px 6px; justify-content: space-between; }
    .ft-onizleme--koyu .ft-p--cta { background: var(--ft-a); }
    .ft-p--cta i { display: block; height: 5px; width: 46%; border-radius: 2px; background: rgba(255,255,255,.55); }
    .ft-p--cta b { display: block; height: 9px; width: 26%; border-radius: 999px; background: var(--ft-a); }
    .ft-onizleme--koyu .ft-p--cta b { background: rgba(255,255,255,.85); }
    .ft-p--logo i { display: block; height: 10px; width: 38%; border-radius: 3px; background: var(--ft-a); opacity: .8; }
    .ft-p--logo-orta { flex-direction: column; gap: 4px; }
    .ft-p--logo-orta i { display: block; height: 10px; width: 34%; border-radius: 3px; background: var(--ft-a); opacity: .85; }
    .ft-p--logo-orta u { display: block; height: 4px; width: 58%; border-radius: 2px; background: var(--ft-c); }
    .ft-p--kol { flex: 1; align-items: stretch; gap: 5px; }
    .ft-p--kol s { display: block; flex: 1; border-radius: 4px; background: var(--ft-c); opacity: .75; min-height: 30px; }
    .ft-p--kol s:first-child { flex: 1.5; opacity: .95; }
    .ft-p--tek { flex-direction: column; gap: 5px; align-items: center; flex: 1; justify-content: center; }
    .ft-p--tek u { display: block; height: 5px; border-radius: 2px; background: var(--ft-c); width: 72%; }
    .ft-p--tek u:last-child { width: 46%; }
    .ft-p--alt { height: 5px; border-radius: 2px; background: var(--ft-c); opacity: .55; margin-top: auto; }

    .sa-toggle-card.ft-pasif { opacity: .45; }
    .sa-toggle-card.ft-pasif .sa-switch { pointer-events: none; }

    #footerSortable .footer-tr.sortable-ghost { opacity: .45; background: #FFF7ED; }
    #footerSortable .footer-tr.sortable-chosen { background: #fff; box-shadow: 0 8px 24px rgba(201,106,43,.12); }
    .sa-order { width: 1.75rem; height: 1.75rem; border-radius: .5rem; display: inline-flex; align-items: center; justify-content: center;
        font-size: .65rem; font-weight: 800; font-family: ui-monospace, monospace;
        background: #FFF7ED; color: #C96A2B; border: 1px solid rgba(231,181,138,.55); }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function(){
    // ——— Footer tasarım kartı seçimi ———
    const kartlar = [...document.querySelectorAll('.ft-kart')];
    kartlar.forEach((kart) => {
        const radio = kart.querySelector('.ft-kart__radio');
        if (!radio) return;
        radio.addEventListener('change', () => {
            kartlar.forEach(k => k.classList.toggle('is-on', k === kart));
        });
    });

    // ——— Logo kaynağına göre alanları göster/gizle ———
    const logoTip = document.getElementById('footerLogoTip');
    if (logoTip) {
        const ozel = document.getElementById('footerLogoOzelAlan');
        const boyut = document.getElementById('footerLogoBoyutAlan');
        const uygula = () => {
            const v = logoTip.value;
            if (ozel) ozel.style.display = (v === 'ozel') ? '' : 'none';
            if (boyut) boyut.style.display = (v === 'site' || v === 'ozel') ? '' : 'none';
        };
        logoTip.addEventListener('change', uygula);
        uygula();
    }

    // ——— Blok anahtarlarının görsel durumu ———
    document.querySelectorAll('.sa-toggle-card:not(.ft-pasif) .sa-switch input[type="checkbox"]').forEach((cb) => {
        cb.addEventListener('change', () => {
            cb.closest('.sa-toggle-card')?.classList.toggle('is-on', cb.checked);
        });
    });

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const tbody = document.getElementById('footerSortable');
    if (tbody && window.Sortable) {
        Sortable.create(tbody, {
            handle: '.sa-drag',
            animation: 180,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            draggable: 'tr.footer-tr',
            onEnd: async function () {
                const ids = [...tbody.querySelectorAll('tr.footer-tr[data-id]')].map(n => parseInt(n.dataset.id, 10));
                tbody.querySelectorAll('tr.footer-tr').forEach((row, i) => {
                    const badge = row.querySelector('.sa-order');
                    if (badge) badge.textContent = String(i + 1).padStart(2, '0');
                });
                try {
                    const res = await fetch(@json(route('panel.site-ayarlari.reorder')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ type: 'footer', ids })
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    if (window.saToast) window.saToast('Sıralama kaydedildi', 'ok');
                } catch (e) {
                    if (window.saToast) window.saToast('Sıralama kaydedilemedi', 'err');
                }
            }
        });
    }
    window.saInitToggles?.(@json(route('panel.site-ayarlari.toggle')), csrf);

    document.querySelectorAll('.footer-link-type').forEach((sel) => {
        sel.addEventListener('change', () => {
            const row = sel.getAttribute('data-row');
            const isUrl = sel.value === 'url';
            const routeWrap = document.querySelector('.footer-route-wrap[data-row="' + row + '"]');
            const urlWrap = document.querySelector('.footer-url-wrap[data-row="' + row + '"]');
            if (routeWrap) routeWrap.style.display = isUrl ? 'none' : '';
            if (urlWrap) urlWrap.style.display = isUrl ? '' : 'none';
        });
    });
})();
</script>
@endpush
