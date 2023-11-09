<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManagerDailyWorkSummaryEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $accountName='';
    public $userName='';
    public $data='';
    public $totalTime;
    public $totalUsers;
    public $averageActivity;
    public $bottom5Members;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($Data,$accountName,$userName,$totalTime,$totalUsers,$averageActivity,$bottom5Members)
    {
        $this->accountName = $accountName;
        $this->userName = $userName;
        $this->data = $Data;
        $this->totalTime = $totalTime;
        $this->totalUsers = $totalUsers;
        $this->averageActivity = $averageActivity;
        $this->bottom5Members = $bottom5Members;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(' Daily Team Work Summary for '.$this->accountName)
        ->view('emails.manager-daily-work-summary.manager-daily-work-summary',
        [
            'data' => $this->data,
            'accountName'=>$this->accountName,
            'userName'=>$this->userName,
            'totalTime'=>$this->totalTime,
            'totalUsers'=>$this->totalUsers,
            'averageActivity'=>$this->averageActivity,
            'bottom5Members'=>$this->bottom5Members,
        ]);
    }
}
