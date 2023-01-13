<button {{ $attributes->merge(['class' => 'text-sm flex items-center rounded-md bg-red-600 text-white px-6 py-2 hover:bg-red-500 focus:outline-none active:bg-red-700 transition duration-150 ease-in-out']) }}>
    {{ $slot }}
</button>
