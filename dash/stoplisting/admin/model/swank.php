<?php 
if(isset($_REQUEST["action"]))
{
	
	
	$keyword	=	mysql_real_escape_string($_REQUEST["keyword"]);
	$limit	=	mysql_real_escape_string($_REQUEST["limit"]);
	
	
	if(trim($keyword)==''){
		
		$keyword='shirt';
	
	}
	
	if(trim($limit)==''){
		
		$limit=10;
	
	}
	
	$swank	=	$db_fun->getSwank($keyword,$limit);
	
	
	
		$msg = "$swank";

		echo "<script>window.location = '".SAURL."index.php?p=swank&pram=".$msg."&keyword=".$keyword."&limit=".$limit."';</script> ";
		exit;
	
	
}

?>