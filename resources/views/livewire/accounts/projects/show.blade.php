<?php
use App\Models\Department;
    $category = $project->category ?? Department::where('id',$project->department_id)->pluck('title')->first();
?>
<div>
    <x-page.title-breadcrum svg="svgs.folder" route="{{ route('accounts.projects', ['account' => request()->account]) }}">
        Projects
        <x-slot name="breadcrum">
            {{ $project->title }}
        </x-slot>
    </x-page.title-breadcrum>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full xl:w-2/3">
            <!-- <div class="bg-white mx-4 mb-8 rounded-md border shadow-sm p-8">
                <div class="pb-8 flex items-start justify-between">
                    <div>
                        <h4 class="font-montserrat font-semibold text-xl text-gray-700 pb-2">
                            {{ $project->title }}
                        </h4>
                        <span class="px-2 py-1 text-xs bg-purple-100 text-purple-500 rounded">{{ $category }}</span>
                    </div>
                </div>
                <p class="text-gray-500">
                    {{ $project->description }}
                </p>
            </div> -->
            <div class="bg-white mx-4 mb-8 rounded-md border shadow-sm p-6 space-y-3">
    

    <!-- Metadata block -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-600">
        <div>
            <p class="text-gray-500 font-semibold mb-1">Project #</p>
            <p class="text-gray-700 rounded text-sm inline-block">
                {{ $project->project_number ?? '_' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 font-semibold mb-1">Client Name</p>
            <p class="text-gray-700 text-sm">
                {{ $project->client_name ?? '_' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 font-semibold mb-1">Department</p>
            <p class="px-2 py-1 bg-purple-100 text-purple-600 rounded text-xs inline-block">
                {{ $category }}
            </p>
        </div>
    </div>
    <!-- Title -->
    <h4 class="font-montserrat font-semibold text-lg text-gray-800">
        {{ $project->title }}
    </h4>

    <!-- Description -->
    <p class="text-gray-600 leading-relaxed text-sm ">
        {{ $project->description }}
    </p>
</div>

            @livewire('accounts.projects.project-tasks', ['project' => $project])

        </div>
        <div class="w-full xl:w-1/3">
            <div class="bg-white mx-4 mb-8 rounded-md border shadow-sm p-6 sm:p-8">
                <h4 class="font-montserrat text-sm font-semibold text-blue-600 pb-4 flex items-center">
                    Members
                </h4>

                @livewire('accounts.projects.project-users-form', ['projectId' => $project->id])
            </div>
        </div>
    </div>
    @push('modals')
@livewire('accounts.tasks.delete-task-modal')
@endpush

</div>
