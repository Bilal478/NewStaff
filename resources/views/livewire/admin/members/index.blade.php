<div>
    <x-page.title svg="svgs.users">
        Super Admin Users
    </x-page.title>

    <div class="md:flex items-center justify-between pb-8">
        <div class="md:flex items-center md:space-x-4">
            <x-inputs.search wire:model.debounce.500ms="search" class="w-full md:w-72" placeholder="Search by email"  />

            <!-- <x-inputs.select-without-label wire:model="filter" name="filter" class="w-full md:w-40 mt-4 md:mt-0">
                <option value="members">Members</option>
                <option value="invites">Invites</option>
            </x-inputs.select-without-label> -->
        </div>

        <button wire:click="$emit('addAdmin')" type="button" class="w-full md:w-auto mt-4 md:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
            <x-svgs.plus class="w-5 h-5 mr-1" />
            Create Admin
        </button>
    </div>

    @if ($users->count())
        <div>
            @if ($filter == 'members')
                <x-admins.headings />
                @foreach ($users as $user)
                    <x-admins.row :user="$user" :key="$user->id" />
                @endforeach
            @else
                <x-admins.invites.headings />
                @foreach ($users as $user)
                    <x-admins.invites.row :user="$user" :key="$user->id" />
                @endforeach
            @endif
        </div>

        <div class="pt-5">
            {{ $users->links('vendor.pagination.default') }}
        </div>
        <div wire:loading>
            <!-- Show the loading animation -->
            <div class="loading-overlay">
            <div  class="loading-animation">
                <!-- Add your loading animation here -->
                
            </div>
            </div>
    
        </div>
    @else
        <x-states.empty-data message="There are no pending invites." />
    @endif

    @push('modals')
        @livewire('admin.members.members-invite')
    @endpush
    @push('modals')
        @livewire('admin.members.members-update')
    @endpush
</div>
<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.7);
        z-index: 999;
    }

    

    .loading-animation {
    /* Add your styles for the loading animation */
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>
