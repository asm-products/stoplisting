<?php 
if(isset($_REQUEST["action"]))
{
	//echo '<pre>'.print_r($_POST,true).'</pre>';exit;
	$plan	=	mysql_real_escape_string($_REQUEST["plan"]);
	$price	=	mysql_real_escape_string($_REQUEST["price"]);
	$limit	=	mysql_real_escape_string($_REQUEST["limit"]);
	$plan_url_code	=	mysql_real_escape_string($_REQUEST["plan_url_code"]);
	$gum_id	=	mysql_real_escape_string($_REQUEST["gum_id"]);
	
	
	
	$new_add	=	$db_fun->addPackage($plan,$price,$limit,$plan_url_code,$gum_id); 
	
	if($new_add > 0){		
		$_SESSION['msg'] = "Package Added Successfully";

		echo "<script>window.location = '".SAURL."index.php?p=packages';</script> ";
		exit;
	}
	
}
?>