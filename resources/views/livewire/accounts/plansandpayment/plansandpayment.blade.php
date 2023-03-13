<script src="https://js.stripe.com/v3/"></script>

<?php

use App\Models\Account;	
use App\Models\User;

$account = Account::find(session()->get('account_id'));

	$owner_id_query = DB::table('account_user')
		->where('account_id', $account->id)
		->first();
	$subs = DB::select('SELECT * FROM account_user WHERE account_id="'.$owner_id_query->account_id.'"');	
	
	
	$count_subs = count($subs);
	
	$user_id = Auth::user()->id;
	$trial_verify = DB::select('SELECT quantity FROM subscriptions WHERE user_id = "'.$user_id.'"AND stripe_status != "canceled"');
	$temp = count($trial_verify);

if($temp >0){//or 1
	header('Location: /dashboard');
		die();
}

	if(($_GET['plan']!='Monthly') && ($_GET['plan']!='Annual')){
		header('Location: https://neostaff.app/#price');
		die();	
	}

	$company = DB::select('SELECT * FROM accounts WHERE id="'.$account->id.'"');	
	$show_company = $company[0]->name;
	
?>

@extends('layouts.auth2')
<title>Billing Information</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
	<link rel="shortcut icon" href="{{ url(asset('neostaff-icon.png')) }}">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<div class="container">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
       <div class="flex justify-center pt-2 pb-2">
            
			<a href="{{ route('home') }}" class="inline-block">
               <x-logo/>  
            </a>
        </div> 
		<h2 class="mt-2 mb-4 text-2xl sm:text-3xl font-bold text-center text-gray-600 leading-9">
            Billing Information 
        </h2>
	
	<div class="row">
	
	<?php 
	if($_GET['plan']=='Monthly'){
	?>
		<div class="col-md-5 switch-left">
			<div class="Monthly-price">
				<span style="font-weight: bold;" id="Monthly-price" >Pay Monthly</span>
			</div>
		</div>
		<div class="col-md-2 switch-center">
			<div class="check">
				<label class="switch mt-2">
					<input type="checkbox" id="checkbox">
					<span class="slider round"></span>
				</label>
			</div>
		</div>
		<div class="col-md-5 switch-right">
			<div class="Annual-price">
				<span  style="font-weight:normal" id="Annual-price">Pay Annual</span>
			</div>	
		</div>
	<?php
	}
	if($_GET['plan']=='Annual'){
	?>
		<div class="col-md-5 switch-left">
			<div class="Monthly-price">
				<span id="Monthly-price" >Pay Monthly</span>
			</div>
		</div>
		
		<div class="col-md-2 switch-center">
			<div class="check">
				<label class="switch mt-2">
					<input type="checkbox" checked id="checkbox">
					<span class="slider round"></span>
				</label>
			</div>
		</div>
			
		<div class="col-md-5 switch-right">
			<div class="Annual-price">
				<span  style="font-weight:bold" id="Annual-price">Pay Annual</span>
			</div>	
		</div>
	<?php
	}
	?>
	</div>
	
		<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
			<div  class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10 no-padding">
				
				<form " id="payment-form" action="" method="POST">
				@csrf			
				
				<select style="visibility:hidden" name="plan" id="plan" class="pl-0">
					<option value="Monthly" <?php if ($_GET['plan']=='Monthly'){echo 'selected';} ?> >Monthly</option>
					<option value="Annual" <?php if ($_GET['plan']=='Annual'){echo 'selected';} ?> >Annual</option>
				</select>
				<?php
			
				$price = 0;
				
				if($_GET['plan']=='Monthly'){
					
					$price = 4.0;
				}
				if($_GET['plan']=='Annual'){
					
					$price = 3.0*12;
				}
				
				
				function getInicials($show_company){
					$name = '';
					
					$explode = explode(' ',$show_company);
					foreach($explode as $x){
						$name .=  $x[0];
					}
				return $name;    
				}

				$initials = getInicials($show_company);
				

				echo 
				
				'
				
				<div class="bg-blue-600 mt-2 show-details">
				
					
					<div class="container">
						<div class="row">
							
							<div class="col-lg-4 izq">
								<h1 class="text-blue-600 initials" >'.$initials.'</h1>
							</div>
							
							<div class="der col-lg-8"> 
								
								<h2 class = "company">'.$show_company.'</h2>
								<h2 class = "price" id="price">$'.$price.'USD</h2>
							</div>
							
						</div>
					</div>
				
				</div>';
				
				
				/*
					<h4 class="chosen_plan">Your plan:&nbsp;</h4>
					<h2 class = "plan text-white-600">'.$_GET['plan'].'</h2>
				*/	
				?>
				
				<?php
				
				if($count_subs == '1'){
				
				?>
				
				<div class="form-group pt-6">
					<label for="">Users</label>
					<input onchange="Calculate(<?php echo $price; ?>)" type="number" min="<?php echo $count_subs ?>" name="selectseats" id="selectseats" class="form-control placeholder-gray-300 w-full" value="1" placeholder="# seats">
				</div>
				
				<?php 
				
				}else{ ?>
				
				<div class="form-group pt-6">
					<label for="">Seats</label>
					<input onchange="Calculate(<?php  echo $price; ?>)"  min="<?php echo $count_subs ?>" type="number" name="selectseats" id="selectseats" class="form-control placeholder-gray-300 w-full" value="<?php echo $count_subs ?>" placeholder="# seats">
				</div>
				<?php }
				?>
				<div class="form-group py-6">
					<label for="">Name</label>
						<input type="text" name="name" id="card-holder-name" class="form-control placeholder-gray-300 w-full" value="" placeholder="Name on the card">
				</div>
				
				<div class="form-group pb-6">
					<label for="">Card details</label>
					<div  onchange="Show()" id="card-element"></div>
				</div>
							<p>
				
				<button type="submit" class="text-center w-full  mt-2 sm:mt-0 h-10  rounded-md bg-blue-600 
					text-white pl-4 pr-6 hover:bg-blue-500 focus:outline-none active:bg-blue-700 transition duration-150 ease-in-out"	
					id="card-button" data-secret="{{ $intent->client_secret }}">PAY NOW</button>  
					
				</form>
				
				<a href="https://media.neostaff.app/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-3 py-1 rounded-md leading-6 flex items-center hover:bg-gray-100 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
					</svg>
                    <span class="text-gray-700">Logout</span>
                </a>

                <form id="logout-form" action="https://media.neostaff.app/logout" method="POST" style="display: none;">
				@csrf
				{{method_field('POST')}}
                    <input type="hidden" name="" value="">                
				</form>
				<span class="text-gray-700"><a href="{{ url()->previous() }}">back</a></span>
			</div>
		</div>
	</div>
	
 </div>
 <style>
 .chosen_plan{
	color: white;
	font-weight: bold;
	font-size: 20px;
	text-align:center;
	display: inline-block;
	
 }
 .plan{
	display: inline-block;
	font-weight: bold;
	font-size: 20px;
	color: white;
	
 }
body{
	overflow: hidden;
}

.der{
	padding-right:15px;
}
.izq{
	
	border-radius: 40px;
	width: 80px;
	height: 80px;
	background-color: #fff !important;
	margin: auto;
}
.initials{
	align-items: center;
	display: flex;
	justify-content: center;
	font-size: 60px;
	font-weight: bold;
	
}
.price{
	color: white;
	font-weight: bold;
	height: 30px;
	text-align: center;
	font-size: 18px;
}
.company{
	color: white;
	font-weight: bold;
	height: 35px;
	font-size:20px;
	text-align: center;
}
.show-details{
	text-align: center;
	padding: 10px;
	border-radius:7px;
	font-family: system-ui;
	
}

.no-padding {
	padding-top: 0 !important;
}

.check{
	display: inline-block;
}
.Annual-price{
	display: inline-block;
	color: #525252;
}
.Monthly-price{
	display: inline-block;
	color: #525252;
	
}

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 25px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #0284C7;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 9px;
  bottom: 3px;
  background-color: #fff;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #0284C7;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.switch-left {
	text-align: right;
}

.switch-center {
	text-align: center;
}

.switch-right {
	text-align: left;
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
					title:'Alert',
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

<script>
	function Show(){
	
	var card = document.getElementById("card-element").value;
	var show_card = document.getElementById("card");
	
	show_card.innerHTML = card;
	}

</script>


<script>
	function Calculate(price){
		var number_seats = document.getElementById("selectseats").value;
		var calculate_price = price*number_seats;
		var obj = document.getElementById('price');
		
		obj.innerHTML = "$"+calculate_price+"USD";
	}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var checkbox = document.querySelector('input[type="checkbox"]');

  checkbox.addEventListener('change', function () {
    if (checkbox.checked) {
      // do this
		//document.getElementById("checkbox").checked = true;
		
		document.getElementById('Annual-price').style.fontWeight='bold';
		document.getElementById('Monthly-price').style.fontWeight='normal';
		history.pushState(null, "", "https://media.neostaff.app/billing_information?plan=Annual");
		location.reload();
		console.log('Annual');
    } else {
      // do that 
		// document.getElementById("checkbox").checked = false;
		document.getElementById('Monthly-price').style.fontWeight='bold';
		document.getElementById('Annual-price').style.fontWeight='normal';
		history.pushState(null, "", "https://media.neostaff.app/billing_information?plan=Monthly");
		location.reload();
		console.log('Monthly');
    }
  });
});
</script>