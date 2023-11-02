<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Account;
use App\Models\Subscription;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Stripe\Price;
use Stripe\Stripe;

class TransactionHistory extends Component
{
    use WithPagination;
    public $search;
    public $startDate;
    public $endDate;
    public function render()
    {
        $transactionsData = $this->transactionsData();
        return view('livewire.admin.transaction-history',compact('transactionsData'))
        ->layout('layouts.admin', ['title' => 'Transaction History']);
    }
    public function transactionsData()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $startDate = $this->startDate ? Carbon::parse($this->startDate)->format('Y-m-d') : null;
        $endDate = $this->endDate ? Carbon::parse($this->endDate)->format('Y-m-d') : null;
      
        $query = DB::table('transaction_log')
        ->join('users', 'users.id', '=', 'transaction_log.user_id')
        ->join('accounts', 'accounts.id', '=', 'transaction_log.account_id')
        ->select('transaction_log.*', 'users.firstname', 'users.lastname', 'accounts.name')
        ->orderBy('transaction_log.created_at', 'desc');
        if ($startDate && $endDate) {
          if($startDate===$endDate){
            $query->whereDate('transaction_log.created_at', $startDate);
          }else{
            $query->whereBetween('transaction_log.created_at', [$startDate, $endDate]);
          }
        } elseif ($startDate) {
            $query->whereDate('transaction_log.created_at', '=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('transaction_log.created_at', '=', $endDate);
        }
       
        if ($this->search) {
          if (strpos($this->search, ',') !== false) {
            $array = explode(',', $this->search);

            $array = array_map(function($item) {
                return str_replace(' ', '_', strtolower($item));
            }, $array);
            $query->whereIn('transaction_log.action', $array);
        }
        else{
          $query->where(function($query) {
              $query->where('users.firstname', 'like', '%' . $this->search . '%')
                  ->orWhere('users.lastname', 'like', '%' . $this->search . '%')
                  ->orWhere('transaction_log.action', 'like', '%' . $this->search . '%')
                  ->orWhere('accounts.name', 'like', '%' . $this->search . '%');
          });
        }    
      } 
        $transactionRecords = $query->paginate(10);
        // dd($transactionRecords);
        $allData=[]; // Initialize $allData as an empty array
      
        foreach ($transactionRecords as $record) {
          $totalAmount = 0;
          $subscription = Subscription::where('id', $record->subscription_id)->first();
          $user = DB::table('users')->where('id',$record->user_id)->first();
          $account = DB::table('accounts')->where('id',$record->account_id)->first();
      
          $userName = $user->firstname.' '.$user->lastname;
          $accountName = $account->name;
      
          // Retrieve price details from Stripe
          $price = Price::retrieve($subscription->stripe_price);
          $amount = $price->unit_amount / 100; // Convert cents to dollars
           if ($record->quantity > 1) {
              $amount *= $record->quantity;
            }
          $totalAmount += $amount;
          $allData[] = [
            'userName' =>  $userName,
            'accountName' =>  $accountName,
            'amount'   =>  $totalAmount,
            'action'   =>  $record->action,
            'created_at'   =>  $record->created_at
          ];
        }
        return [$allData, $transactionRecords];
    } 
    public function updatedSearch()
    {
    $this->resetPage();
    }
    public function mount()
    {
    $this->startDate = Request::query('startDate');
    $this->endDate = Request::query('endDate');
    $this->search = Request::query('search');
    }
}