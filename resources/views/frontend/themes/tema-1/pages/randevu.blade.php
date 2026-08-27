@extends(theme_layout())

@section('baslik', 'Randevu Al | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', 'Online randevu wizard: hizmet, gün ve saat seçin, birkaç saniyede randevunuzu oluşturun.')

@section('icerik')
@php
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $photo    = $doktor['profil_resmi'] ?? null;
@endphp

<div class="page-header parallaxie"@if($photo) style="background-image:url('{{ $photo }}')"@endif>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="page-header-box">
                    <h1 class="text-anime-style-2" data-cursor="-opaque">Online Randevu</h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('frontend.anasayfa') }}">Anasayfa</a></li>
                            <li class="breadcrumb-item active">Randevu</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="our-appointment">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div id="ra-wizard-hata" class="d-none" style="padding:1rem;margin-bottom:1rem;border-radius:.5rem;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:.9rem"></div>

                {{-- Adım göstergesi --}}
                <div class="ra-steps" style="display:flex;gap:1rem;justify-content:center;margin-bottom:2.5rem;flex-wrap:wrap">
                    @foreach([1=>'Hizmet',2=>'Gün',3=>'Saat',4=>'Bilgiler'] as $n=>$ad)
                        <div class="ra-step" data-step="{{ $n }}" style="display:flex;align-items:center;gap:.75rem">
                            <div class="ra-step-num" style="width:36px;height:36px;border-radius:50%;background:#e5e7eb;color:#6b7280;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;transition:.2s">{{ $n }}</div>
                            <span class="ra-step-label" style="font-size:.85rem;color:#6b7280;font-weight:600">{{ $ad }}</span>
                            @if($n < 4)
                                <span style="width:24px;height:2px;background:#e5e7eb;display:inline-block"></span>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Adım 1: Hizmet --}}
                <div class="ra-step-panel" data-panel="1">
                    <h2 class="text-anime-style-2" data-cursor="-opaque" style="text-align:center;margin-bottom:2rem">Hizmet seçin</h2>
                    <div id="ra-hizmetler" class="row" style="gap:0;row-gap:1rem">
                        <div class="col-12" style="text-align:center;color:#6b7280;font-size:.9rem">Hizmetler yükleniyor…</div>
                    </div>
                </div>

                {{-- Adım 2: Gün --}}
                <div class="ra-step-panel d-none" data-panel="2">
                    <h2 class="text-anime-style-2" data-cursor="-opaque" style="text-align:center;margin-bottom:2rem">Gün seçin</h2>
                    <div style="max-width:400px;margin:0 auto">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                            <button type="button" id="ra-prev-week" class="btn-default" style="padding:.5rem 1rem;font-size:.85rem">‹ Önceki</button>
                            <span id="ra-week-label" style="font-weight:700;color:var(--primary-color)"></span>
                            <button type="button" id="ra-next-week" class="btn-default" style="padding:.5rem 1rem;font-size:.85rem">Sonraki ›</button>
                        </div>
                        <div id="ra-gunler" style="display:grid;grid-template-columns:repeat(7,1fr);gap:.5rem"></div>
                    </div>
                    <div style="text-align:center;margin-top:2rem">
                        <button type="button" class="btn-default" onclick="raStepGo(1)" style="background:transparent;border:1px solid var(--primary-color);color:var(--primary-color)">← Geri</button>
                    </div>
                </div>

                {{-- Adım 3: Saat --}}
                <div class="ra-step-panel d-none" data-panel="3">
                    <h2 class="text-anime-style-2" data-cursor="-opaque" style="text-align:center;margin-bottom:2rem">Saat seçin</h2>
                    <p id="ra-saat-info" style="text-align:center;color:#6b7280;font-size:.9rem;margin-bottom:1.5rem"></p>
                    <div id="ra-saatler" style="display:flex;flex-wrap:wrap;gap:.75rem;justify-content:center;max-width:600px;margin:0 auto"></div>
                    <div style="text-align:center;margin-top:2rem">
                        <button type="button" class="btn-default" onclick="raStepGo(2)" style="background:transparent;border:1px solid var(--primary-color);color:var(--primary-color)">← Geri</button>
                    </div>
                </div>

                {{-- Adım 4: Bilgiler --}}
                <div class="ra-step-panel d-none" data-panel="4">
                    <h2 class="text-anime-style-2" data-cursor="-opaque" style="text-align:center;margin-bottom:2rem">Bilgileriniz</h2>

                    <div id="ra-ozet" style="max-width:500px;margin:0 auto 2rem;padding:1rem;background:var(--secondary-color);border-radius:.5rem;font-size:.85rem"></div>

                    <form id="ra-form" style="max-width:500px;margin:0 auto">
                        @csrf
                        <input type="text" name="website_url" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">

                        <div class="row" style="gap:0;row-gap:1rem">
                            <div class="col-md-6" style="padding-right:.5rem">
                                <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:.35rem">Ad *</label>
                                <input type="text" name="ad" required maxlength="100" style="width:100%;padding:.75rem 1rem;border:1px solid #e5e7eb;border-radius:.5rem;font-size:.9rem">
                            </div>
                            <div class="col-md-6" style="padding-left:.5rem">
                                <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:.35rem">Soyad *</label>
                                <input type="text" name="soyad" required maxlength="100" style="width:100%;padding:.75rem 1rem;border:1px solid #e5e7eb;border-radius:.5rem;font-size:.9rem">
                            </div>
                            <div class="col-12">
                                <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:.35rem">Telefon *</label>
                                <input type="tel" name="telefon" required maxlength="30" placeholder="0532 XXX XX XX" style="width:100%;padding:.75rem 1rem;border:1px solid #e5e7eb;border-radius:.5rem;font-size:.9rem">
                            </div>
                            <div class="col-12">
                                <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:.35rem">E-posta</label>
                                <input type="email" name="e_posta" maxlength="255" style="width:100%;padding:.75rem 1rem;border:1px solid #e5e7eb;border-radius:.5rem;font-size:.9rem">
                            </div>
                            <div class="col-12">
                                <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:.35rem">Not (opsiyonel)</label>
                                <textarea name="not" rows="3" maxlength="1000" style="width:100%;padding:.75rem 1rem;border:1px solid #e5e7eb;border-radius:.5rem;font-size:.9rem;resize:vertical"></textarea>
                            </div>
                            <div class="col-12">
                                <label style="display:flex;align-items:flex-start;gap:.5rem;font-size:.8rem;color:#374151;cursor:pointer">
                                    <input type="checkbox" name="kvkk_onay" required style="margin-top:.15rem">
                                    <span>Kişisel verilerimin randevu oluşturma amacıyla işlenmesine onay veriyorum. *</span>
                                </label>
                            </div>
                        </div>

                        <div style="display:flex;gap:.75rem;margin-top:2rem">
                            <button type="button" class="btn-default" onclick="raStepGo(3)" style="background:transparent;border:1px solid var(--primary-color);color:var(--primary-color);flex:1">← Geri</button>
                            <button type="submit" id="ra-submit" class="btn-default" style="flex:2">Randevumu Oluştur</button>
                        </div>
                    </form>
                </div>

                {{-- Adım 5: Başarı --}}
                <div class="ra-step-panel d-none" data-panel="5">
                    <div style="text-align:center;padding:2rem">
                        <div style="width:80px;height:80px;border-radius:50%;background:#dcfce7;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1.5rem">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#166534" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Randevunuz alındı!</h2>
                        <p id="ra-basari-mesaj" style="color:#6b7280;margin-top:1rem"></p>
                        <div style="margin-top:2rem">
                            <a href="{{ route('frontend.anasayfa') }}" class="btn-default">Anasayfaya Dön</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const routes = {
        services: @json(route('frontend.booking.services')),
        slots: @json(route('frontend.booking.slots')),
        create: @json(route('frontend.booking.appointments')),
    };
    const csrf = document.querySelector('meta[name=csrf-token]')?.content;

    const state = {
        adim: 1,
        hizmet: null,
        tarih: null,
        saat: null,
        haftaStart: null,
    };

    const hataEl = document.getElementById('ra-wizard-hata');
    function hataGoster(msg) { hataEl.textContent = msg; hataEl.classList.remove('d-none'); window.scrollTo({top: 0, behavior: 'smooth'}); }
    function hataGizle() { hataEl.classList.add('d-none'); }

    function stepGoster(n) {
        state.adim = n;
        document.querySelectorAll('.ra-step-panel').forEach(el => el.classList.add('d-none'));
        const p = document.querySelector(`.ra-step-panel[data-panel="${n}"]`);
        if (p) p.classList.remove('d-none');
        // Adım göstergesini güncelle
        document.querySelectorAll('.ra-step').forEach(el => {
            const s = parseInt(el.dataset.step, 10);
            const num = el.querySelector('.ra-step-num');
            const lbl = el.querySelector('.ra-step-label');
            if (s < n) { num.style.background = 'var(--accent-color)'; num.style.color = '#fff'; num.textContent = '✓'; lbl.style.color = 'var(--primary-color)'; }
            else if (s === n) { num.style.background = 'var(--primary-color)'; num.style.color = '#fff'; num.textContent = s; lbl.style.color = 'var(--primary-color)'; }
            else { num.style.background = '#e5e7eb'; num.style.color = '#6b7280'; num.textContent = s; lbl.style.color = '#6b7280'; }
        });
        window.scrollTo({top: document.querySelector('.our-appointment').offsetTop - 80, behavior: 'smooth'});
    }
    window.raStepGo = stepGoster;

    /* ---- Adım 1: Hizmet ---- */
    async function hizmetleriYukle() {
        try {
            const r = await fetch(routes.services, { headers: { 'Accept': 'application/json' } });
            const j = await r.json();
            const cont = document.getElementById('ra-hizmetler');
            const items = j.data || j.services || [];
            if (!items.length) { cont.innerHTML = '<div class="col-12" style="text-align:center;color:#6b7280">Şu an aktif hizmet bulunmuyor.</div>'; return; }
            cont.innerHTML = items.map(h => `
                <div class="col-md-6" style="padding:.5rem">
                    <button type="button" data-hizmet-id="${h.id}" data-hizmet-ad="${(h.ad || h.baslik || '').replace(/"/g,'&quot;')}" data-hizmet-sure="${h.sure || 30}"
                            class="ra-hizmet-btn"
                            style="width:100%;padding:1.25rem;background:#fff;border:2px solid #e5e7eb;border-radius:.5rem;text-align:left;cursor:pointer;transition:.2s">
                        <div style="font-weight:700;color:var(--primary-color);font-size:.95rem">${h.ad || h.baslik || 'Hizmet'}</div>
                        <div style="font-size:.8rem;color:#6b7280;margin-top:.3rem">
                            ${h.sure || 30} dakika ${h.fiyat ? '· ' + (h.fiyat_str || (h.fiyat + ' ₺')) : ''}
                        </div>
                        ${h.aciklama ? `<div style="font-size:.8rem;color:#6b7280;margin-top:.5rem;line-height:1.5">${String(h.aciklama).substring(0,120)}${h.aciklama.length>120?'…':''}</div>` : ''}
                    </button>
                </div>`).join('');
            cont.querySelectorAll('.ra-hizmet-btn').forEach(b => {
                b.addEventListener('mouseenter', () => { b.style.borderColor = 'var(--accent-color)'; b.style.transform = 'translateY(-2px)'; });
                b.addEventListener('mouseleave', () => { b.style.borderColor = '#e5e7eb'; b.style.transform = 'translateY(0)'; });
                b.addEventListener('click', () => {
                    state.hizmet = { id: b.dataset.hizmetId, ad: b.dataset.hizmetAd, sure: b.dataset.hizmetSure };
                    haftaBaslat();
                    stepGoster(2);
                    gunleriRender();
                });
            });
        } catch { hataGoster('Hizmetler yüklenemedi. Sayfayı yenileyin.'); }
    }

    /* ---- Adım 2: Gün ---- */
    function haftaBaslat() {
        const bugun = new Date();
        bugun.setHours(0,0,0,0);
        state.haftaStart = bugun;
    }
    function gunleriRender() {
        const gunlerEl = document.getElementById('ra-gunler');
        const gunAdlari = ['Paz','Pzt','Sal','Çar','Per','Cum','Cmt'];
        const aylar = ['Oca','Şub','Mar','Nis','May','Haz','Tem','Ağu','Eyl','Eki','Kas','Ara'];
        const bugun = new Date(); bugun.setHours(0,0,0,0);
        const w = state.haftaStart;
        document.getElementById('ra-week-label').textContent =
            `${w.getDate()} ${aylar[w.getMonth()]} - ${new Date(w.getTime()+6*86400000).getDate()} ${aylar[new Date(w.getTime()+6*86400000).getMonth()]}`;
        gunlerEl.innerHTML = '';
        for (let i = 0; i < 7; i++) {
            const g = new Date(w.getTime() + i*86400000);
            const gecmis = g < bugun;
            const iso = g.toISOString().slice(0,10);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.disabled = gecmis;
            btn.style.cssText = `padding:.75rem .25rem;border:1px solid ${gecmis?'#f3f4f6':'#e5e7eb'};background:${gecmis?'#fafafa':'#fff'};color:${gecmis?'#d1d5db':'var(--primary-color)'};border-radius:.5rem;cursor:${gecmis?'not-allowed':'pointer'};text-align:center;font-size:.85rem;transition:.2s`;
            btn.innerHTML = `<div style="font-size:.7rem;color:#6b7280">${gunAdlari[g.getDay()]}</div><div style="font-weight:700;margin-top:.2rem">${g.getDate()}</div><div style="font-size:.7rem;color:#6b7280">${aylar[g.getMonth()]}</div>`;
            if (!gecmis) {
                btn.addEventListener('click', () => { state.tarih = iso; state.tarihGoster = `${g.getDate()} ${aylar[g.getMonth()]} ${gunAdlari[g.getDay()]}`; saatleriYukle(); });
            }
            gunlerEl.appendChild(btn);
        }
    }
    document.getElementById('ra-prev-week').addEventListener('click', () => {
        const t = new Date(state.haftaStart.getTime() - 7*86400000);
        const bugun = new Date(); bugun.setHours(0,0,0,0);
        if (t < bugun) state.haftaStart = bugun; else state.haftaStart = t;
        gunleriRender();
    });
    document.getElementById('ra-next-week').addEventListener('click', () => {
        state.haftaStart = new Date(state.haftaStart.getTime() + 7*86400000);
        gunleriRender();
    });

    /* ---- Adım 3: Saat ---- */
    async function saatleriYukle() {
        stepGoster(3);
        document.getElementById('ra-saat-info').textContent = `${state.tarihGoster} · ${state.hizmet.ad}`;
        const saatler = document.getElementById('ra-saatler');
        saatler.innerHTML = '<div style="color:#6b7280;font-size:.9rem">Saatler yükleniyor…</div>';
        try {
            const r = await fetch(`${routes.slots}?date=${state.tarih}`, { headers: { 'Accept': 'application/json' } });
            const j = await r.json();
            const slots = j.data?.slots || j.slots || j.data || [];
            if (!Array.isArray(slots) || !slots.length) {
                saatler.innerHTML = '<div style="color:#6b7280;font-size:.9rem;text-align:center;padding:2rem">Bu gün için uygun saat bulunamadı. Lütfen başka bir gün seçin.</div>';
                return;
            }
            saatler.innerHTML = slots.map(s => {
                const t = typeof s === 'string' ? s : (s.saat || s.time || '');
                return `<button type="button" data-saat="${t}" class="ra-saat-btn" style="padding:.75rem 1.25rem;background:#fff;border:1px solid #e5e7eb;border-radius:.5rem;font-weight:600;color:var(--primary-color);cursor:pointer;transition:.2s">${t}</button>`;
            }).join('');
            saatler.querySelectorAll('.ra-saat-btn').forEach(b => {
                b.addEventListener('mouseenter', () => { b.style.background = 'var(--accent-color)'; b.style.color = '#fff'; b.style.borderColor = 'var(--accent-color)'; });
                b.addEventListener('mouseleave', () => { b.style.background = '#fff'; b.style.color = 'var(--primary-color)'; b.style.borderColor = '#e5e7eb'; });
                b.addEventListener('click', () => {
                    state.saat = b.dataset.saat;
                    ozetGoster();
                    stepGoster(4);
                });
            });
        } catch { hataGoster('Saatler yüklenemedi. Sayfayı yenileyin.'); }
    }

    /* ---- Adım 4: Bilgiler ---- */
    function ozetGoster() {
        document.getElementById('ra-ozet').innerHTML = `
            <div style="display:flex;justify-content:space-between;padding:.35rem 0;border-bottom:1px solid rgba(0,0,0,.05)"><span style="color:#6b7280">Hizmet</span><span style="font-weight:700">${state.hizmet.ad}</span></div>
            <div style="display:flex;justify-content:space-between;padding:.35rem 0;border-bottom:1px solid rgba(0,0,0,.05)"><span style="color:#6b7280">Tarih</span><span style="font-weight:700">${state.tarihGoster}</span></div>
            <div style="display:flex;justify-content:space-between;padding:.35rem 0"><span style="color:#6b7280">Saat</span><span style="font-weight:700">${state.saat}</span></div>`;
    }

    document.getElementById('ra-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        hataGizle();
        const btn = document.getElementById('ra-submit');
        btn.disabled = true;
        const orig = btn.textContent;
        btn.textContent = 'Gönderiliyor…';

        const fd = new FormData(e.target);
        fd.append('hizmet_id', state.hizmet.id);
        fd.append('tarih', state.tarih);
        fd.append('saat', state.saat);

        try {
            const r = await fetch(routes.create, {
                method: 'POST',
                body: fd,
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            });
            const j = await r.json();
            if (r.ok && (j.success || j.data)) {
                document.getElementById('ra-basari-mesaj').textContent = j.message || `${state.tarihGoster} saat ${state.saat} için randevunuz oluşturuldu. Onay için sizinle iletişime geçeceğiz.`;
                stepGoster(5);
            } else {
                hataGoster(j.message || 'Randevu oluşturulamadı. Bilgilerinizi kontrol edin.');
                btn.disabled = false; btn.textContent = orig;
            }
        } catch {
            hataGoster('Sunucuya ulaşılamadı. Bağlantınızı kontrol edip tekrar deneyin.');
            btn.disabled = false; btn.textContent = orig;
        }
    });

    hizmetleriYukle();
})();
</script>
@endpush

@endsection
