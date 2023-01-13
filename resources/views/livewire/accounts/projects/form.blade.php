<x-modals.small x-on:open-create-modal.window="open = true" x-on:close-create-modal.window="open = false">
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            {{ $isEditing ? 'Edit Project' : 'New Projects' }}
        </h5>

        <x-inputs.text wire:model.lazy="project.title" label="Title" name="title" type="text" placeholder="Title" required/>

        <x-inputs.textarea wire:model.lazy="project.description" label="Description" name="description" type="text" placeholder="Description" required />

        <x-inputs.text wire:model.lazy="project.category" label="Category" name="category" type="text" placeholder="Category" required />

        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                {{ $isEditing ? 'Update Project' : 'Create Project' }}
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
