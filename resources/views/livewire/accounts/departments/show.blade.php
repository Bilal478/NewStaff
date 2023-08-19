@php
    $user_role=[];
    $user_login = auth()->id();
    $role=DB::select('SELECT * FROM department_admin where user_id='.$user_login );
    foreach($role as $val){
                $user_role[]=$val->department_id;
            }    
@endphp
<div>
    <x-page.title-breadcrum svg="svgs.departments" route="{{ route('accounts.departments', ['account' => request()->account]) }}">
        Departments
        <x-slot name="breadcrum">
            {{ $department->title }}
        </x-slot>
    </x-page.title-breadcrum>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full xl:w-2/3">
            <div class="bg-white mx-4 mb-8 rounded-md border shadow-sm p-8">
                <div class="pb-8 flex items-start justify-between">
                    <div>
                        <h4 class="font-montserrat font-semibold text-xl text-gray-700 pb-2">
                            {{ $department->title }}
                        </h4>
                    </div>
                </div>
            </div>

            @livewire('accounts.departments.department-tasks', ['department' => $department])
        </div>
        <div class="w-full xl:w-1/3">
            <div class="bg-white mx-4 mb-8 rounded-md border shadow-sm p-6 sm:p-8">
                <h4 class="font-montserrat text-sm font-semibold text-blue-600 pb-4 flex items-center float-left">
                    Members
                </h4>
                <br>
               
                @if($managerExists > 0)
                    @role(['owner', 'manager'])
                    <x-buttons.blue-inline wire:click="$emit('showCreateAdmin','{{ $department->id }}')" class="h-10" id="btn-admin">
                        Assign Admin
                    </x-buttons.blue-inline>
                    @endrole
                    @role('member')
                    @if (in_array($department->id,$user_role))
                    <x-buttons.blue-inline wire:click="$emit('showCreateAdmin','{{ $department->id }}')" class="h-10" id="btn-admin">
                        Assign Admin
                    </x-buttons.blue-inline>
                    @endif
                    @endrole
                @endif
                @livewire('accounts.departments.department-users-form', ['departmentId' => $department->id])
            </div>
        </div>
    </div>
</div>
@push('modals')
@livewire('create-admin-modal')    
@endpush
<style>
    #btn-admin{
        margin-left: 170px;
        margin-bottom: 10px;
    }
</style>