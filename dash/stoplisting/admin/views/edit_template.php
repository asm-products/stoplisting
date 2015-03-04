<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading"> Edit Template </header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="template" method="post" action="">
          <input type="hidden" name="action" value="edit_template" />
          
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Title</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="title" value="<?php echo stripslashes($mfa['temp_title'])?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Template</label>
            <div class="col-sm-6">
              <textarea id="editor1" name="detail" rows="10" cols="80"><?php echo stripslashes($mfa['temp_detail'])?></textarea>
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
