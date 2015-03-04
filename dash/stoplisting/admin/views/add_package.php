<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading"> Add New Package </header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="">
          <input type="hidden" name="action" value="add_package" />
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Gender</label>
            <div class="minimal-green single-row col-sm-1 ">
              
              <div class="radio ">
                <input tabindex="1" type="radio" checked="checked" value="Free" name="plan">
                <label>Free</label>
              </div>
              <div class="radio ">
                <input tabindex="2" type="radio" value="Startup" name="plan">
                <label>Startup</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="3" type="radio"  value="Basic" name="plan">
                <label>Basic</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="4" type="radio" value="Empowered" name="plan">
                <label>Empowered</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="5" type="radio" value="Enterprise" name="plan">
                <label>Enterprise</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Price</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="price">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Limit</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="limit">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Package URL Code</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="plan_url_code">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">GumRoad Product Id</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="gum_id">
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
