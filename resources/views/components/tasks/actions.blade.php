<x-dropdowns.context-menu>
    @if (!$task->completed)
    <x-dropdowns.context-menu-item wire:click.stop="taskComplete({{$task->id}})" name="Complete" svg="svgs.check-circle"/>
    @else
    <x-dropdowns.context-menu-item wire:click.stop="taskProcessing({{$task->id}})" name="In progress" svg="svgs.check-circle"/>
    @endif
    @role(['owner', 'manager'])
        <x-dropdowns.context-menu-item wire:click.stop="$emit('taskEdit', {{$task->id}})" name="Edit" svg="svgs.edit"/>
        <x-dropdowns.context-menu-item wire:click.stop="taskDelete({{$task->id}})" name="Delete" svg="svgs.trash" class="border-t"/>
		<x-dropdowns.context-menu-item wire:click.stop="$emit('activity', {{$task->id}})" name="Add Time" svg="svgs.plus"/>
    @endrole
</x-dropdowns.context-menu>
