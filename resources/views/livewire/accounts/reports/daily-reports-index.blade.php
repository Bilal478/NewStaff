<?php 
$user_login = auth()->id();
?>
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
                    <x-inputs.datepicker-week-without-label wire:model="week" faux-date="date" class="w-56 sm:w-60"
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
        
            @foreach ($dates as $date)
                <?php $inner_count = 0; ?>
            <tr class="text-left uppercase text-xs text-gray-700 font-medium border-b-2">
            <th class="px-4 py-4">
                {{ $date->format('M d, Y') }} 
            </th>
            </tr>
            
            @foreach ($users as $day)
               
                   {{-- $data=$day['project_title'];
                   $data1=$day['task_title'];
                   $data2=$day['duration'];
                   $data3=$day['start_time'];
                   $data4=$day['end_time']; --}}
                  
                @if($date->format('Y-m-d') == $day['date'])
                    @if($inner_count==0)
                   
                    <tr class="text-left font-extrabold uppercase text-xs text-gray-700 font-medium border-b-2">
                    
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Project
                    </td>
                   
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Duration
                    </td>
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Activity
                    </td>
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Time
                    </td>
                </tr>
                @endif
                <?php $inner_count = 1; ?>
                <tr class="text-left uppercase text-xs text-gray-700 font-medium border-b-2">
                   
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                    {{ $day['project_title'] }}
                        <p><span class="taskTitle"> {{ $day['task_title'] }}</span></p>
                        
                    </td>
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                   
                   <p><span class="taskTitle"> {{ $day['duration'] }}</span></p>
                   
               </td>
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
              
                   <p><span class="taskTitle"> {{ $day['productivity'] }}</span></p>
                   
               </td>
               
               <td style="cursor: pointer;" wire:click="$emit('showEditTimeModal', {{ json_encode($day) }})" class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
              
                   <p><span class="taskTitle"> {{ $day['start_time'] }} - {{ $day['end_time'] }}</span></p>
                   
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
</style>
@endpush

