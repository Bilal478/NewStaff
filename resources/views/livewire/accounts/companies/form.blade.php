<x-modals.small x-on:open-create-company-modal.window="open = true" x-on:close-create-company-modal.window="open = false">
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            {{ $isEditing ? 'Edit company' : 'New company' }}
        </h5>

        <x-inputs.text wire:model.lazy="company.title" label="Title" name="title" type="text" placeholder="Title" required/>

        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                {{ $isEditing ? 'Update company' : 'Create company' }}
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
