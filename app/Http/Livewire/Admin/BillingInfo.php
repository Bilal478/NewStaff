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
        $lastActiveMember = DB::table('transaction_log')
        ->orderBy('created_at', 'desc') // Assuming there's a created_at timestamp
        ->first();
        // dd( $lastActiveMember );

        $currentDayAmount = $this->currentDayBilledAmount();
        $currentMonthAmount = $this->currentMonthBilledAmount();
        $currentMonthCanceledAmount = $this->currentMonthCanceledAmount();
        $transactionsData = $this->transactionsData();
        // dd($this->transactionHistory());
        return view('livewire.admin.billing-info', compact('accounts', 'activeMembers', 'lastActiveMember', 'currentDayAmount', 'currentMonthAmount', 'currentMonthCanceledAmount', 'transactionsData'))
            ->layout('layouts.admin', ['title' => 'Billing Info']);
    }

    public function currentMonthBilledAmount(){
        Stripe::setApiKey(config('services.stripe.secret'));
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $lastDayOfMonth = Carbon::now()->endOfMonth();

        $subscriptions = Subscription::whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
        ->where('stripe_status', '!=', 'canceled')
        ->get();

        $totalAmount = 0;

        foreach ($subscriptions as $subscription) {
          $priceId = $subscription->stripe_price;
    
          // Retrieve price details from Stripe
          $price = Price::retrieve($priceId);
          $amount = $price->unit_amount / 100; // Convert cents to dollars

          // If quantity is greater than 1, multiply by quantity
           if ($subscription->quantity > 1) {
              $amount *= $subscription->quantity;
            }

          $totalAmount += $amount;
        }
        return $totalAmount;
    }

    public function currentDayBilledAmount(){
        Stripe::setApiKey(config('services.stripe.secret'));
        $currentDay = Carbon::now()->toDateString();
        $subscriptions = Subscription::whereDate('created_at', $currentDay)
        ->where('stripe_status', '!=', 'canceled')
        ->get();

        $totalAmount = 0;

        foreach ($subscriptions as $subscription) {
          $priceId = $subscription->stripe_price;
    
          // Retrieve price details from Stripe
          $price = Price::retrieve($priceId);
          $amount = $price->unit_amount / 100; // Convert cents to dollars

          // If quantity is greater than 1, multiply by quantity
           if ($subscription->quantity > 1) {
              $amount *= $subscription->quantity;
            }

          $totalAmount += $amount;
        }
       return $totalAmount;
   }
   public function currentMonthCanceledAmount(){
    Stripe::setApiKey(config('services.stripe.secret'));
    $firstDayOfMonth = Carbon::now()->startOfMonth();
    $lastDayOfMonth = Carbon::now()->endOfMonth();

    $subscriptions = Subscription::whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
    ->where('stripe_status', '=', 'canceled')
    ->get();

    $totalAmount = 0;

    foreach ($subscriptions as $subscription) {
      $priceId = $subscription->stripe_price;

      // Retrieve price details from Stripe
      $price = Price::retrieve($priceId);
      $amount = $price->unit_amount / 100; // Convert cents to dollars

      // If quantity is greater than 1, multiply by quantity
       if ($subscription->quantity > 1) {
          $amount *= $subscription->quantity;
        }

      $totalAmount += $amount;
    }
    return $totalAmount;
} 

public function transactionsData(){
  Stripe::setApiKey(config('services.stripe.secret'));

  $transactionRecords = DB::table('transaction_log')->orderBy('id','desc')->paginate(3);
  $allData=[]; // Initialize $allData as an empty array

  foreach ($transactionRecords as $record) {
    $totalAmount = 0;
    $subscriptionId = $record->subscription_id;
    $subscription = Subscription::where('id', $record->subscription_id)->first();
    $user = DB::table('users')->where('id',$record->user_id)->first();
    // dd($user->firstname);
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
