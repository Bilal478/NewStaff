<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Symfony\Component\HttpFoundation\Response;
use Stripe\Webhook\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook as StripeWebhook;

class StripeWebhookController extends CashierWebhookController
{
    /**
     * Handle the incoming Stripe webhook request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();;
        $signature = $request->header('Stripe-Signature');

        try {
            $event = StripeWebhook::constructEvent($payload, $signature, config('services.stripe.webhook_secret'));
        } catch (SignatureVerificationException $e) {
            return response('Webhook signature verification failed.', 403);
        }

        // Dynamically call the appropriate method based on the event type
        $method = 'handle' . str_replace('.', '_', $event->type);

        if (method_exists($this, $method)) {
            return $this->{$method}($event);
        }

        // Fallback to a more generic handler
        return $this->handleEvent($event);
    }

    /**
     * Handle Stripe subscription updated event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleCustomer_Subscription_Updated($event): Response
    {
        $stripeSubscriptionId = $event->data->object->id;
        $oldStatus = $event->data->previous_attributes['status'] ?? null;
        $newStatus = $event->data->object->status ?? null;

 
         if ($oldStatus !== $newStatus && $oldStatus !== 'canceled') {
             $subscription = Subscription::where('stripe_id', $stripeSubscriptionId)->first();
             if ($subscription) {
                 $subscription->update(['stripe_status' =>  $newStatus]);
                 Log::info('Subscription status of subscription id = ' . $subscription->id . ' is updated in the database.');
             } else {
                 Log::warning('Subscription not found for the given Stripe subscription ID.');
             }
         }
        return $this->successMethod();
    }

    /**
     * Handle customer.subscription.deleted event.
     *
     * @param  \Stripe\Event  $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomer_Subscription_Deleted($event): Response
    {
        // Retrieve the subscription based on the Stripe subscription ID
        $stripeSubscriptionId = $event->data->object->id;
        $stripeSubscriptionEnded = $event->data->object->ended_at;
        $ends_at = date('Y-m-d H:i:s', $stripeSubscriptionEnded);
        $subscription = Subscription::where('stripe_id', $stripeSubscriptionId)->first();
        if ($subscription) {
            $subscription->update(['stripe_status' =>  'canceled','ends_at' => $ends_at]);
            Log::info('Subscription id = ' . $subscription->id . ' is marked as canceled.');
        } else {
            Log::warning('Subscription not found for the given Stripe subscription ID.');
        }
        return $this->successMethod();
    }
}