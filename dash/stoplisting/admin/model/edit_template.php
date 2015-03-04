<?php 
$today_date=date('Y-m-d h:i:s');

if(isset($_REQUEST["parm"]))
{
	$param	=	$_REQUEST["parm"];
	$table	=	"templates";
	
	$fetch	=	$select->getSingleRecord($table, 'temp_id', $param);
	$mfa = mysql_fetch_assoc($fetch);
	
}


if(isset($_REQUEST["action"]))
{	
	$title	=	mysql_real_escape_string($_REQUEST["title"]);
	$detail	=	mysql_real_escape_string($_REQUEST["detail"]);
	
	$edit_rec	=	$db_fun->updateTemplate($title,$detail,$param);
	
	if($edit_rec == 1)
	{
		$_SESSION['msg'] = "Template Updated Successfully";
		
		echo "<script>window.location = '".SAURL."index.php?p=templates';</script> ";
		exit;
		}
	
	}
?>