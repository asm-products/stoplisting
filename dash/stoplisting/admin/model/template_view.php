<?php 
if(isset($_GET["pram"]))
{	
	$template=$db_fun->getTemplateDetail(intval(trim($_GET["pram"]))); 
}
?>