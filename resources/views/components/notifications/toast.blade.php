<div
    x-data="{show: false, type: 'success', title: '', description: ''}"
    x-show="show"
    x-on:toast.window="show = true; type = $event.detail.type; title = $event.detail.title; description = $event.detail.description; setTimeout(() => { show = false }, $event.detail.duration)"
    x-cloak
    class="fixed bg-white mr-5 mt-5 p-4 right-0 rounded-lg shadow-xl top-0 w-80 border z-30"
>
    <div class="relative flex items-start justify-end">
        <div class="flex-1">
            <div class="flex items-start">
                <div class="pr-4">
                    <x-svgs.x-circle x-show="type == 'error'" class="w-6 h-6 text-red-500" />
                    <x-svgs.check-circle x-show="type == 'success'" class="w-6 h-6 text-green-500" />
                </div>
                <div>
                    <h5 x-text="title" class="text-sm font-semibold text-gray-700 leading-6"></h5>
                    <p x-text="description" class="text-sm text-gray-500 mt-1"></p>
                </div>
            </div>
        </div>
        <button x-on:click="show = false" type="button" class="text-gray-400 hover:text-gray-700">
            <x-svgs.x class="h-5 w-5" />
        </button>
    </div>
</div>
