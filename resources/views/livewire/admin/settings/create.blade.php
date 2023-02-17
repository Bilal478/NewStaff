<x-modals.small x-on:open-create-modal.window="open = true" x-on:close-create-modal.window="open = false">
    <form wire:submit.prevent="create">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            Create Setting
        </h5>
        <x-inputs.text wire:model="settingName" label="Setting Name" name="settingName" type="text" placeholder="Setting Name" required />
        <x-inputs.text wire:model="settingValue" label="Setting Value" name="settingValue" type="text" placeholder="Setting Value" required />
        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Create
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
