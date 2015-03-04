<?php 
$today_date=date('Y-m-d h:i:s');

if(@($_REQUEST["action"]))
{
	$action	=	$_REQUEST["action"];
	
	if($action	==	"adminsignup")
	{
		$name	=	$_REQUEST["firstname"];
		$email	=	$_REQUEST["email"];
		$site_url	=	$_REQUEST["site_url"];
		$username	=	$_REQUEST["username"];
		$pass	=	$_REQUEST["password"];
		$password	=	md5($pass);
		
		$new_add	=	$db_fun->new_admin($name,$email,$site_url,$username,$password); 	
		
		if($new_add > 0)
		{			
			session_start();
			$_SESSION['login_admin']	=	"admin";
			$_SESSION['admin_name']	=	$name;
			echo "<script>window.location = '".SAURL."index.php?p=dash';</script> ";
			exit;
			}
			else
			{
				echo "<script>window.location = '".SAURL."index.php?p=signup';</script> ";
				}			
		
		}
	}	
?>