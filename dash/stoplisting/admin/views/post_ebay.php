<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading"> 
      Publish & Review Listing 
      <?php if($response!=''){echo $clean_response;}?>
      </header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="template" method="post" action="">
          <input type="hidden" name="action" />
          
          
          
          
            
          <div class="form-group">
            <div class="col-sm-12">
                <iframe src="<?php echo SURL.'template_view/template.php?temp_id='.$template.'&list_id='.intval(trim($_GET['pram1']));?>" width="100%" style="border:0 none;height: 500px;"></iframe>
            </div>
          </div>
          
          <div class="form-group">
          	<label class="col-sm-3 control-label">&nbsp;</label>
            <div class="col-sm-6">
              <button class="btn btn-primary" type="submit">Publish To Online Store</button>
              <a href="<?php echo SAURL.'images_listings'?>"><button style="background-color:#F00;border:#F00" class="btn btn-primary">Cancel</button></a>
            </div>
          </div>
        </form>
      </div>
    </section>
  </div>
</div>