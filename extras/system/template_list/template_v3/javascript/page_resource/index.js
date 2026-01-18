$((function(t){var a=db_date(7),e=db_date(10);t(".htd-wrap").on("click",(function(i){i.preventDefault();var l=t(".top-des-val",this).val(),o=t(".top_des_id",this).val();t("#hotel_destination_search_name").val(l),t(".loc_id_holder").val(o),t("#hotel_checkin").val(a),t("#hotel_checkout").val(e),t("#hotel_search").submit()})),t(".activity-search").on("click",(function(a){a.preventDefault();var e=t(".destination_name",this).val(),i=t(".destination_id",this).val(),l=t(".category_id",this).val();o=t(".from_date",this).val(),t("#activity_destination_search_name").val(e),t(".loc_id_holder").val(i),t("#select_cate").val(l),t("#from_date").val(o),t("#activity_search").submit()})),t(".flight_deals_search").on("click",(function(a){a.preventDefault();var e=t(".flight_top_from_id",this).val(),i=t(".flight_top_to_id",this).val(),l=t(".flight_top_from_value",this).val(),o=t(".flight_top_to_value",this).val(),s=t(".flight_top_depature",this).val(),_=t(".flight_trip_type",this).val();"oneway"==_?(t("#flight_form #from").val(),t("#flight_form #to").val(),t("#onew-trp").prop("checked",!0),t("input,checkbox,radio,select","#multi_way_fieldset").attr("disabled","disabled")):(t("#m_from1").val(),t("#m_to1").val(),t("#m_from2").val(),t("#m_to2").val(),t("#m_from3").val(),t("#m_to3").val(),t("#m_from4").val(),t("#m_to4").val(),t("#m_from5").val(),t("#m_to5").val(),t("input,checkbox,radio,select","#onw_rndw_fieldset").attr("disabled","disabled")),t("#from").val(l),t("#from_loc_id").val(e),t("#to").val(o),t("#to_loc_id").val(i),t("#flight_datepicker1").val(s),t("#onew-trp").val(_),t("#flight-form-submit").val("Search Flight"),t("#flight_form").submit()})),t("#owl-demo2").owlCarousel({items:3,itemsDesktop:[991,2],itemsDesktopSmall:[767,2],itemsTablet:[600,1],itemsMobile:[479,1],navigation:!0,pagination:!1}),t("#TopAirLine").owlCarousel({items:5,loop:!0,margin:10,autoplay:!0,navigation:!0,pagination:!1,autoplayTimeout:1e3,autoplayHoverPause:!0}),t("#all_deal").owlCarousel({items:3,itemsDesktop:[1e3,3],itemsDesktopSmall:[991,3],itemsTablet:[767,2],itemsMobile:[480,1],navigation:!0,pagination:!1}),$(document).ready((function(){$("#quote-carousel").carousel({pause:!0,interval:1e4})})));

// Hotel Favorite Button Toggle
$(document).ready(function() {
  $('.hotel-favorite').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).toggleClass('active');
    var icon = $(this).find('i');
    if ($(this).hasClass('active')) {
      icon.text('favorite');
    } else {
      icon.text('favorite_border');
    }
  });
  
  // Prevent card click when clicking favorite
  $('.hotel-card').on('click', function(e) {
    if ($(e.target).closest('.hotel-favorite').length) {
      return false;
    }
  });
});