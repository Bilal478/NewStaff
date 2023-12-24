<div x-data="{ open: false }" class="relative px-2">
    <button x-on:click="open = true" type="button"
        class="flex items-center px-3 py-2 w-full rounded-md hover:bg-gray-100"
        :class="{ 'bg-gray-100': open === true }">
        <div class="bg-blue-300 h-9 w-9 rounded-sm flex items-center justify-center">
            <x-svgs.office-building class="w-6 h-6 text-white" />
        </div>
        <div class="flex-1 ml-2 truncate flex flex-col justify-center items-start">
            <h2 class="text-gray-800 text-sm truncate">
                {{ $currentAccount->name }}
            </h2>
            <span class="text-xs text-gray-400">Company</span>
        </div>
    </button>

    {{-- <div x-show="open" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
        x-on:click.away="open = false" x-cloak class="absolute bg-white inset-x-0 mx-2 mt-1 border shadow-md rounded-md"
        style="top: 100%;">
        <ul>
            @foreach ($accounts as $account)
                <li class="p-1">
                    <button wire:click.prevent="changeAccount({{ $account->id }})"
                        class="w-full flex items-center justify-start px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <div class="bg-blue-300 h-7 w-7 rounded flex items-center justify-center">
                            <x-svgs.office-building class="w-4 h-4 text-white" />
                        </div>

                        <div class="flex-1 ml-2 text-xs truncate text-left">
                            {{ $account->name }}
                        </div>
                    </button>
                </li>
            @endforeach
            @role('owner')
                <li class="p-2 border-t">
                    <a href="{{ route('accounts.create') }}"
                        class="flex items-center px-3 py-1 text-xs text-blue-600 hover:text-blue-500">
                        Create new Company
                        <x-svgs.chevron-right class="w-3 h-3 ml-1" />
                    </a>
                </li>
            @endrole
        </ul>
    </div> --}}
</div>
