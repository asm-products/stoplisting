<?php 
$today_date=date('Y-m-d h:i:s');
error_reporting(E_ALL ^ E_NOTICE);
if(isset($_REQUEST["parm"]))
{
	$param	 =	$_REQUEST["parm"];
	$table	 =	"user_images";
	if (isset($_REQUEST["parm2"])) {
		$user_id =	$_REQUEST["parm2"];
	}
	if (empty($user_id)) {
	
	$fetch	=	$select->getSingleRecord($table, 'ui_id', $param);
	$mfa = mysql_fetch_assoc($fetch);
	//echo '<pre>'.print_r($mfa,true).'</pre>';
	}
	else {
	// New Listing, no saved data yet
	$mfa = array();
	$mfa['user_id'] = (int) $user_id;
	}
	$query_ebay	=	$db_fun->geteBayCategories();
	$query_temp	=	$db_fun->getAllTemplates();

	# get user template_id from users table
	$user_temp_id=$db_fun->getUserDefaultTemplate($mfa['user_id']);
	
	$sqll		= "select * from users where id='".$mfa['user_id']."'";
	$query		= mysql_query($sqll);
	$mfa_user	= mysql_fetch_array($query);
	
}


if(isset($_REQUEST["action"]))
{	
//duration, listing_type, store_cat1, store_cat2,quantity


	$duration	 = mysql_real_escape_string($_REQUEST["duration"]);
	$listing_type	 = mysql_real_escape_string($_REQUEST["listing_type"]);
	// Need to break up the option value explode - to get name and value.
	if (!empty($_REQUEST["store_cat1"])) {
		$cat_mash1 	 = explode("-",  mysql_real_escape_string($_REQUEST["store_cat1"]));
		$cat_mash2 	 = explode("-",  mysql_real_escape_string($_REQUEST["store_cat2"]));
		$store_cat1	 = $cat_mash1[0];
		$store_cat1_name = $cat_mash1[1];
		$store_cat2	 = $cat_mash2[0];
		$store_cat2_name = $cat_mash2[1];
	}
	$quantity	 = (int)$_REQUEST["quantity"];
	$template	 = mysql_real_escape_string($_REQUEST["template"]);
	$category_id	 = mysql_real_escape_string($_REQUEST["category"]);
	$title		 = mysql_real_escape_string($_REQUEST["title"]);
	$detail		 = mysql_real_escape_string($_REQUEST["detail"]);
	$price		 = mysql_real_escape_string(trim($_REQUEST["price"]));
	$status		 = (int) $_REQUEST["status"];
	$condition 	 = (int) $_REQUEST["condition"];
	
	
	//--------------------------------Shipping Stuffs
	$shipping_type 	  	= mysql_real_escape_string($_REQUEST['shipping_type']);				// Calculated, CalculatedDomesticFlatInternational, Flat, FlatDomesticCalculatedInternational,FreightFlat
	if (($shipping_type !== "Flat") && ($shipping_type !== "FreightFlat")) {
		$item_weight_lbs	= (int) trim($_REQUEST['item_weight_lbs']);				// conditional - if calculated
		$item_weight_oz		= (int) trim($_REQUEST['item_weight_oz']);				// conditional - if calculated
		$shipping_package	= mysql_real_escape_string($_REQUEST['shipping_package']); 		// conditional - if calculated:
	} else {
		$shipping_cost 	  	= mysql_real_escape_string($_REQUEST['shipping_cost']);                 // conditonal reverse. if not calculated, have this lol.
	}
	if ($quantity > 1) {
		$shipping_cost_additional = mysql_real_escape_string($_REQUEST['shipping_cost_additional']); 	// conditional - if quanitity is more than one
	}
	$shipping_service 	= mysql_real_escape_string($_REQUEST['shipping_service']);
	$handling_time 	  	= mysql_real_escape_string($_REQUEST['handling_time']);				// handling time in days int (3 = default)
	
	if (!empty($_REQUEST["item_specific_name"]) && (!empty($_REQUEST["item_specific_value"]))) {
		
		$specific_name 	= array_map("utf8_encode", $_REQUEST["item_specific_name"]);
		$specific_value	= array_map("utf8_encode", $_REQUEST["item_specific_value"]);
		
		for($i=0;$i<count($specific_name);$i++) {
			$specific_name[$i] 	= str_replace("'", "&#39;", $specific_name[$i]);
			$specific_value[$i]	= str_replace("'", "&#39;", $specific_value[$i]);
		}
		//$specific_nodes = str_replace("'", "", array("names" => $specific_name, "values" => $specific_value));
		
		$specific_nodes = json_encode(array("names" => $specific_name,"values" => $specific_value));
		//$specific_nodes = json_encode($specific_nodes);
	}
	if (!empty($_REQUEST["image_order"])){
		$image_order 	= implode("-", array_map("mysql_real_escape_string", $_REQUEST["image_order"]));
	}
	
	
	if (empty($user_id)) {
		$status_num	= (int) $db_fun->getListingStatus($param);
		if($status == 1) {
		// Admin Completed, remove one from listings remaining.
			if (($status_num !== 1) && ($status_num !== 2)) {
				$db_fun->updateListingsRemaining(1, "subtract", $mfa['user_id']);
			}
		}
		else if($status !== 2) {
		//meaning that its not an admin complete listing, nor listed to the online store., so with this we check if  status in db is already  or 2, if so, we add one to listings remaining, because an admin dun goofed.
			if (($status_num === 1) || ($status_num == 2)) {
				$db_fun->updateListingsRemaining(1, "add", $mfa['user_id']);
			}
		}
		$edit_rec	= $db_fun->updateListing($category_id, $title, $detail, $price, $status, $param, $specific_nodes, $condition, $template, $image_order, $duration, $listing_type, $store_cat1, $store_cat2, $quantity, $store_cat1_name, $store_cat2_name, $shipping_type, $item_weight_lbs, $item_weight_oz, $shipping_package, $shipping_cost, $shipping_cost_additional, $shipping_service, $handling_time);
	} else {
	//saving new listing.
		$db_fun->updateListingsRemaining(1, "subtract", $mfa['user_id']);
		$edit_rec	= $db_fun->createListing($category_id, $title, $detail, $price, $status, $mfa['user_id'], $specific_nodes, $condition, $template, $image_order, $duration, $listing_type, $store_cat1, $store_cat2, $quantity, $store_cat1_name, $store_cat2_name, $shipping_type, $item_weight_lbs, $item_weight_oz, $shipping_package, $shipping_cost, $shipping_cost_additpional, $shiping_service, $handling_time);
	}

	if($edit_rec == 1) {
		$_SESSION['msg'] = "Listing Updated Successfully";
		//echo $db_fun->getListingsRemaining($mfa['user_id'])."listings remaining. PREVIOUS STATUS:".$status_num."current status:".$status;
		if ($_REQUEST["publish"] == "yes") {
			echo "<script>window.location = '".SAURL."index.php?p=post_ebay&pram1=".$param."&pram2=".$_REQUEST['user_id']."';</script>";
		} else {
		if (empty($param)) {
		$param = 200;
		}
		$sql_prev_next = mysql_query("
		(SELECT ui_image,ui_id FROM user_images WHERE ui_id > ".$param." AND ui_status=0 ORDER BY ui_id ASC LIMIT 1)
		 UNION
		(SELECT ui_image,ui_id FROM user_images WHERE ui_id < ".$param." AND ui_status=0 ORDER BY ui_id DESC LIMIT 1)
		");
		$item_wing=array();
		$i=0;
		while ($row = mysql_fetch_array($sql_prev_next)) {
		$item_wing[$i]['ui_id'] = $row['ui_id'];
		$item_wing[$i]['ui_image'] = $row['ui_image'];
		$i++;
		}
		include("carousel.php");
		echo '<meta http-equiv="refresh" content="120;url='.SAURL.'index.php?p=images_listings">';
			//echo "<script>window.location = '".SAURL."index.php?p=images_listings';</script>";
		}
		exit;
	}
	
	}
?>