{{-- Footer (tema paketi olmayan yedek layout) — tasarım seçimi tema bazlıdır. --}}
@include('frontend.partials.footer.index', ['doktor' => $doktor ?? [], 'waGoster' => true])
