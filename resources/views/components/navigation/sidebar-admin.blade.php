<aside class="fixed bg-white border-r inset-y-0 left-0 w-60 hidden lg:block">
    <nav class="h-full pt-8 flex flex-col justify-between" style="overflow: scroll !important;">
        <div>
            <div class="flex items-center justify-center pb-8">
                <a href="{{ route('home') }}" class="inline-block">
                    <img class="w-28 h-auto" alt="Neostaff" src="{{ url(asset('images/logo/neostaff-logo.png')) }}">
                </a>
            </div>

            <div class="pt-6">
                <ul>
                    <x-navigation.sidebar-item route="admin.dashboard" img="svgs.office-building">
                        Accounts
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="admin.members" img="svgs.users">
                        Members
                    </x-navigation.sidebar-item>
                </ul>
            </div>
        </div>

        @livewire('admin.user.user-info')
    </nav>
</aside>
