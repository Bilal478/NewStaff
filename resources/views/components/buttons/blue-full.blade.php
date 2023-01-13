@props([
    'text',
    'type' => 'button'
])

<div class="block w-full rounded-md shadow-sm">
    <button
        type="{{ $type }}"
        {{ $attributes->whereStartsWith('wire:click') }}
        class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out">
        {{ $text }}
    </button>
</div>
