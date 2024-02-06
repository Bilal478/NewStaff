<?php 
use Carbon\Carbon;
$user_login = auth()->id();
?>
@php
$account_user = DB::table('account_user')
    ->where('user_id', auth()->user()->id)
    ->first();
@endphp
<div>
    <x-page.title svg="svgs.report">
        Timesheets
    </x-page.title>
    <div class="container">
        <span class="stripe">
        <a class="toggle-button"  href="{{ route('accounts.reports') }}">Weekly</a>
        <a class="toggle-button white" href="{{ route('accounts.dailyreports') }}" >Daily</a>
        
        </span>
    
  </div>
    <div class="flex items-center flex-wrap pb-8 flex-col sm:flex-row">
        <div>
            <div class="mx-auto flex items-center justify-center">
                <button wire:click.prevent="prevWeek()" type="button"
                    class="h-10 appearance-none bg-white block px-3 py-2 border border-gray-300 rounded-md text-gray-600 focus:outline-none hover:border-blue-500 hover:text-blue-600 transition duration-150 ease-in-out text-sm leading-5 mr-2">
                    <x-svgs.arrow-left class="h-4 w-4" />
                </button>

                <button wire:click.prevent="nextWeek()" type="button"
                    class="h-10 appearance-none bg-white block px-3 py-2 border border-gray-300 rounded-md text-gray-600 focus:outline-none hover:border-blue-500 hover:text-blue-600 transition duration-150 ease-in-out text-sm leading-5 mr-2">
                    <x-svgs.arrow-right class="h-4 w-4" />
                </button>
                <div class="flex-1">
                    <x-inputs.datepicker-week-without-label wire:model="week" faux-date="date" class="w-60 sm:w-72"
                        name="date" type="text" :clear-button="false" />
                </div>
		
            </div>
        </div>
        @role(['owner', 'manager'])
                <div class="mt-4  sm:mt-0 mr-4">
                        <div class="ml-2">
                        <x-inputs.select-without-label wire:model="user_id" class="w-60" name="user_id">
                                @forelse ($user_list->count() ? $user_list : $login as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $user_login ? 'selected' : '' }}>
                                        {{ $user->full_name }}
                                    </option>
                                @empty
                                    <option disabled>No users found.</option>
                                @endforelse
                            </x-inputs.select-without-label>
                        </div>
		</div>
        @if (count($users))
        <button wire:click="download" type="button"
            class="w-full sm:w-auto mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
            <x-svgs.plus class="w-5 h-5 mr-1" />
            Download PDF
        </button>
        @endif
		@endrole
       
    </div>

    
    <div class="w-full overflow-x-auto rounded-md border">
        <table class="w-full bg-white">
            <tbody>
            <div wire:loading>
                <!-- Show the loading animation -->
                <div class="loading-overlay">
                <div  class="loading-animation">
                    <!-- Add your loading animation here -->
                   
                </div>
                </div>
       
            </div>
            @foreach ($dates as $date)
                <?php
                $totalDuration = collect($users)
                ->where('date', $date->format('Y-m-d'))
                ->sum(function ($item) {
                // Convert duration to seconds and sum them up
                return strtotime($item['duration']) - strtotime('00:00:00');
                });

                // Convert total seconds back to HH:MM:SS format
                $totalDurationFormatted = gmdate('H:i:s', $totalDuration);
                ?>
                <?php $inner_count = 0; ?>
            <tr class="text-left  text-xs text-gray-700 font-medium border-b-2">
            <th class="px-4 py-4">
                {{ $date->format('D, M d, Y') }}
                @if ($totalDurationFormatted != '00:00:00')
                    {{ $totalDurationFormatted }} hrs
                @endif
            </th>
            </tr>
            @foreach ($users as $day)
                @if($date->format('Y-m-d') == $day['date'])
                    @if($inner_count==0)
                   
                    <tr class="text-left font-extrabold text-xs text-gray-700 font-medium border-b-2">

                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Organization
                    </td>
                    
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Project
                    </td>

                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Task
                    </td>
                   
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Activity
                    </td>

                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Idle
                    </td>
                      
                     <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Manual
                    </td>

                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Duration
                    </td>
                     <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Start
                    </td>
                     <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Stop
                    </td>
                     <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Type
                    </td>
                     <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Payment Type
                    </td>
                </tr>
                @endif
                <?php $inner_count = 1; ?>
                <tr class="text-left text-xs text-gray-700 font-medium border-b-2 @if($loop->odd) bg-gray-200 @endif">
                   
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
                    
                        <p><span class="taskTitle">{{ $day['account_name'] }}</span></p>
                        
                    </td>
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
                   
                   <p><span class="taskTitle"> {{ $day['project_title'] }}</span></p>
                   
               </td>
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
              
                   <p><span class="taskTitle">  {{ $day['task_title'] }}</span></p>
                   
               </td>
                <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
              
                   <p><span class="taskTitle">  {{ $day['productivity'] }}%</span></p>
                   
               </td>
               
              <!--  <td  style="cursor: pointer;"  class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
              
                   <p style="display: inline;"><span class="taskTitle"> {{ $day['start_time'] }} - {{ $day['end_time'] }}</span></p>
                   @if($account_user->allow_edit_time == 1)
                   <button type="button" wire:click="$emit('showEditTimeModal', {{ json_encode($day) }})">
                        <span class="text-xs text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg></span>
                    </button>
                    <button id="alertConfirm" wire:click.prevent="confirmDeleteActivity({{ json_encode($day) }})" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                    @endif
               </td> -->
                <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
              
                   <p><span class="taskTitle"> {{ $day['productivity'] }}</span></p>
                   
               </td> 
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
              
                   <p><span class="taskTitle">
                   @if ($day['is_manual_time'] == 1)
                   100%
                   @else
                   0%
                   @endif  
                   </span></p>
                   
               </td> 
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
              
                   <p><span class="taskTitle"> {{ $day['duration'] }}</span></p>
                   
               </td> 
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
              
                   <p><span class="taskTitle"> {{ $date->format('D, M d, Y') }} {{ Carbon::createFromFormat('h:i A', $day['start_time'])->format('g:i a') }}

                   </span></p>
                   
               </td> 
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
              
                   <p><span class="taskTitle"> {{ $date->format('D, M d, Y') }} {{ Carbon::createFromFormat('h:i A', $day['end_time'])->format('g:i a') }}</span></p>
                   
               </td> 
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
              
                   <p><span class="taskTitle"> Time Entry</span></p>
                   
               </td> 
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5 @if($loop->odd) bg-gray-200 @endif">
              
                   <p><span class="taskTitle"> Billable</span></p>
                   
               </td>
                   
                </tr>
                @endif
                @endforeach
                @endforeach 
            </tbody>
        </table>
    </div>
    
   
    @push('modals')
        @livewire('activites-modal')
        @livewire('time-modal')
        @livewire('accounts.activities.edit-time-modal')
    @endpush
</div>

@push('style')
<style>
    .bg-gray-200 {
    background-color: #edf2f7; /* Set your desired background color */
}

    .activityTimeShow::-webkit-scrollbar {
        width: 10px;
    }

    .activityTimeShow::-webkit-scrollbar-button {
        background: #ccc
    }

    .activityTimeShow::-webkit-scrollbar-track-piece {
        background: #888
    }

    .activityTimeShow::-webkit-scrollbar-thumb {
        background: #eee
    }
    .taskTitle {
    
    font-weight: 600;
    font-size: 11px;
    color: gray;
    margin-top: 10px;
}
.container {
    margin-bottom: 5px;
      display: flex;
      justify-content: center;
    }
    
    .toggle-button {
      margin: 0 10px;
      padding: 10px 28px;
      border-radius: 18px;
    }
    .stripe{
    background: #e5e5e5;
    padding: 7px;
    border-radius: 26px;
    }
    .white{
        background: white;
    }
    .loading-overlay {
        position: absolute;
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
