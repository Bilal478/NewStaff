<div>
    <x-page.title-breadcrum svg="svgs.team" route="{{ route('accounts.teams', ['account' => request()->account]) }}">
        Teams
        <x-slot name="breadcrum">
            {{ $team->title }}
        </x-slot>
    </x-page.title-breadcrum>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full xl:w-2/3">
            <div class="bg-white mx-4 mb-8 rounded-md border shadow-sm p-8">
                <div class="pb-8 flex items-start justify-between">
                    <div>
                        <h4 class="font-montserrat font-semibold text-xl text-gray-700 pb-2">
                            {{ $team->title }}
                        </h4>
                    </div>
                </div>
            </div>

            @livewire('accounts.teams.team-tasks', ['team' => $team])
        </div>
        <div class="w-full xl:w-1/3">
            <div class="bg-white mx-4 mb-8 rounded-md border shadow-sm p-6 sm:p-8">
                <h4 class="font-montserrat text-sm font-semibold text-blue-600 pb-4 flex items-center">
                    Members
                </h4>

                @livewire('accounts.teams.team-users-form', ['teamId' => $team->id])
            </div>
        </div>
    </div>
</div>
