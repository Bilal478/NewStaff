<div class="flex items-start flex-wrap -mx-4 pt-8 border-t">
    <div class="w-full md:w-1/3">
        <div class="mx-4 mb-4 md:mb-0">
            <h6 class="font-montserrat font-semibold text-sm text-gray-700 pb-3">Danger Zone</h6>
            <p class="text-sm text-gray-500">Delete account, this action can't be undone.</p>
        </div>
    </div>
    <div class="w-full md:w-2/3">
        <article class="bg-white mx-4 mb-8 rounded-md border shadow-sm px-6 py-4">
            <form wire:submit.prevent="delete">
                <p class="text-sm text-gray-500 leading-5 flex items-center">
                    <span class="text-xl text-gray-700 mr-2">&bull;</span>Delete all members.
                </p>
                <p class="text-sm text-gray-500 leading-5 flex items-center">
                    <span class="text-xl text-gray-700 mr-2">&bull;</span>Delete all projects.
                </p>
                <p class="text-sm text-gray-500 leading-5 flex items-center">
                    <span class="text-xl text-gray-700 mr-2">&bull;</span>Delete all tasks.
                </p>
                <p class="text-sm text-gray-500 leading-5 flex items-center">
                    <span class="text-xl text-gray-700 mr-2">&bull;</span>Delete all activities.
                </p>
                <div class="flex justify-end mt-2">
                    <x-buttons.red-inline type="submit">
                        Delete Account
                    </x-buttons.red-inline>
                </div>
            </form>
        </article>
    </div>
</div>
