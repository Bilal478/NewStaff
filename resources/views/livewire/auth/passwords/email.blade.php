@section('title', 'Reset password')

<div style="font-family: Roboto,ui-sans-serif,system-ui;" >
    
	<div class="logo-neostaff">
        <a href="{{ route('home') }}">
            <x-logo  class="w-auto h-16 mx-auto text-indigo-600" />
        </a>
	</div>
	<div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-2xl sm:text-3xl font-bold text-center text-gray-600 leading-9">
            Reset password
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            @if ($emailSentMessage)
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="ml-3">
                            <p class="text-sm leading-5 font-medium text-green-800">
                                {{ $emailSentMessage }}
                            </p>
                        </div>
                    </div>
                </div>
                <br>
                <a href="{{ route('home') }}" style="float: right;" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    Go to Login page
                 </a>
            @else
                <form wire:submit.prevent="sendResetPasswordLink">
                    <div>
                        <label for="email" class="block text-sm  text-gray-500 leading-5">
                            Email address
                        </label>

                        <div class="mt-1 rounded-md shadow-sm">
                            <input wire:model.lazy="email" 
								id="email" 
								name="email" 
								type="email"
								placeholder="kirk@enterprise.com"
								required 
								class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('email') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red @enderror" 
							/>
                        </div>

                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 ">
                        <span class="block w-full rounded-md shadow-sm">
                            <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out">
                                Send password reset link
                            </button>
                        </span>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
<style>
	.logo-neostaff{
		display: flex;
		align-items: center;
		justify-content: center;
	}
</style>
