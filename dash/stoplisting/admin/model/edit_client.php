<?php 
$today_date=date('Y-m-d h:i:s');

if(isset($_REQUEST["parm"]))
{
	$parm	=	$_REQUEST["parm"];
	$tbl_name	=	"users";
	
	$fetch	=	$select->sngl_rec_sel($tbl_name,$parm);	
	$db_rec = mysql_fetch_assoc($fetch);
	}


if(isset($_REQUEST["action"]))
{	
	$cl_name	= mysql_real_escape_string($_REQUEST["name"]);
	$email	=	mysql_real_escape_string($_REQUEST["email"]);
	
	$plan	=	mysql_real_escape_string($_REQUEST["plan"]);
	$template	=	mysql_real_escape_string($_REQUEST["template"]);
	$price	=	mysql_real_escape_string($_REQUEST["price"]);
	$offercode	=	mysql_real_escape_string($_REQUEST["offercode"]);
	$order_no	=	mysql_real_escape_string($_REQUEST["order_no"]);
	$db_app_key	=	mysql_real_escape_string($_REQUEST["db_app_key"]);
	$db_secret_key	=	mysql_real_escape_string($_REQUEST["db_secret_key"]);
	$ebay_dev=mysql_real_escape_string($_REQUEST["ebay_dev"]);
	$ebay_app=mysql_real_escape_string($_REQUEST["ebay_app"]);
	$ebay_cert=mysql_real_escape_string($_REQUEST["ebay_cert"]);
	$ebay_token=mysql_real_escape_string($_REQUEST["ebay_token"]);
	
	$paypal_id=mysql_real_escape_string($_REQUEST["paypal_id"]);
	
	
	$gender	=	stripslashes($_REQUEST["gender"]);
	$username	=	stripslashes($_REQUEST["username"]);
	
	$password=stripslashes(trim($_REQUEST["password"]));
	$old_password=stripslashes(trim($_REQUEST["old_password"]));
	
	if($password!=$old_password){
		$password=md5($password);
	}
	$cl_status	=	stripslashes($_REQUEST["status"]);
	
	$edit_rec	=	$db_fun->edit_client($parm, $cl_name, $email, $plan, $template, $price, $offercode, $order_no, $db_app_key, $db_secret_key, $ebay_dev, $ebay_app, $ebay_cert, $ebay_token, $paypal_id, $password, $cl_status); 
	
	if($edit_rec == 1)
	{
		$_SESSION['msg'] = "Customer Updated Successfully";
		
		echo "<script>window.location = '".SAURL."index.php?p=customers';</script> ";
		exit;
		}
	
	}
	
$query_temp=$db_fun->getAllTemplates();
$query_plan=$db_fun->getPackages();
?>