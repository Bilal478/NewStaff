@php
$account_user = DB::table('account_user')
    ->where('user_id', auth()->user()->id)
    ->first();
@endphp
<x-modals.small x-on:open-activity-modal.window="open = true" x-on:close-activity-modal.window="open = false" style="z-index: 10">

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
                
                <p><span class="taskTitle">
                    @if ($item['minutes']==1440)
                        24:00:00
                    @else
                     {{ $item['duration'] }}
                    @endif
                    </span>
                </p>
                
                </td>
                <td class="p-2 border-r">
                
                    <p><span class="taskTitle"> {{ $item['productivity'] }}</span></p>
                    
                </td>
                
                <td  style="cursor: pointer;" class="p-2 border-r">
                <p style="display: inline;"><span class="taskTitle"> {{ $item['start_time'] }} - {{ $item['end_time'] }}</span></p>
                @if($account_user->allow_edit_time == 1)
                <button type="button"  wire:click="$emit('showEditTimeModal', {{ json_encode($item) }})">
                        <span class="text-xs text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg></span>
                </button>  
                <button id="alertConfirm" wire:click.prevent="confirmDeleteActivity({{ json_encode($item) }})" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </button> 
                @endif                 
                </td>
            </tr>
    @endforeach


        </tbody>
    </table>
  
       <div>
    <button wire:click="$emit('activityCreate', '{{ $date }}', '{{ $user_id }}' , 'notActivityPage')" type="button" class="mt-3 w-full h-10 text-center text-sm flex items-center justify-center rounded-md bg-blue-600 text-white hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
        <x-svgs.plus class="w-5 h-5 mr-1" />
        <span>Add time</span>
    </button>
</div>

    


</x-modals.small>
@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
     
    window.addEventListener('show-delete-confirmation', event => {
        swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this activity!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    Livewire.emit('deleteConfirmedActivites');
                    // Livewire.emit('refreshActivities')
                    swal("Your activity has been deleted!", {
                        icon: "success",
                    });
                }
            });
    });

    function refreshPagintation()
    {
        Livewire.emit('refreshPagination');
    }
</script>
@endpush
