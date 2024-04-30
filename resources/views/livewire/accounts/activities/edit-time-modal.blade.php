<x-modals.small x-on:open-activities-edit-time-modal.window="open = true" x-on:close-activities-edit-time-modal.window="open = false">
		<form wire:submit.prevent="update"  autocomplete="off">	 
			
			<h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
				Edit Time		
			</h5>
            <div class="border border-dark rounded p-3">
                <div class="flex justify-around">
                  <div>
                    <h3>Member</h3>
                    <span>{{$firstName}} {{$lastName}}</span>
                  </div>
                  <div>
                    <h3>Project</h3>
                    <span>{{$projectTitle}}</span>
                  </div>
                </div>
                <div class="flex flex-col" id="timespan-div">
                    <span id="timespan">TIME SPAN</span>
                    <span>{{$date}} from {{$startTime}} to {{$endTime}}</span>
                </div>
            </div>
            <h3 class="mb-3 mt-3">Old Time :</h3>
            <span class="mr-2">From</span><input type="text" class="border border-dark custom-input-small" value="{{$startTime}}" readonly><br><br>
            <span class="mr-7">To</span><input type="text" class="border border-dark custom-input-small" value="{{$endTime}}" readonly>
            <h3 class="mb-3 mt-3">New Time :</h3>
            <span class="mr-2">From</span><input wire:model="newStartTime" id="startTimePicker" type="text" class="border border-dark custom-input"><br><br>
            <span class="mr-7">To</span><input wire:model="newEndTime" id="endTimePicker" type="text" class="border border-dark custom-input">
			<div class="flex justify-end mt-6">
				<x-buttons.blue-inline type="submit">
				  Update Time
				</x-buttons.blue-inline>
			</div>
		</form>
	</x-modals.small>

<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.6/dist/flatpickr.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
   const startTimePicker = flatpickr("#startTimePicker", {
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: "{{$startTime}}",
       minuteIncrement: 10,
       onClose: function (selectedDates, dateStr) {
           const startTimeDisplay = document.getElementById("startTimeDisplay");
           if (startTimeDisplay) {
               startTimeDisplay.textContent = dateStr;
           }
       }
   });

   const endTimePicker = flatpickr("#endTimePicker", {
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: "{{$endTime}}",
       minuteIncrement: 10,
       onClose: function (selectedDates, dateStr) {
           const endTimeDisplay = document.getElementById("endTimeDisplay");
           if (endTimeDisplay) {
               endTimeDisplay.textContent = dateStr;
           }
       }
   });
});
</script>

<style>
    #timespan{
        font-weight: 600;
        margin-top: 5px;
    }
    #timespan-div{
        margin-left: 56px;
    }
    h3{
        font-weight: 600;
    }
    .custom-input {
    height: 40px; 
    width: 308px;
    border-radius: 5px;
  }
  .custom-input-small{
    height: 40px; 
    width: 80px; 
    border-radius: 5px;
    text-align: center;
    margin-left: 5px;
  }
</style>
	