
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
		<?php unset($_SESSION['msg']);}?>
    <section class="panel">
      <header class="panel-heading"> Published Images List <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a> <a href="javascript:;" class="fa fa-cog"></a> <a href="javascript:;" class="fa fa-times"></a> </span> </header>
      <div class="panel-body">
	  
        <div class="adv-table">
          <table  class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
              <tr>
                <th>Category Name</th>
                <th>Image Name</th>
                <th>Image URL</th>
				<th class="hidden-phone">Create Date</th>
                <th class="hidden-phone">Status</th>
              </tr>
            </thead>
            <tbody>
				<?php 
					$chkCount	=	0;
					$colid	=	1;				
					for($i=0;$i<$num_rows;$i++) 
					{
					?>
					
				 
              <tr class="gradeX">
                <td><?php echo $user[$i]['cat_name']; ?></td>
                <td><?php echo $user[$i]['img_name']; ?></td>
                <td><?php echo $user[$i]['url']; ?></td>
				
				<td><?php echo $user[$i]['create_date']; ?></td>
                <td class="center hidden-phone"><?php 
						if($user[$i]['status'] == 1){
							?>
                  <span class="badge bg-success"> Active </span>
                  <?php
						} else {
							?>
                  <span class="badge bg-important">Inactive </span>
                  <?php
                        }
						?></td>
                
              </tr>
			  
			  <?php $colid++;} ?>

            </tbody>
            <tfoot>
               <tr>
                <th>Category Name</th>
                <th>Image Name</th>
                <th>Image URL</th>
				<th class="hidden-phone">Create Date</th>
                <th class="hidden-phone">Status</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>
