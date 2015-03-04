<?php

if (array_key_exists("logout", $_GET)) {
    session_start();
    unset($_SESSION['login_user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['oauth_provider']);
    session_destroy();
    header("location: home.php");
}
?>
