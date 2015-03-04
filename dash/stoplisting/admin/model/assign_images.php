<?php 
$today_date=date('Y-m-d h:i:s');

if(isset($_REQUEST["action"]))
{	
	
/*	$cl_name	=	$_REQUEST["name"];
	$email	=	$_REQUEST["email"];
	$city	=	$_REQUEST["city"];
	$gender	=	$_REQUEST["gender"];
	$username	=	$_REQUEST["username"];
	$password	=	$_REQUEST["password"];
	$cl_status	=	$_REQUEST["status"];
	
	$new_add	=	$db_fun->add_new_client($cl_name,$email,$city,$gender,$username,$password,$today_date,$cl_status); 
	
	if($new_add > 0)
	{		
		$_SESSION['msg'] = "Customer Added Successfully";

		echo "<script>window.location = '".SAURL."index.php?p=customers';</script> ";
		exit;
		}
	*/
	}
	
	$category_data	=	mysql_query("select id,name from tbl_image_cat") or die(mysql_error());
	
//	$image_data	=	"select id,name from tbl_image_cat";
	
	
?>