<?php

/*
Explanation:

For the app, it uploads to dropbox, and then it sends you the dropbox url. 
So i think we should make some smaller resolution versions of each image for the website and put that in a special folder called dropbox_thumbnails -lets talk about this later.
------------------------------------------

Note all Api responses should be given and received in JSON format.
*/
include('../con/config.php');
session_start();
header('Content-type: application/json');
if(isset($_SESSION['login_user']))
{
	echo "<script>window.location = '".SURL."index.php';</script> ";
}

// Functions

/*
Functions:

Get db table data
Insert into db table
move table row.
*/


//Image Upload
if(isset($_GET['image_path']) && isset($_GET['user_id'])){
	//Check if multiple images:  image_path=image1.png-image2.png-image3.png		
	$image_single = strpos($url, "-");
	
	if ($image_single !== false) {	// multiple images		
		$date	   = date('Y-m-d');
		$url_list  = trim($_GET['image_path']);
		$user_id   = intval($_GET['user_id']);
		$urls      = explode("-", $url_list);
		$file_list = "";
		foreach($urls as $url) { //breaks down image array one at a time to save copies - make this a thumbnail version and save the url to the dropbox fullsize image url given from the app in the database alongside the thumbnail image url that you have created
			$file	  = basename($url);
			$file 	  = pathinfo($file, PATHINFO_EXTENSION);
			$file	  = $user_id.'_app_'.uniqid().time().'.'.$file;
			copy($url, '../upload_dropbox/'.$file);
			$file_list .= $file."-";
		}
		$file_list 	= rtrim($file_list, "-");
		$sql	 	= "insert into user_images(user_id, ui_image, ui_dropbox, ui_date) values('".$user_id."', '".$file_list."', 0, '".$date."')";
	} else {
			//single image
			$date	   	= date('Y-m-d');
			$url       	= trim($_GET['image_path']);
			$user_id   	= intval($_GET['user_id']);
			//make this a thumbnail version and save the url to the dropbox fullsize image url given from the app in the database alongside the thumbnail image url that you have created
			
			$file	  	= basename($url);
			$file 	  	= pathinfo($file, PATHINFO_EXTENSION);
			$file	  	= $user_id.'_app_'.uniqid().time().'.'.$file;
			$file_list 	= $file;
			copy($url, '../upload_dropbox/'.$file);
	
	// Add new column in database to support db thumbnails that you generate so you can save the app dropbox urls and the thumbnails that you make
	$sql	 = "insert into user_images(user_id, ui_image, ui_dropbox, ui_date) values('".$user_id."', '".$file."', 0, '".$date."')";
	}
	$query	 = mysql_query($sql);
	if($query){
		// This will need to be in json format use json_encode status 200 = OK/success
		$app_response = array('process' => 'image_upload', 'status' => '200', 'db_urls_thumbs' => $file_list, 'time_uploaded' => date('Y-m-d H:m:s'));
		echo json_encode($app_response);
		//echo "Successfully Uploaded to Webserver"."<br>";
		echo "temp photo needs to be removed:<img src='".SURL.'upload_dropbox/'.$file."'>";
	}
	else{
		// This will need to be in json format use json_encode status 503 = server down
		$app_response = array('process' => 'image_upload', 'status' => '503', 'db_url_thumbs' => '', 'time_uploaded' => '');
		echo json_encode($app_response);
		//echo "Ooops... something went wrong";
	}
}

/*
Login:
api must verify the login details of the account that a user logins as.
On login validation, the app will require information from the api about current listing statuses.
*/
if(isset($_GET['image_path']) && isset($_GET['user_id'])){} 

/*
Dashboard
Gather Dashboard Data Stats:
X _ number of items added this week (published listings)
X _ number of items in moderation ( unpublished listings)
X _ Need More Info ( Rejected Listings)
*/


/*

Manage
UNPublished Tab: Function sends app latest published listings from the listings table (Has User Options)
Published Tab: Function sends app latest published listings from the listings table (No user options, just shows the date of published and perhaps social buttons)
rejected Tab: Function sends app latest rejected listings from the listings table (Has User Options)

*/


/*
Swank - api will need to receive searches from text or from barcode scan and return the following data:
		Swank Rank Number
		Average Price
		Swank Result Title
		Latest Sold Date
		Lastest Sold Price
*/

/*
Live Finder - the api will need to receive searches from images which can also be images of a barcode. the following must be returned to the app:
		Swank Rank Number
		Average Price
		Swank Result title
		Latest Sold Date
		Lastest Sold Price

		The Api will need to be able to recieve to options from the app for the livefinder:
		Option 1: List the found result items
		Option 2: Try the live finder service again - this is the wrong item.
*/

/*


Account - This will have the similar functions to the account page on the user panel.
Api should be able to send current settings and make changes to any settings that users change to their database row from the app
*/

/*
Upgrade - The api must have a current list of the packages for sell, the package names, the package prices and their descriptions.
The api must return the current package name on call.
If a user upgrades their account, the api should update their user package level
*/

/*Get Pricing Plans*/

if(isset($_GET['get_plan'])){
	if(!isset($_GET['user_id'])) { // no user - all plans get
		$sql_get_plans	 = "select plan_id, plan_title, plan_price, plan_limit from plans";
		$query_get_plans = mysql_query($sql_get_plans);
		$rows = array();
		while($r = mysql_fetch_assoc($query_get_plans)) {
		  $r = array_combine(array('id', 'title', 'price', 'count'), array_values($r));
		  $rows[] = $r;
		}
		print json_encode($rows);
	} else {
		// Get Specific User Plan
		$user_id 		 = $_GET['user_id']; // use pdo to sanitize data
		$sql_get_user_plan	 = "select plans.plan_id,  plans.plan_title,  plans.plan_price,  plans.plan_limit, users.plan from plans, users WHERE plans.plan_id=users.plan AND users.id=".$user_id;
		$query_get_user_plan 	 = mysql_query($sql_get_user_plan);
		$row_user_plan		 = mysql_fetch_assoc($query_get_user_plan);
		//print_r($row_user_plan);
		
		echo '[{"id":"'.$row_user_plan['plan_id'].'","title":"'.$row_user_plan['plan_title'].'", "price":"'.$row_user_plan['plan_price'].'", "count":"'.$row_user_plan['plan_limit'].'"}]';
	}
}
?>