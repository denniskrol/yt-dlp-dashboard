@props(['value'])

<label {{ $attributes->merge(['class' => 'block p-2 font-medium text-sm text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>
