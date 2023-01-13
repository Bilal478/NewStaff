@props([
    'avatar' => '',
    'large' => false,
])

@if ($avatar)
    <div class="{{ $large ? 'h-10 w-10' : 'w-8 h-8' }} bg-blue-300 rounded-full flex items-center justify-center object-cover overflow-hidden flex-shrink-0">
        <img src="https://i.pravatar.cc/50?img=67">
    </div>
@else
    <div class="{{ $large ? 'h-10 w-10' : 'w-8 h-8' }} bg-blue-300 rounded-full flex items-center justify-center flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </div>
@endif
