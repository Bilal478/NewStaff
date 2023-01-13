<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="inline-block">
                <x-logo/>
            </a>
        </div>

        <h2 class="mt-6 text-2xl sm:text-3xl font-bold text-center text-gray-600 leading-9">
            Create a new account
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="register">
                <div class="flex items-center">
                    <div class="w-1/2">
                        <x-inputs.text
                            class="pr-5"
                            wire:model.lazy="firstName"
                            label="First Name"
                            name="firstName"
                            placeholder="James T"
                        />
                    </div>
                    <div class="w-1/2">
                        <x-inputs.text
                            class="pl-5"
                            wire:model.lazy="lastName"
                            label="Last Name"
                            name="lastName"
                            placeholder="Kirk"
                        />
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-1/2">
                        <x-inputs.text
                            class="pr-5"
                            wire:model.lazy="email"
                            label="Email address"
                            name="email"
                            type="email"
                            disabled
                        />
                    </div>

                    <div class="w-1/2">
                        <x-inputs.text
                            class="pl-5"
                            wire:model.lazy="password"
                            label="Password"
                            name="password"
                            type="password"
                        />
                    </div>
                </div>

                <x-buttons.blue-full
                    text="Create a new account"
                    type="submit"
                />
            </form>
        </div>
    </div>
</div>
