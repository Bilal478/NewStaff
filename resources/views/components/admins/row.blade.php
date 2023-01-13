@props(['user'])

<div class="w-full bg-white py-5 rounded-md border mb-3">
    <div class="flex items-center text-sm">
        <div class="flex-1 px-3 truncate">
            <div class="flex items-center">
                <x-user.avatar />
                <span class="ml-3 block text-left font-montserrat text-xs font-semibold text-gray-500">{{ $user->full_name }}</span>
            </div>
        </div>
        <div class="w-56 px-3 text-xs text-gray-500">
            {{ $user->email }}
        </div>
        <div class="w-20 px-3 flex justify-end">
            <x-dropdowns.context-menu>
                <x-dropdowns.context-menu-item wire:click.stop="adminDelete({{$user->id}})" name="Remove" svg="svgs.x-circle"/>
            </x-dropdowns.context-menu>
        </div>
    </div>
</div>
