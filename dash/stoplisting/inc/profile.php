<?php
include('admin/classes/sql_queries.php');
include('admin/classes/db_queries.php');


$select=new sql_function();
$fetch=$select->getSingleRecord('users', 'id', $_SESSION['login_user_id']);	
$db_rec=mysql_fetch_assoc($fetch);


$db_fun	=	new db_queries();
$query_temp=$db_fun->getAllUserTemplates($_SESSION['login_user_id']);
$query_plan=$db_fun->getPackages();
$plan=$db_fun->getPlan($db_rec['plan']);



$msg='';
if(isset($_POST['profile'])){
	
	//echo '<pre>'.print_r($_POST,true).'</pre>';
	
	$parm=$_SESSION['login_user_id'];
	$cl_name=stripslashes($_REQUEST["name"]);
	$email=stripslashes($_REQUEST["email"]);
	//$city=stripslashes($_REQUEST["city"]);
	
	$plan=stripslashes($_REQUEST["plan"]);
	$template=stripslashes($_REQUEST["template"]);
	$price=stripslashes($_REQUEST["price"]);
	$offercode=stripslashes($_REQUEST["offercode"]);
	$order_no=stripslashes($_REQUEST["order_no"]);
	$db_app_key=stripslashes($_REQUEST["db_app_key"]);
	$db_secret_key=stripslashes($_REQUEST["db_secret_key"]);
	
	$ebay_dev=mysql_real_escape_string($_REQUEST["ebay_dev"]);
	$ebay_app=mysql_real_escape_string($_REQUEST["ebay_app"]);
	$ebay_cert=mysql_real_escape_string($_REQUEST["ebay_cert"]);
	$ebay_token=mysql_real_escape_string($_REQUEST["ebay_token"]);
	
	$paypal_id=mysql_real_escape_string($_REQUEST["paypal_id"]);
	
	$password=stripslashes(trim($_REQUEST["password"]));
	$old_password=stripslashes(trim($_REQUEST["old_password"]));
	
	if($password!=$old_password){
		$password=md5($password);
	}
	
	$cl_status=stripslashes($_REQUEST["status"]);
	
	$db_fun=new db_queries();
	$edit_rec=$db_fun->edit_client($parm, $cl_name, $email , $plan, $template, $price, $offercode, $order_no, $db_app_key, $db_secret_key, $ebay_dev, $ebay_app, $ebay_cert, $ebay_token, $paypal_id, $password, $cl_status); 
	if($edit_rec==1){
		$msg="Customer Updated Successfully";
		$url=SURL.'index.php?page=profile&pram=success';
		echo "<script>window.location = '".$url."';</script> ";
	}
	
}

if(isset($_GET['pram']) && $_GET['pram']=='success'){

	$msg="Profile Updated Successfully";
	
}


$week_total=$db_fun->thisWeekListing($_SESSION['login_user_id']);
$need_more_info=$db_fun->needMoreInfo($_SESSION['login_user_id']);
$ready_for_publish=$db_fun->readyForPublish($_SESSION['login_user_id']);

?>
<div class="updates small-12 columns"> <a href=""><span><em><?php echo $ready_for_publish?></em> Ready for Publishing</span></a> <a href=""><span><em><?php echo $need_more_info?></em> Need More Information</span></a> <a href=""><span><em><?php echo  $week_total?></em> Added This Week</span></a> <a href=""><span><em>?</em> Get Support</span></a> </div>
<div class="small-12 columns">
  <h3>View / Update Profile</h3>
  <div class="listings">
    
    
    
    
    
    <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="http://agapeworks.x10.mx/dash/stoplisting/profile">
          <input type="hidden" name="profile" value="edit_profile" />
          
          <div class="form-group">
            
            <label style="color:#090" class="col-sm-3 control-label"><?php echo $msg?></label>
            
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="name" value="<?php echo stripslashes($db_rec['name'])?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Email</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="email" value="<?php echo stripslashes($db_rec['email'])?>">
            </div>
          </div>
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Current Plan <a href="<?php echo SURL.'upgrade'?>">Upgrade</a></label>
            <div class="col-sm-6">
              <input type="text" class="form-control" readonly="readonly" value="<?php echo stripslashes($plan)?>">
              <input type="hidden" name="plan" value="<?php echo $db_rec['plan']?>" />
            </div>
          </div>
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Default Template</label>
            <div class="col-sm-6">
              <select id="e2" class="populate " name="template" style="width: 300px">
                
                
                	<?php 
                    while($mfa_temp=mysql_fetch_array($query_temp)){
                        
                    $selected="";
                    if($db_rec['temp_id']==$mfa_temp['temp_id']){
                        $selected="selected='selected'";
                    }
                    ?>
                
                	<option <?php echo $selected?> value="<?php echo stripslashes($mfa_temp['temp_id'])?>"><?php echo stripslashes($mfa_temp['temp_title'])?></option>
                
				<?php }?>
				
              
              </select>
            </div>
          </div>
          
          
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Price</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="price" value="<?php echo stripslashes($db_rec['price'])?>" readonly="readonly">
            </div>
          </div>
          
          <div class="form-group" style="display:none">
            <label class="col-sm-3 control-label">Offercode</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="offercode" value="<?php echo stripslashes($db_rec['offercode'])?>">
            </div>
          </div>
          
          <div class="form-group" style="display:none">
            <label class="col-sm-3 control-label">Order Number</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="order_no" value="<?php echo stripslashes($db_rec['order_no'])?>">
            </div>
          </div>
          
          
          <div class="form-group" style="display:none">
            <label class="col-sm-3 control-label">Dropbox App Key</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="db_app_key" value="<?php echo stripslashes($db_rec['db_app_key'])?>">
            </div>
          </div>
          
          
          <div class="form-group" style="display:none">
            <label class="col-sm-3 control-label">Dropbox Secret Key</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="db_secret_key" value="<?php echo stripslashes($db_rec['db_secret_key'])?>">
            </div>
          </div>
          
          <div class="form-group" style="display:none">
            <label class="col-sm-3 control-label">Ebay Developer Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_dev" value="<?php echo stripslashes($db_rec['ebay_dev'])?>">
            </div>
          </div>
          <div class="form-group" style="display:none">
            <label class="col-sm-3 control-label">Ebay App Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_app" value="<?php echo stripslashes($db_rec['ebay_app'])?>">
            </div>
          </div>
          
          <div class="form-group" style="display:none">
            <label class="col-sm-3 control-label">Ebay Cert Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_cert" value="<?php echo stripslashes($db_rec['ebay_cert'])?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Ebay Auth Token</label>
            <div class="col-sm-6">
              <textarea spellcheck="false" rows="6" class="form-control" name="ebay_token"><?php echo stripslashes($db_rec['ebay_token'])?></textarea>
            </div>
          </div>
          
          <div class="form-group" style="display:none">
            <label class="col-sm-3 control-label">Paypal Address</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="paypal_id" value="<?php echo stripslashes($db_rec['paypal_id'])?>">
            </div>
          </div>
          
          <div class="form-group" style="display:none">
            <label class="col-sm-3 control-label">Password</label>
            <div class="col-sm-6">
              <input type="password" class="form-control" name="password" value="<?php echo $db_rec['password']?>">
              <input type="hidden" name="old_password" value="<?php echo $db_rec['password']?>" />
            </div>
          </div>
          <?php $status=$db_rec["status"];?>
          <div class="form-group">
            <label class="col-sm-3 control-label">Account Status</label>
            <div class="minimal-red single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="3" type="radio" <?php if($status=="0"){?> checked="checked" <?php }?> value="0" name="status">
                <label>Disable My Account</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="3" type="radio" <?php if($status=="1"){?> checked="checked" <?php }?> value="1" name="status">
                <label>Enable My Account</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-7" style="text-align:center;">
              <button class="btn btn-primary" type="submit">Update</button>
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