<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading"> Swank Rank <span style="color:red; font-weight:bold"><?php if(isset($_GET['pram'])){echo $_GET['pram'];}?></span> </header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="">
          <input type="hidden" name="action" value="swank" />
          
          
          
          
          <div class="form-group">
            <label class="col-sm-3 control-label">Keyword</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="keyword" value="<?php echo stripslashes(@$_GET['keyword'])?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Limit</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="limit" value="<?php echo stripslashes(@$_GET['limit'])?>">
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
