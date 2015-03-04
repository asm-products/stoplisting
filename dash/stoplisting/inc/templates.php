<?php 
include('admin/classes/sql_queries.php');
include('admin/classes/db_queries.php');
$db_fun=new db_queries();
$sql="select * from templates where user_id='".$_SESSION['login_user_id']."' OR user_id=0";
$query=mysql_query($sql);

$msg='';
# remove template
if(isset($_GET['temp_id'])){
	
	$temp_id=intval($_GET['temp_id']);
	$db_fun->trashSingle('templates', 'temp_id', $temp_id);
	
	
	$url=SURL.'index.php?page=templates&msg=Delete Record Successfully';
	echo "<script>window.location = '".$url."';</script> ";
}
if(isset($_GET['msg'])){

	$msg=$_GET['msg'];

}
?>
<div class="small-12 columns">
  <div class="listings">
    <div class="small-4 columns"> <!--<span class="sort_list"> <i class="fi-list"></i> <i class="fi-thumbnails"></i> </span>--> <a href="<?php echo SURL.'add-template'?>">Add New Template</a> </div>
    <div class="small-4 columns">
      <h4>Manage Templates</h4>
      <?php 
	  if($msg!=''){
      	echo "<h7 style='color:green'>$msg</h7>";
	  }
	  ?>
	</div>
    <div class="small-4 columns"> <!--<span class="export_list"> Export List: <a href="">XML</a> <a href="">CSV</a> <a href="">PDF</a> </span>--> </div>
  </div>
 </div>
 
 <div class="small-12 columns">
  <table id="listing_view" class="listing_list listing_grid">
    <tr>
      
      <td><i class="fi-clipboard-pencil"> Title</i></td>
      <td><i class="fi-clipboard-pencil"> User</i></td>
      <td><i class="fi-results-demographics"> Action</i></td>
    </tr>
    <?php 
	$user_default_temp_id=$db_fun->getUserDefaultTemplate($_SESSION['login_user_id']);
	while($mfa=mysql_fetch_array($query)){
		
	$user='stoplisting';
	if($mfa['user_id']!=0){
		
		$user=$db_fun->getUser($mfa['user_id']);
	
	}
	$default_str='';
	if($user_default_temp_id==$mfa['temp_id']){
		
		$default_str=" [ <span style='color:#0C0'>Default</span> ]";
	}
		
	?>
    <tr class="listing_entry">
      
      <td style="background-image:none"><p><?php echo stripslashes($mfa['temp_title']).$default_str?></p></td>
      <td>
      	<?php 
		if($user!='stoplisting'){
		?>
		<a href="<?php echo SURL.'profile'?>">
			<?php echo stripslashes($user)?>
		</a>
		<?php
		}
		else{
			echo stripslashes($user);
		}?>
      </td>
      
     
      <td>
      	<?php 
		if($mfa['user_id']!=0){
		?>
            <a href="<?php echo SURL.'index.php?page=edit-template&temp_id='.$mfa['temp_id']?>">
            	<button><i class="fi-pencil"></i>Edit</button>
            </a>
            <a href="<?php echo SURL.'index.php?page=templates&temp_id='.$mfa['temp_id']?>">
            	<button><i class="fi-x"></i>Remove</button>
            </a>
        <?php 
		}
		?>
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

<?php include("footer.php");?>
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
			$( "#listing_view" ).addClass("listing_grid");
			$('.listing_list .listing_entry span').contents().unwrap().wrap('<td/>');
		});
	});
	</script>
