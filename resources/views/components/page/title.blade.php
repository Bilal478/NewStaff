@props(['svg'])

<div class="flex items-center space-x-3 pb-12">
    <h1 class="font-montserrat text-xl font-semibold text-gray-700 flex items-center">
        <x-dynamic-component :component="$svg" class="w-8 h-8 text-blue-600 mr-2" />
        {{ $slot }}
    </h1>
</div>
