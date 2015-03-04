<?php
session_start();
if(@($_SESSION['login_admin']) == 'admin')
{
	unset($_SESSION['login_admin']);
	unset($_SESSION['admin_name']);
	}
echo "<script>window.location = '".SAURL."index.php';</script> ";
exit;	 
?>