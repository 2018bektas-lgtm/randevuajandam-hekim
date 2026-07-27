@extends(theme_layout())

@php
    $iletisim = $doktor['iletisim_sayfa'] ?? [];
    $pageBaslik = html_entity_decode((string) ($iletisim['baslik'] ?? 'Randevu Al'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $pageAlt = html_entity_decode((string) ($iletisim['alt_metin'] ?? 'Hizmet seçin, müsait gün ve saati belirleyin. Kayıt zorunlu değildir.'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $formGoster = (bool) ($iletisim['form_goster'] ?? true);
    $haritaGoster = (bool) ($iletisim['harita_goster'] ?? true);
    $saatlerGoster = (bool) ($iletisim['saatler_goster'] ?? true);
    $hizmetler = collect($doktor['hizmetler'] ?? [])->values();
    $onlineGorusme = !empty($doktor['online_gorusme']);
@endphp

@section('baslik', $pageBaslik.' | '.($doktor['ad_soyad'] ?? 'Hekim'))
@section('meta_aciklama', $pageAlt)

@section('icerik')
@php
    $tel = $doktor['telefon'] ?? null;
    $telRaw = $doktor['telefon_raw'] ?? preg_replace('/\D+/', '', (string) $tel);
    $eposta = $doktor['e_posta'] ?? null;
    $adres = $doktor['adres'] ?? trim(($doktor['ilce'] ?? '').' '.($doktor['il'] ?? ''));
    $cs = $doktor['calisma_saatleri'] ?? [];
@endphp

@include('frontend.themes.delogis.partials.page-header', [
    'title' => $pageBaslik,
    'crumb' => request()->routeIs('frontend.randevu') ? 'Randevu' : 'İletişim',
])

<section class="contact-page" style="padding-bottom:20px">
    <div class="container">
        <div class="row">
            @if($tel)
            <div class="col-xl-4 col-lg-4">
                <div class="contact-page__single">
                    <div class="contact-page__icon"><span class="icon-phone-call"></span></div>
                    <h3 class="contact-page__title">Telefon</h3>
                    <p class="contact-page__text"><a href="tel:{{ $telRaw }}">{{ $tel }}</a></p>
                </div>
            </div>
            @endif
            @if($eposta)
            <div class="col-xl-4 col-lg-4">
                <div class="contact-page__single">
                    <div class="contact-page__icon"><span class="fas fa-envelope"></span></div>
                    <h3 class="contact-page__title">E-posta</h3>
                    <p class="contact-page__text"><a href="mailto:{{ $eposta }}">{{ $eposta }}</a></p>
                </div>
            </div>
            @endif
            @if($adres)
            <div class="col-xl-4 col-lg-4">
                <div class="contact-page__single">
                    <div class="contact-page__icon"><span class="fas fa-map-marker-alt"></span></div>
                    <h3 class="contact-page__title">Adres</h3>
                    <p class="contact-page__text">{{ $adres }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Delogis randevu (make-appointment + contact-details uyumlu) --}}
<section class="contact-details dg-randevu" id="randevu">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-title__tagline">Online randevu</span>
            <h2 class="section-title__title">{{ $pageBaslik }}</h2>
            @if($pageAlt !== '')
                <p class="dg-randevu__lead">{{ $pageAlt }}</p>
            @endif
        </div>
        <div class="row gutter-y-30">
            @if($formGoster)
            <div class="col-xl-8 col-lg-7">
            <div class="mp-book dg-book">
            <div class="mp-book-shell dg-book__shell">
                <div class="mp-book-head dg-book__head">
                    <div>
                        <h2>Randevu al</h2>
                        <p id="mp-step-caption">1 / 3 · Hizmet seçin</p>
                    </div>
                    <div class="mp-book-steps" aria-hidden="true">
                        <span class="mp-book-step-dot is-active" data-dot="1"></span>
                        <span class="mp-book-step-dot" data-dot="2"></span>
                        <span class="mp-book-step-dot" data-dot="3"></span>
                    </div>
                </div>

                <div class="mp-book-body dg-book__body">
                    <div id="mp-book-alert" class="mp-book-alert" hidden></div>
                    <div id="mp-book-success" class="mp-book-success" hidden></div>

                    <form id="mp-booking-form" autocomplete="on">
                        @csrf
                        <div style="position:absolute;left:-9999px;opacity:0;height:0;overflow:hidden" aria-hidden="true">
                            <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="hidden" name="hizmet_id" id="mp-hizmet-id" value="">
                        <input type="hidden" name="tarih" id="mp-tarih" value="">
                        <input type="hidden" name="saat" id="mp-saat" value="">

                        <div id="mp-summary" class="mp-book-summary" hidden></div>

                        {{-- Step 1: Services --}}
                        <div class="mp-book-panel" data-panel="1">
                            <p class="mp-book-label">Hizmet seçin</p>
                            <div class="mp-book-svc-search">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                                <input type="search" id="mp-svc-search" placeholder="Hizmet ara…" autocomplete="off" aria-label="Hizmet ara">
                            </div>
                            <div class="mp-book-svc-scroll">
                                <div class="mp-book-svc-grid" id="mp-svc-grid">
                                    @forelse ($hizmetler as $h)
                                        @php
                                            $hid = $h['id'] ?? null;
                                            $ad = decode_text($h['baslik'] ?? $h['ad'] ?? 'Hizmet');
                                            $sure = $h['sure'] ?? '';
                                            $desc = plain_text($h['kisa'] ?? $h['aciklama'] ?? '', 48);
                                            $img = $h['image'] ?? $h['resim'] ?? null;
                                            $search = mb_strtolower($ad.' '.$desc);
                                        @endphp
                                        <button type="button" class="mp-book-svc"
                                                data-id="{{ $hid }}"
                                                data-ad="{{ $ad }}"
                                                data-search="{{ e($search) }}">
                                            <span class="mp-book-svc-media {{ $img ? '' : 'is-empty' }}">
                                                @if($img)
                                                    <img src="{{ $img }}" alt="" loading="lazy">
                                                @else
                                                    <span class="icon-form" aria-hidden="true"></span>
                                                @endif
                                            </span>
                                            <span>
                                                <span class="mp-book-svc-name">{{ $ad }}</span>
                                                @if($desc)<span class="mp-book-svc-desc">{{ $desc }}</span>@endif
                                                @if($sure)<span class="mp-book-svc-tag">{{ $sure }}</span>@endif
                                            </span>
                                        </button>
                                    @empty
                                        <p class="mp-book-empty" id="mp-svc-loading">Hizmetler yükleniyor…</p>
                                    @endforelse
                                </div>
                            </div>
                            <p id="mp-svc-empty" class="mp-book-empty" hidden>Aramanıza uygun hizmet yok.</p>
                            <p id="mp-err-1" class="mp-book-err" hidden>Lütfen bir hizmet seçin.</p>
                        </div>

                        {{-- Step 2: Date + slots --}}
                        <div class="mp-book-panel" data-panel="2" hidden>
                            <div class="mp-book-datetime">
                                <div>
                                    <p class="mp-book-label">Tarih seçin</p>
                                    <div class="mp-book-cal">
                                        <div class="mp-book-cal-nav">
                                            <button type="button" class="mp-book-icon-btn" id="mp-cal-prev" aria-label="Önceki ay">‹</button>
                                            <p class="mp-book-cal-title" id="mp-cal-title">—</p>
                                            <button type="button" class="mp-book-icon-btn" id="mp-cal-next" aria-label="Sonraki ay">›</button>
                                        </div>
                                        <div class="mp-book-weekdays">
                                            @foreach (['Pt','Sa','Ça','Pe','Cu','Ct','Pz'] as $d)
                                                <span>{{ $d }}</span>
                                            @endforeach
                                        </div>
                                        <div class="mp-book-cal-grid" id="mp-cal-grid"></div>
                                    </div>
                                </div>
                                <div>
                                    <p class="mp-book-label">
                                        Saat seçin
                                        <span id="mp-saat-label" style="font-weight:500;text-transform:none;letter-spacing:0;color:var(--mp-blue)"></span>
                                    </p>
                                    <p id="mp-slots-ph" class="mp-book-empty">Soldan bir tarih seçin.</p>
                                    <p id="mp-slots-loading" class="mp-book-empty" hidden>Saatler yükleniyor…</p>
                                    <p id="mp-slots-empty" class="mp-book-empty" hidden>Bu günde müsait saat yok.</p>
                                    <div class="mp-book-slots" id="mp-slots-grid"></div>
                                </div>
                            </div>
                            <p id="mp-err-2" class="mp-book-err" hidden>Müsait bir gün ve saat seçin.</p>
                        </div>

                        {{-- Step 3: Contact --}}
                        <div class="mp-book-panel" data-panel="3" hidden>
                            @if($onlineGorusme)
                                <p class="mp-book-label">Görüşme türü</p>
                                <div class="mp-gorusme-opts" style="margin-bottom:16px">
                                    <label class="mp-gorusme-opt">
                                        <input type="radio" name="gorusme_tipi" value="yuz_yuze" checked>
                                        <span>
                                            <strong>Yüz yüze</strong>
                                            <small>Muayenehanede</small>
                                        </span>
                                    </label>
                                    <label class="mp-gorusme-opt">
                                        <input type="radio" name="gorusme_tipi" value="online">
                                        <span>
                                            <strong>Online</strong>
                                            <small>Görüntülü görüşme</small>
                                        </span>
                                    </label>
                                </div>
                            @else
                                <input type="hidden" name="gorusme_tipi" id="gorusme_tipi_hidden" value="yuz_yuze">
                            @endif

                            <p class="mp-book-label">İletişim bilgileri</p>
                            <div class="mp-book-fields">
                                <div>
                                    <label for="mp-ad">Ad *</label>
                                    <input type="text" id="mp-ad" name="ad" required maxlength="100" autocomplete="given-name" placeholder="Adınız">
                                </div>
                                <div>
                                    <label for="mp-soyad">Soyad *</label>
                                    <input type="text" id="mp-soyad" name="soyad" required maxlength="100" autocomplete="family-name" placeholder="Soyadınız">
                                </div>
                                <div>
                                    <label for="mp-telefon">Telefon *</label>
                                    <input type="tel" id="mp-telefon" name="telefon" required maxlength="30" autocomplete="tel" placeholder="05xx xxx xx xx">
                                </div>
                                <div>
                                    <label for="mp-eposta">E-posta</label>
                                    <input type="email" id="mp-eposta" name="e_posta" maxlength="255" autocomplete="email" placeholder="opsiyonel@mail.com">
                                </div>
                                <div class="full" id="otp-block" style="display:none">
                                    <label for="otp_kod">SMS doğrulama kodu</label>
                                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center">
                                        <input type="text" name="otp_kod" id="otp_kod" maxlength="6" placeholder="6 haneli kod" inputmode="numeric" autocomplete="one-time-code" style="flex:1;min-width:140px">
                                        <button type="button" class="mp-btn mp-btn-outline" id="otp-send-btn">Kod Gönder</button>
                                    </div>
                                    <p style="margin:.35rem 0 0;font-size:.78rem;color:var(--muted)">Platform SMS doğrulaması zorunluysa buraya girin.</p>
                                </div>
                                <div class="full">
                                    <label for="mp-not">Not</label>
                                    <textarea id="mp-not" name="not" rows="3" maxlength="1000" placeholder="Belirtmek istediğiniz bir şey var mı?"></textarea>
                                </div>
                                <div class="full">
                                    <label class="mp-book-kvkk">
                                        <input type="checkbox" id="mp-kvkk" name="kvkk_onay" value="1" required>
                                        <span>Kişisel verilerimin randevu oluşturma amacıyla işlenmesini kabul ediyorum. *</span>
                                    </label>
                                </div>
                            </div>
                            <p id="mp-err-3" class="mp-book-err" hidden>Zorunlu alanları doldurun.</p>
                        </div>

                        <div class="mp-book-nav dg-book__nav" id="mp-book-nav">
                            <button type="button" class="thm-btn thm-btn--two mp-btn mp-btn-outline" id="mp-btn-prev" hidden>Geri</button>
                            <button type="button" class="thm-btn mp-btn mp-btn-primary" id="mp-btn-next" style="margin-left:auto">Devam</button>
                            <button type="submit" class="thm-btn mp-btn mp-btn-primary" id="mp-btn-submit" hidden style="margin-left:auto">Randevu Talebini Gönder</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
            </div>
            @else
            <div class="col-xl-8 col-lg-7">
                <div class="dg-book__shell dg-book__shell--closed">
                    <h2>Online randevu kapalı</h2>
                    <p>Telefon veya e-posta ile iletişime geçebilirsiniz.</p>
                    <div class="dg-book__closed-actions">
                        @if(!empty($doktor['telefon_raw']))
                            <a href="tel:{{ $doktor['telefon_raw'] }}" class="thm-btn">{{ $doktor['telefon'] }}</a>
                        @endif
                        @if(!empty($doktor['e_posta']))
                            <a href="mailto:{{ $doktor['e_posta'] }}" class="thm-btn thm-btn--two">E-posta</a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="col-xl-4 col-lg-5">
                <div class="dg-book__aside">
                    <div class="dg-book__aside-card">
                        <h3 class="dg-book__aside-title">İletişim</h3>
                        <ul class="list-unstyled dg-book__aside-list">
                            <li><span>Telefon</span><strong>{{ $doktor['telefon'] ?? '—' }}</strong></li>
                            <li><span>E-posta</span><strong>{{ $doktor['e_posta'] ?? '—' }}</strong></li>
                            <li><span>Adres</span><strong>{{ $doktor['adres'] ?? '—' }}</strong></li>
                        </ul>
                    </div>
                    @if($saatlerGoster)
                    <div class="dg-book__aside-card">
                        <h3 class="dg-book__aside-title">Çalışma saatleri</h3>
                        <ul class="list-unstyled dg-book__aside-list">
                            @forelse (($doktor['calisma_saatleri'] ?? []) as $gun => $saat)
                                <li><span>{{ $gun }}</span><strong>{{ $saat }}</strong></li>
                            @empty
                                <li><span>Randevu ile</span><strong>Planlanır</strong></li>
                            @endforelse
                        </ul>
                    </div>
                    @endif
                    @if($haritaGoster && !empty($doktor['maps_embed']))
                    <div class="dg-book__aside-card dg-book__aside-card--map">
                        <iframe class="dg-book__map" src="{{ $doktor['maps_embed'] }}" loading="lazy" title="Harita"></iframe>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@if($formGoster)
@push('scripts')
<script>
(function () {
    const BOOKING_BASE = @json(url('/site-api/booking'));
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const form = document.getElementById('mp-booking-form');
    if (!form) return;

    let step = 1;
    let calYear, calMonth; // 0-based month
    let selectedDate = '';
    let selectedSaat = '';
    let selectedHizmet = { id: '', ad: '' };
    const today = new Date(); today.setHours(0,0,0,0);

    const captions = {
        1: '1 / 3 · Hizmet seçin',
        2: '2 / 3 · Tarih ve saat',
        3: '3 / 3 · Bilgileriniz',
    };

    function apiHeaders(extra = {}) {
        return {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(CSRF ? { 'X-CSRF-TOKEN': CSRF } : {}),
            ...extra,
        };
    }
    async function apiGet(path) {
        const res = await fetch(BOOKING_BASE + path, { headers: apiHeaders(), credentials: 'same-origin' });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(data.message || ('İstek başarısız (' + res.status + ')'));
        return data;
    }
    async function apiPost(path, body) {
        const res = await fetch(BOOKING_BASE + path, {
            method: 'POST',
            headers: apiHeaders({ 'Content-Type': 'application/json' }),
            credentials: 'same-origin',
            body: JSON.stringify(body),
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            const msg = data.message
                || (data.errors ? Object.values(data.errors).flat().join(' ') : null)
                || ('İstek başarısız (' + res.status + ')');
            throw new Error(msg);
        }
        return data;
    }

    function showAlert(msg) {
        const el = document.getElementById('mp-book-alert');
        el.hidden = false;
        el.textContent = msg;
    }
    function hideAlert() {
        const el = document.getElementById('mp-book-alert');
        el.hidden = true;
        el.textContent = '';
    }
    function setStep(n) {
        step = n;
        document.querySelectorAll('.mp-book-panel').forEach(p => {
            p.hidden = Number(p.dataset.panel) !== n;
        });
        document.querySelectorAll('.mp-book-step-dot').forEach(d => {
            const dn = Number(d.dataset.dot);
            d.classList.toggle('is-active', dn === n);
            d.classList.toggle('is-done', dn < n);
        });
        document.getElementById('mp-step-caption').textContent = captions[n] || '';
        document.getElementById('mp-btn-prev').hidden = n === 1;
        document.getElementById('mp-btn-next').hidden = n === 3;
        document.getElementById('mp-btn-submit').hidden = n !== 3;
        updateSummary();
        hideAlert();
        ['mp-err-1','mp-err-2','mp-err-3'].forEach(id => {
            const e = document.getElementById(id);
            if (e) e.hidden = true;
        });
    }
    function updateSummary() {
        const box = document.getElementById('mp-summary');
        if (!selectedHizmet.id && !selectedDate) {
            box.hidden = true;
            return;
        }
        const parts = [];
        if (selectedHizmet.ad) parts.push('<strong>' + selectedHizmet.ad + '</strong>');
        if (selectedDate) parts.push(selectedDate.split('-').reverse().join('.'));
        if (selectedSaat) parts.push(selectedSaat);
        box.innerHTML = parts.join(' · ');
        box.hidden = parts.length === 0;
    }

    // Service search + select
    document.getElementById('mp-svc-search')?.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        let visible = 0;
        document.querySelectorAll('.mp-book-svc').forEach(btn => {
            const ok = !q || (btn.dataset.search || '').includes(q);
            btn.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });
        document.getElementById('mp-svc-empty').hidden = visible > 0;
    });
    document.getElementById('mp-svc-grid')?.addEventListener('click', function (e) {
        const btn = e.target.closest('.mp-book-svc');
        if (!btn) return;
        document.querySelectorAll('.mp-book-svc').forEach(b => b.classList.remove('is-selected'));
        btn.classList.add('is-selected');
        selectedHizmet = { id: btn.dataset.id, ad: btn.dataset.ad };
        document.getElementById('mp-hizmet-id').value = selectedHizmet.id;
        updateSummary();
    });

    // Calendar
    const monthNames = ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
    function ymd(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth()+1).padStart(2,'0');
        const day = String(d.getDate()).padStart(2,'0');
        return y+'-'+m+'-'+day;
    }
    function renderCal() {
        const title = document.getElementById('mp-cal-title');
        const grid = document.getElementById('mp-cal-grid');
        title.textContent = monthNames[calMonth] + ' ' + calYear;
        grid.innerHTML = '';
        const first = new Date(calYear, calMonth, 1);
        // Monday-first
        let startPad = (first.getDay() + 6) % 7;
        const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
        for (let i = 0; i < startPad; i++) {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'mp-book-day is-muted';
            b.disabled = true;
            b.textContent = '';
            grid.appendChild(b);
        }
        for (let d = 1; d <= daysInMonth; d++) {
            const date = new Date(calYear, calMonth, d);
            const iso = ymd(date);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = d;
            btn.className = 'mp-book-day';
            if (date < today) {
                btn.classList.add('is-off');
                btn.disabled = true;
            } else {
                btn.classList.add('is-on');
                if (iso === selectedDate) btn.classList.add('is-selected');
                btn.addEventListener('click', () => selectDate(iso, btn));
            }
            grid.appendChild(btn);
        }
    }
    async function selectDate(iso, btn) {
        selectedDate = iso;
        selectedSaat = '';
        document.getElementById('mp-tarih').value = iso;
        document.getElementById('mp-saat').value = '';
        document.querySelectorAll('.mp-book-day').forEach(b => b.classList.remove('is-selected'));
        if (btn) btn.classList.add('is-selected');
        document.getElementById('mp-saat-label').textContent = ' · ' + iso.split('-').reverse().join('.');
        updateSummary();
        await loadSlots(iso);
    }
    async function loadSlots(date) {
        const ph = document.getElementById('mp-slots-ph');
        const loading = document.getElementById('mp-slots-loading');
        const empty = document.getElementById('mp-slots-empty');
        const grid = document.getElementById('mp-slots-grid');
        ph.hidden = true;
        empty.hidden = true;
        grid.innerHTML = '';
        loading.hidden = false;
        try {
            const res = await apiGet('/slots?date=' + encodeURIComponent(date));
            const slots = (res.data && res.data.slots) || res.slots || [];
            loading.hidden = true;
            if (!slots.length) {
                empty.hidden = false;
                return;
            }
            slots.forEach(s => {
                const saat = s.saat || s.time || s;
                const free = s.musait !== false && s.available !== false && !s.dolu;
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'mp-book-slot ' + (free ? 'is-free' : 'is-busy');
                b.textContent = saat;
                b.disabled = !free;
                if (free) {
                    b.addEventListener('click', () => {
                        document.querySelectorAll('.mp-book-slot').forEach(x => x.classList.remove('is-selected'));
                        b.classList.add('is-selected');
                        selectedSaat = saat;
                        document.getElementById('mp-saat').value = saat;
                        updateSummary();
                    });
                }
                grid.appendChild(b);
            });
        } catch (e) {
            loading.hidden = true;
            empty.hidden = false;
            empty.textContent = e.message || 'Slotlar alınamadı.';
        }
    }
    document.getElementById('mp-cal-prev')?.addEventListener('click', () => {
        calMonth--;
        if (calMonth < 0) { calMonth = 11; calYear--; }
        renderCal();
    });
    document.getElementById('mp-cal-next')?.addEventListener('click', () => {
        calMonth++;
        if (calMonth > 11) { calMonth = 0; calYear++; }
        renderCal();
    });

    // Nav
    document.getElementById('mp-btn-next')?.addEventListener('click', () => {
        if (step === 1) {
            if (!selectedHizmet.id) {
                document.getElementById('mp-err-1').hidden = false;
                return;
            }
            setStep(2);
            if (!calYear) {
                calYear = today.getFullYear();
                calMonth = today.getMonth();
                renderCal();
            }
            return;
        }
        if (step === 2) {
            if (!selectedDate || !selectedSaat) {
                document.getElementById('mp-err-2').hidden = false;
                return;
            }
            setStep(3);
        }
    });
    document.getElementById('mp-btn-prev')?.addEventListener('click', () => {
        if (step > 1) setStep(step - 1);
    });

    // If no services in HTML, load via API
    async function ensureServices() {
        const grid = document.getElementById('mp-svc-grid');
        if (grid.querySelector('.mp-book-svc')) return;
        try {
            const res = await apiGet('/services');
            const list = res.data || [];
            const loading = document.getElementById('mp-svc-loading');
            if (loading) loading.remove();
            if (!list.length) {
                grid.innerHTML = '<p class="mp-book-empty">Aktif hizmet bulunamadı.</p>';
                return;
            }
            grid.innerHTML = list.map(h => {
                const ad = h.ad || h.baslik || 'Hizmet';
                const sure = h.sure ? `<span class="mp-book-svc-tag">${h.sure} dk</span>` : '';
                const search = (ad + ' ' + (h.aciklama || '')).toLowerCase().replace(/"/g, '');
                return `<button type="button" class="mp-book-svc" data-id="${h.id}" data-ad="${ad.replace(/"/g,'')}" data-search="${search}">
                    <span class="mp-book-svc-media">✚</span>
                    <span><span class="mp-book-svc-name">${ad}</span>${sure}</span>
                </button>`;
            }).join('');
        } catch (e) {
            showAlert(e.message || 'Hizmetler yüklenemedi.');
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideAlert();
        if (!selectedHizmet.id || !selectedDate || !selectedSaat) {
            showAlert('Hizmet, tarih ve saat seçimi zorunludur.');
            return;
        }
        const ad = document.getElementById('mp-ad').value.trim();
        const soyad = document.getElementById('mp-soyad').value.trim();
        const telefon = document.getElementById('mp-telefon').value.trim();
        const kvkk = document.getElementById('mp-kvkk').checked;
        if (!ad || !soyad || !telefon || !kvkk) {
            document.getElementById('mp-err-3').hidden = false;
            return;
        }
        const gorusmeRadio = document.querySelector('input[name="gorusme_tipi"]:checked');
        const gorusmeTipi = gorusmeRadio
            ? gorusmeRadio.value
            : (document.getElementById('gorusme_tipi_hidden')?.value || 'yuz_yuze');

        const payload = {
            hizmet_id: Number(selectedHizmet.id),
            tarih: selectedDate,
            saat: selectedSaat,
            ad, soyad, telefon,
            e_posta: document.getElementById('mp-eposta').value.trim() || null,
            not: document.getElementById('mp-not').value.trim() || null,
            gorusme_tipi: gorusmeTipi,
            kvkk_onay: 1,
            website_url: document.getElementById('website_url')?.value || '',
            otp_kod: document.getElementById('otp_kod')?.value?.trim() || null,
        };

        const submitBtn = document.getElementById('mp-btn-submit');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Gönderiliyor…';
        try {
            if (window.raGetRecaptchaToken) {
                payload.recaptcha_token = await window.raGetRecaptchaToken('randevu');
            }
            const res = await apiPost('/appointments', payload);
            const d = res.data || {};
            const tipLabel = payload.gorusme_tipi === 'online' ? 'Online' : 'Yüz yüze';
            const yonet = d.yonetim_url ? `<br><a href="${d.yonetim_url}" style="color:var(--mp-blue);font-weight:700">Randevuyu yönet →</a>` : '';
            form.hidden = true;
            document.getElementById('mp-book-nav').hidden = true;
            const ok = document.getElementById('mp-book-success');
            ok.hidden = false;
            ok.innerHTML = `<strong>${res.message || 'Talebiniz alındı.'}</strong><br>
                ${selectedHizmet.ad}<br>
                Tarih: ${d.tarih || payload.tarih} · Saat: ${d.saat || payload.saat}<br>
                Görüşme: ${tipLabel}<br>
                Durum: ${d.durum || '-'}
                ${yonet}`;
        } catch (err) {
            showAlert(err.message || 'Randevu oluşturulamadı.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Randevu Talebini Gönder';
        }
    });

    ensureServices();
    setStep(1);

    document.getElementById('otp-send-btn')?.addEventListener('click', async () => {
        const telefon = document.getElementById('mp-telefon')?.value?.trim() || document.getElementById('telefon')?.value?.trim();
        if (!telefon) { showAlert('Önce telefon girin.'); return; }
        try {
            hideAlert();
            const res = await apiPost('/otp/send', { telefon });
            showAlert(res.message || 'Doğrulama kodu gönderildi.', true);
        } catch (e) {
            showAlert(e.message);
        }
    });
    const otpBlock = document.getElementById('otp-block');
    if (otpBlock) otpBlock.style.display = 'block';})();
</script>
@endpush
@endif
