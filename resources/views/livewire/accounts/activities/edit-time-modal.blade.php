	<x-modals.small x-on:open-activities-edit-time-modal.window="open = true" x-on:close-activities-edit-time-modal.window="open = false">
		<form wire:submit.prevent="update"  autocomplete="off">	 
			
			<h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
				Edit Time		
			</h5>
            <div class="border border-dark rounded">
                <div class="flex justify-around">
                  <div>
                    <h3>Member</h3>
                    <span>Ali</span>
                  </div>
                  <div>
                    <h3>Project</h3>
                    <span>Web</span>
                  </div>
                </div>
                <div class="ml-24 flex flex-col">
                    <span>Timespan</span>
                    <span>Tuesday 12,2023 14:23pm</span>
                </div>
              </div>
              <div class="toggle-container  mt-3 mb-3">
                <button id="dailyReportButton" class="toggle-button active" onclick="toggleReport('daily')">Reassign Time</button>
                <button id="weeklyReportButton" class="toggle-button" onclick="toggleReport('weekly')">Delete Time</button>
            </div>
            <span class="mr-2">From</span><input type="text" class="border border-dark"> <span class="mr-2">To</span><input type="text" class="border border-dark">
              
              

          
              
			
		
				
			<div class="flex justify-end mt-2">
				<x-buttons.blue-inline type="submit">
				  Update Time
				</x-buttons.blue-inline>
			</div>
		</form>
	</x-modals.small>

<script>
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
</style>
	