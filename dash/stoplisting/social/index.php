<?php
//Always place this code at the top of the Page
session_start();
if (isset($_SESSION['login_user_id'])) {
    // Redirection to login page twitter or facebook
    header("location: home.php");
}
$path_fb='http://agapeworks.x10.mx/dash/stoplisting/social/login-facebook.php';
$path_tw='http://agapeworks.x10.mx/dash/stoplisting/social/login-twitter.php';

if (array_key_exists("login", $_GET)) {
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'twitter') {
        header("Location: $path_tw");
    } else if ($oauth_provider == 'facebook') {
        header("Location: $path_fb");
    }
}
?>
<title>9lessons Facebook | Twitter Login</title>
<style type="text/css">
    #buttons
	{
	text-align:center
	}
    #buttons img,
    #buttons a img
    { border: none;}
	h1
	{
	font-family:Arial, Helvetica, sans-serif;
	color:#999999;
	}
	
</style>



<div id="buttons">
<h1>Twitter Facebook Login </h1>
    <a href="?login&oauth_provider=twitter"><img src="images/tw_login.png"></a>&nbsp;&nbsp;&nbsp;
    <a href="http://agapeworks.x10.mx/dash/stoplisting/social/?login&oauth_provider=facebook"><img src="images/fb_login.png"></a> <br />
	<br />
	<a href="http://www.9lessons.info">http://9lessons.info</a>   
</div>
