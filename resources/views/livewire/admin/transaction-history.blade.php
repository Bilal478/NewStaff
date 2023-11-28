@php
    $transactions=$transactionsData[0];
    $transactionRecords=$transactionsData[1];
@endphp
<div>
    <div>
        <x-page.title svg="svgs.report">
            Transaction History
        </x-page.title>
    </div>
    <div class="md:flex items-center justify-between pb-8">
        <div class="md:flex items-center md:space-x-4">
            <x-inputs.search wire:model.debounce.500ms="search" class="w-full md:w-72" placeholder=""  />
            <x-inputs.datepicker wire:model="startDate" label="Start Date" name="date" class="w-full md:w-72" />
            <x-inputs.datepicker wire:model="endDate" label="End Date" name="date" class="w-full md:w-72" />
        </div>
    </div>
    <div class="w-full pb-4">
        <div class="uppercase text-xs text-gray-400 font-medium flex items-center">
            <div class="w-56 px-3">
                Transaction Id
            </div>
            <div class="w-60 px-3 ">
                Stripe Id
            </div>
            <div class="w-56  text-center">
                User Name
            </div>
            <div class="w-56 px-3 text-center">
                Company Name
            </div>
            <div class="w-44 px-3 text-center">
                Amount
            </div>
            <div class="w-44 px-3 text-center">
                Details
            </div>
            <div class="w-44 px-3 text-center">
                DateTime
            </div>
        </div>
    </div>
    @foreach ($transactions as $key=>$data)
    <div wire:click.stop="$emit('transactionShow','{{ $data['id']}}','{{ $data['amount'] }}')" class="w-full bg-white py-5 rounded-md border mb-3" style="cursor: pointer;">
        <div class="flex items-center text-sm">
            <div class="w-56 px-3 truncate">
                <div class="flex items-center">
                    <x-user.logs />
                    <span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">
                        {{ $data['id'] }}
                    </span>
                </div>
            </div>
            <div class="w-60  text-xs text-gray-500 text-center">
                {{-- {{ $data['payment_intent_id'] }} --}}
                
pi_3OD2twAdgPnFPgwG1MwovEmB
            </div>
            <div class="w-56 text-xs text-gray-500 text-center">
                {{ $data['userName'] }}
            </div>
            <div class="w-56 px-3 text-xs text-gray-500 text-center">
                {{ $data['accountName'] }}
            </div>
            <div class="w-44 px-3 text-xs text-gray-500 text-center">
                ${{ $data['amount'] }}.00
            </div>
            <div class="w-44 px-3 text-xs text-gray-500 text-center">
                @if ($data['action']=='CANCEL_SUBSCRIPTION')
                <td style="padding: 10px;">Cancel Subscription</td>
                @elseif ($data['action']=='DELETE_SEATS')
                <td style="padding: 10px;">Delete Seats</td>
                @elseif ($data['action']=='SUBSCRIPTION_USER')
                <td style="padding: 10px;">Subscription User</td>
                @else
                <td style="padding: 10px;">Buy Seats</td>
                @endif
               </div>
            <div class="w-44 px-3 text-xs text-gray-500 text-center">
                {{ $data['created_at'] }}
            </div>
        </div>  
    </div> 
    @endforeach
        <div wire:loading>
            <!-- Show the loading animation -->
            <div class="loading-overlay">
            <div  class="loading-animation">
                <!-- Add your loading animation here -->  
            </div>
            </div>
        </div>
    <div class="pt-5">
        {{ $transactionRecords->links() }}
    </div>
</div>
@push('modals')
    @livewire('admin.transaction-history.transactions-show')
@endpush
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
</style>