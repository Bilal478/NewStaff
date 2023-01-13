<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\AdminInvitation;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AdminInvitation $adminInvitation)
    {
        $this->url = URL::signedRoute('admin.invitation', ['adminInvitation' => $adminInvitation->id]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Admin Invitation')
            ->markdown('emails.admin.invite', [
                'url' => $this->url,
            ]);
    }
}
