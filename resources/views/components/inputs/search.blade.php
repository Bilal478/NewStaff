@props([
    'name' => '',
])
<div class="relative text-gray-400 focus-within:text-blue-600 {{ $attributes->get('class') }}">
    <input
        id="{{ $name }}"
        type="text"
        name="search"
        {{ $attributes->whereStartsWith('wire:model') }}
        {{ $attributes->whereStartsWith('placeholder') }}
        class="h-10 appearance-none block w-full pl-10 pr-3 py-2 border border-gray-300 shadow-sm rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-400 transition duration-150 ease-in-out text-sm leading-5"
    >
    <div class="absolute inset-y-0 left-0 flex items-center justify-center pl-3 pointer-events-none">
        <x-svgs.search class="w-5 h-5" />
    </div>
</div>
