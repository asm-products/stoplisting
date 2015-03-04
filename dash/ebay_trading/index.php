<?php
set_time_limit(0);
ini_set("display_errors","1");
ini_set("memory_limit","64M");

ini_set('include_path', ini_get('include_path') . ':.');

require_once 'eBaySOAP.php';

// Code to open database connection - starts here
/*
require_once '../includes/configure.php';*/
$conn = mysqli_connect('localhost',' ',' ',' ');
//$db = mysqli_select_db(' ',$conn);

// Code to open database connection - ends here


$config = parse_ini_file('ebay.ini', true);
$site = $config['settings']['site'];
$compatibilityLevel = $config['settings']['compatibilityLevel'];

$dev = $config[$site]['devId'];
$app = $config[$site]['appId'];
$cert = $config[$site]['cert'];
$token = $config[$site]['authToken'];
$location = $config[$site]['gatewaySOAP'];

$session = new eBaySession($dev, $app, $cert);
$session->token = $token;
$session->site = 3; // 0 = US;
$session->location = $location;

try {
	$client = new eBaySOAP($session);
	
	$Item = array('eBayAuthToken' => $token);
	$params = array('Version' => $compatibilityLevel, 'RequesterCredentials' => $Item, 'CategorySiteID' => 3, 'DetailLevel' => 'ReturnAll');
	$results = $client->GetCategories($params);

	echo '<pre>'.print_r($results,true).'</pre>';
	for ($i=0;$i<count($results->CategoryArray->Category);$i++) {
		//Code to insert the categories - Starts here
		if($results->CategoryArray->Category[$i]->LeafCategory){
			
			$numCatId = $results->CategoryArray->Category[$i]->CategoryID;
			$strCatName = $results->CategoryArray->Category[$i]->CategoryName;		
			$sql = "insert into ebay_categories set ebay_cat_id = $numCatId, ebay_cat_title = '".addslashes($strCatName)."' ";
			mysqli_query($conn,$sql) or die(mysqli_error());
		
		}
		
		//Code to insert the categories - Ends here
	}


} catch (SOAPFault $f) {
	print $f; 
}
mysqli_close($conn);
?>