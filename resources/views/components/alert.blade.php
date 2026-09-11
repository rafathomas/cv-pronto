@props(['type' => 'warning'])

@php
$variants = [
    'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
    'error' => 'bg-red-50 border-red-200 text-red-800',
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
];
$style = $variants[$type] ?? $variants['warning'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-lg border text-sm px-4 py-3 {$style}"]) }}>
    {{ $slot }}
</div>
