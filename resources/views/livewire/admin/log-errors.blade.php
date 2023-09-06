@php
    // dd($logsData);
@endphp
<div>
    <div class="pb-12">
        <h1 class="font-montserrat text-xl font-semibold text-gray-700">
            Log Errors
        </h1>
    </div>
    <div class="w-full pb-4">
    <div class="uppercase text-xs text-gray-400 font-medium hidden md:flex items-center">
        <div class="w-40 px-3">
        Timestamp
        </div>
        <div class="px-3" style="width: 600px;">
        Error Message
        </div>
        <div class=" w-40 px-3">
        Status
        </div>
        <div class=" w-40 px-3">
        Created At
        </div>
        <div class=" w-40 px-3 text-right">
            Action
        </div>
    </div>

    <div class="text-center uppercase text-xs text-gray-400 font-medium px-3 block md:hidden">
        Tasks
    </div>
  </div>
  <div>
    @foreach ($logsData as $data)
    
    <div  class="w-full bg-white py-5 rounded-md border mb-3 cursor-pointer hover:shadow-md">
        <div class="hidden md:flex items-center text-sm">
            <div class=" w-40 px-3 text-xs text-gray-500">
                {{ $data->timestamp }}
            </div>
               
            <div class="px-3 text-xs text-gray-500 error-message-container" style="width: 600px;">
                <div class="truncate">{{ Str::limit($data->message,75) }}</div>
                {{-- <div class="hidden full-message">{{ $data->message }}</div> --}}
                {{-- <a href="#" class="show-more-link">Show More</a> --}}
            </div>
            
            <div class=" w-40 px-3 text-xs text-gray-500">
                {{ $data->status }}
            </div>
    
            <div class=" w-40 px-3 text-xs text-gray-500">
                {{ $data->created_at}}
            </div>

            <div class="w-40 px-3 flex justify-end">
                <x-dropdowns.context-menu>
                    <x-dropdowns.context-menu-item  name="Edit" wire:click.stop="$emit('logErrorsEdit', {{$data->id}})" svg="svgs.edit"/>
                    <x-dropdowns.context-menu-item  name="Remove" svg="svgs.x-circle"/>
                </x-dropdowns.context-menu>
            </div>
    
        </div>
    
    </div>
    @endforeach
    </div>
    <div class="pt-5">
        {{ $logsData->links('vendor.pagination.default') }}
    </div>
</div>
@push('modals')
    @livewire('admin.edit-log-errors');
@endpush
{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const showMoreLinks = document.querySelectorAll(".show-more-link");

        showMoreLinks.forEach(link => {
            link.addEventListener("click", function (event) {
                event.preventDefault();
                const parentDiv = this.closest(".error-message-container");
                const truncateDiv = parentDiv.querySelector(".truncate");
                const fullMessageDiv = parentDiv.querySelector(".full-message");

                truncateDiv.classList.toggle("hidden");
                fullMessageDiv.classList.toggle("hidden");

                if (truncateDiv.classList.contains("hidden")) {
                    this.innerText = "Show Less";
                } else {
                    this.innerText = "Show More";
                }
            });
        });
    });
</script> --}}




   


