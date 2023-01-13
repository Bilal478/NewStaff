<x-modals.small x-on:open-delete-image-activity-modal.window="open = true"
    x-on:close-delete-image-activity-modal.window="open = false">
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
            <button type="button" wire:click.prevent="deleteImageActivity()"
                class="h-10 px-5  text-white transition-colors duration-150 bg-blue-600 rounded-lg focus:shadow-outline hover:bg-blue-500">Yes</button>
            <button type="button"  wire:click.prevent="closeModal()"
                class="h-10 px-5 text-white transition-colors duration-150 bg-red-600 rounded-lg focus:shadow-outline hover:bg-red-500">No</button>
        </div>
    </div>

</x-modals.small>

@push('scripts')
    <script></script>
@endpush
