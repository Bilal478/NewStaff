<div>
    <x-page.title svg="svgs.user">
        Active Members
    </x-page.title>

    <div class="md:flex items-center justify-between pb-8">
        <div class="md:flex items-center md:space-x-4">
            <x-inputs.search wire:model.debounce.500ms="search" class="w-full md:w-72"/>
        </div>
    </div>
    <div class="w-full pb-4">
        <div class="uppercase text-xs text-gray-400 font-medium flex items-center">
            <div class="flex-1 px-3">
                User Name
            </div>
            <div class="w-56 px-3">
                Companies
            </div>
            <div class="w-56 px-3">
                Email
            </div>
            <div class="w-56 px-3">
                Registration Date
            </div>
        </div>
    </div>
    @foreach ($userData as $data)
    @if($data['user_data'])
    <div wire:click.stop="$emit('memberShow','{{ $data['user_data']->id }}')" class="w-full bg-white py-5 rounded-md border mb-3" style="cursor: pointer;">
        <div class="flex items-center text-sm">
            <div class="flex-1 px-3 truncate">
                <div class="flex items-center">
                    <x-user.avatar />
                    <span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">
                       {{ $data['user_data']->firstname }} {{ $data['user_data']->lastname }}
                    </span>
                </div>
            </div>
            <div class="w-56 px-3 text-xs text-gray-500">
                @if ($data['accounts_data']->isNotEmpty())
                @foreach($data['accounts_data'] as $index => $account) 
                    {{ $account->name }}
                    @if ($index < $data['accounts_data']->count() - 1)
                        ,
                    @endif
                @endforeach
                @else
                No Company
                @endif
            </div>
            <div class="w-56 px-3 text-xs text-gray-500">
                {{ $data['user_data']->email }}
            </div>
            <div class="w-56 px-3 text-xs text-gray-500">
                {{ $data['user_data']->created_at }}
            </div>
        </div>  
    </div>
    @endif   
    @endforeach
        <div wire:loading>
            <!-- Show the loading animation -->
            <div class="loading-overlay">
            <div  class="loading-animation">
                <!-- Add your loading animation here -->  
            </div>
            </div>
        </div>
    <div class="pt-5">
        {{ $activeMembers->links() }}
    </div> 
</div>
@push('modals')
    @livewire('admin.active-members.members-show')
@endpush
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

