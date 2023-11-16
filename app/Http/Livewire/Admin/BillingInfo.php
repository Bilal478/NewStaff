<?php

namespace App\Http\Livewire\Admin;

use App\Models\Account;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;
use Stripe\Price;
use Stripe\Stripe;
// use Stripe\Invoice;
// use Stripe\SetupIntent;
// use Stripe\PaymentIntent;
// use Stripe\Subscription as StripeSubscription;

class BillingInfo extends Component
{
    public function render()
    {
        $accounts = Account::whereNull('deleted_at')->get();
        $activeMembers=Subscription::where('stripe_status' , 'active')->get();
        $lastTransactionRecord = DB::table('transaction_log')
        ->orderBy('created_at', 'desc')
        ->first();
        $lastTransaction=date('Y-m-d', strtotime($lastTransactionRecord->created_at));

        $currentDayAmount = $this->currentDayBilledAmount();
        $currentMonthAmount = $this->currentMonthBilledAmount();
        $currentMonthCanceledAmount = $this->currentMonthCanceledAmount();
        return view('livewire.admin.billing-info', compact('accounts', 'activeMembers', 'lastTransaction', 'currentDayAmount', 'currentMonthAmount', 'currentMonthCanceledAmount'))
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }

    public function currentMonthBilledAmount(){
        Stripe::setApiKey(config('services.stripe.secret'));
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $lastDayOfMonth = Carbon::now()->endOfMonth();

        $currentMonthTransactions = DB::table('transaction_log')->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
        ->whereIn('action', ['subscription_user', 'buy_seats'])
        ->get();

        $totalAmount = 0;
        // $price = StripeSubscription::retrieve('sub_1OC6V1AdgPnFPgwGGRIZDXO8');
        // dd($price);
        // $price = PaymentIntent::retrieve('pi_3OCzm0AdgPnFPgwG0XUcCzqK');
        // dd($price);
        foreach ($currentMonthTransactions as $transaction) {
          $subscription=Subscription::where('id',$transaction->subscription_id)->first();
          if($subscription){
          $priceId = $subscription->stripe_price;
    
          // Retrieve price details from Stripe
          $price = Price::retrieve($priceId);
          $amount = $price->unit_amount / 100; // Convert cents to dollars
          }
          // If quantity is greater than 1, multiply by quantity
           if ($transaction->quantity > 1) {
              $amount *= $transaction->quantity;
            }

          $totalAmount += $amount;
        }
        return $totalAmount;
    }

    public function currentDayBilledAmount(){
        Stripe::setApiKey(config('services.stripe.secret'));
        $currentDay = Carbon::now()->toDateString();
        $currentDayTransactions = DB::table('transaction_log')->whereDate('created_at', $currentDay)
        ->whereIn('action', ['subscription_user', 'buy_seats'])
        ->get();

        $totalAmount = 0;

        foreach ($currentDayTransactions as $transaction) {
          $subscription=Subscription::where('id',$transaction->subscription_id)->first();
          $priceId = $subscription->stripe_price;
          // dd($priceId);
    
          // Retrieve price details from Stripe
          $price = Price::retrieve($priceId);
          $amount = $price->unit_amount / 100; // Convert cents to dollars

          // If quantity is greater than 1, multiply by quantity
           if ($transaction->quantity > 1) {
              $amount *= $transaction->quantity;
            }

          $totalAmount += $amount;
        }
       return $totalAmount;
   }
   public function currentMonthCanceledAmount(){
    Stripe::setApiKey(config('services.stripe.secret'));
    $firstDayOfMonth = Carbon::now()->startOfMonth();
    $lastDayOfMonth = Carbon::now()->endOfMonth();

    $currentMonthCanceledTransactions = DB::table('transaction_log')->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
    ->whereIn('action', ['cancel_subscription', 'delete_seats'])
    ->get();

    $totalAmount = 0;

    foreach ($currentMonthCanceledTransactions as $transaction) {
      $subscription=Subscription::where('id',$transaction->subscription_id)->first();
      if($subscription){
        $priceId = $subscription->stripe_price;
  
        // Retrieve price details from Stripe
        $price = Price::retrieve($priceId);
        $amount = $price->unit_amount / 100; // Convert cents to dollars
        }
      // If quantity is greater than 1, multiply by quantity
       if ($transaction->quantity > 1) {
          $amount *= $transaction->quantity;
        }

      $totalAmount += $amount;
    }
    return $totalAmount;
} 

}
