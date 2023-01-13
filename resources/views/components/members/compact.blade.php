<div class="flex items-center justify-between {{ $attributes->get('class') }}">
    <div class="flex items-center">
        <x-user.avatar :large="true" />

        <div class="ml-3 truncate">
            <span class="block text-left font-montserrat text-sm font-semibold text-gray-700 truncate">{{ $user->full_name }}</span>
            <span class="block text-left text-xs text-gray-400 truncate">{{ $user->email }}</span>
        </div>
    </div>

    @role(['owner', 'manager'])
        <button {{ $attributes->whereStartsWith('wire:click') }} type="button" class="text-gray-400 hover:text-red-500">
            <x-svgs.x-circle class="h-5 w-5" />
        </button>
    @endrole
</div>
