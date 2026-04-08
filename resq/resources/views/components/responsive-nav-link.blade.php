@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-3 border-l-4 border-primary-500 text-start text-base font-medium text-primary-700 bg-primary-50 focus:outline-none transition duration-150 ease-in-out rounded-r-lg'
            : 'block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-medium text-slate-600 hover:text-primary-600 hover:bg-slate-50 hover:border-slate-300 focus:outline-none transition duration-150 ease-in-out rounded-r-lg';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
