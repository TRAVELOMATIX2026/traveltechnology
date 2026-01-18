
<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/car_search.js'), 'defer' => 'defer');
// Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/jquery-1.11.0.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_session_expiry_script.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => JAVASCRIPT_LIBRARY_DIR.'jquery.jsort.0.4.min.js', 'defer' => 'defer');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/car-result.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/owl.carousel.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => JAVASCRIPT_LIBRARY_DIR.'jquery.nicescroll.js', 'defer' => 'defer');
echo $this->template->isolated_view('share/js/lazy_loader');
foreach ($active_booking_source as $t_k => $t_v) {
	$active_source[] = $t_v['source_id'];
}
$active_source = json_encode($active_source);
?>
<script>
var search_session_alert_expiry = "<?php echo $GLOBALS ['CI']->config->item ( 'flight_search_session_expiry_alert_period' ); ?>";
var search_session_expiry = "<?php echo $GLOBALS ['CI']->config->item ( 'flight_search_session_expiry_period' ); ?>";
var search_hash = '';
var session_time_out_function_call = 0;
var api_request_cnt = 0;
	var load_car = function(loader, offset, filters){		
		offset = offset || 0;
		var url_filters = '';
		$(".car_filter_load").show();
		if ($.isEmptyObject(filters) == false) {
			url_filters = '&'+($.param({'filters':filters}));
			
		}
		_lazy_content = $.ajax({
			type: 'GET',
			url: app_base_url+'index.php/ajax/car_list/'+offset+'?booking_source=<?=$active_booking_source[0]['source_id']?>&search_id=<?=$car_search_params['search_id']?>&op=load'+url_filters,
			async: true,
			cache: true,
			//dataType: 'json',
			success: function(res) {
				loader(res);
				$(".car_filter_load").hide();
				$("#result_found_text").removeClass('hide');
			}
		});		
	}
	var interval_load = function (res) {
		
		var dui;
		var r = res;
		dui = setInterval(function(){
			if (typeof(process_result_update) != "undefined" && $.isFunction(process_result_update) == true) {
				clearInterval(dui);
				process_result_update(r);
	     		
	     		ini_result_update(r);
	    	}
		}, 1);
	};
	load_car(interval_load);

</script>
<span class="hide">
	<input type="hidden" id="pri_search_id" value='<?=$car_search_params['search_id']?>'>
	<input type="hidden" id="pri_active_source" value='<?=$active_source?>'>
	<input type="hidden" id="pri_app_pref_currency" value='<?= $this->currency->get_currency_symbol(get_application_currency_preference()) ?>'>
	<?php // echo $this->currency->get_currency_symbol(get_application_display_currency_preference());?>
</span>
<?php
	//debug($car_search_params); exit;
	$data['result'] = $car_search_params;
	$mini_loading_image = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="Loading........"/></div>';
	$loading_image = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v1.gif').'" alt="Loading........"/></div>';
	$template_images = $GLOBALS['CI']->template->template_images();
	echo $GLOBALS['CI']->template->isolated_view('car/search_panel_summary');
	?>
		<div id="page-parent">
		
		<div class="allpagewrp top80">
		
		  <div class="clearfix"></div>
		  <div class="search-result car_search_results">
		    <div class="container p-0">
		     <?php //echo $GLOBALS['CI']->template->isolated_view('share/loader/car_result_pre_loader', $car_search_params); ?>
		      <div class="filtrsrch">
		        <div class="coleft">
		          <div class="flteboxwrp">
		            
		            <div class="fltrboxin"> 
						<div class="hdr_flx">
							<h5>Filters</h5>
							<a id="reset_filters" class="float-end">Reset All</a>
						</div>
						<div class="filtersho">                        
							<div class="avlhtls"><strong id="filter_records">0</strong> Car found</div>                       
							<span class="close_fil_box"><i class="fa fa-close"></i></span>                    
						</div>
						<div class="clearfix"></div>
						<div class="septor"></div>
		              <div class="bnwftr">
		                
		                <div class="rangebox">
							<button data-bs-target="#collapse501" data-bs-toggle="collapse"
									class="collapsebtn" type="button">Price</button>
									<strong><span id="total_result_count"><?php echo $mini_loading_image?></span></strong>
							<div id="collapse501" class="in collapse price_slider1">
								<div class="price_slider1">
									<div id="core_min_max_slider_values" class="hide">
										<input type="hidden" id="core_minimum_range_value" />
										<input type="hidden" id="core_maximum_range_value" />
									</div>
									<p id="car-price" class="level"></p>
									<div id="price-range" class="" aria-disabled="false"></div>
								</div>
							</div>
						</div>

                        <div class="rangebox">
							<button data-bs-target="#collapse505" data-bs-toggle="collapse" class="collapsebtn" type="button">Supplier</button>
							<div id="collapse505" class="collapse in">
								<div class="boxins">
									<ul class="locationul" id="car-vendor-list-wrapper">
									
									</ul>
								</div>
							</div>
		                </div>

		              
		                 <div class="rangebox">
							<button data-bs-target="#collapse510" data-bs-toggle="collapse" class="collapsebtn" type="button">Auto/Manual</button>
							<div id="collapse510" class="collapse in">
								<div class="boxins">
									<ul class="locationul" id="car-vehicle-manual-wrapper"></ul>
								</div>
							</div>
		                </div> 
		                
		                <!-- <div class="rangebox">
							<button data-bs-target="#collapse506" data-bs-toggle="collapse" class="collapsebtn" type="button">Included Insurance</button>
							<div id="collapse506" class="collapse in">
								<div class="boxins">
									<ul class="locationul" id="car-package-list-wrapper">
									</ul>
								</div>
							</div>
		                </div> -->
		               
		                <div class="rangebox">
							<button data-bs-target="#collapse515" data-bs-toggle="collapse" class="collapsebtn" type="button">AC/Non AC</button>
							<div id="collapse515" class="collapse in">
								<div class="boxins">
									<ul class="locationul" id="car-vehicle-ac-wrapper">
									</ul>
								</div>
							</div>
		                </div> 
		                <div class="septor"></div>
						<div class="rangebox">
							<button data-bs-target="#collapse502" data-bs-toggle="collapse"
									class="collapsebtn" type="button">Package</button>
							<div id="collapse502" class="collapse in">
								<div class="boxins">
									<ul class="locationul" id="car-vehicle-package-wrapper">
									</ul>
								</div>
							</div>
		                </div>
		                <div class="septor"></div>
		                <div class="rangebox">
							<button data-bs-target="#collapse503" data-bs-toggle="collapse" class="collapsebtn" type="button">Door Count</button>
							<div id="collapse503" class="collapse">
								<div class="boxins">
									<ul class="locationul" id="car-door-count-wrapper">
									</ul>
								</div>
							</div>
		                </div>
		                <div class="septor"></div>		                
		                <div class="rangebox">
							<button data-bs-target="#collapse504" data-bs-toggle="collapse"
									class="collapsebtn" type="button">Passenger Count</button>
							<div id="collapse504" class="collapse">
								<div class="boxins">
									<ul class="locationul" id="car-passenger-quantity-wrapper">
									</ul>
								</div>
							</div>
		                </div>		                
		                <div class="septor"></div>
						<div class="rangebox">
		                	<button data-bs-target="#collapse507" data-bs-toggle="collapse" class="collapsebtn" type="button">Car Category</button>
							<div id="collapse507" class="collapse">
								<div class="boxins">
									<ul class="locationul" id="car-vehicle-category-wrapper">
									</ul>
								</div>
							</div>
		                </div>
		                <div class="septor"></div>
		                <div class="rangebox">
							 <button data-bs-target="#collapse508" data-bs-toggle="collapse"
									class="collapsebtn" type="button">Car Size</button>
							<div id="collapse508" class="collapse">
								<div class="boxins">
									<ul class="locationul" id="car-vehicle-size-wrapper">
									</ul>
								</div>
							</div>
		                </div>
		                
		              </div>
		            </div>
		          </div>
		        </div>
		        <div class="colrit">
		          <div class="insidebosc">
                 

                  <div class="topmisty hote_reslts">              
                  <div class="col-12 nopad fullshort">               
                   <button class="filter_show"><i class="fa fa-filter"></i> <span class="text_filt">Filter</span></button>                

	                   <div class="insidemyt">                  
		                   <div class="col-12 nopad">                    
			                   <ul class="sortul">                    
			                   <li class="sortli" data-sort="hn">
			                   <a class="sorta asc name-l-2-h" data-order="asc"><span class="fa fa-sort-alpha-asc"></span> Car Name</a>
			                   <a class="sorta des name-h-2-l hide" data-order="desc"><span class="sirticon fa fa-sort-alpha-desc"></span> Car Name</a>
			                   </li>

			                   <li class="sortli" data-sort="s">
			                   <a class="sorta asc supplier-l-2-h" data-order="asc"><span class="sirticon fa fa-user"></span> Supplier</a>
			                   <a class="sorta des supplier-h-2-l hide" data-order="desc"><span class="sirticon fa fa-user"></span> Supplier</a>
			                   </li>

			                   <li class="sortli" data-sort="sr">
			                   <a class="sorta asc cartype-l-2-h" data-order="asc"><span class="sirticon fa fa-star-o"></span> Category</a>
			                   <a class="sorta des cartype-h-2-l hide" data-order="desc"><span class="sirticon fa fa-star-o"></span> Category</a>
			                   </li>                        

			                   <li class="sortli" data-sort="p">
			                   <a class="sorta asc price-l-2-h hide" data-order="asc"><span class="sirticon fa fa-tag"></span> Price</a>
			                   <a class="sorta des price-h-2-l active" data-order="desc"><span class="sirticon fa fa-tag"></span> Price</a>
			                   </li>
			                   </ul>                  
		                   </div>                
	                   </div>              
                   </div>            
                   </div>
		           
		            <!--All Available cars result comes here -->
					<div class="car_results" id="car_search_result">
						<!-- Result Loader Overlay -->
						<div id="result-loader-overlay" class="result-loader-overlay hide">
							<div class="result-loader-content">
								<div class="result-loader-graphic">
									<div class="plane-icon">
										<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z" fill="currentColor"/>
										</svg>
									</div>
									<div class="loader-rings">
										<div class="ring ring-1"></div>
										<div class="ring ring-2"></div>
										<div class="ring ring-3"></div>
									</div>
								</div>
								<p class="result-loader-text">Loading cars...</p>
								<div class="loader-progress-bar">
									<div class="loader-progress-fill"></div>
								</div>
							</div>
						</div>

						
						<!-- Result Skeleton Loader -->
						<div id="result-skeleton-loader" class="car_sec" style="display: block !important;">
							<?php for ($i = 1; $i <= 6; $i++): ?>
								<div class="rowresult r-r-i" style="margin-bottom: <?php echo $i === 1 ? 'var(--spacing-3)' : 'var(--spacing-4)'; ?>;">
									<div class="madgrid">
										<div class="col-12 nopad">
											<div class="sidenamedesc mobile_f_i">
												<!-- Car Image Skeleton -->
												<div class="celhtl width20 midlbord">
													<div class="car_image skeleton-loader" style="width: 100%; height: 200px; border-radius: var(--border-radius-md);"></div>
												</div>
												<!-- Car Details Skeleton -->
												<div class="celhtl width60">
													<div class="waymensn">
														<div class="flitruo_hotel">
															<div class="hoteldist">
																<!-- Car Name -->
																<div class="skeleton-loader" style="height: 24px; width: 250px; margin-bottom: var(--spacing-2); border-radius: var(--border-radius-sm);"></div>
																<div class="skeleton-loader" style="height: 16px; width: 180px; margin-bottom: var(--spacing-3); border-radius: var(--border-radius-sm);"></div>
																
																<!-- Vehicle Class -->
																<div class="pick cr_wdt" style="margin-bottom: var(--spacing-2);">
																	<div class="skeleton-loader" style="height: 18px; width: 200px; border-radius: var(--border-radius-sm);"></div>
																</div>
																
																<!-- Fuel Type -->
																<div class="pick" style="margin-bottom: var(--spacing-2);">
																	<div class="skeleton-loader" style="height: 18px; width: 150px; border-radius: var(--border-radius-sm);"></div>
																</div>
																
																<!-- Vehicle Type -->
																<div class="pick cr_wdt" style="margin-bottom: var(--spacing-2);">
																	<div class="skeleton-loader" style="height: 18px; width: 180px; border-radius: var(--border-radius-sm);"></div>
																</div>
															</div>
															<div class="clearfix"></div>
															<div class="middleCol">
																<!-- Features Skeleton -->
																<ul class="features" style="margin-bottom: var(--spacing-2);">
																	<li style="display: inline-block; margin-right: var(--spacing-2);">
																		<div class="skeleton-loader" style="width: 40px; height: 40px; border-radius: 50%;"></div>
																	</li>
																	<li style="display: inline-block; margin-right: var(--spacing-2);">
																		<div class="skeleton-loader" style="width: 40px; height: 40px; border-radius: 50%;"></div>
																	</li>
																	<li style="display: inline-block; margin-right: var(--spacing-2);">
																		<div class="skeleton-loader" style="width: 40px; height: 40px; border-radius: 50%;"></div>
																	</li>
																</ul>
																<!-- Mileage Skeleton -->
																<div class="pick" style="margin-bottom: var(--spacing-2);">
																	<div class="skeleton-loader" style="height: 16px; width: 160px; border-radius: var(--border-radius-sm);"></div>
																</div>
																<!-- Supplier Logo Skeleton -->
																<div class="suplier_logo">
																	<div class="skeleton-loader" style="width: 80px; height: 30px; border-radius: var(--border-radius-sm);"></div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!-- Price Skeleton -->
												<div class="width20 mobile_f_i">
													<div class="mrinfrmtn">
														<div class="sidepricewrp">
															<div class="sideprice">
																<div class="skeleton-loader" style="height: 32px; width: 120px; margin-bottom: var(--spacing-2); border-radius: var(--border-radius-md);"></div>
																<div class="skeleton-loader" style="height: 20px; width: 100px; margin-bottom: var(--spacing-2); border-radius: var(--border-radius-sm);"></div>
																<div class="skeleton-loader" style="height: 40px; width: 140px; border-radius: var(--border-radius-md);"></div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endfor; ?>
						</div>
					</div>
					<!-- End of result -->
				  <!--Map view indipendent hotel-->
		          </div>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
	</div>
	 
	<div id="empty-search-result" class="jumbotron container" style="display:none">
		<h1><i class="fa fa-taxi"></i> Oops!</h1>
		<p>No cars were found in this location today.</p>
		<p>
			Search results change daily based on availability.If you have an urgent requirement, please get in touch with our call center using the contact details mentioned on the home page. They will assist you to the best of their ability.
		</p>
	</div>
	<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_session_expiry_popup');?>

<script>
/*	$('.filter_show').click(function(){
		$('.filtrsrch').addClass('open');
	});
	
	$('.close_filter').click(function(){
		$('.filtrsrch').removeClass('open');
	});*/
	
	/*  Mobile Filter  */
	$('.filter_show').click(function() {
		$('.filtrsrch').stop( true, true ).toggleClass('open');
		$('.col30').addClass('round_filt');
		// $('.col30').stop( true, true ).slideToggle(500);
		$(".col30.round_filt").show();
	});
	$(".close_fil_box").click(function(){
			$(".col30.round_filt").hide();
	});	
	
	</script>
	<!-- <script type="text/javascript" src="js/owl.carousel.min.js"></script> -->
<script>
  $(document).ready(function(){
    $("#owl-demo2").owlCarousel({
        items : 6, 
        itemsDesktop : [1000,6],
        itemsDesktopSmall : [900,4], 
        itemsTablet: [600,2], 
        itemsMobile : [479,1], 
        navigation : true,
        navigationText: [],
        pagination : false,
        autoPlay : 5000
    });


  });
</script>


 <script type="text/javascript">
   $(document).ready(function(){
    $('[data-bs-toggle="tooltip"]').tooltip();
   });
  </script>

<!-- Mobile Filter FAB Button and Overlay -->
<div class="filter-overlay"></div>
<button class="filter-fab-btn" title="Open Filters">
  <i class="material-icons">tune</i>
  <span class="filter-count hide">0</span>
</button>