<?php

use App\Models\Department;
use Illuminate\Support\Facades\Auth;

if(Auth::guard('web')->user()->isOwner()){
    $departments = Department::orderBy('title')->get();
}
else{

    $departments = Auth::guard('web')->user()
            ->departments()
            ->orderBy('title')->get();
}
?>

<x-modals.small x-on:open-create-modal.window="open = true" x-on:close-create-modal.window="open = false">
    <form wire:submit.prevent="save">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            {{ $isEditing ? 'Edit Project' : 'New Projects' }}
        </h5>

        <x-inputs.text wire:model.lazy="project.title" label="Title" name="title" type="text" placeholder="Title" required/>

        <x-inputs.textarea wire:model.lazy="project.description" label="Description" name="description" type="text" placeholder="Description" required />

        <x-inputs.select wire:model.lazy="project.department_id" name="userId" label="Department" name="project.department_id" required>
                    <option value="">Select Department</option>
                    @foreach ($departments as $department)
					<option value="{{ $department->id }}">{{ $department->title }}</option>
					@endforeach
                   
                </x-inputs.select-without-label>

        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                {{ $isEditing ? 'Update Project' : 'Create Project' }}
            </x-buttons.blue-inline>
        </div>
    </form>
</x-modals.small>
