@props([
    'status' => 'active'
])

@switch($status)
    @case('active')
        <span class="text-xs bg-green-100 text-green-600 py-1 inline-block w-20 rounded text-center">Active</span>
        @break
    @case('Inactive')
        <span class="text-xs bg-gray-100 text-gray-600 py-1 inline-block w-20 rounded text-center">Inactive</span>
        @break

    @default
        <span class="text-xs bg-gray-100 text-gray-600 py-1 inline-block w-20 rounded text-center">In progress</span>
@endswitch

