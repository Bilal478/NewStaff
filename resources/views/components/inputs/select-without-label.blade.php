@props([
    'name',
])

<div  wire:ignore class="{{ $attributes->get('class') }}">
    <div class="relative text-gray-400 focus-within:text-blue-600 rounded-md shadow-sm">
        <select
            id="{{ $name }}"
            {{ $attributes->whereStartsWith('wire:model') }}
            {{ $attributes->whereStartsWith('autofocus') }}
            {{ $attributes->whereStartsWith('required') }}
            class="h-10 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5"
        >
            {{ $slot }}
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center justify-center pr-3 pointer-events-none">
            <x-svgs.chevron-down class="w-5 h-5" />
        </div>
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
@push('scripts')

<script>
    $(document).ready(function() {
        $('#{{ $name }}').select2({
            // placeholder: '{{__('Select your option')}}',
            // allowClear: true
            // $('#{{ $name }}').select2('{{$name}}');
        });
        $("#{{ $name }}").select2();
        $('#{{ $name }}').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
    });

    </script>

@endpush
