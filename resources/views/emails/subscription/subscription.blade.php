<?php

use App\Models\Account;	
use App\Models\User;


$user = Auth::user();

$user_firstname = $user->firstname;
$user_email = $user->email;
$ipAddress = $user->ipaddress;


$paymentMethods = $user->paymentMethods();
$CC = $paymentMethods[0]->card->last4;
$fech = $paymentMethods[0]->created;
$date_at = date('M/d/Y', $fech);


$user_subscriptions = $user->subscriptions()->active()->get();
$price = $user_subscriptions[0]->quantity;

$stripeSubscription = $user->subscription($user_subscriptions[0]->name)->asStripeSubscription();
$chargeDate = date('M/d/Y', $stripeSubscription->trial_end);


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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome Email</title>
</head>
<body style="background-color: #f4f4f4; font-family: 'Segoe UI', 'Open Sans', sans-serif; font-size: 16px; line-height: 1.6; color: #333; margin: 0; padding: 0;">

    <div style="max-width: 700px; margin: 30px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">

        <div style="padding: 30px; text-align: center; border-bottom: 1px solid #e0e0e0;">
            <a href="{{ route('home') }}" style="text-decoration: none;">
                <x-logo />
            </a>
        </div>

        <div style="padding: 40px 30px;">
            <h2 style="text-align: center; color: #2c3e50; font-size: 26px; margin-top: 0;">Welcome to NeoStaff Manuel</h2>

            <h3 style="color: #436D9E; margin-bottom: 10px; font-size: 20px;">Invoice</h3>

			<div style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px; line-height: 1.5; text-align: left;">
                <p style="margin: 5px 0;"><strong style="color: #436D9E;">Your account:</strong> {{ $user_email }}</p>
                <p style="margin: 5px 0;"><strong style="color: #436D9E;">IP Address:</strong> {{ $ipAddress }}</p>
                <p style="margin: 5px 0;"><strong style="color: #436D9E;">Start date:</strong> {{ $date_at }}</p>
            </div>

            <div style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
                <p style="font-size: 18px; color: #2d3748;"><strong>Thank you for your business.</strong></p>
                <p>Your 30 days trial has just started.</p>

                <p>The credit card ending in <strong>{{ $CC }}</strong> will be charged <strong>${{ $total }}</strong> on <strong>{{ $chargeDate }}</strong>.</p>

                <p>If you use Windows, Mac or Linux, you can download the time tracker desktop app from here:</p>

                <p style="margin: 20px 0 10px 0; text-align: center; font-weight: bold;">
                    <a href="//media.neostaff.app/downloads/windows" style="color: #1d4ed8; text-decoration: none;">Windows</a> |
                    <a href="//media.neostaff.app/downloads/mac" style="color: #1d4ed8; text-decoration: none;">Mac</a> |
                    <a href="//media.neostaff.app/downloads/ubutnu" style="color: #1d4ed8; text-decoration: none;">Linux</a>
                </p>

                <p style="margin-top: 20px;">Click here to <a href="{{ route('login') }}" style="color: #0ea5e9; text-decoration: underline;">Login</a></p>

                <p style="margin-top: 30px; font-size: 16px;">
                    <strong>If you have any questions, please let us know. We'll get back to you as soon as we can.</strong>
                </p>

                <p style="margin-top: 20px;">Your friends,<br>
                    <a href="mailto:support@neostaff.app" style="color: #0EA5E9; text-decoration: none;">support@neostaff.app</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>    