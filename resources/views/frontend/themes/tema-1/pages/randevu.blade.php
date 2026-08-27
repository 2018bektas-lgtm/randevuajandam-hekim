@extends(theme_layout())

@section('baslik', 'Randevu Al | '.trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim')))
@section('meta_aciklama', 'Online randevu wizard: hizmet, gün ve saat seçin, birkaç saniyede randevunuzu oluşturun.')

@section('icerik')
@php
    $doktorAd = trim(($doktor['unvan'] ?? '').' '.($doktor['ad_soyad'] ?? 'Hekim'));
    $photo    = $doktor['profil_resmi'] ?? null;
@endphp

@include('frontend.themes.tema-1.partials.page-banner', [
    'kod' => 'randevu',
    'baslik' => 'Online Randevu',
    'breadcrumb' => [['label' => 'Randevu', 'aktif' => true]],
])

<section class="ra-wizard-section">
    <div class="container">
        <div class="ra-wizard-card wow fadeInUp">
            <div id="ra-hata" class="ra-alert d-none" role="alert"></div>

            {{-- Progress bar --}}
            <div class="ra-progress">
                @foreach([1=>'Hizmet',2=>'Gün',3=>'Saat',4=>'Bilgileriniz'] as $n=>$ad)
                    <div class="ra-progress-step" data-step="{{ $n }}">
                        <div class="ra-progress-dot">
                            <span class="ra-progress-num">{{ $n }}</span>
                            <svg class="ra-progress-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="ra-progress-label">{{ $ad }}</span>
                    </div>
                @endforeach
                <div class="ra-progress-line"><div class="ra-progress-line-fill" style="width:0%"></div></div>
            </div>

            {{-- Adım 1: Hizmet --}}
            <div class="ra-panel is-active" data-panel="1">
                <div class="ra-panel-head">
                    <span class="ra-eyebrow">Adım 1 / 4</span>
                    <h2 class="ra-title">Almak istediğiniz hizmeti seçin</h2>
                    <p class="ra-sub">Aşağıdaki hizmetlerden birini seçerek başlayın.</p>
                </div>
                <div id="ra-hizmetler" class="ra-services-grid">
                    <div class="ra-empty">Hizmetler yükleniyor…</div>
                </div>
            </div>

            {{-- Adım 2: Gün --}}
            <div class="ra-panel" data-panel="2">
                <div class="ra-panel-head">
                    <span class="ra-eyebrow">Adım 2 / 4</span>
                    <h2 class="ra-title">Uygun bir gün seçin</h2>
                    <p class="ra-sub" id="ra-secili-hizmet-ozet"></p>
                </div>
                <div class="ra-calendar">
                    <div class="ra-calendar-head">
                        <button type="button" class="ra-nav-btn" id="ra-prev-week" aria-label="Önceki hafta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <div class="ra-week-label" id="ra-week-label"></div>
                        <button type="button" class="ra-nav-btn" id="ra-next-week" aria-label="Sonraki hafta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                    <div class="ra-days" id="ra-gunler"></div>
                </div>
                <div class="ra-actions">
                    <button type="button" class="ra-btn-ghost" onclick="raStepGo(1)">‹ Hizmet Değiştir</button>
                </div>
            </div>

            {{-- Adım 3: Saat --}}
            <div class="ra-panel" data-panel="3">
                <div class="ra-panel-head">
                    <span class="ra-eyebrow">Adım 3 / 4</span>
                    <h2 class="ra-title">Size uygun saati seçin</h2>
                    <p class="ra-sub" id="ra-saat-info"></p>
                </div>
                <div class="ra-slots" id="ra-saatler"></div>
                <div class="ra-actions">
                    <button type="button" class="ra-btn-ghost" onclick="raStepGo(2)">‹ Başka Gün Seç</button>
                </div>
            </div>

            {{-- Adım 4: Bilgiler --}}
            <div class="ra-panel" data-panel="4">
                <div class="ra-panel-head">
                    <span class="ra-eyebrow">Son Adım</span>
                    <h2 class="ra-title">Bilgilerinizi girin</h2>
                    <p class="ra-sub">Randevu detayları aşağıda. İletişim bilgilerinizle işlemi tamamlayın.</p>
                </div>

                <div class="ra-summary" id="ra-ozet"></div>

                <form id="ra-form" class="ra-form" novalidate>
                    @csrf
                    <input type="text" name="website_url" tabindex="-1" autocomplete="off" aria-hidden="true" class="ra-honeypot">

                    <div class="ra-field-grid">
                        <div class="ra-field">
                            <label for="ra-ad">Adınız <span class="req">*</span></label>
                            <input type="text" id="ra-ad" name="ad" required maxlength="100" placeholder="Örn. Ayşe">
                        </div>
                        <div class="ra-field">
                            <label for="ra-soyad">Soyadınız <span class="req">*</span></label>
                            <input type="text" id="ra-soyad" name="soyad" required maxlength="100" placeholder="Örn. Yılmaz">
                        </div>
                        <div class="ra-field ra-field-full">
                            <label for="ra-telefon">Telefon <span class="req">*</span></label>
                            <input type="tel" id="ra-telefon" name="telefon" required maxlength="30" placeholder="0532 000 00 00">
                        </div>
                        <div class="ra-field ra-field-full">
                            <label for="ra-eposta">E-posta</label>
                            <input type="email" id="ra-eposta" name="e_posta" maxlength="255" placeholder="ornek@eposta.com">
                        </div>
                        <div class="ra-field ra-field-full">
                            <label for="ra-not">Notunuz <span class="opt">(opsiyonel)</span></label>
                            <textarea id="ra-not" name="not" rows="3" maxlength="1000" placeholder="Görüşmeden önce bilmemi istediğiniz bir şey var mı?"></textarea>
                        </div>
                        <div class="ra-field ra-field-full">
                            <label class="ra-check">
                                <input type="checkbox" name="kvkk_onay" required>
                                <span>Kişisel verilerimin randevu oluşturma amacıyla işlenmesine onay veriyorum. <a href="#" onclick="return false" class="ra-link">Aydınlatma metnini oku</a></span>
                            </label>
                        </div>
                    </div>

                    <div class="ra-actions ra-actions-split">
                        <button type="button" class="ra-btn-ghost" onclick="raStepGo(3)">‹ Saati Değiştir</button>
                        <button type="submit" id="ra-submit" class="ra-btn-primary">
                            <span class="ra-btn-label">Randevumu Oluştur</span>
                            <svg class="ra-btn-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Adım 5: Başarı --}}
            <div class="ra-panel" data-panel="5">
                <div class="ra-success">
                    <div class="ra-success-icon">
                        <svg viewBox="0 0 52 52"><circle cx="26" cy="26" r="24" fill="none" stroke="currentColor" stroke-width="2"/><path fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 27l7 7 17-17"/></svg>
                    </div>
                    <h2 class="ra-title">Randevunuz oluşturuldu</h2>
                    <p class="ra-sub" id="ra-basari-mesaj"></p>
                    <div class="ra-success-actions">
                        <a href="{{ route('frontend.anasayfa') }}" class="ra-btn-primary">Anasayfaya Dön</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('head')
<style>
/* ============ Randevu Wizard - Tema-1 Hipno ============ */
.ra-wizard-section{padding:60px 0 100px;background:var(--secondary-color)}
.ra-wizard-card{max-width:960px;margin:0 auto;background:#fff;border-radius:1rem;box-shadow:0 30px 80px rgba(0,0,0,.06);padding:40px clamp(20px,4vw,56px);position:relative}

.ra-alert{padding:1rem 1.25rem;margin-bottom:1.5rem;border-radius:.75rem;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:.9rem;font-family:var(--font)}

/* Progress */
.ra-progress{display:flex;justify-content:space-between;align-items:flex-start;position:relative;margin-bottom:56px;padding:0 clamp(10px,3vw,40px)}
.ra-progress-line{position:absolute;top:22px;left:calc(clamp(10px,3vw,40px) + 22px);right:calc(clamp(10px,3vw,40px) + 22px);height:2px;background:#e5e7eb;z-index:0}
.ra-progress-line-fill{height:100%;background:var(--accent-color);transition:width .5s ease}
.ra-progress-step{position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;gap:.6rem;flex:1;max-width:120px}
.ra-progress-dot{width:44px;height:44px;border-radius:50%;background:#fff;border:2px solid #e5e7eb;color:#9ca3af;display:flex;align-items:center;justify-content:center;font-weight:700;font-family:var(--display);transition:all .3s ease}
.ra-progress-check{width:20px;height:20px;display:none}
.ra-progress-step.is-active .ra-progress-dot{background:var(--primary-color);border-color:var(--primary-color);color:#fff;box-shadow:0 4px 16px rgba(0,0,0,.15)}
.ra-progress-step.is-done .ra-progress-dot{background:var(--accent-color);border-color:var(--accent-color);color:#fff}
.ra-progress-step.is-done .ra-progress-num{display:none}
.ra-progress-step.is-done .ra-progress-check{display:block}
.ra-progress-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;text-align:center;transition:color .3s}
.ra-progress-step.is-active .ra-progress-label,
.ra-progress-step.is-done .ra-progress-label{color:var(--primary-color)}

/* Panels */
.ra-panel{display:none;animation:raFade .4s ease}
.ra-panel.is-active{display:block}
@keyframes raFade{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
.ra-panel-head{text-align:center;margin-bottom:32px}
.ra-eyebrow{display:inline-block;font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:var(--accent-color);font-weight:700;margin-bottom:.5rem;font-family:var(--font)}
.ra-title{font-family:var(--display);font-size:clamp(1.5rem,3vw,2rem);color:var(--primary-color);margin:0 0 .5rem;line-height:1.2}
.ra-sub{color:#6b7280;font-size:.95rem;margin:0;line-height:1.6;font-family:var(--font)}

/* Empty */
.ra-empty{text-align:center;padding:3rem 1rem;color:#9ca3af;font-size:.9rem;grid-column:1/-1}

/* Services */
.ra-services-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px}
.ra-service-card{background:#fff;border:2px solid #e5e7eb;border-radius:.9rem;padding:1.25rem;text-align:left;cursor:pointer;transition:all .3s ease;font-family:var(--font);position:relative;overflow:hidden}
.ra-service-card::after{content:'';position:absolute;top:0;right:0;width:0;height:0;border-style:solid;border-width:0 30px 30px 0;border-color:transparent var(--accent-color) transparent transparent;opacity:0;transition:opacity .3s}
.ra-service-card:hover{border-color:var(--accent-color);transform:translateY(-3px);box-shadow:0 15px 40px rgba(0,0,0,.08)}
.ra-service-card:hover::after{opacity:1}
.ra-service-name{font-family:var(--display);font-size:1.1rem;color:var(--primary-color);font-weight:600;margin:0 0 .5rem;line-height:1.3}
.ra-service-meta{display:flex;gap:.8rem;font-size:.78rem;color:#6b7280;margin-bottom:.5rem}
.ra-service-meta span{display:inline-flex;align-items:center;gap:.3rem}
.ra-service-desc{color:#6b7280;font-size:.82rem;line-height:1.55}

/* Calendar */
.ra-calendar{max-width:520px;margin:0 auto}
.ra-calendar-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem}
.ra-nav-btn{width:40px;height:40px;border-radius:50%;background:#fff;border:1px solid #e5e7eb;color:var(--primary-color);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s}
.ra-nav-btn svg{width:16px;height:16px}
.ra-nav-btn:hover:not(:disabled){background:var(--accent-color);border-color:var(--accent-color);color:#fff}
.ra-nav-btn:disabled{opacity:.4;cursor:not-allowed}
.ra-week-label{font-family:var(--display);font-size:1.05rem;color:var(--primary-color);font-weight:600}
.ra-days{display:grid;grid-template-columns:repeat(7,1fr);gap:.5rem}
.ra-day{padding:.75rem .3rem;border:1.5px solid #e5e7eb;background:#fff;border-radius:.75rem;text-align:center;cursor:pointer;transition:all .25s;font-family:var(--font);display:flex;flex-direction:column;gap:.15rem}
.ra-day-wday{font-size:.68rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;font-weight:700}
.ra-day-num{font-family:var(--display);font-size:1.35rem;font-weight:600;color:var(--primary-color)}
.ra-day-mon{font-size:.65rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em}
.ra-day:hover:not(.is-disabled){border-color:var(--accent-color);transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.06)}
.ra-day.is-disabled{opacity:.35;cursor:not-allowed;background:#fafafa}
.ra-day.is-selected{background:var(--primary-color);border-color:var(--primary-color)}
.ra-day.is-selected .ra-day-wday,.ra-day.is-selected .ra-day-num,.ra-day.is-selected .ra-day-mon{color:#fff}

/* Slots */
.ra-slots{display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:.65rem;max-width:640px;margin:0 auto}
.ra-slot{padding:.85rem .5rem;border:1.5px solid #e5e7eb;background:#fff;border-radius:.6rem;font-family:var(--font);font-weight:600;color:var(--primary-color);cursor:pointer;transition:all .2s;font-size:.95rem;letter-spacing:.02em}
.ra-slot:hover{background:var(--accent-color);border-color:var(--accent-color);color:#fff;transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.06)}

/* Summary */
.ra-summary{background:var(--secondary-color);border:1px solid #eee;border-radius:.75rem;padding:1.25rem 1.5rem;margin-bottom:2rem;font-family:var(--font)}
.ra-summary-row{display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;font-size:.9rem}
.ra-summary-row + .ra-summary-row{border-top:1px solid rgba(0,0,0,.06)}
.ra-summary-label{color:#6b7280;font-weight:500}
.ra-summary-value{color:var(--primary-color);font-weight:700;font-family:var(--display)}

/* Form */
.ra-form{max-width:600px;margin:0 auto}
.ra-honeypot{position:absolute;left:-9999px;opacity:0;pointer-events:none}
.ra-field-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem 1.25rem}
.ra-field{display:flex;flex-direction:column;gap:.4rem}
.ra-field-full{grid-column:1/-1}
.ra-field label{font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;font-weight:700;color:var(--primary-color);font-family:var(--font)}
.ra-field .req{color:#dc2626}
.ra-field .opt{color:#9ca3af;font-weight:500;text-transform:none;letter-spacing:0}
.ra-field input,.ra-field textarea{padding:.8rem 1rem;border:1.5px solid #e5e7eb;border-radius:.6rem;font-family:var(--font);font-size:.92rem;color:var(--primary-color);background:#fff;transition:all .2s;resize:vertical}
.ra-field input:focus,.ra-field textarea:focus{outline:none;border-color:var(--accent-color);box-shadow:0 0 0 3px rgba(155,154,132,.15)}
.ra-check{display:flex;align-items:flex-start;gap:.6rem;font-size:.85rem;line-height:1.5;color:#4b5563;font-weight:500!important;text-transform:none!important;letter-spacing:0!important;cursor:pointer;font-family:var(--font)!important}
.ra-check input{margin-top:.25rem;width:18px;height:18px;accent-color:var(--accent-color);cursor:pointer}
.ra-link{color:var(--accent-color);text-decoration:underline;text-underline-offset:2px}

/* Actions & buttons */
.ra-actions{display:flex;justify-content:center;margin-top:2rem}
.ra-actions-split{justify-content:space-between;gap:1rem;flex-wrap:wrap}
.ra-btn-ghost{padding:.75rem 1.4rem;background:transparent;border:1.5px solid #d1d5db;color:#6b7280;border-radius:.6rem;font-family:var(--font);font-weight:600;font-size:.85rem;cursor:pointer;transition:all .2s}
.ra-btn-ghost:hover{border-color:var(--primary-color);color:var(--primary-color)}
.ra-btn-primary{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.9rem 2rem;background:var(--primary-color);color:#fff;border:none;border-radius:.6rem;font-family:var(--font);font-weight:700;font-size:.95rem;cursor:pointer;transition:all .25s;text-decoration:none;letter-spacing:.02em}
.ra-btn-primary:hover{background:var(--accent-color);transform:translateY(-2px);box-shadow:0 14px 30px rgba(0,0,0,.15)}
.ra-btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none}
.ra-btn-arrow{width:16px;height:16px;transition:transform .2s}
.ra-btn-primary:hover .ra-btn-arrow{transform:translateX(3px)}

/* Success */
.ra-success{text-align:center;padding:2.5rem 1rem}
.ra-success-icon{width:88px;height:88px;margin:0 auto 1.5rem;color:var(--accent-color);animation:raPop .5s ease}
.ra-success-icon svg{width:100%;height:100%}
@keyframes raPop{0%{transform:scale(0);opacity:0}60%{transform:scale(1.1);opacity:1}100%{transform:scale(1)}}
.ra-success-actions{margin-top:2rem}

/* Responsive */
@media (max-width:640px){
    .ra-wizard-card{padding:24px 18px;border-radius:.75rem}
    .ra-progress{gap:.25rem;padding:0}
    .ra-progress-line{left:22px;right:22px}
    .ra-progress-dot{width:36px;height:36px;font-size:.85rem}
    .ra-progress-label{font-size:.6rem}
    .ra-field-grid{grid-template-columns:1fr}
    .ra-title{font-size:1.35rem}
    .ra-services-grid{grid-template-columns:1fr}
    .ra-day{padding:.55rem .1rem}
    .ra-day-num{font-size:1.1rem}
    .ra-actions-split{flex-direction:column-reverse}
    .ra-actions-split .ra-btn-ghost,.ra-actions-split .ra-btn-primary{width:100%}
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    const routes = {
        services: @json(route('frontend.booking.services')),
        slots: @json(route('frontend.booking.slots')),
        create: @json(route('frontend.booking.appointments')),
    };
    const csrf = document.querySelector('meta[name=csrf-token]')?.content;

    const state = { adim: 1, hizmet: null, tarih: null, saat: null, tarihGoster: '', haftaStart: null };

    const hataEl = document.getElementById('ra-hata');
    function hataGoster(msg){ hataEl.textContent = msg; hataEl.classList.remove('d-none'); document.querySelector('.ra-wizard-card').scrollIntoView({behavior:'smooth', block:'start'}); }
    function hataGizle(){ hataEl.classList.add('d-none'); }

    function stepGoster(n){
        state.adim = n;
        document.querySelectorAll('.ra-panel').forEach(el => el.classList.remove('is-active'));
        document.querySelector(`.ra-panel[data-panel="${n}"]`)?.classList.add('is-active');
        document.querySelectorAll('.ra-progress-step').forEach(el => {
            const s = parseInt(el.dataset.step, 10);
            el.classList.remove('is-active','is-done');
            if (s < n) el.classList.add('is-done');
            else if (s === n) el.classList.add('is-active');
        });
        const dolu = n <= 1 ? 0 : n >= 4 ? 100 : ((n-1)/3)*100;
        document.querySelector('.ra-progress-line-fill').style.width = dolu + '%';
        document.querySelector('.ra-wizard-card').scrollIntoView({behavior:'smooth', block:'start'});
    }
    window.raStepGo = stepGoster;

    /* --- Adım 1: Hizmet --- */
    async function hizmetleriYukle(){
        try {
            const r = await fetch(routes.services, { headers: {'Accept':'application/json'} });
            const j = await r.json();
            const cont = document.getElementById('ra-hizmetler');
            const items = j.data || j.services || [];
            if (!items.length){ cont.innerHTML = '<div class="ra-empty">Şu an aktif hizmet bulunmuyor.</div>'; return; }
            cont.innerHTML = items.map(h => `
                <button type="button" class="ra-service-card" data-hizmet='${JSON.stringify({id:h.id, ad:h.ad||h.baslik||'Hizmet', sure:h.sure||30}).replace(/'/g,"&#39;")}'>
                    <div class="ra-service-name">${h.ad || h.baslik || 'Hizmet'}</div>
                    <div class="ra-service-meta">
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> ${h.sure || 30} dk</span>
                        ${h.fiyat ? `<span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg> ${h.fiyat_str || (h.fiyat + ' ₺')}</span>` : ''}
                    </div>
                    ${h.aciklama ? `<div class="ra-service-desc">${String(h.aciklama).substring(0,140)}${h.aciklama.length>140?'…':''}</div>` : ''}
                </button>`).join('');
            cont.querySelectorAll('.ra-service-card').forEach(b => {
                b.addEventListener('click', () => {
                    state.hizmet = JSON.parse(b.dataset.hizmet.replace(/&#39;/g,"'"));
                    document.getElementById('ra-secili-hizmet-ozet').textContent = `Seçilen hizmet: ${state.hizmet.ad} (${state.hizmet.sure} dk)`;
                    haftaBaslat();
                    stepGoster(2);
                    gunleriRender();
                });
            });
        } catch { hataGoster('Hizmetler yüklenemedi. Sayfayı yenileyip tekrar deneyin.'); }
    }

    /* --- Adım 2: Gün --- */
    const gunAdlari = ['Paz','Pzt','Sal','Çar','Per','Cum','Cmt'];
    const aylar = ['Oca','Şub','Mar','Nis','May','Haz','Tem','Ağu','Eyl','Eki','Kas','Ara'];
    function haftaBaslat(){ const b=new Date(); b.setHours(0,0,0,0); state.haftaStart=b; }
    function gunleriRender(){
        const w = state.haftaStart;
        const son = new Date(w.getTime()+6*86400000);
        document.getElementById('ra-week-label').textContent = `${w.getDate()} ${aylar[w.getMonth()]} - ${son.getDate()} ${aylar[son.getMonth()]}`;
        const bugun = new Date(); bugun.setHours(0,0,0,0);
        const prevBtn = document.getElementById('ra-prev-week');
        prevBtn.disabled = new Date(w.getTime()-86400000) < bugun;

        const gunler = document.getElementById('ra-gunler');
        gunler.innerHTML = '';
        for (let i=0; i<7; i++){
            const g = new Date(w.getTime()+i*86400000);
            const gecmis = g < bugun;
            const iso = g.toISOString().slice(0,10);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'ra-day' + (gecmis ? ' is-disabled' : '');
            btn.disabled = gecmis;
            btn.innerHTML = `<span class="ra-day-wday">${gunAdlari[g.getDay()]}</span><span class="ra-day-num">${g.getDate()}</span><span class="ra-day-mon">${aylar[g.getMonth()]}</span>`;
            if (!gecmis){
                btn.addEventListener('click', () => {
                    gunler.querySelectorAll('.ra-day').forEach(x => x.classList.remove('is-selected'));
                    btn.classList.add('is-selected');
                    state.tarih = iso;
                    state.tarihGoster = `${gunAdlari[g.getDay()]}, ${g.getDate()} ${aylar[g.getMonth()]}`;
                    setTimeout(() => saatleriYukle(), 220);
                });
            }
            gunler.appendChild(btn);
        }
    }
    document.getElementById('ra-prev-week').addEventListener('click', () => {
        const t = new Date(state.haftaStart.getTime()-7*86400000);
        const b = new Date(); b.setHours(0,0,0,0);
        state.haftaStart = t < b ? b : t;
        gunleriRender();
    });
    document.getElementById('ra-next-week').addEventListener('click', () => {
        state.haftaStart = new Date(state.haftaStart.getTime()+7*86400000);
        gunleriRender();
    });

    /* --- Adım 3: Saat --- */
    async function saatleriYukle(){
        stepGoster(3);
        document.getElementById('ra-saat-info').textContent = `${state.tarihGoster} · ${state.hizmet.ad}`;
        const saatler = document.getElementById('ra-saatler');
        saatler.innerHTML = '<div class="ra-empty">Saatler yükleniyor…</div>';
        try {
            const r = await fetch(`${routes.slots}?date=${state.tarih}`, { headers:{'Accept':'application/json'} });
            const j = await r.json();
            const slots = j.data?.slots || j.slots || j.data || [];
            if (!Array.isArray(slots) || !slots.length){
                saatler.innerHTML = '<div class="ra-empty">Bu gün için uygun saat bulunamadı. Lütfen başka bir gün seçin.</div>';
                return;
            }
            saatler.innerHTML = slots.map(s => {
                const t = typeof s === 'string' ? s : (s.saat || s.time || '');
                return `<button type="button" class="ra-slot" data-saat="${t}">${t}</button>`;
            }).join('');
            saatler.querySelectorAll('.ra-slot').forEach(b => b.addEventListener('click', () => {
                state.saat = b.dataset.saat;
                ozetGoster();
                stepGoster(4);
            }));
        } catch { hataGoster('Saatler yüklenemedi. Sayfayı yenileyip tekrar deneyin.'); }
    }

    /* --- Adım 4: Bilgiler --- */
    function ozetGoster(){
        document.getElementById('ra-ozet').innerHTML = `
            <div class="ra-summary-row"><span class="ra-summary-label">Hizmet</span><span class="ra-summary-value">${state.hizmet.ad}</span></div>
            <div class="ra-summary-row"><span class="ra-summary-label">Tarih</span><span class="ra-summary-value">${state.tarihGoster}</span></div>
            <div class="ra-summary-row"><span class="ra-summary-label">Saat</span><span class="ra-summary-value">${state.saat}</span></div>`;
    }

    document.getElementById('ra-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        hataGizle();
        const btn = document.getElementById('ra-submit');
        btn.disabled = true;
        const label = btn.querySelector('.ra-btn-label');
        const orig = label.textContent;
        label.textContent = 'Gönderiliyor…';

        const fd = new FormData(e.target);
        fd.append('hizmet_id', state.hizmet.id);
        fd.append('tarih', state.tarih);
        fd.append('saat', state.saat);

        try {
            const r = await fetch(routes.create, {
                method: 'POST', body: fd,
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
            });
            const j = await r.json();
            if (r.ok && (j.success || j.data)){
                document.getElementById('ra-basari-mesaj').textContent = j.message || `${state.tarihGoster} saat ${state.saat} için randevunuz oluşturuldu. Onay için sizinle iletişime geçeceğiz.`;
                stepGoster(5);
            } else {
                hataGoster(j.message || 'Randevu oluşturulamadı. Bilgilerinizi kontrol edin.');
                btn.disabled = false; label.textContent = orig;
            }
        } catch {
            hataGoster('Sunucuya ulaşılamadı. Bağlantınızı kontrol edip tekrar deneyin.');
            btn.disabled = false; label.textContent = orig;
        }
    });

    hizmetleriYukle();
})();
</script>
@endpush

@endsection
