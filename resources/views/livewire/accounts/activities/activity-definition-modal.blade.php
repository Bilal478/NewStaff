<x-modals.large x-on:open-activity-definition-modal.window="open = true" x-on:close-activity-definition-modal.window="open = false">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6" x-data="{ selectedTab: 'activity-averages' }">
        <h1 class="text-lg text-gray-700 font-semibold mb-4">Activity in Neostaff</h1>
        <div class="flex">
            <!-- Sidebar -->
            <div class="w-1/4 border-r pr-4">
                <ul>
                    <li class="mb-2">
                        <a href="#" 
                           :class="{'text-blue-600 font-bold': selectedTab === 'activity-averages', 'text-gray-600': selectedTab !== 'activity-averages'}"
                           x-on:click.prevent="selectedTab = 'activity-averages'">Activity Averages</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" 
                           :class="{'text-blue-600 font-bold': selectedTab === 'the-apps', 'text-gray-600': selectedTab !== 'the-apps'}"
                           x-on:click.prevent="selectedTab = 'the-apps'">The Apps</a>
                    </li>
                </ul>
            </div>
            <!-- Main content -->
            <div class="w-3/4 pl-4">
                <!-- Activity Averages Content -->
                <div x-show="selectedTab === 'activity-averages'">
                    <h2 class="text-md font-bold mb-5" style="width:500px;">Activity measures how active users are on their mouse and keyboard.</h2>
                    <div class="mb-4">
                        <div class="flex items-center mt-5">
                            <div class="w-24 h-7 bg-green-500 rounded-full mr-2 text-center text-white px-4 flex items-center justify-center">51-100%</div>
                            <span class="text-sm" style="width:360px;">Great Job!, you are exceptional</span>
                        </div>
                        <div class="flex items-center mt-5">
                            <div class="w-24 h-7 bg-yellow-500 rounded-full mr-2 text-center text-white px-4 flex items-center justify-center">21-50%</div>
                            <span class="text-sm" style="width:360px;">Average Range to be in, by industry standards</span>
                        </div>
                        <div class="flex items-center mt-5">
                            <div class="w-24 h-7 bg-red-500 rounded-full mr-2 text-center text-white px-4 flex items-center justify-center">0-20%</div>
                            <span class="text-sm" style="width:360px;">Activity low on the computer or device</span>
                        </div>
                        <div class="flex items-center mt-4">
                            <div class="w-24 h-7 bg-gray-500 rounded-full mr-2 text-center text-white px-4 flex items-center justify-center">Idle</div>
                            <span class="text-sm" style="width:360px;">Idle You weren't touching the mouse or keyboard at all. Idle settings can be customized for each team member</span>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mt-2">Welcome to activity video</h2>
                    <div>
                        <img src="https://via.placeholder.com/150" alt="Activity Video Thumbnail" class="rounded-lg">
                    </div>
                </div>
                <!-- The Apps Content -->
                <div x-show="selectedTab === 'the-apps'">
                    <div class="mb-4">
                        <p>These timers do track activity</p>
                       <div class="bg-blue-100 text-gray-600 mr-2 mt-3 px-2 py-1" style="width: 400px: border-radius:3px"><b>Desktop apps</b> (also record screenshots)</div>
                    <div class="flex items-center justify-between my-3 ">
                        <div>
                            <a href="//media.neostaff.app/downloads/windows" class="flex flex-col items-center">
                                <x-svgs.windows class="w-4 h-4 text-blue-500" />
                                <span class="text-sm font-bold text-gray-400 mt-2">
                                    Windows
                                </span>
                            </a>
                        </div>
                        <div>
                            <a href="//media.neostaff.app/downloads/mac" class="flex flex-col items-center">
                            <x-svgs.mac class="w-4 h-4 text-blue-500 mr-1" />
                            <span class="text-sm font-bold text-gray-400 mt-2">
                                Mac OS X 
                            </span>
                            </a>
                        </div>
                        <div>
                            <a href="//media.neostaff.app/downloads/ubutnu" class="flex flex-col items-center">
                            <x-svgs.ubuntu class="w-8 h-8 text-blue-500 mr-1" />
                            <span class="text-sm font-bold text-gray-400 mt-2">
                                Linux
                            </span>
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
                
            </div> 
        </div>
    </div>
</x-modals.large>
