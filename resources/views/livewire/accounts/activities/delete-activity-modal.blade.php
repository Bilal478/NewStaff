<x-modals.small x-on:open-delete-activity-modal.window="open = true"
    x-on:close-delete-activity-modal.window="open = false">
    <h3>Do you want delete the activity?</h3>
    <div class="flex items-center flex-wrap -mx-2 pb-8">
        <div class="w-full mt-6 ">
            <button type="button" wire:click.prevent="deleteActivity()"
                class="h-10 px-5  text-white transition-colors duration-150 bg-blue-600 rounded-lg focus:shadow-outline hover:bg-blue-500">Yes</button>
            <button type="button"  wire:click.prevent="closeModal()"
                class="h-10 px-5 text-white transition-colors duration-150 bg-red-600 rounded-lg focus:shadow-outline hover:bg-red-500">No</button>
        </div>
    </div>

</x-modals.small>

@push('scripts')
    <script></script>
@endpush
