<div x-data="{ open: false }" class="relative px-2">
    <button x-on:click="open = true" type="button" class="flex items-center px-3 py-2 w-full rounded-md hover:bg-gray-100" :class="{ 'bg-gray-100': open === true }">
        <div class="bg-blue-300 h-9 w-9 rounded-sm flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <div class="flex-1 ml-2 truncate flex flex-col justify-center items-start">
            <h2 class="text-gray-800 text-sm truncate">
                Dickinson and Barton
            </h2>
            <span class="text-xs text-gray-400">Account</span>
        </div>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-on:click.away="open = false"
        x-cloak
        class="absolute bg-white inset-x-0 mx-2 mt-1 border shadow-md rounded-md"
        style="top: 100%;"
    >
        <ul>
            <li class="p-1">
                <a href="" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    <div class="bg-blue-300 h-7 w-7 rounded flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="flex-1 ml-2 truncate flex flex-col justify-center">
                        <h2 class="text-xs truncate">
                            Dickinson and Barton
                        </h2>
                    </div>
                </a>
            </li>
            <li class="p-1">
                <a href="" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    <div class="bg-blue-300 h-7 w-7 rounded flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="flex-1 ml-2 truncate flex flex-col justify-center">
                        <h2 class="text-xs truncate">
                            Netflix
                        </h2>
                    </div>
                </a>
            </li>
            <li class="p-2 border-t">
                <a href="" class="flex items-center px-3 py-1 text-xs text-blue-600 hover:text-blue-500">
                    Create new account
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </li>
        </ul>
    </div>
</div>
