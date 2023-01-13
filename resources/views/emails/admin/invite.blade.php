@component('mail::message')
You have been invited to join Neostaff as an admin!

You may accept this invitation by clicking the button below:

@component('mail::button', ['url' => $url])
Accept Invitation
@endcomponent

If you did not expect to receive an invitation to join, you may discard this email.

Thanks,<br>
Neostaff
@endcomponent
