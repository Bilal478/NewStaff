@props(['message'])

<div class="py-10">
    <div class="flex items-center justify-center pb-4">
        <x-svgs.no-data class="h-32 w-auto" />
    </div>

    <h4 class="text-gray-400 text-center">
        {{ $message }}
    </h4>
</div>
