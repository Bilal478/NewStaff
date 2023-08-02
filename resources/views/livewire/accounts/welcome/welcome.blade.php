<?php

use App\Models\Account;	
 
$account = Account::find(session()->get('account_id'));
	
	$owner_id_query = DB::table('account_user')
		->where('account_id', $account->id)
		->where('role', 'owner')
		->first();
	
	$count_subs = DB::table('subscriptions')
		->where('user_id', $owner_id_query->user_id)
		->where('stripe_status', '!=', 'canceled')
		->get();

if(count($count_subs) == '0'){
	
	header('Location: https://media.neostaff.app/');
	die();	
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="shortcut icon" href="{{ url(asset('neostaff-icon.png')) }}">
	<title>Thank you</title>
</head>
<body>

	<section class="bkg-thankyou p-100">
		<div class="">
			<div class="thanks-container">
				<div class="row">
					<div class="col-md-12 text-center">
						<div class="logo">
							<a href="{{ route('home') }}" class=""><x-logo/></a>
						</div>
					</div>
					<br>
					<div class="col-md-12">
						<div class="text-center">
							<h2 class="head-message pb-2" >Thank You!</h2>
							<h2 class="head-message pb-2" >You Have Successfully Subscribed<br>On NeoStaff</h2>
						</div>
					</div>
				</div>
				<div class="p-50">
				<div class="row justify-content-center">
						{{-- <div class="col-md-6 col-sm-6 col-xs-12">
							<div class="video-btn text-rightbtn">
								<button class="btn-thankyou" id="myBtn">How To Use <i aria-hidden="true" class="fas fa-arrow-right"></i></button>
							</div>
						</div> --}}
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="dash-btn text-leftbtn">
								<a class="btn-thankyou" href="http://127.0.0.1:8000/dashboard">Dashboard <i aria-hidden="true" class="fas fa-arrow-right"></i></a>
							</div>
						</div>
				</div>
				</div>
			</div>
		</div>
		<div id="myModal" class="modal">

					    <!-- Modal content -->
							<div class="modal-content">
								<span class="close">&times;</span>
								<iframe src="https://www.youtube.com/embed/u31qwQUeGuM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							</div>
					     </div>
	</section>
	
</body>

</html>
<style>

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 200px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: unset;
  margin: auto;
  padding: 0;
  width: fit-content;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.close {
  color: white;
  font-size: 30px;
  font-weight: bold;
  position: absolute;
  right: 0%;
  top: -15%;
  opacity: .75;
}

.close:hover,
.close:focus {
  color: white;
  text-decoration: none;
  cursor: pointer;
  opacity: .5 !important;
}

@media (min-width: 992px){
	.bkg-thankyou {
		background-size: contain;
	}
	
	.modal-content iframe {
		width: 700px;
		height: 400px;
	}
}

@media (min-width: 768px) and (max-width: 991px){
	.modal-content iframe {
		width: 600px;
		height: 350px;
	}
}

.modal-content iframe {
	border-radius: 7px;
}

@media (min-width: 768px){
	.text-rightbtn {
		text-align: right;
	}
	.text-leftbtn {
		text-align: center;
	}
}

@media (max-width: 767px){
	.text-rightbtn {
		text-align: center;
	}
	.text-leftbtn {
		text-align: center;
		margin-top: 60px;
	}
	
	.modal-content iframe {
		height: 200px;
	}
	
	
}

.text-rightbtn button {
	margin-top: -30px;
}


.btn-thankyou {
	fill: #FFFFFF;
    color: #FFFFFF !important;
    background-color: #FFFFFF00;
    border-style: solid;
    border-width: 2px 2px 2px 2px;
    border-color: #FFFFFF;
	padding: 20px 60px;
    border-radius: 50px;
	font-weight: 600;
}
.btn-thankyou:hover{
    color: #1F1F1F !important;
    background-color: #FFFFFF;
    border-color: #FFFFFF;
    border-radius: 50px;
	text-decoration: none !important;
}

.bkg-thankyou {
	background-color: #5580ff;
	background-image: url(/images/Software-bg.png);
	background-position: center left;
    background-repeat: no-repeat;
	height: 100vh;
	position: relative;
	width: 100%;
}

.p-100 {
	padding: 100px 0 100px 0;
}

.p-50 {
	padding: 50px 0 50px 0;
}

.head-message{
	padding-top:30px;
	color: white;
	font-weight: bold;
	font-size: 42px;
}



.thanks-container {
	margin: 0;
	position: absolute;
	top:50%;
	left: 50%;
	width: fit-content;
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
}






body{
	font-family: system-ui;
}
h5{
	color: white;
}
.inbox-img{
	width: 30px;
	float: right;
	padding-right: 0px;
	margin-right: 0px;
}

.inbox{
	height: 10vh;
	margin-left:0;
	margin-top:200px;
	padding-left 0px;
	margin-left: 0px;
}

.video{
	display:flex;
    justify-content: center;
	margin-top: 25vh;
	position: absolute;
	float: center;
}

.section-bottom{
	height: 40vh;
	background-color:white;
	border-radius:30px;
}



.flex {
	display: none !important;
}

button:focus {
	outline: none !important;
}
</style>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>