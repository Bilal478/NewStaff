<x-modals.small x-on:open-activity-modal.window="open = true" x-on:close-activity-modal.window="open = false">

    <table class="w-full border mt-5">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Duration</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Activity</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Time</th>
            </tr>
        </thead>
        <tbody>
        <tr class="bg-gray-50 text-center"> 
        <p><small>{!! $activities?('<b>Date:</b> '.$activities[0]['date']):'' !!}</small> </p>
        <p><small>{!! $activities?('<b>Project:</b> '.$activities[0]['project_title']):'' !!}</small> </p>
        <p><small>{!! $activities?('<b>Task:</b> '.$activities[0]['task_title']):'' !!}</small> </p>
        
        </tr>
            @foreach ($activities as $item)
            <tr class="text-left uppercase text-center text-xs text-gray-700 font-medium border-b-2">
                   
                
                <td class="p-2 border-r ">
                
                <p><span class="taskTitle"> {{ $item['duration'] }}</span></p>
                
                </td>
                <td class="p-2 border-r">
                
                    <p><span class="taskTitle"> {{ $item['productivity'] }}</span></p>
                    
                </td>
                
                <td  style="cursor: pointer;" wire:click="$emit('showEditTimeModal', {{ json_encode($item) }})" class="p-2 border-r">
                <p style="display: inline;"><span class="taskTitle"> {{ $item['start_time'] }} - {{ $item['end_time'] }}</span></p>
                <button type="button">
                        <span class="text-xs text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg></span>
                    </button>                    
                </td>
            </tr>
    @endforeach


        </tbody>
    </table>


</x-modals.small>
