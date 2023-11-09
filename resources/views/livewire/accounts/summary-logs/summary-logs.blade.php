<div>
    <x-page.title svg="svgs.logs-error">
        Summary Logs
    </x-page.title>
    <div class="w-full pb-4">
        <div class="uppercase text-xs text-gray-400 font-medium flex items-center">
            <div class="flex-1 px-3">
                Sr No
            </div>
            <div class="w-56 px-3">
                Start DateTime
            </div>
            <div class="w-56 px-3">
                End DateTime
            </div>
            <div class="w-56 px-3 text-center">
                Total Emails Sent
            </div>
        </div>
    </div>
    @foreach ($summary_logs as $key=>$logs)
    @if($logs)
    <div class="w-full bg-white py-5 rounded-md border mb-3" style="cursor: pointer;">
        <div class="flex items-center text-sm">
            <div class="flex-1 px-3 truncate">
                <div class="flex items-center">
                    <x-user.logs />
                    {{-- <x-svgs.logs-error class="w-6 h-6 text-blue-600 mr-3" /> --}}
                    <span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">
                        {{  $key+1 }}
                    </span>
                </div>
            </div>
            <div class="w-56 px-3 text-xs text-gray-500">
                {{ $logs->start_datetime }}
            </div>
            <div class="w-56 px-3 text-xs text-gray-500">
                {{ $logs->end_datetime }}
            </div>
            <div class="w-56 px-3 text-xs text-gray-500 text-center">
                {{ $logs->total_emails_sent }}
            </div>
        </div>  
    </div>
    @endif   
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
        {{ $summary_logs->links('vendor.pagination.default') }}
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
</style>
