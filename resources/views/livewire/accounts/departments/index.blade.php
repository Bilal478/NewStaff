<div>
  <x-page.title svg="svgs.departments">
    Departments
  </x-page.title>
  <div class="sm:flex items-center justify-between pb-8">
        <x-inputs.search wire:model.debounce.500ms="search" name="search" class="w-full sm:w-72" />

        @role(['owner', 'manager'])
            <button wire:click="$emit('departmentsCreate')" type="button" class="w-full sm:w-auto mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
                <x-svgs.plus class="w-5 h-5 mr-1" />
                New Department
            </button>
        @endrole
    </div>
    @if ($departments->count())
        <div class="flex flex-wrap -mx-4">
            @foreach ($departments as $department)
            <x-departments.card
                :department="$department"
                :users="$department->users"
                :users-count="$department->users_count"
            />
            @endforeach
        </div>

        <div>
            {{ $departments->links('vendor.pagination.default') }}
        </div>
    @else
        @role(['owner', 'manager'])
            <x-states.empty-data message="There are no departments created." />
        @else
            <x-states.empty-data message="You don't have departments assigned." />
        @endrole
    @endif

    @push('modals')
        @livewire('accounts.departments.departments-form')
    @endpush
</div>
