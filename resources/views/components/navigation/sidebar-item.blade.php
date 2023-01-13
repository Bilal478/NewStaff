@props([
    'route' => 'accounts.dashboard',
    'img' => '',
])

<li class="px-2 py-1">
    <a
        href="{{ route($route) }}"
        class="px-3 py-2 text-sm rounded-md flex items-center {{ Request::routeIs($route . '*') ? 'bg-gray-100 text-blue-600' : ' text-gray-500 hover:bg-gray-100 hover:text-blue-600' }}"
    >
        <x-dynamic-component :component="$img" class="w-5 h-5 mr-2" />
       {{ $slot }}
    </a>
</li>
