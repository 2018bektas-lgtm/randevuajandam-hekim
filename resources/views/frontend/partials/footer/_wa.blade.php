{{-- WhatsApp yüzen butonu — $f --}}
@if($f['whatsapp'] !== '' && ($doktor['whatsapp_goster'] ?? true))
    <a href="https://wa.me/{{ $f['whatsapp'] }}" target="_blank" rel="noopener"
       class="ftr-wa" aria-label="WhatsApp ile iletişim">
        <i class="fab fa-whatsapp" aria-hidden="true"></i>
    </a>
@endif
