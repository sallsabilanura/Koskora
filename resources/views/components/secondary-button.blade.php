<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-white border-2 border-slate-200 rounded-[0.875rem] font-bold text-[11px] text-slate-600 uppercase tracking-[0.15em] shadow-sm hover:bg-slate-50 focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
