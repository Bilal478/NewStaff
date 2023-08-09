<?php

namespace App\Mail;

use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\AccountInvitation;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $randomID;
    public $account;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Account $account, AccountInvitation $accountInvitation,$randomID=null)
    {
        $this->account = $account;
        // $this->url = URL::signedRoute('invitation', ['accountInvitation' => $accountInvitation->id]);
        $urlParameters = ['randomID' => $randomID];
        $this->randomID = $randomID;
        $this->url = url(route('accept-invitation', $urlParameters));

        
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$user = Auth::user();
		$name = $user->firstname;
		$subject = $name.' invited you to join the '.$this->account->name.' team on NeoStaff';
		
        return $this->subject($subject)
            ->markdown('emails.account.invite', [
                'url' => $this->url,
                'accountName' => $this->account->name,
            ]);
    }
}
