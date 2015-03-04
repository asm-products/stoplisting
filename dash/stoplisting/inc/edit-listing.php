<?php
include('admin/classes/sql_queries.php');
include('admin/classes/db_queries.php');

$pram='';

if(isset($_GET['pram'])){

	
	$pram=intval($_GET['pram']);

}
else{
	
	$url=SURL.'listings';
	echo "<script>window.location = '".$url."';</script> ";

}

$select=new sql_function();
$fetch=$select->getSingleRecord('user_images', 'ui_id', $pram);	
$mfa=mysql_fetch_assoc($fetch);

$db_fun=new db_queries();

$query_temp=$db_fun->getAllTemplates();
$user_temp_id=$db_fun->getUserDefaultTemplate($mfa['user_id']);


$msg='';

if(isset($_POST['update_listing'])){
	
	$category_id	=	mysql_real_escape_string($_REQUEST["category"]);
	$title	=	mysql_real_escape_string($_REQUEST["title"]);
	$detail	=	mysql_real_escape_string($_REQUEST["detail"]);
	$price	=	mysql_real_escape_string(trim($_REQUEST["price"]));
	$ship	=	mysql_real_escape_string(trim($_REQUEST["ship"]));
	$price_detail	=	mysql_real_escape_string(trim($_REQUEST["price_detail"]));
	$status	=	mysql_real_escape_string($_REQUEST["status"]);
	
	
	$edit_rec	=	$db_fun->updateListing($category_id,$title,$detail,$price,$ship,$price_detail,$status,$pram);
	if($edit_rec==1){
		$msg="Listing Updated Successfully";
		$url=SURL.'index.php?page=listings&msg=success';
		echo "<script>window.location = '".$url."';</script> ";
	}
	
}

if(isset($_GET['pram']) && $_GET['pram']=='success'){

	$msg="Listing Updated Successfully";
	
}

$fetch=$select->getSingleRecord('users', 'id', $mfa['user_id']);
$mfa_user=mysql_fetch_assoc($fetch);

$fetch=$select->getSingleRecord('templates', 'temp_id', $mfa_user['temp_id']);
$mfa_temp=mysql_fetch_assoc($fetch);




$query_ebay=$db_fun->geteBayCategories();

?>
<div class="updates small-12 columns"> <a href=""><span><em>20</em> Ready for Publishing</span></a> <a href=""><span><em>3</em> Need More Information</span></a> <a href=""><span><em>35</em> Added This Week</span></a> <a href=""><span><em>?</em> Get Support</span></a> </div>
<div class="small-12 columns">
  <h3>Update Listing</h3>
  <div class="listings">
    
    
    
    
    
    <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="<?php echo SURL.'index.php?page=edit-listing&pram='.$pram?>">
          <input type="hidden" name="update_listing" />
          
          
          
          
          
          
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Title</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="title" value="<?php echo stripslashes($mfa['title'])?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Description</label>
            <div class="col-sm-6">
              <textarea class="form-control" rows="6" name="detail" spellcheck="false"><?php echo stripslashes($mfa['detail'])?></textarea>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Price</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" readonly="readonly" name="price" value="<?php echo stripslashes($mfa['price'])?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Shipping Detail</label>
            <div class="col-sm-6">
              <textarea class="form-control" rows="6" name="ship" spellcheck="false"><?php echo stripslashes($mfa['ship_detail'])?></textarea>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Pricing Detail</label>
            <div class="col-sm-6">
              <textarea class="form-control" rows="6" name="price_detail" spellcheck="false"><?php echo stripslashes($mfa['pricing_detail'])?></textarea>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Choose Category</label>
            <div class="col-sm-6">
              <select id="e1" class="populate " name="category" style="width: 300px">
                
                
                <?php 
                    while($mfa_ebay=mysql_fetch_array($query_ebay)){
                        
                   	$selected="";
                    if($mfa_ebay['ebay_cat_id']==$mfa['category_id']){
                        $selected="selected='selected'";
                    }
                    ?>
                
                	<option <?php echo $selected?> value="<?php echo $mfa_ebay['ebay_cat_id']?>"><?php echo stripslashes($mfa_ebay['ebay_cat_title'])?></option>
                
				<?php }?>
				
              
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Status</label>
            <div class="col-sm-6">
              <select id="e2" class="populate" disabled="disabled" style="width: 300px">
                
                
                	<?php 
                    foreach($status_array as $key=>$value){
                        
                    $selected="";
                    if($key==$mfa['ui_status']){
                        $selected="selected='selected'";
                    }
                    ?>
                
                		<option <?php echo $selected?> value="<?php echo $key?>"><?php echo stripslashes($value)?></option>
                
					<?php 
					}
					?>
				
              
              </select>
            </div>
          </div>
          
          
          <?php /*?><div class="form-group">
            <label class="col-sm-3 control-label">Image</label>
            <div class="col-sm-6">
              <img src="<?php echo SURL.'upload_dropbox/'.$mfa['ui_image']?>" width="100" height="80">
            </div>
          </div><?php */?>
          
          <?php /*?><div class="form-group">
            <label class="col-sm-3 control-label">Template</label>
            <div class="col-sm-6">
              <?php echo stripslashes($mfa_temp['temp_detail'])?>
            </div>
          </div><?php */?>
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Default Template</label>
            <div class="col-sm-6">
              <select id="e3" class="populate" name="template" style="width: 300px">
                
                
                	<?php 
		
                    while($mfa_temp=mysql_fetch_assoc($query_temp)){
                        
                    $selected="";
                    if($mfa_temp['temp_id']==$user_temp_id){
                        $selected="selected='selected'";
                    }
                    ?>
                
                		<option <?php echo $selected?> value="<?php echo $mfa_temp['temp_id']?>"><?php echo stripslashes($mfa_temp['temp_title'])?></option>
                
					<?php 
					}
					?>
				
              
              </select>&nbsp;<a href="<?php echo SURL.'template_view/template.php?temp_id='.intval(trim($user_temp_id)).'&list_id='.intval(trim($mfa['ui_id']))?>" target="_blank">Template View</a>
            </div>
          </div>
          
          
          
          <input type="hidden" name="status" value="<?php echo stripslashes($mfa['ui_status'])?>">
          
          <div class="form-group">
            <div class="col-sm-7" style="text-align:center;">
              <button class="btn btn-primary" type="submit">Update Listing</button>
            </div>
          </div>
        </form>
    
    
  </div>
</div>
<!--<div class="columns">
  <div id="form_end">
    <div class="small-5 small-5-offset columns selected_listings"> <a href="<?=SURL?>upload" class="button radius small"><i class="fi-upload"></i> Upload Photos</a> <a href="<?=SURL?>create" class="button radius small"><i class="fi-plus"></i> New Listing</a> </div>
    <div class="small-2 columns selected_listings"> <a href="<?=SURL?>manage" class="button radius small">View More</a> </div>
  </div>
</div>-->
<?php include("footer.php");?>
<script>
	$(document).ready(function() {
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
	});
</script>