<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    protected function handleInvoice_Paid($event)
    {
        // Retrieve the invoice object from the event
        $invoice = $event->data->object;

        // Retrieve other relevant details from the invoice object
        $invoiceId = $invoice->id;
        $subscriptionId = $invoice->subscription;
        $prorationAmount = $invoice->proration;
        $totalAmount = $invoice->total/100;
        $subscription=Subscription::where('stripe_id',$subscriptionId)->first();
        $user=User::where('id',$subscription->user_id)->first();
        $account=Account::where('owner_id',$user->id)->first();
        DB::table('transaction_log')->insert([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'subscription_id' => $subscription->id,
            'amount' =>  $totalAmount,
            'created_at' => now(),
            'updated_at' => now(),
            'invoice_id' => $invoiceId,
        ]);

        // Log the invoice paid event and additional details
        Log::info("Invoice Paid event received. Invoice ID: $invoiceId, Subscription ID: $subscriptionId");
        Log::info("Proration Amount: $prorationAmount, Total Amount: $totalAmount");

        return $this->successMethod();
    }

}