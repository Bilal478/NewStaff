<x-modals.small x-on:open-create-department-modal.window="open = true" x-on:close-create-department-modal.window="open = false">
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            {{ $isEditing ? 'Edit Department' : 'New Department' }}
        </h5>

        <x-inputs.text wire:model.lazy="department.title" label="Title" name="title" type="text" placeholder="Title" required/>

        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                {{ $isEditing ? 'Update Department' : 'Create Department' }}
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
