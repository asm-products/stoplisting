<?php 
include('admin/classes/db_queries.php');

if(isset($_GET['pram'])){
	
	$sql="select ui_image from user_images where user_id='".$_SESSION['login_user_id']."' and ui_id='".intval(trim($_GET['pram']))."'";
	$qry=mysql_query($sql);
	$mfa_del=mysql_fetch_array($qry);
	
	$sql="delete from user_images where user_id='".$_SESSION['login_user_id']."' and ui_id='".intval(trim($_GET['pram']))."'";
	mysql_query($sql);
	
	if($mfa_del['ui_image']!='' && file_exists(SURL.'upload_dropbox/'.$mfa_del['ui_image'])){
		
		unlink(SURL.'upload_dropbox/'.$mfa_del['ui_image']);
		
	}
	
	$url=SURL.'index.php?page=listings&msg=Listing remove successfully';
	echo "<script>window.location = '".$url."';</script> ";

}

$db_fun=new db_queries();
$week_total=$db_fun->thisWeekListing($_SESSION['login_user_id']);
$need_more_info=$db_fun->needMoreInfo($_SESSION['login_user_id']);
$ready_for_publish=$db_fun->readyForPublish($_SESSION['login_user_id']);




$sql="select * from users where id='".$_SESSION['login_user_id']."'";
$query=mysql_query($sql);
$num_rows=mysql_num_rows($query);
if($num_rows==1){

	$mfa=mysql_fetch_array($query);
	
}

$msg='';
if(isset($_GET['msg'])){
	$msg='Listing Successfully Updates';
}

/*
1_app_54453a08f258c1413822984.jpg-1_app_54453a09190181413822985.jpg-1_app_54453a092c8671413822985.jpg
*/
?>



<div class="updates small-12 columns"> <a href=""><span><em><?php echo $ready_for_publish?></em> Ready for Publishing</span></a> <a href=""><span><em><?php echo $need_more_info?></em> Need More Information</span></a> <a href=""><span><em><?php echo  $week_total?></em> Added This Week</span></a> <a href=""><span><em>?</em> Get Support</span></a> </div>
<style>
#listing_view .listing_entry td:nth-child(9), #listing_view .listing_entry td:nth-child(4), #listing_view .listing_entry td:nth-child(4){
	
	font-size:0.875rem;
	font-weight:normal;
}
</style>




<div class="small-12 columns">
  <div class="listings">
    <!--<div class="small-4 columns"> <span class="sort_list"> <i class="fi-list"></i> <i class="fi-thumbnails"></i> </span> </div>-->
    <div class="small-4 columns">
      <h4>View Listing</h4>
      <?php 
	  if($msg!=''){
      	echo "<h6 style='color:green'>$msg</h6>";
      }
	  ?>
    </div>
    <!--<div class="small-4 columns"> <span class="export_list"> Export List: <a href="">XML</a> <a href="">CSV</a> <a href="">PDF</a> </span> </div>-->
  </div>
  <table id="listing_view" class="listing_list listing_grid">
    <tr>
        <td><i class="fi-checkbox"></i></td>
        <td><i class="fi-photo"> Photo</i></td>
        <td><i class="fi-clipboard-pencil"> Title</i></td>
        <td><i class="fi-dollar"> Price</i></td>
        <td><i class="fi-calendar"> Date</i></td>
        <td><i class="fi-check">State</i></td>
        <td><i class="fi-info"> Plan</i></td>
        <td><i class="fi-info">Is Dropbox?</i></td>
        <td><i class="fi-megaphone"> Category</i></td>
        <td><i class="fi-layout"> Template</i></td>
        <td><i class="fi-results-demographics"> Action</i></td>
      
    </tr>
    <?php 
	
	
				error_reporting(E_ALL);
				//enable_implicit_flush();
				set_time_limit(0);
				require_once("dropbox-sdk/DropboxClient.php");
				/*$prefix=$mfa['id'].'_dropbox';
				foreach(glob("upload_dropbox/$prefix*.*") as $filename){
    
						if(!unlink($filename)){
							echo 'Error Image Unlink';
						}
					
				}*/
				
				
				$dropbox=new DropboxClient(array(
					'app_key' => $mfa['db_app_key'], 
					'app_secret' => $mfa['db_secret_key'],
					'app_full_access' => false,
				),'en');
				
				
				//echo '<pre>'.print_r($dropbox,true).'</pre>';

				
				
				//$access_token = load_token("access");
				$sqll="select * from tokens where user_id='".$mfa['id']."'";
				$q=mysql_query($sqll);
				$num_rows=mysql_num_rows($q);
				$m=mysql_fetch_array($q);
				
				$access_token='';
				if($num_rows==1){
					$access_token=array('t'=>$m['token_t'], 's'=>$m['token_s']);
					//echo '<pre>'.print_r($access_token,true).'</pre>';
				}
				
				if(!empty($access_token)) {
					
					$dropbox->SetAccessToken($access_token);
					//echo "loaded access token:";
					//print_r($access_token);
				}
				elseif(!empty($_GET['auth_callback'])){
					
					// then load our previosly created request token
					$request_token = load_token($_GET['oauth_token']);
					if(empty($request_token)) die('Request token not found!');
	
					// get & store access token, the request token is not needed anymore
					$access_token = $dropbox->GetAccessToken($request_token);
					echo '<pre>'.print_r($access_token,true).'</pre>';
					
					$sql_del="delete from tokens where user_id='".$mfa['id']."'";
					mysql_query($sql_del);
					
					$sqll="insert into tokens(user_id, token_t, token_s, token_date) values('".$mfa['id']."', '".mysql_real_escape_string(trim($access_token['t']))."','".mysql_real_escape_string(trim($access_token['s']))."','".date('Y-m-d H:i:s')."')";
					mysql_query($sqll); 
					
					/*store_token($access_token, "access");*/
					delete_token($_GET['oauth_token']);
				
				}

				// checks if access token is required
				if(!$dropbox->IsAuthorized()){
					
					// redirect user to dropbox auth page
					$return_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?page=listings&auth_callback=1";
					$auth_url = $dropbox->BuildAuthorizeUrl($return_url);
					$request_token = $dropbox->GetRequestToken();
					store_token($request_token, $request_token['t']);
					die("Authentication required. <a href='$auth_url'>Click here.</a>");
				
				}

				
				/*print_r($dropbox->GetAccountInfo());*/

				$files = $dropbox->GetFiles("/",true);
				
				
				//echo '<pre>'.print_r($files,true).'</pre>';
				foreach($files as $value){
					$img='';
					if($value->is_dir!=1){
						
						
						if($value->mime_type=='image/jpeg' || $value->mime_type=='image/png' || $value->mime_type=='image/gif'){
						
							
							//$img=base64_encode($dropbox->GetThumbnail($value->path,'m'));
							
							$text=$value->path;
    						if(substr_count($text, '/')==2){
								
								$exp=explode('/',$text);
								$text='/'.$exp[2];
								
							}
							$rand=$mfa['id'].'_dropbox_'.uniqid().'_'.rand(1000,999999999).'.';
							$text=ltrim($text,'/');
							$text=pathinfo($text, PATHINFO_EXTENSION);
							$text=$rand.$text;
							
							
							$sql="select * from user_images where user_id='".$mfa['id']."' and client_mtime='".$value->client_mtime."'";
							$q=mysql_query($sql);
							if(mysql_num_rows($q)>0){
								
								continue;
							
							}
							$dropbox->DownloadFile($value->path, 'upload_dropbox/'.$text);
							$barcode="Comment Function";
							$db_fun=new db_queries();
							//$barcode=$db_fun->getBarcode($text);
							
							$sql="insert into user_images(user_id, category_id, title, detail, price, ship_detail, pricing_detail, ui_image, ui_barcode, ui_dropbox, ui_status, path, client_mtime, modified, ui_date) values('".$mfa['id']."', '112529', '', '', '', '', '', '".$text."', '".trim($barcode)."', '1', '0', '".$value->path."', '".$value->client_mtime."', '".$value->modified."',  '".date('Y-m-d H:i:s')."')";
							mysql_query($sql);
	
	}
		
		
			}
	}
	$sql="select * from user_images where user_id='".$mfa['id']."' and ui_status!=2 order by ui_id DESC";
	$q=mysql_query($sql);
	while($mfa=mysql_fetch_array($q)){
		
		
	
	
	# plan & template
	$sql_pt="select plan,temp_id from users where id='".$mfa['user_id']."'";
	$query_pt=mysql_query($sql_pt);
	$mfa_pt=mysql_fetch_array($query_pt);
	
	$db_fun=new db_queries();
	
	$plan=$db_fun->getPlan($mfa_pt['plan']);
	$template=$db_fun->getTemplate($mfa_pt['temp_id']);
	
	# Get Category
	if($mfa['category_id']==0){
		$mfa['category_id']=112529;
	}
	$category=$db_fun->getCategoryTitle($mfa['category_id']);
	$date=strtotime($mfa['ui_date']);
	$date=date('d/m/y H:i:s',$date);
		
	?>
    <tr class="listing_entry">
      
      
        <td><input id="checkbox" name="listing" class="checkbox_list" type="checkbox"></td>
        <td><img class="listing_avatar" src="<?php echo SURL.'upload_dropbox/'.$mfa['ui_image']?>" alt="listing_avatar" /></td>
        <td><p><?php echo stripslashes($mfa['title'])?></p></td>
        <td><?php echo stripslashes($mfa['price'])?></td>
        <td><?php echo stripslashes($date)?></td>
        <td><?php echo stripslashes($status_array[$mfa['ui_status']])?></td>
        <td><?php echo stripslashes($plan)?></td>
        <td><?php echo trim($mfa['ui_dropbox'])?></td>
        <td><?php echo stripslashes($category)?></td>
        <td><?php echo stripslashes($template)?></td>
      
      
          <td>
            
                <a style="color:#FFF" href="<?php echo SURL.'index.php?page=edit-listing&pram='.$mfa['ui_id']?>"><button><i class="fi-pencil"></i>Edit</button></a>
                
                <a style="color:#FFF" onclick="return confirm('Do you want to delete this listing?');" href="<?php echo SURL.'index.php?page=listings&pram='.$mfa['ui_id']?>"><button><i class="fi-x"></i>Remove</button></a>
            
           </td>
           
    </tr>
    
    
    
    <?php
	}
    ?>
    
  </table>
</div>
<!--<div class="small-12 columns">
  <div id="form_end">
    <div class="small-3 columns selected_listings">
      <select>
        <option value="">-- With Selected --</option>
        <option value="publish">List</option>
        <option value="edit">Edit</option>
        <option value="remove">Remove</option>
      </select>
    </div>
    <div class="small-1 small-6-offset columns selected_listings"> <a href="#" class="button radius small">Go</a> </div>
    <div class="small-2 columns selected_listings">
      <select>
        <option value="">-- View --</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="100">100</option>
        <option value="200">200</option>
        <option value="0">All</option>
      </select>
    </div>
  </div>
</div>-->
<?php include("footer.php");




function store_token($token, $name)
{
	if(!file_put_contents("tokens/$name.token", serialize($token)))
		die('<br />Could not store token! <b>Make sure that the directory `tokens` exists and is writable!</b>');
}

function load_token($name)
{
	if(!file_exists("tokens/$name.token")) return null;
	return @unserialize(@file_get_contents("tokens/$name.token"));
}

function delete_token($name)
{
	@unlink("tokens/$name.token");
}

?>
<script>
	$(document).ready(function() {
		/*$( ".listing_entry" ).clone().appendTo( "#listing_view" );
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
		$( ".sort_list .fi-thumbnails" ).click(function() {
			$("#listing_view").removeClass("listing_list").addClass("listing_grid");
			$('.listing_grid td').contents().unwrap().wrap('</span>');
		});*/
		$( ".sort_list .fi-list" ).click(function() {
			$( "#listing_view" ).removeClass("listing_grid").addClass("listing_list");
			$('.listing_list .listing_entry span').contents().unwrap().wrap('<td/>');
		});
	});
	</script>
