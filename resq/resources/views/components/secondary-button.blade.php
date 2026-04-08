<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary w-full sm:w-auto text-sm']) }}>
    {{ $slot }}
</button>
