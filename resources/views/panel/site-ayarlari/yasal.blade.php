@extends('panel.layouts.app')
@section('baslik', 'Site AyarlarÄ± Â· Yasal Metinler')
@section('sayfa_baslik', 'Site AyarlarÄ± Â· Yasal')

@section('icerik')
@include('panel.site-ayarlari._shell')

<form method="POST" action="{{ route('panel.site-ayarlari.yasal.kaydet') }}" class="sa-wrap">
    @csrf
    <div class="sa-layout">
        <div class="sa-card">
            <div class="sa-card-head">
                <div>
                    <h3>Hekim sitesi yasal metinleri</h3>
                    <p class="sa-hint">
                        Bu metinler <strong>sizin hekim sitenizin</strong> ziyaretÃ§ileri ve randevu formlarÄ± iÃ§indir.
                        Randevu Ajandam platformunun (randevuajandam.com) yasal sayfalarÄ±ndan
                        <strong>ayrÄ±dÄ±r</strong> â€” oradaki metinler SaaS aboneliÄŸi iÃ§indir.
                    </p>
                </div>
                <span class="sa-badge">KVKK</span>
            </div>
            <div class="sa-card-body space-y-4">
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-3 text-[11px] text-amber-950 leading-relaxed">
                    <strong>Ã–neri:</strong> Veri sorumlusu olarak kliniÄŸinizin unvanÄ±, iletiÅŸim bilgisi ve
                    hangi verileri (ad, telefon, randevu notu vb.) hangi amaÃ§la iÅŸlediÄŸinizi yazÄ±n.
                    BoÅŸ bÄ±rakÄ±rsanÄ±z sitede kÄ±sa bir varsayÄ±lan metin gÃ¶sterilir; lÃ¼tfen kendi metninizi kaydedin.
                </div>

                <div class="sa-field">
                    <label class="sa-label">KVKK aydÄ±nlatma metni</label>
                    <p class="sa-hint !mt-0 !mb-1.5">Public: <a href="{{ $publicUrls['kvkk'] }}" target="_blank" class="text-brand-600 font-semibold underline">{{ $publicUrls['kvkk'] }}</a></p>
                    <textarea name="kvkk" rows="12" class="sa-textarea font-mono text-xs"
                              placeholder="KVKK AydÄ±nlatma Metniâ€¦">{{ old('kvkk', $ayarlar['kvkk']) }}</textarea>
                </div>

                <div class="sa-field">
                    <label class="sa-label">Gizlilik politikasÄ±</label>
                    <p class="sa-hint !mt-0 !mb-1.5">Public: <a href="{{ $publicUrls['gizlilik'] }}" target="_blank" class="text-brand-600 font-semibold underline">{{ $publicUrls['gizlilik'] }}</a></p>
                    <textarea name="gizlilik" rows="10" class="sa-textarea font-mono text-xs"
                              placeholder="Gizlilik politikasÄ±â€¦">{{ old('gizlilik', $ayarlar['gizlilik']) }}</textarea>
                </div>

                <div class="sa-field">
                    <label class="sa-label">KullanÄ±m koÅŸullarÄ± (opsiyonel)</label>
                    <p class="sa-hint !mt-0 !mb-1.5">Public: <a href="{{ $publicUrls['kullanim'] }}" target="_blank" class="text-brand-600 font-semibold underline">{{ $publicUrls['kullanim'] }}</a></p>
                    <textarea name="kullanim" rows="8" class="sa-textarea font-mono text-xs"
                              placeholder="Site kullanÄ±m koÅŸullarÄ±â€¦">{{ old('kullanim', $ayarlar['kullanim']) }}</textarea>
                </div>
            </div>
            <div class="sa-card-foot">
                <button type="submit" class="sa-btn sa-btn-primary">Yasal metinleri kaydet</button>
            </div>
        </div>
    </div>
</form>
@endsection

