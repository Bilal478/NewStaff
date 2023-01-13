<x-modals.small x-on:open-invite-modal.window="open = true" x-on:close-invite-modal.window="open = false">
    <form wire:submit.prevent="create">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            Invite Admin
        </h5>

        <x-inputs.text wire:model.lazy="email" label="Email Address" name="email" type="email" placeholder="johndoe@example.com" required />

        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Invite Member
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
