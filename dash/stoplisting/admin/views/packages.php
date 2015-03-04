
<div class="row">
  <div class="col-sm-12">
  <?php 
  if(isset($_SESSION['msg']))
  {
  	$msg	=	$_SESSION['msg'];
  	
	?>
        <div class="alert alert-success alert-block fade in">
          <button data-dismiss="alert" class="close close-sm" type="button"> <i class="fa fa-times"></i> </button>
          <h4> <i class="icon-ok-sign"></i> Success! </h4>
          <p><?php echo $_SESSION['msg'];?></p>
        </div>
		<?php }?>
    <section class="panel">
      <header class="panel-heading"> Packages List <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a> <a href="javascript:;" class="fa fa-cog"></a> <a href="javascript:;" class="fa fa-times"></a> </span> </header>
      <div class="panel-body">
	  
        <div class="adv-table">
          <table  class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
              <tr>
                
                <th>Package Title</th>
                <th>Package Price</th>
                <th>Package Limit</th>
                <th>Gumroad ID</th>
                <th>Package URL Code</th>
                <!--<th class="hidden-phone">Join Date</th>-->
                <!--<th class="hidden-phone">Status</th>-->
				<th class="hidden-phone">Action</th>
              </tr>
            </thead>
            <tbody>
				<?php 
				$count=0;
				while($mfa=mysql_fetch_array($result)){	
				$count++;
				?>
					
				 
              	<tr class="gradeX">
                    
                    
                    <td><?php echo stripslashes($mfa['plan_title'])?></td>
                    <td><?php echo '$'.stripslashes($mfa['plan_price'])?></td>
                    <td><?php echo stripslashes($mfa['plan_limit']).' Items Monthly'?></td>
                    <td><?php echo stripslashes($mfa['plan_product_id'])?></td>
                    <td><?php echo stripslashes($mfa['plan_url_code'])?></td>
                    
                    <td class="center hidden-phone">
							<?php /*?><a class="btn btn-success" href="<?=SAURL?>index.php?p=add_cat">View</a><?php */?>
                            <a class="btn btn-info" href="<?=SAURL?>index.php?p=edit_package&parm=<?php echo $mfa['plan_id']?>">Edit</a>
                            <?php /*?><a class="btn btn-danger" href="<?=SAURL?>index.php?p=packages&parm=<?php echo $mfa['plan_id']?>">Delete</a><?php */?>
                    </td>
              
              	</tr>
			  
			  
			  <?php } ?>

            </tbody>
            
          </table>
        </div>
      </div>
    </section>
  </div>
</div>