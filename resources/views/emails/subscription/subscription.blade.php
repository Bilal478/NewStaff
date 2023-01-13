<?php

use App\Models\Account;	
use App\Models\User;


$user = Auth::user();

$user_firstname = $user->firstname;
$user_email = $user->email;


$paymentMethods = $user->paymentMethods();
$CC = $paymentMethods[0]->card->last4;
$fech = $paymentMethods[0]->created;
$date_at = date('M/d/Y', $fech);


$user_subscriptions = $user->subscriptions()->active()->get();
$price = $user_subscriptions[0]->quantity;

if($user_subscriptions[0]->name =='Annual'){
	
	$total_price = $price*36;
	$total = $total_price.' USD';
	$subscription_price = $total_price.' USD / Annual';
}
elseif($user_subscriptions[0]->name =='Monthly'){
	
	$total_price = $price*4;
	$total = $total_price.' USD';
	$subscription_price = $total_price.' USD / Monthly';
}
?>
<html>
<body style="background-color:#E2E1E0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
	
	<div style="padding:30px 30px 0px 30px;text-align:center;font-size:24px;font-weight:bold;">
		<a href="{{ route('home') }}" class="inline-block"><x-logo/></a>
	</div>
	<table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border: solid 1px grey;">
		<thead>
		  <tr>
			<th style="text-align:center;"><h2>Welcome to NeoStaff&nbsp;<?php echo $user_firstname;?></h2></th>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td colspan="2" style="font-size:20px;padding:30px 15px 0 0px;font-weight:bold; color:#436D9E;">Invoice</td>
		  </tr>
		  <tr>
			<td colspan="2" style="padding:15px 15px 15px 0px;border-bottom: 1px solid #ddd;">
				<p style="font-size:14px;margin:0 0 6px 0;"><span style="color:#436D9E;font-weight:bold;display:inline-block;min-width:100px;">Your account: </span><?php echo $user_email;?></p>
				<p style="font-size:14px;margin:0 0 0 0;"><span style="color:#436D9E;font-weight:bold;display:inline-block; min-width:85px;">Billing date: </span><?php echo $date_at; ?></p>  
			</td>
		  </tr>
		  <tr>
			<td colspan="2" style="padding:20px 15px 15px 0px;font-weight:bold; border-bottom: 1px solid #ddd;">
				<strong style="font-size:20px;color:#436D9E;display:block;" >Thank you for your business.</strong><br>
				<p style="font-size:14px;font-weight:normal; line-height: 150%;">The credit card ending in&nbsp;<?php echo $CC;?>&nbsp;has been successfully charged $<?php echo $total; ?><br>
				A copy of the receipt is also in your Billing Statements.<br><br>If you have any questions, please let us know. We'll get back to you soon<br>
				as we can.<br><br>
				Your friends,<br>
				<a style="color: #0EA5E9;" href="#">support@neostaff.app</a>
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="font-size:14px;padding:20px 15px 0 0px;border-bottom: 1px solid #ddd; line-height: 150%;">
			  <strong style="display:inline-block;margin:0 0 10px 0; min-width:420px;">Subscription</strong><strong>$<?php echo $subscription_price; ?></strong>
			  <br><br>
			  For the upcoming year, beginning&nbsp;<?php echo $date_at; ?><br>
			  40 Plan (Annual) - $XXXX<br>
			  20% annual discount (Annual) - $XXXX<br><br>
			</td>
		</tr>
		</tbody>
		<tfooter>
		  <tr>
			<td colspan="2" style="font-size:14px;padding:20px 15px 0 0px;">
			  <strong style="display:inline-block;margin:0 0 10px 0; min-width:420px;">Total</strong><strong>$<?php echo $total; ?></strong>
			  <br>
			</td>
		  </tr>
		</tfooter>
	</table>
</body>
</html>           