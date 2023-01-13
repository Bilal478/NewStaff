<div wire:ignore class="pb-6 {{ $attributes->get('class') }}">
    <label for="{{ $name }}" class="block text-sm text-gray-500 leading-5">
        {{ $label }} @if ($attributes->has('required'))<span class="text-red-500 text-xs">*</span>@endif
    </label>
    <div class="" style="border: 1px solid #e5e5e5; border-radius:3px; width:100%; margin-bottom:15px; padding: 3px 0px;">
        <select id="{{ $name }}" style="width: 100%" {{ $attributes->whereStartsWith('wire:model') }}
            {{ $attributes->whereStartsWith('autofocus') }} {{ $attributes->whereStartsWith('required') }}>
            {{ $slot }}
			
        </select>
    </div>

    @error($name)
    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
@push('scripts')

<script>
    document.addEventListener('livewire:load', function (event) {

            Livewire.hook('message.processed', () => {

                $('#{{ $name }}').select2();

            });

        });

            $('#{{ $name }}').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });

        $('#{{ $name }}').select2();

</script>
@endpush
