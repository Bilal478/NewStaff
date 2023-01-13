<x-modals.small x-on:open-track-modal.window="open = true" x-on:close-track-modal.window="open = false">
    @if ($message != null)
    <div role="alert">

        <div class="border border-t-0 border-red-400  rounded-b bg-red-100 px-4 py-3 text-red-700" style="color: red">
            <p>{{ $message }}</p>
        </div>
    </div>
    @endif
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            Edit
        </h5>

        {{-- <x-inputs.text wire:model.lazy="start_time" label="Start time" name="start_time" type="text" placeholder=" " required />
        <x-inputs.text wire:model.lazy="end_time" label="End time" name="end_time" type="text" placeholder=" " required /> --}}

        <div wire:ignore class="pb-6">
            <label for="start_time" class="block text-sm text-gray-500 leading-5">
                Start time <span class="text-red-500 text-xs">*</span> </label>

            <div class="mt-1 rounded-md shadow-sm">
                <input id="start_time" type="text" placeholder=" " wire:model="start_time" required="required"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepicker">
            </div>

        </div>
        <div wire:ignore class="pb-6">
            <label for="end_time" class="block text-sm text-gray-500 leading-5">
                End time <span class="text-red-500 text-xs">*</span> </label>

            <div class="mt-1 rounded-md shadow-sm">
                <input id="end_time" type="text" placeholder=" " wire:model="end_time" required="required"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepicker">
            </div>

        </div>


        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
               Update
            </x-buttons.blue-inline>
        </div>




    </form>
</x-modals.small>

@push('scripts')
    <script>
        $('#start_time').timepicker({
            timeFormat: 'H:mm:ss',
            interval: 10,
            dynamic: true,
            dropdown: true,
            scrollbar: true,
            change: tmTotalHrsOnSitestart_time
        });

        function tmTotalHrsOnSitestart_time () {
            var x = $("#start_time").val();
            let start_time = $("#start_time").attr('id');
            var data_start_time = $("#start_time").val();
            @this.set(start_time, data_start_time);

        }
        $('#end_time').timepicker({
            timeFormat: 'H:mm:ss',
            interval: 10,
            dynamic: true,
            dropdown: true,
            scrollbar: true,
            change: tmTotalHrsOnSite
        });

        function tmTotalHrsOnSite () {
            var x = $("#end_time").val();
            let end_time = $("#end_time").attr('id');
            var data_end_time = $("#end_time").val();
            @this.set(end_time, data_end_time);

        }


    </script>
@endpush
