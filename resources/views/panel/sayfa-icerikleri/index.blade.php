@extends('panel.layouts.app')

@section('baslik', 'Sayfa Başlıkları & Bannerlar')
@section('sayfa_baslik', 'Sayfa Başlıkları & Bannerlar')

@section('icerik')
<div class="space-y-6">

    <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-sm">
        <h3 class="text-base font-bold font-display text-[#111827]">Frontend Sayfaları</h3>
        <p class="text-xs text-[#6B7280] mt-1 max-w-2xl">
            Her sayfa için üstteki <strong>banner (başlık bandı)</strong> içeriğini buradan düzenleyin.
            Anasayfa modülleri ayrı yerde yönetilir → <a href="{{ route('panel.sayfa-builder.index') }}" class="text-[#C96A2B] font-bold underline">Ana Sayfa Tasarımı</a>.
        </p>
    </div>

    {{-- Sayfa listesi (soldaki tab + sağdaki form) --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

        {{-- Sol: sayfa tabları --}}
        <div class="lg:col-span-1">
            <div class="p-3 rounded-2xl bg-white border border-[#E5E7EB] shadow-sm sticky top-4">
                <div class="text-[10px] font-bold uppercase text-[#6B7280] tracking-wider px-2 py-1.5 mb-1">Sayfalar</div>
                <div class="space-y-1" id="sayfa-tablar">
                    @foreach($sayfalar as $kod => $tanim)
                        <button type="button" data-sayfa-kod="{{ $kod }}"
                                class="sayfa-tab w-full flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold text-left transition {{ $loop->first ? 'bg-[#FFF7ED] text-[#C96A2B]' : 'text-[#4B5563] hover:bg-slate-50' }}">
                            <span>{{ $tanim['label'] ?? $kod }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sağ: seçili sayfa formu --}}
        <div class="lg:col-span-3 space-y-4">
            @foreach($sayfalar as $kod => $tanim)
                <div class="sayfa-form-panel {{ $loop->first ? '' : 'hidden' }}" data-sayfa-form="{{ $kod }}">
                    <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-sm">
                        <div class="flex items-start justify-between gap-3 flex-wrap mb-4">
                            <div>
                                <h3 class="text-base font-bold font-display text-[#111827]">{{ $tanim['label'] ?? $kod }}</h3>
                                <p class="text-xs text-[#6B7280] mt-0.5">{{ $tanim['aciklama'] ?? '' }}</p>
                            </div>
                            @if(isset($tanim['link']))
                                <a href="{{ route($tanim['link']) }}" class="text-[11px] font-bold text-[#C96A2B] hover:underline">
                                    Ayrı sayfada düzenle →
                                </a>
                            @endif
                        </div>

                        <form class="sayfa-form space-y-4" data-kod="{{ $kod }}">
                            @csrf
                            @foreach(($tanim['alanlar'] ?? []) as $alanKod => $alan)
                                @php $val = $ayarlar[$kod][$alanKod] ?? ($alan['varsayilan'] ?? ''); @endphp
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-[#6B7280] tracking-wider mb-1">
                                        {{ $alan['label'] ?? $alanKod }}
                                    </label>
                                    @if(($alan['tip'] ?? 'metin') === 'uzun_metin')
                                        <textarea name="{{ $alanKod }}" rows="3"
                                                  class="w-full rounded-xl border border-[#E5E7EB] px-3 py-2 text-xs">{{ $val }}</textarea>
                                    @elseif(($alan['tip'] ?? 'metin') === 'resim')
                                        <div class="resim-upload">
                                            <input type="hidden" name="{{ $alanKod }}" value="{{ $val }}" class="resim-url">
                                            <div class="flex items-center gap-3">
                                                <div class="resim-preview shrink-0 w-24 h-16 rounded-lg border border-[#E5E7EB] bg-slate-50 overflow-hidden flex items-center justify-center">
                                                    @if($val)
                                                        <img src="{{ $val }}" alt="" class="w-full h-full object-cover">
                                                    @else
                                                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1 flex items-center gap-2 flex-wrap">
                                                    <label class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-slate-800 hover:bg-black text-white text-[11px] font-bold transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12"/></svg>
                                                        Görsel Yükle
                                                        <input type="file" accept="image/*" class="hidden resim-input">
                                                    </label>
                                                    @if($val)
                                                        <button type="button" class="resim-sil px-3 py-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-[11px] font-bold transition">Kaldır</button>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="resim-hata mt-2 text-[10px] text-red-600 hidden"></p>
                                        </div>
                                        <p class="mt-1 text-[10px] text-[#6B7280]">JPG, PNG, WEBP — max 5 MB. Boş bırakırsanız hekimin profil resmi kullanılır.</p>
                                    @endif
                                    @elseif(($alan['tip'] ?? 'metin') === 'sayi')
                                        <input type="number" name="{{ $alanKod }}" value="{{ $val }}"
                                               class="w-full rounded-xl border border-[#E5E7EB] px-3 py-2 text-xs">
                                    @else
                                        <input type="text" name="{{ $alanKod }}" value="{{ $val }}"
                                               class="w-full rounded-xl border border-[#E5E7EB] px-3 py-2 text-xs">
                                    @endif
                                </div>
                            @endforeach

                            @if(empty($tanim['alanlar']))
                                <p class="text-xs text-[#6B7280] italic">Bu sayfa için düzenlenebilir alan tanımlı değil.</p>
                            @else
                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#F5F5F4]">
                                    <span class="sayfa-form-msg text-[11px] font-bold hidden"></span>
                                    <button type="submit"
                                            class="px-4 py-2 rounded-xl bg-[#C96A2B] hover:bg-[#B55A20] text-white text-[11px] font-bold transition">
                                        Kaydet
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name=csrf-token]')?.content;

document.querySelectorAll('.sayfa-tab').forEach(b => b.addEventListener('click', () => {
    document.querySelectorAll('.sayfa-tab').forEach(x => { x.classList.remove('bg-[#FFF7ED]','text-[#C96A2B]'); x.classList.add('text-[#4B5563]','hover:bg-slate-50'); });
    b.classList.add('bg-[#FFF7ED]','text-[#C96A2B]');
    b.classList.remove('text-[#4B5563]','hover:bg-slate-50');
    document.querySelectorAll('.sayfa-form-panel').forEach(p => p.classList.add('hidden'));
    document.querySelector(`[data-sayfa-form="${b.dataset.sayfaKod}"]`)?.classList.remove('hidden');
}));

/* Görsel yükleme — tüm resim alanlarında */
document.querySelectorAll('.resim-upload').forEach(box => {
    const input = box.querySelector('.resim-input');
    const urlHidden = box.querySelector('.resim-url');
    const preview = box.querySelector('.resim-preview');
    const hata = box.querySelector('.resim-hata');
    const silBtn = box.querySelector('.resim-sil');

    input?.addEventListener('change', async () => {
        const file = input.files[0];
        if (!file) return;
        hata.classList.add('hidden');
        const fd = new FormData();
        fd.append('file', file);
        preview.innerHTML = '<div class="text-[10px] text-slate-500">Yükleniyor…</div>';

        try {
            const r = await fetch(@json(route('panel.upload.image')), {
                method: 'POST', body: fd,
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const j = await r.json();
            if (r.ok && j.success && j.url) {
                urlHidden.value = j.url;
                preview.innerHTML = `<img src="${j.url}" alt="" class="w-full h-full object-cover">`;
                if (!silBtn) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = 'Kaldır';
                    btn.className = 'resim-sil px-3 py-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-[11px] font-bold transition';
                    btn.addEventListener('click', silHandler);
                    input.parentElement.parentElement.appendChild(btn);
                }
            } else {
                hata.textContent = j.message || 'Yükleme başarısız';
                hata.classList.remove('hidden');
                preview.innerHTML = urlHidden.value
                    ? `<img src="${urlHidden.value}" alt="" class="w-full h-full object-cover">`
                    : '<svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159"/></svg>';
            }
        } catch {
            hata.textContent = 'Sunucuya ulaşılamadı';
            hata.classList.remove('hidden');
        }
        input.value = '';
    });

    function silHandler() {
        urlHidden.value = '';
        preview.innerHTML = '<svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159"/></svg>';
        box.querySelector('.resim-sil')?.remove();
    }
    silBtn?.addEventListener('click', silHandler);
});

document.querySelectorAll('.sayfa-form').forEach(f => f.addEventListener('submit', async e => {
    e.preventDefault();
    const msg = f.querySelector('.sayfa-form-msg');
    const btn = f.querySelector('button[type=submit]');
    btn.disabled = true; btn.textContent = 'Kaydediliyor…';
    msg?.classList.add('hidden');

    const fd = new FormData(f);
    try {
        const r = await fetch(@json(url('/yonetim/sayfa-icerikleri')) + '/' + f.dataset.kod, {
            method: 'POST',
            body: fd,
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
        const j = await r.json();
        if (msg) {
            msg.textContent = j.message || (r.ok ? 'Kaydedildi ✓' : 'Hata');
            msg.className = 'sayfa-form-msg text-[11px] font-bold ' + (r.ok ? 'text-emerald-600' : 'text-red-600');
        }
    } catch {
        if (msg) { msg.textContent = 'Sunucuya ulaşılamadı'; msg.className = 'sayfa-form-msg text-[11px] font-bold text-red-600'; }
    }
    btn.disabled = false; btn.textContent = 'Kaydet';
    setTimeout(() => msg?.classList.add('hidden'), 2500);
}));
</script>
@endpush

@endsection
