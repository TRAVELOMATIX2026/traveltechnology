<?php
$template_images = $GLOBALS['CI']->template->template_images();
$mini_loading_image = '<div class="text-center loader-image">Please Wait <img src="'.$template_images.'loader_v3.gif" alt="Loading........"/></div>';
foreach ($raw_sightseeing_list['SSSearchResult']['SightSeeingResults'] as $product_key=>$product) {

	//debug($product);

		$current_hotel_rate = $product['StarRating'];
?>
<div class="rowresult r-r-i col-4">
	<div class="madgrid forhtlpopover" data-key="<?=$product_key?>" data-product-name="<?=$product['ProductName']?>" data-product-code="<?=@$product['ProductCode']?>" data-result-token="<?=$product['ResultToken']?>">
		<?php
			
		   if($product['Promotion']){
		   		$d_offer = 'discount_offer';
		   }else{
		   		$d_offer ='';
		   }
		?>

		<div class="col-4 nopad listimage">
			<div class="imagehtldis">
				
				<?php if($product['ImageHisUrl']):?>

					<a href="<?php echo base_url()?>index.php/sightseeing/sightseeing_details?op=get_details&booking_source=<?=urlencode($booking_source)?>&search_id=<?=$search_id?>&product_code=<?=@$product['ProductCode']?>&result_token=<?=$product['ResultToken']?>"><img src="<?=$product['ImageHisUrl']?>" alt="Image" data-src="<?=$product['ImageHisUrl']?>" title="<?=$product['ProductName']?>" class="lazy h-img"><img class="spcl_offr <?=$d_offer?>" src="<?php echo $GLOBALS['CI']->template->template_images('special_offer.png'); ?>" /></a>				
				<?php else:?>				 		
				 <a href="<?php echo base_url()?>index.php/sightseeing/sightseeing_details?op=get_details&booking_source=<?=urlencode($booking_source)?>&search_id=<?=$search_id?>&product_code=<?=@$product['ProductCode']?>&result_token=<?=$product['ResultToken']?>"><img src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>" alt="Image" data-src="<?=$product['ImageUrl']?>" title="<?=$product['ProductName']?>" class="lazy h-img">
				 
				 <img class="spcl_offr <?=$d_offer?>" src="<?php echo $GLOBALS['CI']->template->template_images('special_offer.png'); ?>" />

				 </a>	

				<?php endif;?>
				
			</div>
		</div>
		<div class="col-8 nopad listfull">
			<div class="sidenamedesc">
				<div class="celhtl width70">
					<div class="innd acttbosrch">
						<div class="property-type" data-property-type="hotel"></div>
						<div class="shtlnamehotl">
							<span class="h-name"><a  href="<?php echo base_url()?>index.php/sightseeing/sightseeing_details?op=get_details&booking_source=<?=urlencode($booking_source)?>&search_id=<?=$search_id?>&product_code=<?=@$product['ProductCode']?>&result_token=<?=$product['ResultToken']?>"><?php echo $product['ProductName']?></a></span>
							<?php
								$filter_str = implode(",",$product['Cat_Ids']);
							?>
							<span class="activity-cate hide"><?=$filter_str?></span>
							
						</div>
						<div class="clearfix"></div>
						<div class="strrat">
							
							<?php
											
						    	$current_hotel_rate = round($product['StarRating']);

							?>
							<ul class="std">
								<?php if(empty($current_hotel_rate) == false){ ?>
								<li class="starrtinghotl rating-no">
										<span class="h-sr hide"><?php echo $current_hotel_rate?></span>
										<?php echo print_star_rating($current_hotel_rate);?>
								</li>
							<?php } ?>
							 <?php 
							 //$product['reviewCount'] = 5;
							 if(!empty($product['ReviewCount']))
							 {
							 ?>
							 <li><span class="review"><?=$product['ReviewCount']?> Reviews</span></li>
							 
							 <?php } ?>
							
							</ul>
					    </div>
					    <?php if(valid_array($product['Recommended_For_Ids'])){?>
							<div class="strrat">
							 	<ul class="std">
							 	<?php foreach($product['Recommended_For_Ids'] as $recommed){?>
								<li><span class="adreshotle"><b><?=$recommed.', ';?></b></span></i></li>
							 
							 	<?php } ?>
								</ul>
							</div>
						<?php } ?>
						<div class="desc hide">
							<p><?=$product['Description']?></p>
						</div>
						<div class="adreshotle h-adr">
						 
						 <p><i class="fal fa-map-marker-alt"></i><?php echo $product['DestinationName']?></p>
						</div>
						<div class="clearfix"></div>
						<?php if($product['Duration'] && empty(trim($product['Duration'])) == false):?>
                        <div class="col-md-12 col-12 nopad">
						   <div class="loc_see">
								<span>Duration: <?php echo $product['Duration']?></span>
							</div>
						</div>
						<?php endif;?>

						<a  class="detailsflt iti-btn non-clickable" data-bs-toggle="modal" data-bs-target="#sendmail_multi_<?=$product_key?>" data-backdrop="static"><span class="fal fa-envelope"></span> Send Mail</a>
						<div class="loc_see refund">
						<?php if($product['Cancellation_available']==1):?>
							<span>Refundable</span>
						<?php else:?>
							<span>Non-Refundable</span>
						<?php endif;?>
						</div>
					</div>
				</div>
				<?php
				
					$search_id = intval($attr['search_id']);
					$ProductPrice= $product['Price']['TotalDisplayFare'];
					$NetFare = $product['Price']['NetFare'];
					$agent_commission = $product['Price']['_Commission'];
					$tds_oncommission = $product['Price']['_tdsCommission'];
					$agent_earning = $product['Price']['_AgentEarning'];
					$agent_markup = $product['Price']['_Markup'];
					?>
				<div class="celhtl width30">
					<div class="sidepricewrp">					
						<div class="priceflights">
							<div class="prcstrtingt">starting @ </div>
							<strong class="hide"> <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> </strong>
							<strong class="currency_symbol"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></strong>
							<span class="h-p"><?php echo $ProductPrice; ?></span>
							<?php //echo generateCurrencyOptions($ProductPrice, $currencyList)?>
							<div style="display:none" class="net-fare-tag snf_hnf" title="C <?=$agent_commission-$tds_oncommission?>+M <?=$agent_markup?> =<?=$agent_earning?> "><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> <?=$NetFare?></div>

							<?php							
							$offer = 0;
							   if($product['Promotion']){
							   		$offer = $product['Promotion'];
							   }
							   
							?>
							<p class="special-offer hide"><?=$offer?></p>
							<div class="clearfix"></div>
							<?php if($product['PromotionAmount']):?>
								<span class="saving-amount">Save <span>
								<?=$product['PromotionAmount']?></span></span>
							<?php endif;?>
							
						</div>
                        
                        <div class="snf_hnf hide">
							<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> 
                        	<?php echo $ProductPrice; ?>
                        	<input type="hidden" class="category_list" value="<?php echo $product['category_cstr']?>">
                        	<input type="hidden" class="duration_list" value="<?php if(isset($product['duration_cstr'])){ echo $product['duration_cstr'];} else { echo "";}?>">
                        	<input type="hidden" class="recommendation_list" value="<?php if(isset($product['recommendation_cstr'])){ echo $product['recommendation_cstr'];} else { echo "";}?>">
                        	
                        </div>
                        
						<form method="GET" action="<?php echo base_url().'index.php/sightseeing/sightseeing_details'?>">
							<div class="hide">
								<input type="hidden" value="get_details"									name="op" class="operation">						
								<input type="hidden" value="<?=urlencode($booking_source)?>"				name="booking_source" class="booking_source">	
								<input type="hidden" name="search_id" value="<?=$search_id?>">					
								<input type="hidden" name="result_token" value="<?=$product['ResultToken']?>">		
								<input type="hidden" name="product_code" value="<?=@$product['ProductCode']?>">
							</div>							
							<button class="confirmBTN b-btn bookallbtn plhotltoy" type="submit">Check Dates</button>							
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<div id="sendmail_multi_<?=$product_key?>" class="modal fade" role="dialog" data-id="<?=$product_key?>">
   <div class="modal-dialog" style="margin: 200px auto;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-bs-dismiss="modal" id="close_modal_multi<?=$product_key?>">&times;</button>
            <h4 class="modal-title mdltitl">Send Activities Information</h4>
         </div>
         <div class="modal-body">
            <div class="form-group">
               <input type="email" class="form-control mfc" id="email_multi_<?=$product_key?>" placeholder="Enter Email ID" name="email" /><input type="hidden" class="form-control mfc" id="sightseeingdetails_multi_<?=$product_key?>" placeholder="Enter email" name="email" value="<?= serialized_data($product)?>" /> <span id="errormsg_multi_<?=$product_key?>"></span> 
               <div id="send_email_loading_image_multi_<?=$product_key?>" style="display: none;">
                  <div class="text-center loader-image" style="display: none;">
                    <img src="<?=$GLOBALS['CI']->template->template_images('loader_v3.gif');?>" alt="please wait" /></div>
               </div>
            </div>
            <button type="button" id="send_email_btn_not_multi<?=$product_key?>" class="btn btn-secondary flteml" onclick="sendSightseeingdetails_multi('<?=$product_key?>')">Send</button> 
         </div>
      </div>
   </div>
</div>

<?php
}//foreach end
?>
<script type="text/javascript">
	$(function(){
		$('[data-bs-toggle="tooltip"]').tooltip(); 
	})

	// Safest way
$(document).on('click', '.non-clickable', function (e) {
    e.stopPropagation(); 
});

</script>