<div style="overflow-x: auto;">
    <x-page.title svg="svgs.logs-error">
        Summary Logs
    </x-page.title>
    <table>
        <thead>
            <tr class="text-gray-400 text-xs" style="font-size: 0.85rem; text-align:left;">
                <th>Sr No</th>
                <th>Start DateTime</th>
                <th>End DateTime</th>
                <th>Total Emails Sent</th>
            </tr>
            <tr style="background-color: transparent;">
                <th style="height: 10px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summary_logs as $key=>$logs)
            <tr style="font-size:13px;" class="bg-white border border-gray-200 text-gray-500 ">
                <td style="padding: 10px;">{{  $key+1 }}</td>
                <td style="padding: 10px;">{{ $logs->start_datetime }}</td>
                <td style="padding: 10px;">{{ $logs->end_datetime }}</td>
                <td style="padding: 10px;">{{ $logs->total_emails_sent }}</td>
            </tr>
            <tr style="background-color: transparent;">
                <td style="height: 10px;"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pt-5">
        {{ $summary_logs->links('vendor.pagination.default') }}
    </div>
    <div wire:loading>
        <!-- Show the loading animation -->
        <div class="loading-overlay">
        <div  class="loading-animation">
            <!-- Add your loading animation here -->
            
        </div>
        </div>

    </div>
</div>
<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.7);
        z-index: 999;
    }

    

    .loading-animation {
    /* Add your styles for the loading animation */
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
    table {
        border-collapse: collapse;
        width: 100%;
        border: none;
    }

    th, td {
        padding: 8px;
        text-align: center;
        border: none;
    }

    @media screen and (max-width: 600px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr { border: 1px solid #ccc; }

        td {
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
        }

        td:before {
            position: absolute;
            top: 6px;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
        }

        td:nth-of-type(1):before { content: "Sr No"; }
        td:nth-of-type(2):before { content: "Start DateTime"; }
        td:nth-of-type(3):before { content: "End DateTime"; }
        td:nth-of-type(4):before { content: "Total Emails Sent"; }

</style>
