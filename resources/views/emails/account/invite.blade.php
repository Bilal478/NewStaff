<?php
use App\Models\User;

$user = Auth::user();

$user_email = $user->email;

?>

@component('mail::message')

You have been invited to join the  {{ $accountName }} team owned by <?php echo $user_email; ?>

on NeoStaff. To get started, accept the invite below: 

@component('mail::button', ['url' => $url])
Accept Invitation
@endcomponent



Joining the team will give you access to the team's dashboard, including information 
about projects, tasks, teams, and more.<br>      
You can find answers to most questions and get in touch with us at 
<br>
<a href="https://neostaff.app/support">https://neostaff.app/support.</a> We’re here to help you at any step along the way.                  

Yours,<br>  
 The NeoStaff Team        

@endcomponent
