<x-modals.small x-on:open-delete-activity-modal.window="open = true" x-on:close-delete-activity-modal.window="open = false">
    @if ($message != null)
    <div role="alert">

        <div class="border border-t-0 border-red-400  rounded-b bg-red-100 px-4 py-3 text-red-700" style="color: red">
            <p>{{ $message }}</p>
        </div>
    </div>
    @endif
   <h4>testing</h4>
</x-modals.small>

@push('scripts')
    <script>
       

    </script>
@endpush
