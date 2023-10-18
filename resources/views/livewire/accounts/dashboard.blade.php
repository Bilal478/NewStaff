{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
<div>
    <x-page.title svg="svgs.chart">
        Dashboard
    </x-page.title>
    <div class="mb-6 border-b w-full xl:w-1/4" >
        <h1 class="text-blue-500  text-xl" style="font-size: 30px">{{date('F d')}}</h1>
        <h4 class="text-blue-500  text-xl" style="font-size: 18px">showing data from {{ $dashWeek }}</h4>
    </div>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full sm:w-1/2 xl:w-1/4">
            <div class="bg-white rounded-md border p-6 mx-4 mb-8">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    WORKED THIS WEEK
                </h3>
                <span class="text-3xl text-gray-800">{{ $timeCount }}</span>
            </div>
        </div>

        <div class="w-full sm:w-1/2 xl:w-1/4">
            <div class="bg-white rounded-md border p-6 mx-4 mb-8">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    PROJECTS WORKED
                </h3>
                <span class="text-3xl text-gray-800">{{ $projectsCount }}</span>
            </div>
        </div>

        <div class="w-full sm:w-1/2 xl:w-1/4">
            <div class="bg-white rounded-md border p-6 mx-4 mb-8">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    TASKS WORKED
                </h3>
                <span class="text-3xl text-gray-800">{{ $tasksCount }}</span>
            </div>
        </div>

        <div class="w-full sm:w-1/2 xl:w-1/4">
            <div class="bg-white rounded-md border p-6 mx-4 mb-8">
                <h3 class="text-sm text-blue-500 xl:tracking-widest uppercase mb-6">
                    ACTIVITIES LOGGED
                </h3>
                <span class="text-3xl text-gray-800">{{ $activitiesCount }}</span>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full xl:w-1/2">
            <div class="bg-white rounded-md border p-6 mx-4 mb-8 h-72">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="inline-block text-sm text-blue-500 xl:tracking-widest uppercase">
                        PROJECTS
                    </h3>
                    <span class="text-gray-400 text-sm">{{ $week }}</span>
                </div>

                <div class="w-full">
                    <div class="flex items-center text-gray-700 text-xs font-semibold border-b">
                        <div class="flex-1 px-2 py-4">Project</div>
                        <div class="w-32 px-2 py-4">Time</div>
                    </div>

                    @foreach ($projects as $activityProject)
                        <div class="flex items-center text-gray-500 text-sm {{ $loop->last ? '' : 'border-b' }}">
                            <div class="flex-1 px-2 py-4 truncate">{{ $activityProject->project ? $activityProject->project->title : "" }}</div>
                            <div class="w-32 px-2 py-4">{{ Carbon\CarbonInterval::seconds($activityProject->seconds)->cascade()->format('%H:%I:%S') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="w-full xl:w-1/2">
            <div class="bg-white rounded-md border p-6 mx-4 mb-8 h-72">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="inline-block text-sm text-blue-500 xl:tracking-widest uppercase">
                        TASKS
                    </h3>
                    <span class="text-gray-400 text-sm">{{ $week }}</span>
                </div>

                <div class="w-full">
                    <div class="flex items-center text-gray-700 text-xs font-semibold border-b">
                        <div class="flex-1 px-2 py-4">Task</div>
                        <div class="w-32 px-2 py-4">Time</div>
                    </div>

                    @foreach ($tasks as $activityTask)
                        <div class="flex items-center text-gray-500 text-sm {{ $loop->last ? '' : 'border-b' }}">
                            <div class="flex-1 px-2 py-4 truncate">{{ $activityTask->task ? $activityTask->task->title : "" }}</div>
                            <div class="w-32 px-2 py-4">{{ Carbon\CarbonInterval::seconds($activityTask->seconds)->cascade()->format('%H:%I:%S') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if (session()->get('account_role') === 'manager')
    <div class="flex flex-wrap -mx-4">
        <div class="w-full xl:w-1/2">
            <div class="bg-white rounded-md border p-6 mx-4 mb-8 h-84">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="inline-block text-sm text-blue-500 xl:tracking-widest uppercase">
                        Manager
                    </h3>
                    <h3 class="inline-block text-sm text-blue-500 xl:tracking-widest uppercase">
                        {{ $authUserName }}
                    </h3>
                </div>

                <div class="w-full">
                    <div class="flex items-center text-gray-700 text-xm font-semibold border-b">
                        {{-- <div class="flex-1 px-2 py-4">User Id</div> --}}
                        <div class="flex-1 px-2 py-4">Users</div>
                    </div>

                    @foreach ($userRecords as $record)
                        <div class="flex items-center text-gray-500 text-sm border-b">
                            {{-- <div class="flex-1 px-2 py-4 truncate">{{ $record->id }}</div> --}}
                            <div class="flex-1 px-2 py-4">{{ $record->firstname }} {{$record->lastname}}</div>
                        </div>
                    @endforeach
                </div>
                <div class="pt-5">
                    {{ $userRecords->links('vendor.pagination.default') }}
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
<style>
    
</style>