<?php 
$today_date=date('Y-m-d h:i:s');

if(@($_REQUEST["action"]))
{
	$action	=	$_REQUEST["action"];
	
	if($action	==	"loginadmin")
	{		
		$usr	=	$_POST['usrname'];
		$pass	=	$_POST['pass'];
		
		$row	=	$select->chk_admin($usr,$pass);
		
		$data = mysql_fetch_object($row);
		
		if(mysql_num_rows($row) > 0)
		{
			$_SESSION['login_admin']	=	"admin";
			$_SESSION['admin_name']	=	$data->name;
			echo "<script>window.location = '".SAURL."dash';</script> ";
			exit;
			}
			else
			{
				echo "<script>window.location = '".SAURL."';</script> ";
				}			
		}
	}	
?>
