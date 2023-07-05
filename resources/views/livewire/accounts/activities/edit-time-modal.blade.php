<x-modals.small x-on:open-activities-edit-time-modal.window="open = true" x-on:close-activities-edit-time-modal.window="open = false">
		<form wire:submit.prevent="update"  autocomplete="off">	 
			
			<h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
				Split Time		
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
                    <span id="timespan">Timespan</span>
                    <span>{{$date}} from {{$startTime}} to {{$endTime}}</span>
                </div>
            </div>
            <div class="toggle-container  mt-3 mb-3">
                <button id="dailyReportButton" class="toggle-button active" onclick="toggleReport('daily')">Reassign Time</button>
                <button id="weeklyReportButton" class="toggle-button" onclick="toggleReport('weekly')">Delete Time</button>
            </div>
            <h3 class="mb-3">New Time :</h3>
            <span class="mr-2">From</span><input wire:model="newStartTime" id="startTimePicker" type="text" class="border border-dark custom-input" value="{{$startTime}}"><br><br>
            <span class="mr-7">To</span><input wire:model="newEndTime" id="endTimePicker" type="text" class="border border-dark custom-input" value="{{$endTime}}">
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
        onClose: function (selectedDates, dateStr, instance) {
            document.getElementById("startTimeDisplay").textContent = dateStr;
            instance.close(); // Close the time picker after selecting a time
        }
    });

    const endTimePicker = flatpickr("#endTimePicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        defaultDate: "{{$endTime}}",
        minuteIncrement: 10,
        onClose: function (selectedDates, dateStr, instance) {
            document.getElementById("endTimeDisplay").textContent = dateStr;
            instance.close(); // Close the time picker after selecting a time
        }
    });
});


    function toggleReport(reportType) {
        if (reportType === 'daily') {
            document.getElementById('dailyReportButton').classList.add('active');
            document.getElementById('weeklyReportButton').classList.remove('active');
            // TODO: Show the daily report content and hide the weekly report content
        } else if (reportType === 'weekly') {
            document.getElementById('dailyReportButton').classList.remove('active');
            document.getElementById('weeklyReportButton').classList.add('active');
            // TODO: Show the weekly report content and hide the daily report content
        }
    }
</script>

<style>
    .toggle-container {
        display: flex;
    }

    .toggle-button {
        padding: 5px 10px;
        background-color: #ddd;
        border: none;
        outline: none;
        cursor: pointer;
    }

    .toggle-button.active {
        background-color: #007bff;
        color: #fff;
    }
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
    height: 40px; /* Adjust the height as needed */
    width: 308px; /* Adjust the width as needed */
    border-radius: 5px;
  }
</style>
	