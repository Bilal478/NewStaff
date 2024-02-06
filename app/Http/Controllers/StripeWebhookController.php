<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User; // Make sure to import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends CashierWebhookController
{
    /**
     * Handle Stripe subscription updated event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleSubscriptionUpdated(Request $request): Response
    {
        // Get the payload from the request
        $payload = $request->all();

        // Check if the status transitioned from trialing to active
        $oldStatus = $payload['data']['previous_attributes']['status'] ?? null;
        $newStatus = $payload['data']['object']['status'] ?? null;

        if ($oldStatus !== $newStatus) {
            // Retrieve the subscription based on the Stripe subscription ID
            $stripeSubscriptionId = $payload['data']['object']['id'];
            $subscription = Subscription::where('stripe_id', $stripeSubscriptionId)->first();
        
            if ($subscription) {
                // Update the subscription status in the database
                $subscription->update(['stripe_status' =>  $newStatus]);
        
                // Log the update
                Log::info('Subscription status of subscription id = ' . $subscription->id . ' is updated in the database.');
            } else {
                Log::warning('Subscription not found for the given Stripe subscription ID.');
            }
        }
        // Your other logic here

        return $this->successMethod();
    }
}
