<?php 
$user_login = auth()->id();
$totalTimeInSeconds = 0;

foreach ($users as $userName => $activity) {
    $time = $activity['total'];
    $timeParts = explode(':', $time);
    $hours = (int) $timeParts[0];
    $minutes = (int) $timeParts[1];
    $seconds = (int) $timeParts[2];
    $totalTimeInSeconds += $hours * 3600 + $minutes * 60 + $seconds;
}

$hours = floor($totalTimeInSeconds / 3600);
$minutes = floor(($totalTimeInSeconds % 3600) / 60);
$seconds = $totalTimeInSeconds % 60;

$totalTimeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
?>
<div>
    <x-page.title svg="svgs.report">
        Timesheets
    </x-page.title>
    <div class="container">
        <span class="stripe">
        <a class="toggle-button white" href="{{ route('accounts.reports') }}">Weekly</a>
        <a class="toggle-button" href="{{ route('accounts.dailyreports') }}">Daily</a>
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
                    <x-inputs.datepicker-week-without-label wire:model="week" faux-date="date" class="w-72"
                        name="date" type="text" :clear-button="false" />
                </div>
		
            </div>
        </div>
        @role(['owner', 'manager'])
                <div class="mt-4  sm:mt-0 ">
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
		@endrole
        {{-- @if ($users->count()) --}}
        <button wire:click="download" type="button"
            class="w-full sm:w-auto mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
            <x-svgs.plus class="w-5 h-5 mr-1" />
            Download PDF
        </button>
        {{-- @endif --}}
        
    </div>
    <div style="float: right">
        <b>Total Hours = {{$totalTimeFormatted}}</b>
    </div>
    
    <div class="w-full overflow-x-auto rounded-md border">
        <table class="w-full bg-white">
        <div wire:loading>
            <!-- Show the loading animation -->
            <div class="loading-overlay">
            <div  class="loading-animation">
                <!-- Add your loading animation here -->
                
            </div>
            </div>
        </div>
            <tbody>
                <tr class="text-left uppercase text-xs text-gray-700 font-medium border-b-2">
                    <th class="min-w-52 sticky left-0 top-auto bg-white z-10 px-4 py-4">
                        Projects
                        <div class="border-r-2 bg-red-500 absolute right-0 inset-y-0"></div>
                    </th>
                    @foreach ($dates as $date)
                    <th class="px-4 py-4">
                        {{ $date->format('D M d, Y') }}
                    </th>
                    @endforeach
                    <th class="px-4 py-4">
                        Weekly Total
                    </th>
                </tr>
                @if ($users->count())
                @foreach ($users as $userName => $activity)
                <tr class="text-sm text-gray-600 hover:bg-gray-50 {{ $loop->last ? '' : 'border-b' }}">
                    <td class="min-w-52 sticky left-0 top-auto bg-white z-10 px-4 py-5">
                        {{ $userName }}
                        <p><span class="taskTitle">{{$activity['task_title']}}</span></p>
                        <div class="border-r-2 bg-red-500 absolute right-0 inset-y-0"></div>
                    </td>

                    @foreach ($activity['days'] as $day)
                    <td class="min-w-36 px-4 py-5">
                        <a href="#" wire:click="$emit('activityModal','{{ $day['date'] }}','{{ $day['user_id'] }}','{{ $day['account_id'] }}','{{ $day['project_id'] }}','{{ $day['task_id'] }}')">{{ $day['seconds'] }}</a>
                        <x-svgs.computer class="w-4 h-4 text-blue-500 mr-1" />
                        {{ $day['productivity'] }}%
                        <br>
                        @if ($show == $day['seconds'].$day['productivity'] )
                        {{-- @if (\App\Models\Activity::where('date',date('Y-m-d',
                        strtotime($day['date'])))->where('user_id', $day['user_id'])->get()->count() > 0)

                        <div class="activityTimeShow"
                            style="overflow-y:scroll;    width: 196px; overflow-x:hidden; height:70px; "
                            id="displySection{{ $day['seconds'].$day['productivity'] }}">
                            @foreach (\App\Models\Activity::where('date',date('Y-m-d',
                            strtotime($day['date'])))->where('user_id', $day['user_id'])->get() as $item)
                            {{ "Activity $item->id" }} - {{ $item->seconds }}
                            <button type="button"
                                wire:click="$emit('timeEdit','{{ $item->id }}','{{ $item->seconds }}')">
                                <span class="text-xs text-gray-500"><svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg></span>
                            </button>
                            <button wire:click.stop="$emit('deleteActivity','{{ $item->id }}')" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button><br>
                            @endforeach
                        </div>
                        @endif --}}
                        @endif
                    </td>
                    @endforeach
                    <td class="min-w-36 px-4 py-5">
                        {{ $activity['total'] }} 
                        <x-svgs.computer class="w-4 h-4 text-blue-500 mr-1" />
                        {{ $activity['total_productivity'] }}%
                    </td>
                </tr>
                @endforeach
                @else
                <tr class="text-sm text-gray-600 hover:bg-gray-50">
                    <td class="min-w-52 sticky left-0 top-auto bg-white z-10 px-4 py-5">
                        No-todo
                        <p><span class="taskTitle">No-todo</span></p>
                        <div class="border-r-2 bg-red-500 absolute right-0 inset-y-0"></div>
                    </td>

                    @for ($i=0;$i<7;$i++)
                    <td class="min-w-36 px-4 py-5">
                        00:00:00
                        <x-svgs.computer class="w-4 h-4 text-blue-500 mr-1" />
                        0%
                        <br>
                    </td>
                    @endfor
                    <td class="min-w-36 px-4 py-5">
                        00:00:00 
                        <x-svgs.computer class="w-4 h-4 text-blue-500 mr-1" />
                        0%
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{-- @else
    <x-states.empty-data message="There are no records for this week." />
    @endif --}}
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
