<?php
$template_images = $GLOBALS['CI']->template->template_images();
$mini_loading_image = '<div class="text-center loader-image">Please Wait <img src="'.$template_images.'loader_v3.gif" alt="Loading........"/></div>';
if($search_params['trip_type'] == "circle"){
	$search_params['trip_type'] = "Roundtrip";
}
foreach ($raw_transfer_list['TransferSearchResult']['TransferResults'] as $product_key=>$product) {

	//debug($product);exit;
?>
<div class="rowresult r-r-i col-4">
	<div class="madgrid forhtlpopover " data-key="<?=$product_key?>" data-product-name="<?=$product['transferType']?>" data-product-code="<?=@$product['categoryCode']?>" data-result-token="<?=$product['ResultToken']?>" >

		<div class="col-3 nopad listimage">
			<div class="imagehtldis">
				
				<?php if($product['image']):?>

					<img src="<?=$product['image']?>" alt="Image" data-src="<?=$product['image']?>" title="<?=$product['transferType']?>" class="lazy h-img">				
				<?php else:?>				 		
				

				 	<img src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>" alt="Image" data-src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>" title="<?=$product['transferType']?>" class="lazy h-img">
				 
				 

				 	

				<?php endif;?>
				
			</div>
		</div>
		<div class="col-9 nopad listfull">
			<div class="sidenamedesc">
				<div class="celhtl width70">
					<div class="innd acttbosrch">
						<div class="property-type" data-property-type="hotel"></div>
						<div class="shtlnamehotl">
							<span class="h-name"><a  href="<?php echo base_url()?>index.php/transfer/booking?op=get_details&booking_source=<?=urlencode($booking_source)?>&search_id=<?=$search_id?>&product_code=<?=@$product['categoryCode']?>&result_token=<?=$product['ResultToken']?>"><?php echo $product['transferType']?> - <?php echo $product['categoryName']?> - <?php echo $product['vehicleName']?></a></span>
							
							
							
						</div>
						<div class="clearfix"></div>
						
						<div class="adreshotle h-adr">
						 
						 <p><i class="fal fa-map-marker-alt"></i><?php echo $product['pickupFrom']?></p>
						</div>
						<div class="clearfix"></div>
	                    <?php if($product['estimatedTime']):?>
							<div class="adreshotle h-adr">
					   			<span><i class="fal fa-clock"></i><?=$product['estimatedTime']?></span>
							</div>
						<?php endif;?>
						<div class="clearfix"></div>
						<?php if($product['minPaxCapacity']){?>
							<div class="adreshotle h-adr">
							<span><i class="fal fa-user"></i><?php echo $product['minPaxCapacity'];?> passenger(s) minimum
							</span>
						</div>
						<?php } ?>
						<div class="clearfix"></div>
						<?php if($product['maxPaxCapacity']){?>
							<div class="adreshotle h-adr">
							<span><i class="fal fa-user"></i><?php echo $product['maxPaxCapacity'];?> passenger(s) maximum</span>
							</div>
						<?php } ?>
						
						<div class="clearfix"></div>
						<?php if($product['permittedSuitcases']){?>
							<div class="adreshotle h-adr">
							<span><i class="fal fa-suitcase"></i><?php echo $product['permittedSuitcases'];?></span>
							</div>
						<?php } ?>
						<div class="clearfix"></div>
						<div class="adreshotle h-adr">
						<?php if($product['cancellationAvailable']==1):?>
							<span><i class="far fa-money-bill-alt"></i></i>Refundable</span>
						<?php else:?>
							<span><i class="far fa-money-bill-alt"></i>Non-Refundable</span>
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
					<input type="hidden" class="category_code" value="<?php echo $product['categoryCode']?>" />
					<input type="hidden" class="vehicle_code" value="<?php echo $product['vehicleCode']?>" />
					<input type="hidden" class="suitcase" value="<?php echo $product['permittedSuitcases']?>" />
					
				<div class="celhtl width30">
					<div class="sidepricewrp">					
						<div class="priceflights">
							
							<strong class="hide"> <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> </strong>
							<strong class="currency_symbol"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></strong>
							<span class="h-p"><?php echo $ProductPrice; ?></span>
							<div style="display:none" class="net-fare-tag snf_hnf" title="C <?=$agent_commission-$tds_oncommission?>+M <?=$agent_markup?> =<?=$agent_earning?> " ><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> <?=$NetFare?></div>
							<div class="prcstrtingt"><?php echo ucfirst($search_params['trip_type']);?> price, for all travellers </div>
							
							<div class="clearfix"></div>
							
							
						</div>
                        
                        <div class="snf_hnf hide">
							<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> 
                        	<?php echo $ProductPrice; ?>
                        	
                        </div>
                          <div class="snf_hnf hide">
							<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> 
                        	<?php echo $ProductPrice; ?>
                        	
                        </div>
						<form method="post" action="<?php echo base_url().'index.php/transfer/booking'?>">
							<div class="hide">
								<input type="hidden" value="booking" name="op" class="operation">			
								<input type="hidden" value="<?=urlencode($booking_source)?>"				name="booking_source" class="booking_source">	
								<input type="hidden" name="search_id" value="<?=$search_id?>">					
								<input type="hidden" name="result_token" value="<?=$product['ResultToken']?>">		
								
							</div>							
							<button class="confirmBTN b-btn bookallbtn plhotltoy" type="submit">Book</button>							
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<?php
	
}//foreach end
?>
<script type="text/javascript">
	$(function(){
		$('[data-bs-toggle="tooltip"]').tooltip(); 
	})
</script>