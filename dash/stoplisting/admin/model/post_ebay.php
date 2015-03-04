<?php 
if(isset($_GET['pram1']) && isset($_GET['pram2'])){	
	$id=intval(trim($_GET['pram1']));
	$listing_info=$db_fun->getListingInfo($id);
	
	$id=intval(trim($_GET['pram2']));
	$user_info=$db_fun->getUserInfo($id);
	
	if ($listing_info['temp_id'] !== 0) {
		$template=$listing_info['temp_id'];
	} else {
		// Get default
		$template=$user_info['temp_id'];
	}
}
$response ='';
if(isset($_REQUEST["action"])){
set_time_limit(0);
	//require_once '../trading/tradingConstants.php';
		
	# Get Template
	$sql	= "select temp_detail from templates where temp_id='".$template."'";
	$q	= mysql_query($sql);
	$mfa	= mysql_fetch_array($q);
	$html 	= $mfa['temp_detail'];	
	
	//$pictureURL_Base  	= SURL.'upload_dropbox/';
	$pictureURL_Base  	= 'http://agapeworks.x10.mx/dash/stoplisting/admin/image-resizer-master/getImage.php?publish&amp;url=';
	#User Chosen Defaults
	$paypal 	  	= $user_info['paypal_id'];
	$user_token 	  	= $user_info['ebay_token'];
	$zipcode 	  	= (int) $user_info['zipcode'];
	$returns_accepted 	= trim($user_info['returns_accepted']); 
	$refunds_option 	= trim($user_info['refunds_option']); 
	$refunds_within 	= trim($user_info['refunds_within']); 
	$shipping_carriers 	= (int) $user_info['shipping_carriers'];
	$ebay_auth_token 	= trim($user_info['ebay_token']);
	
	#Shipping Info - If Taken from the actual listing, it gets set as the one.
	// Note: you;ll need to make the editlisting page pickup the user_info defaults, if there are none of these saved for each listings
	// addslashes on storecat1 and 2 names , so that they dont break the db
	$shipping_cost 	  	= trim($listing_info['shipping_cost']);
	$shipping_cost_additional = trim($listing_info['shipping_cost_additional']);
	$shipping_service 	= trim($listing_info['shipping_service']);
	$shipping_type 	  	= trim($listing_info['shipping_type']);
	$handling_time 	  	= (int) $listing_info['handling_time'];
	$listing_type 	  	= trim($listing_info['listing_type']);
	$quantity 	  	= (int) $listing_info['quantity'];
	
	#User Defaults - Activated if a user listing setting doesnt have the value.
	if(empty($shipping_cost)) 	{$shipping_cost 	= trim($user_info['shipping_cost']);}
	if(empty($shipping_cost_additional)) 	{$shipping_cost_additional 	= trim($user_info['shipping_cost_additional']);}
	if(empty($shipping_service)) 	{$shipping_service 	= trim($user_info['shipping_service']);}
	if(empty($shipping_type)) 	{$shipping_type 	= trim($user_info['shipping_type']);}
	if(empty($handling_time)) 	{$handling_time 	= (int) $user_info['handling_time'];}
	if(empty($listing_type)) 	{$listing_type 		= trim($user_info['listing_type']);}

	#Setup HTML TEMPLATE
	$html=str_ireplace("{Title}", $listing_info['title'], $html);
	
	$html=str_ireplace("{Detail}", $listing_info['detail'], $html);
	if ($listing_info['handling_time'] == "0") {
		$handling_time_string = "Same Day Shipping";
	} else if ($listing_info['handling_time'] == "1") {
		$handling_time_string = "1 Business Day";
	} else {
		$handling_time_string = $listing_info['handling_time']." Business Days";
	}
	
	$html=str_ireplace("{Handling}", $handling_time_string, $html);
	$html=str_ireplace("{Ship}", $user_info['ship_detail'], $html);
	$html=str_ireplace("{Pricing}", $user_info['pricing_detail'], $html);
	$html=str_ireplace("{Paypal Logo}", '<!-- PayPal Logo --><table align="center" border="0" cellpadding="10" cellspacing="0"><tbody><tr><td align="center"></td></tr><tr><td align="center"><a href="https://www.paypal.com/webapps/mpp/paypal-popup"><img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_SbyPP_mc_vs_dc_ae.jpg" alt="PayPal Acceptance Mark" border="0"></a></td></tr></tbody></table><!-- PayPal Logo -->', $html);
	
	$item_stats_list = "<ul id=\"item_specifics\">";
		$item_stats 	= json_decode($listing_info['item_stats'], true);
	        for($i=0;$i < count($item_stats['names']);$i++) {
		                $item_stats_list .= "<li>".htmlentities(str_replace("&#39;", "'", $item_stats['names'][$i])).": ".htmlentities(str_replace("&#39;", "'", $item_stats['values'][$i]))."</li>";
	        }
		$item_stats_list .= "</ul>";
	$html=str_ireplace("{Item Specs}", $item_stats_list, $html);
	if (empty($user_info['ebay_user'])) {
		$store_name = "Welcome";
	} else {$store_name = $user_info['ebay_user'];}
	
	$html=str_ireplace("{Store Name}", $store_name, $html);
	$photos = explode("-", $listing_info['ui_image']);
	$image_urls = "<ul id=\"gallery\">";
	for($i=0;$i<count($photos);$i++) {
	
		if ($i % 4 == 0) {
		$image_urls .='<br>';
		}
		$image_urls .='<li class="gallery_single"><img alt="'.$listing_info['title'].' | Photo #'.$i.'" src="http://agapeworks.x10.mx/dash/stoplisting/admin/image-resizer-master/getImage.php?app&amp;url='.$photos[$i].'"></img></li>';
		
	}
	$image_urls .= "</ul>";
	$html=str_ireplace("{Gallery}", $image_urls, $html);
	$html=str_ireplace("{Pricing}", $listing_info['pricing_detail'], $html); 
	
	if ( $user_info['returns_accepted'] == "ReturnsAccepted") {
		//$refunds_option_string 	= preg_split('/.(?=[A-Z])/',lcfirst(" ".$user_info['refunds_option']));
		//$refunds_option 	= implode(" ", $refund_options_string);
		$refunds_within_string 	= explode("_", $user_info['refunds_within']);
		$returns_detail = "<span style=\"font-weight:bold;text-align:center;\">Returns Are Accepted. ".$user_info['refunds_option']." will be given within ".$refunds_within_string[1]." ".$refunds_within_string[0]."</span>";
		
	} else { //No Policy
		$returns_detail = "No return policy. If you have an issue with an item, please contact me individually. If a return is granted, buyer will pay return shipping.";
	}
	$html=str_ireplace("{Return Policy}", $returns_detail, $html);
	$html=str_ireplace("{Ship Cost}", "$".$listing_info['shipping_cost'], $html); 
	$html=str_ireplace("{Barcode}", $listing_info['ui_barcode'], $html); 
	$html=str_ireplace("{Price}", "$".$listing_info['price'], $html); 
	
	
	#Listing Data (varies per listing)
	$description 	 	= iconv("utf-8", "utf-8//ignore", "//<![CDATA[".str_ireplace(array("<br>","<BR>","<br/>","<BR/>"),"<BR></BR>",$html)."//]]>"); 
	
	//"//<![CDATA[".str_ireplace(array("<br>","<BR>","<br/>","<BR/>"),"<BR></BR>",$html)."//]]>";
	$title 		  	= stripslashes(trim($listing_info['title']));
	$categoryID 	  	= (int) $listing_info['category_id'];
	$startPrice 	  	= number_format($listing_info['price'], 2, '.', '');
	$pictureURL 	  	= trim($listing_info['ui_image']);
	$store_cat1 	  	= trim($listing_info['storecat1']);
	$store_cat2 	  	= trim($listing_info['storecat2']);
	$store_cat1_name  	= stripslashes(trim($listing_info['storecat1_name']));
	$store_cat2_name  	= stripslashes(trim($listing_info['storecat2_name']));
	$condition 	  	= (int) $listing_info['item_condition'];
	$duration 	  	= trim($listing_info['duration']);
	$item_weight_lbs	= (int) $listing_info['item_weight_lbs'];
	$item_weight_oz		= (int) $listing_info['item_weight_oz'];
	$shipping_package	= trim($listing_info['shipping_package']);
	$specific_nodes		= trim($listing_info['item_stats']);
	$returns_detail 	= "//<![CDATA[".$returns_detail."//]]>";
	
	//$response 	= $db_fun->uploadhostedImages($pictureURL, $ebay_auth_token, $pictureURL_Base);
	
	$response 	= $db_fun->getAddItem($title, $categoryID, $startPrice, $pictureURL, $pictureURL_Base, $description, $user_token, $condition, $duration, $listing_type, $paypal, $zipcode, $quantity, $handling_time, $shipping_type, $shipping_service, $shipping_cost, $returns_accepted, $refunds_option, $refunds_within, $shipping_carriers, $store_cat1, $store_cat1_name, $store_cat2, $store_cat2_name, $shipping_cost_additional, $shipping_package, $item_weight_lbs, $item_weight_oz, $specific_nodes, $ebay_auth_token, $returns_detail);	
	
	//echo $response;
	$xmlResponse 	= simplexml_load_string($response);
	//print_r($xmlResponse); 
	if ($xmlResponse) {
		$clean_response = "<div style='background-color:pink'>";
	}
		if ($xmlResponse->Ack == "Success") {
			// Display the item id number added
			$clean_response .= "<p>Successfully added item as item #" . $xmlResponse->ItemID . "<br/>";
			
			// Calculate fees for listing
			// loop through each Fee block in the Fees child node
			$totalFees = 0;
			$fees = $xmlResponse->Fees;
			foreach ($fees->Fee as $fee) {
				$totalFees += $fee->Fee;
			}
			$clean_response .= "Total Fees for this listing: " . $totalFees . ".</p>";
		} 
		else {
			$clean_response .= "<p>The AddItem called failed due to the following error(s):<br/>";
			foreach ($xmlResponse->Errors as $error) {
				$errCode = $error->ErrorCode;
				$errLongMsg = htmlentities($error->LongMessage);
				$errSeverity = $error->SeverityCode;
				$clean_response .= $errSeverity . ": [" . $errCode . "] " . $errLongMsg . "<br/>";
			}
			$clean_response .= "</p>";
		}
	$clean_response .= "</div>";
	$sql="update user_images set ui_status=2 where user_id='".$user_info['id']."' AND ui_id=".$listing_info["ui_id"]."";
	mysql_query($sql);
}?>