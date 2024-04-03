<div>
    <x-page.title svg="svgs.user">
        Active Members
    </x-page.title>

    <div class="md:flex items-center justify-between pb-8">
        <div class="md:flex items-center md:space-x-4">
            <x-inputs.search wire:model.debounce.500ms="search" class="w-full md:w-72"/>
            <div class="ml-2">
                <x-inputs.select-without-label2 wire:model="selectedAccount"  name="selectedAccount">
                    <option value="">All Accounts</option>
                    @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach 
                </x-inputs.select-without-label>
            </div>
        </div>
    </div>
    <div class="w-full pb-4">
        <div class="uppercase text-xs text-gray-400 font-medium flex items-center">
            <div class="w-12 px-3">
        
            </div>
            <div class="w-60" style="padding-left: 27px">
                User Name
            </div>
            <div class="w-44 px-3">
                Companies
            </div>
            <div class="w-60 px-3">
                Email
            </div>
            <div class="w-44 px-3">
                Registration Date
            </div>
            <div class="w-44 px-3 text-center">
                Puchin Pin Code
            </div>
            <div class="w-44 px-3 text-center">
                Last Login At
            </div>
            <div class="w-20 px-3">
                Actions
            </div>
        </div>
    </div>
    @foreach ($users as $user)
    <div x-data="{ expanded: false }" class="w-full bg-white py-5 rounded-md border mb-3" style="cursor: pointer;">
        <div class="flex items-center text-sm">
            <div class="w-12 px-1 text-gray-700 flex font-montserrat font-semibold">
                <button  @click="expanded = !expanded">
                    <x-svgs.minus x-show="expanded" class="w-6 h-6 text-blue-600 mr-3" />
                    <x-svgs.plus x-show="!expanded" class="w-6 h-6 text-blue-600 mr-3" />
                </button>
            </div>
            <div wire:click.stop="$emit('memberShow','{{ $user->id }}')" class="w-60 px-3 truncate">
                <div class="flex items-center">
                    <x-user.avatar />
                    <span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">
                       {{ $user->firstname }} {{ $user->lastname }}
                    </span>
                </div>
            </div>
            <div wire:click.stop="$emit('memberShow','{{ $user->id }}')" class="w-44 px-3 text-xs text-gray-500">
                @if ($user->accounts->isNotEmpty())
                    @foreach($user->accounts as $index => $account) 
                        {{ $account->name }}
                        @if ($index < $user->accounts->count() - 1)
                            ,
                        @endif
                    @endforeach
                @else
                    No Company
                @endif
            </div>
            <div wire:click.stop="$emit('memberShow','{{ $user->id }}')" class="w-60 px-3 text-xs text-gray-500">
                {{ $user->email }}
            </div>
            <div wire:click.stop="$emit('memberShow','{{ $user->id }}')" class="w-44 px-3 text-xs text-gray-500">
                {{ $user->created_at }}
            </div>
            <div wire:click.stop="$emit('memberShow','{{ $user->id }}')" class="w-44 px-3 text-xs text-gray-500 text-center">
                {{ $user->punchin_pin_code }}
            </div> 
            <div wire:click.stop="$emit('memberShow','{{ $user->id }}')" class="w-44 px-3 text-xs text-gray-500 text-center">
                {{ $user->last_login_at }}
            </div>
            <div class="text-center w-20 px-3 flex justify-end">
                <x-dropdowns.context-menu>
                    <x-dropdowns.context-menu-item wire:click.stop="$emit('resetPassword', {{$user->id}})"  name="Reset Password" svg="svgs.edit"/>
                </x-dropdowns.context-menu>
            </div>
        </div>
        <div class="" x-show="expanded">
            <hr>
            <div class="text-xs mt-5 text-gray-400 hidden font-medium  md:flex items-center">
                <div class="w-12 px-3">
                
                </div>
                <div class="w-44 px-3" style="font-size: 13px;">
                    PERMISSIONS
                </div>
                <div class="w-44 text-gray-700" style="font-size: 13px;">
                    {{ $user->permissions ?? "" }}
                </div>
            </div>
            <div class="text-xs mt-5 text-gray-700 font-medium md:flex items-center">
                <div class="w-12 px-3">
                </div>
                <div class="w-44 px-3 text-gray-400" style="font-size: 13px;">
                    IP ADDRESS
                </div>
                <div class="w-44" style="font-size: 13px;">
                    {{ $user->ipaddress ?? "" }}
                </div>
            </div>
        </div>   
    </div> 
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
        {{ $users->links() }}
    </div> 
</div>
@push('modals')
    @livewire('admin.active-members.members-show')
    @livewire('admin.active-members.reset-password')
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