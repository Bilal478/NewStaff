<div>
    <x-page.title svg="svgs.task">
        Tasks     <?php 
        
        if(!isset($_GET)){
            print_r($_GET['page']);
            $var = $_GET['page'];
        }
        ?>
    </x-page.title>

    <div class="sm:flex items-center justify-between pb-8">
        <x-inputs.search wire:model.debounce.500ms="search" class="w-full sm:w-72" />

        @role(['owner', 'manager'])
            <button wire:click="$emit('taskCreate')" type="button" class="w-full sm:w-auto mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
                <x-svgs.plus class="w-5 h-5 mr-1" />
                New Task
            </button>
        @endrole
    </div>

    @if ($tasks->count())
        <div>
            <x-tasks.table-headings />
            @foreach ($tasks as $task)
                <x-tasks.table-row :task="$task" />
            @endforeach
        </div>

        <div class="pt-5">
            {{ $tasks->links('vendor.pagination.default') }}
        </div>
    @else
        @role(['owner', 'manager'])
            <x-states.empty-data message="There are no tasks created." />
        @else
            <x-states.empty-data message="You don't have tasks assigned." />
        @endrole
    @endif
    @push('modals')
    @livewire('accounts.tasks.tasks-form')
	@livewire('accounts.tasks.tasks-form2')

    @livewire('accounts.tasks.tasks-form-edit')
    @livewire('accounts.tasks.tasks-show')
    
    @endpush
    @push('modals')
        @livewire('time-modal')
    @endpush

</div>
