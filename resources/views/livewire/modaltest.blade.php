<div class="sm:flex new-activity-button pb-0">
			<button wire:click="$emit('showFormModal')" type="button" class="w-full sm:w-auto mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
				<x-svgs.plus class="w-5 h-5 mr-1" />
					New Activity
            </button>
		</div>

<x-modals.small x-on:open-time-modal.window="open = true" x-on:close-time-modal.window="open = false">

    <form wire:submit.prevent="datetimerange">

        <div wire:ignore class="pb-6">

            <label for="start_time" class="block text-sm text-gray-500 leading-5">
                Time  
			</label>
            <div class="mt-1 rounded-md shadow-sm">
                <input id="seconds" type="text" step='1' min="00:00:00" max="24:00:00" placeholder=" "
                    wire:model="seconds" required="required"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepickerccc">
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
