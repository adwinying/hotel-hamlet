@props([
    'component' => 'button',
    'variant'   => 'default',
    'size'      => 'md',
])

@php
$attrs = $attributes->merge([
    'class' => implode(' ', [
        'rounded shadow transition duration-100',
        match ($variant) {
            'primary' => 'bg-cyan-500 hover:bg-cyan-400 text-gray-50',
            'danger'  => 'bg-red-500 hover:bg-red-400 text-gray-50',
            'default' => 'bg-gray-500 hover:bg-gray-400 text-gray-50',
        },
        match ($size) {
            'sm' => 'px-2 py-1 text-sm',
            'md' => 'px-3 py-2 text-md',
            'lg' => 'px-4 py-3 text-lg',
        },
        match ($sizeMd ?? $size) {
            'sm' => 'md:px-2 md:py-1 md:text-sm',
            'md' => 'md:px-3 md:py-2 md:text-md',
            'lg' => 'md:px-4 md:py-3 md:text-lg',
        },
    ]),
]);
@endphp

@switch ($component)
    @case('button')
        <button {{ $attrs }}>{{ $slot }}</button>
        @break
    @case('a')
        <a {{ $attrs }}>{{ $slot }}</a>
        @break
    @default
        @php throw new Exception('Invalid component type'); @endphp
@endswitch
