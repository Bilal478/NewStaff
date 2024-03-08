<?php

use App\Models\Account;
use App\Models\User;
use App\Models\Subscription;

$account = Account::find(session()->get('account_id'));

	$owner_id_query = DB::table('account_user')
		->where('account_id', $account->id)
		->first();
	
	$subs = DB::select('SELECT * FROM account_user WHERE account_id="'.$owner_id_query->account_id.'"');	
	
	$count_subs = count($subs);

	// $user = Auth::user()->id;
	$user = $account->owner_id;

	
	$quantity_seats = DB::select('SELECT quantity FROM subscriptions WHERE user_id = "'.$user.'"AND stripe_status != "canceled"');
 	
	if($quantity_seats){
		$seats= $quantity_seats[0]->quantity;		
	}else{
		$seats=0;
	}
	

	$result=$seats-$count_subs;
	
	$plan = DB::select('SELECT name FROM subscriptions WHERE user_id = "'.$user.'"AND stripe_status != "canceled"');
	
	if($plan){
		$plan_name = $plan[0]->name;
	}else{
		$plan_name = 'test';
	}
	
?>

<?php
	if($result=='0'){
?>
		<script src="https://js.stripe.com/v3/"></script>	
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php
	}
?>	

<x-modals.small x-on:open-invite-modal.window="open = true" x-on:close-invite-modal.window="open = false">

<?php 
	if($result != '0'){
?>

    <form wire:submit.prevent="create">
        <h5 class="font-montserrat font-semibold text-lg text-gray-700 mb-6">
            Invite Member

        </h5>

        <x-inputs.text wire:model.lazy="email" label="Email Address" name="email" type="email" placeholder="johndoe@example.com" required />

        <x-inputs.select wire:model.lazy="role" label="Role" name="role" type="text" required>
            <option value="owner">Owner</option>
            <option value="manager">Manager</option>
            <option value="member">Member</option>
        </x-inputs.select>
		<div>
			<label for="department">Department:</label>
			<select wire:model="selectedDepartment" id="department">
				<option value="">Select a department</option>
				@foreach ($departments as $department)
					<option value="{{ $department->id }}">{{ $department->title }}</option>
				@endforeach
			</select>
		</div>
		
		<div>
			<label for="project">Project:</label>
			<select wire:model="selectedProject" id="project">
				<option value="">Select a project</option>
				@foreach ($projects as $project)
					<option value="{{ $project->id }}">{{ $project->title }}</option>
				@endforeach
			</select>
		</div>
	<?php
		}
	?>
	
	<?php
	
		if($result > '0'){
	?>
	
        <div class="flex justify-end mt-2">
            <x-buttons.blue-inline type="submit">
                Invite Member
            </x-buttons.blue-inline>
        </div>
    </form>
	
	<?php
		}
	?>
	
	<div class="flex justify-end mt-2">
        <?php
		echo "Available seats:".$result;	
		?>
    </div>
	

	<?php
				
	if($result == '0'){
				
	?>
	<h3 class="mt-4 text-2xl sm:text-3xl font-bold text-center text-gray-600 leading-8">
            Buy more seats 
    </h3>
	
	 
	<form id="payment-form" action="" wire:submit.prevent="create2" method="POST">
		@csrf			
				
		<select style="visibility:hidden;" name="plan" id="plan" class="pl-0">
			<option value= "Monthly" <?php if($plan_name=='Monthly'){echo 'selected';} ?> >Monthly</option>
			<option value= "Annual"  <?php if($plan_name=='Annual'){echo 'selected';} ?> >Annual</option>
		</select>
						

		<div class="form-group pt-2 pb-6">
			<label for="">Seats</label>
			<input onkeypress="return onlyNumberKey(event)" type="number" min="1" name="selectseats" id="selectseats" class="form-control placeholder-gray-300 w-full" value="1" placeholder="# seats">
		</div>
			
<?php
/*
?>		
		<div class="form-group py-5">
			<label for="">Name</label>
			<input type="text" name="name" id="card-holder-name" class="form-control placeholder-gray-300 w-full" value="" placeholder="Name on the card">
		</div>
			
		<div class="form-group pb-6">
			<label for="">Card details</label>
			<div id="card-element"></div>
		</div>	
<?php

*/
?>
		<p>
				
		<button type="submit" class="w-full  mt-4 sm:mt-0 h-10 text-sm flex items-center rounded-md bg-blue-600 
			text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out"	
			id="card-button" data-secret="{{ $intent->client_secret }}">PAY NOW
		</button>
					
	</form>
	
	<?php 
	
	} 
	?>
	
</x-modals.small>

<script>
    const stripe = Stripe('{{ config('cashier.key') }}')

    const elements = stripe.elements()
    const cardElement = elements.create('card')

    cardElement.mount('#card-element')

    const form = document.getElementById('payment-form')
    const cardBtn = document.getElementById('card-button')
    const cardHolderName = document.getElementById('card-holder-name')

    form.addEventListener('submit', async (e) => {
        e.preventDefault()

        cardBtn.disabled = true
        const { setupIntent, error } = await stripe.confirmCardSetup(
            cardBtn.dataset.secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: cardHolderName.value
                    }
                }
            }
        )

        if(error) {
            cardBtn.disable = false
			swal({
					title:'Ups!',
					text:error.message,
					icon:'error'
				}
			);
        }
		
		
		else {
			
            let token = document.createElement('input')

            token.setAttribute('type', 'hidden')
            token.setAttribute('name', 'token')
            token.setAttribute('value', setupIntent.payment_method)
			
            form.appendChild(token)
			
			
            form.submit();
        }
    })
</script>
