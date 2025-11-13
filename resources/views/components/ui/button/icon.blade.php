@props([
    'variant' => 'ghost',
    'color' => 'default'
])

@php
$baseClasses = 'inline-flex items-center justify-center rounded-full h-8 w-8 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2';

$variantClasses = [
    'ghost' => 'hover:bg-gray-100 dark:hover:bg-gray-700',
    'outline' => 'border border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700',
];

$colorClasses = [
    'default' => 'text-gray-500 dark:text-gray-400 focus:ring-indigo-500',
    'destructive' => 'text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/50 focus:ring-red-500',
];

$classes = implode(' ', [
    $baseClasses,
    $variantClasses[$variant] ?? $variantClasses['ghost'],
    $colorClasses[$color] ?? $colorClasses['default'],
]);
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
