@props(['activity', 'countActivity','allow_delete_screenshoot'])
@if (isset($countActivity))
@endif
@php 
$account_user = DB::table('account_user')
    ->where('user_id', auth()->user()->id)
    ->first();
$seconds = $activity['seconds'];
$minutes = floor($seconds / 60);
$remainingSeconds = $seconds % 60;
$formattedTime = sprintf('%d:%02d', $minutes, $remainingSeconds);
@endphp
<div class="w-full sm:w-1/2 md:w-1/3 xl:w-1/6">
    <article class="bg-white mx-4 mb-8 rounded-md border shadow-sm hover:shadow-md article">
        <div class="relative group rounded-t-md overflow-hidden bg-black">
            <div class="activity-img flex items-center justify-center absolute inset-0 z-10 opacity-0 transition duration-500 ease-linear group-hover:opacity-100">
                {{-- @dd($loop->iteration) --}}
                @if(!empty($activity['screenshots']))
                    @if ($activity['screenshots'][0]->path != '00/1234567890.png')
                        <button
                            wire:click.stop="$emit('screenshotsShow', {{ $activity['user_id'] }}, {{ $activity['account_id'] }}, '{{ $activity['date'] }}')"
                            onclick="currentSlide({{ $countActivity }})" type="button"
                            class="number_of_screeshot bg-white font-montserrat font-semibold px-3 py-1 rounded-sm text-gray-700 text-xs">
                            View screens
                        </button>
                    @endif
                @endif
            </div>
            {{-- @dump($activity) --}}
            <div
                class="overflow-hidden object-cover h-28 rounded-t-md transition duration-300 transform ease-linear group-hover:scale-110 group-hover:opacity-60">
                @if ($activity['screenshots'])
                    @if (count($activity['screenshots']) > 0)
                    <img src="{{ $activity['screenshots'][0]->fullPath() }}" alt="">
                    @endif
                @endif
                @if($activity['screenshots'] != NULL)
                    @if ($activity['screenshots'][0]->path === '00/1234567890.png')
                        <img class="img_placeholder" src="2.png">
                    @endif
                @else
                @if($activity['is_manual_time']==0)
                <img class="img_placeholder" src="image_short.png">
                @else
                <img class="img_placeholder" src="2.png">
                @endif
                @endif
            </div>

        </div>

        <div class="relative pt-6 pb-4">
            <div class="number_of_screeshot absolute w-full flex justify-center top-1 -mt-5 z-10">
            @if($activity['screenshots'] != NULL)
                @if ($activity['screenshots'][0]->path != '00/1234567890.png')    
                <span style="margin-top: -35px;" class=" font_weight bg-white flex items-center justify-center h-6 rounded-full shadow-md text-blue-600 text-center text-xs w-24">{{count($activity['screenshots'])}} {{ count($activity['screenshots']) == 1 ? 'screen' : 'screens'}}</span>
                @endif
            @else
            <span style="margin-top: -35px;" class=" font_weight bg-white flex items-center justify-center h-6 rounded-full shadow-md text-blue-600 text-center text-xs w-24">0 screen</span>
            @endif   
          
                <!-- <button wire:click.stop="$emit('screenshotsShow', {{$activity['id']}})" class="bg-white flex items-center justify-center h-6 rounded-full shadow-md text-blue-600 text-center text-xs w-24">
                    {{ count($activity['screenshots']) }} {{ count($activity['screenshots']) == 1 ? 'screen' : 'screens'}}
                </button> -->
            </div>


            <div class="px-4">
                <div class="flex mb-4 w-full">
                    <span class="text-xs text-gray-500">{{ $activity['start_time']->format('g:i: a') }} -
                        {{ $activity['end_time']->format('g:i: a') }}</span>
                </div>
                <br>
                <div class="flex mb-4 w-3/5" style="
                margin-top: -36px; justify-content: center;">
                    
                        <!-- <button type="button"
                            wire:click="$emit('trackEdit','{{ $activity['id'] }}','{{ $activity['date'] }}','{{ $activity['start_time']->format('H:i:s') }}','{{ $activity['end_time']->format('H:i:s') }}')">
                            <span class="text-xs text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </span>
                        </button> -->
                        @if($account_user->allow_edit_time == 1)
                        <button type="button" class="mr-1" wire:click="$emit('deleteActivityShow',{{ $activity['id'] }})">
                            <span class="text-xs text-red-500">
                            <svg class="svg-icon" style="font-size: 1rem;" viewBox="0 0 20 20">
							<path d="M17.114,3.923h-4.589V2.427c0-0.252-0.207-0.459-0.46-0.459H7.935c-0.252,0-0.459,0.207-0.459,0.459v1.496h-4.59c-0.252,0-0.459,0.205-0.459,0.459c0,0.252,0.207,0.459,0.459,0.459h1.51v12.732c0,0.252,0.207,0.459,0.459,0.459h10.29c0.254,0,0.459-0.207,0.459-0.459V4.841h1.511c0.252,0,0.459-0.207,0.459-0.459C17.573,4.127,17.366,3.923,17.114,3.923M8.394,2.886h3.214v0.918H8.394V2.886z M14.686,17.114H5.314V4.841h9.372V17.114z M12.525,7.306v7.344c0,0.252-0.207,0.459-0.46,0.459s-0.458-0.207-0.458-0.459V7.306c0-0.254,0.205-0.459,0.458-0.459S12.525,7.051,12.525,7.306M8.394,7.306v7.344c0,0.252-0.207,0.459-0.459,0.459s-0.459-0.207-0.459-0.459V7.306c0-0.254,0.207-0.459,0.459-0.459S8.394,7.051,8.394,7.306"></path>
						</svg>
                            </span>
                        </button>
                        @endif
                        @if($account_user->allow_delete_screenshot == 1)
                        @if(count($activity['screenshots']) != 0)
                        <button type="button" wire:click="$emit('deleteImageActivityShow',{{ $activity['id'] }},{{ $activity['screenshots'] }})">
                            <span class="text-xs text-blue-500">
                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" style="color: rgb(226, 111, 3)"
                                    width="12.000000pt" height="12.000000pt" viewBox="0 0 20.000000 20.000000"
                                    preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,20.000000) scale(0.026667,-0.025316)" fill="#000000"
                                        stroke="none">
                                        <path
                                            d="M131 730 c-46 -11 -100 -67 -111 -116 -12 -51 -12 -417 0 -468 11
                               -51 65 -105 116 -116 51 -12 417 -12 468 0 51 11 105 65 116 116 12 51 12 417
                               0 468 -11 51 -65 105 -116 116 -47 11 -426 11 -473 0z m494 -95 c24 -23 25
                               -29 25 -160 0 -74 -2 -135 -4 -135 -2 0 -23 24 -46 53 -48 59 -64 71 -84 60
                               -8 -4 -44 -45 -80 -90 -37 -46 -70 -83 -74 -83 -4 0 -38 23 -76 50 -38 28 -72
                               50 -76 50 -4 0 -32 -18 -63 -41 l-57 -40 0 156 c0 152 1 156 25 180 l24 25
                               231 0 231 0 24 -25z m-31 -354 c64 -80 72 -116 31 -156 l-24 -25 -231 0 -231
                               0 -24 25 c-43 42 -34 73 35 123 l60 44 76 -56 c42 -31 80 -56 83 -56 13 0 38
                               26 96 98 31 39 61 72 65 72 4 0 33 -31 64 -69z" />
                                    </g>
                                </svg>


                            </span>
                        </button>
                        @endif
                    @endif
                </div>
                {{-- </form> --}}

                <div class="relative mb-4">
                    <div class="w-full h-1 bg-gray-200 rounded-sm"></div>
                    @if ($activity['total_activity_percentage'] >= 51)
                        <div class="h-1 rounded-sm absolute top-0 left-0 bg-green-500"
                            style="width: {{ $activity['total_activity_percentage'] }}%"></div>
                    @endif
                    @if ($activity['total_activity_percentage'] >= 21 and $activity['total_activity_percentage'] <= 50)
                        <div class="h-1 rounded-sm absolute top-0 left-00"
                            style="width: {{ $activity['total_activity_percentage'] }}%;background-color: #fdd54ac4;"></div>
                    @endif
                    @if ($activity['total_activity_percentage'] <= 20)
                        <div class="h-1 rounded-sm absolute top-0 left-0 bg-red-500"
                            style="width: {{ $activity['total_activity_percentage'] }}%"></div>
                    @endif

                    {{-- <div class="h-1 rounded-sm absolute top-0 left-0 {{ $activity->total_activity_percentage >= 50 ? 'bg-green-500' : 'bg-red-500' }}"
                        style="width: {{ $activity->total_activity_percentage }}%"></div> --}}
                </div>

                <div class="flex items-center justify-center space-x-2">
                    <div class="flex items-center">
                        <x-svgs.keyboard class="w-4 h-4 text-blue-500 mr-1" />
                        <span class="text-xs text-gray-500">
                            @if ($activity['is_manual_time']==1)
                            <x-svgs.minus class="w-4 h-4 text-black-500 mr-1" />
                            @else
                            {{ $activity['keyboard_count'] }}
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center">
                        <x-svgs.cursor class="w-4 h-4 text-blue-500 mr-1" />
                        <span class="text-xs text-gray-500">
                            @if ($activity['is_manual_time']==1)
                            <x-svgs.minus class="w-4 h-4 text-black-500 mr-1" />
                            @else
                            {{ $activity['mouse_count'] }}
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center">
                        <x-svgs.computer class="w-4 h-4 text-blue-500 mr-1" />
                        <span class="text-xs text-gray-500">
                            @if ($activity['is_manual_time']==1)
                            <x-svgs.minus class="w-4 h-4 text-black-500 mr-1" />
                            @else
                            {{ $activity['total_activity_percentage'] }}%
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex items-center justify-center space-x-2 mt-2">
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500">
                            @if ($activity['is_manual_time'] == 1)
                                <span class="flex items-center">
                                    <x-svgs.minus class="w-4 h-4 text-black-500 mr-1" /> % of {{$formattedTime}} min
                                </span>
                            @else
                               {{ $activity['total_activity_percentage'] }}% of {{$formattedTime}} min
                            @endif
                        </span>
                   </div>
                </div>
            </div>

        </div>
    </article>
</div>
<style>
        /* -----
SVG Icons - svgicons.sparkk.fr
----- */

.svg-icon {
  width: 1em;
  height: 1em;
}

.svg-icon path,
.svg-icon polygon,
.svg-icon rect {
  fill: red;
}

.svg-icon circle {
  stroke: #4691f6;
  stroke-width: 1;
}
    .font_weight{
        font-weight: 600 !important;
        cursor: default !important;
    }
</style>
