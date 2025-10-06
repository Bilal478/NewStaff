<div>
    <x-page.title svg="svgs.logs-error">
        Access Logs
    </x-page.title>
 <div class="w-full pb-4">
        <div class="uppercase text-xs text-gray-400 font-medium flex items-center">
            <div class="flex-1 px-3">
                Action
            </div>
            <div class="flex-1 px-3">
                Performed By 
            </div>
            <div class="flex-1 px-3">
               Target User
            </div>
            <div class="flex-1 px-3">
               Target User Email
            </div>
            <div class="flex-1 px-3">
               Time Period
            </div>
            <div class="flex-1 px-3">
               Original Time
            </div>
             <div class="flex-1 px-3">
               New Time
            </div>
            <div class="flex-1 px-3 text-center">
               Date/Time
            </div>
        </div>
    </div>
    @foreach ($accessLogs as $key=>$logs)
    @if($logs)
    <div class="w-full bg-white py-5 rounded-md border mb-3" style="cursor: pointer;">
        <div class="flex items-center text-sm">
            <div class="flex-1 px-3 truncate">
                {{ $logs->action }}
            </div>
            <div class="flex-1 px-3 text-xs text-gray-500">
                {{ $logs->performed_by_name }}
            </div>
            <div class="flex-1 px-3 text-xs text-gray-500">
                {{ $logs->target_user_name }}
            </div>
             <div class="flex-1 px-3 text-xs text-gray-500">
                {{ $logs->target_user_email }}
            </div>
             <div class="flex-1 px-3 text-xs text-gray-500">
                {{ $logs->start_datetime ? \Carbon\Carbon::parse($logs->start_datetime)->format('H:i:s') : '' }} - 
                {{ $logs->end_datetime ? \Carbon\Carbon::parse($logs->end_datetime)->format('H:i:s') : '' }}
            </div>
             <div class="flex-1 px-3 text-xs text-gray-500">
                {{ $logs->original_time ?? '-' }}
            </div>
             <div class="flex-1 px-3 text-xs text-gray-500">
                {{ $logs->new_time ?? '-' }}
            </div>
            <div class="flex-1 px-3 text-xs text-gray-500 text-center">
                {{ $logs->created_at }}
            </div>
        </div>  
    </div>
    @endif   
    @endforeach 
     <div class="pt-5">
        {{ $accessLogs->links('vendor.pagination.default') }}
    </div> 
</div>