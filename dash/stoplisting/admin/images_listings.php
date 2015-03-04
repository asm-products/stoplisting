<div class="row">
  <div class="col-sm-12">
    <section class="panel">
      <header class="panel-heading"> IMAGES LISTINGS <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a> <a href="javascript:;" class="fa fa-cog"></a> <a href="javascript:;" class="fa fa-times"></a> </span> </header>
      <div class="panel-body">
	  <?php if(isset($msg)){?>
        <div class="alert alert-success alert-block fade in">
          <button data-dismiss="alert" class="close close-sm" type="button"> <i class="fa fa-times"></i> </button>
          <h4> <i class="icon-ok-sign"></i> Success! </h4>
          <p>Best check yo self, you're not looking too good...</p>
        </div>
		<?php }?>
        <div class="adv-table">
          <table  class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
              <tr>
                <td>User</td>
                <td>Title</td>
                <td>Photo</td>
                <td>Barcode</td>
                <td>Template</td>
                <td>Plan</td>
                <td>Is Dropbox?</td>
                <td>Status</td>
                <td class="hidden-phone">Action</td>
              </tr>
            </thead>
            <tbody>
              
              
              
              
              <?php 
			  
			  error_reporting(E_ALL);
			  //enable_implicit_flush();
			  set_time_limit(0);
			  require_once("../dropbox-sdk/DropboxClient.php");
			  while($mfa=mysql_fetch_array($sql)){
				
				
				$dropbox=new DropboxClient(array(
					'app_key' => $mfa['db_app_key'], 
					'app_secret' => $mfa['db_secret_key'],
					'app_full_access' => false,
				),'en');
				
				
				//$access_token = load_token("access");
				$sqll="select * from tokens where user_id='".$mfa['id']."'";
				$q=mysql_query($sqll);
				$num_rows=mysql_num_rows($q);
				$m=mysql_fetch_array($q);
				
				$access_token='';
				if($num_rows==1){
					$access_token=array('t'=>$m['token_t'], 's'=>$m['token_s']);
					echo '<pre>'.print_r($access_token,true).'</pre>';
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
					
					$sql_del="delete from tokens where user_id='".$mfa['id']."'";
					mysql_query($sql_del);
					
					$sqll="insert into tokens(user_id, token_t, token_s, token_date) values('".$mfa['id']."', '".mysql_real_escape_string(trim($access_token['t']))."','".mysql_real_escape_string(trim($access_token['s']))."','".date('Y-m-d H:i:s')."')";
					mysql_query($sqll); 
					
					/*store_token($access_token, "access");
					delete_token($_GET['oauth_token']);*/
				
				}

				// checks if access token is required
				if(!$dropbox->IsAuthorized()){
					
					// redirect user to dropbox auth page
					$return_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?p=images_listings&auth_callback=1";
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
						
							
							$text=$value->path;
    						if(substr_count($text, '/')==2){
								
								$exp=explode('/',$text);
								$text='/'.$exp[2];
								
							}
							$rand=$mfa['id'].'_dropbox_'.uniqid().'_'.rand(1000,999999999).'.';
							$text=ltrim($text,'/');
							$text=pathinfo($text, PATHINFO_EXTENSION);
							$text=$rand.$text;
							
							
							$sqls="select * from user_images where user_id='".$mfa['id']."' and client_mtime='".$value->client_mtime."'";
							$q=mysql_query($sqls);
							if(mysql_num_rows($q)>0){
								
								continue;
							
							}
							
							
							$dropbox->DownloadFile($value->path, '../upload_dropbox/'.$text);
							
							$barcode="Comment Function";
							$barcode=$db_fun->getBarcode($text);
							
							$sqld="insert into user_images(user_id, title, detail, ui_image, ui_barcode, ui_dropbox, ui_status, path, client_mtime, modified, ui_date) values('".$mfa['id']."', '', '', '".$text."', '".trim($barcode)."', '1', '0', '".$value->path."', '".$value->client_mtime."', '".$value->modified."',  '".date('Y-m-d H:i:s')."')";
							mysql_query($sqld);

				
				
					}
					
					
					}
				}
              
             }
				
			
			$sql="select * from user_images where ui_dropbox=1";
			$q=mysql_query($sql);
			while($mfa=mysql_fetch_array($q)){
				
				
			$sqll="select * from users where id='".$mfa['user_id']."'";
			$query=mysql_query($sqll);
			$mfa1=mysql_fetch_array($query);
			
			
			# plan & template
			$sql_pt="select plan,temp_id from users where id='".$mfa['user_id']."'";
			$query_plan=mysql_query($sql_pt);
			$mfa_pt=mysql_fetch_array($query_pt);
			
			$plan=$db_fun->getPlan($mfa_pt['plan']);
			$template=$db_fun->getTemplate($mfa_pt['temp_id']);
			
			?>

              
              <tr class="gradeX">
                <td><a href="<?php echo SAURL?>index.php?p=edit_client&parm=<?php echo stripslashes($mfa1['id'])?>"><?php echo stripslashes($mfa1['email'])?></a></td>
                <td><?php echo stripslashes($mfa['title'])?></td>
                <td>
                <a data-toggle="modal" href="#myModal">
					<?php //echo "<img src=\"data:image/jpeg;base64,$img\" alt=\"Generating PDF thumbnail failed!\" style=\"border: 1px solid black;\" />"?>
                    <!--<img alt="" src="../script/timthumb.php?src=&w=100&h=80" />-->
                    <img src="<?php echo SURL.'upload_dropbox/'.$mfa['ui_image']?>" width="100" height="80" />
                    
                </a>
                  <!-- Modal -->
                  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title"><?php echo trim($mfa['ui_barcode'])?></h4>
                        </div>
                        <div class="modal-body"> <img src="<?=SURL?>img/game.JPG" alt="listing_avatar" style="width:100%;" /> </div>
                      </div>
                    </div>
                  </div>
                  <!-- modal -->
                </td>
                <td><?php echo trim($mfa['ui_barcode'])?></td>
                <td><?php echo stripslashes($template)?></td>
                <td><?php echo stripslashes($plan)?></td>
                <td><?php echo trim($mfa['ui_dropbox'])?></td>
				<?php /*?><td>
				
				<select id="e1" class="populate " name="image_cat">
                
                <option value="">Select Category</option>
				<?php 
				while($category=mysql_fetch_array($category_data))
				{
					
				?>
                <option value="<?php echo $category["id"];?>"><?php echo $category["name"];?></option>
                <?php }?>
              
              </select>
			 
				</td><?php */?>
                
                <td><?php echo stripslashes($status_array[$mfa['ui_status']])?></td>
                <td><a class="btn btn-info" href="<?=SAURL?>index.php?p=edit-listing&parm=<?php echo $mfa['ui_id']?>">Edit</a> <!--<a class="btn btn-danger" href="">Delete</a>--> </td>
              </tr>
              
              <?php
              }
			  ?>
              
              
              
              
              
              
              
              
              
            </tbody>
            
          </table>
        </div>
      </div>
    </section>
  </div>
</div>
<?php


function store_token($token, $name)
{
	if(!file_put_contents("../tokens/$name.token", serialize($token)))
		die('<br />Could not store token! <b>Make sure that the directory `tokens` exists and is writable!</b>');
}

function load_token($name)
{
	if(!file_exists("../tokens/$name.token")) return null;
	return @unserialize(@file_get_contents("../tokens/$name.token"));
}

function delete_token($name)
{
	@unlink("../tokens/$name.token");
}





function enable_implicit_flush()
{
	
	@apache_setenv('no-gzip', 1);
	@ini_set('zlib.output_compression', 0);
	@ini_set('implicit_flush', 1);
	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	ob_implicit_flush(1);
	echo "<!-- ".str_repeat(' ', 2000)." -->";
}




?>
<script>
	$(document).ready(function() {
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
		$( ".sort_list .fi-thumbnails" ).click(function() {
			$("#listing_view").removeClass("listing_list").addClass("listing_grid");
			$('.listing_grid td').contents().unwrap().wrap('<span></span>');
		});
		$( ".sort_list .fi-list" ).click(function() {
			$( "#listing_view" ).removeClass("listing_grid").addClass("listing_list");
			$('.listing_list span').contents().unwrap().wrap('<td></td>');
		});
	});
	</script>
