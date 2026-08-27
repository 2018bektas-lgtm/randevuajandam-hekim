@extends('panel.layouts.app')

@section('baslik', 'Sayfa Builder')
@section('sayfa_baslik', 'Anasayfa Düzenle')

@section('icerik')
<div class="space-y-6">

    {{-- Bilgi bandı --}}
    <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-sm">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h3 class="text-base font-bold font-display text-[#111827]">Modüler Anasayfa</h3>
                <p class="text-xs text-[#6B7280] mt-1 max-w-xl">
                    Aktif temanız <strong class="text-[#C96A2B]">{{ $tema['ad'] ?? $temaId }}</strong>.
                    Modülleri sürükleyerek sıralayabilir, aç/kapat toggle'ı ile gizleyebilir, ⚙ butonundan içeriğini düzenleyebilirsiniz.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ url('/') }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-1.5 px-3 py-2 text-[11px] font-bold text-[#1F2937] bg-white border border-[#E5E7EB] hover:bg-slate-50 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    Canlı Önizleme
                </a>
            </div>
        </div>

        {{-- Tema seçici --}}
        <form action="{{ route('panel.sayfa-builder.tema.sec') }}" method="POST" class="mt-4 flex items-center gap-2 flex-wrap">
            @csrf
            <label class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Tema:</label>
            <select name="tema_id" class="text-xs px-3 py-2 rounded-lg border border-[#E5E7EB] bg-white">
                @foreach($temalar as $tid => $tinfo)
                    <option value="{{ $tid }}" @selected($tid === $temaId)>{{ $tinfo['ad'] ?? $tid }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-2 rounded-lg bg-[#1F2937] hover:bg-black text-white text-[11px] font-bold transition">Uygula</button>
        </form>
    </div>

    {{-- Renk Paleti --}}
    <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-sm">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <h3 class="text-sm font-bold font-display text-[#111827]">Renk Paleti</h3>
                <p class="text-xs text-[#6B7280] mt-0.5">Hazır paletlerden seçin veya kendi renklerinizi girin.</p>
            </div>
            <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-[#FFF7ED] text-[#C96A2B]">Aktif: {{ $aktifPalet['ad'] ?? $aktifPalet['kod'] }}</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($paletler as $pkod => $palet)
                <button type="button" onclick="paletSec('{{ $pkod }}')"
                        class="palet-kart p-3 rounded-xl border text-left transition {{ ($aktifPalet['kod'] ?? '') === $pkod ? 'border-[#C96A2B] bg-[#FFF7ED]/40' : 'border-[#E5E7EB] hover:border-[#C96A2B]' }}">
                    <div class="flex gap-1 mb-2">
                        <span class="w-6 h-6 rounded-full border border-white shadow-sm" style="background:{{ $palet['primary'] }}"></span>
                        <span class="w-6 h-6 rounded-full border border-white shadow-sm" style="background:{{ $palet['accent'] }}"></span>
                        <span class="w-6 h-6 rounded-full border border-white shadow-sm" style="background:{{ $palet['bg'] }}"></span>
                    </div>
                    <span class="text-[11px] font-bold text-[#111827]">{{ $palet['ad'] }}</span>
                </button>
            @endforeach
            <button type="button" onclick="paletOzelAc()"
                    class="palet-kart p-3 rounded-xl border border-dashed border-[#E5E7EB] hover:border-[#C96A2B] text-left transition">
                <div class="flex gap-1 mb-2">
                    <span class="w-6 h-6 rounded-full border border-[#E5E7EB]" style="background:linear-gradient(45deg,#f00,#0f0,#00f)"></span>
                </div>
                <span class="text-[11px] font-bold text-[#111827]">Özel Renkler…</span>
            </button>
        </div>
    </div>

    {{-- Modül Listesi --}}
    <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold font-display text-[#111827]">Modüller</h3>
                <p class="text-xs text-[#6B7280] mt-0.5">Sürükleyerek sıralayın · Toggle ile aç/kapat · ⚙ ile düzenle</p>
            </div>
            <button type="button" id="siraKaydetBtn" onclick="siraKaydet()" disabled
                    class="px-3 py-2 rounded-lg bg-[#C96A2B] hover:bg-[#B55A20] disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-[11px] font-bold transition">
                Sıralamayı Kaydet
            </button>
        </div>

        <ul id="modulListesi" class="space-y-2">
            @foreach($moduller as $m)
                <li data-kod="{{ $m['kod'] }}" data-tanim='@json($m['tanim'])' data-ayar='@json($m['ayar'])'
                    class="modul-item flex items-center gap-3 p-3 rounded-xl border border-[#E5E7EB] bg-white hover:bg-slate-50 transition cursor-move">
                    <span class="drag-handle text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16"/></svg>
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-[#111827]">{{ $m['tanim']['ad'] ?? $m['kod'] }}</div>
                        <div class="text-[10px] text-[#6B7280] truncate">{{ $m['tanim']['aciklama'] ?? '' }}</div>
                    </div>
                    @if(! $m['silinebilir'])
                        <span class="px-2 py-0.5 text-[9px] font-bold uppercase bg-slate-100 text-slate-600 rounded-full">Zorunlu</span>
                    @endif
                    <button type="button" onclick="modulDuzenleAc('{{ $m['kod'] }}')"
                            class="p-2 rounded-lg hover:bg-slate-100 text-slate-600 transition" title="Düzenle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="modul-aktif sr-only peer" @checked($m['aktif']) @if(! $m['silinebilir']) disabled @endif>
                        <div class="w-10 h-6 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-4"></div>
                    </label>
                </li>
            @endforeach
        </ul>
    </div>

</div>

{{-- Modül Düzenle Modal --}}
<div id="modulModal" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-2xl max-w-3xl w-full overflow-hidden flex flex-col max-h-[92vh]">
        <div class="p-5 border-b border-[#E5E7EB] flex items-start justify-between gap-3 shrink-0">
            <div>
                <h3 id="modulModalBaslik" class="text-lg font-bold font-display text-[#111827]">Modül</h3>
                <p id="modulModalAciklama" class="text-xs text-[#6B7280] mt-1"></p>
            </div>
            <button type="button" onclick="modulDuzenleKapat()" class="text-[#6B7280] hover:text-[#111827] p-1">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="modulForm" class="p-5 overflow-y-auto flex-1 space-y-4"></form>

        <div class="p-4 border-t border-[#E5E7EB] flex items-center justify-end gap-2 shrink-0">
            <button type="button" onclick="modulDuzenleKapat()" class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-[#6B7280] hover:bg-slate-50 text-xs font-bold">İptal</button>
            <button type="button" id="modulKaydetBtn" onclick="modulKaydet()" class="px-4 py-2 rounded-xl bg-[#C96A2B] hover:bg-[#B55A20] text-white text-xs font-bold">Kaydet</button>
        </div>
    </div>
</div>

{{-- Özel Palet Modal --}}
<div id="paletOzelModal" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-2xl max-w-md w-full overflow-hidden">
        <div class="p-5 border-b border-[#E5E7EB] flex items-start justify-between">
            <h3 class="text-lg font-bold font-display text-[#111827]">Özel Renkler</h3>
            <button type="button" onclick="paletOzelKapat()" class="text-[#6B7280] hover:text-[#111827]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-5 space-y-3">
            @foreach(['primary'=>'Ana Renk','accent'=>'Vurgu','bg'=>'Zemin','text'=>'Metin','text_light'=>'Açık Metin'] as $k=>$l)
                <div class="flex items-center justify-between gap-3">
                    <label class="text-xs font-bold text-[#111827] w-24">{{ $l }}</label>
                    <input type="color" id="ozel-{{ $k }}" value="{{ $aktifPalet[$k] ?? '#000000' }}"
                           class="w-16 h-10 rounded-lg cursor-pointer border border-[#E5E7EB]">
                </div>
            @endforeach
        </div>
        <div class="p-4 border-t border-[#E5E7EB] flex items-center justify-end gap-2">
            <button type="button" onclick="paletOzelKapat()" class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-[#6B7280] hover:bg-slate-50 text-xs font-bold">İptal</button>
            <button type="button" onclick="paletOzelKaydet()" class="px-4 py-2 rounded-xl bg-[#C96A2B] hover:bg-[#B55A20] text-white text-xs font-bold">Uygula</button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const TEMA_ID = @json($temaId);
const CSRF = document.querySelector('meta[name=csrf-token]')?.content;
const routes = {
    sira: @json(route('panel.sayfa-builder.sira')),
    modul: @json(url('/yonetim/sayfa-builder/modul')),
    palet: @json(route('panel.sayfa-builder.palet.sec')),
};

/* Drag-drop sıralama */
let siraDegisti = false;
new Sortable(document.getElementById('modulListesi'), {
    handle: '.drag-handle',
    animation: 150,
    onEnd: () => { siraDegisti = true; document.getElementById('siraKaydetBtn').disabled = false; }
});
document.querySelectorAll('.modul-aktif').forEach(el => {
    el.addEventListener('change', () => { siraDegisti = true; document.getElementById('siraKaydetBtn').disabled = false; });
});

async function siraKaydet() {
    const btn = document.getElementById('siraKaydetBtn');
    btn.disabled = true; btn.textContent = 'Kaydediliyor...';
    const liste = [...document.querySelectorAll('#modulListesi li')].map((li, i) => ({
        kod: li.dataset.kod,
        aktif: li.querySelector('.modul-aktif').checked,
        sira: (i + 1) * 10,
    }));
    try {
        const r = await fetch(routes.sira, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json'},
            body: JSON.stringify({ tema_id: TEMA_ID, liste }),
        });
        const j = await r.json();
        if (j.success) { siraDegisti = false; btn.textContent = 'Kaydedildi ✓'; setTimeout(() => btn.textContent = 'Sıralamayı Kaydet', 1500); }
        else alert(j.message || 'Kaydedilemedi');
    } catch { alert('Sunucuya ulaşılamadı.'); btn.disabled = false; btn.textContent = 'Sıralamayı Kaydet'; }
}

/* Modül düzenle */
let mevcutKod = null;
function modulDuzenleAc(kod) {
    const li = document.querySelector(`#modulListesi li[data-kod="${kod}"]`);
    if (!li) return;
    const tanim = JSON.parse(li.dataset.tanim);
    const ayar = JSON.parse(li.dataset.ayar || '{}');
    mevcutKod = kod;

    document.getElementById('modulModalBaslik').textContent = tanim.ad || kod;
    document.getElementById('modulModalAciklama').textContent = tanim.aciklama || '';

    const form = document.getElementById('modulForm');
    form.innerHTML = '';
    for (const [alanKod, alan] of Object.entries(tanim.alanlar || {})) {
        const val = ayar[alanKod] !== undefined ? ayar[alanKod] : (alan.varsayilan ?? '');
        form.appendChild(alanElement(alanKod, alan, val));
    }

    const m = document.getElementById('modulModal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function alanElement(kod, alan, val) {
    const wrap = document.createElement('div');
    const label = document.createElement('label');
    label.className = 'block text-[10px] font-bold uppercase text-slate-600 mb-1';
    label.textContent = alan.label || kod;
    wrap.appendChild(label);

    let input;
    switch (alan.tip) {
        case 'uzun_metin':
        case 'liste':
            input = document.createElement('textarea');
            input.rows = alan.tip === 'liste' ? 4 : 3;
            input.value = val ?? '';
            break;
        case 'sayi':
            input = document.createElement('input');
            input.type = 'number';
            input.value = val ?? 0;
            break;
        case 'ikon_baslik_metin':
            input = document.createElement('textarea');
            input.rows = 6;
            input.value = JSON.stringify(val ?? [], null, 2);
            input.dataset.json = '1';
            break;
        case 'db_kaynak':
            input = document.createElement('input');
            input.type = 'text';
            input.value = val ?? '';
            input.readOnly = true;
            input.className = 'bg-slate-50 cursor-not-allowed';
            break;
        default:
            input = document.createElement('input');
            input.type = 'text';
            input.value = val ?? '';
    }
    input.name = kod;
    input.className = (input.className || '') + ' w-full rounded-xl border border-[#E5E7EB] px-3 py-2 text-xs';
    wrap.appendChild(input);
    return wrap;
}
function modulDuzenleKapat() {
    const m = document.getElementById('modulModal');
    m.classList.add('hidden'); m.classList.remove('flex');
    mevcutKod = null;
}
async function modulKaydet() {
    if (!mevcutKod) return;
    const btn = document.getElementById('modulKaydetBtn');
    btn.disabled = true; btn.textContent = 'Kaydediliyor...';
    const form = document.getElementById('modulForm');
    const data = new FormData();
    data.append('tema_id', TEMA_ID);
    for (const el of form.querySelectorAll('input, textarea')) {
        let v = el.value;
        if (el.dataset.json) {
            try { v = JSON.stringify(JSON.parse(v)); }
            catch { alert('İkon+Başlık+Metin alanı geçerli JSON değil.'); btn.disabled = false; btn.textContent = 'Kaydet'; return; }
        }
        data.append(el.name, v);
    }
    try {
        const r = await fetch(routes.modul + '/' + mevcutKod, {
            method: 'POST', body: data,
            headers: {'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json'},
        });
        const j = await r.json();
        if (j.success) { modulDuzenleKapat(); location.reload(); }
        else alert(j.message || 'Kaydedilemedi');
    } catch { alert('Sunucuya ulaşılamadı.'); }
    btn.disabled = false; btn.textContent = 'Kaydet';
}

/* Palet */
async function paletSec(kod) {
    try {
        const r = await fetch(routes.palet, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json'},
            body: JSON.stringify({ palet_kod: kod }),
        });
        const j = await r.json();
        if (j.success) location.reload(); else alert(j.message || 'Hata');
    } catch { alert('Sunucuya ulaşılamadı.'); }
}
function paletOzelAc() { const m = document.getElementById('paletOzelModal'); m.classList.remove('hidden'); m.classList.add('flex'); }
function paletOzelKapat() { const m = document.getElementById('paletOzelModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
async function paletOzelKaydet() {
    const ozel = {
        primary: document.getElementById('ozel-primary').value,
        accent: document.getElementById('ozel-accent').value,
        bg: document.getElementById('ozel-bg').value,
        text: document.getElementById('ozel-text').value,
        text_light: document.getElementById('ozel-text_light').value,
    };
    try {
        const r = await fetch(routes.palet, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json'},
            body: JSON.stringify({ palet_kod: 'ozel', ozel }),
        });
        const j = await r.json();
        if (j.success) location.reload(); else alert(j.message || 'Hata');
    } catch { alert('Sunucuya ulaşılamadı.'); }
}
</script>
@endpush

@endsection
