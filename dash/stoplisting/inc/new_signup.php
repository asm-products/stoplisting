<?php 
include('../con/config.php');
session_start();

/* YOU NEED TO USE PDO ON ALL STATEMENTS : http://php.net/manual/en/ref.pdo-mysql.php 
 Use PDO for all mysql statements, we dont need to get the site compromised. 

--------------
Here is the test purchase data from gumroad
$_POST['seller_id'] 			=== <removed>
$_POST['product_id'] 			=== ejYJ1ytcXpRZS7sDwGvEgA%3D%3D
$_POST['product_name'] 			=== test
$_POST['permalink']		 		=== Nzfw
$_POST['product_permalink'] 	=== https%3A%2F%2Fgum.co%2FNzfw
$_POST['email']					=== testuser@test.com
$_POST['price']					=== 0
$_POST['currency'] 				=== usd
$_POST['order_number'] 			=== 1055925619
$_POST['full_name'] 			=== Test%20User
$_POST['ip_country'] 			=== United%20States



seller_id=2SicfcvgM9tUpMdkmxyJmQ%3D%3D&product_id=KKhGoxib7PACL9NiSAlO_w%3D%3D&product_name=StopListing%20-%20Startup%20Service&permalink=uadgI&product_permalink=https%3A%2F%2Fgum.co%2FuadgI&email=conerstoneventures%40gmail.com&price=4000&currency=usd&order_number=462374259&full_name=StopListing&url_params[source_url]=http%3A%2F%2Fstoplisting.com%2Fget-started%2F&test=true&ip_country=United%20States


*/
if(isset($_SESSION['login_user']))
{
	echo "<script>window.location = '".SURL."index.php';</script> ";
}
// Check if Its the Right Seller Account 
if((isset($_POST['order_number'])) && (isset($_POST['seller_id'])) && ($_POST['seller_id'] == '2SicfcvgM9tUpMdkmxyJmQ==')){
	
	// Get User Data
	$price		=  trim($_POST['price']);
	$fname		=  trim($_POST['full_name']);
	$pid		=  trim($_POST['product_id']);
	$plan		=  trim($_POST['product_name']); 
	$order_no	=  trim($_POST['order_number']);
	$offercode	=  trim($_POST['offer_code']);
	$email		=  trim($_POST['email']);
	$country	=  trim($_POST['ip_country']);
	$app_signup	=  trim($_POST['app_signup']);
	$password	=  trim($_POST['password']);
	$plan_number	=  '0';
	
	// Check The Pricing Plan - Gets plan data from table, then checks that plan data against the $_POST plan data
	
	//EDIT: change this to PDO Format query
	$sql0 	  = "select plan_id, plan_product_id, plan_price, plan_limit from plans";
	$q0	  = mysql_query($sql0);
	if(mysql_num_rows($q0)>0) {
		  while ($plan_item = mysql_fetch_assoc($q0)) {
			    if ($pid == $plan_item['plan_product_id']) {
			   	 $plan_number 	=  $plan_item['plan_id'];
			   	 $plan 	       .= " - $" .$plan_item['plan_price'] . "/ Month";
			   	 $plan_limit 	= $plan_item['plan_limit'];
			    } 
		  }
		  if ($plan_number == 0) {exit;}
	 } 

	//EDIT: change this to PDO Format query (checking for new user
	$sql_old_user	 = "select * from users where email='".$email."'";
	$query_old_users = mysql_query($sql_old_user);
	$num_rows	 = mysql_num_rows($query_old_users);
	if($num_rows==0){ //new user
		// We're creating a salt using:   with a base number of 7. depending on the day of the week, the base number is increased for that user. md5(datetime.ordernumber)
		$truncate_num 	 = (int) date("l"); // gets day of week
		$truncate_num 	+= 7; //adds 7 to total number
		$salt 	 	 = substr(md5(date("dmyhms").$order_no), 0, $truncate_num);
		if(empty($app_signup)) {
			$password 	 = sha1($salt.$order_no); // this is temporary password hash, but the salt is permanent for user.
		} else { 
			$password 	 = sha1($salt.trim($_POST['password']));
		}
		$joindate	 = date('Y-m-d');
		$latest_purchase = date('Y-m-d H:i:s');
		
		// update to pdo
		//$sql		= "insert into users(name, email, country, plan, price,  offercode, order_no, db_app_key, db_secret_key, gender, password, salt, joindate, status, general_condition, price_preference, latest_purchase, bonus_listings, listings_remaining, listing_preference, num_referrals, referred_by, latest_app_login) 
		//                              values('".$fname."', '".$email."', '".$country."', '".$plan_number."', '".$price."', '".$offercode."', '".$order_no."', '', '', '1', '".$password."', '".$salt."', '".$joindate."', '1', '0', '', '".$latest_purchase."', '0', '".$plan_limit."', '', '0', '0', '')";
		$sql		= "INSERT INTO `users`(`id`, `oauth_provider`, `oauth_uid`, `name`, `email`, `twitter_oauth_token`, `twitter_oauth_token_secret`, `country`, `plan`, `temp_id`, `price`, `offercode`, `order_no`, `db_app_key`, `db_secret_key`, `password`, `salt`, `joindate`, `status`, `general_condition`, `price_preference`, `latest_purchase`, `bonus_listings`, `listings_remaining`, `listing_preference`, `num_referrals`, `referred_by`, `latest_app_login`) 
		                                VALUES('','','','".$fname."','".$email."','','','".$country."','".$plan_number."','','".$price."','".$offercode."','".$order_no."','','','".$password."','".$salt."','".$joindate."','1','0','','".$latest_purchase."','0','".$plan_limit."','','0','0',NOW())";
		$query		= mysql_query($sql);
		$last_id	= mysql_insert_id();
		if(empty($app_signup)) {
		// Link created with user id and their temp user password that will direct the login to pass them through and the dashboard to prompt the setup wizard 
		$msg 		= "Thank you for your purchase of: ".$plan.". \nYour User Password is: \"".sha1($order_no)."\"<br/> http://dash.stoplisting.com/login?a=nuwizard&id=".$last_id."&p=".sha1($order_no)." <== Click This Link To automatically access your account ";
		} else {
		// Link created with user id and their temp user password that will direct the login to pass them through and the dashboard to prompt the setup wizard 
		$msg 		= "Thank you for your purchase of: ".$plan.". \nYour User Password is: \"".trim($_POST['password'])."\"<br/> http://dash.stoplisting.com/login?a=nuwizard&id=".$last_id."&p=".sha1(trim($_POST['password']))." <== Click This Link To automatically access your account ";
		}
		// send email - use wordwrap() if lines are longer than 70 characters - $msg = wordwrap($msg,70);
		mail($email,"[StopListing] Your Temporary Login Password...",$msg);
	} else { // Old user
		$latest_purchase = date('Y-m-d H:i:s');
		// CHANGE: $_SESSION['login_user_id'] to   $query_old_users ---> user email (use correct notation)
		$sql		= "update users set country='".$country."', plan='".$plan_number."', price='".$price."', offercode='".$offercode."', order_no='".$order_no."', listings_remaining='".$plan_limit."', status = '1', latest_purchase='".$latest_purchase."' where email='".$email."'";
		mysql_query($sql);
		// Link created with user id and their temp user password that will direct the login to pass them through and the dashboard to prompt the setup wizard 
		$msg 		= 'Thanks for your purchase of: '.$plan.'. \nYou should be able to Access Your Account Via the App or via our http://stoplisting.com/login\nHave a Great Day,\n StopListing Staff';
		
		// send email - use wordwrap() if lines are longer than 70 characters - $msg = wordwrap($msg,70);
		mail($email,"[StopListing] You've Updated Your Listing Plan!",$msg);
	}
    	header("HTTP/1.1 200 OK");
    	echo   "OK";
}
?>