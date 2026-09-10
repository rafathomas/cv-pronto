@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-accent focus:ring-accent rounded-lg shadow-sm text-sm']) }}>
