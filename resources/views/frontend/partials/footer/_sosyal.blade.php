{{-- Sosyal ikon listesi — $f, $stil: 'daire' | 'pill' --}}
@php $stil = $stil ?? 'daire'; @endphp

@if($f['goster']['sosyal'])
    <ul class="ftr-sosyal ftr-sosyal--{{ $stil }}">
        @foreach($f['sosyal'] as $s)
            <li>
                <a href="{{ $s['url'] }}" target="_blank" rel="noopener" aria-label="{{ $s['ad'] }}">
                    <i class="{{ $s['ikon'] }}" aria-hidden="true"></i>
                </a>
            </li>
        @endforeach
    </ul>
@endif
