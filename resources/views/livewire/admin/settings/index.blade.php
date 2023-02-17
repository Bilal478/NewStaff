<div>
    <x-page.title svg="svgs.settings">
        Settings
    </x-page.title>
    <div class="md:flex items-center justify-between pb-8">
        <div class="md:flex items-center md:space-x-4">
           
        </div>

        <button wire:click="$emit('addSetting')" type="button" class="w-full md:w-auto mt-4 md:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
            <x-svgs.plus class="w-5 h-5 mr-1" />
            New Setting
        </button>
    </div>
    <div class="max-w-5xl">
        <div class="flex items-start flex-wrap -mx-4">
            <div class="w-full md:w-1/3">
                <div class="mx-4 mb-4 md:mb-0">
                    <h6 class="font-montserrat font-semibold text-sm text-gray-700 pb-3">Settings Details</h6>
                </div>
            </div>
            <div class="w-full md:w-2/3">
                <article class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4">
                    <form wire:submit.prevent="save">
                        @foreach($settingsData as $index=>$setting)
                            <div wire:key="user-field-{{ $index }}">
                                <x-inputs.text   wire:model.lazy="settingsData.{{ $index }}.settings_value" label="{{$labels[$index]}}"  name="CurrentVersion" type="text" placeholder="Dickinson and Barton" />
                            </div>     
                        @endforeach
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
@push('modals')
        @livewire('admin.settings.settings-create')
    @endpush
