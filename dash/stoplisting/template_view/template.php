<?php 
include '../con/config.php'; 
include '../admin/classes/db_queries.php'; 
if(isset($_GET["temp_id"]) && isset($_GET["list_id"])){	
	$sql="select temp_detail from templates where temp_id='".intval(trim($_GET["temp_id"]))."'";
	$q=mysql_query($sql);
	$mfa=mysql_fetch_array($q);
	
	$sql="select * from user_images where ui_id='".intval(trim($_GET["list_id"]))."'";
	$q=mysql_query($sql);
	$mfaList=mysql_fetch_array($q);	
	
	$db_fun	=	new db_queries();
	$user_info=$db_fun->getUserInfo($mfaList["user_id"]);
}

$html=$mfa['temp_detail'];
$html=str_ireplace("{Title}", $mfaList['title'], $html);

$html=str_ireplace("{Detail}", $mfaList['detail'], $html);
if ($mfaList['handling_time'] == "0") {
	$handling_time = "Same Day Shipping";
} else if ($mfaList['handling_time'] == "1") {
	$handling_time = "1 Business Day";
} else {
	$handling_time = $mfaList['handling_time']." Business Days";
}

$html=str_ireplace("{Handling}", $handling_time, $html);
$html=str_ireplace("{Ship}", $user_info['ship_detail'], $html);
$html=str_ireplace("{Pricing}", $user_info['pricing_detail'], $html);
$html=str_ireplace("{Paypal Logo}", '<!-- PayPal Logo --><table align="center" border="0" cellpadding="10" cellspacing="0"><tbody><tr><td align="center"></td></tr><tr><td align="center"><a href="https://www.paypal.com/webapps/mpp/paypal-popup"><img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_SbyPP_mc_vs_dc_ae.jpg" alt="PayPal Acceptance Mark" border="0"></a></td></tr></tbody></table><!-- PayPal Logo -->', $html);

$item_stats_list = "<ul id=\"item_specifics\">";
	$item_stats 	= json_decode($mfaList['item_stats'], true);
        for($i=0;$i < count($item_stats['names']);$i++) {
	                //$item_stats_list .= "<li>".htmlentities($item_stats['names'][$i]).": ".htmlentities($item_stats['values'][$i])."</li>";
	                $item_stats_list .= "<li>".htmlentities(str_replace("&#39;", "'", $item_stats['names'][$i])).": ".htmlentities(str_replace("&#39;", "'", $item_stats['values'][$i]))."</li>";
	       
        }
	$item_stats_list .= "</ul>";
$html=str_ireplace("{Item Specs}", $item_stats_list, $html);
if (empty($user_info['ebay_user'])) {
	$store_name = "Welcome";
} else {$store_name = $user_info['ebay_user'];}

$html=str_ireplace("{Store Name}", $store_name, $html);
$photos = explode("-", $mfaList['ui_image']);
$image_urls = "<ul id=\"gallery\">";
for($i=0;$i<count($photos);$i++) {

	if ($i % 4 == 0) {
		$image_urls .='<li class="gallery_single break_photo"><img alt="'.$mfaList['title'].' | Photo #'.$i.'" src="http://agapeworks.x10.mx/dash/stoplisting/admin/image-resizer-master/getImage.php?app&amp;url='.$photos[$i].'"></li>';
	} else {
		$image_urls .='<li class="gallery_single"><img alt="'.$mfaList['title'].' | Photo #'.$i.'" src="http://agapeworks.x10.mx/dash/stoplisting/admin/image-resizer-master/getImage.php?app&amp;url='.$photos[$i].'"></li>';
	}
}
$image_urls .= "</ul>";
$html=str_ireplace("{Gallery}", $image_urls, $html);
$html=str_ireplace("{Pricing}", $mfaList['pricing_detail'], $html); 

if ( $user_info['returns_accepted'] == "ReturnsAccepted") {
	//$refunds_option_string 	= preg_split('/.(?=[A-Z])/',lcfirst(" ".$user_info['refunds_option']));
	//$refunds_option 	= implode(" ", $refund_options_string);
	$refunds_within_string 	= explode("_", $user_info['refunds_within']);
	$returns_detail = "<span style=\"font-weight:bold;text-align:center;\">Returns Are Accepted. ".$user_info['refunds_option']." will be given within ".$refunds_within_string[1]." ".$refunds_within_string[0]."</span>";
	
} else { //No Policy
	$returns_detail = "No return policy. If you have an issue with an item, please contact me.";
}
$html=str_ireplace("{Return Policy}", $returns_detail, $html);
$html=str_ireplace("{Ship Cost}", "$".$mfaList['shipping_cost'], $html); 
$html=str_ireplace("{Barcode}", $mfaList['ui_barcode'], $html); 
$html=str_ireplace("{Price}", "$".$mfaList['price'], $html); 
echo $html;
?>