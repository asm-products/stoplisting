<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading"> Add New Images Category </header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="">
		<input type="hidden" name="action" value="add_cat" />
          <div class="form-group">
            <label class="col-sm-3 control-label">Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="name">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Category Description</label>
            <div class="col-sm-6">
              <textarea class="form-control" rows="6" name="cat_desc"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Status</label>
            <div class="minimal-red single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="3" type="radio"  name="status" value="0">
                <label>Disable</label>
              </div>
            </div>
            <div class="minimal-green single-row col-sm-1 ">
              <div class="radio ">
                <input tabindex="3" type="radio" checked="checked"  name="status" value="1">
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
