<div>
    <div class="pb-12">
        <h1 class="font-montserrat text-xl font-semibold text-gray-700">
            Dashboard
        </h1>
    </div>
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

</div>
