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
      <header class="panel-heading"> LATEST IMAGES LISTINGS <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a> <a href="javascript:;" class="fa fa-cog"></a> <a href="javascript:;" class="fa fa-times"></a> </span> </header>
      <div class="panel-body">
        <div class="adv-table">
          <table  class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
              <tr>
                <td><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></td>
                <td> Photo</td>
                <td>Title</td>
                <td>Date</td>
                <td>State</td>
                <td class="hidden-phone">Action</td>
              </tr>
            </thead>
            <tbody>
              <tr class="gradeX">
                <td><input type="checkbox" class="checkboxes" id="CB<?=++$chkCount?>" name="CB<?=$chkCount?>" value=" <?=$user[$i]['id'];?>" /></td>
                <td><a data-toggle="modal" href="#myModal"><img src="<?=SURL?>img/game.JPG" alt="listing_avatar" width="70" height="40"/></a>
                  <!-- Modal -->
                  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">16GB JXD S7800B Quad Core RK3188 Android Console Pad Tablet HD</h4>
                        </div>
                        <div class="modal-body"> <img src="<?=SURL?>img/game.JPG" alt="listing_avatar" style="width:100%;" /> </div>
                      </div>
                    </div>
                  </div>
                  <!-- modal -->
                </td>
                <td>16GB JXD S7800B Quad Core RK3188 Android Console Pad Tablet HD</td>
                <td>07/05/14</td>
                <td>New</td>
                <td><a class="btn btn-info" href="">Edit</a> <a class="btn btn-danger" href="">Delete</a> </td>
              </tr>
              <tr class="gradeX">
                <td><input type="checkbox" class="checkboxes" id="CB<?=++$chkCount?>" name="CB<?=$chkCount?>" value=" <?=$user[$i]['id'];?>" /></td>
                <td><a data-toggle="modal" href="#myModal"><img src="<?=SURL?>img/game.JPG" alt="listing_avatar" width="70" height="40"/></a>
                  <!-- Modal -->
                  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">16GB JXD S7800B Quad Core RK3188 Android Console Pad Tablet HD</h4>
                        </div>
                        <div class="modal-body"> <img src="<?=SURL?>img/game.JPG" alt="listing_avatar" style="width:100%;" /> </div>
                      </div>
                    </div>
                  </div>
                  <!-- modal -->
                </td>
                <td>16GB JXD S7800B Quad Core RK3188 Android Console Pad Tablet HD</td>
                <td>07/05/14</td>
                <td>New</td>
                <td><a class="btn btn-info" href="">Edit</a> <a class="btn btn-danger" href="">Delete</a> </td>
              </tr>
              <tr class="gradeX">
                <td><input type="checkbox" class="checkboxes" id="CB<?=++$chkCount?>" name="CB<?=$chkCount?>" value=" <?=$user[$i]['id'];?>" /></td>
                <td><a data-toggle="modal" href="#myModal"><img src="<?=SURL?>img/game.JPG" alt="listing_avatar" width="70" height="40"/></a>
                  <!-- Modal -->
                  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">16GB JXD S7800B Quad Core RK3188 Android Console Pad Tablet HD</h4>
                        </div>
                        <div class="modal-body"> <img src="<?=SURL?>img/game.JPG" alt="listing_avatar" style="width:100%;" /> </div>
                      </div>
                    </div>
                  </div>
                  <!-- modal -->
                </td>
                <td>16GB JXD S7800B Quad Core RK3188 Android Console Pad Tablet HD</td>
                <td>07/05/14</td>
                <td>New</td>
                <td><a class="btn btn-info" href="">Edit</a> <a class="btn btn-danger" href="">Delete</a> </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></td>
                <td> Photo</td>
                <td>Title</td>
                <td>Date</td>
                <td>State</td>
                <td class="hidden-phone">Action</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>
<?php /*?><div class="row"> <a href="<?=SAURL?>index.php?p=upload" class="btn btn-primary"><i class="fa fa-cloud-upload"></i>Upload Photos</a> <a href="<?=SAURL?>index.php?p=create" class="btn btn-success"><i class="fa fa-refresh"></i> New Listing</a> <a href="<?=SAURL?>index.php?p=manage" class="btn btn-default"><i class="fa fa-eye"></i>View More</a> </div><?php */?>
