
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
      <header class="panel-heading"> Template Listing <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a> <a href="javascript:;" class="fa fa-cog"></a> <a href="javascript:;" class="fa fa-times"></a> </span> </header>
      <div class="panel-body">
	  
        <div class="adv-table">
          <table  class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>User</th>
                <th class="hidden-phone">Action</th>
              </tr>
            </thead>
            <tbody>
				<?php 
				$count=0;
				while($mfa=mysql_fetch_array($result)){	
				$count++;
				
				$user='admin';
				if($mfa['user_id']!=0){
					
					$user=$db_fun->getUser($mfa['user_id']);
				
				}
				?>
					
				 
              	<tr class="gradeX">
                    
                    <td><?php echo $count?></td>
                    <td><?php echo stripslashes($mfa['temp_title'])?></td>
                    <td>
                    	<?php 
						if($user!='admin'){
						?>
                    	<a href="<?php echo SAURL?>index.php?p=edit_client&parm=<?php echo stripslashes($mfa['user_id'])?>">
							<?php echo stripslashes($user)?>
                        </a>
                        <?php
                        }
                        else{
                        	echo stripslashes($user);
                        }?>
                        
                    </td>
                    
                    <td class="center hidden-phone">
                        <a class="btn btn-info" href="<?=SAURL?>index.php?p=edit_template&parm=<?php echo $mfa['temp_id']?>">Edit</a>
                        <?php /*?><a class="btn btn-danger" href="<?=SAURL?>index.php?p=templates&parm=<?php echo $mfa['temp_id']?>">Delete</a><?php */?>
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