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
                </div>
                <div class="form-group mt-4 mb-4">
                    <div class="captcha">
                    <span>{!! captcha_img() !!}</span>
                    <button type="button" class="btn btn-danger" class="reload" id="reload">
                    ↻
                    </button>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <input id="captcha" type="text" class="form-control" wire:model.lazy="captcha" placeholder="Enter Captcha" name="captcha" required>
                    @error('captcha')
                      <div class="alert alert-danger mt-1 mb-1">Invalid Captcha</div>
                    @enderror
                    {{-- <x-inputs.text
                    class="md:pl-5"
                    wire:model.lazy="captcha"
                    label="Captcha"
                    name="captcha"
                    type="text"
                    required
                /> --}}
                </div>

                <x-buttons.blue-full
                    text="Create a new account"
                    type="submit"
                />
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<script type="text/javascript">
$('#reload').click(function () {
$.ajax({
type: 'GET',
url: 'reload-captcha',
success: function (data) {
$(".captcha span").html(data.captcha);
}
});
});
</script>
