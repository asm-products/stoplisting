<div class="small-12 columns">
  <div class="listings">
    <div class="small-4 columns"> <span class="sort_list"> <i class="fi-list"></i> <i class="fi-thumbnails"></i> </span> </div>
    <div class="small-4 columns">
      <h4>Manage Listings</h4>
    </div>
    <div class="small-4 columns"> <span class="export_list"> Export List: <a href="">XML</a> <a href="">CSV</a> <a href="">PDF</a> </span> </div>
  </div>
  <table id="listing_view" class="listing_list listing_grid">
    <tr>
      <td><i class="fi-checkbox"></i></td>
      <td><i class="fi-photo"> Photo</i></td>
      <td><i class="fi-clipboard-pencil"> Title</i></td>
      <td><i class="fi-dollar"> Price</i></td>
      <td><i class="fi-calendar"> Date</i></td>
      <td><i class="fi-check"> State</i></td>
      <td><i class="fi-info"> Format</i></td>
      <td><i class="fi-clock"> Duration</i></td>
      <td><i class="fi-megaphone"> Promoted</i></td>
      <td><i class="fi-layout"> Template</i></td>
      <td><i class="fi-results-demographics"> Action</i></td>
    </tr>
    <tr class="listing_entry">
      <td><input id="checkbox" name="listing" class="checkbox_list" type="checkbox"></td>
      <td><img class="listing_avatar" src="img/game.JPG" alt="listing_avatar"/></td>
      <td><p>16GB JXD S7800B Quad Core RK3188 Android Console Pad Tablet HD</p></td>
      <td>$200.00</td>
      <td>07/05/14</td>
      <td>New</td>
      <td>Fixed Price</td>
      <td>7 Days</td>
      <td><i class="fi-social-facebook"></i> <i class="fi-social-twitter"></i> <i class="fi-social-pinterest"></i> <i class="fi-social-stumbleupon"></i> <i class="fi-social-tumblr"></i> <i class="fi-social-reddit"></i> </td>
      <td>Minimal Template #1</td>
      <td><button><i class="fi-pencil"></i> Edit</button>
        <button><i class="fi-x"></i> Remove</button>
        <button><i class="fi-check"></i> List</button></td>
    </tr>
  </table>
</div>
<div class="small-12 columns">
  <div id="form_end">
    <div class="small-3 columns selected_listings">
      <select>
        <option value="">-- With Selected --</option>
        <option value="publish">List</option>
        <option value="edit">Edit</option>
        <option value="remove">Remove</option>
      </select>
    </div>
    <div class="small-1 small-6-offset columns selected_listings"> <a href="#" class="button radius small">Go</a> </div>
    <div class="small-2 columns selected_listings">
      <select>
        <option value="">-- View --</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="100">100</option>
        <option value="200">200</option>
        <option value="0">All</option>
      </select>
    </div>
  </div>
</div>
<?php include("footer.php");?>
<script>
	$(document).ready(function() {
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
		$( ".listing_entry" ).clone().appendTo( "#listing_view" );
		$( ".sort_list .fi-thumbnails" ).click(function() {
			$("#listing_view").removeClass("listing_list").addClass("listing_grid");
			$('.listing_grid td').contents().unwrap().wrap('</span>');
		});
		$( ".sort_list .fi-list" ).click(function() {
			$( "#listing_view" ).removeClass("listing_grid").addClass("listing_list");
			$('.listing_list .listing_entry span').contents().unwrap().wrap('<td/>');
		});
	});
	</script>
