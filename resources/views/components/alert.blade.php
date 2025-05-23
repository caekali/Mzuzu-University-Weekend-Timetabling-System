@props([
    'type' => 'info', // options: success, danger, warning, info
    'message',
])

@php
    $baseClasses = 'rounded-md px-4 py-3 text-sm font-medium';
    $typeStyles = [
        'success' => 'bg-green-50 text-green-700 border border-green-300',
        'danger' => 'bg-red-50 text-red-700 border border-red-300',
        'warning' => 'bg-yellow-50 text-yellow-800 border border-yellow-300',
        'info' => 'bg-blue-50 text-blue-700 border border-blue-300',
    ];
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses {$typeStyles[$type]}"]) }}>
    {{ $message }}
</div>
