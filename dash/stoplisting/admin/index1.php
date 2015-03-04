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
	
	/*echo "here";
	exit;*/
	include('../con/config.php');
	include('../con/db_function.php');
	
	
	if(!isset($_SESSION['login_admin']))
	{
		echo "<script>window.location = '".SAURL."pages/login.php';</script> ";
		}
		
	
	define('SAURL', 'http://'.$_SERVER['HTTP_HOST'].'/stop_listing/admin/');
	

	
	$p = $_REQUEST['p']; 
?>
<!DOCTYPE HTML>
<html>
<head>
<title>StopListing | Admin Panel</title>
<meta name="keywords" content=""/>
<meta name="description" content=""/>
<meta name="robots" content="noindex,nofollow" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta charset="utf-8" />
<link href="http://fonts.googleapis.com/css?family=Ubuntu:bold" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Vollkorn" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=SURL?>css/foundation.css" />
<link rel="stylesheet" href="<?=SURL?>font/icons.css" />
<link rel="stylesheet" href="<?=SURL?>css/style2.css?v=1.5"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
<div id="top_nav"> <span id="logo"> <i class="fi-list close"></i> <a href="<?=SAURL?>index.php?p=dash"> <img src="<?=SURL?>img/logo-invert.png" alt="Stoplisting - Home"/> </a> </span>
  <ul>
    <?php /*?><li> <a href="<?=SAURL?>index.php?p=plan"> <span id="listing_limit">50</span> Listings Remaining </a> </li><?php */?>
    <li id="avatar_panel"> <img id="user_avatar" src="<?=SURL?>img/avatar/user_img.jpg" alt="user_avatar"/><?php echo $_SESSION['admin_name'];?> <em>&#9660;</em> <span style="display:none;"> <a href="<?=SAURL?>index.php?p=a_set"><i class="fi-widget"></i> Settings</a> <a href="<?=SAURL?>index.php?p=logout"><i class="fi-power"></i> Logout</a> </span> </li>
  </ul>
</div>
<?php include('inc/sidebar.php');?>

<div id="main_wrapper">
  <?php
			// Page Changer
			$page_array = array("a_set","account","close","dash","delete","design","fix","l_set","login","logout","manage","plan","promote","support","upgrade","upload");

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
							/*echo "<pre>", print_r($_REQUEST), "</pre>";

exit;*/
			if(file_exists('pages/'.$p.'.php'))
			{
				include('pages/'.$p.'.php');
				}
				else 
				{
					include("pages/404.php");
					}		

		?>
</div>
</body>
</html>
