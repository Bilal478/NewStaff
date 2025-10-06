<x-modals.small 
    x-on:open-activities-edit-time-modal.window="open = true" 
    x-on:close-activities-edit-time-modal.window="open = false"
>
    <form wire:submit.prevent="update" autocomplete="off" class="space-y-6"> 
        <h5 class="font-montserrat font-semibold text-lg text-gray-700">
            Edit Time
        </h5>

        <div class="flex items-center justify-between mt-5">
            <div class="flex items-center">
                <div class="avatar">
                    <x-user.avatar />
                </div>
                <div class="fullname">
                    <span class="ml-3 block text-left font-montserrat text-md font-semibold text-gray-500 cursor-default">
                        {{ $firstName }} {{$lastName}}
                    </span>
                </div>
            </div>

            <div>
                <input 
                    type="text" 
                    id="datePicker" 
                    wire:model="date" 
                    class="w-full border rounded-md px-3 py-2 text-center focus:outline-none cursor-default"
                    readonly
                >
            </div>
        </div>

        <div class="flex items-center justify-between mt-5">
            {{-- Project --}}
            <div class="flex flex-col w-1/2 mr-3">
                <label class="text-gray-600 font-medium mb-1" style="font-size: small;">PROJECT</label>
                <div class="w-full border rounded-md px-3 py-2 cursor-default">
                    {{ $projectTitle ?? 'Select Project' }}
                </div>
            </div>

            {{-- Task --}}
            <div class="flex flex-col w-1/2">
                <label class="text-gray-600 font-medium mb-1" style="font-size: small;">TASK</label>
                <div class="w-full border rounded-md px-3 py-2 cursor-default">
                    {{ $taskTitle ?? 'Select Task' }}
                </div>
            </div>
        </div>

        {{-- Time span (read-only section) --}}
        <div class="flex flex-col mt-5">
            <label class="text-gray-600 font-medium mb-1" style="font-size: small;">TIME SPAN (MDT)*</label>

            <div class="flex items-center gap-3">
                {{-- Date --}}
                <input 
                    type="text" 
                    value="{{ $duration }}" 
                    class="border rounded-md text-center  py-2 w-1/3 focus:outline-none cursor-default mr-2"  
                    readonly
                >

                {{-- From --}}
                <span class="text-gray-600 mr-2">FROM</span>
                <input 
                    type="text" 
                    value="{{ $startTime }}"
                    class="border rounded-md  py-2 w-28 text-center focus:outline-none cursor-default mr-2"
                    readonly
                >

                {{-- To --}}
                <span class="text-gray-600 mr-2">TO</span>
                <input 
                    type="text" 
                    value="{{ $endTime }}"
                    class="border rounded-md  py-2 w-28 text-center focus:outline-none cursor-default"
                    readonly
                >
            </div>
        </div>

        {{-- Editable Time span --}}
        <div class="flex flex-col mt-5">
            <label class="text-gray-600 font-medium mb-1 text-sm">Set New Time</label>
            
            <!-- Date + From + To -->
            <div class="flex items-center gap-3 mb-3">
                <!-- Date -->
                <input 
        type="text"
        readonly
        class="mr-2 border rounded-md  py-2 w-1/3 text-center bg-gray-100 text-gray-600 focus:outline-none cursor-default"
        value="{{ $this->getDurationText() }}"
    >

                <!-- From -->
                <span class="mr-2 text-gray-600">FROM</span>
                <input 
                    type="time" 
                    wire:model="newStartTime"
                    class="mr-2 border rounded-md  py-2 w-28 text-center bg-gray-50 focus:ring focus:ring-blue-300"
                >

                <!-- To -->
                <span class="mr-2 text-gray-600">TO</span>
                <input 
                    type="time" 
                    wire:model="newEndTime"
                    class="border rounded-md  py-2 w-28 text-center bg-gray-50 focus:ring focus:ring-blue-300"
                >
            </div>
        </div>

        {{-- Submit button --}}
        <div class="flex justify-end mt-5">
            <x-buttons.blue-inline type="submit">
                Update Time
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
