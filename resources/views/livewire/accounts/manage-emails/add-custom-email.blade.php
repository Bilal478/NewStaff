<x-modals.small x-on:open-add-custom-email.window="open = true" x-on:close-add-custom-email.window="open = false">
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            Add Custom Email
        </h5>

        <x-inputs.text wire:model.lazy="firstname" label="Firstname" name="firstname" type="text" required />

        <x-inputs.text wire:model.lazy="lastname" label="Lastname" name="lastname" type="text" required />

        <x-inputs.text wire:model.lazy="email" label="Email Address" name="email" type="email" required />   
        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Add
            </x-buttons.blue-inline>
        </div>
    </form> 
</x-modals.small>

