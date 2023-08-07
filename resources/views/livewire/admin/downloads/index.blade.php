<div>
<x-page.title svg="svgs.download">
    Desktop Applocation
</x-page.title>

<div class="max-w-5xl">
    <div class="flex items-start flex-wrap -mx-4">
        <div class="w-full md:w-1/3">
            <div class="mx-4 mb-4 md:mb-0">
                <h6 class="font-montserrat font-semibold text-sm text-gray-700 pb-3">Windows Setup</h6>
                <p class="text-sm text-gray-500">Wtite the new version of Windows Setup </p>
            </div>
        </div>

        <div class="w-full md:w-2/3">
            <article class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4">
                <form wire:submit.prevent="updateWindowSetup" >
                    <x-inputs.text wire:model.lazy="windowSetupName" label="File Name with version" name="windowSetupName" type="text" placeholder="windows_setup_1.0" required />
                    
                    <div class="flex justify-end mt-2">    
                        <x-buttons.blue-inline type="submit">
                            Update
                        </x-buttons.blue-inline>
                    </div>
                </form>
            </article>
        </div>
    </div>

    <div class="flex items-start flex-wrap -mx-4 pt-8 border-t">
        <div class="w-full md:w-1/3">
            <div class="mx-4 mb-4 md:mb-0">
                <h6 class="font-montserrat font-semibold text-sm text-gray-700 pb-3">MAC Setup</h6>
                <p class="text-sm text-gray-500">Write the new version of MAC Setup</p>
            </div>
        </div>

        <div class="w-full md:w-2/3">
            <article class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4">
                <form wire:submit.prevent="updateMacSetup" >
                <x-inputs.text wire:model.lazy="macSetupName" label="File Name with version" name="macSetupName" type="text" placeholder="mac_setup_1.0" required />

                    <div class="flex justify-end mt-2">
                        <x-buttons.blue-inline type="submit">
                            Update
                        </x-buttons.blue-inline>
                    </div>
                </form>
            </article>
        </div>
    </div>

    <div class="flex items-start flex-wrap -mx-4 pt-8 border-t">
        <div class="w-full md:w-1/3">
            <div class="mx-4 mb-4 md:mb-0">
                <h6 class="font-montserrat font-semibold text-sm text-gray-700 pb-3">Ubuntu Setup</h6>
                <p class="text-sm text-gray-500">Write the new version of Ubuntu Setup</p>
            </div>
        </div>

        <div class="w-full md:w-2/3">
            <article class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4">
                <form wire:submit.prevent="updateUbuntuSetup" >
                <x-inputs.text wire:model.lazy="ubuntuSetupName" label="File Name with version" name="ubuntuSetupName" type="text" placeholder="ubuntu_setup_1.0" required />

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

