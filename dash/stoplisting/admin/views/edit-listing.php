<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading col-lg-12"><div class="col-lg-2"><h4><?php if ($_GET['parm'] == 0) { ?>Create<?php } else { ?> Edit<?php } ?> Listing</h4></div><div class="col-lg-5 col-lg-offset-5"> <input class="col-lg-offset-2 col-lg-7" type="text" class="form-control" placeholder="Reference URL" name="reference_url" value=""/> <a href="javascript:;" class="badge bg-red col-lg-3" id="get_stats">Get Data</a></div></header>
      <div class="panel-body">
        <form class="cmxform form-horizontal bucket-form" id="signupForm" method="post" action="">
          <input type="hidden" name="action" value="edit_listing" />
          <div class="form-group">
          <div class="form-group">
		<div class="col-sm-2 photo_menu">
			<h3>Photos</h3><hr/>
			<label>With Selected</label><br/>
			<a href="javascript:;" class="rotate" id="rotate_right_multi">Rotate + 90 Deg</a>
			<a href="javascript:;" class="rotate" id="rotate_left_multi">Rotate - 90 Deg</a>
			<a href="javascript:;" class="rotate" id="rotate_flip_multi">Rotate + 180 Deg</a>
			<a href="javascript:;" id="delete_multi_images">Delete Images</a>
			<a href="javascript:;" id="move_old_delete_selected">Make New Listing</a>
		</div>
            <div class="col-sm-10">
            
		<?php 
		if (isset($mfa['ui_image']) || isset($_REQUEST['ui_image'])) {
		if(isset($_REQUEST['ui_image'])) {
			$photos = explode("-", $_REQUEST['ui_image']);
		} else {
			$photos = explode("-", $mfa['ui_image']);
		}
		for($i=0;$i<count($photos);$i++) {?>
		<div id="photo_left_<?=$i;?>" class="image_wrapper">
		<input type="hidden" name="image_order[]" value="<?php echo $photos[$i]?>">
		<div class="image_header">
			    <input type="checkbox" name="image_change[]" value="<?php echo $photos[$i]?>"/>
			    <a href="javascript:;" id="rotate_left_<?=$i;?>" class="rotate_single"><i class="fa fa-undo"></i></a>
			    <a href="javascript:;" id="rotate_right_<?=$i;?>" class="rotate_single"><i class="fa fa-repeat"></i></a>
			    <a href="javascript:;" id="left_<?=$i;?>" class="order_image"><i class="fa fa-arrow-left"></i></a>
			    <a href="javascript:;" id="right_<?=$i;?>" class="order_image"><i class="fa fa-arrow-right"></i></a>
			    <a href="javascript:;" class="delete_image"><i class="fa fa-times"></i></a>
		</div>
		<a data-toggle="modal" id="myModallink<?php echo $i?>" href="#myModal<?php echo $i?>">
		<img src="../../stoplisting/admin/image-resizer-master/getImage.php?url=../../upload_dropbox/<?php echo $photos[$i]?>&height=150&width=156" /></a>
		</div>
		<!--<img src="<?php echo SURL.'upload_dropbox/'.$photos[$i]?>" width="200" />-->
				
				  <!-- Modal -->
		  <div class="modal fade" id="myModal<?php echo $i?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    <a href="javascript:;" id="left_modal_<?=$i;?>" class="modal_change"><i class="fa fa-chevron-left"></i></a>
			    <h4 class="modal-title"><?php echo stripslashes($mfa['title'])?></h4>
			    <a href="javascript:;" id="right_modal_<?=$i;?>" class="modal_change"><i class="fa fa-chevron-right"></i></a>
		        </div>
		        
		        <div class="modal-body"> 
		<img alt="listing_avatar" style="width:100%;"  src="../../stoplisting/admin/image-resizer-master/getImage.php?url=../../upload_dropbox/<?php echo $photos[$i]?>&height=700&width=500" />
		        <!--<img src="<?php echo SURL.'upload_dropbox/'.$photos[$i]?>" alt="listing_avatar" style="width:100%;" />--> </div>
		      </div>
		    </div>
		  </div>
		  <!-- modal -->
		<?php }}?>
			<!-- Photo Upload -->
				<div id="photo_left_<?=$i;?>" class="image_wrapper">
		
		<a id="add_photo" href="javascript:;">
		<i class="fa fa-plus"></i>
		</a>
		</div>
			<!-- end photo upload -->
			
			
			
            </div>
          </div>
          
          <div class="form-group">
            	<div class="col-sm-12 user_preferences">
                <div class="col-sm-4"><label class="control-label"><b>Price Preferences</b></label><br/><h5><?php if(empty($mfa_user['price_preference'])) {echo "None Added";} else { echo stripslashes(ucfirst($mfa_user['price_preference']));}?></h5></div>
		<div class="col-sm-4"><label class="control-label"><b>Listing Preferences</b></label><br/><h5><?php if(empty($mfa_user['listing_preference'])) {echo "None Added";} else {echo stripslashes(ucfirst($mfa_user['listing_preference']));}?></h5></div>
		<div class="col-sm-4"><label class="control-label"><b>General Condition</b></label><br/><h5><?php if ($mfa_user['general_condition'] == '0') {echo "None Added";} else {echo stripslashes(ucfirst($mfa_user['general_condition']));}?></h5></div>
		</div>
          </div>
         
          <div class="form-group">
            <label for="title" class="col-sm-1 control-label">Title</label>
            <div class="col-sm-5">
              <input type="text" id="title" class="form-control" name="title" value="<?php if (isset($mfa['title'])) { echo stripslashes($mfa['title']); } ?>"><span id="title_count">100</span>
            </div>
    
    	  <label class="col-sm-1 control-label">Quantity</label>
            <div class="col-sm-1">
				<input type="text" class="form-control" id="quantity_item" placeholder="#" name="quantity" value="<?php if (isset($mfa['title'])) { echo stripslashes($mfa['quantity']); }?>">
		</div>
         
            
            <label class="col-sm-1 control-label">Price</label>
            <div class="col-sm-2">
		<div class="input-group">
			<div class="input-group-addon">$</div>
				<input type="text" class="form-control" name="price" value="<?php if (isset($mfa['title'])) { echo stripslashes($mfa['price']); }?>">
			</div>
		</div>
            </div>
          </div>
          
          <div class="form-group">
	<?php
		$names 		= '';
		$values 	= '';
		$subtract	= '';
            if(!empty($mfa['item_stats'])) {

            	$item_stats 	= json_decode($mfa['item_stats'], true);
                for($i=0;$i < count($item_stats['names']);$i++) {
               	 	$names 		.= '<input type="text" class="form-control specific_names" name="item_specific_name[]" id="name_'.$i.'" placeholder="Specs Name" value="'.$item_stats['names'][$i].'"/><br/>';              	 	
               	 	$values		.= '<input type="text" class="form-control specific_values" name="item_specific_value[]" id="value_'.$i.'" placeholder="Value" value="'.$item_stats['values'][$i].'"/><br/>';
               		$subtract 	.= '<br/><a href="javascript:;" class="col-sm-1 remove_spec" id="sub_'.$i.'">&minus;</a>';
                }
            }
            
            ?>
            <label id="specifics_row" class="col-sm-1 control-label">Item Specifics<?php echo $subtract;?></label>
              	
            <div class="col-sm-6">
         
              <div class="col-sm-4 specific_names_row">
              	<b>Specs Name</b><hr/>
              	<?php echo $names;?>
              </div>
              <div class="col-sm-7 specific_values_row">
              	<b>Specs Values</b><hr/>
              	<?php echo $values;?>
              </div>
              <a href="javascript:;" class="col-sm-1 badge bg-success" id="more_specs">ADD</a>
            </div>
            <div class="col-sm-1">
		<label class="control-label">Category</label><br/><br/><br/>
		<label class="control-label">Template</label><br/><br/>
		<label class="control-label">Condition</label><br/><br/>
		<label class="control-label">Listing</label><br/><br/>
		<label class="control-label">Duration</label><br/>
		<?php		
		$store_cat = $db_fun->getStoreCategories("cornerstoneventures",$mfa_user['ebay_token']);
		if(!is_null($store_cat)) { ?>
		<label class="control-label">Store Cat. #1</label><br/>
		<label class="control-label">Store Cat. #2</label><br/><br/>
		<?php } ?>
            </div>
            <div class="col-sm-3">
              <select id="e1" class="populate " name="category" style="padding-left:5px;width: 255px"> 
                	<option></option>  
			<?php 
			while($mfa_ebay=mysql_fetch_assoc($query_ebay)){
				$selected="";
				if($mfa_ebay['ebay_cat_id']==$mfa['category_id']){
					$selected="selected='selected'";
				}
			?>
                	<option <?php echo $selected; ?> value="<?php echo $mfa_ebay['ebay_cat_id']?>"><?php echo stripslashes($mfa_ebay['ebay_cat_title']); ?></option>
		<?php } ?>
		<option selected="selected" value="<?php echo $mfa['category_id']?>"><?php echo "Auto Generated Cat:".stripslashes($mfa['category_id']);?></option>
              </select>
            
            <br/><br/>
              <select id="e6" class="populate" name="template" style="margin-left:4px;width: 251px;padding: 3px 6px;color:#222;">
		<?php 		
		while($mfa_temp=mysql_fetch_assoc($query_temp)){
		$selected="";
		$default ="";
			if(!empty($mfa['title'])) {
				if($mfa_temp['temp_id']==$mfa['temp_id']){
					$selected	= "selected='selected'";
				}
			} else {
				if($mfa_temp['temp_id']==$mfa_user['temp_id']){
					$selected	= "selected='selected'";
					$default 	= " - User Default";
				}
			}
		?>
                	<option <?php echo $selected;?> value="<?php echo $mfa_temp['temp_id']?>"><?php echo stripslashes($mfa_temp['temp_title'])?><?php echo $default;?></option>          
		<?php } ?>
              </select>
   <br/><br/>
              <select id="e5" class="populate" name="condition" style="margin-left:4px;width: 251px;padding: 3px 6px;color:#222;">
              <?php
	             $condition_check = (int) stripslashes($mfa['item_condition']);
              ?>
        		<option value="1000" <?php if($condition_check==1000){echo 'selected="selected"';}?>>New</option>
        		<option value="1500" <?php if($condition_check==1500){echo 'selected="selected"';}?>>New Other</option>
        		<option value="1750" <?php if($condition_check==1750){echo 'selected="selected"';}?>>New With Defects</option>
        		<option value="3000" <?php if(($condition_check==3000) || (empty($condition_check))) {echo 'selected="selected"';}?>>Used/Good</option>
        		<option value="2000" <?php if($condition_check==2000){echo 'selected="selected"';}?>>Manufacturer Refurbished</option>
        		<option value="2500" <?php if($condition_check==2500){echo 'selected="selected"';}?>>Seller Refurbished</option>
        		<option value="4000" <?php if($condition_check==4000){echo 'selected="selected"';}?>>Very Good</option>
        		<option value="6000" <?php if($condition_check==6000){echo 'selected="selected"';}?>>Acceptable</option>	
              </select>
              
 <br/><br/>
              <select id="e6" class="populate" name="listing_type" style="margin-left:4px;width: 251px;padding: 3px 6px;color:#222;">
              <?php
              if(empty($mfa['title']))
              {$listing_check = $mfa_user['listing_type'];}
              else {$listing_check = stripslashes($mfa['listing_type']);}
              ?>
        		<option value="Chinese" <?php if($listing_check=='Chinese'){echo 'selected="selected"';}?>>Auction</option>
        		<option value="FixedPriceItem" <?php if(($listing_check=="FixedPriceItem") || (empty($listing_check))) {echo 'selected="selected"';}?>>FixedPrice</option>
              </select>
                            
 <br/><br/>
              <select id="e7" class="populate" name="duration" style="margin-left:4px;width: 251px;padding: 3px 6px;color:#222;">
              <?php
              if(empty($mfa['title']))
              {$duration_check = $mfa_user['duration'];}
              else {$duration_check = stripslashes($mfa['duration']);}
              ?>
        		<option value="Days_3" <?php if($duration_check=="Days_3"){echo 'selected="selected"';}?>>3 Days</option>      		
        		<option value="Days_5" <?php if($duration_check=="Days_5"){echo 'selected="selected"';}?>>5 Days</option>       		
        		<option value="Days_7" <?php if($duration_check=="Days_7"){echo 'selected="selected"';}?>>7 Days</option>       		
        		<option value="Days_10" <?php if(($duration_check=="Days_10") || (empty($duration_check))) {echo 'selected="selected"';}?>>10 Days</option>       		
        		<option value="Days_30" <?php if($duration_check=="Days_30"){echo 'selected="selected"';}?>>30 Days (FixedPrice Only)</option>        		
        		<option value="GTC" <?php if($duration_check=="GTC"){echo 'selected="selected"';}?>>Good Til Cancelled (FixedPrice Only)</option>     
              </select>
              <?php if(!is_null($store_cat)) { 
              //not yet tested
		$options = array();
		
		foreach ($store_cat as $cats) {
			$options[$cats->CategoryID] = $cats->Name;
			$option_list ='<option value="'.$options[$cats->CategoryID].'-'.$cats->Name.'"';
			if(stripslashes($mfa['store_cat1'])==$options[$cats->CategoryID]){
				$option_list .= 'selected="selected"';
			}
			$option_list .='>'.$cats->Name.'</option>';     
			
			$option_list2 ='<option value="'.$options[$cats->CategoryID].'-'.$cats->Name.'"';
			if(stripslashes($mfa['store_cat2'])==$options[$cats->CategoryID]){
				$option_list2 .= 'selected="selected"';
			}
			$option_list2 .='>'.$cats->Name.'</option>';    			
		}
              ?>
 <br/><br/>
		<select id="e8" class="populate" name="store_cat1" style="margin-left:4px;width: 251px;padding: 3px 6px;color:#222;">
		<?php echo $option_list; ?>
		</select>
 <br/><br/>
		<select id="e10" class="populate" name="store_cat2" style="margin-left:4px;width: 251px;padding: 3px 6px;color:#222;">
		<?php echo $option_list2; ?>
		</select>
              <?php } ?>
            </div>
          </div>
          

          
          <div class="form-group">
          <label class="col-sm-1 control-label">Description</label>
            <div class="col-sm-11">
              <textarea class="form-control" rows="6" id="description" name="detail" spellcheck="true"><?php if (isset($mfa['title'])) { echo stripslashes($mfa['detail']); }?></textarea>
            </div>
          </div>
           	
        <div class="col-sm-12">
        <label class="col-sm-2 control-label">Shipping Type</label>    
	<div class="col-sm-2">
		<div class="input-group">
		<?php
		if(empty($mfa['title']))
		{$shipping_type = $mfa_user['shipping_type'];}
		else {$shipping_type = stripslashes($mfa['shipping_type']);}
		?>    
	            <select class="form-control" id="ship_type" name="shipping_type">
	         	<option value="Flat" <?php if(($shipping_type=="Flat") || (empty($shipping_type))) {echo 'selected="selected"';}?>>Flat</option>
			<option value="FreightFlat" <?php if($shipping_type=="FreightFlat"){echo 'selected="selected"';}?>>Freight Flat</option>				
			<option value="Calculated" <?php if($shipping_type=="Calculated"){echo 'selected="selected"';}?>>Calculated</option>
			<option value="CalculatedDomesticFlatInternational" <?php if($shipping_type=="CalculatedDomesticFlatInternational"){echo 'selected="selected"';}?>>(International) Calculated Domestic Flat</option>
			<option value="FlatDomesticCalculatedInternational" <?php if($shipping_type=="FlatDomesticCalculatedInternational"){echo 'selected="selected"';}?>>(International) Flat Domestic Calculated</option>
	            </select> 	
		</div>
	</div>		

        <label class="col-sm-2 control-label">Shipping Service</label> 
    	<div class="col-sm-2">
		<div class="input-group">
		<?php
		if(empty($mfa['title']))
		{$shipping_service = $mfa_user['shipping_service'];}
		else {$shipping_service = stripslashes($mfa['shipping_service']);}
		?>
	                   <select class="form-control" name="shipping_service">						
				<option value="USPSPriority" <?php if (($shipping_service=="USPSPriority") || (empty($shipping_service))){echo 'selected="selected"';}?>>USPS Priority</option>
				<option value="USPSAirmailLetter" <?php if($shipping_service=="USPSAirmailLetter"){echo 'selected="selected"';}?>>USPS Airmail Letter Post</option>
				<option value="USPSAirmailParcel" <?php if($shipping_service=="USPSAirmailParcel"){echo 'selected="selected"';}?>>USPS Airmail Parcel Post</option>
				<option value="USPSEconomyLetter" <?php if($shipping_service=="USPSEconomyLetter"){echo 'selected="selected"';}?>>USPS Economy Letter Post</option>
				<option value="USPSEconomyParcel" <?php if($shipping_service=="USPSEconomyParcel"){echo 'selected="selected"';}?>>USPS Economy Parcel Post</option>
				<option value="USPSExpressFlatRateEnvelope" <?php if($shipping_service=="USPSExpressFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Express Flat Rate Envelope</option>
				<option value="USPSExpressMail" <?php if($shipping_service=="USPSExpressMail"){echo 'selected="selected"';}?>>USPS Priority Mail Express</option>
				<option value="USPSExpressMailInternational" <?php if($shipping_service=="USPSExpressMailInternational"){echo 'selected="selected"';}?>>USPS Priority Mail Express International</option>
				<option value="USPSFirstClass" <?php if($shipping_service=="USPSFirstClass"){echo 'selected="selected"';}?>>USPS First Class</option>
				<option value="USPSFirstClassMailInternational" <?php if($shipping_service=="USPSFirstClassMailInternational"){echo 'selected="selected"';}?>>USPS First Class Mail Intl / First Class Package Intl Service</option>
				<option value="USPSGlobalExpress" <?php if($shipping_service=="USPSGlobalExpress"){echo 'selected="selected"';}?>>USPS Global Express Mail</option>
				<option value="USPSGlobalExpressGuaranteed" <?php if($shipping_service=="USPSGlobalExpressGuaranteed"){echo 'selected="selected"';}?>>USPS Global Express Guaranteed</option>
				<option value="USPSGlobalPriority" <?php if($shipping_service=="USPSGlobalPriority"){echo 'selected="selected"';}?>>USPS Global Priority Mail</option>
				<option value="USPSGlobalPriorityLargeEnvelope" <?php if($shipping_service=="USPSGlobalPriorityLargeEnvelope"){echo 'selected="selected"';}?>>USPS Global Priority Mail Large Envelope</option>
				<option value="USPSGlobalPrioritySmallEnvelope" <?php if($shipping_service=="USPSGlobalPrioritySmallEnvelope"){echo 'selected="selected"';}?>>USPS Global Priority Mail Small Envelope</option>
				<option value="USPSGround" <?php if($shipping_service=="USPSGround"){echo 'selected="selected"';}?>>USPS Ground</option>
				<option value="USPSMedia" <?php if($shipping_service=="USPSMedia"){echo 'selected="selected"';}?>>USPS Media</option>
				<option value="USPSPriorityFlatRateBox" <?php if($shipping_service=="USPSPriorityFlatRateBox"){echo 'selected="selected"';}?>>USPS Priority Flat Rate Box</option>
				<option value="USPSPriorityFlatRateEnvelope" <?php if($shipping_service=="USPSPriorityFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Flat Rate Envelope</option>
				<option value="USPSPriorityMailInternational" <?php if($shipping_service=="USPSPriorityMailInternational"){echo 'selected="selected"';}?>>USPS Priority Mail International</option>
				<option value="USPSStandardPost" <?php if($shipping_service=="USPSStandardPost"){echo 'selected="selected"';}?>>USPS Standard Post</option>
				<option value="USPSParcel" <?php if($shipping_service=="USPSParcel"){echo 'selected="selected"';}?>>USPS Parcel Select Non-Presort</option>
				<option value="USPSExpressMailFlatRateBox" <?php if($shipping_service=="USPSExpressMailFlatRateBox"){echo 'selected="selected"';}?>>USPS Priority Mail Express Flat Rate Box</option>
				<option value="USPSExpressMailFlatRateEnvelope" <?php if($shipping_service=="USPSExpressMailFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Express Flat Rate Envelope</option>
				<option value="USPSExpressMailInternationalFlatRateBox" <?php if($shipping_service=="USPSExpressMailInternationalFlatRateBox"){echo 'selected="selected"';}?>>USPS Priority Mail Express International Flat Rate Box</option>
				<option value="USPSExpressMailInternationalFlatRateEnvelope" <?php if($shipping_service=="USPSExpressMailInternationalFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Express International Flat Rate Envelope</option>
				<option value="USPSExpressMailInternationalLegalFlatRateEnvelope" <?php if($shipping_service=="USPSExpressMailInternationalLegalFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Express International Legal Flat Rate Envelope</option>
				<option value="USPSExpressMailInternationalPaddedFlatRateEnvelope" <?php if($shipping_service=="USPSExpressMailInternationalPaddedFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Express International Padded Flat Rate Envelope</option>
				<option value="USPSExpressMailLegalFlatRateEnvelope" <?php if($shipping_service=="USPSExpressMailLegalFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Express Legal Flat Rate Envelope</option>
				<option value="USPSExpressMailPaddedFlatRateEnvelope" <?php if($shipping_service=="USPSExpressMailPaddedFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Express Padded Flat Rate Envelope</option>
				<option value="USPSPriorityMailFlatRateBox" <?php if($shipping_service=="USPSPriorityMailFlatRateBox"){echo 'selected="selected"';}?>>USPS Priority Mail Flat Rate Box</option>
				<option value="USPSPriorityMailFlatRateEnvelope" <?php if($shipping_service=="USPSPriorityMailFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Flat Rate Envelope</option>
				<option value="USPSPriorityMailInternationalFlatRateBox" <?php if($shipping_service=="USPSPriorityMailInternationalFlatRateBox"){echo 'selected="selected"';}?>>USPS Priority Mail International Flat Rate Box</option>
				<option value="USPSPriorityMailInternationalFlatRateEnvelope" <?php if($shipping_service=="USPSPriorityMailInternationalFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail International Flat Rate Envelope</option>
				<option value="USPSPriorityMailInternationalLargeFlatRateBox" <?php if($shipping_service=="USPSPriorityMailInternationalLargeFlatRateBox"){echo 'selected="selected"';}?>>USPS Priority Mail International Large Flat Rate Box</option>
				<option value="USPSPriorityMailInternationalLegalFlatRateEnvelope" <?php if($shipping_service=="USPSPriorityMailInternationalLegalFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail International Legal Flat Rate Envelope</option>
				<option value="USPSPriorityMailInternationalPaddedFlatRateEnvelope" <?php if($shipping_service=="USPSPriorityMailInternationalPaddedFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail International Padded Flat Rate Envelope</option>
				<option value="USPSPriorityMailInternationalSmallFlatRateBox" <?php if($shipping_service=="USPSPriorityMailInternationalSmallFlatRateBox"){echo 'selected="selected"';}?>>USPS Priority Mail International Small Flat Rate Box</option>
				<option value="USPSPriorityMailLargeFlatRateBox" <?php if($shipping_service=="USPSPriorityMailLargeFlatRateBox"){echo 'selected="selected"';}?>>USPS Priority Mail Large Flat Rate Box</option>
				<option value="USPSPriorityMailLegalFlatRateEnvelope" <?php if($shipping_service=="USPSPriorityMailLegalFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Legal Flat Rate Envelope</option>
				<option value="USPSPriorityMailPaddedFlatRateEnvelope" <?php if($shipping_service=="USPSPriorityMailPaddedFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Priority Mail Padded Flat Rate Envelope</option>
				<option value="USPSPriorityMailRegionalBoxA" <?php if($shipping_service=="USPSPriorityMailRegionalBoxA"){echo 'selected="selected"';}?>>USPS Priority Mail Regional Box A</option>
				<option value="USPSPriorityMailRegionalBoxB" <?php if($shipping_service=="USPSPriorityMailRegionalBoxB"){echo 'selected="selected"';}?>>USPS Priority Mail Regional Box B</option>
				<option value="USPSPriorityMailRegionalBoxC" <?php if($shipping_service=="USPSPriorityMailRegionalBoxC"){echo 'selected="selected"';}?>>USPS Priority Mail Regional Box C</option>
				<option value="USPSPriorityMailSmallFlatRateBox" <?php if($shipping_service=="USPSPriorityMailSmallFlatRateBox"){echo 'selected="selected"';}?>>USPS Priority Mail Small Flat Rate Box</option>
				<option value="UPS2DayAirAM" <?php if($shipping_service=="UPS2DayAirAM"){echo 'selected="selected"';}?>>UPS Next Day Air</option>
				<option value="UPS2ndDay" <?php if($shipping_service=="UPS2ndDay"){echo 'selected="selected"';}?>>UPS 2nd Day</option>
				<option value="UPS3rdDay" <?php if($shipping_service=="UPS3rdDay"){echo 'selected="selected"';}?>>UPS 3rd Day</option>
				<option value="UPSGround" <?php if($shipping_service=="UPSGround"){echo 'selected="selected"';}?>>UPS Ground</option>
				<option value="UPSNextDay" <?php if($shipping_service=="UPSNextDay"){echo 'selected="selected"';}?>>UPS Next Day</option>
				<option value="UPSNextDayAir" <?php if($shipping_service=="UPSNextDayAir"){echo 'selected="selected"';}?>>UPS Next Day Air</option>
				<option value="UPSStandardToCanada" <?php if($shipping_service=="UPSStandardToCanada"){echo 'selected="selected"';}?>>UPS Standard To Canada</option>
				<option value="UPSWorldWideExpedited" <?php if($shipping_service=="UPSWorldWideExpedited"){echo 'selected="selected"';}?>>UPS Worldwide Expedited</option>
				<option value="UPSWorldWideExpress" <?php if($shipping_service=="UPSWorldWideExpress"){echo 'selected="selected"';}?>>UPS Worldwide Express</option>
				<option value="UPSWorldWideExpressBox10kg" <?php if($shipping_service=="UPSWorldWideExpressBox10kg"){echo 'selected="selected"';}?>>UPS Worldwide Express Box 10 Kg</option>
				<option value="UPSWorldWideExpressBox25kg" <?php if($shipping_service=="UPSWorldWideExpressBox25kg"){echo 'selected="selected"';}?>>UPS Worldwide Express Box 25 Kg</option>
				<option value="UPSWorldWideExpressPlus" <?php if($shipping_service=="UPSWorldWideExpressPlus"){echo 'selected="selected"';}?>>UPS Worldwide Express Plus</option>
				<option value="UPSWorldWideExpressPlusBox10kg" <?php if($shipping_service=="UPSWorldWideExpressPlusBox10kg"){echo 'selected="selected"';}?>>UPS Worldwide Express Plus Box 10 Kg</option>
				<option value="UPSWorldWideExpressPlusBox25kg" <?php if($shipping_service=="UPSWorldWideExpressPlusBox25kg"){echo 'selected="selected"';}?>>UPS Worldwide Express Plus box 25 Kg</option>
				<option value="UPSWorldwideSaver" <?php if($shipping_service=="UPSWorldwideSaver"){echo 'selected="selected"';}?>>UPS Worldwide Saver</option>
	  		  </select>	
		</div>
	</div>
            
			<label class="col-sm-2 control-label">Handling Time</label> 
			<?php
			if(empty($mfa['title']))
			{$handling_time = $mfa_user['handling_time'];}
			else {$handling_time = stripslashes($mfa['handling_time']);}
			?>
			<div class="col-sm-2">
			<div class="input-group">
					<input type="text" class="form-control" placeholder="3" name="handling_time" value="<?php echo $handling_time; ?>">
				<div class="input-group-addon">Days</div>
				</div>
			</div>
        </div>
              
		<div class="col-sm-12 box_ship">
			<label class="col-sm-2 control-label" id="shipcostlabel">Shipping Cost</label>	
			<?php
			if(empty($mfa['title']))
			{$shipping_cost = $mfa_user['shipping_cost'];}
			else {$shipping_cost = stripslashes($mfa['shipping_cost']);}
			?>
			<div class="col-sm-2">
			<div class="input-group" id="shipcost">
				<div class="input-group-addon">$</div>
					<input type="text" class="form-control" placeholder="0.00" name="shipping_cost" value="<?php echo $shipping_cost; ?>">
				</div>
			</div>
			<label class="col-sm-2 control-label" id="shipcostaddlabel">Shipping Cost Additional</label>
			<?php
			if(empty($mfa['title']))
			{$shipping_cost_additional = $mfa_user['shipping_cost_additional'];}
			else {$shipping_cost_additional = stripslashes($mfa['shipping_cost_additional']);}
			?>
			<div class="col-sm-2">
			<div class="input-group" id="shipcostadd">
				<div class="input-group-addon">$</div>
					<input type="text" class="form-control" placeholder="0.00" name="shipping_cost_additional" value="<?php echo $shipping_cost_additional; ?>">
				</div>
			</div>	
        </div>
        <div class="col-sm-12 calculated_values">
		<label class="col-sm-2 control-label">Item Weight</label> 
        	<div class="col-sm-2">
			<div class="input-group">
					<input type="text" class="form-control" placeholder="0" name="item_weight_lbs" value="<?php echo stripslashes($mfa['item_weight_lbs'])?>">
					<div class="input-group-addon">lbs</div>
			</div>
		</div>	

		<div class="col-sm-2">
			<div class="input-group">
					<input type="text" class="form-control" placeholder="0" name="item_weight_oz" value="<?php echo stripslashes($mfa['item_weight_oz'])?>">
					<div class="input-group-addon">oz</div>
			</div>
		</div>	
		        
		<label class="col-sm-2 control-label">Shipping Package</label> 
	    	<div class="col-sm-4">
			<div class="input-group">
			<?php
			$shipping_package = stripslashes($mfa['shipping_package']);
			?>
		                   <select class="form-control" name="shipping_package">
					<option value="PackageThickEnvelope" <?php if (($shipping_package=="PackageThickEnvelope") || (empty($shipping_package))){echo 'selected="selected"';}?>>Package/thick envelope</option>
					<option value="USPSFlatRateEnvelope" <?php if($shipping_package=="USPSFlatRateEnvelope"){echo 'selected="selected"';}?>>USPS Flat Rate Envelope</option>
					<option value="USPSLargePack" <?php if($shipping_package=="USPSLargePack"){echo 'selected="selected"';}?>>USPS Large Package/Oversize 1</option>
					<option value="VeryLargePack" <?php if($shipping_package=="VeryLargePack"){echo 'selected="selected"';}?>>Very Large Package/Oversize 2</option>
					<option value="UPSLetter" <?php if($shipping_package=="UPSLetter"){echo 'selected="selected"';}?>>UPS Letter</option>
					<option value="PaddedBags" <?php if($shipping_package=="PaddedBags"){echo 'selected="selected"';}?>>Padded Bags</option>
					<option value="ParcelOrPaddedEnvelope" <?php if($shipping_package=="ParcelOrPaddedEnvelope"){echo 'selected="selected"';}?>>Parcel or padded Envelope</option>
					<option value="BulkyGoods" <?php if($shipping_package=="BulkyGoods"){echo 'selected="selected"';}?>>Bulky goods</option>
					<option value="LargeEnvelope" <?php if($shipping_package=="LargeEnvelope"){echo 'selected="selected"';}?>>LargeEnvelope</option>
					<option value="Letter" <?php if($shipping_package=="Letter"){echo 'selected="selected"';}?>>Letter</option>
					<option value="MailingBoxes" <?php if($shipping_package=="MailingBoxes"){echo 'selected="selected"';}?>>Mailing Boxes</option>
					<option value="Caravan" <?php if($shipping_package=="Caravan"){echo 'selected="selected"';}?>>Caravan</option>
					<option value="Cars" <?php if($shipping_package=="Cars"){echo 'selected="selected"';}?>>Cars</option>
					<option value="Europallet" <?php if($shipping_package=="Europallet"){echo 'selected="selected"';}?>>Europallet</option>
					<option value="ExpandableToughBags" <?php if($shipping_package=="ExpandableToughBags"){echo 'selected="selected"';}?>>Expandable Tough Bags</option>
					<option value="ExtraLargePack" <?php if($shipping_package=="ExtraLargePack"){echo 'selected="selected"';}?>>Extra Large Package/Oversize 3</option>
					<option value="Furniture" <?php if($shipping_package=="Furniture"){echo 'selected="selected"';}?>>Furniture</option>
					<option value="IndustryVehicles" <?php if($shipping_package=="IndustryVehicles"){echo 'selected="selected"';}?>>Industry vehicles</option>
					<option value="LargeCanadaPostBox" <?php if($shipping_package=="LargeCanadaPostBox"){echo 'selected="selected"';}?>>Large Canada Post Box</option>
					<option value="LargeCanadaPostBubbleMailer" <?php if($shipping_package=="LargeCanadaPostBubbleMailer"){echo 'selected="selected"';}?>>Large Canada Post Bubble Mailer</option>
					<option value="MediumCanadaPostBox" <?php if($shipping_package=="MediumCanadaPostBox"){echo 'selected="selected"';}?>>Medium Canada Post Box</option>
					<option value="MediumCanadaPostBubbleMailer" <?php if($shipping_package=="MediumCanadaPostBubbleMailer"){echo 'selected="selected"';}?>>Medium Canada Post Bubble Mailer</option>
					<option value="Motorbikes" <?php if($shipping_package=="Motorbikes"){echo 'selected="selected"';}?>>Motorbikes</option>
					<option value="None" <?php if($shipping_package=="None"){echo 'selected="selected"';}?>>None</option>
					<option value="OneWayPallet" <?php if($shipping_package=="OneWayPallet"){echo 'selected="selected"';}?>>Onewaypallet</option>
					<option value="Roll" <?php if($shipping_package=="Roll"){echo 'selected="selected"';}?>>Roll</option>
					<option value="SmallCanadaPostBox" <?php if($shipping_package=="SmallCanadaPostBox"){echo 'selected="selected"';}?>>Small Canada Post Box</option>
					<option value="SmallCanadaPostBubbleMailer" <?php if($shipping_package=="SmallCanadaPostBubbleMailer"){echo 'selected="selected"';}?>>Small Canada Post Bubble Mailer</option>
					<option value="ToughBags" <?php if($shipping_package=="ToughBags"){echo 'selected="selected"';}?>>Tough Bags</option>
					<option value="Winepak" <?php if($shipping_package=="Winepak"){echo 'selected="selected"';}?>>Winepak</option>
		  		  </select>	
			</div>
		</div>
		</div>
		        <div class="form-group admin_values col-sm-12">
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-6">
              <select id="e2" class="populate" name="status" style="width: 300px">
				<?php 
				foreach($status_array as $key=>$value){
					$selected="";
					if(empty($mfa['title']) & ($key=="1")) { //uncompleted
						$selected="selected='selected'";
					}
					else if($key==$mfa['ui_status']){
						$selected="selected='selected'";
					} 
				?>
								<option <?php echo $selected?> value="<?php echo $key?>"><?php echo $value?></option>
					<?php } ?>
              </select>
              
		</div>
		<div class="col-sm-3" style="text-align:center;">
			<input type="hidden" name="publish" value="no">
			<input type="hidden" name="user_id" value="<?=$mfa['user_id'];?>">
			<button class="btn btn-primary button-submit" type="submit">Save Item</button>
			<a id="publish" class="btn btn-secondary bg-success" href="javascript:;">Save & Publish</a>
		</div>
		</div>
        </div>
	</div>
        </form>
    </section>
  </div>
</div>
<script type="text/javascript" src="../js/niceedit.js"></script>
  <script type="text/javascript">
  bkLib.onDomLoaded(function() {
        new nicEditor({fullPanel : true,maxHeight : 200}).panelInstance('description');
  });
$("#get_stats").click(function(){
var url 	= $("input:text[name=reference_url]").val();
var result 	= url.split('?');
var result1 	= result[0].split('itm/');
var result2 	= result1[1].split('/');

   $.ajax({
         url: "../../../../stoplisting.com/api/index.php",
         type: "GET",
         dataType: "json",
         data:"&get_item_data=yes&item_id="+result2[1],
         cache: false,
         success: function (data) {
		$("input:text[name=reference_url]").val(result2[1]).change();
		$('input:text[name=title]').val(data.Title).change();
		$('.nicEdit-main').html(data.Description).change();
		$('input:text[name=price]').val(data.Price).change();
		//$('#e1').val(data.CategoryId).change();
		$('#e1').prepend('<option value="'+ data.CategoryId +'" selected=\'selected\'> Auto Selected Category #:'+ data.CategoryId +'</option>').change();
		$("#e1 option:empty").remove();
		$.each(data.ItemSpecifics.NameValueList, function(i, item) {	
			var num_delim = $('.specific_values').length;
			
			$('.specific_names_row').append('<input type="text" class="form-control specific_names" name="item_specific_name[]" id="name_'+ num_delim +'" placeholder="Specs Name" value="'+ item.Name +'"/><br/>');
			$('.specific_values_row').append('<input type="text" class="form-control specific_values" name="item_specific_value[]" id="value_'+ num_delim +'" placeholder="Value" value="'+ item.Value +'"/><br/>');
			$('#specifics_row').append('<br/><a href="javascript:;" class="col-sm-1 remove_spec" id="sub_'+ num_delim +'">&minus;</a>');
		});
        }
     });
});

$("#more_specs").click(function(){
	var num_delim = $('.specific_values').length;
	if(num_delim === 0 ) {
	$('#specifics_row br').remove();
	}
	$('.specific_names_row').append('<input type="text" class="form-control specific_names" name="item_specific_name[]" id="name_'+ num_delim +'" placeholder="Specs Name" value=""/><br/>');
	$('.specific_values_row').append('<input type="text" class="form-control specific_values" name="item_specific_value[]" id="value_'+ num_delim +'" placeholder="Value" value=""/><br/>');
	$('#specifics_row').append('<br/><a href="javascript:;" class="col-sm-1 remove_spec" id="sub_'+ num_delim +'">&minus;</a>');
});
$(document).on('click', '.remove_spec', function () {
	var classname 	= $(this).attr('id');
	var delete_row 	= classname.split('_');
	$("#value_" + delete_row[1]).next("br").remove();
	$("#value_" + delete_row[1]).remove();
	$("#name_" + delete_row[1]).next("br").remove();
	
	$("#name_" + delete_row[1]).remove();
	$("#sub_" + delete_row[1]).next("br").remove();
	
	$("#sub_" + delete_row[1]).remove();
});

$(document).on('click', '.modal_change', function () {
	var idname 	= $(this).attr('id');
	var change_row 	= idname.split('_');
	if (change_row[0] === "left") {
		var num = parseInt(change_row[2]) - 1; 
	}
	if (change_row[0] === "right") {
		var num = parseInt(change_row[2]) + 1; 	
	}
	if ($("#myModallink"+num).length > 0) {
		$("div.modal-backdrop *, div.modal-backdrop, #myModal"+ parseInt(change_row[2])).hide();
		$("#myModallink"+num)[0].click();
	} else {$("div.modal-backdrop").hide();
		$(this).closest('.modal').hide();
		}
});

$(document).on('click', '.delete_image', function () {
	$(this).parent().parent().remove();
});

$(document).on('click', '#delete_multi_images', function () {
$("input:checkbox[name='image_change\\[\\]']:checked").each(function () { 
	$(this).parent().parent().remove();
});
});

$(document).on('click', '#move_old_delete_selected', function () {
var ui_image ="";
$("input:checkbox[name='image_change\\[\\]']:checked").each(function () { 
	ui_image += $(this).parent().parent().find("input:hidden[name='image_order\\[\\]']").val() + "-";
	$(this).parent().parent().remove();
	
});
ui_image = ui_image.substring(0, ui_image.length - 1);
window.open("http://agapeworks.x10.mx/dash/stoplisting/admin/index.php?p=edit-listing&parm=0&parm2=<? echo $mfa['user_id'];?>&ui_image=" + ui_image);

});

$(document).on('click', '.rotate', function () {
	var ui_image = "";
	var deg = 0;
	var rotation = $(this).attr('id');
	
var rotations 	= rotation.split('_');
var rotation 	= rotations[1];
	$("input:checkbox[name='image_change\\[\\]']:checked").each(function () { 
		ui_image += $(this).parent().parent().find("input:hidden[name='image_order\\[\\]']").val() + "-";
	});
	ui_image = ui_image.substring(0, ui_image.length - 1);
	if (rotation == "left") {
		deg = 90;
	} else if (rotation == "right") {
		deg = 270;
	} else if (rotation == "flip") {
		deg = 180;
	}

$.ajax({
         url: "../../../../dash/stoplisting/admin/image-resizer-master/rotateImage.php",
         type: "GET",
         dataType: "html",
         data:"&image="+ ui_image +"&degree="+ deg,
         cache: false,         
       });
       var img = "";
       var img_src = "";
       var img_derand = "";
	$("input:checkbox[name='image_change\\[\\]']:checked").each(function () { 
	img = $(this).parent().parent().find("img");
	img_src = img.attr('src');
	img_derand = img_src.split('&rand=');
        img.attr('src', img_derand[0]+'&rand='+Math.random());
       });
});		
	
	

$(document).on('click', '.rotate_single', function () {
	var ui_image = $(this).parent().parent().find("input:hidden[name='image_order\\[\\]']").val();
	var deg = 0;
	var rotation = $(this).attr('id');
	
var rotations 	= rotation.split('_');
var rotation 	= rotations[1];
	if (rotation == "left") {
		deg = 90;
	} else if (rotation == "right") {
		deg = 270;
	} 

$.ajax({
         url: "../../../../dash/stoplisting/admin/image-resizer-master/rotateImage.php",
         type: "GET",
         dataType: "html",
         data:"&image="+ ui_image +"&degree="+ deg,
         cache: false,         
       });
	
	var img = $(this).parent().parent().find("img");
   	var img_src = img.attr('src');
	var img_derand = img_src.split('&rand=');
        img.attr('src', img_derand[0]+'&rand='+Math.random());
});			
			
$(document).on('click', '#publish', function () {
	$('input:hidden[name=publish]').val("yes").change();
	var publish = $('input:hidden[name=publish]').val();
        $(".button-submit").click();
});

$(document).on('click', '.order_image', function () {
	var idname 	= $(this).attr('id');
	var change_row  = idname.split('_');
	if (change_row[0] === "left") {
       	 	$(this).parent().parent().insertBefore($(this).parent().parent().prev());  
	}
	if (change_row[0] === "right") {
        	$(this).parent().parent().insertAfter($(this).parent().parent().next());  
	}
});
$(document).ready(function() {
	
       var quantity = parseInt($('#quantity_item').val(), 10);
       if (quantity > 1) {
        	$('#shipcostadd,#shipcostaddlabel').show();
       } else {$('#shipcostadd,#shipcostaddlabel').hide();}
       
       var shiptype = $('select#ship_type :selected').text();
       if ((shiptype === "Flat") || (shiptype === "FreightFlat"))  {
        $('.calculated_values').hide();
        $('#shipcost,#shipcostlabel').show();
       } else {
        $('.calculated_values').show();
        $('#shipcost,#shipcostlabel').hide();
       }  
       
       $('#quantity_item').keyup(function () {
       var quantity = parseInt($('#quantity_item').val(), 10);
       if (quantity > 1) {
        	$('#shipcostadd,#shipcostaddlabel').show();
       } else {$('#shipcostadd,#shipcostaddlabel').hide();}
       });
       
    $("select#ship_type").change(function(){
        
       var shiptype = $('select#ship_type :selected').text();
       if ((shiptype === "Flat") || (shiptype === "FreightFlat"))  {
        $('.calculated_values').hide();
        $('#shipcost,#shipcostlabel').show();
       } else {
        $('.calculated_values').show();
        $('#shipcost,#shipcostlabel').hide();
       }       
    });
});
</script>
<style>
	input, textarea {color: #111 !important;}
	.remove_spec {font-size: 37px !important;font-weight:bold;color: red;text-align:right !important;width: 100%; padding:0 !important;}
	.exceeds {color:red !important;font-weight:bold;}
	.image_wrapper {display:inline-block !important;padding: 10px 0px;margin-right:10px;}
	.image_wrapper img {border: 1px solid rgba(0,0,0,0.4) !important; display:block !important;width:100%;}
	.image_header {border:1px solid rgba(0,0,0,0.2);background:rgba(0,0,0,0.04); }
	.image_header a {font-size:17px !important;padding:7px; color:rgba(0,0,0,0.7);}
	.image_header a:hover {border-right:1px solid rgba(55,66,77,0.2);border-left:1px solid rgba(55,66,77,0.2);background:rgba(55,66,77,0.1);padding:1px  6px !important;}
	.image_header a:last-child:hover {color:rgba(220,0,0,0.7);}
	#title_count {font-size:14px;}
	<?php if(!empty($mfa['title'])) {?>
	.fa-chevron-right {float:right !important;font-size:22px !important;margin-top:-23px;color:#776;}
	.fa-chevron-left {float:left;font-size:22px;padding-top:28px;padding-right:7px;color:#776;}
	<?php } else { ?>
	.fa-chevron-right {float:right;font-size:22px;color:#776;}
	.fa-chevron-left {float:left;font-size:22px;color:#776;}
	.modal-header .close,.fa-chevron-left,.fa-chevron-right {margin-top:-9px;margin-left:7px;}
	<?php } ?>
	.box_ship, .submit_box {margin: 25px 0px;}
	.calculated_values {margin-bottom:40px;}
	select {color:#444 !important;}
	#get_stats {
	background: none repeat scroll 0% 0% #FBA900 !important;
	padding: 10px;
	left: 10px;
	border-radius: 2px;
	text-shadow: 1px 1px 2px rgba(43, 43, 43, 0.55);
	}
	input.col-lg-offset-2 {
	padding: 6px;
	}
	.image_header .fa { font-size:1.2em;}
	div.user_preferences {
	border: 1px solid rgba(0,0,0,0.15);
	background: rgba(0,0,0,0.025);
	margin-left:25px;
	width:95.7%;
	min-height:90px;
	border-radius:5px;
	}
	.image_header input[type=checkbox] {width:30px;}
	
	/* Photo Menu */
	.photo_menu {background: rgba(25,25,25,0.05); margin-top:10px;padding-left:15px;max-width:150px;margin-left:12px;padding-bottom: 10px;}
	.photo_menu a {display:block;margin:10px 0px;line-height:25px;}
	.photo_menu label {font-style:italic;color:rgba(0,0,0,0.7);}
	.photo_menu a:nth-child(1n) {background:rgba(85,85,85,1);color:rgba(255,255,255,1);padding-left:6px;}
	.photo_menu a:nth-child(2n) {background:rgba(182,71,0,1);color:rgba(255,255,255,1);padding-left:6px;}
	.photo_menu a:nth-child(3n+1) {background:rgba(0,124,140,1);color:rgba(255,255,255,1);padding-left:6px;}
	.photo_menu a:nth-child(4n) {background:rgba(154,163,0,1);color:rgba(255,255,255,1);padding-left:6px;}
	.photo_menu a:last-child {background:rgba(43,43,43,1);color:rgba(255,255,255,1);padding-left:6px;}
	
	.photo_menu a:nth-child(1n):hover,.photo_menu a:nth-child(2n):hover,	.photo_menu a:nth-child(3n+1):hover,.photo_menu a:nth-child(4n):hover {box-shadow: 1px 1px 3px rgba(0,0,0,0.5);}
	#add_photo .fa-plus {font-size: 20px; display:block;background:#eee;padding:2px;width:22px;border:1px solid rgba(0,0,0,0.2);}

</style>
