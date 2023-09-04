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
                        Super Admin Users
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="admin.settings" img="svgs.settings">
                        Settings
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="admin.getsetupversion" img="svgs.download">
                        Desktop Application
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="admin.deletedcompanies" img="svgs.x-circle">
                        Deleted Accounts
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="admin.logerrors" img="svgs.logs-error">
                        Log Errors
                    </x-navigation.sidebar-item>
                    <x-navigation.sidebar-item route="admin.summary_logs" img="svgs.logs-error">
                        Summaery Logs
                    </x-navigation.sidebar-item>
                </ul>
            </div>
        </div>

        @livewire('admin.user.user-info')
    </nav>
</aside>
