<?php
use App\Models\Account;
use App\Models\User;
use App\Models\AccountInvitation;

$account = AccountInvitation::get();

$account = DB::select('SELECT user_id FROM account_invitations WHERE email = "'.$email.'"');
$user_id = $account[0]->user_id;

$invitation = DB::select('SELECT account_id FROM account_invitations WHERE user_id = "'.$user_id.'"');

$subscription = DB::select('SELECT quantity FROM subscriptions WHERE user_id = "'.$user_id.'"AND stripe_status != "canceled"');

$users = DB::select('SELECT * FROM account_user WHERE account_id = "'.$invitation[0]->account_id.'"');


$seats = count($users );

$quantity = $subscription[0]->quantity;

$invited = count($invitation);

$available_seats = $quantity - $seats;



?>

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="inline-block">
                <x-logo/>
            </a>
        </div>

        <h2 class="mt-6 text-2xl sm:text-3xl font-bold text-center text-gray-600 leading-9">
            Create a new account
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="register">
                <div class="flex items-center">
                    <div class="w-1/2">
                        <x-inputs.text
                            class="pr-5"
                            wire:model.lazy="firstName"
                            label="First Name"
                            name="firstName"
                            placeholder="James T"
							required
                        />
                    </div>
                    <div class="w-1/2">
                        <x-inputs.text
                            class="pl-5"
                            wire:model.lazy="lastName"
                            label="Last Name"
                            name="lastName"
                            placeholder="Kirk"
							required
                        />
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-1/2">
                        <x-inputs.text
                            class="pr-5"
                            wire:model.lazy="email"
                            label="Email address"
                            name="email"
                            type="email"
                            disabled
							required
                        />
                    </div>

                    <div class="w-1/2">
                        <x-inputs.text
                            class="pl-5"
                            wire:model.lazy="password"
                            label="Password"
                            name="password"
                            type="password"
							required

                        />
                    </div>
                </div>
				<?php
				if($available_seats > 0 ){
				?>
					<x-buttons.blue-full
						text="Create a new account"
						type="submit"
					/>
					
				<?php
				}else{
				?>
				
				<div class="alert">
					<span class="closebtn" onclick="this.parentElement.style.display='none';"></span> 
					<strong>There are no available seats.</strong> Please contact your company administrator to add more seats and then try again.
				</div>
				<?php } ?>
            </form>
        </div>
    </div>
</div>
<style>
.alert {
  padding: 20px;
  background-color: #f44336;
  color: white;
}





</style>
