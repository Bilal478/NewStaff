<x-modals.small x-on:open-time-modal.window="open = true" x-on:close-time-modal.window="open = false">
    @if ($message != null)
    <div class="border border-t-0 border-red-400  rounded-b bg-red-100 px-4 py-3 text-red-700" style="color: red">
        <p>{{ $message }}</p>
    </div>
    @endif
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            {{$title}}
        </h5>
        <div class="pb-6">

            <label for="start_time" class="block text-sm text-gray-500 leading-5">
                Activity Name  </label>
            <div class="mt-1 rounded-md shadow-sm">
                <input id="name" type="text"  placeholder=" "
                    wire:model="name" required="required" readonly
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 ">
            </div>

        </div>
        <div wire:ignore class="pb-6">

            <label for="start_time" class="block text-sm text-gray-500 leading-5">
                Time  
			</label>
            <div class="mt-1 rounded-md shadow-sm">
                <input id="seconds" type="text" step='1' min="00:00:00" max="24:00:00" placeholder=" "
                    wire:model="seconds" required="required"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepicker">
            </div>

        </div>
        <input type="text" name="" id="">



        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Update
            </x-buttons.blue-inline>
        </div>




    </form>
</x-modals.small>

@push('scripts')
<script>
    $('.timepicker').timepicker({
            timeFormat: 'H:mm:ss',
            interval: 10,
            dynamic: true,
            dropdown: true,
            scrollbar: true,
            change: tmTotalHrsOnSite
        });

        function tmTotalHrsOnSite () {
            var x = $(".timepicker").val();
            let elementName = $(".timepicker").attr('id');
            var data = $(".timepicker").val();
            @this.set(elementName, data);

        }
</script>
@endpush
