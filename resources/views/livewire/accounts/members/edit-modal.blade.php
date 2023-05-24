<x-modals.small x-on:open-member-edit-modal.window="open = true" x-on:close-member-edit-modal.window="open = false">
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            Edit Member
        </h5>

        <x-inputs.text wire:model.lazy="firstname" label="Firstname" name="firstname" type="text" disabled />

        <x-inputs.text wire:model.lazy="lastname" label="Lastname" name="lastname" type="text" disabled />

        <x-inputs.text wire:model.lazy="email" label="Email Address" name="email" type="text" disabled />

        @if(Auth::user()->isOwnerOrManager())
        <x-inputs.text wire:model.lazy="punchin_pin_code"  label="Punch In Pin Code" name="punchin_pin_code" type="text"/>
        @endif
       
        <label for="role">Role <span>*</span></label><br>
        <select  wire:model.lazy="role" label="Role" name="role" wire:change=handlePermissions() type="text" required>
            @if($is_owner)
             <option  value="owner">Owner</option>
            @endif
            <option  value="manager">Manager</option>
            <option value="member">Member</option>
        </select>
        {{-- <x-inputs.multiselect wire:model.lazy="team_id"   label="Team" name="team_id">
            @foreach (App\Models\Team::get() as $item)
                <option value="{{ $item->id }}">{{ $item->title }}</option>
            @endforeach
        </x-inputs.multiselect> --}}

        {{-- <x-inputs.select wire:model.lazy="department_id"  label="Department" name="department">
            @foreach (App\Models\Department::get() as $item)
                <option value="{{ $item->id }}">{{ $item->title }}</option>
            @endforeach
        </x-inputs.select> --}}

        <div class="mb-6">
            <x-inputs.multiselect wire:model="department_id" label="Department" name="department_id" required>
                <option value="">Select a project</option>
                @foreach (App\Models\Department::get() as $item)
                <option value="{{ $item->id }}">
                    {{ $item->title }}
                </option>
                @endforeach
            </x-inputs.multiselect>
            @if ($show_permission)
            <input wire:model="permissions" type="checkbox" name="permissions[]" value="departments" {{ in_array('departments', $permissions) ? 'checked' : '' }}> Departments
            {{-- <input wire:model="permissions" type="checkbox" name="permissions[]" value="teams" {{ in_array('teams', $permissions) ? 'checked' : '' }}> Teams --}}
            <input wire:model="permissions" type="checkbox" name="permissions[]" value="members" {{ in_array('members', $permissions) ? 'checked' : '' }}> Members
            <input wire:model="permissions" type="checkbox" name="permissions[]" value="settings" {{ in_array('settings', $permissions) ? 'checked' : '' }}> Settings 
            <input wire:model="permissions" type="checkbox" name="permissions[]" value="memberships" {{ in_array('memberships', $permissions) ? 'checked' : '' }}>  Memberships 
            
            @endif
            
        </div>
       



        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Update
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
<style>
#hidden_div {
    display: none;
}
select {
    background-color: white;
    border: 1px solid #aaa;
    border-radius: 5px;
    cursor: text;
    /* padding-bottom: 5px; */
    /* padding-right: 5px; */
    margin-bottom: 20px;
    position: relative;
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    min-height: 34px;
    width: 512px;
    user-select: none;
    -webkit-user-select: none;

} 
select:focus{
    outline: none !important;
    border: 1px solid #aaa;
}
select option{
    --tw-text-opacity: 1;
    color: rgba(82, 82, 82, var(--tw-text-opacity));
}
label{
    --tw-text-opacity: 1;
    color: rgba(115, 115, 115, var(--tw-text-opacity));
    font-size: 0.875rem;
}
span{
    font-size: 11px;
}
</style>
<script>
 
</script>
