<x-modals.small x-on:open-edit-log-errors.window="open = true" x-on:close-edit-log-errors.window="open = false">
    <div>
        <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
        Edit Log Error
        </h5>
        <x-inputs.log-textarea  label="Error Message" wire:model.lazy="error_message" name="error_message"  type="text"/>
        <x-inputs.comment-log-textarea  label="Comments" wire:model="comments" name="comments"  type="text"/>

        <x-inputs.select label="Status" wire:model.lazy="status" name="status" required>
                    <option value="">Select Status</option>
					<option value="open">Open</option>
					<option value="in_progress">In Progress</option>
					<option value="completed">Completed</option>
        </x-inputs.select-without-label>
        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
               Update
            </x-buttons.blue-inline>
        </div>
    </form>
    </div>
</x-modals.small>