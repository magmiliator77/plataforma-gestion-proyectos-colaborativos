{{-- Botón principal: el rojo de la portada --}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#e0261b] border border-transparent rounded-md font-semibold text-sm text-white hover:bg-[#c11f15] focus:bg-[#c11f15] active:bg-[#c11f15] focus:outline-none focus:ring-2 focus:ring-[#e0261b] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
