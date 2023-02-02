@php
$account_user = DB::table('account_user')
    ->where('user_id', auth()->user()->id)
    ->first();
@endphp
<x-dropdowns.context-menu>
    @if (!$task->completed)
    <x-dropdowns.context-menu-item wire:click.stop="taskComplete({{$task->id}})" name="Complete" svg="svgs.check-circle"/>
    @else
    <x-dropdowns.context-menu-item wire:click.stop="taskProcessing({{$task->id}})" name="In progress" svg="svgs.check-circle"/>
    @endif
    @role(['owner', 'manager'])
        <x-dropdowns.context-menu-item wire:click.stop="$emit('taskEdit', {{$task->id}})" name="Edit" svg="svgs.edit"/>
        <x-dropdowns.context-menu-item wire:click.stop="taskDelete({{$task->id}})" name="Delete" svg="svgs.trash" class="border-t"/>
    @endrole
    @if($account_user->allow_edit_time == 1)
    <x-dropdowns.context-menu-item wire:click.stop="$emit('activity', {{$task->id}})" name="Add Time" svg="svgs.plus"/>
    @endif
</x-dropdowns.context-menu>
