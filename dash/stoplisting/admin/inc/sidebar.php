<aside>
  <div id="sidebar" class="nav-collapse">
    <!-- sidebar menu start-->
    <div class="leftside-navigation">
      <ul class="sidebar-menu" id="nav-accordion">
		<li class="sub-menu"> <a href="javascript:;"> <i class="fa fa-book"></i> <span>Customer Listings</span> </a>
          <ul class="sub">
            <li><a href="<?=SAURL?>images_listings-type-0">Uncompleted Listings <?php if ($undone[0] > 0 ) {?><span id="num_undone"><?php echo $undone[0];?></span><? } ?></a></li>
            <li><a href="<?=SAURL?>images_listings-type-1">Completed Listings</a></li> 
            <li><a href="<?=SAURL?>images_listings-type-5">Rejected Listings <?php if ($reject[0] > 0 ) {?><span id="num_rejects"><?php echo $reject[0];?></span><? } ?></a></li> 
            <li><a href="<?=SAURL?>images_listings-type-7">Items In Listing Queue</a></li>    
            <li><a href="<?=SAURL?>images_listings-type-2">Published To WebStore</a></li>    
            <li><a href="<?=SAURL?>images_listings-type-4">Not Enough User Info <?php if ($notenough[0] > 0 ) {?><span id="num_enough"><?php echo $notenough[0];?></span><? } ?></a></li>
            <li><a href="<?=SAURL?>images_listings-type-6">Removed Listings</a></li>    
          </ul>
        </li>
		<li class="sub-menu"> <a href="javascript:;"> <i class="fa fa-book"></i> <span>Manage Customers</span> </a>
          <ul class="sub">
            <li><a href="<?=SAURL?>customers">Customers List</a></li>
            <li><a href="<?=SAURL?>add_client">Add New Customers</a></li>            
          </ul>
        </li>
        
        
        <li class="sub-menu"> <a href="javascript:;"> <i class="fa fa-book"></i> <span>Manage Packages</span> </a>
          <ul class="sub">
            <li><a href="<?=SAURL?>packages">Package List</a></li>
           <!-- <li><a href="<?=SAURL?>add_package">Add New Package</a></li>-->            
           
            <li><a href="<?=SAURL?>categories">Categories List</a></li>
            <li><a href="<?=SAURL?>add_cat">Add New Images Category</a></li>   
          </ul>
        </li>
        
         <li class="sub-menu"> <a href="javascript:;"> <i class="fa fa-book"></i> <span>Manage Templates</span> </a>
          <ul class="sub">
            <li><a href="<?=SAURL?>templates">Template List</a></li>
            <li><a href="<?=SAURL?>add_template">Add New Template</a></li>            
          </ul>
        </li>
        
        <li class="sub-menu"> <a href="javascript:;"> <i class="fa fa-book"></i> <span>Swank Rank</span> </a>
          <ul class="sub">
            <li><a href="<?=SAURL?>swank">Swank Rank</a></li>
          </ul>
        </li>
        
      </ul>
    </div>
    <!-- sidebar menu end-->
  </div>
</aside>
<!--sidebar end-->
