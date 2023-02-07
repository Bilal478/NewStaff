<x-modals.small x-on:open-delete-image-activity-modal.window="open = true"
    x-on:close-delete-image-activity-modal.window="open = false" class="text-center">
    {{-- @if ($message != null)
    <div role="alert">

        <div class="border border-t-0 border-red-400  rounded-b bg-red-100 px-4 py-3 text-red-700" style="color: red">
            <p>{{ $message }}</p>
        </div>
    </div>
    @endif --}}
    <h3>Do you want delete the image?</h3>
    <div class="flex items-center flex-wrap -mx-2 pb-8">
        <div class="w-full mt-6 ">

            <article class="bg-white mx-4 mb-8 ">
                <div class="flex items-center">
                    <input wire:click="screenshotSelect1()" checked id="{{$activity_id}}" type="checkbox" value="" class="mr-3" >
                     
                <div class="relative group rounded-t-md overflow-hidden bg-black rounded-md border shadow-sm hover:shadow-md article">
                    <div
                        class="activity-img flex items-center justify-center absolute inset-0 z-10 opacity-0 transition duration-500 ease-linear group-hover:opacity-100">
                    
                    
                    </div>
                
                    <div
                        class="overflow-hidden object-cover rounded-t-md transition duration-300 transform ease-linear group-hover:scale-110 group-hover:opacity-60">
                    
                                <img style="" src="{{ $activity1 }}" alt="">
                        
                    
                    </div>

                </div>
                </div>
            </article>

            @if($activity2 != '')
            <article class="bg-white mx-4 mb-8 ">
            <div class="flex items-center">
                    <input wire:click="screenshotSelect2()" checked id="c{{$activity_id}}" type="checkbox" value="" class="mr-3" >
                <div class="relative group rounded-t-md overflow-hidden bg-black rounded-md border shadow-sm hover:shadow-md article">
                    <div
                        class="activity-img flex items-center justify-center absolute inset-0 z-10 opacity-0 transition duration-500 ease-linear group-hover:opacity-100">
                    
                    
                    </div>
                
                    <div
                        class="overflow-hidden object-cover rounded-t-md transition duration-300 transform ease-linear group-hover:scale-110 group-hover:opacity-60">
                    
                                <img style="" src="{{ $activity2 }}" alt="">
                        
                    
                    </div>

                </div>
            </div>
            </article>
            @endif
            @if($firstScreenshotSelected == 1 || ($secondScreenshotSelected == 1 && $numberOfScreenshots == 2))
            <button type="button" wire:click.prevent="deleteImageActivity()"
                class="mr-2 h-10 px-5  text-white transition-colors duration-150 bg-blue-600 rounded-lg focus:shadow-outline hover:bg-blue-500">Yes</button>
            @endif
                <button type="button"  wire:click.prevent="closeModal()"
                class="h-10 px-5 text-white transition-colors duration-150 bg-red-600 rounded-lg focus:shadow-outline hover:bg-red-500">No</button>
        </div>
    </div>

</x-modals.small>

@push('scripts')
    <script></script>
@endpush
