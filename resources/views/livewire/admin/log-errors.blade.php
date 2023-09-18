<div style="overflow-x: auto;">
    <x-page.title svg="svgs.logs-error">
        Log Errors
    </x-page.title>
    <table>
        <thead>
            <tr class="text-gray-400 text-xs" style="font-size: 0.85rem;">
                <th style="text-align: left">Error Message</th>
                <th>Date Occurred</th>
                <th>Last Date Occurred</th>
                <th>Times Occurred</th>
                <th>Completed Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <tr style="background-color: transparent;">
                <th style="height: 10px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logsData as $data)
            <tr style="font-size:13px;" class="bg-white border border-gray-200 text-gray-500 ">
                <td style="text-align: left; padding: 10px;">{{ Str::limit($data->message, 50) }}</td>
                <td style="padding: 10px;">{{ $data->timestamp }}</td>
                <td style="padding: 10px;">{{ $data->last_date_ocurred }}</td>
                <td style="padding: 10px;">{{ $data->times_ocurred }}</td>
                <td style="padding: 10px;">{{ $data->completed_date }}</td>
                <td style="padding: 10px;">{{ $data->status }}</td>
                <td style="padding: 10px;">
                    <x-dropdowns.context-menu>
                        <x-dropdowns.context-menu-item  name="Edit" wire:click.stop="$emit('logErrorsEdit', {{$data->id}})" svg="svgs.edit"/>
                    </x-dropdowns.context-menu>
                </td>
            </tr>
            <tr style="background-color: transparent;">
                <td style="height: 10px;"></td>
            </tr>
            
            
            @endforeach
        </tbody>
    </table>
    <div class="pt-5">
        {{ $logsData->links('vendor.pagination.default') }}
    </div>
</div>
@push('modals')
@livewire('admin.edit-log-errors');
@endpush
<style>
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

    th {
        /* background-color: #f2f2f2; */
    }

    th:first-child,
    td:first-child {
        flex: 2; /* Set the width of the first column (Error Message) to double the others */
        min-width: 200px; /* Set a minimum width to ensure responsiveness */
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

        td:nth-of-type(1):before { content: "Error Message"; }
        td:nth-of-type(2):before { content: "Date Occurred"; }
        td:nth-of-type(3):before { content: "Last Date Occurred"; }
        td:nth-of-type(4):before { content: "Times Occurred"; }
        td:nth-of-type(5):before { content: "Completed Date"; }
        td:nth-of-type(6):before { content: "Status"; }
        td:nth-of-type(7):before { content: "Action"; }
    }
</style>
