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

class BillingInfo extends Component
{
    public function render()
    {
        $accounts = Account::whereNull('deleted_at')->get();
        $activeMembers=Subscription::where('stripe_status' , 'active')->get();
        $lastTransaction = DB::table('transaction_log')
        ->orderBy('created_at', 'desc')
        ->first();

        $currentDayAmount = $this->currentDayBilledAmount();
        $currentMonthAmount = $this->currentMonthBilledAmount();
        $currentMonthCanceledAmount = $this->currentMonthCanceledAmount();
        $transactionsData = $this->transactionsData();
        return view('livewire.admin.billing-info', compact('accounts', 'activeMembers', 'lastTransaction', 'currentDayAmount', 'currentMonthAmount', 'currentMonthCanceledAmount', 'transactionsData'))
            ->layout('layouts.admin', ['title' => 'Billing Info']);
    }

    public function currentMonthBilledAmount(){
        Stripe::setApiKey(config('services.stripe.secret'));
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $lastDayOfMonth = Carbon::now()->endOfMonth();

        $currentMonthTransactions = DB::table('transaction_log')->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
        ->whereIn('action', ['subscription_user', 'buy_seats'])
        ->get();

        $totalAmount = 0;

        foreach ($currentMonthTransactions as $transaction) {
          $subscription=Subscription::where('id',$transaction->subscription_id)->first();
          $priceId = $subscription->stripe_price;
    
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
      $priceId = $subscription->stripe_price;

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

public function transactionsData(){
  Stripe::setApiKey(config('services.stripe.secret'));

  $transactionRecords = DB::table('transaction_log')->orderBy('created_at','desc')->paginate(10);
  $allData=[]; // Initialize $allData as an empty array

  foreach ($transactionRecords as $record) {
    $totalAmount = 0;
    $subscription = Subscription::where('id', $record->subscription_id)->first();
    $user = DB::table('users')->where('id',$record->user_id)->first();

    $userName = $user->firstname.' '.$user->lastname;

    // Retrieve price details from Stripe
    $price = Price::retrieve($subscription->stripe_price);
    $amount = $price->unit_amount / 100; // Convert cents to dollars

    // If quantity is greater than 1, multiply by quantity
     if ($record->quantity > 1) {
        $amount *= $record->quantity;
      }

    $totalAmount += $amount;
    
    // Add this record to $allData
    $allData[] = [
      'userName' =>  $userName,
      'amount'   =>  $totalAmount,
      'action'   =>  $record->action,
      'created_at'   =>  $record->created_at
    ];
  }

  return [$allData, $transactionRecords];
  // return $allData;
} 

}
