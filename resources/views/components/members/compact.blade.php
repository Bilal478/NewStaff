<?php

use Illuminate\Support\Facades\DB;
if(isset($departmentId)){
    $q = ['user_id'=>$user->id,'department_id'=>$departmentId];
}else{
    $q = ['user_id'=>$user->id];
    $departmentId = 0;
}
$isAdminOfDepartment = DB::table('department_admin')
    ->where($q)
    ->first();  
?>
<div class="flex items-center justify-between {{ $attributes->get('class') }}">
    <div class="flex items-center">
        <x-user.avatar :large="true" />
         <div class="ml-3 truncate">
            <span class="block text-left font-montserrat text-sm font-semibold text-gray-700 truncate">{{ $user->full_name }}
                @if($isAdminOfDepartment)
                    <span><small>(Admin @if($isAdminOfDepartment)
                    <button type="button" class="text-red-400 hover:text-red-500" title="Remove Admin role">
                        <small  wire:click.prevent="removeAdminRoleOfDepartment({{$user->id}},{{$departmentId}})">Remove Role</small>
                    </button>
                    @endif)</small></span>
                @endif
            </span>
            <span class="block text-left text-xs text-gray-400 truncate">{{ $user->email }}</span>
        </div>
    </div>

    @role(['owner', 'manager'])
        <button {{ $attributes->whereStartsWith('wire:click') }} type="button" class="text-gray-400 hover:text-red-500" title="Remove from Department">
            <x-svgs.x-circle class="h-5 w-5" />
        </button>
    @endrole
</div>
