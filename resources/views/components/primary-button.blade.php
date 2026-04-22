<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-brand-blue border border-transparent rounded-[0.875rem] font-bold text-[11px] text-white uppercase tracking-[0.2em] hover:bg-brand-red focus:bg-brand-red active:bg-brand-blue focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
