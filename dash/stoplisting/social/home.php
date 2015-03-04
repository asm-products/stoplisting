<?php

//Always place this code at the top of the Page
session_start();
if (!isset($_SESSION['login_user_id'])) {
    // Redirection to login page twitter or facebook
    header("location: index.php");
}
else{
	
	header("location: http://agapeworks.x10.mx/dash/stoplisting/dash");
	
}
echo '<h1>Welcome</h1>';
echo 'id : ' . $_SESSION['login_user_id'];
echo '<br/>Name : ' . $_SESSION['user_name'];
echo '<br/>Email : ' . $_SESSION['email'];
echo '<br/>You are login with : ' . $_SESSION['oauth_provider'];
echo '<br/>Logout from <a href="logout.php?logout">' . $_SESSION['oauth_provider'] . '</a>';
?>
