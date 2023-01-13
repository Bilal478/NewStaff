<button {{ $attributes->merge(['class' => 'text-sm flex items-center rounded-md bg-blue-600 text-white px-6 py-2 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out']) }}>
    {{ $slot }}
</button>
