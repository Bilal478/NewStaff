<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="inline-block">
                <x-logo/>
            </a>
        </div>

        <h2 class="mt-6 text-2xl sm:text-3xl font-bold text-center text-gray-600 leading-9">
            Accept Invitation
        </h2>
        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w font-medium">
           {{$email}}
        </p>
        @if(session()->get('account_not_found'))
        <p class="mt-2 text-sm text-center text-red-600 leading-5 max-w">
           You can't access the dashboard.
        </p>
        @endif
        {{session()->forget('account_not_found')}}
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="update">
                <x-inputs.text
                    wire:model.lazy="firstname"
                    label="First Name"
                    name="firstname"
                    type="text"
                    placeholder="john"
                />
                <x-inputs.text
                    wire:model.lazy="lastname"
                    label="Last Name"
                    name="lastname"
                    type="text"
                    placeholder="doe"
                />
                <x-inputs.text
                    wire:model.lazy="password"
                    label="Password"
                    name="password"
                    type="password"
                />
                <x-inputs.text
                    wire:model.lazy="confirm_password"
                    label="Confirm Password"
                    name="confirm_password"
                    type="password"
                />
                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out">
                            Save It
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
