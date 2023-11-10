<div>
    <div>
        <x-page.title svg="svgs.trash">
            Deleted Accounts
        </x-page.title>
    </div>
    <div class="md:flex items-center justify-between pb-8">
        <div class="md:flex items-center md:space-x-4">
            <x-inputs.search wire:model.debounce.500ms="search" class="w-full md:w-72" placeholder=""  />
        </div>

      
    </div>
    <div class="w-full pb-4">
    <div class="uppercase text-xs text-gray-400 font-medium hidden md:flex items-center">
        <div class="w-12 px-3">
        
        </div>
        <div class="w-44 px-3">
        Company Name
        </div>
        <div class=" w-44 px-3">
        Owner Name
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
        <div class="w-32">
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

<div x-data="{ expanded: false }" class="w-full bg-white py-5 rounded-md border mb-3 cursor-pointer hover:shadow-md">
    <div class="hidden md:flex items-center text-sm">
        <div class="w-12 px-1 text-gray-700 flex font-montserrat font-semibold">
            <button  @click="expanded = !expanded">
               
                <x-svgs.minus x-show="expanded" class="w-6 h-6 text-blue-600 mr-3" />
                
                <x-svgs.plus x-show="!expanded" class="w-6 h-6 text-blue-600 mr-3" />
                
            </button>
        </div>
        <div class="w-44 px-3 text-gray-700 flex font-montserrat font-semibold">
        
                    <x-svgs.office-building class="w-6 h-6 text-blue-600 mr-3" />
                    {{ $account->name }}
               
        </div>
        @foreach ($account->users as $user)
            @if($user->pivot->role == 'owner')
            <div class="w-44 px-3 text-gray-700 font-montserrat font-semibold">
                {{-- {{ $user->firstname }} {{ $user->firstname }} {{count($account->users)}} --}}
                {{ $user->firstname }} {{ $user->lastname }} 
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
        <div class="text-center w-32 flex justify-end">
        <x-dropdowns.context-menu>
            <a href="#miModal"><x-dropdowns.context-menu-item wire:click="confirmDelete({{ $account->id }})" name="Delete" svg="svgs.x-circle"/></a>
        </x-dropdowns.context-menu>
        </div>
    </div>
    <div class="" x-show="expanded">
        <hr>
        <div class="text-xs mt-5 text-gray-400 hidden font-medium  md:flex items-center extra_record_{{$index}}">
            <div class="w-12 px-3">
            
            </div>
            <div class="w-44 px-3" style="font-size: 13px;">
            EMAIL
            </div>
            <div class="w-44 text-gray-700" style="font-size: 13px;">
            {{isset($user) ? $user->email :""}}
            </div>
        </div>
        <div class="text-xs mt-5 text-gray-700 font-medium md:flex items-center">
            <div class="w-12 px-3">
            </div>
            <div class="w-44 px-3 text-gray-400" style="font-size: 13px;">
            IP ADDRESS
            </div>
            <div class="w-44" style="font-size: 13px;">
            {{isset($user) ? $user->ipaddress:""}}
            </div>
        </div>
        </div>

</div>
@endforeach
</div>
<div class="pt-5">
            {{ $accounts->links('vendor.pagination.default') }}
</div>
<div wire:loading>
    <!-- Show the loading animation -->
    <div class="loading-overlay">
    <div  class="loading-animation">
        <!-- Add your loading animation here -->

    </div>
    </div>

</div>
<div id="miModal" class="modal pt-7">
    <div class="modal-contenido">
        <p class="mb-5 mt-5 ml-5">Are you sure to delete the account permanently?</p>
        <a href="#"><button type="button"  class="sm_button float-left ml-96 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-3 pr-3 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">No
         </button></a>
         <a href="#"><button type="button" id="cancel_button" wire:click.stop="permanent_delete_company()" class="sm_button float-left ml-5 h-10 text-sm flex items-center rounded-md bg-red-600 text-white pl-3 pr-3 hover:bg-red-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">Yes, Delete
        </button></a>
    </div>
</div>
@push('modals')
        @livewire('admin.accounts.accounts-update')
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
.flex-1{
	flex: 3 3 20% !important;
}
.text-center {
    text-align: center;
}
.text-right {
    text-align: right;
}

    .pl-3 {
    padding-left: 0.75rem;
}

.pr-3 {
    padding-right: 0.75rem;
}
.ml-96 {
    margin-left: 24rem;
}
.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}
.h-10 {
    height: 2.5rem;
}

.float-left {
    float: left;
}
.items-center {
    align-items: center;
}
/* .flex {
    display: flex;
} */
    .pt-7 {
    padding-top: 1.75rem;
}
.ml-5 {
    margin-left: 1.25rem;
}
.mb-5 {
    margin-bottom: 1.25rem;
}
.mt-5 {
    margin-top: 1.25rem;
}
.flex-1{
	flex: 3 3 20% !important;
}
.modal-contenido {
            background-color: white;
            border-radius: 8px;
            width: 570px;
            height: 140px;
            padding: 5px 10px;
            margin: 0 auto;
            position: relative;
        }

        .modal {
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            opacity: 0;
            pointer-events: none;
            transition: all 1s;
        }

        #miModal:target {
            opacity: 1;
            pointer-events: auto;
        }
        @media screen and (max-width: 768px) {
            .modal-contenido {
            padding: 1px 2px;
            width: 300px;
            height: 100px;
            
        }
        p{
            font-size: 12px;
            margin-top: 0px;
            margin-bottom: 0px;
            
        }
        .sm_button{
            padding-left: 5px;
            padding-right: 5px;
            height: 30px;
            margin-left: 165px;
        }
        #cancel_button{
            margin-left: 5px;
        }
    }
</style>