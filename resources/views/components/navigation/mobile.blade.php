<nav x-data="{open: false}" class="bg-white shadow-sm lg:hidden">
    <div class="flex items-center justify-center py-4 border-b relative">
        <a href="{{ route('home') }}" class="inline-block">
            <img class="w-24 h-auto" alt="Neostaff" src="{{ url(asset('images/logo/neostaff-logo.png')) }}">
        </a>
        <button x-show="!open" x-on:click="open = true" type="button" class="absolute left-0 top-0 bottom-0 flex items-center justify-center px-4 text-gray-700">
            <x-svgs.menu class="h-5 w-5" />
        </button>
        <button x-show="open" x-on:click="open = false" x-cloak type="button" class="absolute left-0 top-0 bottom-0 flex items-center justify-center px-4 text-gray-700">
            <x-svgs.x class="h-5 w-5" />
        </button>
    </div>

    <div x-show="open" class="border-b py-3" x-cloak>
        <div>
            @livewire('accounts.account.account-info')
        </div>
        <div>
            <ul>
		<x-navigation.sidebar-item route="accounts.teams" img="svgs.team">
                    Teams
                </x-navigation.sidebar-item>
                <x-navigation.sidebar-item route="accounts.departments" img="svgs.departments">
                    Departments
                </x-navigation.sidebar-item>
                <x-navigation.sidebar-item route="accounts.dashboard" img="svgs.chart">
                    Dashboard
                </x-navigation.sidebar-item>
                <x-navigation.sidebar-item route="accounts.activities" img="svgs.computer">
                    Activities
                </x-navigation.sidebar-item>
                <x-navigation.sidebar-item route="accounts.tasks" img="svgs.task">
                    Tasks
                </x-navigation.sidebar-item>
                <x-navigation.sidebar-item route="accounts.projects" img="svgs.folder">
                    Projects
                </x-navigation.sidebar-item>
		<x-navigation.sidebar-item route="accounts.reports" img="svgs.report">
                    Weekly Report
                </x-navigation.sidebar-item>
            </ul>
        </div>

        @role('owner')
            <div class="pt-6">
                <h4 class="px-4 uppercase text-xs text-gray-400 font-semibold tracking-wider pb-2">
                    Admin
                </h4>
                <ul>
                    <x-navigation.sidebar-item route="accounts.members" img="svgs.users">
                        Members
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="accounts.settings" img="svgs.settings">
                        Settings
                    </x-navigation.sidebar-item>
					
					<x-navigation.sidebar-item route="accounts.billing" img="svgs.folder">
                        Membership
                    </x-navigation.sidebar-item>
                </ul>
            </div>
        @endrole

        <div class="pt-6">
            <h4 class="px-4 uppercase text-xs text-gray-400 font-semibold tracking-wider pb-2">
                Profile
            </h4>
            <ul>
                <x-navigation.sidebar-item route="profile.edit" img="svgs.user">
                    My Profile
                </x-navigation.sidebar-item>
                <li class="px-2 py-1">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="px-3 py-2 text-sm rounded-md flex items-center text-gray-500 hover:bg-gray-100 hover:text-blue-600">
                        <x-svgs.logout class="w-5 h-5 mr-2" />
                        Logout
                    </a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
