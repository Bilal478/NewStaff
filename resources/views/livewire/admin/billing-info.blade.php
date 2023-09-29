@php
    $transactions=$transactionsData[0];
    $transactionRecords=$transactionsData[1];
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div>
    <div class="pb-12">
        <h1 class="font-montserrat text-xl font-semibold text-gray-700 float-left mr-20">
            Billing Information
        </h1>
    </div>
   
    <div class="flex justify-between">
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    TOTAL ACTIVE COMPANIES
                </h3>
                <span class="text-2xl text-gray-800">{{ count($accounts) }}</span>
            </div>
        </div>
    
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    ACTIVE PAID USERS
                </h3>
                <span class="text-2xl text-gray-800">{{ count($activeMembers) }}</span>
            </div>
        </div>
    
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    LAST TRANSACTION DATE
                </h3>
                @if ($lastActiveMember)
                <span class="text-2xl text-gray-800">{{ $lastActiveMember->created_at }}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="flex justify-between">
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    CURRENT MONTH BILLED AMOUNT
                </h3>
                <span class="text-2xl text-gray-800">${{$currentMonthAmount}}.00</span>
            </div>
        </div>
    
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    TODAY BILLED AMOUNT
                </h3>
                <span class="text-2xl text-gray-800">${{$currentDayAmount}}.00</span>
            </div>
        </div>
    
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    CURRENT MONTH CANCELATION AMOUNT
                </h3>
                <span class="text-2xl text-gray-800">${{$currentMonthCanceledAmount}}.00</span>
            </div>
        </div>
    </div>
    
    
    <div class="pb-12">
        <h1 class="font-montserrat text-xl font-semibold text-gray-700 float-left mr-20">
            Transaction History
        </h1>
    </div>

    <table>
        <thead>
            <tr class="text-gray-400 text-xs" style="font-size: 0.85rem; text-align:left;">
                <th>User Name</th>
                <th>Transaction Amount</th>
                <th>Transaction Detail</th>
                <th>DateTime</th>
            </tr>
            <tr style="background-color: transparent;">
                <th style="height: 10px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $key=>$data)
            <tr style="font-size:13px;" class="bg-white border border-gray-200 text-gray-500 ">
                <td style="padding: 10px;">{{ $data['userName'] }}</td>
                <td style="padding: 10px;">${{ $data['amount'] }}.00</td>
                @if ($data['action']=='cancel_subscription')
                <td style="padding: 10px;">Cancel Subscription</td>
                @elseif ($data['action']=='buy_seats')
                <td style="padding: 10px;">Buy Seats</td>
                @elseif ($data['action']=='subscription_user')
                <td style="padding: 10px;">User Subscription</td>
                @else
                <td style="padding: 10px;">Delete Seats</td>
                @endif
                <td style="padding: 10px;">{{ $data['created_at'] }}</td>
            </tr>
            <tr style="background-color: transparent;">
                <td style="height: 10px;"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pt-5">
        {{ $transactionRecords->links() }}
    </div>
</div>
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
