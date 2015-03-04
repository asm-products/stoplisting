<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading"> Add New Customer </header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="">
          <input type="hidden" name="action" value="add_client" />
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Oauth Provider Facebook</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="oauth_provider" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Oauth_uid Facebook</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="oauth_uid" value="">
            </div>
          </div>
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="name" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Email</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="email" value="">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Twitter Oauth Token</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="twitter_oauth_token" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Twitter Oauth Token Secret</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="twitter_oauth_token_secret" value="">
            </div>
          </div>
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Country</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="country" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Plan</label>
            <div class="col-sm-6">
              <select id="e1" class="populate " name="plan" style="width: 300px">
                
                
                <?php 
                    while($mfa_plan=mysql_fetch_array($query_plan)){
                        
                   $selected="";
                     /*if($value==$mfa['ui_status']){
                        $selected="selected='selected'";
                    }*/
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
                     /*if($value==$mfa['ui_status']){
                        $selected="selected='selected'";
                    }*/
                    ?>
                
                	<option <?php echo $selected?> value="<?php echo stripslashes($mfa_temp['temp_id'])?>"><?php echo stripslashes($mfa_temp['temp_title'])?></option>
                
				<?php }?>
				
              
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Price</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="price" value="">
            </div>
          </div>
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Offer Code</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="offercode" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Order#</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="order_no" value="">
            </div>
          </div>
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Dropbox App Key</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="db_app_key" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Dropbox Secret Key</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="db_secret_key" value="">
            </div>
          </div>
          
           <div class="form-group">
            <label class="col-sm-3 control-label">Ebay Developer Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_dev" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Ebay App Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_app" value="">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Ebay Cert Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_cert" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Ebay Auth Token</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="ebay_token" value="">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Paypal ID</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="paypal_id" value="">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Password</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="password" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Join Date</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="join_date" value="">
            </div>
          </div>
          
          
         <div class="form-group">
            <label class="col-sm-3 control-label">Status</label>
            <div class="minimal-red single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="3" type="radio" value="0" name="status">
                <label>Disable</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="3" type="radio"  checked="checked" value="1" name="status">
                <label>Enable </label>
              </div>
            </div>
          </div>
         <div class="form-group">
            <label class="col-sm-3 control-label">General Condition</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="general_condition" value="">
            </div>
          </div>
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Price Preference</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="price_preference" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Latest Purchase</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="latest_purchase" value="">
            </div>
          </div>
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Bonus Listings</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="bonus_listings" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Listings Remaining</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="listings_remaining" value="">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Listing Preference</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="listing_preference" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">#Referrals</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="num_referrals" value="">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Referred By</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="referred_by" value="">
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
