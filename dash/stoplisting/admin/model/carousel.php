<div id="carousel">
	<h2>Item Listing Success</h2>
	<div class="item_wing">
		<a href="http://agapeworks.x10.mx/dash/stoplisting/admin/index.php?p=edit-listing&parm=<?php echo $item_wing[0]['ui_id']; ?>">
			<span>Previous Item</span>
			<img src="http://agapeworks.x10.mx/dash/stoplisting/admin/image-resizer-master/getImage.php?app&width=90&height=100&amp;url=<?php $photo_prev = explode("-", $item_wing[0]['ui_image']); echo $photo_prev[0]; ?>"/>
			<i class="fa">&lt;</i>
		</a>
	</div>
	<div id="item_middle">
		<div id="item_middle_left">
			<?php
			$photos = explode("-", $image_order);
			echo '<img alt="Photo" src="http://agapeworks.x10.mx/dash/stoplisting/admin/image-resizer-master/getImage.php?&app&width=152&height=152&amp;url='.$photos[0].'">';
			?>
			
			<span><strong>Price:</strong> $<?php echo $price; ?></span>
			<span><strong>Shipping:</strong> $<?php echo $shipping_cost; ?></span>
			<span><strong>Category:</strong> <?php echo $category_id; ?></span>
			<span><strong>Condition:</strong> <?php echo $condition; ?></span>
			<span><strong>Duration:</strong> <?php echo $duration; ?></span>
			<span><strong>Quantity:</strong> <?php echo $quantity; ?></span>
		</div>
		<div id="item_middle_right">
			<span><strong>Title:</strong> <?php echo $title; ?></span>
			<span><strong>Description:</strong> <?php echo $detail; ?></span>
			<span><strong>Listing Type:</strong> <?php echo $listing_type; ?></span>
			<span><strong>Template:</strong> <?php echo $template; ?></span>
			<span><strong>Shipping Service:</strong> <?php echo $shipping_type; echo "-".$shipping_package; echo "-".$shiping_service ?></span>
			<span><strong>Handling Time:</strong> <?php echo $handling_time; ?> Days</span>
			<span><strong>Specifications:</strong></span><hr/>
			<ul>
			<?
			$item_stats = json_decode($specific_nodes, true);
			for($i=0;$i < count($item_stats['names']);$i++) {
				echo  "<li>".htmlentities(str_replace("&#39;", "'", $item_stats['names'][$i])).": ".htmlentities(str_replace("&#39;", "'", $item_stats['values'][$i]))."</li>";
			}
			?>
			</ul>
		</div>
			
		<div id="item_middle_bottom">
			<a href="http://agapeworks.x10.mx/dash/stoplisting/admin/index.php?p=edit-listing&parm=<?php echo $param; ?>" class="carousel_option">Edit Again</a>
			<a href="#" class="carousel_option">Send to Listing Queue</a>
			<a href="http://agapeworks.x10.mx/dash/stoplisting/admin/index.php?p=post_ebay&pram1=<?php echo $param; ?>&pram2=<?php echo $mfa['user_id'];?>" class="carousel_option">List Now</a>
			<a href="http://agapeworks.x10.mx/dash/stoplisting/admin/images_listings-type-0" class="carousel_option">Go To Uncompleted Page</a>
			<a href="http://agapeworks.x10.mx/dash/stoplisting/admin/images_listings-type-1" class="carousel_option">Go To Completed Page</a>
		</div>
	</div>
	<div class="item_wing">
		<a href="http://agapeworks.x10.mx/dash/stoplisting/admin/index.php?p=edit-listing&parm=<?php echo $item_wing[1]['ui_id']; ?>">
			<span>Next Item</span>
			<img src="http://agapeworks.x10.mx/dash/stoplisting/admin/image-resizer-master/getImage.php?app&width=92&height=100&amp;url=<?php $photo_next = explode("-", $item_wing[1]['ui_image']); echo $photo_next[0]; ?>"/>
			<i class="fa">&gt;</i>
		</a>
	</div>
</div>
<style>
#carousel * {font-family: Arial !important;padding:0;margin:0;}
#carousel {width: 79.49%;border: 1px solid rgba(0,0,0,0.5);border-radius:3px 3px 2px 2px;min-height:410px;margin-left:19%;background: #CCCFBC;position:relative;top:80px;}
#carousel h2 {font-size:1.5em;padding: 1% 0%;margin:0%;text-align:center;border-bottom: 2px solid rgba(0,0,0,0.8);background: rgba(0,0,0,0.8);color:#86942A;box-shadow:inset 0px 0px 60px rgba(0,0,0,1);text-shadow:1px 1px 1px rgba(25,25,25,1);}
#item_middle {color: rgba(255,255,255,0.87); width: 75%;float:left;padding:1%;background: rgba(0,0,0,0.75);border-bottom:1px solid rgba(0,0,0,0.4);}
.item_wing {width: 12.5%;height:363px;float:left;padding:1.07%;background: rgba(0,0,0,0.83);box-shadow:inset 0px 0px 10px rgba(0,0,0,0.6);}	
.item_wing img {width: 100%;margin:1em 0%;border:2px solid rgba(0,0,0,0.5);max-height:134px;}
.item_wing a {display:block;height:100%;text-decoration:none;color:#eee;text-align:center;font-weight:bold;font-size:1.4em;line-height:2.4em;}
.item_wing .fa {font-size:130px;color:rgba(255,255,255,0.15);text-shadow:1px 1px 5px rgba(0,0,0,0.3);}

#item_middle_left {float:left;}
#item_middle_left {width:25%;margin-bottom:20px;}

#item_middle_left img {width:100%;border:1px solid rgba(0,0,0,0.3);}
#item_middle_left span{display:block;padding:1%;text-align:right;}

#item_middle_right{float:right;width:70%;padding:2%}
#item_middle_right span  {display:block;}

#item_middle_bottom {clear:both;border: 2px solid rgba(0,0,0,0.4);background:rgba(25,25,25,0.95);box-shadow: inset 0px 0px 30px 1px rgba(0,0,0,0.5);border-radius:2px;}
#item_middle_bottom a {font-weight:bold;text-decoration:none;color:rgba(15,15,15,1); border: 1px solid rgba(0,0,0,0.5);line-height:25px;padding:7px;margin-left:2.4%;box-shadow: inset 0px 0px 10px 1px rgba(255,255,255,0.2);}

#item_middle_bottom a:nth-child(1) {background:rgba(10,124,140,1);}
#item_middle_bottom a:nth-child(2) {background:rgba(154,163,0,1);}
#item_middle_bottom a:nth-child(3) {background:rgba(182,71,0,1);}
#item_middle_bottom a:nth-child(4) {background:rgba(198,31,50,1);}
#item_middle_bottom a:nth-child(5) {background:rgba(128,131,120,1);}


#item_middle hr {margin-bottom:10px;}
#item_middle li {list-style: square;font-style:italic;margin-left:30px;line-height:24px;}
#item_middle_left strong {font-size:1.2em;}
#item_middle_right strong {font-size:1.1em;line-height: 1.6em;}
</style>

