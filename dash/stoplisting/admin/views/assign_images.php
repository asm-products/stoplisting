<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading"> Assign Images to Category </header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="">
          <input type="hidden" name="action" value="add_catimage" />
          <div class="form-group">
            <label class="col-sm-3 control-label">Category</label>
            <div class="col-sm-6">
              <select id="e1" class="populate " name="image_cat" style="width: 300px">
                
                <option value="">Select Category</option>
				<?php 
				while($category=mysql_fetch_array($category_data))
				{
				?>
                <option value="<?php echo $category["id"];?>"><?php echo $category["name"];?></option>
                <?php }?>
              
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Image</label>
            <div class="col-sm-6">
              <select id="e2" class="populate " name="image" style="width: 300px">
                <option value="">Select Image</option>
                <!--<option value="AK">Alaska</option>
                <option value="HI">Hawaii</option>
                
                <option value="CA">California</option>
                <option value="NV">Nevada</option>
                <option value="OR">Oregon</option>
                <option value="WA">Washington</option>
                
                <option value="AZ">Arizona</option>
                <option value="CO">Colorado</option>
                <option value="ID">Idaho</option>
                <option value="MT">Montana</option>
                <option value="NE">Nebraska</option>
                <option value="NM">New Mexico</option>
                <option value="ND">North Dakota</option>
                <option value="UT">Utah</option>
                <option value="WY">Wyoming</option>-->
              
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Image Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="name" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Image URL</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="url" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Image Description</label>
            <div class="col-sm-6">
              <textarea class="form-control" rows="6" name="img_desc"></textarea>
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
            <div class="col-sm-7" style="text-align:center;">
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </div>
</div>
