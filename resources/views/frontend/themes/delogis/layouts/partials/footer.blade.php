{{-- Footer — tasarım seçimi Site Ayarları → Footer (tema bazlı). --}}
@include('frontend.partials.footer.index', ['doktor' => $doktor ?? [], 'waGoster' => false])
