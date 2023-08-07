@php
use Carbon\Carbon;
$totalTime = Carbon::createFromTime(0, 0, 0); 
foreach ($selectedDateRecord as $index => $activity) {
    $time = $activity['duration'];
    $timeParts = explode(':', $time);
    $hours = (int) $timeParts[0];
    $minutes = (int) $timeParts[1];
    $seconds = (int) $timeParts[2];
    $totalTime = $totalTime->addHours($hours)
                           ->addMinutes($minutes)
                           ->addSeconds($seconds);
}
$totalTimeFormatted = $totalTime->format('H:i:s');
$account_user = DB::table('account_user')
    ->where('user_id', auth()->user()->id)
    ->first();
@endphp
<div>
    <x-modals.small x-on:open-edit-activity-modal.window="open = true" x-on:close-edit-activity-modal.window="open = false">
        <article class="pt-2 pb-4">
            <div class="flex items-center space-x-4 mb-4">
                <div class="flex items-center text-gray-500">
                    <x-svgs.user class="w-6 h-6 mr-1 text-black-600" />
                    @if ($userName)
                    <span class="block text-left font-montserrat text-lg font-medium "><b>{{$userName}}</b></span>
                    @else
                    <span class="text-xs">Not assigned</span>
                    @endif
                </div>
                <div style="text-align:center; background-color: #F5F5F5; border-radius: 6px; padding: 2px 0px;" class="w-32 px-3 text-lg text-gray-700">
                {{$totalTimeFormatted}}
                </div>
            </div>
        </article>
        <div class="w-1/2 md:w-full mb-4 md:mb-0" style="display:inline-block;">
            <div class="mx-2 flex items-center justify-center">
                <button wire:click.prevent="subDay()" type="button" class="h-10 appearance-none bg-white block px-3 py-2 border border-gray-300 rounded-md text-gray-600 focus:outline-none hover:border-blue-500 hover:text-blue-600 transition duration-150 ease-in-out text-sm leading-5 mr-2">
                    <x-svgs.arrow-left class="h-4 w-4" />
                </button>

                <button wire:click.prevent="addDay()" type="button" class="h-10 appearance-none bg-white block px-3 py-2 border border-gray-300 rounded-md text-gray-600 focus:outline-none hover:border-blue-500 hover:text-blue-600 transition duration-150 ease-in-out text-sm leading-5 mr-2">
                    <x-svgs.arrow-right class="h-4 w-4" />
                </button>
                <div class="flex-1">
                    <x-inputs.datepicker-without-label wire:model="date" class="w-full" name="date" type="text" :clear-button="false" />
                </div>
            </div>
        </div>
        <br>
        <br>
        <table class="w-full border mt-5">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Project</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Task</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Duration</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Activity</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Time</th>
            </tr>
        </thead>
        <tbody>
        
            @foreach ($selectedDateRecord as $item)
            <tr class="text-left uppercase text-center text-xs text-gray-700 font-medium border-b-2">
                   
                <td class="p-2 border-r ">
                
                    <p><span class="taskTitle"> {{ $item['project_title'] }}</span></p>
                    
                </td>
                <td class="p-2 border-r ">
                
                    <p><span class="taskTitle"> {{ $item['task_title'] }}</span></p>
                    
                </td>
                <td class="p-2 border-r ">
                
                <p><span class="taskTitle"> {{ $item['duration'] }}</span></p>
                
                </td>
                <td class="p-2 border-r">
                
                    <p><span class="taskTitle"> {{ $item['productivity'] }}</span></p>
                    
                </td>
                
                <td  style="cursor: pointer;"  class="p-2 border-r">
                <p style="display: inline;"><span class="taskTitle"> {{ $item['start_time'] }} - {{ $item['end_time'] }}</span></p>
                @if($account_user->allow_edit_time == 1)
                <button type="button" wire:click="$emit('showEditTimeModal', {{ json_encode($item) }})">
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
    </x-modals.small>
</div>
@push('scripts')

<script>
    $('.timepicker_one').timepicker({

        timeFormat: 'HH:mm:ss a',
        interval: 30,
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        change: tmTotalHrsOnSite

    });

    function tmTotalHrsOnSite() {
        var x = $(".timepicker_one").val();

        document.getElementById('seconds_two').value = '';

        let elementName = $(".timepicker_one").attr('id');
        var data = $(".timepicker_one").val();
        @this.set(elementName, data);

        var block_time = x.split(':');

        var start_t = parseInt(block_time[1]) + parseInt(30);

        if (start_t == 60) {
            start_t = '00';
            block_time[0] = parseInt(block_time[0]) + parseInt(1);
        }

        $('.timepicker_two').timepicker({
            timeFormat: 'HH:mm:ss a',
            interval: 30,
            dynamic: false,
            dropdown: true,
            scrollbar: true,
            change: tmTotalHrsOnSite_two
        });
        $('.timepicker_two').timepicker('option', 'minTime', new Date(0, 0, 0, block_time[0], start_t, 0));
    }

    function tmTotalHrsOnSite_two() {
        var x = $(".timepicker_two").val();
        let elementName = $(".timepicker_two").attr('id');
        var data = $(".timepicker_two").val();
        @this.set(elementName, data);
    }
</script>
@endpush
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
                    Livewire.emit('deleteConfirmedActivitesFromTask');
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
