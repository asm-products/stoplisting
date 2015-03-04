<?php 
/*$con=mysql_connect('localhost', 'root', '');
mysql_select_db(' ',$con);*/
function geteBayCategories($url){
$xml=simplexml_load_file($url);

//echo '<pre>'.print_r($xml,true).'</pre>'; 
$appid ="";
$a=json_encode($xml,true);
$a=json_decode($a,true);
echo '<pre>'.print_r($a,true).'</pre>';
$options='';
foreach($a['CategoryArray']['Category'] as $value){
	
	if(trim($value['CategoryID']!=-1)){
		/*$sql="insert into ebay_categories(ebay_cat_id, ebay_cat_title, ebay_cat_siteid) values('".$value['CategoryID']."', '".trim($value['CategoryName'])."', '0')";
		mysql_query($sql);*/
		$options.="<option value='".$value['CategoryID']."'>".stripslashes($value['CategoryName'])."</option>";
		//if($value['CategoryID']==20081){
			//$options.=kaleem($value['CategoryID']);
		//}
	}
}

return array('Total Categories: '.count($a['CategoryArray']['Category']), $options);

}



function kaleem($id){
	
	$url="http://open.api.ebay.com/Shopping?callname=GetCategoryInfo&appid=$appid&siteid=0&CategoryID=$id&version=729&IncludeSelector=ChildCategories";

	
	$xml=simplexml_load_file($url);
	$a=json_encode($xml,true);
	$a=json_decode($a,true);
	//echo '<pre>'.print_r($a,true).'</pre>';
	$options='';
	foreach($a['CategoryArray']['Category'] as $value){
		
		if(trim($value['CategoryParentID']!=-1)){
			/*$sql="insert into ebay_categories(ebay_cat_id, ebay_cat_title, ebay_cat_siteid) values('".$value['CategoryID']."', '".trim($value['CategoryName'])."', '0')";
			mysql_query($sql);*/
			$options.="<option value='".$value['CategoryID']."'>".'--'.stripslashes($value['CategoryName'])."</option>";
			//if($value['CategoryID']==37903){
			if(trim($value['LeafCategory'])=='false'){
				$options.=sami($value['CategoryID']);
			}
			//}
		}
	}

	return $options;

	
	
}


function sami($id){
	
	$url="http://open.api.ebay.com/Shopping?callname=GetCategoryInfo&appid=$appid&siteid=0&CategoryID=$id&version=729&IncludeSelector=ChildCategories";

	
	$xml=simplexml_load_file($url);
	$a=json_encode($xml,true);
	$a=json_decode($a,true);
	//echo '<pre>'.print_r($a,true).'</pre>';
	$options='';
	foreach($a['CategoryArray']['Category'] as $value){
		
		if(trim($value['CategoryID']!=$id)){
			/*$sql="insert into ebay_categories(ebay_cat_id, ebay_cat_title, ebay_cat_siteid) values('".$value['CategoryID']."', '".trim($value['CategoryName'])."', '0')";
			mysql_query($sql);*/
			$options.="<option value='".$value['CategoryID']."'>".'----'.stripslashes($value['CategoryName'])."</option>";
			
		}
	}

	return $options;

	
	
}




?>