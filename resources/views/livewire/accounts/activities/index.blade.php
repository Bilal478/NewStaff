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
?>
<div>
    <x-page.title svg="svgs.computer">
        Activities
        <button type="button" class="ml-20 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-3 pr-3 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
            <a href="#miModal"> Track Time</a>
         </button>
    </x-page.title>
    
    <div></div>

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

                    {{-- @foreach ($login as $log)
                    <option value="{{ $log->id }}">
                        {{ $log->full_name }}
                    </option>
                    @endforeach --}}
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->full_name }}
                        {{-- @if ($loop->iteration == 5)
                            @break
                        @endif --}}
                    </option>
                    @endforeach

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

<!-- activities -->
    <div class="mx-4">
    <div class="md:flex bg-white rounded-md border mx-4 mb-8">
        <div class="  p-6">
            <h4 class="text-sm  xl:tracking-widest uppercase mb-2">
                Time
            </h3>
            <span class="text-lg text-gray-800">
                {{ gmdate('H:i', $timeToday) }}
            </span>
            <br>
            <span class="text-sm text-gray-800">
                TOTAL WORKED
            </span>
        </div>
        <div class="  p-6">
            <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-2">
                 
            </h3>
            <div class="flex items-center text-sm {{ $totalPreviuosTimeState=='more'? 'text-red-500' :'text-green-500'}}">
                @if($totalPreviuosTimeState=='more')
                <img style="margin-top:-2px;margin-right: 10px;" src="https://d2elkgkdx2cp5d.cloudfront.net/assets/global/arrow_red-7d7d05038fc89ddb147974ea866c4c303ba2bfccc049b6bf073d4709f0d026bb.svg">
                @else
                <img style="margin-top: -2px;margin-right: 10px;"  src="https://d2elkgkdx2cp5d.cloudfront.net/assets/global/arrow_green-bb4267018493d26d5ef23d41f52f674046a789343cd449b2dace465966c00883.svg">
                @endif
                <span class="text-lg">
                    {{ gmdate('H:i', $totalPreviuosTime) }}
                </span>
            </div>


            <span class="text-sm text-gray-800">
                TO PREV DAY
            </span>
        </div>
        <div class="flex flex-row">
            <div class="left_border p-6">
                <h4 class="text-sm  xl:tracking-widest uppercase mb-2">
                    Today Activity
                </h3>
                @if ($todayActivityPer >= 51)
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
                @endif
                <br>
                <span class="text-sm text-gray-800">
                    Average
                </span>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="left_border p-6">
                <h4 class="text-sm  xl:tracking-widest uppercase mb-2">
                    THIS WEEK TOTAL HOURS
                </h3>
                <span class="text-lg text-gray-800">
                    {{ $WeeklyHours }}
                </span>
                <br>
                <span class="text-sm text-gray-800">
                    TOTAL WORKED
                </span>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="left_border p-6">
                <h4 class="text-sm  xl:tracking-widest uppercase mb-2">
                    Weekly Activity
                </h3>
                @if ($weekActivityPer >= 51)
                <span class="text-lg text-green-500">
                    {{$weekActivityPer}}%
                </span>
                @endif

                @if ($weekActivityPer >= 21 and $weekActivityPer <= 50)
                <span class="text-lg golden">
                    {{$weekActivityPer}}%
                </span>
                @endif

                @if ($weekActivityPer <= 20)
                <span class="text-lg text-red-500">
                    {{$weekActivityPer}}%
                </span>
                @endif
                <br>
                <span class="text-sm text-gray-800">
                    Average
                </span>
            </div>
        </div>
    </div>
    </div>





    {{-- {{ date('H:i', strtotime($timeToday) - strtotime($timeYesterday)) }}
    {{ strtotime($timeToday) - strtotime('TODAY') }}
    {{ gmdate("H:i:s", 12360 ) }} --}}

    @php
    $countActivity = 0;
    @endphp
    @if ($activitiesGroup->count())
    {{-- @dump($activitiesGroup) --}}
    @foreach ($activitiesGroup as $time => $activities)
    <div class="text-gray-500 text-xs py-4 activities">
        {{ $time }}
    </div>
    <div class="flex flex-wrap -mx-4 activities">
        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '00';
        }))
        @php
        if($activities->first()->screenshots->count() == 2){
            $countActivity = $countActivity+2;
        }
        else{
            $countActivity = $countActivity+1; 
        }
        
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif
        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '10';
        }))
        @php
        if($activities->first()->screenshots->count() == 2 && $countActivity !=0){
            $countActivity = $countActivity+2;
        }
        else{
            $countActivity = $countActivity+1; 
        }
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif

        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '20';
        }))
        @php
        if($activities->first()->screenshots->count() == 2 && $countActivity !=0){
            $countActivity = $countActivity+2;
        }
        else{
            $countActivity = $countActivity+1; 
        }
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif

        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '30';
        }))
        @php
        if($activities->first()->screenshots->count() == 2 && $countActivity !=0){
            $countActivity = $countActivity+2;
        }
        else{
            $countActivity = $countActivity+1; 
        }
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif
        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '40';
        }))
        @php
        if($activities->first()->screenshots->count() == 2 && $countActivity !=0){
            $countActivity = $countActivity+2;
        }
        else{
            $countActivity = $countActivity+1; 
        }
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif
        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '50';
        }))
        @php
        if($activities->first()->screenshots->count() == 2 && $countActivity !=0){
            $countActivity = $countActivity+2;
        }
        else{
            $countActivity = $countActivity+1; 
        }
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif
    </div>
    
    @endforeach
    @else
    <x-states.empty-data2 />

    <div id="miModal" class="modal">
        <div class="modal-contenido">
            <a href="#">X</a>
            <div class="py-4 pl-4">
                <h4 class="text-gray-600">
                    <i class="fa fa-exclamation-circle"></i> Your team has not tracked any time.
                </h4>
            </div>

            <h1 class="py-4"><b>Getting Started</b></h1>
            <div class="list">
                <ol >
                    <li>1. Each team member needs to open their invite email and click the accept link.</li>
                    <li>
                            <li>2. You use Windows, you can download the time tracker desktop app from here:
                                &nbsp;&nbsp;&nbsp;&nbsp;<a href="//media.neostaff.app/downloads/windows" style="color: blue">https://media.neostaff.app/downloads/windows</a></li><br>
    
            
                               <li>&nbsp;&nbsp;&nbsp;&nbsp;If you use MAC, you can download the time tracker desktop app from here:
                                &nbsp;&nbsp;&nbsp;&nbsp;<a href="https://media.neostaff.app/downloads/mac" style="color: blue">https://media.neostaff.app/downloads/mac</a></li>
                            
                    </li>
                    <li>3. Finally they have to install the app and use it to track time to a project.</li>
                </ol>
            </div>
            <h1 class="py-4"><b>Your Organizations</b></h1>
            <h1 class="pb-2"><b><?php if (isset($account[0])) {
                echo $account[0]->name . ' team members';
            }  ?> </b></h1>
            <h4 class="text-gray-400 py-4"><i class="fas fa-info-circle"></i> Time can also be added manually on the
                timesheets page.</h4>
        </div>
    </div>

    <style>
        .modal-contenido {
            background-color: white;
            border-radius: 8px;
            width: 650px;
            height: 480px;
            padding: 10px 20px;
            margin: 0 auto;
            position: relative;
        }

        .list {
            margin-left: 30px;
        }

        .modal {
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            opacity: 0;
            pointer-events: none;
            transition: all 1s;
        }

        #miModal:target {
            opacity: 1;
            pointer-events: auto;
        }
    </style>


    @endif



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
{{-- <script>
     $(".btnMostrar").click(function(){
         alert("AFDS");
        $('activities').hide();
    }); 

</script> --}}

<script>




//        var listActivityImg = document.getElementsByClassName("activity-img");

// listActivityImg.forEach(item => {
//     item.classList.remove('group-hover:opacity-100');
// });

</script>
