<?php
use App\Models\Account;	
use App\Models\User;

$user_id = Auth::user()->id;
$trial_verify = DB::select('SELECT * FROM subscriptions WHERE user_id = "'.$user_id.'"AND stripe_status != "canceled"');
$temp = count($trial_verify);

if($temp >0){
	header('Location: /dashboard');
		die();
}
?>

@extends('layouts.auth2')

<!-- component -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Plan</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="{{ url(mix('css/app.css')) }}">
	<link rel="shortcut icon" href="{{ url(asset('neostaff-icon.png')) }}">
</head>
<body style="background-color: #fff;">

	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="prices-cards left-card">
					<div class="img-head">
						<h5 class="per-year">Free Trial 15 days</h5>
						<img src="Pages-price.png">
					</div>
					<div class="prices-number">
						<div class="price-symbol-purple">
							<h3>$</h3>
						</div>
						<div class="number">
							<h2 class="h2">3.0</h2>
						</div>
						<div class="plan-frecuency">
							<h4 class="h4">/monthly<br>(per year)</h4>
						</div>
					</div>
					<div class="feature-list">
						<table class="feature-table">
							<tbody>
								<tr class="feature-rows">
									<td class="feature-left">Project Management</td>
									<td class="feature-right"><i style="color: #5580ff;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Team Management</td>
									<td class="feature-right"><i style="color: #5580ff;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Department Management</td>
									<td class="feature-right"><i style="color: #5580ff;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Time Tracker</td>
									<td class="feature-right"><i style="color: #5580ff;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Screenshot Time Tracker</td>
									<td class="feature-right"><i style="color: #5580ff;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Custom Reports</td>
									<td class="feature-right"><i style="color: #5580ff;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Flexible payment per user</td>
									<td class="feature-right"><i style="color: #5580ff;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="btn-select-plan-left">
						<a href="https://media.neostaff.app/billing_information?plan=Annual">Select Plan <i aria-hidden="true" class="fas fa-arrow-right"></i></a>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="prices-cards right-card">
					<div class="img-head">
						<h5 class="per-month">Free Trial 15 days</h5>
						<img src="Pages-price.png">
					</div>
					<div class="prices-number">
						<div class="price-symbol-pink">
							<h3>$</h3>
						</div>
						<div class="number">
							<h2 class="h2">4.0</h2>
						</div>
						<div class="plan-frecuency">
							<h4 class="h4">/monthly</h4>
						</div>
					</div>
					<div class="feature-list">
						<table class="feature-table">
							<tbody>
								<tr class="feature-rows">
									<td class="feature-left">Project Management</td>
									<td class="feature-right"><i style="color: #ED00CE;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Team Management</td>
									<td class="feature-right"><i style="color: #ED00CE;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Department Management</td>
									<td class="feature-right"><i style="color: #ED00CE;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Time Tracker</td>
									<td class="feature-right"><i style="color: #ED00CE;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Screenshot Time Tracker</td>
									<td class="feature-right"><i style="color: #ED00CE;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Custom Reports</td>
									<td class="feature-right"><i style="color: #ED00CE;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
								<tr class="feature-rows">
									<td class="feature-left">Flexible payment per user</td>
									<td class="feature-right"><i style="color: #ED00CE;" aria-hidden="true" class="fas fa-check-circle"></i></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="btn-select-plan-right">
						<a href="https://media.neostaff.app/billing_information?plan=Monthly">Select Plan <i aria-hidden="true" class="fas fa-arrow-right"></i></a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div  class="col-lg-12">
				<div class="btn-back">
					<a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="https://media.neostaff.app/logout">
						<i aria-hidden="true" class="fas fa-arrow-left"></i> Logout
					</a>			

					<form id="logout-form" action="https://media.neostaff.app/logout" method="POST" style="display: none;">
					@csrf
					{{method_field('POST')}}
						<input type="hidden" name="" value="">                
					</form>
				</div>
			</div>
		</div>
		
	</div>

<style>

.btn-select-plan-left a{
	background-color: #0040E5;
    box-shadow: 0px 10px 20px 0px rgb(0 64 229 / 35%);
	padding: 20px 40px 20px 40px;
	border-radius: 50px 50px 50px 50px;
	color: #fff;
	font-weight: 600;
}

.btn-select-plan-left {
	padding: 30px 0 20px 0;
}

.btn-select-plan-right a{
	background-color: #ED00CE;
    box-shadow: 0px 10px 20px 0px rgb(237 0 206 / 45%);
	padding: 20px 40px 20px 40px;
	border-radius: 50px 50px 50px 50px;
	color: #fff;
	font-weight: 600;
}

.btn-select-plan-right {
	padding: 30px 0 25px 0;
}

.btn-back a{
	background-color: #FF4E00;
    box-shadow: 0px 10px 20px 0px rgb(244 115 42 / 46%);
	padding: 20px 40px 20px 40px;
	border-radius: 50px 50px 50px 50px;
	color: #fff;
	font-weight: 600;
}

.btn-back {
	padding: 30px 0 60px 0;
	text-align: center;
}

.price-symbol-purple h3 {
	color: #5580ff;
	font-size: 25px;
    font-weight: 700;
}

.price-symbol-pink h3 {
	color: #ED00CE;
	font-size: 25px;
    font-weight: 700;
}

.plan-frecuency h4{
	color: #6e727d;
}

.prices-number {
	padding-top: 10px;
}

.prices-cards {
	border-style: solid;
    border-width: 2px 2px 2px 2px;
    border-color: #F2F5FE;
    transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
    margin: 0px 20px 0px 0px;
    padding: 10px 40px 20px 40px;
	border-radius: 15px;
	margin: 20px 0 20px 0;
}

.left-card:hover {
	border-color: #5580FF;
}

.right-card:hover {
	border-color: #ED00CE;
}

.img-head img{
	margin: auto;
	width: 112px;	
}
.per-year {
	margin: 10px 0px 20px 0px;
    padding: 8px 20px 8px 20px;
    background-color: transparent;
    background-image: linear-gradient(
270deg, #04D7F1 0%, #473BF0 100%);
    border-radius: 50px 50px 50px 50px;
	color: #fff;
	font-weight: 600;
    font-size: 22px;
	width: fit-content;
}

.per-month {
	margin: 10px 0px 20px 0px;
    padding: 8px 20px 8px 20px;
    background-color: transparent;
    background-image: linear-gradient(
	270deg, #ED00CE 0%, #7839F3 100%);
	border-radius: 50px 50px 50px 50px;
	color: #fff;
	font-weight: 600;
    font-size: 22px;
	width: fit-content;
}

.img-head {
	background-image: url('Pages-price-bg.png');
	background-repeat: no-repeat;
    background-size: contain;
    background-position: top;
}

.price-symbol-purple, .price-symbol-pink, .number, .plan-frecuency {
	display: inline-block;
	
	margin: 0px 0px 10px 0px;
	align-self: flex-end;
	width: auto;
}
.h2{
	font-size: 45px;
	margin-top: 0;
    margin-bottom: .5rem;
    font-weight: 500;
    line-height: 1.2;
}
.h4{
	margin-top: 0;
    margin-bottom: .5rem;
    font-weight: 500;
    line-height: 1.2;
}
.feature-table, .feature-rows {
	width: 100%;
}
.feature-left, .feature-right {
	width: 50%;
}
.feature-right {
	text-align: right;
}
.feature-left {
	color: #6e727d;
	font-weight: 400;
    font-size: 16px;
}
.feature-rows {
	line-height: 40px; 
}

.flex {
	display:none !important;
}

</style>



</body>
</html>

