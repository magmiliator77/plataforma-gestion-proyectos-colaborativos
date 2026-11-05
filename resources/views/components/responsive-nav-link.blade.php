@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-red-600 text-start text-base font-medium text-stone-900 bg-white focus:outline-none focus:bg-stone-100 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-100 hover:border-stone-300 focus:outline-none focus:text-stone-900 focus:bg-stone-100 focus:border-stone-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
