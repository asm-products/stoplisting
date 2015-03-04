<?php 
include('admin/classes/db_queries.php');
$db_fun=new db_queries();




if(isset($_GET['pram'])){
	
	$sql="select ui_image from user_images where user_id='".$_SESSION['login_user_id']."' and ui_id='".intval(trim($_GET['pram']))."'";
	$qry=mysql_query($sql);
	$mfa_del=mysql_fetch_array($qry);
	
	$sql="delete from user_images where user_id='".$_SESSION['login_user_id']."' and ui_id='".intval(trim($_GET['pram']))."'";
	mysql_query($sql);
	
	if($mfa_del['ui_image']!='' && file_exists(SURL.'upload_dropbox/'.$mfa_del['ui_image'])){
		
		unlink(SURL.'upload_dropbox/'.$mfa_del['ui_image']);
		
	}
	
	$url=SURL.'index.php?page=dash&msg=Listing remove successfully';
	echo "<script>window.location = '".$url."';</script> ";

}





$week_total=$db_fun->thisWeekListing($_SESSION['login_user_id']);
$need_more_info=$db_fun->needMoreInfo($_SESSION['login_user_id']);
$ready_for_publish=$db_fun->readyForPublish($_SESSION['login_user_id']);



$sql="select * from user_images where user_id='".$_SESSION['login_user_id']."' and ui_status=2 order by ui_id DESC";
$q=mysql_query($sql);

?>
<div class="updates small-12 columns"> <a href=""><span><em><?php echo $ready_for_publish?></em> Ready for Publishing</span></a> <a href=""><span><em><?php echo $need_more_info?></em> Need More Information</span></a> <a href=""><span><em><?php echo  $week_total?></em> Added This Week</span></a> <a href=""><span><em>?</em> Get Support</span></a> </div>
<style>
#listing_view .listing_entry td:nth-child(9), #listing_view .listing_entry td:nth-child(4), #listing_view .listing_entry td:nth-child(4){
	
	font-size:0.875rem;
	font-weight:normal;
}
</style>
<div class="small-12 columns">
  <h3>LATEST LISTINGS</h3>
  <div class="listings">
    <table id="listing_view" class="listing_list">
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
	  
	  while($mfa=mysql_fetch_array($q)){
	
	
		$sqll="select * from users where id='".$mfa['user_id']."'";
		$query=mysql_query($sqll);
		$mfa1=mysql_fetch_array($query);
		
		
		# plan & template
		$sql_pt="select plan,temp_id from users where id='".$mfa['user_id']."'";
		$query_pt=mysql_query($sql_pt);
		$mfa_pt=mysql_fetch_array($query_pt);
		
		$plan=$db_fun->getPlan($mfa_pt['plan']);
		$template=$db_fun->getTemplate($mfa_pt['temp_id']);
		
		# Get Category
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
        	
            <button><i class="fi-pencil"></i><a style="color:#FFF" href="<?php echo SURL.'index.php?page=edit-listing&pram='.$mfa['ui_id']?>">Edit</a></button>
        	
            <button><i class="fi-x"></i><a style="color:#FFF" onclick="return confirm('Do you want to delete this listing?');" href="<?php echo SURL.'index.php?page=dash&pram='.$mfa['ui_id']?>">Remove</a></button>
        	
			<button><i class='fi-check'></i><a style="color:#FFF" href="<?php echo SURL.'index.php?page=edit-listing&pram='.$mfa['ui_id']?>">List</a></button>
			
            
         </td>
          
          
          
      </tr>
      
      
      <?php 
	  }
	  ?>
      
    </table>
  </div>
</div>

<?php include("footer.php");?>
<script>
	/*$(document).ready(function() {
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
	});*/
</script>
