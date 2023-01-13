<div x-data="{ open: false }" class="relative border-t">
    <button x-on:click="open = true" type="button" class="w-full px-6 py-3 flex items-center hover:bg-gray-100" :class="{ 'bg-gray-100': open === true }">
        <x-user.avatar />
        <span class="flex-1 text-left inline-block text-sm text-gray-700 pl-2 truncate">
            {{ $user->full_name }}
        </span>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-on:click.away="open = false"
        x-cloak
        class="absolute bg-white inset-x-0 mx-2 mb-1 border shadow-md rounded-md"
        style="bottom: 100%;"
    >
        <ul class="text-gray-700 text-xs">
            <li class="p-1">
                <a href="{{ route('admin.profile.edit') }}" class="px-3 py-1 rounded-md leading-6 flex items-center hover:bg-gray-100 hover:text-blue-600">
                    <x-svgs.user class="w-4 h-4 mr-2" />
                    <span class="text-gray-700">My Profile</span>
                </a>
            </li>
            <li class="p-1">
                <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-3 py-1 rounded-md leading-6 flex items-center hover:bg-gray-100 hover:text-blue-600">
                    <x-svgs.logout class="w-4 h-4 mr-2" />
                    <span class="text-gray-700">Logout</span>
                </a>

                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
