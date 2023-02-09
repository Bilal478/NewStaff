<x-modals.small x-on:open-create-modal.window="open = true" x-on:close-create-modal.window="open = false">
    <form wire:submit.prevent="create">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            Create Admin
        </h5>
        <x-inputs.text wire:model="firstname" label="First Name" name="firstname" type="text" placeholder="First Name" required />
        <x-inputs.text wire:model="lastname" label="Last Name" name="lastname" type="text" placeholder="Last Name" required />
        <x-inputs.text wire:model="email" label="Email Address" name="email" type="email" placeholder="johndoe@example.com" required />
        <x-inputs.text wire:model="password" label="Password" name="password" type="password" placeholder="Password" required />

        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Create
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
