@props([
    'route' => '',
    'svg'
])

<div class="flex items-center space-x-3 pb-12">
    <a href="{{ $route }}" class="font-montserrat text-xl font-semibold text-gray-500 flex items-center hover:text-blue-600">
        <x-dynamic-component :component="$svg" class="w-8 h-8 text-blue-600 mr-2" />
        {{ $slot }}
    </a>
    <span>/</span>
    <h2 class="font-montserrat text-xl font-semibold text-gray-700 flex items-center">
        {{ $breadcrum }}
    </h2>
</div>
