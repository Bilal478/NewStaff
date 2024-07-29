<div>
    <x-page.title svg="svgs.users">
        Manage Managers Emails
    </x-page.title>
     {{-- <div class="sm:flex items-center justify-between pb-8">
        <x-inputs.search wire:model.debounce.500ms="search" class="w-full sm:w-72" />

            <button wire:click="manageCustomEmails" type="button" class="w-full sm:w-auto mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
                Manage Custom Emails
            </button>
    </div> --}}
    <div class="w-full pb-4">
        <div class="uppercase text-xs text-gray-400 font-medium flex items-center">
            <div class="flex-1 px-3">
                Name
            </div>
            <div class="w-56 px-3">
                Email
            </div>
            <div class="w-44 px-3">
                Role
            </div>
            <div class="w-44 px-3">
                ON/OFF Emails
            </div>
        </div>
    </div>
    
@foreach ($managers as $manager)
    
<div class="w-full bg-white py-5 rounded-md border mb-3">
    <div class="flex items-center text-sm">
        <div class="flex-1 px-3 ">
            <div class="flex items-center">
				<div class="avatar">
					<x-user.avatar />
				</div>
				<div class="fullname">
					<span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">{{ $manager->firstname }} {{ $manager->lastname }}</span>
				</div>
			</div>
        </div>
        <div class="w-56 px-3 text-xs text-gray-500">
            {{ $manager->email }}
        </div>
        <div class="w-44 px-3 text-xs text-gray-500">

            {{ ucfirst($manager->role) }}
        </div>
        
        <div class="w-44 px-3 text-xs text-gray-500">
        <div class="flex items-center  w-full ">
        
        <label 
            for="time_{{$manager->id}}"
            class="flex items-center cursor-pointer"
        >
            <!-- toggle -->
            <div class="relative">
            <!-- input -->
            <input name="time_{{$manager->id}}" id="time_{{$manager->id}}" wire:click.prevent="editTimePermssion({{$manager->id}},{{$manager->email_status}})"  type="checkbox" 
            @if($manager->email_status == 1)
            checked
            @endif
            class="sr-only" />
            <!-- line -->
            <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
            <!-- dot -->
            <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
            </div>
            
        </label>
        
        </div>
        </div>
        
    </div>
</div>
@endforeach

 <div class="sm:flex items-center justify-between pb-6 pt-2">
    <div class="font-montserrat text-xl font-semibold text-gray-700 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        
        <span style="font-size: 18px;">Manage Managers Emails</span>
    </div>
    

            <button wire:click="$emit('addCustomEmails')" type="button" class="w-full sm:w-auto mt-4 sm:mt-0 h-8 px-2 text-sm flex items-center rounded-md bg-blue-600 text-white hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
                Add Custom Email
            </button>
    </div>
    <div class="w-full pb-4">
        <div class="uppercase text-xs text-gray-400 font-medium flex items-center">
            <div class="flex-1 px-3">
                Name
            </div>
            <div class="w-56 px-3">
                Email
            </div>
            <div class="w-44 px-3">
                ON/OFF Emails
            </div>
            <div class="w-44 px-3 pl-28">
                Action
            </div>
        </div>
    </div>
    
@foreach ($customUsers as $customUser)
    
<div class="w-full bg-white py-5 rounded-md border mb-3">
    <div class="flex items-center text-sm">
        <div class="flex-1 px-3 ">
            <div class="flex items-center">
				<div class="avatar">
					<x-user.avatar />
				</div>
				<div class="fullname">
					<span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">{{ $customUser->firstname }} {{ $customUser->lastname }}</span>
				</div>
			</div>
        </div>
        <div class="w-56 px-3 text-xs text-gray-500">
            {{ $customUser->email }}
        </div>
       
        
        <div class="w-44 px-3 text-xs text-gray-500">
        <div class="flex items-center  w-full ">
        
        <label 
            for="time_{{$customUser->id}}"
            class="flex items-center cursor-pointer"
        >
            <!-- toggle -->
            <div class="relative">
            <!-- input -->
            <input name="time_{{$customUser->id}}" id="time_{{$customUser->id}}" wire:click.prevent="editCustomEmailPermssion({{$customUser->id}},{{$customUser->email_status}})"  type="checkbox" 
            @if($customUser->email_status == 1)
            checked
            @endif
            class="sr-only" />
            <!-- line -->
            <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
            <!-- dot -->
            <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
            </div>
            
        </label>
        
        </div>
        </div>
        <div class="w-44 px-3 text-xs text-gray-500 pl-32">
            <x-dropdowns.context-menu class="-mr-2">
                <x-dropdowns.context-menu-item wire:click.stop="$emit('customUserEdit', {{$customUser->id}})" name="Edit" svg="svgs.edit"/>
                <x-dropdowns.context-menu-item wire:click.stop="$emit('customUserDelete', {{$customUser->id}})" name="Delete" svg="svgs.trash"/>
            </x-dropdowns.context-menu>
        </div>
    </div>
</div>
@endforeach
    @push('modals')
        @livewire('accounts.manage-emails.add-custom-email')
        @livewire('accounts.manage-emails.edit-custom-email')
    @endpush
</div>
<style>
    /* Toggle A */
input:checked ~ .dot {
  transform: translateX(100%);
  background-color: #0284c7;
}
@media (max-width: 625px) {
  .avatar{
	display:none;
  }
  .fullname{
	margin-left:-10px !important;
  }
}
</style>
