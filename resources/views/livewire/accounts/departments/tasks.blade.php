@php
    $user_role=[];
    $user_login = auth()->id();
    $role=DB::select('SELECT * FROM department_admin where user_id='.$user_login );
    foreach($role as $val){
                $user_role[]=$val->department_id;
            }    
@endphp
<div class="mx-4 mb-8">
    @role(['owner', 'manager'])
        <div class="flex items-center justify-end mb-8">
            @role(['owner', 'manager'])
            <button wire:click="$emit('projectCreate')" type="button"
                class="w-full sm:w-auto mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out mr-2">
                <x-svgs.plus class="w-5 h-5 mr-1" />
                New Project
            </button>
            @endrole
            <button wire:click="$emit('taskCreate')" type="button" class="w-full sm:w-auto h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
                <x-svgs.plus class="w-5 h-5 mr-1" />
                New Task
            </button>
        </div>
    @endrole
    @role('member')
    @if (in_array($department->id,$user_role))
    <div class="flex items-center justify-end mb-8">
        <button wire:click="$emit('projectCreate')" type="button"
            class="w-full sm:w-auto mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out mr-2">
            <x-svgs.plus class="w-5 h-5 mr-1" />
            New Project
        </button>
        <button wire:click="$emit('taskCreate')" type="button" class="w-full sm:w-auto h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
            <x-svgs.plus class="w-5 h-5 mr-1" />
            New Task
        </button>
    </div>
    @endif
@endrole
    @if ($tasks->count())
        <x-tasks.table-headings />
        @foreach ($tasks as $task)
            <x-tasks.table-row :task="$task" />
        @endforeach

        <div class="pt-5">
            {{ $tasks->links('vendor.pagination.default') }}
        </div>
    @else
        <x-states.empty-data message="This department doesn't have tasks." />
    @endif

    @push('modals')
        @livewire('accounts.tasks.tasks-form', ['department' => $department])
        @livewire('accounts.tasks.tasks-show')
        @livewire('accounts.tasks.tasks-form-edit')

    @endpush
</div>
