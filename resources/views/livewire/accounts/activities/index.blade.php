<?php

use App\Models\Account;
use App\Models\User;

$account_user = DB::table('account_user')
    ->select('account_id')
    ->where('user_id', auth()->user()->id)
    ->get();

$account_id = $account_user[0]->account_id;

$account = Account::where('id', $account_id)
    ->select('name')
    ->get();
?>
<div>
    <x-page.title svg="svgs.computer">
        Activities
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

        @role(['owner', 'manager'])
        <div class="mt-4  sm:mt-0 ">
            <div class="ml-2">
                <x-inputs.select-without-label2 wire:model="user_id" class="w-48" name="user_id">
                    <?php //  <option value="{{ auth()->id() }}" selected > {{ auth()->user()->firstname }}  {{ auth()->user()->lastname }}</option>
                    ?>

                    @foreach ($login as $log)
                    <option value="{{ $log->id }}">
                        {{ $log->full_name }}
                    </option>
                    @endforeach
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
            <button wire:click="$emit('activityCreate')" type="button" class="mt-4 mb-4 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-1 pr-3 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
                <x-svgs.plus class="w-5 h-5 mr-1" />
                Add time
            </button>
        </div>

        @endrole

    </div>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full xl:w-1/1">
            <div class="bg-white rounded-md border p-6 mx-4 mb-8">
                <div class="w-full xl:w-1/2">
                    <div class="w-full xl:w-1/2 flex flex-wrap">
                        <div class="w-full xl:w-1/2">
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

                        <div class="w-full xl:w-1/2">
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
                    </div>

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
        ++$countActivity;
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif
        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '10';
        }))
        @php
        ++$countActivity;
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif

        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '20';
        }))
        @php
        ++$countActivity;
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif

        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '30';
        }))
        @php
        ++$countActivity;
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif
        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '40';
        }))
        @php
        ++$countActivity;
        @endphp
        <x-activities.card :activity="$activity" :countActivity="$countActivity" />
        @else
        <x-activities.empty />
        @endif
        @if ($activity = $activities->first(function ($activity, $key) {
        return $activity->start_datetime->format('i') == '50';
        }))
        @php
        ++$countActivity;
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
                <ul>
                    <ol>1. Each team member needs to open their invite email and click the accept link.</li>
                        <ol>2. Next they must download the <a class="text-blue-600" href="https://neostaff.app/download">NeoStaff App.</a></li>
                            <ol>3. Finally they have to install the app and use it to track time to a project.</li>
                </ul>
            </div>
            <h1 class="py-4"><b>Your Organizations</b></h1>
            <h1 class="pb-2"><b><?php echo $account[0]->name . ' team members'; ?> </b></h1>
            <h4 class="text-gray-400 py-4"><i class="fas fa-info-circle"></i> Time can also be added manually on the
                timesheets page.</h4>
        </div>
    </div>

    <style>
        .modal-contenido {
            background-color: white;
            border-radius: 8px;
            width: 650px;
            height: 400px;
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
