@props([
    'name',
    'label',
    'type' => 'text',
    'placeholder' => '',
    'height' => 'h-24',
])

<div
    x-data="{ content: $wire.entangle('{{ $attributes->whereStartsWith('wire:model')->first() }}').defer, limit: 100 }"
    class="pb-6 {{ $attributes->get('class') }}"
>
    <label for="{{ $name }}" class="block text-sm text-gray-500 leading-5">
        {{ $label }}
        @if ($attributes->has('required'))
            <span class="text-red-500 text-xs">*</span>
        @endif
    </label>

    <div class="mt-1 rounded-md shadow-sm">
        <textarea
            id="{{ $name }}"
            x-model.debounce.300ms="content"
            maxlength="100"
            placeholder="{{ $placeholder }}"
            {{ $attributes->whereStartsWith('wire:model') }}
            {{ $attributes->whereStartsWith('autofocus') }}
            {{ $attributes->whereStartsWith('required') }}
            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 text-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm leading-5 {{ $height }} @error('name') border-red-300 focus:border-red-300 focus:ring-red @enderror"
        ></textarea>

        <div class="text-right text-xs mt-1 text-gray-500" x-text="`${content.length} / ${limit}`"></div>
        <template x-if="content.length >= limit">
            <div class="text-red-500 text-xs mt-1">You’ve reached the 1000 character limit.</div>
        </template>
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
