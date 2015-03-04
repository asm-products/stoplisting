<?php 
if(isset($_REQUEST["action"]))
{
	//echo '<pre>'.print_r($_POST,true).'</pre>';exit;
	$title	=	mysql_real_escape_string($_REQUEST["title"]);
	$detail	=	mysql_real_escape_string($_REQUEST["detail"]);
	
	
	
	
	$new_add	=	$db_fun->addTemplate(0,$title,$detail); 
	
	if($new_add > 0){		
		$_SESSION['msg'] = "Template Added Successfully";

		echo "<script>window.location = '".SAURL."index.php?p=templates';</script> ";
		exit;
	}
	
}
?>