<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div>
    <div class="pb-12">
        <h1 class="font-montserrat text-xl font-semibold text-gray-700 float-left mr-20">
            Dashboard
        </h1>
        
        <button type="button" class="mt-4 mb-4 h-10 text-sm flex items-center rounded-md bg-blue-600 text-white pl-3 pr-3 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out">
           <a href="#miModal"> Track Time</a>
        </button>
    
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
<div id="miModal" class="modal">
    <div class="modal-contenido">
        <a href="#">X</a>
        <div class="py-4 pl-4">
            <h4 class="text-gray-600">
                <i class="fa fa-exclamation-circle"></i> Your team has not tracked any time.
            </h4>
        </div>

        <h1 class="py-4"><b>Getting Started</b></h1>
        <div class="list">
            <ul>
                <ol>1. Each team member needs to open their invite email and click the accept link.</li>
                    <ol>2. Next they must download the <a class="text-blue-600" href="https://neostaff.app/download">NeoStaff App.</a></li>
                        <ol>3. Finally they have to install the app and use it to track time to a project.</li>
            </ul>
        </div>
        <h1 class="py-4"><b>Your Organizations</b></h1>
        <h1 class="pb-2"><b><?php if (isset($account[0])) {
            echo $account[0]->name . ' team members';
        }  ?> </b></h1>
        <h4 class="text-gray-400 py-4"><i class="fas fa-info-circle"></i> Time can also be added manually on the
            timesheets page.</h4>
    </div>
</div>

<style>
    .modal-contenido {
        background-color: white;
        border-radius: 8px;
        width: 650px;
        height: 400px;
        padding: 10px 20px;
        margin: 100px 100px 350px 350px;
        position: relative;
    }

    .list {
        margin-left: 30px;
    }

    .modal {
        background-color: rgba(0, 0, 0, 0.5);
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        opacity: 0;
        pointer-events: none;
        transition: all 1s;
    }

    #miModal:target {
        opacity: 1;
        pointer-events: auto;
    }
</style>