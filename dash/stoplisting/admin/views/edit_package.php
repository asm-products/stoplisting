<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading"> Edit Package </header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="">
          <input type="hidden" name="action" value="edit_package" />
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Gender</label>
            <div class="minimal-green single-row col-sm-1 ">
              
              <div class="radio ">
                <input tabindex="1" type="radio" <?php if(stripslashes($mfa['plan_title'])=='Free'){ echo "checked='checked'";}?> value="Free" name="plan">
                <label>Free</label>
              </div>
              <div class="radio ">
                <input tabindex="2" type="radio" <?php if(stripslashes($mfa['plan_title'])=='Startup'){ echo "checked='checked'";}?> value="Startup" name="plan">
                <label>Startup</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 "> 
              <div class="radio ">
                <input tabindex="3" type="radio" <?php if(stripslashes($mfa['plan_title'])=='Basic'){ echo "checked='checked'";}?>  value="Basic" name="plan">
                <label>Basic</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="4" type="radio" <?php if(stripslashes($mfa['plan_title'])=='Empowered'){ echo "checked='checked'";}?> value="Empowered" name="plan">
                <label>Empowered</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="5" type="radio" <?php if(stripslashes($mfa['plan_title'])=='Enterprise'){ echo "checked='checked'";}?> value="Enterprise" name="plan">
                <label>Enterprise</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Price</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="price" value="<?php echo stripslashes($mfa['plan_price'])?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Limit</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="limit" value="<?php echo stripslashes($mfa['plan_limit'])?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Package URL(in user panel upgrade link) Code</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="plan_url_code" value="<?php echo stripslashes($mfa['plan_url_code'])?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">GumRoad Product Id</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="gum_id" value="<?php echo stripslashes($mfa['plan_product_id'])?>">
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
