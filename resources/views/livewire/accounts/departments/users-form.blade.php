@php
    $user_role=[];
    $user_ids=[];
    $account_users=[];
    $user_login = auth()->id();
    $role=DB::select('SELECT * FROM department_admin where user_id='.$user_login );
    foreach($role as $val){
                $user_role[]=$val->department_id;
            } 
 
    $account_users[]=DB::table('account_user')->whereNotNull('deleted_at')->get();
    foreach ($account_users as $users) {
        foreach ($users as $user){
        $user_ids[]=$user->user_id;
    }
    }  
@endphp
<div class="w-full">
    @role(['owner', 'manager'])
        <div class="pb-6 mb-2 border-b">
            <form wire:submit.prevent="add" class="flex items-center space-x-2">
                <x-inputs.select-without-label wire:model.lazy="userId" name="userId" class="flex-1">
                    <option value="">Select member</option>
                    @foreach ($usersOut as $user)
                    @if (!in_array($user->id, $user_ids))
                        <option value="{{ $user->id }}">
                            {{ $user->full_name }}
                        </option>
                        @endif
                    @endforeach
                </x-inputs.select-without-label>
                @if ($userId)
                <x-buttons.blue-inline type="submit" class="h-10" >
                    Add
                </x-buttons.blue-inline>
                @else
                <button class="button text-sm flex items-center rounded-md   text-white px-6 py-2   focus:outline-none   transition duration-150 ease-in-out h-10" disabled>Add</button>

                @endif
            </form>
        </div>
    @endrole
    @role('member')
    @if (in_array($departmentId,$user_role))
    <div class="pb-6 mb-2 border-b">
        <form wire:submit.prevent="add" class="flex items-center space-x-2">
            <x-inputs.select-without-label wire:model.lazy="userId" name="userId" class="flex-1">
                <option value="">Select member</option>
                @foreach ($usersOut as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->full_name }}
                    </option>
                @endforeach
            </x-inputs.select-without-label>
            @if ($userId)
            <x-buttons.blue-inline type="submit" class="h-10" >
                Add
            </x-buttons.blue-inline>
            @else
            <button class="button text-sm flex items-center rounded-md   text-white px-6 py-2   focus:outline-none   transition duration-150 ease-in-out h-10" disabled>Add</button>

            @endif
        </form>
    </div>
    @endif
@endrole

    <div class="h-96 overflow-y-scroll">
        @foreach ($usersIn as $user)
            <x-members.compact
                :departmentId="$departmentId"
                :user="$user"
                :key="$user->id"
                wire:click="$emit('removeDepartmentMember', {{$user->id}},{{$usersIn}},{{$departmentId}})"
                class="p-2 my-2 rounded-md cursor-default hover:bg-gray-100"
            />
        @endforeach
    </div>
</div>

@push('style')
    <style>
  
.button{
    background: gray
}
    </style>
@endpush
