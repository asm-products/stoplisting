<?php ob_start(); error_reporting(E_ALL); ini_set('display_errors', 'On'); 
class sql_function
{
	function chk_admin($usr,$pass)
	{
		$row	=	mysql_query("select * from  admin  where username = '".mysql_real_escape_string($usr)."' and password = '".mysql_real_escape_string(md5($pass))."' and status != '0' ") or die(mysql_error());
		return $row ;
		}
	function sngl_rec_sel($tbl_name,$rec_id)
	{
		$row	=	mysql_query("select * from  ".$tbl_name."  where id = '".$rec_id."'") or die(mysql_error());
		return $row ;
		}	
		
	function all_rec_sel($tbl_name)
	{
		$row	=	mysql_query("select * from  ".$tbl_name) or die(mysql_error());
		return $row ;
		}		
	
	function getSingleRecord($table, $column, $param){
		
		$row	=	mysql_query("select * from  $table where $column = '".$param."' ") or die(mysql_error());
		return $row ;
		
	}
	}
?>	