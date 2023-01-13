<div class="w-full">
    @role(['owner', 'manager'])
    <div class="pb-6 mb-2 border-b">
        <form wire:submit.prevent="add" class="flex items-center space-x-2">
            <x-inputs.select-without-label wire:model.lazy="departmentId" name="departmentId" class="flex-1">
                <option value="">Select department</option>
                @foreach ($departmentOut as $department)
                <option value="{{ $department->id }}">
                    {{ $department->title }}
                </option>
                @endforeach
            </x-inputs.select-without-label>

            <x-buttons.blue-inline type="submit" class="h-10">
                Add
            </x-buttons.blue-inline>
        </form>
    </div>
    @endrole

    <div class="h-96 overflow-y-scroll">
        @foreach ($departmentIn as $department)

        <x-members.department :department="$department" :key="$department->id" wire:click="remove({{$department->id}})"
            class="p-2 my-2 rounded-md cursor-default hover:bg-gray-100" />

        @endforeach
    </div>
</div>
