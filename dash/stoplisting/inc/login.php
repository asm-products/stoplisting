<?php 
include('../con/config.php');
session_start();
if(isset($_SESSION['login_user']))
{
	echo "<script>window.location = '".SURL."index.php';</script> ";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="ThemeBucket">
<link rel="shortcut icon" href="images/favicon.png">
<title>Login</title>
<!--Core CSS -->
<link href="../bucket/bs3/css/bootstrap.min.css" rel="stylesheet">
<link href="../bucket/css/bootstrap-reset.css" rel="stylesheet">
<link href="../bucket/font-awesome/css/font-awesome.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../bucket/css/style.css" rel="stylesheet">
<link href="../bucket/css/style-responsive.css" rel="stylesheet" />
<!-- Just for debugging purposes. Don't actually copy this line! -->
<!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-body">
<div class="container">
  <form class="cmxform form-signin" id="signupForm" method="post" action="../con/db_function.php"><!-- -->
    <h2 class="form-signin-heading">sign in now</h2>
    <div class="login-wrap">
      <div class="user-login-info">
        <input type="text" class="form-control" id="emails" name="emails" placeholder="Email" autofocus>
        <input type="password" class="form-control" id="password" name="pass" placeholder="Password">
        
        <a href="<?php echo SURL.'social/index.php/'?>?login&oauth_provider=twitter"><img src="../social/images/tw_login.png"></a>&nbsp;&nbsp;&nbsp;
    	<a href="<?php echo SURL.'social/index.php/'?>?login&oauth_provider=facebook"><img src="../social/images/fb_login.png"></a> <br />
        
        <input type="hidden" name="action" value="loginusr" />
      </div>
      <!--<label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>

                </span>
            </label>-->
      <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
      <div class="registration"> Don't have an account yet? <a class="" href="<?=SURL?>inc/signup.php"> Create an Account </a> </div>
    </div>
    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Forgot Password ?</h4>
          </div>
          <div class="modal-body">
            <p>Enter your e-mail address below to reset your password.</p>
            <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
          </div>
          <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
            <button class="btn btn-success" type="button">Submit</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal -->
  </form>
</div>
<!-- Placed js at the end of the document so the pages load faster -->
<!--Core js-->
<script src="../bucket/jquery.js"></script>
<script src="../bucket/bs3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../bucket/jquery.validate.min.js"></script>
<!--common script init for all pages-->
<script src="../bucket/scripts.js"></script>
<!--this page script-->
<script src="../bucket/validation-init.js"></script>
</body>
</html>
