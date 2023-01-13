<div>
    <x-page.title svg="svgs.user">
        My Profile
    </x-page.title>

    <div class="max-w-5xl">
        <div class="flex items-start flex-wrap -mx-4">
            <div class="w-full md:w-1/3">
                <div class="mx-4 mb-4 md:mb-0">
                    <h6 class="font-montserrat font-semibold text-sm text-gray-700 pb-3">Personal Information</h6>
                    <p class="text-sm text-gray-500">Change your display name and email address.</p>
                </div>
            </div>

            <div class="w-full md:w-2/3">
                <article class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4">
                    <form wire:submit.prevent="save">
                        <x-inputs.text wire:model.lazy="firstname" label="First Name" name="firstname" type="text" placeholder="John" required />
                        <x-inputs.text wire:model.lazy="lastname" label="Last Name" name="lastname" type="text" placeholder="Doe" required />
                        <x-inputs.text wire:model.lazy="email" label="Email Address" name="email" type="email" placeholder="johndoe@example.com" required />
                        <x-inputs.text wire:model.lazy="punchin_pin_code"  label="Punch In Pin Code" name="punchin_pin_code" type="text"/>
                        <div class="flex justify-end mt-2">
                            <x-buttons.blue-inline type="submit">
                                Save
                            </x-buttons.blue-inline>
                        </div>
                    </form>
                </article>
            </div>
        </div>

        <div class="flex items-start flex-wrap -mx-4 pt-8 border-t">
            <div class="w-full md:w-1/3">
                <div class="mx-4 mb-4 md:mb-0">
                    <h6 class="font-montserrat font-semibold text-sm text-gray-700 pb-3">Password</h6>
                    <p class="text-sm text-gray-500">Change your current password.</p>
                </div>
            </div>

            <div class="w-full md:w-2/3">
                <article class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4">
                    <form wire:submit.prevent="updatePassword">
                        <x-inputs.text wire:model.lazy="password" label="Password" name="password" type="password" required />
                        <x-inputs.text wire:model.lazy="passwordConfirmation" label="Password Confirmation" name="passwordConfirmation" type="password" required />

                        <div class="flex justify-end mt-2">
                            <x-buttons.blue-inline type="submit">
                                Update
                            </x-buttons.blue-inline>
                        </div>
                    </form>
                </article>
            </div>
        </div>
    </div>
</div>
