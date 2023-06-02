<x-modals.small x-on:open-task-form-edit-modal.window="open = true" x-on:close-task-form-edit-modal.window="open = false">
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
		{{--  {{ $isEditing ? 'Edit Tasky' : 'New Tasky' }} --}}
		Edit Task
        </h5>

        @if (! $inProject)

        <div class="border-b mb-6">
            <x-inputs.select wire:model="project_id" label="Project" name="project_id" required>
                <option value="">Select a project</option>
                @foreach ($projects as $project)
                <option value="{{ $project->id }}">
                    {{ $project->title }}
                </option>
                @endforeach
            </x-inputs.select>
        </div>

        @endif

        <x-inputs.text wire:model.lazy="title" label="Title" name="edit_title" type="text" placeholder="Title" required />

        <x-inputs.textarea wire:model.lazy="description" label="Description" name="edit_description" type="text"
            placeholder="Description" required />

        <x-inputs.select wire:model.lazy="user_id" label="Assignee" name="user_id">
            <option value="">Select a Assignee</option>
             @foreach (App\Models\User::get() as $user)
            <option value="{{ $user->id }}">
                {{ $user->firstname }} {{ $user->lastname }}
            </option>
            @endforeach
        </x-inputs.select>
        <div >
		{{--
            <div class="pb-6">
                <label for="activityId" class="block text-sm text-gray-500 leading-5">
                    Activity
                </label>
                <div class="relative text-gray-400 focus-within:text-blue-600 mt-1 rounded-md shadow-sm">
                    <select id="activityId" name="activityId" style="width: 100%" wire:model="activityId">
                         @foreach (App\Models\Activity::get() as $activity)
                            <option value="{{ $activity->id }}" data-activityId="{{ $activity->id }}">
                                Activity {{ $activity->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @error('activityId')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
		--}}
            {{-- <x-inputs.select wire:click="changeEvent()" label="Activity" name="edit_activityId" required>
                <option value="">Select a activity</option>

            </x-inputs.select> --}}
        </div>
		{{--
		<x-inputs.time wire:model="tracking_time" label="Tracking time" name="edit_tracking_time" required />
		--}}

        <x-inputs.select wire:model.lazy="team_id" label="Team" name="team_id" type="text">
        {{-- <option value="">Select a team</option>--}}
            @foreach (App\Models\Team::get() as $item)
            <option value="{{ $item->id }}">{{ $item->title }}</option>
            @endforeach
        </x-inputs.select>
        <x-inputs.select wire:model.lazy="department_id" label="Department" name="department_id" type="text">
		{{--<option value="">Select a department</option>--}}
            @foreach (App\Models\Department::get() as $item)
            <option value="{{ $item->id }}">{{ $item->title }}</option>
            @endforeach
        </x-inputs.select>


        <x-inputs.select wire:model.lazy="completed" label="Status" name="edit_completed" required>
        {{--  <option value="">Select statuss</option>--}}
            <option value="false">In progress</option>
            <option value="true"> Completed </option>
        </x-inputs.select>



        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
			{{-- {{ $isEditing ? 'Update Tasky' : 'Create Tasky' }}--}}
			   Update Task
            </x-buttons.blue-inline>
        </div>




    </form>
</x-modals.small>

@push('scripts')

<script>
    document.addEventListener('livewire:load', function (event) {

            Livewire.hook('message.processed', () => {

                $('#activityId').select2();

            });

        });

            $('#activityId').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            // $('#tracking_time').val(data);
            @this.set(elementName, data);
        });

        $('#activityId').select2();

</script>

@endpush
