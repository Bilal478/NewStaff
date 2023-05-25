<x-modals.small x-on:open-create-admin-modal.window="open = true" x-on:close-create-admin-modal.window="open = false">
    <div role="alert">
        <h4>Assign Admin Role to Department</h4>
        <x-inputs.select-without-label wire:model.lazy="userId" name="userId" class="flex-1">
                    <option value="">Select member</option>
                    @foreach ($usersIn as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->full_name }}
                        </option>
                    @endforeach
                </x-inputs.select-without-label>
                <x-buttons.blue-inline wire:click="$emit('createAdmin')" class="h-10" id="btn-save">
                    Save
                </x-buttons.blue-inline>
    </div>
  
</x-modals.small>
<style>
    h4{
        margin-bottom: 10px;
    }
#btn-save{
    margin-left: 435px;
    margin-top: 10px;
}
</style>

