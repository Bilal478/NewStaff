<x-modals.small x-on:open-reset-password.window="open = true" x-on:close-reset-password.window="open = false">
    <h3 class="text-xl font-bold mb-4">Reset Password</h3>
    <form wire:submit.prevent="resetPassword">
    <x-inputs.text
    wire:model.lazy="password"
    label="New Password"
    name="password"
    type="password"
    />
    <x-inputs.text
    wire:model.lazy="confirm_password"
    label="Confirm New Password"
    name="confirm_password"
    type="password"
    />
    <div class="mt-6">
        <span class="block w-full rounded-md shadow-sm">
            <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out">
                Reset Password
            </button>
        </span>
    </div>
</form>
</x-modals.small>
