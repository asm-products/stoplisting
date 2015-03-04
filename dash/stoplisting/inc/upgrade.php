<?php 

include('admin/classes/sql_queries.php');
include('admin/classes/db_queries.php');

$db_fun=new db_queries();
$query=$db_fun->getPackages();


?>
<div id="upgrade_wrapper">
  <div class="small-12 columns">
    <div class="row" id="upgrade">
      <h1>Upgrade Today To Access All Of Our Ecommerce Services.</h1>
    </div>
  </div>
</div>
<div class="small-12 columns">
  <div class="row">
    <p> Here's our super cool copy: Bacon ipsum dolor sit amet strip steak kielbasa meatball, chuck fatback shank beef chicken biltong turducken. Beef ribs turducken ground round ribeye leberkas, ham pig drumstick spare ribs tongue short loin fatback brisket t-bone. Here's our super cool copy: Bacon ipsum dolor sit amet strip steak kielbasa meatball, chuck fatback shank beef chicken biltong turducken. Beef ribs turducken ground round ribeye leberkas, ham pig drumstick spare ribs tongue short loin fatback brisket t-bone. </p>
    <div class="row">
      <div class="small-6 columns">
        <h5>We Promote Your Listings To Your Social Media Accounts</h5>
        <i class="social_upgrade fi-social-facebook"></i> <i class="social_upgrade fi-social-twitter"></i> <i class="social_upgrade fi-social-google-plus"></i> <i class="social_upgrade fi-social-tumblr"></i> <i class="social_upgrade fi-social-pinterest"></i> <i class="social_upgrade fi-social-reddit"></i>
        <hr/>
        <br/>
      </div>
      <div class="small-6 columns">
        <h5>Upgraded Plans Come With</h5>
        <ul>
          <li>Social Listing Promotion On Popular Channels</li>
          <li>Access To (Swank), An Item's Likelihood of Sale</li>
          <li>Speed up Listing with Scanable Items</li>
          <li>Access To Our WYSIWYG Template Generator</li>
          <li>Image Background Remover</li>
        </ul>
      </div>
    </div>
  </div>
  <!-- Payment Plans -->
  <h4 class="section_header">Pricing Plans</h4>
  <div class="row">
    <?php
			/*$plans = array(
				array("Basic Seller","Power Seller","Small Business","Small Business Plus"),
				array("135","249","375","562"),
				array("100","200","300","500"),array("UAge","AQGhE","NOOQ","qNLY") 
			);
			for($i=0;$i < count($plans);$i++) {
				echo '
				<div class="buy_area side_compare small-3 columns"><strong>'.$plans[0][$i].' Package</strong><br>
				<span><em>$'.$plans[1][$i].'</em>/Mo</span>
				<p>Access to our listing service for up to '.$plans[2][$i].' Items monthly.</p><br>
				<a href="https://gum.co/'.$plans[3][$i].'?wanted=false&amp;locale=false" class="gumroad-button">Upgrade</a></div>
				';
			}*/
			
			while($mfa=mysql_fetch_assoc($query)){
				
				echo '
				<div class="buy_area side_compare small-3 columns"><strong>'.stripslashes($mfa['plan_title']).' Package</strong><br>
				<span><em>$'.stripslashes($mfa['plan_price']).'</em>/Mo</span>
				<p>Access to our listing service for up to '.stripslashes($mfa['plan_limit']).' Items monthly.</p><br>
				<a href="https://gum.co/'.stripslashes($mfa['plan_url_code']).'?wanted=false&amp;locale=false" class="gumroad-button">Upgrade</a></div>
				';
				
				
			}
		?>
  </div>
  <div class="buy_area paypal" style="display:none"><strong>Pay With PayPal</strong><br>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
      <input name="cmd" value="_s-xclick" type="hidden">
      <br>
      <input name="hosted_button_id" value="7TPG7NWMLFZ2N" type="hidden">
      <br>
      <input name="on0" value="Pay With Paypal" type="hidden">
      <p></p>
      <select>
        <option value="Startup Package">Startup Package : $70.00 USD – monthly</option>
        <option value="Basic Seller Package">Basic Seller Package : $135.00 USD – monthly</option>
        <option value="Power Seller Package">Power Seller Package : $249.00 USD – monthly</option>
        <option value="Small Business Package">Small Business Package : $374.99 USD – monthly</option>
      </select>
      <input name="currency_code" value="USD" type="hidden">
      <br>
      <input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_SM.gif" type="image">
    </form>
  </div>
  <!-- End Payment Plans -->
  <script type="text/javascript" src="https://gumroad.com/js/gumroad.js"></script>
  <?php include("footer.php");?>
</div>
