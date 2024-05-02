<div>
	<x-modals.small x-on:open-activities-form-modal-2.window="open = true" x-on:close-activities-form-modal-2.window="open = false">
		<form wire:submit.prevent="create_activity"  autocomplete="off">	 
			
			<h4 class="font-montserrat text-center font-semibold text-lg text-gray-700 mb-6">
				{{isset($task['title'])?$task['title']:''}}
			</h4>
			
			<h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
				Create Activity			
			</h5>
			
			<label for="start_time" class="block text-sm text-gray-500 my-4 leading-5">
				Date 
			</label>
			
			<div class="flex-1">
                    <x-inputs.datepicker-without-label-two wire:model="datetimerange" class="w-full" name="datetimerange" type="text"
                        :clear-button="false" />
            </div>
			
			<?php	//echo "Seconds: ".$seconds_one_task." - Seconds Two:".$seconds_two_task; ?>

				<label for="start_time" class="block mt-4 text-sm text-gray-500 leading-5">
					Start Time 
				</label>
				<div class="mt-1 rounded-md shadow-sm">
                <input name="seconds_one_task" id="seconds_one_task" type="text" step='1' min="00:00:00" max="24:00:00"
                    wire:model="seconds_one_task" required="required"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepicker_one_task">
				</div>

				<label for="end_time" style="padding-top: 15px !important;" class="block text-sm  text-gray-500 leading-5">
					End Time  
				</label>
				<div class="mt-1 rounded-md shadow-sm">
                <input name="seconds_two_task" id="seconds_two_task" type="text" step='1' min="12:00:00" max="24:00:00"
                    wire:model="seconds_two_task" required="required"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepicker_two_task">
				</div>
				
			<div class="flex justify-end mt-2">
				<x-buttons.blue-inline type="submit">
				  Create Activity
				</x-buttons.blue-inline>
			</div>
		</form>
	</x-modals.small>
</div>	
	@push('scripts')
	<script>
	$(document).ready(function() {
    $('.timepicker_one_task').timepicker({
        timeFormat: 'HH:mm:ss a',
        interval: 30,
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        change: function() {
            var data = $(".timepicker_one_task").val();
            Livewire.emit('updateTimepickerOneTask', data);
            updateTimepickerTwoTask();
        }
    });

    function updateTimepickerTwoTask() {
        var x = $(".timepicker_one_task").val();
        var block_time = x.split(':');
        var start_t = parseInt(block_time[1]) + parseInt(30);
        if (start_t == 60) {
            start_t = '00';
            block_time[0] = parseInt(block_time[0]) + parseInt(1);
        }

        $('.timepicker_two_task').timepicker('option', 'minTime', new Date(0, 0, 0, block_time[0], start_t, 0));
    }

    $('.timepicker_two_task').timepicker({
        timeFormat: 'HH:mm:ss a',
        interval: 30,
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        change: function() {
            var data = $(".timepicker_two_task").val();
            Livewire.emit('updateTimepickerTwoTask', data);
        }
    });
});

</script>
@endpush


