<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/sight-search-form.css'), 'media' => 'screen');
  $category_id = 0;
  if ((isset($sight_seen_search_params) == false) || (isset($sight_seen_search_params) == true && valid_array($sight_seen_search_params) == false)) {
    $sparam = $this->input->cookie('sparam', TRUE);
    $sparam = unserialize($sparam);
    $sid = intval(@$sparam[META_SIGHTSEEING_COURSE]);
    if ($sid > 0) {
        $this->load->model('sightseeing_model');
        $sight_seen_search_params = $this->sightseeing_model->get_safe_search_data($sid, META_SIGHTSEEING_COURSE);
        $sight_seen_search_params = $sight_seen_search_params['data'];
        $destination = @$sight_seen_search_params['destination'];
        //$parent_id = @$sight_seen_search_params['parent_id'];
        $destination_id = @$sight_seen_search_params['destination_id'];
        //$api_currency_code = @$sight_seen_search_params['api_currency_code'];
        $from_date =@$sight_seen_search_params['from_date']; 
        $to_date = @$sight_seen_search_params['to_date'];
        
        $category_id = @$sight_seen_search_params['category_id'];
    }
    
  }
 // debug($sight_seen_search_params);
 // exit;
 $activity_datepicker = array(array('from_date', FUTURE_DATE_DISABLED_MONTH));
$GLOBALS['CI']->current_page->set_datepicker($activity_datepicker);
//$GLOBALS['CI']->current_page->auto_adjust_datepicker(array(array('from_date', 'to_date')));
?>


<form action="<?php echo base_url();?>index.php/general/pre_sight_seen_search" 
	autocomplete="off" id="activity_search" class="activity_search">
  <div class="tabspl forhotelonly sight_form">
    <div class="tabrow d-flex align-items-end">
      <div class="col-md-5 col-sm-6 col-12 full_smal_tab mobile_width">
        <div class="padfive">
          <div class="lablform">Enter Location</div>
          <div class="plcetogo plcemark sidebord">           
            <i class="material-icons field-icon field-icon-right">location_on</i>
            <input type="text" id="activity_destination_search_name" class="fromactivity1 normalinput form-control b-r-0" placeholder="Location" name="from" required value="<?php echo @$destination;?>"/>
            <input type="hidden" name="" id="name-search">
            <input class="hide loc_id_holder" name="destination_id" type="hidden" id="destination_id" value="<?=@$destination_id?>" >
          </div>
        </div>
      </div>
      
      <input type="hidden" name="category_id" id="select_cate" value="<?=$category_id?>">

      <div class="col-md-5 col-sm-6 col-12 full_smal_tab mobile_width">
        <div class="padfive">
          <div class="lablform">When</div>  
          <div class="plcetogo plcemark sidebord">
            <i class="material-icons field-icon field-icon-right">calendar_today</i>
            <input  type="text" class="form-control b-r-0 normalinput" data-date="true" readonly name="from_date" id="from_date" placeholder="From Date" required value="<?php echo @$from_date?>"/>
          </div>
        </div>
      </div>
    
      <div class="clear-date hide">
        <div class="lablform">&nbsp;</div>
        <button class="btn btn-info" id="clear_date" type="button">Clear Dates </button>
      </div>

      <div class="col-md-2 col-sm-12 col-12 full_mobile mobile_width srch_sight">
        <div class="lablform">&nbsp;</div>
        <div class="searchsbmtfot flightbutton sightSeeningBtn">
          <input type="submit" class="searchsbmt flight_search_btn" value="Search activity" />
        </div>
      </div>

    </div>
  </div>
  
</form>

<script type="text/javascript">
   
  $(document).ready(function(){
  $(".fromactivity_").autocomplete({
  
    source:"<?php echo base_url(); ?>index.php/activity/get_activity_auto",
    minLength: 2,//search after two characters
    autoFocus: true, // first item will automatically be focused
    select: function(event,ui){
    //	alert(ui);
      $(".departflight").focus();
      //$(".flighttoo").focus();
    }
  });
  
  
var current_module = "sightseen";
  $(".fromactivity1").catcomplete({

        source: function(request, response) {
            var term = request.term;

            $.getJSON(app_base_url + "index.php/ajax/get_sightseen_city_list", request, function(data, status, xhr) {
                //cache[term] = data;
                response(data)
            })
        },
        minLength: 3,
        autoFocus: true,
        select: function(event, ui) {
            var label = ui.item.label;
            var category = ui.item.category;
            $(this).siblings('.loc_id_holder').val(ui.item.id);
        },
        change: function(ev, ui) {
            if (!ui.item) {
                $(this).val("")
            }
        }
    }).bind('focus', function() {
        $(this).catcomplete("search")
    }).catcomplete("instance")._renderItem = function(ul, item) {
        var auto_suggest_value = highlight_search_text(this.term.trim(), item.value, item.label);
        var hotel_count = '';
        var count = parseInt(item.count);
        if (count > 0) {
            var h_lab = '';
            if (count > 1) {
                h_lab = 'Hotels'
            } else {
                h_lab = 'Hotel'
            }
            hotel_count = '<span class="hotel_cnt">(' + parseInt(item.count) + ' ' + h_lab + ')</span>'
        }
        return $("<li class='custom-auto-complete'>").append('<a> <span class="fal fa-map-marker-alt"></span> ' + auto_suggest_value + ' ' + hotel_count + '</a>').appendTo(ul)
    };
  });

</script>
