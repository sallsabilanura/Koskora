@props(['active', 'icon', 'href'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3.5 rounded-xl bg-brand-blue text-white shadow-lg shadow-brand-blue/20 transition-all font-bold text-sm'
            : 'flex items-center px-4 py-3.5 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-brand-blue transition-all font-bold text-sm group';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
    @if($icon)
        <i class="{{ $icon }} w-5 h-5 mr-3 flex items-center justify-center transition-colors {{ ($active ?? false) ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}"></i>
    @endif
    <span class="truncate">{{ $slot }}</span>
</a>
