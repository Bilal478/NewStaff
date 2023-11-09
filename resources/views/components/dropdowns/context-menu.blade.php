<div x-data="{ open: false }" {{ $attributes->merge(['class' => 'relative']) }}>
    <button x-on:click="open = true" type="button" class="text-gray-500 w-8 h-8 flex items-center justify-center rounded-full transition duration-150 ease-in-out hover:bg-gray-50 relative" :class="{ 'bg-gray-50': open === true }">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
        </svg>
    </button>
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-on:click.away="open = false"
        x-cloak
        class="absolute bg-white w-44 border shadow-md rounded-md right-0 z-10"
        style="top: 100%;">
        {{ $slot }}
    </div>
</div>
