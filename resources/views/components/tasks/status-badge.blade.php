@props([
    'status' => 'completed'
])

@if ($status)
    <span class="text-xs bg-green-100 text-green-600 py-1 inline-block w-20 rounded text-center">Completed</span>
@else
    <span class="text-xs bg-gray-100 text-gray-600 py-1 inline-block w-20 rounded text-center">In progress</span>
@endif
