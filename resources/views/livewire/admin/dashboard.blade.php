<div>
    <div class="pb-12">
        <h1 class="font-montserrat text-xl font-semibold text-gray-700">
            Accounts
        </h1>
    </div>
    <div class="md:flex items-center justify-between pb-8">
        <div class="md:flex items-center md:space-x-4">
            <x-inputs.search wire:model.debounce.500ms="search" class="w-full md:w-72" placeholder=""  />
        </div>

      
    </div>
    <div class="w-full pb-4">
    <div class="uppercase text-xs text-gray-400 font-medium hidden md:flex items-center">
        <div class="w-44 px-3">
        Company Name
        </div>
        <div class=" w-44 px-3">
        Owner Name
        </div>
        <div class=" w-60 px-3">
        Owner Email
        </div>
        <div class="text-center w-44 px-3">
        Total Active Users
        </div>
        <div class="text-center w-32 px-3">
        Projects
        </div>
        <div class="text-center w-32 px-3">
        Tasks
        </div>
        <div class="text-center w-32 px-3">
        Activities
        </div>
        <div class="w-32 px-3">
        Storage
        </div>  
        <div class="w-32 px-3">
        Entry Date
        </div>  
        <div class="w-20 px-3">
            Actions
        </div>
    </div>

    <div class="text-center uppercase text-xs text-gray-400 font-medium px-3 block md:hidden">
        Tasks
    </div>
</div>
<div>
@foreach ($accounts as $index=>$account)

<div  class="w-full bg-white py-5 rounded-md border mb-3 cursor-pointer hover:shadow-md">
    <div class="hidden md:flex items-center text-sm">
        <div class="w-44 px-3 text-gray-700 flex font-montserrat font-semibold">
        
                    <x-svgs.office-building class="w-6 h-6 text-blue-600 mr-3" />
                    {{ $account->name }}
               
        </div>
        @foreach ($account->users as $user)
            @if($user->pivot->role == 'owner')
            <div class="w-44 px-3 text-gray-700 font-montserrat font-semibold">
                {{ $user->firstname }} {{ $user->firstname }} {{count($account->users)}}
            </div>
            <div class="break-words w-60 px-3 text-gray-700 font-montserrat font-semibold">
                {{ $user->email }}
            </div>
            @php break @endphp
            @elseif($user->pivot->role != 'owner')
            <div class="w-44 px-3 text-gray-700 font-montserrat font-semibold">
                
            </div>
            <div class=" w-60 px-3 text-gray-700 font-montserrat font-semibold">
                
            </div>
            @php break @endphp
            @endif
        @endforeach
        @if(count($account->users) == 0)
            <div class="w-44 px-3 text-gray-700 font-montserrat font-semibold">
                
            </div>
            <div class=" w-60 px-3 text-gray-700 font-montserrat font-semibold">
                
            </div>
            @endif
        <div class="text-right w-32 px-3 text-xs text-gray-500">
            {{ $account->users_count }}
        </div>
        <div class="text-right w-32 px-3 text-xs text-gray-500">
            {{ $account->projects_count }}
        </div>

        <div class="text-right w-32 px-3 text-xs text-gray-500">
            {{ $account->tasks_count }}
        </div>

        <div class="text-right w-32 px-3 text-xs text-gray-500">
            {{ $account->activities_count }}
        </div>
        <div class="text-right w-32 px-3 text-xs text-gray-500">
            {{ $accountStorage[$index] }}
        </div>

        <div class="text-right w-32 px-3 text-xs text-gray-500">
            {{ $account->created_at->format('Y-M-d')}}
            <br>
            {{ $account->created_at->format('g:i: a')}}
        </div>
       
        <div class="text-center w-32 px-3 flex justify-end">
        <x-dropdowns.context-menu>
            <x-dropdowns.context-menu-item wire:click.stop="$emit('accountEdit', {{$account->id}})"  name="Edit" svg="svgs.edit"/>
        </x-dropdowns.context-menu>
        </div>
    </div>

</div>
@endforeach
</div>
<div class="pt-5">
            {{ $accounts->links('vendor.pagination.default') }}
</div>
@push('modals')
        @livewire('admin.accounts.accounts-update')
@endpush
<style>
.flex-1{
	flex: 3 3 20% !important;
}
</style>
   
