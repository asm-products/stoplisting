<?php
	/*
	===================================================================
	File Name: post_ebay_cron.php
	===================================================================
	*/
	session_start();
	// G-zip Compression	
	ini_set('zlib.output_compression', 'On');  
	ini_set('zlib.output_compression_level', '5');
	// Timezone Set
	date_default_timezone_set('America/New_York');

set_time_limit(0.25*60*60); // 15 minutes Quit Time
$time_start = microtime(true);  //Start Timer

	include('../con/config.php');
	include('classes/sql_queries.php');
	include('classes/db_queries.php');
	include('../../ebay_trading/tradingConstants.php');
	
	
	$select	=	new sql_function();	
	$db_fun	=	new db_queries();

function getAddItemString($addTitle, $addCatID, $addSPrice, $addPicture, $pictureURL_Base, $addDesc, $user_token, $condition, $duration, $listing_type, $paypal, $zipcode, $quantity, $handling_time = 3, $shipping_type, $shipping_service, $shipping_cost, $returns_accepted, $refunds_option, $refunds_within, $shipping_carriers, $store_cat1 = NULL, $store_cat1_name = NULL, $store_cat2 = NULL, $store_cat2_name = NULL, $shipping_cost_additional = NULL, $shipping_package = NULL, $item_weight_lbs = 0, $item_weight_oz = 13, $specific_nodes, $ebay_auth_token, $returns_detail, $listing_id) {
	
	// Create unique id for adding item to prevent duplicate adds
	$uuid = md5(uniqid());
	
	// create the XML request
	$xmlRequest  = "<AddItemRequestContainer>";
	$xmlRequest .= "<Item>";
	$xmlRequest .= "<Title>" . htmlspecialchars($addTitle) . "</Title>";
	$xmlRequest .= "<Description>" . $addDesc . "</Description>";
	$xmlRequest .= "<PrimaryCategory>";
	$xmlRequest .= "<CategoryID>" . $addCatID . "</CategoryID>";
	$xmlRequest .= "</PrimaryCategory>";
	$xmlRequest .= "<StartPrice>" . $addSPrice . "</StartPrice>";
	$xmlRequest .= "<ConditionID>". $condition ."</ConditionID>";
	$xmlRequest .= "<CategoryMappingAllowed>true</CategoryMappingAllowed>";
	$xmlRequest .= "<Country>US</Country>";
	$xmlRequest .= "<Currency>USD</Currency>";	
	if (!empty($specific_nodes)) {
		$xmlRequest .= "<ItemSpecifics>";
            	$item_stats 	= json_decode($specific_nodes, true);
                for($i=0;$i < count($item_stats['names']);$i++) {
	                $xmlRequest .= "<NameValueList>";
		                $xmlRequest .= "<Name>".htmlspecialchars($item_stats['names'][$i])."</Name>";
		                $xmlRequest .= "<Value>".htmlspecialchars($item_stats['values'][$i])."</Value>";
	               	$xmlRequest .= "</NameValueList>";
                }
               $xmlRequest .= "</ItemSpecifics>";
	}
	$xmlRequest .= "<DispatchTimeMax>".$handling_time."</DispatchTimeMax>";
	$xmlRequest .= "<ListingDuration>".$duration."</ListingDuration>";
	$xmlRequest .= "<ListingType>".$listing_type."</ListingType>";
	$xmlRequest .= "<PaymentMethods>PayPal</PaymentMethods>";
	$xmlRequest .= "<PayPalEmailAddress>".$paypal."</PayPalEmailAddress>";
	$xmlRequest .= "<PictureDetails>";
	// echo array list of images
	$xmlRequest .= uploadhostedImages($addPicture, $ebay_auth_token, $pictureURL_Base);	
	$xmlRequest .= "</PictureDetails>";
	$xmlRequest .= "<PostalCode>" .$zipcode. "</PostalCode>";
	$xmlRequest .= "<Quantity>". $quantity ."</Quantity>";
	$xmlRequest .= "<ReturnPolicy>";
	$xmlRequest .= "<ReturnsAcceptedOption>".$returns_accepted."</ReturnsAcceptedOption>";
	// values  'MoneyBack', 'MoneyBackOrExchange', and 'MoneyBackOrReplacement'.
	$xmlRequest .= "<RefundOption>".$refunds_option."</RefundOption>";
	// Days_14, Days_30, Days_60, Months_1
	$xmlRequest .= "<ReturnsWithinOption>".$refunds_within."</ReturnsWithinOption>";
	$xmlRequest .= "<Description>" . $returns_detail . "</Description>";
	$xmlRequest .= "<ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>";
	$xmlRequest .= "</ReturnPolicy>";
	$xmlRequest .= "<ShippingDetails>";
	$xmlRequest .= "<ShippingType>". $shipping_type ."</ShippingType>";
	
	/// Optional if calculated shipping option is activated lolcats and lolbats my friend, lcs and lbs.
	if (strpos($shipping_type,'Calculated') !== false) {
	$xmlRequest .= "<CalculatedShippingRate>";
	$xmlRequest .= "<OriginatingPostalCode>".$zipcode."</OriginatingPostalCode>";
	$xmlRequest .= "<ShippingPackage>".$shipping_package."</ShippingPackage>";
	$xmlRequest .= "<WeightMajor unit=\"lbs\">".$item_weight_lbs."</WeightMajor>";
	$xmlRequest .= "<WeightMinor unit=\"oz\">".$item_weight_oz."</WeightMinor>";
	$xmlRequest .= "</CalculatedShippingRate>";
	} // end calculated shippings stuff
	
	
	$xmlRequest .= "<ShippingServiceOptions>";
	/*
	This integer value controls the order (relative to other shipping services) in which the corresponding ShippingService will appear in the View Item and Checkout page. Sellers can specify up to five international shipping services (with five InternationalShippingServiceOption containers), so valid values are 1, 2, 3, 4, and 5. A shipping service with a ShippingServicePriority value of 1 appears at the top. Conversely, a shipping service with a ShippingServicePriority value of 5 appears at the bottom of a list of five shipping service options.
	This field is applicable to Flat and Calculated shipping. This field is not applicable to Half.com listings. 
	*/
	$xmlRequest .= "<ShippingServicePriority>". $shipping_carriers ."</ShippingServicePriority>";
	// Yes, More than one is allowed, figure that out later. probably a "add shipping method" and then here use array similar to item specifics
	$xmlRequest .= "<ShippingService>". $shipping_service ."</ShippingService>";
	$xmlRequest .= "<ShippingServiceCost currencyID=\"USD\">". $shipping_cost ."</ShippingServiceCost>";
        if (($quantity > 1) && (!is_null($shipping_cost_additional))) {
		$xmlRequest .= "<ShippingServiceAdditionalCost currencyID=\"USD\">". $shipping_cost_additional ."</ShippingServiceAdditionalCost>";
        }
	$xmlRequest .= "</ShippingServiceOptions>";
	$xmlRequest .= "</ShippingDetails>";
	$xmlRequest .= "<Site>US</Site>";
	$xmlRequest .= "<UUID>" . $uuid . "</UUID>";
	//make this conditional on if the user has an ebay store, or ie, storecat is not null.
	/*if(!is_null($store_cat1)) {
		$xmlRequest .= "<Storefront>";
		if(!is_null($store_cat2)) {
			$xmlRequest .= "<StoreCategory2ID>" . $store_cat2 . "</StoreCategory2ID>";
     			$xmlRequest .= "<StoreCategory2Name>" . $store_cat2_name . "</StoreCategory2Name>";
		}
		$xmlRequest .= "<StoreCategoryID>" . $store_cat1 . "</StoreCategoryID>";
     		$xmlRequest .= "<StoreCategoryName>" . $store_cat1_name . "</StoreCategoryName>";
		$xmlRequest .= "</Storefront>";
	}*/
	$xmlRequest .= "</Item>";
	$xmlRequest .= "<MessageID>".$listing_id."</MessageID>";
	$xmlRequest .= "</AddItemRequestContainer>";
	
	return $xmlRequest;
}




function uploadhostedImages($addPicture, $ebay_auth_token, $pictureURL_Base) {
	$image_set = "";
	$photos = explode("-", $addPicture);
	for($i=0;$i<count($photos);$i++) {

	$xmlRequest  = "";	
	$xmlRequest .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
	$xmlRequest .= "<UploadSiteHostedPicturesRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
	
  $xmlRequest .= "<ExternalPictureURL>".$pictureURL_Base.$photos[$i]."</ExternalPictureURL>";
	$xmlRequest .= "<PictureName>".$photos[$i]."</PictureName>";  
	$xmlRequest .= "<RequesterCredentials>"; 
	$xmlRequest .= "<eBayAuthToken>".$ebay_auth_token."</eBayAuthToken>"; 
	$xmlRequest .= "</RequesterCredentials>"; 
	$xmlRequest .= "<ErrorLanguage>en_US</ErrorLanguage>";
	$xmlRequest .= "<WarningLevel>High</WarningLevel>";
	$xmlRequest .= "</UploadSiteHostedPicturesRequest>";
	
	$headers = array(
		'X-EBAY-API-SITEID:'.SITEID,
		'X-EBAY-API-CALL-NAME:UploadSiteHostedPictures',
		'X-EBAY-API-REQUEST-ENCODING:XML',
		'X-EBAY-API-COMPATIBILITY-LEVEL:' . API_COMPATIBILITY_LEVEL,
		'X-EBAY-API-DEV-NAME:' . API_DEV_NAME,
		'X-EBAY-API-APP-NAME:' . API_APP_NAME,
		'X-EBAY-API-CERT-NAME:' . API_CERT_NAME,
		'Content-Type: text/xml;charset=utf-8'
	);
	
	// initialize our curl session
	$session  = curl_init(API_URL);

	// set our curl options with the XML request
	curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($session, CURLOPT_POST, true);
	curl_setopt($session, CURLOPT_POSTFIELDS, $xmlRequest);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// execute the curl request
	$responseXML = curl_exec($session);
	
	// close the curl session
	curl_close($session);

		$xmlResponse 	= simplexml_load_string($responseXML);
		// Verify that the xml response object was created
		if ($xmlResponse) {
//			if ($xmlResponse->SiteHostedPictureDetails->Ack == "Success") { 
				$image_set .= "<PictureURL>".$xmlResponse->SiteHostedPictureDetails->FullURL."</PictureURL>";
//			}
		}
	} // End For Loop
	return $image_set;
}


/*
NEW PLAN:

USING THE ADDITEMS API, we are GONNA upload 5 items at a time and set the limit to 1 hour still, so cron runs every hour.

comes in every 15 minutes and collects the new stuff that was added during this time.
- the cron gathers in sets of 5 items at a time  lets make it bring in 10 sets of 5 every 15 minutes.
which means it can do 200 listings per hour, which is not bad. and 
Each set of 5 in 1.5 minutes.

the cron checks the time all the time. it keeps track of the run time, and also the average time of the calls. the set calls average changes all the time.
So before doing the next call, it will check to see if there is enough time left to do it. If there is not enough estimated time, the file quits.
If there is ample time, the call will request some more sql sets in accordance to how much time it has left.

If there is an error with an item (a true error, not a warning), then it should be recorded and saved to a separate database table. The item should then be set to rejected with the fault falling on admin to fix.


*/

# Get All Current Entries
$sql	= "SELECT * FROM `user_images` WHERE `ui_status` = 7 ORDER BY `modified` DESC LIMIT 50";  //Brings in 50 listings 5 x 10 sets
$q		= mysql_query($sql);
$num_entries    = mysql_num_rows($q);
if ($num_entries < 1 ){exit;}
$count_total    = 0; //total number of overall listings
$count 		= 0; //number of listings counter - set of 5
$additem_data 	= "";
$rejects	= "";
$accepted 	= "";
$ui_lister 	= array();
$remainder      = $num_entries % 5;
$stop_num	= $num_entries - $remainder;

while ($listing_info = mysql_fetch_array($q)) {
	$user_info=$db_fun->getUserInfo($listing_info['user_id']);
	if ($listing_info['temp_id'] !== 0) {
		$template=$listing_info['temp_id'];
	} else {
		// Get default
		$template=$user_info['temp_id'];
	}
		
	# Get Template
	$qt	  			= mysql_query("SELECT temp_detail from templates where temp_id='".$template."'");
	$mfa_temp			= mysql_fetch_array($qt);
	$html 				= $mfa_temp['temp_detail'];	
	
	//$pictureURL_Base 		= SURL.'upload_dropbox/';
	$pictureURL_Base  		= 'http://agapeworks.x10.mx/dash/stoplisting/admin/image-resizer-master/getImage.php?publish&amp;url=';
	#User Chosen Defaults
	$paypal 	  		= $user_info['paypal_id'];
	$user_token 	  		= $user_info['ebay_token'];
	$zipcode 	  		= (int) $user_info['zipcode'];
	$returns_accepted 		= trim($user_info['returns_accepted']); 
	$refunds_option 		= trim($user_info['refunds_option']); 
	$refunds_within 		= trim($user_info['refunds_within']); 
	$shipping_carriers 		= (int) $user_info['shipping_carriers'];
	$ebay_auth_token 		= trim($user_info['ebay_token']);
	
	#Shipping Info - If Taken from the actual listing, it gets set as the one.
	$shipping_cost 	  		= trim($listing_info['shipping_cost']);
	$shipping_cost_additional 	= trim($listing_info['shipping_cost_additional']);
	$shipping_service 		= trim($listing_info['shipping_service']);
	$shipping_type 	  		= trim($listing_info['shipping_type']);
	$handling_time 	  		= (int) $listing_info['handling_time'];
	$listing_type 	  		= trim($listing_info['listing_type']);
	$quantity 	  		= (int) $listing_info['quantity'];
	
	#User Defaults - Activated if a user listing setting doesnt have the value.
	if(empty($shipping_cost)) 		{$shipping_cost 		= trim($user_info['shipping_cost']);}
	if(empty($shipping_cost_additional)) 	{$shipping_cost_additional 	= trim($user_info['shipping_cost_additional']);}
	if(empty($shipping_service)) 		{$shipping_service 		= trim($user_info['shipping_service']);}
	if(empty($shipping_type)) 		{$shipping_type 		= trim($user_info['shipping_type']);}
	if(empty($handling_time)) 		{$handling_time 		= (int) $user_info['handling_time'];}
	if(empty($listing_type)) 		{$listing_type 			= trim($user_info['listing_type']);}

	#Setup HTML TEMPLATE
	$html	=	str_ireplace("{Title}", $listing_info['title'], $html);
	$html	=	str_ireplace("{Detail}", $listing_info['detail'], $html);
	if ($listing_info['handling_time'] == "0") {
		$handling_time_string = "Same Day Shipping";
	} else if ($listing_info['handling_time'] == "1") {
		$handling_time_string = "1 Business Day";
	} else {
		$handling_time_string = $listing_info['handling_time']." Business Days";
	}
	
	$html	=	str_ireplace("{Handling}", $handling_time_string, $html);
	$html	=	str_ireplace("{Ship}", $user_info['ship_detail'], $html);
	$html	=	str_ireplace("{Pricing}", $user_info['pricing_detail'], $html);
	$html	=	str_ireplace("{Paypal Logo}", '<!-- PayPal Logo --><table align="center" border="0" cellpadding="10" cellspacing="0"><tbody><tr><td align="center"></td></tr><tr><td align="center"><a href="https://www.paypal.com/webapps/mpp/paypal-popup"><img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_SbyPP_mc_vs_dc_ae.jpg" alt="PayPal Acceptance Mark" border="0"></a></td></tr></tbody></table><!-- PayPal Logo -->', $html);
	
	$item_stats_list = "<ul id=\"item_specifics\">";
		$item_stats 	= json_decode($listing_info['item_stats'], true);
	        for($i=0;$i < count($item_stats['names']);$i++) {
		                $item_stats_list .= "<li>".htmlentities(str_replace("&#39;", "'", $item_stats['names'][$i])).": ".htmlentities(str_replace("&#39;", "'", $item_stats['values'][$i]))."</li>";
	        }
		$item_stats_list .= "</ul>";
	$html	=	str_ireplace("{Item Specs}", $item_stats_list, $html);
	if (empty($user_info['ebay_user'])) {
		$store_name = "Welcome";
	} else {$store_name = $user_info['ebay_user'];}
	
	$html	=	str_ireplace("{Store Name}", $store_name, $html);
	$photos = explode("-", $listing_info['ui_image']);
	$image_urls = "<ul id=\"gallery\">";
	for($i=0;$i<count($photos);$i++) {
	
		if ($i % 4 == 0) {
		$image_urls .='<br>';
		}
		$image_urls .='<li class="gallery_single"><img alt="'.$listing_info['title'].' | Photo #'.$i.'" src="http://agapeworks.x10.mx/dash/stoplisting/admin/image-resizer-master/getImage.php?app&amp;url='.$photos[$i].'"></img></li>';
		
	}
	$image_urls .= "</ul>";
	$html	=	str_ireplace("{Gallery}", $image_urls, $html);
	$html	=	str_ireplace("{Pricing}", $listing_info['pricing_detail'], $html); 
	
	if ( $user_info['returns_accepted'] == "ReturnsAccepted") {
		//$refunds_option_string 	= preg_split('/.(?=[A-Z])/',lcfirst(" ".$user_info['refunds_option']));
		//$refunds_option 	= implode(" ", $refund_options_string);
		$refunds_within_string 	= explode("_", $user_info['refunds_within']);
		$returns_detail = "<span style=\"font-weight:bold;text-align:center;\">Returns Are Accepted. ".$user_info['refunds_option']." will be given within ".$refunds_within_string[1]." ".$refunds_within_string[0]."</span>";
		
	} else { //No Policy
		$returns_detail = "No return policy. If you have an issue with an item, please contact me individually. If a return is granted, buyer will pay return shipping.";
	}
	$html	=	str_ireplace("{Return Policy}", $returns_detail, $html);
	$html	=	str_ireplace("{Ship Cost}", "$".$listing_info['shipping_cost'], $html); 
	$html	=	str_ireplace("{Barcode}", $listing_info['ui_barcode'], $html); 
	$html	=	str_ireplace("{Price}", "$".$listing_info['price'], $html); 
	
	
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
	
	$additem_data	.= getAddItemString($title, $categoryID, $startPrice, $pictureURL, $pictureURL_Base, $description, $user_token, $condition, $duration, $listing_type, $paypal, $zipcode, $quantity, $handling_time, $shipping_type, $shipping_service, $shipping_cost, $returns_accepted, $refunds_option, $refunds_within, $shipping_carriers, $store_cat1, $store_cat1_name, $store_cat2, $store_cat2_name, $shipping_cost_additional, $shipping_package, $item_weight_lbs, $item_weight_oz, $specific_nodes, $ebay_auth_token, $returns_detail, $listing_info['ui_id']);	
	
	$count++;
	$ui_lister[$count_total] = $listing_info['ui_id'];
	echo $html;
	
	if ($count_total > $stop_num ) {
		$count_param = $remainder; 
	} else {$count_param = 5; }

	if ($count >= $count_param) {
	$time_end = microtime(true);
	//dividing with 60 will give the execution time in minutes other wise seconds
	$execution_time = ($time_end - $time_start)/60;
	if ($execution_time > 12) { echo "Max Execution time of 12 minutes"; exit; } // if script is around 12 minutes running, just end it here.


		// Do the ebay call. 
		$listing_queue = array();
	
	// Create unique id for adding item to prevent duplicate adds
	$uuid = md5(uniqid());
	
	// create the XML request
	$xmlRequest  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	$xmlRequest .= "<AddItemsRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
	$xmlRequest .= "<ErrorLanguage>en_US</ErrorLanguage>";
	$xmlRequest .= "<WarningLevel>High</WarningLevel>";
	// add in the items.
	$xmlRequest .= $additem_data;
	
	$xmlRequest .= "<RequesterCredentials>";
	$xmlRequest .= "<eBayAuthToken>" . $user_token . "</eBayAuthToken>";
	$xmlRequest .= "</RequesterCredentials>";
	$xmlRequest .= "<WarningLevel>High</WarningLevel>";
	$xmlRequest .= "</AddItemsRequest>";
	//return $xmlRequest;
	//AUTH_TOKEN
	// define our header array for the Trading API call
	// notice different headers from shopping API and SITE_ID changes to SITEID
	$headers = array(
		'X-EBAY-API-SITEID:'.SITEID,
		'X-EBAY-API-CALL-NAME:AddItems',
		'X-EBAY-API-REQUEST-ENCODING:XML',
		'X-EBAY-API-COMPATIBILITY-LEVEL:' . API_COMPATIBILITY_LEVEL,
		'X-EBAY-API-DEV-NAME:' . API_DEV_NAME,
		'X-EBAY-API-APP-NAME:' . API_APP_NAME,
		'X-EBAY-API-CERT-NAME:' . API_CERT_NAME,
		'Content-Type: text/xml;charset=utf-8'
	);
	
	// initialize our curl session
	$session  = curl_init(API_URL);

	// set our curl options with the XML request
	curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($session, CURLOPT_POST, true);
	curl_setopt($session, CURLOPT_POSTFIELDS, $xmlRequest);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// execute the curl request
	$responseXML = curl_exec($session);
	
	// close the curl session
	curl_close($session);

		//echo $response;
		$xmlResponse 	= simplexml_load_string($responseXML);
		for ($i=0;$i < count($xmlResponse->AddItemResponseContainer);$i++) { // loop through all the additem responses
			$listing_queue['item_id'][$i] = $xmlResponse->AddItemResponseContainer[$i]->ItemID;
			// Calculate fees for listing
			// loop through each Fee block in the Fees child node
			$totalFees = 0;
			$fees = $xmlResponse->AddItemResponseContainer[$i]->Fees;
			foreach ($fees->Fee as $fee) {
				$totalFees += $fee->Fee;
			}
			$listing_queue['fees'][$i] = $totalFees;
			$listing_queue['start_time'][$i] = $xmlResponse->AddItemResponseContainer[$i]->StartTime;
			$listing_queue['end_time'][$i] = $xmlResponse->AddItemResponseContainer[$i]->EndTime;
			$listing_queue['ui_id'][$i] = $ui_lister[$count_total];
	
				if ($xmlResponse->AddItemResponseContainer[$i]->Errors->ErrorClassification == "RequestError") {
					// Error on StopListing Side - Make Admin Fix.
						$listing_queue['error_code'][$i] 	= $xmlResponse->AddItemResponseContainer[$i]->Errors->ErrorCode;
						$listing_queue['error_message'][$i] = $xmlResponse->AddItemResponseContainer[$i]->Errors->LongMessage;
						
					// ADD Listing ID to Set of Rejects. (5)
					mysql_query("UPDATE `agapewor_stoplisting`.`user_images` SET `ui_status` = '5' WHERE `user_images`.`ui_id` =".$ui_lister[$count_total]);
				} else if ($xmlResponse->AddItemResponseContainer[$i]->Errors->ErrorClassification == "SystemError") {
					//Ebay Error. Ignore, and the cron should try it again
						$listing_queue['error_code'][$i] 	= $xmlResponse->AddItemResponseContainer[$i]->Errors->ErrorCode;
						$listing_queue['error_message'][$i] = $xmlResponse->AddItemResponseContainer[$i]->Errors->LongMessage;
				} else {
					// success, so change that status
					$accepted .= " `ui_id` =".$ui_lister[$count_total]." OR";
						mysql_query("UPDATE `agapewor_stoplisting`.`user_images` SET `ui_status` = '2' WHERE `user_images`.`ui_id` =".$ui_lister[$count_total]);
						$listing_queue['error_code'][$i] 	= "";
						$listing_queue['error_message'][$i] = "";
				}
				
						mysql_query('INSERT INTO `list_log`(`id`, `ui_id`, `ebay_item_id`, `fees`, `start_time`, `end_time`, `error_code`, `error_message`, `date`)
						VALUES (
						""
						,"'.$listing_queue['ui_id'][$i].'"
						,"'.$listing_queue['item_id'][$i].'"
						,"'.$listing_queue['fees'][$i].'"
						,"'.$listing_queue['start_time'][$i].'"
						,"'.$listing_queue['end_time'][$i].'"
						,"'.$listing_queue['error_code'][$i].'"
						,"'.$listing_queue['error_message'][$i].'"
						,NOW())');
						
		
		}					
		 // reset values
		$additem_data 	= "";
		$rejects		= "";
		$accepted 		= "";
		$count 			= 0;
		$ui_lister 		= array();
		$listing_queue 	= array();
	}
	$count_total++;
}

$time_end = microtime(true);
//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start)/60;
echo "Finished ".$count_total." Queries in ".$execution_time." minutes."
?>