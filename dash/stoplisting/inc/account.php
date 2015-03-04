
<div class="large-12 columns">
<h3>Account | <?php echo $_SESSION['user_name'];?></h3>
<div class="large-12 columns">
  <div class="large-4  columns" id="selected_listings"> <img src="http://stoplisting.com/dash/Design/img/avatar/user_img.jpg"/>
    <ul style="list-style:none;">
      <li><b>Date Joined </b></li>
	  <li><?php echo $_SESSION['user_date_join'];?></li>
    </ul>
  </div>
  <div class="large-2 columns" id="selected_listings">
    <button>View More</button>
  </div>
</div>
<?php include("footer.php");?>
</div>
<script>
    $(document).ready(function() {
    $( ".listing_entry" ).clone().appendTo( "#listing_view" );
    });
    </script>
