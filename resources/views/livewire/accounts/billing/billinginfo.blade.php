<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php
use App\Models\User;
use App\Models\Subscription;

use App\Models\Account;
use App\Models\AccountInvitation;

$user = Auth::user();
$user_mail = $user->email;

$paymentMethods = $user->paymentMethods();
$invoices = $user->invoices();

$CC = $paymentMethods[0]->card->last4;
$fech = $paymentMethods[0]->created;
$date_at = date('M/d/Y', $fech);

$fech_update = $paymentMethods[0]->created;
$update_at = date('M/d/Y', $fech_update);


$user_id = Auth::user()->id;
	
	$plan = DB::select('SELECT name FROM subscriptions WHERE user_id = "'.$user_id.'"AND stripe_status != "canceled"');
	
	$plan_name = $plan[0]->name;
	
	$plan_status = DB::select('SELECT stripe_status FROM subscriptions WHERE user_id = "'.$user_id.'"AND stripe_status != "canceled"');

	$status = $plan_status[0]->stripe_status;
	
	
	$fech_start = $invoices[0]->lines['data'][0]->period->start;
	
	$date_start = date('M/d/Y', $fech_start);
	
//	$fech_end = $invoices[0]->lines['data'][0]->period->end;
//	$date_end = date('M/d/Y', $fech_end);

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />   
   <x-page.title svg="svgs.folder">
        Membership
    </x-page.title>
	
	<?php
		$text='';
		if(!empty($_GET)){
			if($_GET['buy_more_seats']==1){
				$text='Seat';
			}
			else{
				$text = 'Seats';
		}
	?>
			<div class="alert" id="alert">
				<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
					You have purchased <?php echo $_GET['buy_more_seats']; echo ' '.$text; ?> more.
			</div>
			<br>
			<script>
			
			function hidealert(){
				document.getElementById('alert').style.display='none';
			}
				setTimeout(hidealert, 3000);
			</script>
	<?php
		}
	?>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<h3 style="display:inline-block" ><b><?php echo $user_mail; ?> </b></h3><span style="display:inline-block">&nbsp;on&nbsp;&nbsp;</span><h5 style="background-color:#CBF4C9; color:#0E6245; display:inline-block;"><?php echo ucwords($status); ?></h5>
			<hr>
		</div>
	</div>	

	<div class="row">
		<div class="col-lg-12">
			<h5><b>Subscription details</b></h5>
			<hr>
		</div>
	</div>	

	<div class="row">
		<div class="col-lg-3">
			<div class="customer">
				<span>Customer</span>
			</div>
		</div>
		
		<div class="col-lg-3">
			<div class="customer-email">
				<span><i class="fas fa-user"></i>&nbsp;&nbsp;<a href="https://media.neostaff.app/profile/edit"><?php echo $user_mail; ?></a></span>
			</div>
		</div>
	<?php
	if($status =='trialing'){
	?>
	
		@foreach($subscriptions as $subscription)
		<div class="col-lg-3">
			<div class="customer">
				<span>Trailing until</span>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="customer">
			<?php 
			$temp = $subscription->trial_ends_at;
			$free_until = strtotime($temp."+ 0 days");
			?>
				<span><?php echo date("M/j/Y",$free_until); ?></span>				
			</div>
		</div>
		@endforeach
	<?php
	}
	?>
	</div>
	
	<div class="row mt-2">
		<div class="col-lg-3">
			<div class="">
				<span>Created</span>
			</div>
		</div>
		
		<div class="col-lg-3">
			<div class="">
				<span><?php echo $date_at; ?></span>
			</div>
		</div>
		
		<div class="col-lg-3">
			<div class="">
				<span>Credit Card</span>
			</div>
		</div>
		
		<div class="col-lg-3">
			
			<div class="customer">
				<span>**** **** **** <?php echo $CC; ?></span>
			</div>
		</div>
	</div>
	
	<div class="row mt-2">
		<div class="col-lg-3">
			<span>Current period</span>
		</div>
		
		<?php
		
			$date = date("d-m-Y", $fech_start);
		
			
			$mod_date_monthly = strtotime($date." +1 month");
			$mod_date_annual = strtotime($date."+ 1 year");
		?>
		
		@foreach($subscriptions as $subscription)
			@if($subscription->name=="Monthly")
				<div class="col-lg-3">
					<span><?php echo $date_start; ?></span> to <span><?php echo date("M/j/Y",$mod_date_monthly); ?></span>
				</div>
			@endif
			@if($subscription->name=="Annual")
				<div class="col-lg-3">
					<span><?php echo $date_start; ?></span> to <span><?php echo date("M/j/Y",$mod_date_annual) ?></span>
				</div>
			@endif
		@endforeach
	</div>
	<br>
	<br>
	<div class="row">
		<div class=" col-lg-12">
			<h5><b>Pricing</b></h5>
			<hr>
		</div>
	</div>	
	<div class="row">
		<div class="col-lg-4">
			<div class="">
				<span>PRODUCT</span>
			</div>
		</div>
		
		<div class="col-lg-4">
			<div class="">
				<span># SEATS</span>
			</div>
		</div>
		
		<div class="col-lg-4">
			<div class="">
				<span>TOTAL</span>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-lg-4">
			<div class="">
			@foreach($subscriptions as $subscription)
				<span>{{$subscription->name}}</span>
			@endforeach
			</div>
		</div>
		
		<div class="col-lg-4">
			<div class="">
			@foreach($subscriptions as $subscription)
				<span>{{$subscription->quantity}}</span>
			@endforeach
			</div>
		</div>
		<div class="col-lg-4">
			<div class="">
			@if($subscription->name =='Annual')
				<span>${{$subscription->quantity*36}} USD / year</span>
			@endif
			
			@if($subscription->name =='Monthly')
				<span>${{$subscription->quantity*4}} USD / month</span>
			@endif
			</div>
		</div>
	</div>
	<br>
	<br>
	<div class="row pt-2">
		<div class=" col-lg-6">
			<h5><b>Available Seats</b></h5>
		</div>
		<div class="btn-delete-seats col-lg-6">
		@if($subscription->quantity - $users->count() > 0)
			<form action="/deleteseats" method="POST">
			@csrf
			{{method_field('POST')}}
				<input type="hidden" name="planname" value="{{$subscription->name}}">
				<input type="hidden" name="all-seats" value="{{$subscription->quantity - $users->count()}}">
					
				<button type="submit" class="delete-all btn btn-danger" >Delete all seats</button>	
			</form>
		@endif
		</div>
	</div>
	<hr>
	<div class="row">
		@if($subscription->quantity - $users->count()>0)
			@for($i = 0 ; $i < $subscription->quantity - $users->count() ; $i++)
			<div class="seats-col col-lg-3">
				<div class="available-seats">
					<span>Seat {{$i+1}}</span>
				</div>
				
				<form action="/deleteseats" method="POST">
				@csrf
				{{method_field('POST')}}
				<input type="hidden" name="planname" value="{{$subscription->name}}">
				<input type="hidden" name="all-seats" value="1">
					<div class="close-icon">
						<input type=image src="images/cancel2.png" width="25">
					</div>
				</form>
			</div>
			@endfor
		@endif
		
		@if($subscription->quantity - $users->count() == 0)
	
		<div class=" col-lg-3">
		</div>
		<div class=" col-lg-6 payment my-4">
			<h4 class="mt-4 mb-3" style="text-align:center;" ><b>Buy more seats</b></h4>
			
			
			<form id="payment-form" action="" method="POST">
			@csrf			
				
				<select style="visibility:hidden;" name="plan" id="plan" class="pl-0">
					<option value= "Monthly" <?php if($plan_name=='Monthly'){echo 'selected';} ?> >Monthly</option>
					<option value= "Annual"  <?php if($plan_name=='Annual'){echo 'selected';} ?> >Annual</option>
				</select>	

				<div class="form-group pt-2 pb-6">
					<label for="">#Seats</label>
					<input onkeypress="return onlyNumberKey(event)" type="number" min="1" name="selectseats" id="selectseats" class="form-control placeholder-gray-300 w-full" value="1" placeholder="# seats">
				</div>

				<p>
				
				<button type="submit" class="w-full btn btn-buy mb-4" 	
					id="card-button" data-secret="{{ $intent->client_secret }}">PAY NOW
				</button>
					
			</form>
			
		@endif
		</div>
		<div class=" col-lg-3">
		</div>
	</div>	
	<hr>
	<br>
	<div class="row">
		<div class="col-lg-4">
			<h5><b>Danger Zone</b></h5>
			<span>Cancel subscription, this action don't be undone</span>
		</div>
		
		<div class="col-lg-8">
			<div class="delete-subscription-box">
				<ul style="padding-left: 20px; padding-top: 20px;">
					<li><img src="images/black-circle.png" style ="display: inline-block;" width="10px"> Cancel all seats</li>
					<li><img src="images/black-circle.png" style ="display: inline-block;" width="10px"> If you still have a free trial you will lose it</li>
					<li><img src="images/black-circle.png" style ="display: inline-block;" width="10px"> Your members won't be able to use Neostaff</li>
				</ul>
				
				<a href="/cancelsubscription?planname={{$subscription->name}}" class="btn-cancel-subscription btn btn-danger">
					Cancel Subscription
				</a>
				
			</div>
		</div>
	</div>

	
</div>
<style>

.btn-delete-seats{
	text-align:right;
	margin-bottom: -25px;
}
.btn-cancel-subscription{
	float: right;
	margin-right: 25px;
	margin-top: 30px;
}

.delete-subscription-box{
	background-color:#ffffff;
	height: 200px;
}
.payment{
	background-color: #ffffff;
}
.btn-buy{
	background-color: #0284C7;
	color: white;
}
.btn-buy:hover{
	background-color: 0EA5E9;
	color: white;
}
.available-seats{
	display:inline-block;
	background-color: #0284C7;
	padding: 3px 30px 3px 30px;
	border-radius: 5px;
	color: white;
	text-align: center;	
}

.close-icon{
	display:inline-block;
    padding-top: 2px;
	
}
.customer-email{
	color:blue;
}
.pricing{
	margin-top{
		20px;
	}
}

.seats-col {
	text-align: center;
}

.alert {
  padding: 20px;
  background-color: #0284C7;
  color: white;
  border-radius: 6px;
}

.closebtn {
	margin-left: 15px;
	color: white;
	font-weight: bold;
	float: right;
	font-size: 22px;
	line-height: 20px;
	cursor: pointer;
	transition: 0.3s;
}

.closebtn:hover {
	color: black;
}
</style>

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
        } else {
            let token = document.createElement('input')

            token.setAttribute('type', 'hidden')
            token.setAttribute('name', 'token')
            token.setAttribute('value', setupIntent.payment_method)

            form.appendChild(token)

            form.submit();
        }
    })
</script>











	











