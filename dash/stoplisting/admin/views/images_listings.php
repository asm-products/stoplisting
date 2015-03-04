<style>
.stats_block {
background: rgba(7,7,7,0.02);box-shadow: inset 0em 0em 5em rgba(99,99,99,0.14),1px 1px 5px rgba(7,7,7,0.05);border-left: 5px solid rgba(0,0,0,0.135);cursor:pointer;
}
.mini-stat-info {width:100%; float:left; cursor:pointer;}
</style>
<div class="row">
  <div class="col-md-3">
    <div class="mini-stat clearfix stats_block">
      <!--<span class="mini-stat-icon orange"><i class="fa fa-star"></i></span>-->
      <a href="<?=SAURL?>pub_images" style=" color: #767676;">
      <div class="mini-stat-info"> <span><em style="float:left;"><?php echo $complete_today[0];?></em></span>
        <p style="float:right; font-size:16px;margin-top: 6px;margin-bottom: 0px;">
          <!--Ready for Publishing-->
          Completed</p>
      </div>
      </a> <a href="<?=SAURL?>images_listings" style=" color: #767676;">
      <div class="mini-stat-info"> <span><em style="float:left;"><?php echo $undone[0];?></em></span>
        <p style="float:right; font-size:16px;margin-top: 6px;margin-bottom: 0px;">Uncompleted</p>
      </div>
      </a> </div>
  </div>
  <div class="col-md-3"> <a href="<?=SAURL?>images_listings/today" style="color: #767676;">
    <div class="mini-stat clearfix stats_block">
      <!--<span class="mini-stat-icon tar"><i class="fa fa-list-alt"></i></span>-->
      <div class="mini-stat-info"> <span><em style=" text-align: center;width: 100%;float: left;"><?php echo $complete_week[0];?></em></span>
        <!--fa-info -->
        <p style="font-size:16px;margin-bottom: 14px;text-align: center;">
          <!--Need More Information-->
          Listings Made This Week</p>
      </div>
    </div>
    </a> </div>
  <div class="col-md-3"> <a href="<?=SAURL?>canc_images" style=" color: #767676;">
    <div class="mini-stat clearfix stats_block">
      <!--<span class="mini-stat-icon pink"><i class="fa fa-tag"></i></span>-->
      <div class="mini-stat-info"> <span><em style=" text-align: center;width: 100%;float: left;"><?php echo $reject[0];?></em></span>
        <p style="font-size:16px;margin-bottom: 14px;text-align: center;">
          <!--Added This Week-->
          Listings Returned</p>
      </div>
    </div>
    </a> </div>
  <div class="col-md-3"> <a href="<?=SAURL?>support" style=" color: #767676;">
    <div class="mini-stat clearfix stats_block">
      <!--<span class="mini-stat-icon green"><i class="fa fa-eye"></i></span>-->
      <div class="mini-stat-info"> <span><em style=" text-align: center;width: 100%;float: left;">?</em></span>
        <p style="font-size:16px;margin-bottom: 14px;text-align: center;">
          <!--Get Support-->
          New Support Questions</p>
      </div>
    </div>
    </a> </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <section class="panel">
    <?php
   		//Set Listings type to get
		/*
			0 = uncompleted listing
			1 = admin completed listing
			2 = successfully listed to online store
			3 = listing error – no judgement made on correction
			4 = User will resolve listing error 
			5 = Admin should resolve listing error
			6 = Listing Destroyed
			7 = In Listing Queue
		*/
		
                switch ($type) {
		    case 0:
		        $ui_status_num 		= 0;
		        $ui_status_title 	= "Uncompleted Listings";
		        break;
		    case 1:
		        $ui_status_num 		= 1;
		        $ui_status_title 	= "Admin Completed Listings";
		        break;
		    case 2:
		        $ui_status_num 		= 2;
		        $ui_status_title 	= "Published To Online Store(s)";
		        break;
		    case 3:
		        $ui_status_num 		= 3;
		        $ui_status_title 	= "Listings With Errors - No Judgement";
		        break;
		    case 4:
		        $ui_status_num 		= 4;
		        $ui_status_title 	= "Listings With Errors - Users to Fix";
		        break;
		    case 5:
		        $ui_status_num 		= "5 OR  ui_status=3";
		        $ui_status_title 	= "Listing With Errors - Need Admin Reslution";
		        break;
		    case 6:
		        $ui_status_num 		= 6;
		        $ui_status_title 	= "Destroyed/Trashed Listings";
		        break;		        
		    case 7:
		        $ui_status_num 		= 7;
		        $ui_status_title 	= "In Listing Queue/Ready For Publishing";
		        break;		   		        		    
		    default:
		        $ui_status_num 		= 0;
		        $ui_status_title 	= "Uncompleted Listings";
		        break;
		}
              
              ?>
      <header class="panel-heading"><h4><?php echo $ui_status_title; ?></h4><span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a> <a href="javascript:;" class="fa fa-cog"></a> <a href="javascript:;" class="fa fa-times"></a> </span> </header>
      <div class="panel-body">
	  <?php if(isset($msg)){?>
        <div class="alert alert-success alert-block fade in">
          <button data-dismiss="alert" class="close close-sm" type="button"> <i class="fa fa-times"></i> </button>
          <h4> <i class="icon-ok-sign"></i> Success! </h4>
        </div>
		<?php }?>
        <div class="adv-table">
          <table  class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
              <tr>
                <td>Photo(s)</td>
                <?php if ($ui_status_num !== 0) {?><td>Title</td><?php } ?>
                <td>User</td>
                <td>Listings Remaining</td>
                <td>Default Template</td>
                <td>Status</td>
                <td class="hidden-phone">Action</td>
              </tr>
            </thead>
            <tbody>
		<?php          
			if ($ui_status_num > 0){$sort_column = "modified";} else {$sort_column = "ui_id";}
			// Get Uncompleted Listings In Order Of Add
			$sql		= "SELECT ui_id, title, ui_status, user_id, ui_image FROM user_images WHERE ui_status = ".$ui_status_num." ORDER BY ".$sort_column." DESC";
			
			$q		= mysql_query($sql);
			while($mfa = mysql_fetch_array($q)){
			$sqll		= "select name, bonus_listings, listings_remaining, temp_id from users where id='".$mfa['user_id']."'";
			$query		= mysql_query($sqll);
			$mfa1		= mysql_fetch_array($query);
			$template	= $db_fun->getTemplate($mfa1['temp_id']);
			// Get listings of only people who have listings remaining.
			if(($mfa1['listings_remaining'] > 0) || ($ui_status_num !== 0 )) {
		?>
                 <tr class="gradeX">
		<td>
		<a data-toggle="modal" href="#myModal<?php echo $mfa['ui_id']?>">
		<?php 
		$photos = explode("-", $mfa['ui_image']);?>
		    <!--<img src="<?php echo SURL.'upload_dropbox/'.$photos[0]?>" width="120" height="80" />-->
			<img src="../../stoplisting/admin/image-resizer-master/getImage.php?url=../../upload_dropbox/<?php echo $photos[0]?>&height=80&width=120" />
		</a>
		  <!-- Modal -->
		  <div class="modal fade" id="myModal<?php echo $mfa['ui_id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		          <h4 class="modal-title"><?php echo stripslashes($mfa['title'])?></h4>
		        </div>
		        <div class="modal-body"> <img src="<?php echo SURL.'upload_dropbox/'.$photos[0]?>" alt="listing_avatar" style="width:100%;" /> </div>
		      </div>
		    </div>
		  </div>
		  <!-- modal -->
		</td>
		<?php if ($ui_status_num !== 0) {?><td><?php echo stripslashes($mfa['title'])?></td><?php } ?>
                <td><?php echo stripslashes($mfa1['name'])?>-<?php echo stripslashes($mfa['user_id'])?></td>
                <td><?php echo stripslashes($mfa1['listings_remaining'])?></td>
                <td><?php echo stripslashes($template)?></td>
                <td><?php echo stripslashes($ui_status_title)?></td>
                <td><a class="btn btn-info" href="<?=SAURL?>index.php?p=edit-listing&parm=<?php echo $mfa['ui_id']?>">Edit</a> <a class="btn btn-danger" href="<?php echo SAURL.'index.php?p=post_ebay&pram1='.$mfa['ui_id'].'&pram2='.$mfa['user_id']?>">Review & Publish</a></td>
                 </tr>
              
              <?php }} ?>           
            </tbody>          
          </table>
        </div>
      </div>
    </section>
  </div>
</div>
<?php


function store_token($token, $name)
{
	if(!file_put_contents("../tokens/$name.token", serialize($token)))
		die('<br />Could not store token! <b>Make sure that the directory `tokens` exists and is writable!</b>');
}

function load_token($name)
{
	if(!file_exists("../tokens/$name.token")) return null;
	return @unserialize(@file_get_contents("../tokens/$name.token"));
}

function delete_token($name)
{
	@unlink("../tokens/$name.token");
}


function enable_implicit_flush()
{
	
	@apache_setenv('no-gzip', 1);
	@ini_set('zlib.output_compression', 0);
	@ini_set('implicit_flush', 1);
	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	ob_implicit_flush(1);
	echo "<!-- ".str_repeat(' ', 2000)." -->";
}
?>

