<div>
    <x-page.title svg="svgs.office-building">
        Create a new company
    </x-page.title>

    <div class="max-w-5xl">
        <div class="flex items-start flex-wrap -mx-4">
            <div class="w-full md:w-1/3">
                <div class="mx-4 mb-4 md:mb-0">
                    <h6 class="font-montserrat font-semibold text-sm text-gray-700 pb-3">Create a new Company</h6>
                    <p class="text-sm text-gray-500">Name and address information for the new company.</p>
                </div>
            </div>
            <div class="w-full md:w-2/3">
                <article class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4">
                    <form wire:submit.prevent="create">
                        <x-inputs.text wire:model.lazy="name" label="Company name" name="name" type="text" placeholder="Dickinson and Barton" required />

                        <x-inputs.text wire:model.lazy="address" label="Address" name="address" type="text" placeholder="Address Line 1" />
                        <x-inputs.text wire:model.lazy="city" label="City" name="city" type="text" placeholder="City" />
                        <x-inputs.text wire:model.lazy="state" label="State" name="state" type="text" placeholder="State" />
                        <x-inputs.text wire:model.lazy="zipcode" label="Zip code" name="zip" type="text" placeholder="60378-4224" />
                        <x-inputs.text wire:model.lazy="phone" label="Phone" name="phone" type="text" placeholder="(682) 233-8550" />

                        <div class="flex justify-end mt-2">
                            <x-buttons.blue-inline type="submit">
                                Create
                            </x-buttons.blue-inline>
                        </div>
                    </form>
                </article>
            </div>
        </div>
    </div>
</div>
