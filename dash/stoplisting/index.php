<?php
	/*
	===================================================================
	File Name: index.php
	===================================================================
	*/
	// Continue Session
	session_start();
	//Handle Errors
	error_reporting(E_ALL); 
	ini_set('log_errors','1'); 
	ini_set('display_errors','0'); 
	// G-zip Compression	
	ini_set('zlib.output_compression', 'On');  
	ini_set('zlib.output_compression_level', '5');
	// Timezone Set
	date_default_timezone_set('America/New_York');
	
	include('con/config.php');
	include('con/db_function.php');
	
	if(isset($_GET['page']))
	{
		$p	=	$_GET['page'];

		if($p	==	"admin")
		{
			echo "<script>window.location = '".SAURL."index.php';</script> ";
			}
		}	 
		
	if(!isset($_SESSION['login_user']))
	{
		echo "<script>window.location = '".SURL."inc/login.php';</script> ";
		}
		
	
	define('SURL', 'http://'.$_SERVER['HTTP_HOST'].'/dash/stoplisting/');
	
	
	
	
	
	$sql="select listings_remaining, bonus_listings from users where id=".$_SESSION['login_user_id'];
	$query=mysql_query($sql);
	$mfa=mysql_fetch_array($query);
	$sum=$mfa['listings_remaining']+$mfa['bonus_listings'];
	
	
?>
<!DOCTYPE HTML>
<html>
<head>
<title>StopListing | User Panel</title>
<meta name="keywords" content=""/>
<meta name="description" content=""/>
<meta name="robots" content="noindex,nofollow" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta charset="utf-8" />
<link href="http://fonts.googleapis.com/css?family=Ubuntu:bold" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Vollkorn" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/foundation.css" />
<link rel="stylesheet" href="font/icons.css" />
<link rel="stylesheet" href="css/style2.css?v=1.5"/>
<!-- Editor -->

<script type="text/javascript" src="<?=SURL?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?=SURL?>ckfinder/ckfinder.js"></script>

</head>
<body>
<div id="top_nav"> <span id="logo"> <i class="fi-list close"></i> <a href="<?=SURL?>dash"> <img src="img/logo-invert.png" alt="Stoplisting - Home"/> </a> </span>
  <ul>
    <li> <a href="<?=SURL?>plan"> <span id="listing_limit"><?php echo $sum?></span> Listings Remaining </a> </li>
    <li id="avatar_panel"> <img id="user_avatar" src="img/avatar/user_img.jpg" alt="user_avatar"/><?php echo $_SESSION['user_name'];?> <em>&#9660;</em> <span style="display:none;"> <a href="<?=SURL?>profile"><i class="fi-widget"></i> Settings</a> <a href="<?=SURL?>logout"><i class="fi-power"></i> Logout</a> </span> </li>
  </ul>
</div>
<div id="sidebar">
  <ul id="side_nav">
    <li class="side_link"><a <?php if(($p == "dash")|| (empty($p))){?> class="active" <?php }?> href="<?=SURL?>dash"><i class="fi-home"></i> Dashboard</a></li>
    <li class="side_link"><a <?php if(($p == "profile")|| (empty($p))){?> class="active" <?php }?> href="<?=SURL?>profile"><i class="fi-torsos"></i>Account</a></li>
    <li class="side_link"><a <?php if(($p == "listings")|| (empty($p))){?> class="active" <?php }?> href="<?=SURL?>listings"><i class="fi-pencil"></i>Manage</a></li>
    <li class="side_link"><a <?php if($p == "upload"){?> class="active" <?php }?> href="<?=SURL?>upload" ><i class="fi-upload"></i> Upload</a></li>
    <li style="display:none" class="side_link"><a <?php if(($p == "publish")||($p == "edit")||($p == "delete")||($p == "fix")||($p == "manage")){?>class="active" <?php }?> href="<?=SURL?>manage"><i class="fi-pencil"></i> Manage</a>
      <ul class="active_nav" <?php if(($p != "publish")&&($p != "edit")&&($p != "delete")&&($p != "fix")&&($p != "manage")){?>style="display:none;"<?php }?>>
        <li><a href="<?=SURL?>create">Create New Listings</a></li>
        <li><a href="<?=SURL?>fix">Resolve Issues</a></li>
      </ul>
    </li>
    <li class="side_link"><a <?php if($p == "promote"){?> class="active" <?php }?> href="<?=SURL?>promote"><i class="fi-megaphone"></i> Promotion</a></li>
    <li class="side_link"><a <?php if($p == "templates" || $p == "add-template" || $p == "edit-template"){?> class="active" <?php }?> href="<?=SURL?>templates"><i class="fi-layout"></i>Templates</a>
    	
        <ul class="active_nav" <?php if(($p != "publish")&&($p != "edit")&&($p != "delete")&&($p != "add-template")&&($p != "templates")){?>style="display:none;"<?php }?>>
            <li><a href="<?=SURL?>add-template">Add New Template</a></li>
            <!--<li><a href="<?=SURL?>fix">Resolve Issues</a></li>-->
      	</ul>
    
    </li>
    <?php /*?><li class="side_link"><a <?php if(($p == "account")||($p == "a_set")||($p == "l_set")||($p == "plan")||($p == "close")){?> class="active" <?php }?> href="<?=SURL?>account"><i class="fi-torsos"></i> Account</a>
      <ul class="active_nav" <?php if(($p != "account")&&($p != "a_set")&&($p != "l_set")&&($p != "plan")&&($p != "close")){?> style="display:none;"<?php }?>>
        <li><a href="<?=SURL?>a_set">Account Settings</a></li>
        <li><a href="<?=SURL?>l_set">Listing Settings</a></li>
        <li><a href="<?=SURL?>plan">Payment Plan</a></li>
        <li><a href="<?=SURL?>close">Close Account</a></li>
      </ul>
    </li><?php */?>
    <li class="side_link"><a <?php if($p == "upgrade"){?> class="active" <?php }?> href="<?=SURL?>upgrade"><i class="fi-trophy"></i> Upgrade</a></li>
    <li class="side_link"><a class="support" href="javascript:;"><i class="fi-comment"></i> Support</a></li>
  </ul>
</div>
<div id="main_wrapper">
  <?php
			// Page Changer
			$page_array = array("a_set","account","close","dash","delete","design","fix","l_set","login","logout","manage","plan","promote","support","upgrade","upload","profile","edit-listing","add-template","templates","listings");

/*			if(in_array($p, $page_array))
			{
				include("inc/$p.php");
				}
				elseif(empty($p))
				{
					include("inc/dash.php");
					}
					else 
					{
						include("inc/404.php");
						}*/
					
			if(file_exists('inc/'.$p.'.php'))
			{
				include('inc/'.$p.'.php');
				}
				else 
				{
					include("inc/404.php");
					}		

		?>
</div>
</body>
</html>
