<x-modals.small x-on:open-create-team-modal.window="open = true" x-on:close-create-team-modal.window="open = false">
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            {{ $isEditing ? 'Edit Team' : 'New Team' }}
        </h5>

        <x-inputs.text wire:model.lazy="team.title" label="Title" name="title" type="text" placeholder="Title" required/>

        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                {{ $isEditing ? 'Update Team' : 'Create Team' }}
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
