@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-200 focus:border-brand-blue focus:ring-0 rounded-xl shadow-sm']) !!}>
