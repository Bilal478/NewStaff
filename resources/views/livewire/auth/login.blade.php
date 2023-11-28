<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="inline-block">
                <x-logo/>
            </a>
        </div>

        <h2 class="mt-6 text-2xl sm:text-3xl font-bold text-center text-gray-600 leading-9">
            Sign in to your account
        </h2>
        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            Or
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                create a new account
            </a>
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
            <form wire:submit.prevent="authenticate">
                {{-- @if($subscriptionExpired)
                 <div class="alert alert-warning text-xs">
                  {{ trans('Subscription Period Ended') }}
                  <a href="{{ route('buy_subscription') }}" class="float-right font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150" href="#">Buy a Subscription</a>
                 </div>
                 @endif --}}
                 @if($subscriptionExpired)
                 <div class="alert alert-warning text-xs">
                 {{ trans('Subscription Period Ended') }}
                   <a wire:click.prevent="buySubscription" class="float-right font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150" href="#">Buy a Subscription</a>
                 </div>
                @endif
                <x-inputs.text
                    wire:model.lazy="email"
                    label="Email address"
                    name="email"
                    type="email"
                    placeholder="kirk@enterprise.com"
                />

                <x-inputs.text
                    wire:model.lazy="password"
                    label="Password"
                    name="password"
                    type="password"
                />

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input wire:model.lazy="remember" id="remember" type="checkbox" class="form-checkbox w-4 h-4 text-blue-600 transition duration-150 ease-in-out" />
                        <label for="remember" class="block ml-2 text-sm text-gray-900 leading-5">
                            Remember
                        </label>
                    </div>
                    
                    <div class="text-sm leading-5">
                        <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                            Forgot your password?
                        </a>
                    </div>              
                    
                </div>

                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out">
                            Sign in
                        </button>
                    </span>
                </div>
            </form>
            <div class="w-full mt-3">
                <i class="fa fa-lock"></i>
                <label for="vehicle1"><small>Subject to HIPAA? See our <b><a  class="text-blue-600" target="_blank" href="/hipaa">BBA</a></b></small></label>
                </div>
        </div>
    </div>
</div>
