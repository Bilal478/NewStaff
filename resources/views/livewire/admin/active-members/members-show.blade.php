<x-modals.small x-on:open-member-show.window="open = true" x-on:close-member-show.window="open = false">
    <div class="p-4">
        @if ($user)
        <h3 class="text-xl font-bold mb-4">Member Details</h3>
        <div class="border border-dark rounded p-5">
        <div class="flex justify-between mb-2">
            <span>User Name:</span>
            <span>{{ $user->firstname }} {{ $user->lastname }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span>Email</span>
            <span>{{  $user->email }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span>Companies:</span>
            <span>
                @if ($accounts->isNotEmpty())
                @foreach($accounts as $index => $account) 
                    {{ $account->name }}
                    @if ($index < $accounts->count() - 1)
                        ,
                    @endif
                @endforeach
                @else
                No Company
                @endif
            </span>
        </div>
        <div class="flex justify-between mb-2">
            <span>Registration Date:</span>
            <span>{{ $user->created_at }}</span>
        </div>
        @endif
       
    </div>     
</x-modals.small>
