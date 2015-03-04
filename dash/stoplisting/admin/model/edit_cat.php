<?php 
$today_date=date('Y-m-d h:i:s');

if(isset($_REQUEST["parm"]))
{
	$parm	=	$_REQUEST["parm"];
	$tbl_name	=	"tbl_image_cat";
	
	$fetch	=	$select->sngl_rec_sel($tbl_name,$parm);	
	$db_rec = mysql_fetch_assoc($fetch);
	}


if(isset($_REQUEST["action"]))
{	
	$cat_name	=	$_REQUEST["name"];
	$cat_desc	=	$_REQUEST["cat_desc"];
	$cat_status	=	$_REQUEST["status"];
	
	$edit_rec	=	$db_fun->edit_cat($parm,$cat_name,$cat_desc,$cat_status); 
	
	if($edit_rec == 1)
	{
		$_SESSION['msg'] = "Category Updated Successfully";
		
		echo "<script>window.location = '".SAURL."index.php?p=categories';</script> ";
		exit;
		}
	
	}
?>