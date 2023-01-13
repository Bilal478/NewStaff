@props([
    'name',
    'label',
    'type' => 'text',
    'placeholder' => '',
    'clearButton' => true,
])

<div
    class="pb-6 {{ $attributes->get('class') }}"
>
    <label for="{{ $name }}" class="block text-sm text-gray-500 leading-5">
        {{ $label }} @if ($attributes->has('required'))<span class="text-red-500 text-xs">*</span>@endif
    </label>
    <div class="relative text-gray-400 focus-within:text-blue-600 mt-1 rounded-md shadow-sm">
        <input
            x-data
            x-ref="datepicker"
            x-init="new Litepicker({
                element: $refs.datepicker,
                format: 'MMM DD, YYYY',
                setup: (picker) => {
                    picker.on('selected', (date) => {
                        $dispatch('input', moment(date.dateInstance).format('MMM DD, YYYY'));
                    });
                },
                {{ $clearButton ?
                'resetButton: () => {
                    let btn = document.createElement("button");
                    btn.innerText = "Clear";
                    btn.addEventListener("click", (evt) => {
                        evt.preventDefault();
                        $dispatch("input", null);
                    });

                    return btn;
                },' : ''}}
            })"
            id="{{ $name }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->whereStartsWith('wire:model') }}
            {{ $attributes->whereStartsWith('autofocus') }}
            {{ $attributes->whereStartsWith('required') }}
            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5"
        />

        <div class="absolute inset-y-0 right-0 flex items-center justify-center pr-3 pointer-events-none">
            <x-svgs.calendar class="w-5 h-5" />
        </div>
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/litepicker@v2.0.x/dist/litepicker.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    @endpush
@endonce
