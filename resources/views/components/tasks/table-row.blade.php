@props(['task','project'])

<div class="w-full bg-white py-5 rounded-md border mb-3 cursor-pointer hover:shadow-md">
    <div class="hidden md:flex items-center text-sm">
        <div wire:click.stop="$emit('taskShow', '{{$task->id}}','{{$task->user_id}}')" class="flex-1 px-3 text-gray-700 font-montserrat font-semibold">
            {{ $task->title }}
        </div>
        <div wire:click.stop="$emit('taskShow', '{{$task->id}}','{{$task->user_id}}')" class="w-44 px-3">
            <div class="flex items-center">
                @if ($task->user)
                    <x-user.avatar />
                    <span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">{{ $task->user->firstname }} {{ $task->user->lastname }}</span>
                @else
                    <span class="text-xs text-gray-500">Not assigned</span>
                @endif
            </div>
        </div>

        <h4 wire:click.stop="$emit('taskShow', '{{$task->id}}','{{$task->user_id}}')" class="w-32 px-3 text-xs text-gray-500">
            {{ $task->project->title }}
        </h4>


        <div wire:click.stop="$emit('taskShow', '{{$task->id}}','{{$task->user_id}}')" class="w-32 px-3 text-xs text-gray-500">
            @if ($task->due_date)
                {{ $task->due_date->format('M d, Y') }}
            @else
                No due date
            @endif
        </div>
        {{-- @dump($task) --}}
        <div wire:click.stop="$emit('taskShow', '{{$task->id}}','{{$task->user_id}}')" class="w-32 px-3 text-xs text-gray-500">
             {{ gmdate("H:i:s", App\Models\Activity::where('project_id',$task->project_id)->where('task_id',$task->id)->sum('seconds'))}}
        </div>
        <div wire:click.stop="$emit('taskShow', '{{$task->id}}','{{$task->user_id}}')" class="w-32 px-3">
            <x-tasks.status-badge :status="$task->completed" />
        </div>
        <div class="w-20 px-3 flex justify-end">
            @role(['owner', 'manager'], optional($task->user)->id)
                <x-tasks.actions :task="$task" />
            @endrole
        </div>
    </div>

    {{-- <div class="text-sm block md:hidden">
        <div class="flex items-start justify-between">
            <div class="px-4 flex-1 truncate">
                <div class="flex items-center justify-between mb-3">
                    <x-tasks.status-badge :status="$task->completed" />
                    <span class="text-xs text-gray-500">
                        @if ($task->due_date)
                            {{ $task->due_date->format('M d, Y') }}
                        @else
                            No due date
                        @endif
                    </span>
                </div>
                <div class="text-gray-700 font-montserrat font-semibold truncate">
                    {{ $task->title }}
                </div>
			
            </div>
            <x-tasks.actions :task="$task" />
        </div>
    </div> --}}
</div>
