<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="inline-block">
                <x-logo/>
            </a>
        </div>

        <h2 class="mt-6 text-2xl sm:text-3xl font-bold text-center text-gray-600 leading-9">
            Account Users List
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <span><b>Select users to remove</b></span>

        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="submitForm">
                @foreach($users as $user)
                    <div class="flex items-center justify-between py-1">
                        <label class="text-sm text-gray-900">{{ $user->firstname }} {{ $user->lastname }}</label>
                        <input type="checkbox" wire:model="selectedUsers.{{ $user->id }}" wire:key="checkbox-{{ $user->id }}" id="user-{{ $user->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                    </div>
                @endforeach

                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out">
                            Next
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
