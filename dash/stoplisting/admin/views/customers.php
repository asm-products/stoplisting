
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
      <header class="panel-heading"> Customers List <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a> <a href="javascript:;" class="fa fa-cog"></a> <a href="javascript:;" class="fa fa-times"></a> </span> </header>
      <div class="panel-body">
	  
        <div class="adv-table">
          <table  class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
              <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th class="hidden-phone">Join Date</th>
                <th class="hidden-phone">Status</th>
				<th class="hidden-phone">Action</th>
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
                <td><?php echo $user[$i]['id']; ?></td>
                <td><?php echo $user[$i]['name']; ?></td>
                <td><?php echo $user[$i]['email']; ?></td>
				<td><?php echo $user[$i]['joindate']; ?></td>
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
                <td class="center hidden-phone">
                            <?php /*?><a class="btn btn-success" href="<?=SAURL?>index.php?p=add_cat">View</a><?php */?>
                            <a class="btn btn-info" href="<?=SAURL?>index.php?p=edit_client&parm=<?php echo $user[$i]['id']; ?>">Edit</a>
							<a class="btn btn-danger" href="<?=SAURL?>index.php?p=customers&parm=<?php echo $user[$i]['id']; ?>">Delete</a>
                        </td>
              </tr>
			  
			  <?php $colid++;} ?>

            </tbody>
            <tfoot>
              <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th class="hidden-phone">Join Date</th>
                <th class="hidden-phone">Status</th>
				<th class="hidden-phone">Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>
