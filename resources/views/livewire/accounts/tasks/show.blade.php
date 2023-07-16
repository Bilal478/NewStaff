@php
$account_user = DB::table('account_user')
    ->where('user_id', auth()->user()->id)
    ->first();
@endphp
<div>

    <x-modals.small x-on:open-task-show-modal.window="open = true" x-on:close-task-show-modal.window="open = false">
        @if ($task)
        <article class="pt-2 pb-4">
            <div class="flex items-center space-x-4 mb-4">
                <x-tasks.status-badge :status="$task->completed" />
                <div class="flex items-center text-gray-500">
                    <x-svgs.calendar class="w-4 h-4 mr-1 text-blue-600" />
                    @if ($task->due_date)
                    <span class="text-xs">{{ $task->due_date->format('M d, Y') }}</span>
                    @else
                    <span class="text-xs">No due datex</span>
                    @endif
                </div>
                <div class="flex items-center text-gray-500">
                    <x-svgs.user class="w-4 h-4 mr-1 text-blue-600" />
                    @if ($task->user)
                    <span class="block text-left font-montserrat text-xs">{{ $task->user->firstname }} {{ $task->user->lastname }}</span>
                    @else
                    <span class="text-xs">Not assigned</span>
                    @endif
                </div>
                <div style="text-align:center; background-color: #F5F5F5; border-radius: 6px; padding: 2px 0px;" class="w-32 px-3 text-xs text-gray-700">
                    {{ gmdate("H:i:s", App\Models\Activity::where('project_id',$task->project_id)->where('task_id',$task->id)->sum('seconds'))}}
                </div>
            </div>
            <h5 class="font-montserrat font-semibold text-gray-700 mb-4">
                {{ $task->title }}
            </h5>
            <p class="text-sm text-gray-500">
                {{ $task->project->title }}
            </p>
            <p class="text-sm text-gray-500">
                {{ $task->description }}
            </p>
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

        {{-- <div class="sm:flex new-activity-button pb-0" style="display:inline-block;" >
			<button wire:click="$emit('activityCreate')" type="button" class="w-full sm:w-auto mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
				<x-svgs.plus class="w-5 h-5 mr-1" />
					Add time
            </button>
		</div>
        --}}
        <br>
        <br>
        <table class="w-full border mt-5">
        <thead>
            <tr class="bg-gray-50 border-b">
                
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Duration</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Activity</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Time</th>
            </tr>
        </thead>
        <tbody>
        
            @foreach ($selectedDateRecord as $item)
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







        <!-- <div>
            <ul>
                @foreach ($activities as $item) 

                <li>{{ $item->date->format('D, F d, Y ') }} - {{ "Activity $item->id" }} - {{ gmdate("H:i:s", $item->seconds) }}
                @if($account_user->allow_edit_time == 1)
                    <button type="button" wire:click="$emit('timeEdit','{{ $item->id }}','{{ $item->seconds }}','{{ $task->title }}')">
                        <span class="text-xs text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg></span>
                    </button>

                    {{-- <button id="alertConfirm" onclick="alertConfirm()"   wire:click.stop="$emit('deleteActivity','{{ $item->id }}')" type="button"> --}}
                    <button id="alertConfirm" wire:click.prevent="confirmDeleteActivity({{ $item->id }})" type="button">


                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>

                @endif

                </li>
                @endforeach              

                @if ( $count ==0 )
                <script>
                  refreshPagintation();           
                </script>
                <?php echo "<p style='color: #737373;'>There aren't activity for this date</p>"; ?>
                @endif
                <br>
            </ul>
            {{ $activities->links() }}
        </div> -->
        @endif
    </x-modals.small>

    <div>
        <x-modals.small2 x-on:open-activities-form-modal-show.window="open = true" x-on:close-activities-form-modal-show.window="open = false">
            <form wire:submit.prevent="create_activity_time" autocomplete="off">

                <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
                    Create Activity
                </h5>

                <label for="start_time" class="block text-sm text-gray-500 my-4 leading-5">
                    Date
                </label>

                <div class="flex-1">
                    <x-inputs.datepicker-without-label-two wire:model="datetimerange" class="w-full" name="datetimerange" type="text" :clear-button="false" />
                </div>

                <?php
                if ($datetimerange > date('Y-m-d')) {
                ?>
                    <span class="mt-4" style="font-size: 13px; color:red;">Cannot be in the future</span>
                <?php
                }
                ?>
                <label for="start_time" class="block mt-4 text-sm text-gray-500 leading-5">
                    Start Time
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <input name="seconds_one" id="seconds_one" type="text" step='1' min="00:00:00" max="24:00:00" wire:model.prevent="seconds_one" required="required" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepicker_one">
                </div>

                <?php if ($seconds_one > date("HH:mm:ss a")) { ?>
                    <span class="mt-4" style="font-size: 13px; color:red;">Cannot be in the future</span>
                <?php
                }
                ?>
                <label for="end_time" style="padding-top: 15px !important;" class="block text-sm  text-gray-500 leading-5">
                    End Time
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <input name="seconds_two" id="seconds_two" type="text" step='1' min="12:00:00" max="24:00:00" wire:model="seconds_two" required="required" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 timepicker_two">
                </div>
                <?php if ($seconds_one > date("HH:mm:ss a")) { ?>
                    <span class="mt-4" style="font-size: 13px; color:red;">Cannot be in the future</span>
                <?php
                }
                ?>

                <div class="flex justify-end mt-2">
                    <x-buttons.blue-inline type="submit">
                        Create Activity
                    </x-buttons.blue-inline>
                </div>
            </form>
        </x-modals.small2>
    </div>
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
                    Livewire.emit('deleteConfirmed');
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