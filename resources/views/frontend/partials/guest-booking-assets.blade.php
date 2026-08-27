{{-- Ortak misafir randevu CSS + JS (tek hekim). Formda #guest-booking-form + standart id'ler olmalı. --}}
@push('head')
<style>
    .booking-alert {
        margin: 1rem 0 0;
        padding: .85rem 1rem;
        border-radius: 12px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: .9rem;
    }
    .booking-success {
        margin: 1rem 0 0;
        padding: 1rem 1.1rem;
        border-radius: 12px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        font-size: .95rem;
        line-height: 1.55;
    }
    .kvkk-label {
        display: flex !important;
        align-items: flex-start;
        gap: .55rem;
        text-transform: none !important;
        letter-spacing: 0 !important;
        font-size: .86rem !important;
        font-weight: 500 !important;
        color: #475569 !important;
        cursor: pointer;
    }
    .kvkk-label input { margin-top: .2rem; width: auto; }
    #saat:disabled, #hizmet_id:disabled, #booking-submit:disabled {
        opacity: .65;
        cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const BOOKING_BASE = @json(url('/site-api/booking'));
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('#guest-booking-form input[name="_token"]')?.value
        || '';

    function apiHeaders(extra = {}) {
        return {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(CSRF ? { 'X-CSRF-TOKEN': CSRF } : {}),
            ...extra,
        };
    }

    const form = document.getElementById('guest-booking-form');
    if (!form || form.dataset.bookingInit === '1') return;
    form.dataset.bookingInit = '1';

    const hizmetEl = document.getElementById('hizmet_id');
    const tarihEl = document.getElementById('tarih');
    const saatEl = document.getElementById('saat');
    const alertEl = document.getElementById('booking-alert');
    const successEl = document.getElementById('booking-success');
    const submitBtn = document.getElementById('booking-submit');

    function showAlert(msg, ok) {
        if (!alertEl) return;
        if (successEl) successEl.hidden = true;
        alertEl.hidden = false;
        alertEl.textContent = msg;
        if (ok) {
            alertEl.style.background = '#ecfdf5';
            alertEl.style.borderColor = '#a7f3d0';
            alertEl.style.color = '#065f46';
        } else {
            alertEl.style.background = '';
            alertEl.style.borderColor = '';
            alertEl.style.color = '';
        }
    }
    function hideAlert() {
        if (!alertEl) return;
        alertEl.hidden = true;
        alertEl.textContent = '';
    }
    function showSuccess(html) {
        hideAlert();
        if (successEl) {
            successEl.hidden = false;
            successEl.innerHTML = html;
        }
        form.hidden = true;
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
            const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : null) || ('İstek başarısız (' + res.status + ')');
            throw new Error(msg);
        }
        return data;
    }

    async function loadServices() {
        if (!hizmetEl) return;
        hizmetEl.innerHTML = '<option value="">Yükleniyor…</option>';
        try {
            const res = await apiGet('/services');
            const list = res.data || [];
            if (!list.length) {
                hizmetEl.innerHTML = '<option value="">Aktif hizmet yok</option>';
                return;
            }
            hizmetEl.innerHTML = '<option value="">Hizmet seçin</option>' +
                list.map(h => {
                    const sure = h.sure ? ` (${h.sure} dk)` : '';
                    return `<option value="${h.id}">${h.ad || h.baslik || 'Hizmet'}${sure}</option>`;
                }).join('');
        } catch (e) {
            hizmetEl.innerHTML = '<option value="">Hizmetler yüklenemedi</option>';
            showAlert(e.message || 'Randevu sistemi kullanılamıyor.');
        }
    }

    async function loadSlots() {
        if (!saatEl || !tarihEl) return;
        const date = tarihEl.value;
        saatEl.innerHTML = '<option value="">Yükleniyor…</option>';
        saatEl.disabled = true;
        if (!date) {
            saatEl.innerHTML = '<option value="">Önce tarih seçin</option>';
            return;
        }
        try {
            const hid = hizmetEl?.value;
            let url = '/slots?date=' + encodeURIComponent(date);
            if (hid) url += '&hizmet_id=' + encodeURIComponent(hid);
            const res = await apiGet(url);
            const raw = (res.data && (res.data.musait || res.data.slots)) || [];
            const slots = (Array.isArray(raw) ? raw : []).filter(s => {
                if (typeof s === 'string') return true;
                const durum = s.durum || '';
                return s.musait !== false && durum !== 'dolu' && durum !== 'izin' && durum !== 'gecmis';
            });
            if (!slots.length) {
                saatEl.innerHTML = '<option value="">Bu tarihte boş slot yok</option>';
                return;
            }
            saatEl.innerHTML = '<option value="">Saat seçin</option>' +
                slots.map(s => {
                    const saat = typeof s === 'string' ? s : (s.saat || s);
                    const bitis = (typeof s === 'object' && s.saat_bitis) ? ' – ' + s.saat_bitis : '';
                    return `<option value="${saat}">${saat}${bitis}</option>`;
                }).join('');
            saatEl.disabled = false;
        } catch (e) {
            saatEl.innerHTML = '<option value="">Slotlar alınamadı</option>';
            showAlert(e.message);
        }
    }

    tarihEl?.addEventListener('change', () => { hideAlert(); loadSlots(); });
    hizmetEl?.addEventListener('change', () => { hideAlert(); if (tarihEl?.value) loadSlots(); });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideAlert();
        const gorusmeRadio = document.querySelector('input[name="gorusme_tipi"]:checked');
        const gorusmeTipi = gorusmeRadio
            ? gorusmeRadio.value
            : (document.getElementById('gorusme_tipi_hidden')?.value || 'yuz_yuze');
        const payload = {
            hizmet_id: Number(hizmetEl?.value),
            tarih: tarihEl?.value,
            saat: saatEl?.value,
            ad: document.getElementById('ad')?.value?.trim(),
            soyad: document.getElementById('soyad')?.value?.trim(),
            telefon: document.getElementById('telefon')?.value?.trim(),
            e_posta: document.getElementById('e_posta')?.value?.trim() || null,
            not: document.getElementById('not')?.value?.trim() || null,
            gorusme_tipi: gorusmeTipi,
            kvkk_onay: document.getElementById('kvkk_onay')?.checked ? 1 : 0,
            website_url: document.getElementById('website_url')?.value || '',
            otp_kod: document.getElementById('otp_kod')?.value?.trim() || null,
        };
        if (!payload.hizmet_id || !payload.tarih || !payload.saat) {
            showAlert('Hizmet, tarih ve saat seçimi zorunludur.');
            return;
        }
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Gönderiliyor…';
        }
        try {
            if (window.raGetRecaptchaToken) {
                payload.recaptcha_token = await window.raGetRecaptchaToken('randevu');
            }
            const res = await apiPost('/appointments', payload);
            const d = res.data || {};
            const yonet = d.yonetim_url
                ? `<br><a href="${d.yonetim_url}" style="color:var(--brand-700,#0f766e);font-weight:700">Randevuyu yönet / iptal et →</a>`
                : '';
            const hesap = d.hesap_url
                ? `<br><a href="${d.hesap_url}" style="color:var(--brand-700,#0f766e);font-weight:700">Hesap oluştur →</a>`
                : '';
            const join = d.platform_join_url
                ? `<br><a href="${d.platform_join_url}" style="color:var(--brand-700,#0f766e);font-weight:700">Görüşmeye katıl (platform) →</a>`
                : (payload.gorusme_tipi === 'online'
                    ? `<br><span style="opacity:.85">Online görüşme odası onay sonrası randevu yönetim sayfanızda açılır.</span>`
                    : '');
            showSuccess(
                `<strong>${res.message || 'Talebiniz alındı.'}</strong><br>` +
                `Tarih: ${d.tarih || payload.tarih} · Saat: ${d.saat || payload.saat}<br>` +
                `Görüşme: ${payload.gorusme_tipi === 'online' ? 'Online' : 'Yüz yüze'}<br>` +
                `Durum: ${d.durum || '-'}<br>` +
                `<span style="opacity:.85">${d.hesap_mesaji || ''}</span>` +
                yonet + join + hesap
            );
        } catch (err) {
            showAlert(err.message || 'Randevu oluşturulamadı.');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Randevu Talebini Gönder';
            }
        }
    });

    document.getElementById('otp-send-btn')?.addEventListener('click', async () => {
        const telefon = document.getElementById('telefon')?.value?.trim();
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
    if (otpBlock) otpBlock.style.display = 'block';

    loadServices();
})();
</script>
@endpush
