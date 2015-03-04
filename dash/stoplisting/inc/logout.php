<?php
session_start();
if(@$_SESSION['login_user'] == 'user')
{
	unset($_SESSION['login_user']);
	unset($_SESSION['login_user_id']);
	unset($_SESSION['user_name']);
	unset($_SESSION['user_date_join']);
	
	if(isset($_SESSION['oauth_id'])){
		
		unset($_SESSION['oauth_id']);
	
	
	}
	if(isset($_SESSION['oauth_provider'])){
		
		unset($_SESSION['oauth_provider']);
	
	
	}
	
	}
echo "<script>window.location = '".SURL."inc/login.php';</script> ";
exit;	 
?>