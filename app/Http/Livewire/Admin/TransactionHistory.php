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
          $query->where(function($query) {
              $query->where('users.firstname', 'like', '%' . $this->search . '%')
                  ->orWhere('users.lastname', 'like', '%' . $this->search . '%')
                  ->orWhere('accounts.name', 'like', '%' . $this->search . '%');
          });
      } 
        $transactionRecords = $query->paginate(10);
        // dd($transactionRecords);
        $allData=[]; // Initialize $allData as an empty array
      
        foreach ($transactionRecords as $record) {
          $user = DB::table('users')->where('id',$record->user_id)->first();
          $account = DB::table('accounts')->where('id',$record->account_id)->first();
      
          $userName = $user->firstname.' '.$user->lastname;
          $accountName = $account->name;
      
          $allData[] = [
            'id' =>  $record->id,
            'userName' =>  $userName,
            'accountName' =>  $accountName,
            'amount'   =>  $record->amount,
            'created_at'   =>  $record->created_at,
            'invoice_id'   =>  $record->invoice_id
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