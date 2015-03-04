<?php 
$today_date=date('Y-m-d h:i:s');

if(isset($_REQUEST["parm"]))
{
	$param	=	$_REQUEST["parm"];
	$table	=	"plans";
	
	$fetch	=	$select->getSingleRecord($table, 'plan_id', $param);
	$mfa = mysql_fetch_assoc($fetch);
	
}


if(isset($_REQUEST["action"]))
{	
	$plan	=	mysql_real_escape_string($_REQUEST["plan"]);
	$price	=	mysql_real_escape_string($_REQUEST["price"]);
	$limit	=	mysql_real_escape_string($_REQUEST["limit"]);
	$plan_url_code	=	mysql_real_escape_string($_REQUEST["plan_url_code"]);
	$gum_id	=	mysql_real_escape_string($_REQUEST["gum_id"]);
	
	$edit_rec	=	$db_fun->updatePackage($plan,$price,$limit,$plan_url_code,$gum_id,$param);
	
	if($edit_rec == 1)
	{
		$_SESSION['msg'] = "Package Updated Successfully";
		
		echo "<script>window.location = '".SAURL."index.php?p=packages';</script> ";
		exit;
		}
	
	}
?>