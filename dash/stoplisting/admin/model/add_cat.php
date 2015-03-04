<?php 
$today_date=date('Y-m-d h:i:s');

if(isset($_REQUEST["action"]))
{	
	
	$cat_name	=	$_REQUEST["name"];
	$cat_desc	=	$_REQUEST["cat_desc"];
	$cat_status	=	$_REQUEST["status"];
	
	$new_add	=	$db_fun->add_new_cat($cat_name,$cat_desc,$today_date,$cat_status); 
	if($new_add > 0)
	{
		//$msg	=	"add";
		
		$_SESSION['msg'] = "Category Added Successfully";
		//$_SESSION['msg_type'] = "Alert-Success";
		//&msg=".$msg."
		echo "<script>window.location = '".SAURL."index.php?p=categories';</script> ";
		exit;
		}
	
	}
?>