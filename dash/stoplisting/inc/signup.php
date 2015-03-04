<?php 
include('../con/config.php');
session_start();
if(isset($_SESSION['login_user']))
{
	echo "<script>window.location = '".SURL."index.php';</script> ";
}

$price='';	
$fname='';
$pid='';
$plan='';
$order_no='';
$offercode='';
$email='';
if(isset($_GET['price'])){
	
	$price=trim($_GET['price']);
	
}	

if(isset($_GET['full_name'])){
	
	$fname=trim($_GET['full_name']);
	
}	


if(isset($_GET['product_id'])){
	
	$pid=trim($_GET['product_id']);
	
	if($pid==1){
		
		$plan="$40 / Month";
		
	}
	
	if($pid==2){
		
		$plan="$75 / Month";
		
	}
	
	if($pid==3){
		
		$plan="$120 / Month";
		
	}
	
	if($pid==4){
		
		$plan="$250 / Month";
		
	}
	
}	


if(isset($_GET['order_number'])){
	
	$order_no=trim($_GET['order_number']);
	
}	


if(isset($_GET['offercode'])){
	
	$offercode=trim($_GET['offercode']);
	
}	
if(isset($_GET['email'])){
	
	$email=trim($_GET['email']);
	
}	


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="ThemeBucket">
<link rel="shortcut icon" href="images/favicon.png">
<title>Login</title>
<!--Core CSS -->
<link href="../bucket/bs3/css/bootstrap.min.css" rel="stylesheet">
<link href="../bucket/css/bootstrap-reset.css" rel="stylesheet">
<link href="../bucket/font-awesome/css/font-awesome.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../bucket/css/style.css" rel="stylesheet">
<link href="../bucket/css/style-responsive.css" rel="stylesheet" />
<!-- Just for debugging purposes. Dont actually copy this line! -->
<!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-body">
<div class="container">
<?php
/*
  <form class="cmxform form-signin" id="signupForm" method="post" action="../con/db_function.php"><!-- -->
    <input type="hidden" name="action" value="usrsignup" />
    <h2 class="form-signin-heading">registration now</h2>
    <div class="login-wrap">
      <p>Enter your personal details below</p>
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input type="text" value="<?php echo stripslashes($fname)?>" class="form-control" name="firstname" placeholder="Full Name" style="margin-bottom:0px;" autofocus>
      </div>
      <!--<div class="col-lg-13" style="margin-bottom:15px;">
        <input type="text" class="form-control" name="address" placeholder="Address" style="margin-bottom:0px;" autofocus>
      </div>--><!--
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input class="form-control" value="<?php echo stripslashes($email)?>" id="email" name="email" type="text" style="margin-bottom:0px;" placeholder="Email" autofocus>
      </div>
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input type="text" class="form-control" name="city" id="city" style="margin-bottom:0px;" placeholder="City/Town" autofocus>
      </div>
      
      
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input type="text" class="form-control" value="<?php echo stripslashes($plan)?>" name="plan" id="plan" style="margin-bottom:0px;" placeholder="Your Plan" autofocus>
        <input type="hidden" name="pid" value="<?php echo trim($pid)?>">
      </div>
      
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input type="text" class="form-control" value="<?php echo stripslashes($price)?>" name="price" id="price" style="margin-bottom:0px;" placeholder="Plan Price" autofocus>
      </div>
      
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input type="text" class="form-control" value="<?php echo stripslashes($offercode)?>" name="offercode" id="offercode" style="margin-bottom:0px;" placeholder="Offercode" autofocus>
      </div>
      
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input type="text" class="form-control" value="<?php echo stripslashes($order_no)?>" name="order_no" id="order_no" style="margin-bottom:0px;" placeholder="Order No" autofocus>
      </div>
      
      
      
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input type="text" class="form-control" name="db_app_key" id="db_app_key" style="margin-bottom:0px;" placeholder="Dropbox App Key" autofocus>
      </div>
      
      
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input type="text" class="form-control" name="db_secret_key" id="db_secret_key" style="margin-bottom:0px;" placeholder="Dropbox Secret Key" autofocus>
      </div>
      
      
      
      
      <div class="radios">
        <label class="label_radio col-lg-6 col-sm-6" for="radio-01">
        <input name="sample-radio" id="radio-01" name="gender" value="1" type="radio" checked />
        Male </label>
        <label class="label_radio col-lg-6 col-sm-6" for="radio-02">
        <input name="sample-radio" id="radio-02" name="gender" value="0" type="radio" />
        Female </label>
      </div>
      <p> Enter your account details below</p>
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input class="form-control " id="username" name="username" style="margin-bottom:0px;" type="text" placeholder="User Name" autofocus>
      </div>
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input class="form-control " id="password" name="password" style="margin-bottom:0px;" type="password" placeholder="Password">
      </div>
      <div class="col-lg-13" style="margin-bottom:15px;">
        <input class="form-control " id="confirm_password" style="margin-bottom:0px;" name="confirm_password" type="password" placeholder="Re-type Password">
      </div>
      <label class="checkbox">
      <input  type="checkbox" style="" style="margin-bottom:0px;width: 20px;" class="checkbox form-control" id="agree" name="agree" />
      I agree to the Terms of Service and Privacy Policy </label>
      <button class="btn btn-lg btn-login btn-block" type="submit">Submit</button>
      <div class="registration"> Already Registered. <a class="" href="<?=SURL?>inc/login.php"> Login </a> </div>
    </div>
  </form>
*/ ?>
  <div class="col-lg-10 col-md-offset-1 pricing_table">
  <h1>Select A Plan</h1>
  <h4>The App is available for use with all Plans</h4>
  <table class="table"> 
        <thead>
          <tr>
            <th>Features</th>
            <th>Free</th>
            <th>Startup</th>
            <th>Basic</th>
            <th>Empowered</th>
            <th>Enterprise</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Price</td>
            <td><strong>$0</strong>/Mo</td>
            <td><strong>$40</strong>/Mo</td>
            <td><strong>$75</strong>/Mo</td>
            <td><strong>$120</strong>/Mo</td>
            <td><strong>$250</strong>/Mo</td>
          </tr>
          <tr>
            <td>Listings/Month</td>
            <td>20 Listings</td>
            <td>50 Listings</td>
            <td>100 Listings</td>
            <td>150 Listings</td>
            <td>450 Listings</td>
          </tr>
          <tr>
            <td>Swank Rankings</td>
		<td><strong>&#x2717;</strong></td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
          </tr>
          <tr>
            <td>Live Finder Searches</td>

		<td><strong>&#x2717;</strong></td>
		<td><strong>&#x2717;</strong></td>
             <td>25 Searches/Mo</td>
            <td>50 Searches/Mo</td>
             <td>100 Searches/Mo</td>
          </tr>
          <tr>
		<td>Barcode To Listing</td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
          </tr>
          
   
          <tr>
		<td> Quality Customer Support</td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
		<td><strong>&#x2713;</strong></td>
          </tr>
          <tr>

		<td></td>
		<td><a href="https://gum.co/qwrv?wanted=true" class="gumroad-button"><button type="button" class="btn btn-light">Get Free</button></a></td>
		<td><a href="https://gum.co/uadgI?wanted=true" class="gumroad-button"><button type="button" class="btn btn-primary">Startup Now</button></a></td>
		<td><a href="https://gum.co/UAge?wanted=true" class="gumroad-button"><button type="button" class="btn btn-danger">Get Basic</button></a></td>
		<td><a href="https://gum.co/AQGhE?wanted=true" class="gumroad-button"><button type="button" class="btn btn-success">Get Empowered</button></a></td>
		<td><a href="https://gum.co/NOOQ?wanted=true" class="gumroad-button"><button type="button" class="btn btn-dark">Get Enterprise</button></a></td>
          </tr>          
          
          
          
        </tbody>
      </table>
</div>
</div>
<!-- Placed js at the end of the document so the pages load faster -->
<!--Core js-->
<script src="../bucket/jquery.js"></script>
<script src="../bucket/bs3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../bucket/jquery.validate.min.js"></script>
<!--common script init for all pages-->
<script src="../bucket/scripts.js"></script>
<!--this page script-->
<script src="../bucket/validation-init.js"></script>
<script type="text/javascript" src="../js/gumroad-fixed.js"></script>
<style>
.pricing_table {background: #fefefe; border-radius: 5px;margin-top: 50px;font-size:1.1em;color: rgba(20, 25, 5, 0.7);}
.table > tbody:nth-child(2) > tr > td:nth-child(1) {font-weight:bold;font-size:1.1em;}
.table > thead > tr > th {font-weight:bold;font-size:1.4em;color: rgba(20, 25, 5, 0.86);}
.table > tbody:nth-child(2) > tr:nth-child(1) > td > strong:nth-child(1) {font-size:1.8em;}
.btn-dark, .btn-dark:hover {background: #343434;color:#fefefe;}
</style>
</body>
</html>
