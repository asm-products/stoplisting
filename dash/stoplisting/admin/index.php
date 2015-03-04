<?php
	/*
	===================================================================
	File Name: index.php
	===================================================================
	*/
	// Continue Session
	session_start();
	//Handle Errors
	//error_reporting(E_ALL); 
	//error_reporting(0);
	//ini_set('log_errors','1'); 
	//ini_set('display_errors','0'); 
	// G-zip Compression	
	ini_set('zlib.output_compression', 'On');  
	ini_set('zlib.output_compression_level', '5');
	// Timezone Set
	date_default_timezone_set('America/New_York');

	include('../con/config.php');
	
	include('classes/sql_queries.php');
	include('classes/db_queries.php');
	
	$select	=	new sql_function();	
	$db_fun	=	new db_queries();
	
	include('inc/pagination.php');

?>
<!DOCTYPE HTML>
<html>
<head>
<title>StopListing | Admin Panel</title>
<!--Core CSS -->
<link href="<?=SURL?>bucket/bs3/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=SURL?>bucket/css/bootstrap-reset.css" rel="stylesheet">
<link href="<?=SURL?>bucket/font-awesome/css/font-awesome.css" rel="stylesheet" />
<!--dynamic table-->
<link href="<?=SURL?>bucket/js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
<link href="<?=SURL?>bucket/js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
<link rel="stylesheet" href="<?=SURL?>bucket/js/data-tables/DT_bootstrap.css" />
<!-- Custom styles for this template -->
<link href="<?=SURL?>bucket/css/style.css" rel="stylesheet">
<link href="<?=SURL?>bucket/css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=SURL?>bucket/js/select2/select2.css" />
<!--icheck-->
<link href="<?=SURL?>bucket/js/iCheck/skins/minimal/minimal.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/minimal/red.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/minimal/green.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/minimal/blue.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/minimal/yellow.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/minimal/purple.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/square/square.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/square/red.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/square/green.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/square/blue.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/square/yellow.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/square/purple.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/flat/grey.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/flat/red.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/flat/green.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/flat/blue.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/flat/yellow.css" rel="stylesheet">
<link href="<?=SURL?>bucket/js/iCheck/skins/flat/purple.css" rel="stylesheet">

<link href="<?=SURL?>bucket/css/sidebar.css" rel="stylesheet">
<link rel="shortcut icon" href="<?=SURL?>bucket/images/favicon.ico" >
<!-- Editor -->

<script type="text/javascript" src="<?=SAURL?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?=SAURL?>ckfinder/ckfinder.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="../../stoplisting/bucket/js/charCount/charCount.js"></script>
<script type="text/javascript">
	$(document).ready(function(){	
		//default usage - http://stackoverflow.com/questions/15594733/using-keyup-to-count-characters-in-jquery
		var input = $('input#title'),
		count = $('span#title_count').text(input.val().length);
		if ( parseInt(input.val().length) > 80 ) {
				$("span#title_count").addClass("exceeds");
				count.text(input.val().length + " - Character Limit Reached. Shorten Your Title to 80 Characters or less!");
     				$('.button-submit').hide();
			} else {$("span#title_count").removeClass("exceeds"); 
				count.text(input.val().length);
		           	$('.button-submit').show();
		}
		input.bind('keydown', function() {
		setTimeout(function() {
			if ( parseInt(input.val().length) > 80 ) {
				$("span#title_count").addClass("exceeds");
				count.text(input.val().length + " - Character Limit Reached. Shorten Your Title to 80 Characters or less!");
     				$('.button-submit').hide();
			} else {$("span#title_count").removeClass("exceeds"); 
				count.text(input.val().length);
		           	$('.button-submit').show();
			}
		},4);

		});
		
	});
</script>
</head>
<?php 
if(isset($_SESSION['login_admin']))
{
	if(isset($_REQUEST['p']))
	{
		list($p, $type) = explode("-type-", $_REQUEST['p']."-type-");
		//$p 		= $page_title['0']; 
		//$type 		= $page_title['1'];
		$undone		= mysql_fetch_array(mysql_query("select count(a.ui_status) as undone from (select * from `user_images` where ui_status='0') as a"));
		$reject		= mysql_fetch_array(mysql_query("select count(b.ui_status) as reject from (select * from `user_images` where ui_status='3' OR ui_status='5') as b"));
		$complete_today	= mysql_fetch_array(mysql_query("select count(c.ui_status) as done_today from (select * from `user_images` where ui_status='1' AND DATE(`modified`) = DATE(CURDATE())) as c"));
		$complete_week 	= mysql_fetch_array(mysql_query("select count(d.ui_status) as done_week from (select * from `user_images` where ui_status='1' AND WEEKOFYEAR(`modified`) = WEEKOFYEAR(NOW())) as d"));
		$notenough	= mysql_fetch_array(mysql_query("select count(e.ui_status) as notenough from (select * from `user_images` where ui_status='6') as e"));
		}
		else
		{
			$p = "images_listings"; 
			$type=0;
			}		
	?>
<body>
<section id="container">
  <!--header start-->
  <?php include('inc/header.php');?>
  <!--header end-->
  <?php include('inc/sidebar.php');?>
  <!--sidebar end-->
  <?php 
 			if(file_exists('model/'.$p.'.php'))
			{
				include('model/'.$p.'.php');
				}
				?>
  <!--main content start-->
  <section id="main-content">
    <section class="wrapper">
      <!-- page start-->
      <?php	
			if(file_exists('views/'.$p.'.php'))
			{
				include('views/'.$p.'.php');
			}
			else 
			{
				include("views/404.php");
			}		

		?>
      <!-- page end-->
    </section>
  </section>
  <!--main content end-->
</section>
<!-- Placed js at the end of the document so the pages load faster -->
<!--Core js-->
<script src="<?=SURL?>bucket/jquery.js"></script>
<script src="<?=SURL?>bucket/bs3/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?=SURL?>bucket/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?=SURL?>bucket/jquery.scrollTo.min.js"></script>
<script src="<?=SURL?>bucket/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="<?=SURL?>bucket/jquery.nicescroll.js"></script>
<!--dynamic table-->
<script type="text/javascript" language="javascript" src="<?=SURL?>bucket/js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?=SURL?>bucket/js/data-tables/DT_bootstrap.js"></script>

<script type="text/javascript" src="<?=SURL?>bucket/jquery.validate.min.js"></script>
<!--common script init for all pages-->
<script src="<?=SURL?>bucket/scripts.js"></script>
<!--dynamic table initialization -->
<script src="<?=SURL?>bucket/dynamic_table_init.js"></script>
<script src="<?=SURL?>bucket/validation-init.js"></script>
<script src="<?=SURL?>bucket/js/iCheck/jquery.icheck.js"></script>
<!--icheck init -->
<script src="<?=SURL?>bucket/icheck-init.js"></script>

<script src="<?=SURL?>bucket/js/select2/select2.js"></script>
<script src="<?=SURL?>bucket/select-init.js"></script>

<script src="<?=SURL?>bucket/advanced-form.js"></script>


<script type="text/javascript">
var editor = CKEDITOR.replace( 'editor1', {
    filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?type=Images',
    filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?type=Flash',
    filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
    filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
CKFinder.setupCKEditor( editor, '../' );

</script>

</body>
<?php 
	}
	else
	{
		?>
<body class="login-body">
<div class="container">
  <?php
			if(isset($_REQUEST['p']))
			{
				$p = $_REQUEST['p'];
				if($p	=	" ")
				{
					$p = "login"; 
					} 
				}
				else
				{
					$p = "login"; 
					}

			
			if(file_exists('model/'.$p.'.php'))
			{
				include('model/'.$p.'.php');
				}
				
			if(file_exists('views/'.$p.'.php'))
			{
				include('views/'.$p.'.php');
				}
				else 
				{
					include("views/404.php");
					}		

		?>
</div>
<!-- Placed js at the end of the document so the pages load faster -->
<!--Core js-->
<script src="<?=SURL?>bucket/jquery.js"></script>
<script src="<?=SURL?>bucket/bs3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=SURL?>bucket/jquery.validate.min.js"></script>
<!--common script init for all pages-->
<script src="<?=SURL?>bucket/scripts.js"></script>
<!--this page script-->
<script src="<?=SURL?>bucket/validation-init.js"></script>
</body>
<?php }?>
</html>
