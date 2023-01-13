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

        <x-inputs.select wire:model.lazy="role" label="Role" name="role" type="text" required>
            <option value="owner">Owner</option>
            <option value="manager">Manager</option>
            <option value="member">Member</option>
        </x-inputs.select>

        <x-inputs.multiselect wire:model.lazy="team_id"   label="Team" name="team_id">
            @foreach (App\Models\Team::get() as $item)
                <option value="{{ $item->id }}">{{ $item->title }}</option>
            @endforeach
        </x-inputs.multiselect>

        {{-- <x-inputs.select wire:model.lazy="department_id"  label="Department" name="department">
            @foreach (App\Models\Department::get() as $item)
                <option value="{{ $item->id }}">{{ $item->title }}</option>
            @endforeach
        </x-inputs.select> --}}

        <div class="border-b mb-6">
            <x-inputs.multiselect wire:model="department_id" label="Department" name="department_id" required>
                <option value="">Select a project</option>
                @foreach (App\Models\Department::get() as $item)
                <option value="{{ $item->id }}">
                    {{ $item->title }}
                </option>
                @endforeach
            </x-inputs.multiselect>
        </div>




        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Update
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
