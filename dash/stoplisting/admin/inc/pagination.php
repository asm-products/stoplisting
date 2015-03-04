<?php

	 define("QS_VAR", "records"); // the variable name inside the query string (don't use this name inside other links)
	 define("STR_FWD", "Next"); // the string is used for a link (step forward)
	 define("STR_BWD", "Previous"); // the string is used for a link (step backward)
 	 // use the rught pathes to get it working with the php function getimagesize
	 define("IMG_FWD", "../images/forward.gif"); // the image for forward link 
	 define("IMG_BWD", "../images/backward.gif"); // the image for backward link 
	 define("NUM_LINKS", 30); // the number of links inside the navigation (the default value)

//error_reporting(E_ALL); // only for testing

class MyPagina {
	
	var $sql;
	var $result;
	var $outstanding_rows = false;
	
	var $get_var;
	var $rows_on_page;

	var $max_rows;
	
	// constructor
	function MyPagina($rows = 0) {
		//$this->connect_db();
		$this->max_rows = $rows;
		$this->get_var = QS_VAR;
		$this->rows_on_page = 30;

	}
	// sets the current page number
	function set_page() {
		$page = (isset($_REQUEST[$this->get_var]) && $_REQUEST[$this->get_var] != "") ? $_REQUEST[$this->get_var] : 0;
		return $page;
	}
	// gets the total number of records 
	function get_total_rows() {
		$tmp_result = mysql_query($this->sql);
		$all_rows = @mysql_num_rows($tmp_result);
		if (!empty($this->max_rows) && $all_rows > $this->max_rows) {
			$all_rows = $this->max_rows;
			$this->outstanding_rows = true;
		}
		@mysql_free_result($tmp_result);
		return $all_rows;
	}
	// database connection
	function connect_db() {
	
	$conn_str =mysql_pconnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
		mysql_select_db(DB_NAME, $conn_str) or die(mysql_error());
	}
	
	// get the totale number of result pages
	function get_num_pages() {
		$number_pages = ceil($this->get_total_rows() / $this->rows_on_page);
		return $number_pages;
	}
	
	// returns the records for the current page
	function get_page_result() {
		$start = $this->set_page() * $this->rows_on_page;
		$page_sql = sprintf("%s LIMIT %s, %s", $this->sql, $start, $this->rows_on_page);
		$this->result = @mysql_query($page_sql);
		return $this->result;
	}
	// get the number of rows on the current page
	function get_page_num_rows() {
		$num_rows = @mysql_num_rows($this->result);
		return $num_rows;
	}
	// free the database result
	function free_page_result() {
		mysql_free_result($this->result);
	}
	// function to handle other querystring than the page variable
	function rebuild_qs($curr_var) {
		if (!empty($_SERVER['QUERY_STRING'])) {
			$parts = explode("&", $_SERVER['QUERY_STRING']);
			$newParts = array();
			foreach ($parts as $val) {
				if (stristr($val, $curr_var) == false)  {
					array_push($newParts, $val);
				}
			}
			if (count($newParts) != 0) {
				$qs = "&".implode("&", $newParts);
			} else {
				return false;
			}
			return $qs; // this is your new created query string
		} else {
			return false;
		}
	} 
	// this method will return the navigation links for the conplete recordset
	function navigation($separator = " | ", $css_current = "", $back_forward = false, $use_images = false) {
		$max_links = NUM_LINKS;
		$curr_pages = $this->set_page(); 
		$all_pages = $this->get_num_pages() - 1;
		$var = $this->get_var;
		$navi_string = "";
		if (!$back_forward) {
			$max_links = ($max_links < 2) ? 2 : $max_links;
		}
		if ($curr_pages <= $all_pages && $curr_pages >= 0) {
			if ($curr_pages > ceil($max_links/2)) {
				$start = ($curr_pages - ceil($max_links/2) > 0) ? $curr_pages - ceil($max_links/2) : 1;
				$end = $curr_pages + ceil($max_links/2);
				if ($end >= $all_pages) {
					$end = $all_pages + 1;
					$start = ($all_pages - ($max_links - 1) > 0) ? $all_pages  - ($max_links - 1) : 1;
				}
			} else {
				$start = 0;
				$end = ($all_pages >= $max_links) ? $max_links : $all_pages + 1;
			}
			if($all_pages >= 1) {
				$forward = $curr_pages + 1;
				$backward = $curr_pages - 1;
				// the text two labels are new sinds ver 1.02
				$lbl_forward = $this->build_back_or_forward("forward", $use_images);
				$lbl_backward = $this->build_back_or_forward("backward", $use_images);
				$navi_string = ($curr_pages > 0) ? "<a href=\"".$_SERVER['PHP_SELF']."?".$var."=".$backward.$this->rebuild_qs($var)."\">".$lbl_backward."</a>&nbsp;" : $lbl_backward."&nbsp;";
				if (!$back_forward) {
					for($a = $start + 1; $a <= $end; $a++){
						$theNext = $a - 1; // because a array start with 0
						if ($theNext != $curr_pages) 
						{
							
								$navi_string .= "<a href=\"".$_SERVER['PHP_SELF']."?".$var."=".$theNext.$this->rebuild_qs($var)."\">";
							
							
							$navi_string .= $a."</a>";
							$navi_string .= ($theNext < ($end - 1)) ? $separator : "";
						} 
						else 
						{
							$navi_string .= ($css_current != "") ? "<span class=\"".$css_current."\">".$a."</span>" : $a;
							$navi_string .= ($theNext < ($end - 1)) ? $separator : "";
						}
					}
				}
				
					$navi_string .= ($curr_pages < $all_pages) ? "&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?".$var."=".$forward.$this->rebuild_qs($var)."\">".$lbl_forward."</a>" : "&nbsp;".$lbl_forward;
					
			}
		}
		return $navi_string;
	}
	// function to create the back/forward elements; $what = forward or backward
	// type = text or img
	function build_back_or_forward($what, $img = false) {
		$label['text']['forward'] = STR_FWD;
		$label['text']['backward'] = STR_BWD;
		$label['img']['forward'] = IMG_FWD;
		$label['img']['backward'] = IMG_BWD;
		if ($img) {
			$img_info = getimagesize($label['img'][$what]);
			$label = "<img src=\"".$label['img'][$what]."\" ".$img_info[3]." border=\"0\">";
		} else {
			$label = $label['text'][$what];
		}
		return $label;
	}
	// this info will tell the visitor which number of records are shown on the current page
	function page_info($str = "Result: %d - %d of %d") {
		$first_rec_no = ($this->set_page() * $this->rows_on_page) + 1;
		$last_rec_no = $first_rec_no + $this->rows_on_page - 1;
		$last_rec_no = ($last_rec_no > $this->get_total_rows()) ? $this->get_total_rows() : $last_rec_no;
		$info = sprintf($str, $first_rec_no, $last_rec_no, $this->get_total_rows());
		return $info;
	}
	// simple method to show only the page back and forward link.
	function back_forward_link($images = false) {
		$simple_links = $this->navigation(" ", "", true, $images);
		return $simple_links;
	}
}
?>