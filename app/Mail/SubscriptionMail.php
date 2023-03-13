<?php
 
namespace App\Mail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class SubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $subject="Welcome to NeoStaff";
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
		$user_id = Auth::user()->id;
			
	//	$free = DB::select('SELECT stripe_status FROM subscriptions WHERE user_id = "'.$user_id.'"AND stripe_status = "trialing"');
		//$status_trial = $free[0]->stripe_status;
		
		//if(empty($status_trial)){
			return $this->view('emails.subscription.subscription');
		//}else{
		//	return $this->view('emails.subscription.freetrial');	
	//	}
    }
}