<?php
//debug($transfer_search_params);exit;
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/transfer_search.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('jquery.nicescroll.js'), 'defer' => 'defer');
echo $this->template->isolated_view('share/js/lazy_loader');
foreach ($active_booking_source as $t_k => $t_v) {
    $active_source[] = $t_v['source_id'];
}
$active_source = json_encode($active_source);
?>
<script>
var load_transfers = function(loader, offset, filters){
    //alert(filters);
    offset = offset || 0;
    var url_filters = '';
    if ($.isEmptyObject(filters) == false) {
        url_filters = '&'+($.param({'filters':filters}));
    }
    _lazy_content = $.ajax({
        type: 'GET',
        url: app_base_url+'index.php/ajax/transfer_list/'+offset+'?booking_source=<?=$active_booking_source[0]['source_id']?>&search_id=<?=$transfer_search_params['search_id']?>&op=load'+url_filters,
        async: true,
        cache: true,
        dataType: 'json',
        success: function(res) {
            loader(res);
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
load_transfers(interval_load);
</script>

<style type="text/css">
    .starrtinghotl {
    display: block;
    max-width: 100%;
    position: absolute;
    right: 0; font-size: 13px;
    top: 8px;
    left: 0px;
    text-align: center;
}
.placenameflt{ padding: 14px 0px; }
.result_srch_htl .sidepricewrp .priceflights { margin:0; }
.celhtl.width30 {
    vertical-align: middle;
    padding: 35px 0; position: relative;
    overflow: hidden;
    display: block;
    background: #fff;
}
.imagehtldis { height: 184px; }
</style>

<span class="hide">
    <input type="hidden" id="pri_search_id" value='<?=$transfer_search_params['search_id']?>'>
    <input type="hidden" id="pri_active_source" value='<?=$active_source?>'>
    <input type="hidden" id="pri_app_pref_currency" value='<?=$this->currency->get_currency_symbol(get_application_currency_preference())?>'>
    
</span>
<?php
    $data['result'] = $transfer_search_params;
    $mini_loading_image = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="Loading........"/></div>';
    $loading_image = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v1.gif').'" alt="Loading........"/></div>';
    $template_images = $GLOBALS['CI']->template->template_images();
    function get_sorter_set()
    {
        return '<div class="filterforallnty" id="top-sort-list-wrapper">
                            <div class="topmistyhtl" id="top-sort-list-1">
                                <div class="col-12 nopad">
                                    <div class="insidemyt">
                                        <ul class="sortul">
                                            
                                            <li class="sortli threonly"><a class="sorta name-l-2-h loader asc"><i class="fa fa-sort-alpha-asc"></i> <strong>Name</strong></a><a
                                                class="sorta name-h-2-l hide loader des"><i class="fa fa-sort-alpha-desc"></i> <strong>Name</strong></a></li>
                                            
                                            <li class="sortli threonly"><a class="sorta star-l-2-h loader asc"><i class="fa fa-star"></i> <strong>Rating</strong></a><a
                                                class="sorta star-h-2-l hide loader des"><i class="fa fa-star"></i> <strong>Rating</strong></a></li>
                                                                                    
                                            <li class="sortli threonly"><a class="sorta price-l-2-h loader asc"><i class="fa fa-tag"></i> <strong>Price</strong></a><a
                                                class="sorta price-h-2-l hide loader des"><i class="fa fa-tag"></i> <strong>Price</strong></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>';
    }
    echo $GLOBALS['CI']->template->isolated_view('transfer/search_panel_summary');
    ?>
<section class="search-result tour_search_results sghtseen">
    <div class="container-fluid"  id="page-parent">
        <?php echo $GLOBALS['CI']->template->isolated_view('share/loader/transfer_pre_loader',$data);?>
        <div class="container">
        <div class="resultalls open">
            <div class="coleft">
                <div class="flteboxwrp">
                    <div class="filtersho">
                        <div class="avlhtls"><strong id="filter_records"></strong> <span class="hide"> of <strong id="total_records"><?php echo $mini_loading_image?></strong> </span> Transfers found
                        </div><span class="close_fil_box"><i class="fas fa-times"></i></span>
                    </div>
                    <div class="fltrboxin">
                        <form autocomplete="off">
                            <div class="celsrch refine">
                                <div class="row_top_fltr">
                                    <a class="snf_btn float-start active" title="Show Net Fare">
                                    <span class="fas fa-tag"></span>
                                    <span class="tag_snf hide">SNF</span></a>
                                    <a class="float-end reset_filter" id="reset_filters" style="font-size: 12px;">RESET ALL</a>
                                </div>
                                <div class="rangebox">
                                    <button data-bs-target="#price-refine" data-bs-toggle="collapse" class="collapsebtn refine-header" type="button">
                                    Price
                                    </button>
                                    <?php echo $mini_loading_image?>
                                    <div id="price-refine" class="in">
                                        <div class="price_slider1">
                                            <div id="core_min_max_slider_values" class="hide">
                                                <input type="hiden" id="core_minimum_range_value" value="">
                                                <input type="hiden" id="core_maximum_range_value" value="">
                                            </div>
                                            <p id="hotel-price" class="level"></p>
                                            <div id="price-range" class="" aria-disabled="false"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="septor"></div>
                                    
                                <div class="rangebox">
                                    <button data-bs-target="#hotelsearch-refine" data-bs-toggle="collapse" class="collapsebtn refine-header" type="button">
                                    Search Transfer Types
                                    </button>
                                    <div id="hotelsearch-refine" class="in">
                                        <div class="boxins">
                                            <div class="relinput">
                                                <input type="text" class="srchhtl form-control" placeholder="Transfers Names" id="tour-name" />
                                                <input type="button" class="srchsmall" id="tour-search-btn" value="" />
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="septor"></div>
<!--                                <div class="rangebox">
    <div class="cate-list">
    <button data-bs-target="#accordion" data-bs-toggle="collapse" class="collapsebtn refine-header" type="button">
     Sightseeing Type
    </button>
    

        
        <div id="accordion" class="card-group category-list">
                <div class="panel card">
                
                <a class="btn cate-btn-click" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#demo">Sightseeing Type</a>
                
                <div id="demo" class="collapse collapse in">
                
                </div>
                </div>

        </div>
        </div>
    
</div> -->
                                <!-- <div class="rangebox">
                                    <button data-bs-target="#sight_seen_type" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                    Sightseeing Type
                                    </button>
                                    <div id="sight_seen_type" class="in">
                                        <div class="boxins sight_seen_types">
                                            <ul class="" id="hotel-amenitie-wrapper">
                                               <li>
                                                  <div class="squaredThree">
                                                  <input type="checkbox" id="wifi-hotels-view" value="filter" class="wifi-hotels-view" name="amenitie[]">
                                                  <label for="wifi-hotels-view"></label>
                                                  </div>
                                                  <label class="lbllbl" for="wifi-hotels-view">Tour Types</label></li>
                                            
                                            </ul>
                                        </div>
                                    </div>
                                </div> -->
                               
                                <div class="septor"></div>
                                <div class="rangebox" id="category_div">
                                    <button data-bs-target="#transfer_category" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                    Category
                                    </button>
                                    <div id="transfer_category" class="in">
                                        <div class="boxins transfer_category" id="transfer-category-wrapper">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="septor"></div>
                                <div class="rangebox" id="vehicle_div">
                                    <button data-bs-target="#transfer_vehicle" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                    Vechile
                                    </button>
                                    <div id="transfer_vehicle" class="in">
                                        <div class="boxins transfer_vehicle" id="transfer-vehicle-wrapper">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="septor"></div>
                                <div class="rangebox" id="suitecase_div">
                                    <button data-bs-target="#transfer_suitcase" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                    Suitcases
                                    </button>
                                    <div id="transfer_suitcase" class="in">
                                        <div class="boxins transfer_suitcase" id="transfer-suitcase-wrapper">
                                            
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
                <!-- Prev|Next Searcrh Button Ends -->
                <div class="insideactivity">
                    <div class="resultall">
                        <div class="filter_tab fa fa-filter"></div>
                        <div class="vluendsort">
                            <div class="insidemyt col-12 nopad">
                                <!-- <div class="col-3 nopad">
                                    <div class="nityvalue">
                                        <label type="button" class="vlulike active filter-hotels-view" for="all-hotels-view">
                                        <input type="radio" id="all-hotels-view" value="all" class="hide deal-status-filter" name="deal_status[]" checked="checked">
                                        All Activity
                                        </label>
                                    
                                    
                                    </div>
                                </div> -->
                                <div class="col-9 mfulwdth nopad">
                                    <div class="filterforallnty" id="top-sort-list-wrapper">
                                        <div class="topmistyhtl" id="top-sort-list-1">
                                            <div class="col-12 nopad">
                                                <div class="insidemyt">
                                                    <ul class="sortul">
                                                        <li class="sortli threonly" data-sort="hn">
                                                            <a class="sorta name-l-2-h asc" data-order="asc"><i class="fa fa-sort-alpha-asc"></i> <strong>Name</strong></a>
                                                            <a class="sorta name-h-2-l hide des" data-order="desc"><i class="fa fa-sort-alpha-desc"></i> <strong>Name</strong></a>
                                                        </li>
                                                        
                                                        <li class="sortli threonly" data-sort="p">
                                                            <a class="sorta price-l-2-h asc" data-order="asc"><i class="fa fa-tag"></i> <strong>Price</strong></a>
                                                            <a class="sorta price-h-2-l hide  des" data-order="desc"><i class="fa fa-tag"></i> <strong>Price</strong></a>
                                                        </li>
                                                    <!--    <li class="sortli threonly" data-sort="p">
                                                        <a class="sorta"><i class="fa fa-tag"></i><strong>Price</strong></a>
                                                                <select class="col-3 form-control seldiv" id="price-filter" name="price">
                                                                    <option value="TOP_SELLERS" selected="selected">Top Seller</option>
                                                                    <option value="PRICE_FROM_A">Price (low->high)</option>
                                                                    <option value="PRICE_FROM_D">Price (high->low)</option>
                                                                    
                                                                </select>
                                                        </li> -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                      
                            <div class="col-3 mobile_none nopad">
                                <div class="mapviw noviews">
                                      <div class="mapviwlist nopad noviews reswd">
                                        <div class="rit_view">
                                            <a class="view_type list_click active" id="list_clickid"><span class="fa fa-list"></span></a> 
                                        </div>
                                    </div>
                                    <div class="mapviwhtl nopad noviews reswd">
                                           <div class="rit_view">
                                                   <a class="view_type grid_click"><span class="fa fa-th"></span></a> 
                                           </div>
                                         </div>
                                  
                                  
                                </div>
                            </div>
                        </div>
                        </div>

                    
                        <div class="allresult">
                            <?php echo $loading_image;?>
                        
                            <div id="tour_search_result" class="hotel-search-result-panel result_srch_htl ">
                            </div>
                            <!-- <div class="hotel_map">
                                                            <div class="map_hotel" id="location_map"></div>
                                                        </div> -->

                            <div id="npl_img" class="text-center hide" loaded="true">
                                <?='<img src="'.$GLOBALS['CI']->template->template_images('loader_v1.gif').'" alt="Please Wait"/>'?>
                            </div>
                            <div id="empty_tour_search_result"  style="display:none">
                                <div class="noresultfnd">
                                    <div class="imagenofnd"><img src="<?=$template_images?>empty.jpg" alt="Empty" /></div>
                                    <div class="lablfnd">No Result Found!!!</div>
                                </div>
                            </div>
                        <hr class="hr-10">
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <div id="empty-search-result" class="jumbotron container" style="display:none">
        <h1><i class="fal fa-bed"></i> Oops!</h1>
        <p>No Transfer places were found in this location today.</p>
        <p>
            Search results change daily based on availability.If you have an urgent requirement, please get in touch with our call center using the contact details mentioned on the home page. They will assist you to the best of their ability.
        </p>
    </div>
</section>
 
<div class="modal fade bs-example-modal-lg" id="map-box-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Hotel Location Map</h4>
            </div>
            <div class="modal-body">
                <iframe src="" id="map-box-frame" name="map_box_frame" style="height: 500px;width: 850px;">
                </iframe>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" name="" id="selected_cate" value="">
<input type="hidden" name="" id="selected_sub_cate" value=""> 
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANXPM-4Tdxq9kMnI8OpL-M6kGsFFWreIY&callback=initialize" type="text/javascript"></script> -->
<?php
//echo $this->template->isolated_view('share/media/hotel_search');
?>
<script type="text/javascript">
    $(function(){
            $("#collapse3 .collapse ul li button.cate-btn-click").on("click",function(){
                //alert("hiiii");
            });
            /*$(document).on("click", ".madgrid", function () {
                
                var search_id = "<?php echo $transfer_search_params['search_id']?>";
                var booking_source = "<?=$active_booking_source[0]['source_id']?>";
                var product_code =$(this).data('product-code');
                var result_token = $(this).data('result-token');
                window.location ='<?php echo base_url()?>index.php/transfer/booking?op=get_details&search_id='+search_id+'&booking_source='+booking_source+'&product_code='+product_code+'&result_token='+result_token;

                //alert(url_text);


            });*/
            
    });

    $(document).ready(function () { 
        $(".close_fil_box").click(function () {
            $(".coleft").hide();
            $(".resultalls").removeClass("open");
        });

    $('.snf_btn').click(function(){
        $(this).toggleClass('active');
        $('.net-fare-tag').toggle();
        var title = 'Show Net Fare' ;
        if( $(this).hasClass('active')){
           title = 'Show Net Fare';
        }
        else{
            title = 'Hide Net Fare';
        }
        $(this).attr('title', title);
        $('.tag_snf', this).text(function(i, text){
            return text === "SNF" ? "HNF" : "SNF";
        });
    });
    
    });

</script>
