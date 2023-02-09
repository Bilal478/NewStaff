<x-modals.small x-on:open-update-modal.window="open = true" x-on:close-update-modal.window="open = false">
    <form wire:submit.prevent="create">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            Update Admin
        </h5>
        <x-inputs.text wire:model="firstname" label="First Name" name="firstname" type="text" placeholder="First Name" required />
        <x-inputs.text wire:model="lastname" label="Last Name" name="lastname" type="text" placeholder="Last Name" required />
        <x-inputs.text wire:model="email" label="Email Address" name="email" type="email" placeholder="johndoe@example.com" required />
        

        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Update
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
