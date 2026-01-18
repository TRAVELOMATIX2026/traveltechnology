<link
	href="<?php echo $GLOBALS['CI']->template->template_css_dir('page_resource/tour-result.css') ?>"
	rel="stylesheet">
<link
	href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_sky.css') ?>"
	rel="stylesheet">
<style>
	.tourfilter {
		border:none;
		overflow:visible;
	}
	#holiday_search .selectedwrap::after {
		background: none;
	}
	.flightbutton {
		position: relative;
		bottom: -8px;
	}
</style>
<div class="full witcontent  marintopcnt">
	<div class="col-12">
		<div class="container-fluid offset-0">
			<div class="cnclpoly">
			<div class="col-md-3 col-12 nopad">
				<h1 id="contentTitle" class="h3">Tours And Packages</h1>
				<div class="clear"></div>
				<div class="tourfilter">
					<form action="<?php echo base_url().'index.php/tours/search'?>"
						autocomplete="off" id="holiday_search">
						<div class="tabspl forhotelonly">
							<div class="tabrow">
								<div class="col-md-12 col-sm-6 col-6 mobile_width padfive">
									<div class="lbl_txt">Country</div>
									<select class="normalsel padselct arimo" id="country"
										name="country">
										<option value="">All</option>
					<?php 
					if(isset($holiday_data['countries'])){
						$country_data = $holiday_data['countries'];
					}else{
						$country_data = $countries;
					}
					if(!empty($country_data)){ ?>
					<?php foreach ($country_data as $country) { ?>
					<option value="<?php echo $country->package_country; ?>"
											<?php if(isset($scountry)){ if($scountry == $country->package_country) echo "selected"; }?>><?php echo $country_name = $this->Package_Model->getCountryName($country->package_country)->name; ?></option>
					<?php } } ?>
				</select>
								</div>
								<div class="col-md-12 col-sm-6 col-6 mobile_width padfive">
									<div class="lbl_txt">Package Type</div>
									<select class="normalsel padselct arimo" id="package_type"
										name="package_type">
										<option value="">All Package Types</option>
					<?php 
					if(isset($holiday_data['package_types'])){
						$holiday = $holiday_data['package_types'];
					}else{
						$holiday = $package_types;
					}
					if(!empty($holiday)){ ?>
					<?php foreach ($holiday as $package_type) { ?>
					<option value="<?php echo $package_type->package_types_id; ?>"
											<?php if(isset($spackage_type)){ if($spackage_type == $package_type->package_types_id) echo "selected"; } ?>><?php echo $package_type->package_types_name; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
								</div>
								<div class="col-md-12 col-sm-6 col-6 mobile_width padfive">
									<div class="lbl_txt">Duration</div>
									<select class="normalsel padselct arimo" id="duration"
										name="duration">
										<option value="">All Durations</option>
										<option value="1-3"
											<?php if(isset($sduration)){ if($sduration == '1-3') echo "selected"; } ?>>1-3</option>
										<option value="4-7"
											<?php if(isset($sduration)){ if($sduration == '4-7') echo "selected"; } ?>>4-7</option>
										<option value="8-12"
											<?php if(isset($sduration)){ if($sduration == '8-12') echo "selected"; } ?>>8-12</option>
										<option value="12"
											<?php if(isset($sduration)){ if($sduration == '12') echo "selected"; } ?>>12</option>
									</select>
								</div>
								<div class="col-md-12 col-sm-6 col-6 mobile_width padfive">
									<div class="lbl_txt">Budget</div>
									<select class="normalsel padselct arimo" id="budget"
										name="budget">
										<option value="">All</option>
										<option value="100-500"
											<?php if(isset($sbudget)){ if($sbudget == '100-500') echo "selected"; } ?>>100-500</option>
										<option value="500-1000"
											<?php if(isset($sbudget)){ if($sbudget == '500-1000') echo "selected"; } ?>>500-1000</option>
										<option value="1000-5000"
											<?php if(isset($sbudget)){ if($sbudget == '1000-5000') echo "selected"; } ?>>1000-5000</option>
										<option value="5000"
											<?php if(isset($sbudget)){ if($sbudget == '5000') echo "selected"; } ?>>5000</option>
									</select>
								</div>
								<div class="col-md-12 col-12 nopad">
									<div class="searchsbmtfot searchsbmtfot flightbutton holidyasBtn">
										<input type="submit" class="searchsbmt flight_search_btn" value="search" />
									</div>
								</div>
							</div>
						</div>

					</form>
				</div>
				</div>
				<div class="col-md-9 col-12 nopad">
				<div id="packgtr" class="packgtr">
					<ul id="container" class="row"> 
          <?php if(!empty($packages)){ ?>
          <?php foreach($packages as $pack){?>
          <?php $country_name = $this->Package_Model->getCountryName($pack->package_country); ?>
            <li class="col-md-12 col-12 nopadMob nopad">
							<div class="inlitp">
								<div class="tpimage col-sm-3 col-3 mobile_width nopad">
									<img
										src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images(basename($pack->image)); ?>"
										alt="<?php echo $pack->package_name; ?>" />
								</div>
								<div class="tpcontent col-sm-7 col-7 mobile_widthontent">
									<h3 class="tptitle txtwrapRow"><?php echo $pack->package_name; ?> </h3>
									<div class="htladrsxl"><?php echo $country_name->name; ?> | <?php echo $pack->package_city; ?>  </div>
									<div class="clear"></div>
									<p> <?php echo substr($pack->package_description, 0,300); ?></p>
								</div>
								<?php $price = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $pack->price ) ); ?>
								<div class="pkprice col-sm-2 col-2 mobile_width">
									<div class="pricebolk">	<strong> <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> </strong> <?php echo $price;?></div>
									<?php //generateCurrencyOptions($price, $currencyList)?>
									<div class="durtio"><?php echo (intval($pack->duration)-1); ?> Nights / <?php echo intval($pack->duration); ?> Days</div>
								
								<div class="clear"></div>

								<a class="detailsflt iti-btn" data-bs-toggle="modal" data-bs-target="#sendmail_multi_<?= $pack->package_id?>" data-backdrop="static"><span class="fal fa-envelope"></span> Send Mail</a>

								<a class="relativefmsub trssxl"
									href="<?php echo base_url(); ?>index.php/tours/details/<?php echo $pack->package_id; ?>">
									<span class="sfitlblx">View Detail</span> <span
									class="srcharowx"></span>
								</a></div>
							</div>
						</li>

						<div id="sendmail_multi_<?=$pack->package_id?>" class="modal fade" role="dialog" data-id="<?=$pack->package_id?>">
			   <div class="modal-dialog" style="margin: 200px auto;">
			      <div class="modal-content">
			         <div class="modal-header">
			            <button type="button" class="close" data-bs-dismiss="modal" id="close_modal_multi<?=$pack->package_id?>">&times;</button>
			            <h4 class="modal-title mdltitl">Send Packages Information</h4>
			         </div>
			         <div class="modal-body">
			            <div class="form-group">
			               <input type="email" class="form-control mfc" id="email_multi_<?=$pack->package_id?>" placeholder="Enter Email ID" name="email" /><input type="hidden" class="form-control mfc" id="tourdetails_multi_<?=$pack->package_id?>" placeholder="Enter email" name="email" value="<?= base64_encode(json_encode($pack))?>" /> 
			               <span id="errormsg_multi_<?=$pack->package_id?>"></span> 
			               <div id="send_email_loading_image_multi_<?=$pack->package_id?>" style="display: none;">
			                  <div class="text-center loader-image" style="display: none;">
			                    <img src="<?=$GLOBALS['CI']->template->template_images('loader_v3.gif');?>" alt="please wait" /></div>
			               </div>
			            </div>
			            <button type="button" id="send_email_btn_not_multi<?=$pack->package_id?>" class="btn btn-secondary flteml" onclick="sendtourdetails_multi('<?=$pack->package_id?>')">Send</button> 
			         </div>
			      </div>
			   </div>
			</div>



            <?php }?>
            <?php }else{?>
             <li class="tpli cenful">
							<div class="inlitp">
								<div class="tpimagexl">
									<img
										src="<?php echo $GLOBALS['CI']->template->template_images('no_result.png'); ?>"
										alt="No Packages Found" />
								</div>
								<div class="tpcontent">
									<h3 class="tptitle center">No Packages Found</h3>
								</div>
								<a class="relativefmsub trssxl"
									href="<?php echo base_url(); ?>index.php/tours/search"> <span
									class="sfitlblx">Reset Filters</span> <span class="srcharowx"></span>
								</a>

							</div>
						</li>
            <?php }?>
          </ul>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('jquery.masonry.min.js'), 'defer' => 'defer');?>

<script type="text/javascript">
$(document).ready(function(){
	var $container = $('#packgtr');
	$container.imagesLoaded( function() {
		$container.masonry({itemSelector:        '.tpli'});
	});
	updateFilters();
});

$(window).resize(function(){
	var $container = $('#packgtr');
	$container.imagesLoaded( function() {
		$container.masonry({itemSelector:        '.tpli'});
	});
});function updateFilters()
{
	var country_list = {};
	var temp_country = '';
	var temp_city = '';
	var temp_maxDuration = '';
	var temp_maxPrice = '';
	var minDuration = 1;
	var maxDuration = 30;
	var minPrice = 1;
	var maxPrice = 99999;

	$('.active_view').each(function(key, value) { 
		temp_country = $('.defaultCountryValue', this).text();
		temp_city = $('.defaultCityValue', this).text();
		temp_maxDuration = parseInt($('.defaultDurationValue', this).text());
		temp_maxPrice = parseFloat($('.defaultPriceValue', this).text())
		if(country_list.hasOwnProperty(temp_country) == false){country_list[temp_country] = temp_country}
		if(city_list.hasOwnProperty(temp_city) == false){city_list[temp_city] = temp_city}			
		if(maxDuration < temp_maxDuration){maxDuration = temp_maxDuration}
		if(maxPrice < temp_maxPrice){maxPrice = temp_maxPrice}
	});
	cityList = getSortedObject(city_list);
	countryList = getSortedObject(country_list);
	loadCityFilter(cityList);
	loadCountryFilter(countryList);
	loadDuration(minDuration,maxDuration);
	loadPrice(minPrice,maxPrice);
}
//sorting cities
function getSortedObject(obj)
{
	var objValArray = getArray(obj);
	var sortObj = {};
	objValArray.sort();
	$.each(objValArray, function(obj_key, obj_val) {
		$.each(obj, function(i_k, i_v) {
		    if (i_v == obj_val) {
		    	sortObj[i_k] = i_v;
			}
		});
	});
	return sortObj;
}
function getArray(objectWrap)
{
	var objectWrapValueArr = [];
	$.each(objectWrap, function(key, value) {
		objectWrapValueArr.push(value);
	});
	return objectWrapValueArr;
}

window.sendtourdetails_multi = function(data){
	//  alert(data);

	$("#errormsg_multi_"+data).show();
	$("#errormsg_multi_"+data).text('');
	var input_text=$("#email_multi_"+data).val();
	var packagedetails=$("#tourdetails_multi_"+data).val();

	var mailformat =/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(input_text);
	// var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	
	if(!mailformat)
	{
		$("#errormsg_multi_"+data).css({"color": "red"});
		$("#errormsg_multi_"+data).text("Enter Valid email address!");
	   
	   return false;
	}

		$('#send_email_loading_image_multi_'+data).show();
		$('.loader-image').show();
		$(document.body).off('click');
		document.getElementById("send_email_btn_not_multi"+data).disabled = true;
		document.getElementById("close_modal_multi"+data).disabled = true;
		// document.getElementsByClassName("close").disabled = true;
		$.ajax({
			url:app_base_url+'index.php/ajax/send_multi_details_mail/',
			type:'POST',
			data:{'email':input_text,'packagedetails':packagedetails,'module':'package'},
			success:function(msg){
                // alert(msg);
				$(document.body).on('click');
				if(msg == true){
				   $("#errormsg_multi_"+data).css({"color": "green"});
				   $("#errormsg_multi_"+data).text("Email Sent Successfully");
				   $("#email_multi_"+data).val("");
				   setTimeout( function(){$("#errormsg_multi_"+data).hide();$("#sendmail_multi_"+data).modal('hide');} , 3000);
				   
					
				   // $('.result-pre-loader').hide();
				  $('#send_email_loading_image_multi_'+data).hide();
					$('.loader-image').hide();
					document.getElementById("send_email_btn_not_multi"+data).disabled = false;
					document.getElementById("close_modal_multi"+data).disabled = false;
					// document.getElementsByClassName("close").disabled = false;
					// setTimeout( function(){} , 3000);
				}
				else
				{
					$("#errormsg_multi_"+data).css({"color": "red"});
					   $("#errormsg_multi_"+data).text("Try again");
					   // $('.result-pre-loader').hide();
					   $('#send_email_loading_image_multi').hide();
					$('.loader-image').hide();
					document.getElementById("send_email_btn_not_multi").disabled = false;
					   location.reload();

				}
			},
			error:function(){
			}
		 }) ;

}
</script>
</body>
</html>
