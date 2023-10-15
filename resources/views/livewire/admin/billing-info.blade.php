<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div>
    <div>
        <x-page.title svg="svgs.logs-error">
            Billing Information
        </x-page.title>
    </div>
   
    <div class="flex justify-between">
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full info-box">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    TOTAL ACTIVE COMPANIES
                </h3>
                <span class="text-2xl text-gray-800">{{ count($accounts) }}</span>
                <div class="tooltip">This is total active companies</div>
            </div>
        </div>
    
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full info-box">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    ACTIVE PAID USERS
                </h3>
                <span class="text-2xl text-gray-800">{{ count($activeMembers) }}</span>
                <div class="tooltip">This is total active users</div>
            </div>
        </div>
    
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full info-box">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    LAST TRANSACTION DATE
                </h3>
                @if ($lastTransaction)
                <span class="text-2xl text-gray-800">{{ $lastTransaction }}</span>
                @endif
                <div class="tooltip">This is last transaction date</div>
            </div>
        </div>
    </div>
    <div class="flex justify-between">
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full info-box">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    CURRENT MONTH BILLED AMOUNT
                </h3>
                <span class="text-2xl text-gray-800">${{$currentMonthAmount}}.00</span>
                <div class="tooltip">This is amount of current month of user subscriptions and buy seats</div>
            </div>
        </div>
    
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full info-box">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    TODAY BILLED AMOUNT
                </h3>
                <span class="text-2xl text-gray-800">${{$currentDayAmount}}.00</span>
                <div class="tooltip">This is amount of today of user subscriptions and buy seats </div>
            </div>
        </div>
    
        <div class="w-full sm:w-1/2 xl:w-1/4 text-center mx-4 mb-8">
            <div class="bg-white rounded-md border p-6 h-full info-box">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    CURRENT MONTH CANCELATION AMOUNT
                </h3>
                <span class="text-2xl text-gray-800">${{$currentMonthCanceledAmount}}.00</span>
                <div class="tooltip">This is amount of current month of cancel subscriptions and delete seats</div>
            </div>
        </div>
    </div>
</div>
<style>
    .info-box {
    position: relative;
    cursor: pointer;
}

.info-box .tooltip {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 0.5rem;
    background-color: #ecf4fc;
    color: #333;
    border-radius: 4px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease-in-out;
    width: 295px;
}

.info-box:hover .tooltip {
    opacity: 1;
    visibility: visible;
}
</style>
