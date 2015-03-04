<?php 

	if(isset($_REQUEST["parm"]))
	{
		$table	=	"plans";
		$value	=	$_REQUEST["parm"];
		$column="plan_id";
		
		$delete	=	$db_fun->trashSingle($table, $column, $value); 
		$_SESSION['msg'] = "Package Deleted Successfully";
		}
	$sql	=	"select * from plans order by plan_id asc";

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
		
	/*while($res = @mysql_fetch_assoc($result)){
		$user[] = $res;
	}*/
	//echo '<pre>'.print_r($result,true).'</pre>';exit;
?>