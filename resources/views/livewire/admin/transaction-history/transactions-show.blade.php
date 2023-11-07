<x-modals.small x-on:open-transactions-show.window="open = true" x-on:close-transactions-show.window="open = false">
        <div class="p-4">
            @if ($transactionRecord)
            <h3 class="text-xl font-bold mb-4">Transaction Details</h3>
            <div class="border border-dark rounded p-5">
            <div class="flex justify-between mb-2">
                <span>ID:</span>
                <span>{{ $transactionRecord->id }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span>Amount:</span>
                <span>${{ $transactionAmount }}.00</span>
            </div>
            <div class="flex justify-between mb-2">
                <span>Action:</span>
                <span>
                    @if ($transactionRecord->action=='delete_seats')
                    Delete Seats
                    @elseif ($transactionRecord->action=='cancel_subscription')
                    Cancel Subscription
                    @elseif ($transactionRecord->action=='buy_seats')
                    Buy Seats
                    @else
                    Subscription User   
                    @endif
                </span>
            </div>
            <div class="flex justify-between mb-2">
                <span>Date:</span>
                <span>{{ $transactionRecord->created_at }}</span>
            </div>
            @endif
            @if ($stripeId)
            <div class="flex justify-between mb-2">
                <span>Stripe ID:</span>
                <a href="https://stripe.com/dashboard/payments/{{ $stripeId }}" target="_blank" class="text-blue-500">{{ $stripeId }}</a>
            </div>
            </div>
            @endif
        </div>     
</x-modals.small>
