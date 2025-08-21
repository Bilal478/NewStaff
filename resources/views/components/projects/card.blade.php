@props(['project', 'users', 'usersCount', 'tasksCount', 'index'])
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
    <article class="bg-white mx-4 mb-8 rounded-2xl border shadow transition-shadow duration-200 px-6 py-5 h-72 flex flex-col justify-between cursor-pointer hover:shadow-lg">
        <div>
            <div class="flex justify-between items-start mb-3">
                <span wire:click="projectShow({{$project->id}})" class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded font-medium">Project #{{ $project->project_number }}</span>
                @role(['owner', 'manager'])
                    <x-dropdowns.context-menu class="-mr-2">
                        <x-dropdowns.context-menu-item wire:click.stop="$emit('projectEdit', {{$project->id}})" name="Edit" svg="svgs.edit"/>
                        <x-dropdowns.context-menu-item wire:click.stop="projectArchive({{$project->id}})" name="Delete" svg="svgs.trash"/>
                    </x-dropdowns.context-menu>
                @endrole
            </div>

            <div wire:click="projectShow({{$project->id}})" class="space-y-1 mb-4">
                <p class="text-xs text-gray-500 font-semibold">{{ $category }}</p>
                <p class="text-xs text-gray-400">Client: {{ $project->client_name ?? '_' }}</p>
            </div>

            <div wire:click="projectShow({{$project->id}})" class="mb-4">
                <h4 class="font-semibold text-gray-500 text-base truncate mb-1">{{ $project->title }}</h4>
                <p class="text-sm text-gray-600 leading-snug line-clamp-3">{{ $project->description }}</p>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-1">
                @foreach ($users as $key => $user)
                    @if ($key < 2)
                        @php
                            $colors = ['bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-yellow-500', 'bg-purple-500', 'bg-teal-500'];
                            $color = $colors[$key % count($colors)];
                        @endphp
                        <div class="h-8 w-8 {{ $color }} rounded-full text-white flex items-center justify-center text-xs font-bold shadow">
                            {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                        </div>
                    @endif
                @endforeach
                @if ($usersCount > 2)
                    <div class="h-8 w-8 bg-gray-400 rounded-full text-white flex items-center justify-center text-xs font-semibold shadow">
                        +{{ $usersCount - 2 }}
                    </div>
                @endif
            </div>

            <div class="flex items-center text-sm text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                {{ $tasksCount }} tasks
            </div>
        </div>
    </article>
</div>
