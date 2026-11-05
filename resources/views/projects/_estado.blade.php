{{-- Subvista para pintar el estado: @include('projects._estado', ['estado' => ...])
     Cada estado tiene su color, pero el texto siempre se ve, así no depende solo del color --}}
@php
    $colores = 'bg-stone-100 text-stone-700 ring-stone-300';
    if ($estado == 'en curso') {
        $colores = 'bg-amber-50 text-amber-800 ring-amber-300';
    }
    if ($estado == 'terminado') {
        $colores = 'bg-teal-50 text-teal-800 ring-teal-300';
    }
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $colores }}">
    {{ ucfirst($estado) }}
</span>
