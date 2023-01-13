<div class="flex items-center justify-between {{ $attributes->get('class') }}">
    <div class="flex items-center">
        <div class="ml-3 truncate">
            <span class="block text-left font-montserrat text-sm font-semibold text-gray-700 truncate">{{ $department->title }}</span>
            <span class="block text-left text-xs text-gray-400 truncate">{{ $department->title }}</span>
        </div>
    </div>

    @role(['owner', 'manager'])
        <button {{ $attributes->whereStartsWith('wire:click') }} type="button" class="text-gray-400 hover:text-red-500">
            <x-svgs.x-circle class="h-5 w-5" />
        </button>
    @endrole
</div>
