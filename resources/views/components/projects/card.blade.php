@props(['project', 'users', 'usersCount', 'tasksCount'])
<?php
use App\Models\Department;

if($project->department_id){
    $category =Department::where('id',$project->department_id)->pluck('title')->first();
}
else{
    $category = $project->category;
}
    
?>
<div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4">
    <article
        
        class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4 h-64 flex flex-col justify-between items-start cursor-pointer hover:shadow-md"
    >
        <div class="w-full">
            <div class="mb-4 flex items-center justify-between">
                <span wire:click="projectShow({{$project->id}})" class="px-2 py-1 text-xs bg-purple-100 text-purple-500 rounded">{{ $category }}</span>
                @role(['owner', 'manager'])
                    <x-dropdowns.context-menu class="-mr-2">
                        <x-dropdowns.context-menu-item wire:click.stop="$emit('projectEdit', {{$project->id}})" name="Edit" svg="svgs.edit"/>
                        <x-dropdowns.context-menu-item wire:click.stop="projectArchive({{$project->id}})" name="Delete" svg="svgs.trash"/>
                    </x-dropdowns.context-menu>
                @endrole
            </div>
            <span wire:click="projectShow({{$project->id}})">
            <h4 class="font-montserrat font-semibold text-sm text-gray-700 pb-4 truncate">
                {{ $project->title }}
            </h4>
            <div class="h-16 overflow-ellipsis overflow-hidden">
                <p class="text-sm text-gray-500">
                    {{ $project->description }}
                </p>
            </div>
            </span>
        </div>
        <div wire:click="projectShow({{$project->id}})" class="w-full flex items-center justify-between">
            <div class="flex items-center space-x-1">
                @if ($usersCount >= 1)
                    <x-user.avatar />
                @endif
                @if ($usersCount >= 2)
                    <x-user.avatar />
                @endif
                @if ($usersCount > 2)
                    <div class="h-8 w-8 bg-indigo-400 rounded-full text-white flex items-center justify-center text-xs tracking-wider">
                        +{{ $usersCount - 2 }}
                    </div>
                @endif
            </div>
            <div class="flex items-center text-sm text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                {{ $tasksCount }} tasks
            </div>
        </div>
    </article>
</div>
