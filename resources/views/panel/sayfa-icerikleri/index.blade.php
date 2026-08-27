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
                                        <input type="text" name="{{ $alanKod }}" value="{{ $val }}"
                                               placeholder="https://... (görsel URL'si)"
                                               class="w-full rounded-xl border border-[#E5E7EB] px-3 py-2 text-xs font-mono">
                                        @if($val)
                                            <img src="{{ $val }}" alt="" class="mt-2 max-h-24 rounded-lg border border-[#E5E7EB]">
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
