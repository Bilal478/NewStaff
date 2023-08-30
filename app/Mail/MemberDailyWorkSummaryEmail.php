<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemberDailyWorkSummaryEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $accountName='';
    public $userName='';
    public $data='';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($Data,$accountName,$userName)
    {
        $this->accountName = $accountName;
        $this->userName = $userName;
        $this->data = $Data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(' Daily Team Work Summary for '.$this->accountName)
        ->view('emails.member-daily-work-summary.member-daily-work-summary',['data' => $this->data,'accountName'=>$this->accountName,'userName'=>$this->userName]);
    }
}
