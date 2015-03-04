<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading"> Edit Customer </header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="">
          <input type="hidden" name="action" value="edit_cat" />
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
            <label class="col-sm-3 control-label">Plan</label>
            <div class="col-sm-6">
              <select id="e1" class="populate " name="plan" style="width: 300px">
                
                
                <?php 
                    while($mfa_plan=mysql_fetch_array($query_plan)){
                        
                   	$selected="";
                    if($db_rec['plan']==$mfa_plan['plan_id']){
                        $selected="selected='selected'";
                    }
                    ?>
                
                	<option <?php echo $selected?> value="<?php echo stripslashes($mfa_plan['plan_id'])?>"><?php echo stripslashes($mfa_plan['plan_title'])?></option>
                
				<?php }?>
				
              
              </select>
            </div>
          </div>
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Template</label>
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
              <input type="text" class="form-control" name="price" value="<?php echo stripslashes($db_rec['price'])?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Offercode</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="offercode" value="<?php echo stripslashes($db_rec['offercode'])?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Order Number</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="order_no" value="<?php echo stripslashes($db_rec['order_no'])?>">
            </div>
          </div>
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Dropbox App Key</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="db_app_key" value="<?php echo stripslashes($db_rec['db_app_key'])?>">
            </div>
          </div>
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Dropbox Secret Key</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="db_secret_key" value="<?php echo stripslashes($db_rec['db_secret_key'])?>">
            </div>
          </div>
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Ebay Developer Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_dev" value="<?php echo stripslashes($db_rec['ebay_dev'])?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Ebay App Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_app" value="<?php echo stripslashes($db_rec['ebay_app'])?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Ebay Cert Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_cert" value="<?php echo stripslashes($db_rec['ebay_cert'])?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Ebay Auth Token</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_token" value="<?php echo stripslashes($db_rec['ebay_token'])?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Paypal ID</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="paypal_id" value="<?php echo stripslashes($db_rec['paypal_id'])?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Password</label>
            <div class="col-sm-6">
              <input type="password" class="form-control" name="password" value="<?php echo stripslashes($db_rec['password'])?>">
              <input type="hidden" name="old_password" value="<?php echo stripslashes($db_rec['password'])?>" />
            </div>
          </div>
          <?php $status=$db_rec["status"]?>
          <div class="form-group">
            <label class="col-sm-3 control-label">Status</label>
            <div class="minimal-red single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="3" type="radio" <?php if($status=="0"){?> checked="checked" <?php }?> value="0" name="status">
                <label>Disable</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="3" type="radio" <?php if($status=="1"){?> checked="checked" <?php }?> value="1" name="status">
                <label>Enable </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-7" style="text-align:center;">
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </div>
</div>
