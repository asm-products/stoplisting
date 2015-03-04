<?php //ob_start(); error_reporting(E_ALL); ini_set('display_errors', 'On'); 
class db_queries
{
	function new_admin($name,$email,$site_url,$username,$password)
	{
		$row = mysql_query("insert into admin (name,email, username, password, site_url, status) values ('".$name."','".$email."','".$username."','".$password."','".$site_url."','1')") or die(mysql_error());
		
		return $lastid	=	mysql_insert_id();
		}
		
	function add_new_cat($cat_name,$cat_desc,$today_date,$cat_status)
	{
		$row = mysql_query("insert into tbl_image_cat (name,cat_desc, cat_date, status) values ('".$cat_name."','".$cat_desc."','".$today_date."','".$cat_status."')") or die(mysql_error());
		
		return $lastid	=	mysql_insert_id();
		}
		
	function edit_cat($parm,$cat_name,$cat_desc,$cat_status)	
	{
		$row = mysql_query("UPDATE tbl_image_cat SET name='".$cat_name."', cat_desc='".$cat_desc."', status='".$cat_status."' WHERE id='".$parm."'") or die(mysql_error());
		
		return 1;
		}
	function add_new_client($array)
	{
		
		  
		$column=implode(',', array_keys($array));
		$values="'".implode("','", array_values($array))."'";
		$row = mysql_query("insert into users ($column) values ($values)") or die(mysql_error());
		
		return $lastid	=	mysql_insert_id();
	}
		
	function edit_client($parm,$cl_name,$email, $plan, $template, $price, $offercode, $order_no, $db_app_key, $db_secret_key, $ebay_dev, $ebay_app, $ebay_cert, $ebay_token, $paypal_id, $password, $cl_status)	
	{
		$row = mysql_query("UPDATE users SET name='".$cl_name."', email='".$email."', plan='".$plan."', temp_id='".$template."', price='".$price."', offercode='".$offercode."', order_no='".$order_no."', db_app_key='".$db_app_key."', db_secret_key='".$db_secret_key."', ebay_dev='".$ebay_dev."', ebay_app='".$ebay_app."', ebay_cert='".$ebay_cert."', ebay_token='".$ebay_token."', paypal_id='".$paypal_id."', password='".$password."', status='".$cl_status."' WHERE id='".$parm."'") or die(mysql_error());
		
		return 1;
		}	
	function delete_function($table_name,$id)
	{
		$row = mysql_query("DELETE FROM ".$table_name." WHERE id='".$id."'") or die(mysql_error());
		
		return 1;
		}
	
	function trashSingle($table, $column, $value){
		
		$row = mysql_query("DELETE FROM ".$table." WHERE $column='".$value."'") or die(mysql_error());
		
		return 1;
		
	}
		
	function getAllUsers(){
		
		$row = mysql_query("select * from users order by id ASC") or die(mysql_error());
		return $row;
	}
	
	function geteBayCategories(){
		
		$row = mysql_query("select * from ebay_categories order by ebay_id ASC") or die(mysql_error());
		return $row;
	}
	
	function getAllTemplates(){
		
		$row = mysql_query("select * from templates order by temp_title ASC") or die(mysql_error());
		return $row;
	}
	
	function getAllUserTemplates($id){
		
		$row = mysql_query("select * from templates where user_id='".$id."' or user_id=0 order by temp_title ASC") or die(mysql_error());
		return $row;
	}
	
	function addPackage($plan,$price,$limit,$plan_url_code,$gum_id){
		
		$row = mysql_query("insert into plans (plan_product_id,plan_title,plan_price,plan_limit,plan_url_code) values ('".$gum_id."','".$plan."','".$price."','".$limit."','".$plan_url_code."')") or die(mysql_error());
		
		return $lastid	=	mysql_insert_id();
	}
	
	function addTemplate($user_id,$title,$detail){
		
		$row = mysql_query("insert into templates (user_id,temp_title,temp_detail) values ($user_id,'".$title."','".$detail."')") or die(mysql_error());
		
		return $lastid	=	mysql_insert_id();
	}
	
	function getPackages(){
		
		$row=mysql_query("select * FROM plans order by plan_id ASC") or die(mysql_error());
		return $row;
		
	}
	
	function updatePackage($plan,$price,$limit,$plan_url_code,$gum_id,$parm){
		
		$row = mysql_query("UPDATE plans SET plan_title='".$plan."', plan_price='".$price."', plan_limit='".$limit."', plan_url_code='".$plan_url_code."', plan_product_id='".$gum_id."'  WHERE plan_id='".$parm."'") or die(mysql_error());
		
		return 1;
		
	}
	function updateTemplate($title,$detail,$param){
		
		$row = mysql_query("UPDATE templates SET temp_title='".$title."', temp_detail='".$detail."' WHERE temp_id='".$param."'") or die(mysql_error());
		
		return 1;
		
	}
	
	function getPlan($id){
		
		$row = mysql_query("select plan_title from plans where plan_id=$id") or die(mysql_error());
		$mfa=mysql_fetch_array($row);
		return $mfa['plan_title'];
		
	}
	
	function getTemplate($id){
		
		$row = mysql_query("select temp_title from templates where temp_id=$id") or die(mysql_error());
		$mfa=mysql_fetch_array($row);
		return $mfa['temp_title'];
		
	}
	
	function getTemplateDetail($id){
		
		$row = mysql_query("select temp_detail from templates where temp_id=$id") or die(mysql_error());
		$mfa=mysql_fetch_array($row);
		return $mfa['temp_detail'];
		
	}
	
	function getUser($id){
		
		$row = mysql_query("select email from users where id=$id") or die(mysql_error());
		$mfa=mysql_fetch_array($row);
		return $mfa['email'];
		
	}
	
	function getUserInfo($id){
		
		$row = mysql_query("select * from users where id=$id") or die(mysql_error());
		$array=mysql_fetch_array($row);
		return $array;
		
	}
	
	function getUserDefaultTemplate($id){
		
		$row = mysql_query("select temp_id from users where id=$id") or die(mysql_error());
		$array=mysql_fetch_array($row);
		return $array['temp_id'];
		
	}
	
	function getCategoryTitle($id){
		
		$row = mysql_query("select ebay_cat_title from ebay_categories where ebay_cat_id=$id") or die(mysql_error());
		$mfa=mysql_fetch_array($row);
		return $mfa['ebay_cat_title'];
		
	}
	
	function updateListing($category_id,$title,$detail,$price,$status,$param,$item_specifics = '', $condition = 3000, $template = 0, $image_order = NULL, $duration, $listing_type, $store_cat1 = NULL, $store_cat2 = NULL, $quantity, $store_cat1_name = NULL, $store_cat2_name = NULL, $shipping_type, $item_weight_lbs, $item_weight_oz, $shipping_package, $shipping_cost, $shipping_cost_additional, $shipping_service, $handling_time){
	
		if(!is_null($item_specifics)) {
			$item_specifics = "item_stats='".$item_specifics."',";
		}
		if(!is_null($image_order)) {
			$image_order = "ui_image='".$image_order."',";
		}
		$row = mysql_query("UPDATE user_images SET 
		category_id='".$category_id."', 
		title='".$title."', 
		detail='".$detail."', 
		price='".$price."', 
		ui_status='".$status."', 
		temp_id='".$template."',
		duration='".$duration."',
		listing_type='".$listing_type."',
		storecat1='".$store_cat1."',
		storecat2='".$store_cat2."',
		quantity='".$quantity."',	
		storecat1_name='".$storecat1_name."',
		storecat2_name='".$storecat2_name."',
		shipping_cost='".$shipping_cost."',
		shipping_cost_additional='".$shipping_cost_additional."',
		shipping_service='".$shipping_service."',
		shipping_type='".$shipping_type."',
		shipping_package='".$shipping_package."',
		handling_time='".$handling_time."',
		item_weight_lbs	='".$item_weight_lbs."',
		item_weight_oz='".$item_weight_oz."',
		".$image_order."
		".$item_specifics."
		modified=NOW(),
		item_condition=".$condition."
		 WHERE ui_id='".$param."'") or die(mysql_error());
		return 1;
		
	}
	
	function createListing($category_id,$title,$detail,$price,$status,$param,$item_specifics = '', $condition = 3000, $template = 0, $image_order = NULL, $duration, $listing_type, $store_cat1 = NULL, $store_cat2 = NULL, $quantity, $store_cat1_name = NULL, $store_cat2_name = NULL, $shipping_type, $item_weight_lbs, $item_weight_oz, $shipping_package, $shipping_cost, $shipping_cost_additional, $shipping_service, $handling_time){
		$row = mysql_query("INSERT INTO `user_images`(`ui_id`, `user_id`, `category_id`, `title`, `detail`, `price`, `ship_detail`, `pricing_detail`, `ui_image`, `ui_barcode`, `ui_dropbox`, `ui_status`, `path`, `client_mtime`, `modified`, `ui_date`, `item_stats`, `item_condition`, `temp_id`, `duration`, `listing_type`, `storecat1`, `storecat2`, `storecat1_name`, `storecat2_name`, `quantity`, `shipping_cost`, `shipping_cost_additional`, `shipping_service`, `shipping_type`, `shipping_package`, `handling_time`, `item_weight_lbs`, `item_weight_oz`) 
		VALUES ('','".$param."','".$category_id."','".$title."','".$detail."','".$price."','','','".$image_order."','','','".$status."','','',NOW(),NOW(),'".$item_specifics."','".$condition."','".$template."','".$duration."','".$listing_type."','".$store_cat1."','".$store_cat2."','".$storecat1_name."','".$storecat2_name."','".$quantity."','".$shipping_cost."','".$shipping_cost_additional."','".$shipping_service."','".$shipping_type."','".$shipping_package."','".$handling_time."','".$item_weight_lbs."','".$item_weight_oz."')") or die(mysql_error());
		return 1;
	}

	function updateListingsRemaining($num, $method, $user_id) {
		if($method == "add") {
			$row = mysql_query("UPDATE users SET listings_remaining=listings_remaining+".$num." WHERE id='".$user_id."'") or die(mysql_error());
		} else if ($method == "subtract") {
			$row = mysql_query("UPDATE users SET listings_remaining=listings_remaining-".$num." WHERE id='".$user_id."'") or die(mysql_error());
		}
	}
	function getListingsRemaining($user_id) {
		$row = mysql_fetch_array(mysql_query("SELECT listings_remaining from users WHERE id=".$user_id));
		return $row['listings_remaining'];
	}
	
	function getListingStatus($param) {
		$row = mysql_fetch_array(mysql_query("SELECT ui_status from user_images WHERE ui_id=".$param));
		return $row['ui_status'];
	}
	function getListingInfo($id){
		
		$row = mysql_query("select * from user_images where ui_id=$id") or die(mysql_error());
		$array=mysql_fetch_array($row);
		return $array;
		
	}
	
	function getBarcode($img){
		
		
		
		
		

  // Enter your data here.
  // You need an Application ID and Application Password,
  // which can be created during registration.
  // If you are not registered yet, register
  // at http://cloud.ocrsdk.com/Account/Register 
  // Application ID and Application Password are passed
  // to Cloud OCR server with each request.
  $applicationId = 'binarylogix';
  $password = '+pu0idrGoHC9SlTPResoorRs';
  $fileName = $img;

  ////////////////////////////////////////////////////////////////
  // 1.a Send an image with barcodes to Cloud OCR server 
  //     using processImage call 
  //     with barcodeRecognition profile as a parameter,
  //     or
  // 1.b Send an image of a barcode to Cloud OCR server 
  //     using processBarcodeField call.
  // 2.  Get response as XML.
  // 3.  Read taskId from XML.
  ////////////////////////////////////////////////////////////////

  // Get path to the file that you are going to process.
  $local_directory='/home/agapewor/public_html/dash/stoplisting/upload_dropbox/';
  
  // Using the processImage method.
  // Use barcodeRecognition profile to extract barcode values.
  // Save results in XML (you can use any other available output format).
  // See details in API Reference.
  $url = 'http://cloud.ocrsdk.com/processImage?profile=barcodeRecognition&exportFormat=xml';

  // Using the processBarcodeField method.
  // Specify the region of a barcode (by default, the whole image is recognized), 
  // barcode type, and other parameters.
  // See details in API Reference.
  // $url = 'http://cloud.ocrsdk.com/processBarcodeField?region=0,0,100,100&barcodeType=pdf417';

  // Send HTTP POST request and get XML response.
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERPWD, "$applicationId:$password");
  curl_setopt($ch, CURLOPT_POST, 1);
  $post_array = array(
    "my_file"=>"@".$local_directory.$fileName
  );
  @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array); 
  $response = curl_exec($ch);
  curl_close($ch);

  // Parse XML response.
  $xml = simplexml_load_string($response);
  $arr = $xml->task[0]->attributes();

  // Task id.
  $taskid = $arr["id"]; 

  /////////////////////////////////////////////////////////////////
  // 4. Get task information in a loop until task processing finishes.
  // 5. If response contains "Completed" status, extract URL with result.
  // 6. Download recognition result.
  /////////////////////////////////////////////////////////////////

  $url = 'http://cloud.ocrsdk.com/getTaskStatus';
  $qry_str = "?taskid=$taskid";

  // Check task status in a loop until it is "Completed".
  do
  {
    sleep(5);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url.$qry_str);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, "$applicationId:$password");
    $response = curl_exec($ch);
    curl_close($ch);

    // Parse XML.
    $xml = simplexml_load_string($response);
    $arr = $xml->task[0]->attributes();
  }
  while($arr["status"] != "Completed");

  // Result is ready. Download it.

  $url = $arr["resultUrl"]; 
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
  curl_close($ch);
  $deXml = simplexml_load_string($response);
  $deJson = json_encode($deXml);
  $xml_array = json_decode($deJson,TRUE);
  //echo '<pre>'.print_r($xml_array,true).'</pre>';
  return @$xml_array['page']['block']['text']['par']['line']['formatting'];

		
		
		
	}
	
	
function getSwank($keyword, $limit){
		
require_once('../swank/finding.php');		
$ebay = new ebay();		
$result=$ebay->findProduct('findCompletedItems', $keyword, $limit);
$count=0;

$time=array();
$array=array();
/*print "<pre>";
echo $result['findCompletedItemsResponse'][0]['paginationOutput'][0][totalEntries][0]." total results available";
print "</pre>";*/
foreach($result as $value){
	foreach($value as $value1){
		foreach($value1['searchResult'][0]['item'] as $value2){
			
			$price=0;
			$stime='';
			$etime='';
			$temp=array();
			
			$temp['title']=trim($value2['title'][0]);
			foreach($value2['sellingStatus'][0]['convertedCurrentPrice'] as $value3){
				
				$price=$value3['__value__'];
				$temp['price']=$price;
				
			
			}
			
			foreach($value2['listingInfo'][0]['startTime'] as $value4){
				
				$stime=$value4;
				$stime=explode('T', $stime);
				$stime=trim($stime[0]);
				$temp['start_date']=$stime;
			}
			
			foreach($value2['listingInfo'][0]['endTime'] as $value5){
				
				$etime=$value5;
				$etime=explode('T', $etime);
				$etime=trim($etime[0]);
				$temp['end_date']=$etime;
			
			}
			
				$temp['image']=$value2['galleryURL'][0];
				$array[]=$temp;
		}
	}
}
//echo '<pre>'.print_r($array,true).'</pre><hr>';

$price=0; 
$swank_points = 0;
$swank_points_calc1 = 0;
$swank_points_calc2 = 0;
$swank_points_calc3 = 0;

foreach($array as $value){
		$price += $value['price'];
		if ($swank_points_calc1 < 33) {
			// Difference between the end date and todays date
			$date1 = new DateTime(date("Y-m-d H:i:s")); //todays date
			$date2 = new DateTime($value['end_date']);
			
			$diff = $date2->diff($date1)->format("%a");
			
			if($diff==0){
				$swank_points_calc1 += 3;
			}
			if($diff==1){	
				$swank_points_calc1 += 1.5;
			}
			if($diff>1 && $diff<=7){	
				$swank_points_calc1 += .5;
			}
			if($diff>7 && $diff<=31){ //within the month	
				$swank_points_calc1 += .4;
			}
			if ($diff>32) { // stop operation if no recent buys within month range
			 break 3;
			}
		} else {$swank_points_calc1 = 33;} //impose limit
}
//echo 'Swank points calculation #1 total: '.$swank_points_calc1.' <hr>';
	
$avg_price	= $price/count($array);
$avg_profit	= round($avg_price*.85);

//echo 'Average Price: '.$avg_price.'<br />';
//echo 'Average Profit: '.$avg_profit.'<br />';

//
switch(true){
	case($avg_profit>=100):
	$swank_points_calc2+=22;
	break;
	case($avg_profit>=75):
	$swank_points_calc2+=15;
	break;
	case($avg_profit>=50):
	$swank_points_calc2+=11;
	break;
	case($avg_profit>=30):
	$swank_points_calc2+=5;
	break;
	case($avg_profit>=20):
	$swank_points_calc2+=4;
	break;
	case($avg_profit>=10):
	$swank_points_calc2+=2;
	break;
}
//echo 'Current Swank via average profit:' .$swank_points_calc2 .'<hr>';

$latest_price 	= $array[0]['price'];
$latest_profit	= round($latest_price*.85);
//echo 'Latest Price: '.$latest_price;
//echo 'Latest Profit: '.$latest_profit;
//calculate swank rank for latest profit
switch(true){
	case($latest_profit>=150):
		$swank_points_calc2+=11.5;
	break;
	case($latest_profit>=100):
		$swank_points_calc2+=11;
	break;
	case($latest_profit>=75):
		$swank_points_calc2+=8;
	break;
	case($latest_profit>=50):
		$swank_points_calc2+=5.5;
	break;
	case($latest_profit>=30):
		$swank_points_calc2+=3.3;
	break;
	case($latest_profit>=20):
		$swank_points_calc2+=2.2;
	break;
	case($latest_profit>=10):
		$swank_points_calc2+=1.1;
	break;
}

//echo 'Current Swank via Latest profit:' .$swank_points_calc2 .'<hr>';
// Final Calculation of last 3 trends - Final 33 points\

$secondary_combo_avg	= (($array[1]['price'] + $array[2]['price'] + $array[3]['price'])/3);
$secondary_avg_profit	= $secondary_combo_avg*.85;
$price_trend 			= round((($latest_profit-$secondary_avg_profit)/$secondary_avg_profit)*100);

if ($price_trend > 0) {
	//positive percentage trend
	$swank_points_calc3	= round($price_trend/3);
	if ($swank_points_calc3 > 33) {$swank_points_calc3 = 33;} // limit cap
} else {
	//negative percentage trend
	$swank_points_calc3	= round($price_trend/5);
	if ($swank_points_calc3 < -20) {$swank_points_calc3 = -20;} // limit cap
}

$swank_points	=	$swank_points_calc1+$swank_points_calc2+$swank_points_calc3; // Final Swank Score

return $swank_points;
		
		
		
		
		
	}
	
	function thisWeekListing($user_id){
		
		$sql="SELECT count(*) as total_this_week FROM user_images WHERE user_id=$user_id AND  YEARWEEK(`ui_date`, 1) = YEARWEEK(CURDATE(), 1)";
		$query=mysql_query($sql);
		$mfa=mysql_fetch_array($query);
		return $mfa['total_this_week'];
	
	}
	
	function needMoreInfo($user_id){
		
		$sql="SELECT count(*) as total FROM user_images WHERE user_id=$user_id AND ui_status=3";
		$query=mysql_query($sql);
		$mfa=mysql_fetch_array($query);
		return $mfa['total'];
	
	}
	
	function readyForPublish($user_id){
		
		$sql="SELECT count(*) as total FROM user_images WHERE user_id=$user_id AND ui_status=1";
		$query=mysql_query($sql);
		$mfa=mysql_fetch_array($query);
		return $mfa['total'];
	
	}
	
	function getAddItem($addTitle, $addCatID, $addSPrice, $addPicture, $pictureURL_Base, $addDesc, $user_token, $condition, $duration, $listing_type, $paypal, $zipcode, $quantity, $handling_time = 3, $shipping_type, $shipping_service, $shipping_cost, $returns_accepted, $refunds_option, $refunds_within, $shipping_carriers, $store_cat1 = NULL, $store_cat1_name = NULL, $store_cat2 = NULL, $store_cat2_name = NULL, $shipping_cost_additional = NULL, $shipping_package = NULL, $item_weight_lbs = 0, $item_weight_oz = 13, $specific_nodes, $ebay_auth_token, $returns_detail) {
	
	
	require_once ('../../../dash/ebay_trading/tradingConstants.php');
	/* Sample XML Request Block for minimum AddItem request
	see ... for sample XML block given length*/
	
	// Create unique id for adding item to prevent duplicate adds
	$uuid = md5(uniqid());
	
	// create the XML request
	$xmlRequest  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	$xmlRequest .= "<AddItemRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
	$xmlRequest .= "<ErrorLanguage>en_US</ErrorLanguage>";
	$xmlRequest .= "<WarningLevel>High</WarningLevel>";
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
	$xmlRequest .= $this->uploadhostedImages($addPicture, $ebay_auth_token, $pictureURL_Base);	
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
	$xmlRequest .= "<RequesterCredentials>";
	$xmlRequest .= "<eBayAuthToken>" . $user_token . "</eBayAuthToken>";
	$xmlRequest .= "</RequesterCredentials>";
	$xmlRequest .= "<WarningLevel>High</WarningLevel>";
	$xmlRequest .= "</AddItemRequest>";
	
	//return $xmlRequest;
	//AUTH_TOKEN
	// define our header array for the Trading API call
	// notice different headers from shopping API and SITE_ID changes to SITEID
	$headers = array(
		'X-EBAY-API-SITEID:'.SITEID,
		'X-EBAY-API-CALL-NAME:AddItem',
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

	// return the response XML
	return $responseXML;
}
	
	
	
		function getAddItemString($addTitle, $addCatID, $addSPrice, $addPicture, $pictureURL_Base, $addDesc, $user_token, $condition, $duration, $listing_type, $paypal, $zipcode, $quantity, $handling_time = 3, $shipping_type, $shipping_service, $shipping_cost, $returns_accepted, $refunds_option, $refunds_within, $shipping_carriers, $store_cat1 = NULL, $store_cat1_name = NULL, $store_cat2 = NULL, $store_cat2_name = NULL, $shipping_cost_additional = NULL, $shipping_package = NULL, $item_weight_lbs = 0, $item_weight_oz = 13, $specific_nodes, $ebay_auth_token, $returns_detail, $listing_id) {
	
	
	require_once ('../../../dash/ebay_trading/tradingConstants.php');
	/* Sample XML Request Block for minimum AddItem request
	see ... for sample XML block given length*/
	
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
	$xmlRequest .= $this->uploadhostedImages($addPicture, $ebay_auth_token, $pictureURL_Base);	
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
	
	
	
	
function getStoreCategories($ebay_user_id, $ebay_auth_token) {
		
	require_once ('../../../dash/ebay_trading/tradingConstants.php');
	$xmlRequest  = "";	
	$xmlRequest .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
	$xmlRequest .= "<GetStoreRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">";
	$xmlRequest .= "<CategoryStructureOnly>true</CategoryStructureOnly>";
	$xmlRequest .= "<LevelLimit>3</LevelLimit>";
	$xmlRequest .= "<UserID>".$ebay_user_id."</UserID>"; 
	$xmlRequest .= "<RequesterCredentials>"; 
	$xmlRequest .= "<eBayAuthToken>".$ebay_auth_token."</eBayAuthToken>"; 
	$xmlRequest .= "</RequesterCredentials>"; 
	$xmlRequest .= "<ErrorLanguage>en_US</ErrorLanguage>";
	$xmlRequest .= "<WarningLevel>High</WarningLevel>";
	$xmlRequest .= "</GetStoreRequest>";
	
	$headers = array(
		'X-EBAY-API-SITEID:'.SITEID,
		'X-EBAY-API-CALL-NAME:GetStore',
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
			if ($xmlResponse->Ack == "Success") { 
				return $xmlResponse->Store->CustomCategories;
			}
			return NULL;
		}
	return $responseXML;
}



function uploadhostedImages($addPicture, $ebay_auth_token, $pictureURL_Base) {
	require_once ('../../../dash/ebay_trading/tradingConstants.php');
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
	
	
	
	
	
	
}
 
?>	