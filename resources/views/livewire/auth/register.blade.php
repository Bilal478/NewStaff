{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
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

        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            Or
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                sign in to your account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="register">
                <div class="pb-10">
                    <x-inputs.text
					
                        class="pb-10 border-b"
                        wire:model.lazy="accountName"
                        label="Company Name"
                        name="accountName"
                        placeholder="Starfleet"
                        autofocus required
                    />
                </div>

                <div class="flex items-center flex-wrap">
                    <div class="w-full md:w-1/2">
                        <x-inputs.text
                            class="md:pr-5"
                            wire:model.lazy="firstName"
                            label="First Name"
                            name="firstName"
                            placeholder="James T"
							required
                        />
                    </div>
                    <div class="w-full md:w-1/2">
                        <x-inputs.text
                            class="md:pl-5"
                            wire:model.lazy="lastName"
                            label="Last Name"
                            name="lastName"
                            placeholder="Kirk"
							required
                        />
                    </div>
                </div>

                <div class="flex items-center flex-wrap">
                    <div class="w-full md:w-1/2">
                        <x-inputs.text
                            class="md:pr-5"
                            wire:model.lazy="email"
                            label="Email address"
                            name="email"
                            type="email"
                            placeholder="kirk@enterprise.com"
							required
                        />
                    </div>

                    <div class="w-full md:w-1/2">
                        <x-inputs.text
                            class="md:pl-5"
                            wire:model.lazy="password"
                            label="Password"
                            name="password"
                            type="password"
							required
                        />
                    </div>
                    <div class="w-full mb-3">
                    <input type="checkbox" id="policy" name="policy" value="policy" required>
                    <label for="vehicle1"><small> I agree to the <b><a  class="text-blue-600" target="_blank" href="/terms-and-conditions">Terms</a>, Privacy Policy</b> and <b>DPA</b><b class="text-red-600">*</b> </small></label>
                    </div>
                </div>
                <x-buttons.blue-full
                    text="Create a new account"
                    type="submit"
                />
            </form>
            <div class="w-full mt-3">
                <i class="fa fa-lock"></i>
                <label for="vehicle1"><small>Subject to HIPAA? Sign our <b><a  class="text-blue-600" target="_blank" href="/hipaa">BBA</a></b></small></label>
                </div>
        </div>
    </div>
</div>
<style>

</style>