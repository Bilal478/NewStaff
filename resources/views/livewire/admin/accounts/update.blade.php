<x-modals.small x-on:open-update-modal.window="open = true" x-on:close-update-modal.window="open = false">
    <form wire:submit.prevent="update">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            Update Account
        </h5>
                <x-inputs.text wire:model.lazy="name" label="Company name" name="name" type="text" placeholder="Dickinson and Barton" required />
                <x-inputs.text wire:model.lazy="address" label="Address" name="address" type="text" placeholder="Address Line 1" />
                <x-inputs.text wire:model.lazy="city" label="City" name="city" type="text" placeholder="City" />
                <x-inputs.text wire:model.lazy="state" label="State" name="state" type="text" placeholder="State" />
                <x-inputs.text wire:model.lazy="zipcode" label="Zip code" name="zip" type="text" placeholder="60378-4224" />
                <x-inputs.text wire:model.lazy="phone" label="Phone" name="phone" type="text" placeholder="(682) 233-8550" />
        
        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Update
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
