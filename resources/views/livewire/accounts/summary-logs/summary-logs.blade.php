<style>
    #start_datetime{
        padding-right: 0px;
        padding-left: 52px;
    }
    #end_datetime{
        padding-left: 48px;
        margin-left: 76px;
    }
    #sr_no{
        padding-right: 28px;
    }
</style>
<div>
    <x-page.title svg="svgs.logs-error">
        Summary Logs
    </x-page.title>
    <div class="w-full pb-4">
        <div class="uppercase text-xs text-gray-400 font-medium hidden md:flex items-center justify-between">
            <div class="flex-auto px-3" id="sr_no">
                Sr. No
            </div>
            <div class="flex-auto px-3" id="start_datetime">
                Start DateTime
            </div>
            <div class="flex-auto px-3" id="end_datetime">
                End DateTime
            </div>
            <div class="flex-auto px-3">
                Total Emails Sent
            </div>
        </div>
    </div>
    
        @foreach ($summary_logs as $key=>$logs)
        <div class="w-full bg-white py-5 rounded-md border mb-3 cursor-pointer hover:shadow-md">
            <div class="uppercase text-xs text-gray-400 font-medium hidden md:flex items-center justify-between">
                <div class="flex-auto px-3 text-xs text-gray-500">
                    {{ $key+1 }}
                </div>
                <div class="flex-auto px-3 text-xs text-gray-500">
                    {{ $logs->start_datetime }}
                </div>
                <div class="flex-auto px-3 text-xs text-gray-500">
                    {{ $logs->end_datetime }}
                </div>
                <div class="flex-auto px-3 text-xs text-gray-500">
                    {{ $logs->total_emails_sent }}
                </div>
            </div>
        </div>
        @endforeach
   
    <div class="pt-5">
        {{ $summary_logs->links('vendor.pagination.default') }}
    </div>
</div>
