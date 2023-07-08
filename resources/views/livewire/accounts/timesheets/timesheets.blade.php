<div>
    <x-page.title svg="svgs.computer">
        Timesheets
        <div class="toggle-container ml-16">
            <button id="dailyReportButton" class="toggle-button active" onclick="toggleReport('daily')">Daily Report</button>
            <button id="weeklyReportButton" class="toggle-button" onclick="toggleReport('weekly')">Weekly Report</button>
        </div>
    </x-page.title>
    
</div>


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
