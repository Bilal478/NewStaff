<?php

namespace App\Http\Livewire\Admin\TransactionHistory;

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransactionsShow extends Component
{
    public $transactionId;
    public $transactionAmount;
    protected $listeners = [
        'transactionShow' => 'show',
    ];
    public function render()
{
    $record = DB::table('transaction_log')->where('id', $this->transactionId)->first();
    $subscription = null;
    $stripeId = null;

    if ($record) {
        $subscription = Subscription::find($record->subscription_id);
        if ($subscription) {
            $stripeId = $subscription->stripe_id;
        }
    }

    return view('livewire.admin.transaction-history.transactions-show', [
        'transactionRecord' => $record,
        'transactionAmount' => $this->transactionAmount,
        'stripeId' => $stripeId,
    ]);
}

    public function show($transactionId,$transactionAmount)
    {
        $this->transactionId=$transactionId;
        $this->transactionAmount=$transactionAmount;
        $this->dispatchBrowserEvent('open-transactions-show');
    }
    
}
