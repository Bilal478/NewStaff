<div x-data="{ open: false }" {{ $attributes }}>
    <div
        x-show="open"
        x-cloak
        x-on:click="open = true"
        class="fixed inset-0 bg-red-200 z-20 p-4 md:p-10"
        style="background-color: rgba(0, 0, 0, 0.5); overflow:scroll;"
    >
        <div
            x-show="open"
            x-on:click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-12"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-12"
            class="bg-white max-w-xl mx-auto rounded-md px-6 md:px-8 py-6 relative"
        >
            <div class="absolute right-4 top-4">
                <button x-on:click="open = false" type="button" class="text-gray-400 hover:text-gray-700">
                    <x-svgs.x class="h-5 w-5" />
                </button>
            </div>

            {{ $slot }}
        </div>
    </div>
</div>
