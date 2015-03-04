<?php 

	if(isset($_REQUEST["parm"]))
	{
		$table_name	=	"tbl_image";
		$id	=	$_REQUEST["parm"];
		
		//$delete	=	$db_fun->delete_function($table_name,$id); 
		$_SESSION['msg'] = "Image Deleted Successfully";
		}
	$sql	=	"select img.*,img.id as img_id,img.name as img_name,cat.name as cat_name from tbl_image as img left join tbl_image_cat as cat on img.cat_id=cat.id where img.op_status='5' order by img.id asc";

	$test = new MyPagina;
	// create query
	$test->sql = $sql;
	$result = $test->get_page_result(); // result set
	$num_rows = $test->get_page_num_rows(); // number of records in result set 
	$nav_links = $test->navigation(" | ", "currentStyle"); // the navigation links (define a CSS class selector for the current link)
	$nav_info = $test->page_info("to"); // information about the number of records on page ("to" is the text between the number)
	$simple_nav_links = $test->back_forward_link(false); // the navigation with only the back and forward links, use true to use images
	$total_recs = $test->get_total_rows(); // the total number of records	
	$showing = $test->page_info();
		
	while($res = @mysql_fetch_assoc($result))					   
	{
		$user[] = $res;					 
		}
?>