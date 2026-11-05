@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-stone-300 focus:border-stone-900 focus:ring-stone-900 rounded-md']) }}>
