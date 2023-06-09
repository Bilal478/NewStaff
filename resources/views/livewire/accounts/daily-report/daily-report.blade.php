<?php

use App\Models\Account;
use App\Models\User;

$account_user = DB::table('account_user')
    ->where('user_id', auth()->user()->id)
    ->first();

$account_id = $account_user->account_id;

$account = Account::where('id', $account_id)
    ->select('name')
    ->get();
    // dd($data);
?>
<div>
    <div class="header-container">
        <x-page.title svg="svgs.report">
            Daily Report
        </x-page.title>
    
        <div class="toggle-container">
            <a href="dailyreport"><button id="dailyReportButton" class="toggle-button active" onclick="toggleReport('daily')">Daily Report</button></a>
            <button id="weeklyReportButton" class="toggle-button" onclick="toggleReport('weekly')">Weekly Report</button>
        </div>
    
    </div>

    <div class="flex items-center flex-wrap pb-8 flex-col sm:flex-row">
        <div>
            <div class=" flex items-center justify-center mx-auto">
                <button wire:click.prevent="subDay()" type="button" class="btnMostrar h-10 appearance-none bg-white block px-3 py-2 border border-gray-300 rounded-md text-gray-600 focus:outline-none hover:border-blue-500 hover:text-blue-600 transition duration-150 ease-in-out text-sm leading-5 mr-2">
                    <x-svgs.arrow-left class="h-4 w-4" />
                </button>
                <button wire:click.prevent="addDay()" type="button" class="btnMostrar h-10 appearance-none bg-white block px-3 py-2 border border-gray-300 rounded-md text-gray-600 focus:outline-none hover:border-blue-500 hover:text-blue-600 transition duration-150 ease-in-out text-sm leading-5 mr-2">
                    <x-svgs.arrow-right class="h-4 w-4" />
                </button>
                <div class="flex-1">
                    <x-inputs.datepicker-without-label wire:model="date" class="w-full" name="date" type="text" :clear-button="false" />
                </div>
            </div>
        </div>

        @if($account_user->allow_edit_time == 1)
        <div class="mt-4  sm:mt-0 ">
            <div class="ml-2">
                <x-inputs.select-without-label2 wire:model="user_id" class="w-48" name="user_id">
                    <?php //  <option value="{{ auth()->id() }}" selected > {{ auth()->user()->firstname }}  {{ auth()->user()->lastname }}</option>
                    ?>
                    @if ($users->count())
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->full_name }}
                        
                    </option>
                    @endforeach 
                    @else
                    @foreach ($login as $log)
                    <option value="{{ $log->id }}">
                        {{ $log->full_name }}
                    </option>
                    @endforeach
                    @endif

                    </x-inputs.select-without-label>
            </div>
        </div>
        <div class="new-activity-button pb-0 ml-4">
            <button wire:click="$emit('activityCreate')" type="button" class="mt-4 mb-4 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-3 pr-3 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
                <x-svgs.plus class="w-5 h-5 mr-1" />
                Add time
            </button>
        </div>

        @endif

    </div>
    <div class="w-full overflow-x-auto rounded-md border">
        <table class="w-full bg-white">
            <tbody>
                <tr class="text-left uppercase text-xs text-gray-700 font-medium border-b-2">
                    <th class="min-w-20 sticky left-0 top-auto bg-white z-10 px-4 py-4">
                        Projects
                        <div class="border-r-2 bg-red-500 absolute right-0 inset-y-0"></div>
                    </th>
                    <th class="px-4 py-4">
                        {{-- @if ($todayActivityPer >= 51)
                <span class="text-lg text-green-500">
                    {{$todayActivityPer}}%
                </span>
                @endif

                @if ($todayActivityPer >= 21 and $todayActivityPer <= 50)
                <span class="text-lg golden">
                {{$todayActivityPer}}%
                </span>
                @endif

                @if ($todayActivityPer<= 20)
                <span class="text-lg text-red-500">
                {{$todayActivityPer}}%
                </span>
                @endif --}}
                Activity
                    </th>
                    <th class="px-4 py-4">
                        {{-- {{ gmdate('H:i', $timeToday) }} --}}
                        Duration
                    </th>
                    <th class="px-4 py-4">
                        Time
                    </th>
                </tr>
                @foreach ($data as $userName => $activity)
                <tr class="text-sm text-gray-600 hover:bg-gray-50 {{ $loop->last ? '' : 'border-b' }}">
                    <td class="min-w-20 sticky left-0 top-auto bg-white z-10 px-4 py-5">
                        {{ $userName }}
                        <p><span class="taskTitle">{{$activity['project_title']}}</span></p>
                        <div class="border-r-2 bg-red-500 absolute right-0 inset-y-0"></div>
                    </td>

                    @foreach ($activity['days'] as $day)
                    <td class="min-w-36 px-4 py-5">
                        {{ $day['productivity'] }}%
                        <br>
                        {{-- @if ($show == $day['seconds'].$day['productivity'] )
                        
                        @endif --}}
                    </td>
                    <td class="min-w-36 px-4 py-5">
                        {{ $day['seconds'] }} 
                    </td>
                    @endforeach

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@push('modals')
@livewire('accounts.tasks.tasks-form')
@endpush

@push('modals')
@livewire('accounts.screenshots.screenshots-show-carousel')
@endpush
@push('modals')
@livewire('track-modal')
@endpush
@push('modals')
@livewire('accounts.activities.delete-activity-modal')
@endpush
@push('modals')
@livewire('accounts.activities.delete-image-activity-modal')
@endpush
</div>
<style>
.new-activity-button {
    display: flex !important;
    justify-content: flex-end !important;
}
svg.w-5.h-5 {
    /* display: none !important; */
}
.golden{
color: #f8c58d !important;
}
@media screen and (min-width: 768px) and (max-width: 1440px) {
        .sm\:flex.new-activity-button.pb-0 {
            margin-left: 10rem !important;
        }
        span.select2.select2-container.select2-container--default{
        width: 90% !important;
    }
    }
</style>
<script>
    function toggleReport(reportType) {
        if (reportType === 'daily') {
            document.getElementById('dailyReportButton').classList.add('active');
            document.getElementById('weeklyReportButton').classList.remove('active');
            // TODO: Show the daily report content and hide the weekly report content
        } else if (reportType === 'weekly') {
            document.getElementById('dailyReportButton').classList.remove('active');
            document.getElementById('weeklyReportButton').classList.add('active');
            // TODO: Show the weekly report content and hide the daily report content
        }
    }
</script>

<style>
    .toggle-container {
        display: flex;
        margin-left: 50px;
    }

    .toggle-button {
        padding: 5px 10px;
        background-color: #ddd;
        border: none;
        outline: none;
        cursor: pointer;
    }

    .toggle-button.active {
        background-color: #007bff;
        color: #fff;
    }
    .header-container {
        display: flex;
        align-items: flex-start;
    }
</style>