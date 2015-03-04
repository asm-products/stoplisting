
<div class="row">
  <div class="col-sm-12">
    <?php 
  if(isset($_SESSION['msg']))
  {
  	$msg	=	$_SESSION['msg'];
  	
	?>
    <div class="alert alert-success alert-block fade in"> <span class="alert-icon"><i class="fa fa-comments-o"></i></span>
      <button data-dismiss="alert" class="close close-sm" type="button"> <i class="fa fa-times"></i> </button>
      <h4> <i class="icon-ok-sign"></i> Success! </h4>
      <p><?php echo $_SESSION['msg'];?></p>
    </div>
    <?php unset($_SESSION['msg']);}?>
    <section class="panel">
      <header class="panel-heading"> Categories List <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a> <a href="javascript:;" class="fa fa-cog"></a> <a href="javascript:;" class="fa fa-times"></a> </span> </header>
      <div class="panel-body">
        <div class="adv-table">
          <table  class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
              <tr>
                <th>Category ID</th>
                <th>Category Name</th>
                <th>Category Description</th>
                <th class="hidden-phone">Date</th>
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
                <td><?php echo wordwrap($user[$i]['cat_desc'],15,"...");?></td>
                <td><?php echo $user[$i]['cat_date']; ?></td>
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
                <td class="center hidden-phone"><?php /*?><a class="btn btn-success" href="<?=SAURL?>index.php?p=add_cat">View</a><?php */?>
                  <a class="btn btn-info" href="<?=SAURL?>index.php?p=edit_cat&parm=<?php echo $user[$i]['id']; ?>">Edit</a> <a class="btn btn-danger" href="<?=SAURL?>index.php?p=categories&parm=<?php echo $user[$i]['id']; ?>">Delete</a> </td>
              </tr>
              <?php $colid++;} ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Category ID</th>
                <th>Category Name</th>
                <th>Category Description</th>
                <th class="hidden-phone">Date</th>
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
