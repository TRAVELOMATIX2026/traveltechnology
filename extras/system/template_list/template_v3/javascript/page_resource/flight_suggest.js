var flight_adult_child_max_count = 9,
  cache = {};
function show_alert_content(t, e) {
  void 0 === e && (e = ".alert-content");
  $(e).html(t);
  $(".toast-message").html(t); // Update toast message
  
  // Check if message exists and has content (strip HTML tags for length check)
  var messageText = t ? t.replace(/<[^>]*>/g, '').trim() : '';
  
  if (messageText && messageText.length > 0) {
    // Show alert wrapper (if exists)
    $(".alert-wrapper").removeClass("hide");
    
    // Show toast - use multiple selectors to ensure we find it
    var $toast = $(".toast-snackbar-wrapper");
    if ($toast.length === 0) {
      console.error("Toast element not found!");
      return;
    }
    
    // Force show the toast
    $toast.removeClass("d-none").removeClass("hide").addClass("show");
    $toast.attr("style", "display: block !important; visibility: visible !important; opacity: 1 !important; pointer-events: auto !important;");
    
    // Auto-hide after 5 seconds
    clearTimeout(window.toastTimeout);
    window.toastTimeout = setTimeout(function() {
      $toast.removeClass("show").addClass("d-none").addClass("hide");
      $toast.attr("style", "display: none !important; visibility: hidden !important; opacity: 0 !important;");
      $(".alert-content, .toast-message").html('');
    }, 5000);
  } else {
    // Hide alert wrapper
    $(".alert-wrapper").addClass("hide");
    
    // Hide toast
    var $toast = $(".toast-snackbar-wrapper");
    if ($toast.length > 0) {
      $toast.removeClass("show").addClass("d-none").addClass("hide");
      $toast.attr("style", "display: none !important; visibility: hidden !important; opacity: 0 !important;");
    }
    clearTimeout(window.toastTimeout);
  }
}

// Swap Flight From and To Button Handler
$(document).on('click', '#swap_flight_btn, .flight-swap-btn', function(e) {
  e.preventDefault();
  e.stopPropagation();
  
  // Get current values
  var fromValue = $('#from').val();
  var toValue = $('#to').val();
  var fromLocId = $('#from_loc_id').val();
  var toLocId = $('#to_loc_id').val();
  var fromAirport = $('#fromairportloc').val();
  var toAirport = $('#toairportloc').val();
  var fromAirportSpan = $('#fromairport_span_val').text();
  var toAirportSpan = $('#span_airport_val').text();
  
  // Swap the values
  $('#from').val(toValue);
  $('#to').val(fromValue);
  $('#from_loc_id').val(toLocId);
  $('#to_loc_id').val(fromLocId);
  $('#fromairportloc').val(toAirport);
  $('#toairportloc').val(fromAirport);
  $('#fromairport_span_val').text(toAirportSpan || 'International Airport');
  $('#span_airport_val').text(fromAirportSpan || 'International Airport');
  
  // Trigger change events to update autocomplete if needed
  $('#from').trigger('change');
  $('#to').trigger('change');
  
  // Add visual feedback
  var $btn = $(this);
  $btn.addClass('swapping');
  setTimeout(function() {
    $btn.removeClass('swapping');
  }, 300);
});

// Close toast button handler
$(document).on('click', '.toast-close', function() {
  $(".toast-snackbar-wrapper").removeClass("show").addClass("d-none").addClass("hide").css({
    "display": "none",
    "visibility": "hidden",
    "opacity": "0"
  });
  $(".alert-wrapper").addClass("hide");
  $(".alert-content, .toast-message").html('');
});
function manage_infant_count(t) {
  var e = "",
    a = parseInt($("#OWT_adult").val().trim()),
    i = parseInt($("#OWT_child").val().trim()),
    l = parseInt($("#OWT_infant").val().trim()),
    n = a + i;
  if ("infant" == t && l > 0) {
    var c = l - 1;
    c >= a &&
      ($("#OWT_infant").val(c),
      $("#OWT_infant")
        .parent(".infant_count_div")
        .find("button[data-type=plus]")
        .attr("disabled", "disabled"),
      (e = "1 Infant Per Adult Allowed"));
  }
  if ("adult" == t)
    n - 1 >= flight_adult_child_max_count
      ? ($("#OWT_adult").val(a - 1),
        $("#OWT_adult")
          .parent(".adult_count_div")
          .find("button[data-type=plus]")
          .attr("disabled", "disabled"),
        (e = "<small>Max 9 Passenger(Adult+Child) Allowed</small>"))
      : ($("#OWT_adult")
          .parent(".adult_count_div")
          .find("button[data-type=plus]")
          .removeAttr("disabled"),
        $("#OWT_child")
          .parent(".child_count_div")
          .find("button[data-type=plus]")
          .removeAttr("disabled")),
      l > 0 && l > a && $("#OWT_infant").val(0),
      $("#OWT_infant")
        .parent(".infant_count_div")
        .find("button[data-type=plus]")
        .removeAttr("disabled");
  else if ("child" == t) {
    n - 1 >= flight_adult_child_max_count
      ? ($("#OWT_child").val(i - 1),
        $("#OWT_child")
          .parent(".child_count_div")
          .find("button[data-type=plus]")
          .attr("disabled", "disabled"),
        (e = "<small>Max 9 Passenger(Adult+Child) Allowed</small>"))
      : ($("#OWT_adult")
          .parent(".adult_count_div")
          .find("button[data-type=plus]")
          .removeAttr("disabled"),
        $("#OWT_child")
          .parent(".child_count_div")
          .find("button[data-type=plus]")
          .removeAttr("disabled"));
  }
  show_alert_content(e);
}
$(document).ready(function () {
  function t(t) {
    toggle_add_city_button(),
      "oneway" == t
        ? ($("#select_type").html("Oneway"),
          $(".alt_days_cls").show(),
          $("#flight_datepicker2")
            .attr("disabled", !0)
            .removeAttr("required")
            .closest(".return-date-wrapper")
            .css({ opacity: "0" }),
          // Show "Add return date" overlay
          $(".return-date-field .add-return-date-overlay").fadeIn(200),
          // $("#flight_datepicker2").val(""),
          0 == $("#onew-trp").parent("label.wament").hasClass("active") &&
            $("#onew-trp").parent("label.wament").addClass("active"),
          1 == $("#rnd-trp").parent("label.wament").hasClass("active") &&
            $("#rnd-trp").parent("label.wament").removeClass("active"),
          1 == $("#multi-trp").parent("label.wament").hasClass("active") &&
            $("#multi-trp").parent("label.wament").removeClass("active"),
          $("#onw_rndw_fieldset").show(),
          $("#multi_way_fieldset").hide(),$('.thrdtraveller').removeClass('multicity_traveller'),$('#flight .tabspl').removeClass('multicitybg'))
        : "circle" == t
        ? ($("#select_type").html("Round Trip"),
          $(".alt_days_cls").show(),
          0 == $("#rnd-trp").parent("label.wament").hasClass("active") &&
            $("#rnd-trp").parent("label.wament").addClass("active"),
          1 == $("#onew-trp").parent("label.wament").hasClass("active") &&
            $("#onew-trp").parent("label.wament").removeClass("active"),
          1 == $("#multi-trp").parent("label.wament").hasClass("active") &&
            $("#multi-trp").parent("label.wament").removeClass("active"),
          $("#flight_datepicker2")
            .removeAttr("disabled")
            .attr("required", "required")
            .closest(".return-date-wrapper")
            .css({ opacity: "1" }),
          // Hide "Add return date" overlay
          $(".return-date-field .add-return-date-overlay").fadeOut(200),
          $("#onw_rndw_fieldset").show(),
          $("#multi_way_fieldset").hide(),$('.thrdtraveller').removeClass('multicity_traveller'),$('#flight .tabspl').removeClass('multicitybg'))
        : "multicity" == t &&
          ($("#select_type").html("Multi-City"),
          $(".alt_days_cls").hide(),
          0 == $("#multi-trp").parent("label.wament").hasClass("active") &&
            $("#multi-trp").parent("label.wament").addClass("active"),
          1 == $("#onew-trp").parent("label.wament").hasClass("active") &&
            $("#onew-trp").parent("label.wament").removeClass("active"),
          1 == $("#rnd-trp").parent("label.wament").hasClass("active") &&
            $("#rnd-trp").parent("label.wament").removeClass("active"),
          $("#onw_rndw_fieldset").hide(),
          $("#multi_way_fieldset").show(),$('.thrdtraveller').addClass('multicity_traveller'),$('#flight .tabspl').addClass('multicitybg'),
          (function() {
            var maxSegments = parseInt($("#max_multicity_segments").val()) || 5;
            var initialCount = parseInt($("#multicity_segment_count").val()) || 2;
            for (var i = 1; i <= maxSegments; i++) {
              if (i > initialCount) {
                $("#multi_city_container_" + i).hide().removeClass("d-flex").addClass("inactive_segment");
                $("input, select", $("#multi_city_container_" + i)).attr("disabled", "disabled");
              } else {
                $("#multi_city_container_" + i).show().addClass("d-flex").removeClass("inactive_segment");
                $("input, select", $("#multi_city_container_" + i)).removeAttr("disabled");
              }
            }
          })());
  }
  validate_roundway_dates(),
    $('#from, #to, [name="trip_type"]').on("change, blur", function () {}),
    $("#flight_form").attr("autocomplete", "off"),
    $("#flight_fare_calendar").on("click", function (t) {
      t.preventDefault();
      var e = {};
      (e.from = $("#from").val()),
        (e.to = $("#to").val()),
        (e.depature = $("#flight_datepicker1").val()),
        (e.trip_type = "oneway"),
        (e.adult = $("#OWT_adult").val());
      var a =
        app_base_url +
        "flight/pre_calendar_fare_search?" +
        $.param(e);
      window.open(a);
    }),
    $("#flight-form-submit").on("click", function (t) {
      var e = $('[name="trip_type"]:checked').val();
      if ("oneway" == e || "circle" == e) {
        if ($("#flight_form #from").val() == $("#flight_form #to").val())
          return (
            show_alert_content(
              "From location and To location cannot be same.",
              "#flight-alert-box"
            ),
            t.preventDefault(),
            ""
          );
        $("input,checkbox,radio,select", "#multi_way_fieldset").attr(
          "disabled",
          "disabled"
        );
      } else {
        var a = $("#m_from1").val(),
          i = $("#m_to1").val(),
          l = $("#m_from2").val(),
          n = $("#m_to2").val();
        $("#m_from3").val(),
          $("#m_to3").val(),
          $("#m_from4").val(),
          $("#m_to4").val(),
          $("#m_from5").val(),
          $("#m_to5").val();
        if (a == i || l == n)
          return (
            show_alert_content(
              "From location and To location can not be same.",
              "#flight-alert-box"
            ),
            t.preventDefault(),
            ""
          );
        $("input,checkbox,radio,select", "#onw_rndw_fieldset").attr(
          "disabled",
          "disabled"
        );
      }
      var c = parseInt($("#OWT_adult").val()),
        r = (parseInt($("#OWT_child").val()), parseInt($("#OWT_infant").val())),
        s = "";
      r > 0 &&
        c < r &&
        (t.preventDefault(), (s = "1 Infant Per Adult Allowed")),
        show_alert_content(s);
    }),
    $('[name="trip_type"]').on("change", function () {
      t(this.value);
    }),
    t($('[name="trip_type"]:checked').val());
  $("#from").val(), $("#to").val();
  ($(".fromflight, .departflight")
    .catcomplete({
      open: function (t, e) {
        $(".ui-autocomplete").off("menufocus hover mouseover mouseenter");
      },
      source: function (t, e) {
        var a = t.term;
        a in cache
          ? e(cache[a])
          : $.getJSON(
              app_base_url + "ajax/get_airport_code_list",
              t,
              function (t, i, l) {
                
                1 == $.isEmptyObject(t) && 0 == $.isEmptyObject(cache[""])
                  ? (t = cache[""])
                  : ((cache[a] = t), e(cache[a]));
              }
            );
      },
      minLength: 0,
      autoFocus: !1,
      select: function (t, e) {
        e.item.label, e.item.category;
        if (
          ("to" == this.id ? e.item.value : "from" == this.id && e.item.value,
          $(this).siblings(".loc_id_holder").val(e.item.id),
          $(this).siblings(".airport_value").html(e.item.airport),
          // $(this).siblings('span.airport_value').text(ui.item.airport);
          $(this).siblings(".airport_loc").val(e.item.airport),
          auto_focus_input(this.id),
          1 == $(this).hasClass("m_arrcity") && "" != e.item.value)
        ) {
          var a = $(this)
            .closest(".multi_city_container")
            .next(".multi_city_container")
            .find(".m_depcity")
            .attr("id");
          "" == $("#" + a).val() &&
            ($("#" + a).val(e.item.value),
            $("#" + a)
              .siblings(".loc_id_holder")
              .val(e.item.id)),
            $("#" + a)
              .siblings(".airport_value")
              .html(e.item.airport),
            // $('#'+next_depcity_id).siblings('span.airport_value').text(ui.item.airport);
            $("#" + a)
              .siblings(".airport_loc")
              .val(e.item.airport);
        }
      },
      change: function (t, e) {
        e.item || $(this).val("");
      },
    })
    .bind("focus", function () {
      $(this).catcomplete("search");
    })
    .catcomplete("instance")._renderItem = function (t, e) {
    var a = highlight_search_text(
      this.term.trim(),
      e.value,
      e.label,
      e.country_code
    );
    if (e.distance != undefined) {
      return $("<li class='custom-auto-complete'>")
        .append(
          '<a class="sub_flt_list">' + '<span class="list_flt_i"><i class="far fa-plane"></i></span>' +
            '<img class="flag_image" src="' +
            e.country_code +
            '">' +
            e.distance +
            " KM " +
            a +'<span class="arprt_name"> '+ e.airport + '</span>'+ 
            "</a>"
        )
        .appendTo(t);
    } else {
      return $("<li class='custom-auto-complete'>")
        .append(
          '<a>' + '<span class="list_flt_i"><i class="far fa-plane"></i></span>' +
            '<img class="flag_image" src="' +
            e.country_code +
            '">' +
            a + '<span class="arprt_name"> '+ e.airport + '</span>'+ 
            "</a>"
        )
        .appendTo(t);
    }
  }),
    ($(".departflight").catcomplete("instance")._renderItem = function (t, e) {
      var a = highlight_search_text(
        this.term.trim(),
        e.value,
        e.label,
        e.country_code
      );

      if (e.distance != undefined) {
        return $("<li class='custom-auto-complete'>")
          .append(
            '<a class="sub_flt_list">' + '<span class="list_flt_i"><i class="far fa-plane"></i></span>' +
              '<img class="flag_image" src="' +
              e.country_code +
              '">' +
              e.distance +
              " KM " +
              a + '<span class="arprt_name"> '+ e.airport + '</span>'+
              "</a>"
          )
          .appendTo(t);
      } else {
        return $("<li class='custom-auto-complete'>")
          .append(
            '<a>' + '<span class="list_flt_i"><i class="far fa-plane"></i></span>' +
              '<img class="flag_image" src="' +
              e.country_code +
              '">' +
              a +'<span class="arprt_name"> '+ e.airport + '</span>'+
              "</a>"
          )
          .appendTo(t);
      }
      //   return $("<li class='custom-auto-complete'>")
      //     .append(
      //       '<a><img class="flag_image" src="' +
      //         e.country_code +
      //         '">' +
      //         a +
      //         "</a>"
      //     )
      //     .appendTo(t);
    }),
    $(
      "#flight_datepicker2, #OWT_adult, #OWT_child, #OWT_infant, #class, #carrier"
    ).change(function () {
      auto_focus_input(this.id);
    }),
    // Update traveller count when adult/child/infant inputs change
    $("#OWT_adult, #OWT_child, #OWT_infant").on('change', function() {
      total_pax_count("flight_form");
    }),
    // Calculate and display total traveller count on page load
    setTimeout(function() {
      total_pax_count("flight_form");
    }, 100),
    $(".choose_airline_class").click(function () {
      var t = $(this).text(),
        e = $(this).data("airline_class");
      
      // Remove active class from all items
      $(".choose_airline_class").removeClass("active");
      
      // Add active class to clicked item
      $(this).addClass("active");
      
      $("#class").val(e),
        "" == e && (t = "Class"),
        $("#choosen_airline_class, #class_select").empty().text(t),
        1 == $(".class_advance_div").hasClass("fadeinn") &&
          $(".class_advance_div").removeClass("fadeinn");
    }),
    // Airline search filter functionality
    $(document).on("input", "#airline_search_filter", function() {
      var searchTerm = $(this).val().toLowerCase().trim();
      var $airlineItems = $(".choose_preferred_airline");
      var $noResults = $(".no-results-message");
      var $container = $("#airline_list_container");
      var visibleCount = 0;
      
      if (searchTerm === "") {
        $airlineItems.show();
        $noResults.hide();
        $container.show();
        return;
      }
      
      $airlineItems.each(function() {
        var airlineText = $(this).text().toLowerCase();
        if (airlineText.indexOf(searchTerm) !== -1) {
          $(this).show();
          visibleCount++;
        } else {
          $(this).hide();
        }
      });
      
      if (visibleCount === 0) {
        $noResults.show();
        $container.hide();
      } else {
        $noResults.hide();
        $container.show();
      }
    });
    
    // Prevent search input from closing dropdown
    $(document).on("click", "#airline_search_filter, .airline-search-wrapper", function(e) {
      e.stopPropagation();
    });
    
    // Clear search when dropdown closes
    $(document).on("click", ".alladvnce", function() {
      var $dropdown = $(this).find(".preferred_airlines_advance_div");
      setTimeout(function() {
        if (!$dropdown.hasClass("fadeinn")) {
          $("#airline_search_filter").val("").trigger("input");
        }
      }, 300);
    });
    
    $(".choose_preferred_airline").click(function () {
      var t = $(this).text(),
        e = $(this).data("airline_code");
      
      // Remove active class from all items
      $(".choose_preferred_airline").removeClass("active");
      
      // Add active class to clicked item
      $(this).addClass("active");
      
      $("#carrier").val(e),
        "" == t && (t = "Preferred Airline"),
        $("#choosen_preferred_airline").empty().text(t),
        1 == $(".preferred_airlines_advance_div").hasClass("fadeinn") &&
          $(".preferred_airlines_advance_div").removeClass("fadeinn");
    }),
    $(".choose_special_fare").click(function () {
      var t = $(this).text(),
        e = $(this).data("special_fare");
      
      // Remove active class from all items
      $(".choose_special_fare").removeClass("active");
      
      // Add active class to clicked item
      $(this).addClass("active");
      
      $("#special_fare").val(e),
        "" == e && (t = "Special Fares (Optional)"),
        $("#choosen_special_fare").empty().text(t),
        1 == $(".special_fares_advance_div").hasClass("fadeinn") &&
          $(".special_fares_advance_div").removeClass("fadeinn");
    }),
    validate_segment_dates();
});
var max_multicity_segments = $("#max_multicity_segments").val(),
  min_multicity_segments = 2,
  pre_segment_count = parseInt($("#multicity_segment_count").val());
function validate_multicity_segments(t) {
  for (var e = parseInt(t) + 1; e <= max_multicity_segments; e++)
    0 == $("#multi_city_container_" + e).is(":visible") &&
      $("input, select", $("#multi_city_container_" + e)).attr(
        "disabled",
        "disabled"
      );
  for (e = parseInt(t); e >= min_multicity_segments; e--)
    1 == $("#multi_city_container_" + e).is(":visible") &&
      $("input, select", $("#multi_city_container_" + e)).removeAttr(
        "disabled"
      );
}
function toggle_add_remove_segments(t) {
  t >= max_multicity_segments
    ? $("#add_city").hide()
    : toggle_add_city_button();
}
function toggle_add_city_button() {
  "multicity" == $('[name="trip_type"]:checked').val()
    ? $("#add_city").show()
    : $("#add_city").hide();
}
function validate_segment_dates() {
  $(".multi_city_container").each(function () {
    var t = $(".m_depature_date", this).attr("id"),
      e = $(this)
        .next(".multi_city_container")
        .find(".m_depature_date")
        .attr("id");
    auto_set_dates($("#" + t).datepicker("getDate"), e, "minDate", 0);
  });
}
function validate_roundway_dates() {
  auto_set_dates(
    $("#flight_datepicker1").datepicker("getDate"),
    "flight_datepicker2",
    "minDate",
    0
  );
}

function catcomplete_new(t) {
  $(t)
    .catcomplete({
      open: function (t, e) {
        $(".ui-autocomplete").off("menufocus hover mouseover mouseenter");
      },
      source: function (t, e) {
        var a = t.term;
        a in cache
          ? e(cache[a])
          : $.getJSON(
              app_base_url + "ajax/get_airport_code_list",
              t,
              function (t, i, l) {
                1 == $.isEmptyObject(t) && 0 == $.isEmptyObject(cache[""])
                  ? (t = cache[""])
                  : ((cache[a] = t), e(cache[a]));
              }
            );
      },
      minLength: 0,
      autoFocus: !1,
      select: function (t, e) {
        e.item.label, e.item.category;
        if (
          ("to" == this.id
            ? (to_airport = e.item.value)
            : "from" == this.id && (from_airport = e.item.value),
          $(this).siblings(".loc_id_holder").val(e.item.id),
          $(this).siblings(".airport_value").html(e.item.airport),
          $(this).siblings(".airport_loc").val(e.item.airport),
          auto_focus_input(this.id),
          1 == $(this).hasClass("m_arrcity") && "" != e.item.value)
        ) {
          var a = $(this)
            .closest(".multi_city_container")
            .next(".multi_city_container")
            .find(".m_depcity")
            .attr("id");
          "" == $("#" + a).val() &&
            ($("#" + a).val(e.item.value),
            $("#" + a)
              .siblings(".loc_id_holder")
              .val(e.item.id)),
            $("#" + a)
              .siblings(".airport_value")
              .html(e.item.airport),
            // $('#'+next_depcity_id).siblings('span.airport_value').text(ui.item.airport);
            $("#" + a)
              .siblings(".airport_loc")
              .val(e.item.airport);
        }
      },
      change: function (t, e) {
        e.item || $(this).val("");
      },
    })
    .bind("focus", function () {
      $(this).catcomplete("search");
    })
    .catcomplete("instance")._renderItem = function (t, e) {
    var a = highlight_search_text(
      this.term.trim(),
      e.value,
      e.label,
      e.country_code
    );
    if(e.distance!=undefined){
      return $("<li class='custom-auto-complete'>")
      .append(
        '<a><img class="flag_image" src="' + e.country_code + '">' +e.distance+'KM '+ a + '<span> '+ e.airport + "</span></a>"
      )
      .appendTo(t);
    }else{
      return $("<li class='custom-auto-complete'>")
      .append(
        '<a><img class="flag_image" src="' + e.country_code + '">' + a + '<span> '+ e.airport + "</span></a>"
      )
      .appendTo(t);
    }
   
  };
}
validate_multicity_segments(pre_segment_count),
  toggle_add_remove_segments(pre_segment_count),
  $("#add_city").click(function (t) {
    t.preventDefault();
    var e = parseInt($("#multicity_segment_count").val());
    if ((toggle_add_remove_segments((e += 1)), e <= max_multicity_segments)) {
      $(".inactive_segment").first().removeClass("inactive_segment").addClass("d-flex"),
        $("#multicity_segment_count").val(e);
      for (var a = 1; a <= e; a++)
        0 == $("#multi_city_container_" + a).hasClass("inactive_segment") &&
          $("#multi_city_container_" + a).show().addClass("d-flex");
      validate_multicity_segments(e), validate_segment_dates();
    }
    $(".m_depcity").each(function () {
      if (1 == $(this).is(":visible") && "" == $(this).val()) {
        var t = $(this)
            .closest(".multi_city_container")
            .prev(".multi_city_container"),
          e = t.find(".m_arrcity").val(),
          a = t.find("input[name='to_loc_id[]']").val();
        "" != e && ($(this).val(e), $(this).siblings(".loc_id_holder").val(a));
      }
    });
  }),
  $(".remove_city").click(function (t) {
    t.preventDefault();
    var e = parseInt($("#multicity_segment_count").val());
    if ((toggle_add_remove_segments(e - 1), e > min_multicity_segments)) {
      var a = $(this).closest(".multi_city_container");
      a.hide().removeClass("d-flex").addClass("inactive_segment"),
        $("input, select", a)
          .val("")
          .attr("disabled", "disabled"),
        a.insertAfter($(".multi_city_container", "#multi_way_fieldset").last()),
        (e -= 1),
        $("#multicity_segment_count").val(e),
        validate_multicity_segments(e),
        validate_segment_dates();
    }
  }),
  $(".m_depature_date").change(function () {
    validate_segment_dates();
  }),
  $("#flight_datepicker1, #flight_datepicker2").change(function () {
    validate_roundway_dates();
  });