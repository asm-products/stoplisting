<?php 
$today_date=date('Y-m-d h:i:s');

if(isset($_REQUEST["action"]))
{	
	
	$oauth_provider	=mysql_real_escape_string($_REQUEST["oauth_provider"]);
	$oauth_uid=mysql_real_escape_string($_REQUEST["oauth_uid"]);
	$name=mysql_real_escape_string($_REQUEST["name"]);
	$email=mysql_real_escape_string($_REQUEST["email"]);
	$twitter_oauth_token=mysql_real_escape_string($_REQUEST["twitter_oauth_token"]);
	$twitter_oauth_token_secret=mysql_real_escape_string($_REQUEST["twitter_oauth_token_secret"]);
	
	$country=mysql_real_escape_string($_REQUEST["country"]);
	$plan=mysql_real_escape_string($_REQUEST["plan"]);
	$temp=mysql_real_escape_string($_REQUEST["template"]);
	
	$price=mysql_real_escape_string($_REQUEST["price"]);
	$offercode=mysql_real_escape_string($_REQUEST["offercode"]);
	$order_no=mysql_real_escape_string($_REQUEST["order_no"]);
	$db_app_key=mysql_real_escape_string($_REQUEST["db_app_key"]);
	$db_secret_key=mysql_real_escape_string($_REQUEST["db_secret_key"]);
	$ebay_dev=mysql_real_escape_string($_REQUEST["ebay_dev"]);
	$ebay_app=mysql_real_escape_string($_REQUEST["ebay_app"]);
	$ebay_cert=mysql_real_escape_string($_REQUEST["ebay_cert"]);
	$ebay_token=mysql_real_escape_string($_REQUEST["ebay_token"]);
	$password=mysql_real_escape_string($_REQUEST["password"]);
	$paypal_id=mysql_real_escape_string($_REQUEST["paypal_id"]);
	$salt='';
	$joindate=date('Y-m-d');//mysql_real_escape_string($_REQUEST["join_date"]);
	$status=mysql_real_escape_string($_REQUEST["status"]);
	$general_condition=2;//mysql_real_escape_string($_REQUEST["general_condition"]);
	$price_preference=mysql_real_escape_string($_REQUEST["price_preference"]);
	
	
	$latest_purchase=date('Y-m-d H:i:s');//mysql_real_escape_string($_REQUEST["latest_purchase"]);
	$bonus_listings=30;//mysql_real_escape_string($_REQUEST["bonus_listings"]);
	$listings_remaining=100;//mysql_real_escape_string($_REQUEST["listings_remaining"]);
	$listing_preference=mysql_real_escape_string($_REQUEST["listing_preference"]);
	$num_referrals=5;//mysql_real_escape_string($_REQUEST["num_referrals"]);
	$referred_by=4;//mysql_real_escape_string($_REQUEST["referred_by"]);
	$latest_app_login=date('Y-m-d H:i:s');
	
	
	$array=array('oauth_provider'=>$oauth_provider, 'oauth_uid'=>$oauth_uid, 'name'=>$name, 'email'=>$email, 'twitter_oauth_token'=>$twitter_oauth_token, 'twitter_oauth_token_secret'=>$twitter_oauth_token_secret, 'country'=>$country, 'plan'=>$plan, 'temp_id'=>$temp, 'price'=>$price, 'offercode'=>$offercode, 'order_no'=>$order_no, 'db_app_key'=>$db_app_key, 'db_secret_key'=>$db_secret_key, 'ebay_dev'=>$ebay_dev, 'ebay_app'=>$ebay_app, 'ebay_cert'=>$ebay_cert, 'ebay_token'=>$ebay_token, 'paypal_id'=>$paypal_id, 'password'=>$password, 'salt'=>$salt, 'joindate'=>$joindate, 'status'=>$status, 'general_condition'=>$general_condition, 'price_preference'=>$price_preference, 'latest_purchase'=>$latest_purchase, 'bonus_listings'=>$bonus_listings, 'listings_remaining'=>$listings_remaining, 'listing_preference'=>$listing_preference, 'num_referrals'=>$num_referrals, 'referred_by'=>$referred_by, 'latest_app_login'=>$latest_app_login);
	
	//echo '<pre>'.print_r($array,true).'</pre>';exit;
	
	
	$new_add	=	$db_fun->add_new_client($array); 
	
	if($new_add > 0)
	{		
		$_SESSION['msg'] = "Customer Added Successfully";

		echo "<script>window.location = '".SAURL."index.php?p=customers';</script> ";
		exit;
		}
	
	}
	
$query_temp=$db_fun->getAllTemplates();
$query_plan=$db_fun->getPackages();
?>