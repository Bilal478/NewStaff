@props([
    'svg' => '',
    'name',
])
<div class="p-1 {{ $attributes->get('class') }}">
    <button {{ $attributes->whereStartsWith('wire:click') }} x-on:click="open = false" type="button" class="w-full text-gray-400 px-3 py-2 flex items-center hover:bg-gray-100 rounded-md hover:text-blue-600">
        @if ($svg)
            <x-dynamic-component :component="$svg" class="w-5 h-5" />
        @endif
        <span class="font-montserrat font-semibold ml-2 text-xs text-gray-700">{{ $name }}</span>
    </button>
</div>
