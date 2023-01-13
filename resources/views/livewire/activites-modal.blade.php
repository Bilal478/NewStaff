<x-modals.small x-on:open-activity-modal.window="open = true" x-on:close-activity-modal.window="open = false">

    <table class="w-full border mt-5">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">#</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Activity</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Time</th>
                <th class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $item)
            <tr class="bg-gray-50 text-center">
                <td class="p-2 border-r">{{$loop->iteration}}</td>
                <td class="p-2 border-r">{{ "Activity $item->id" }}</td>
                <td class="p-2 border-r">{{ gmdate("H:i:s", $item->seconds) }}</td>
                <td class="p-2 border-r">
                    <button type="button" wire:click="$emit('timeEdit','{{ $item->id }}','{{ $item->seconds }}')">
                        <span class="text-xs text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg></span>
                    </button>
                    <button wire:click="deleteActivity('{{ $item->id }}')" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </td>
            </tr>
    @endforeach


        </tbody>
    </table>


</x-modals.small>
