function show_result_pre_loader() {
  var e = setInterval(function () {
    var t = $("#bar"),
      a = parseInt($(".result-pre-loader-container").width()),
      r = (bar_width = parseInt(t.width())),
      n = parseInt(a / 10);
    bar_width >= a
      ? (t.text("Please Wait ... 100%"), clearInterval(e))
      : ((r = bar_width + n), t.width(r), t.text(parseInt(r / 10) + "%"));
  }, 1e3);
}
function hide_result_pre_loader() {
  $(".result-pre-loader-wrapper").hide();
}
function check_newsletter() {
  $(".cus_err_msg").hide(), $(".msgNewsLetterSubsc12").fadeOut();
  var e = app_base_url + "index.php/general/email_subscription",
    t = $("#exampleInputEmail1").val();
  if ("" == t) return $(".msgNewsLetterSubsc12").fadeIn().fadeOut(1e4), !1;
  return /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(t)
    ? ($.ajax({
        url: e,
        data: { subEmail: t },
        method: "POST",
        dataType: "json",
        success: function (e) {
          1 == e.status
            ? ($(".succNewsLetterSubsc").fadeIn().fadeOut(1e4),
              $("#exampleInputEmail1").val(""))
            : 0 == e.status
            ? $(".msgNewsLetterSubsc").fadeIn().fadeOut(1e4)
            : 2 == e.status && $(".msgNewsLetterSubsc1").fadeIn().fadeOut(1e4);
        },
      }),
      !1)
    : ($(".msgNewsLetterSubsc12").fadeIn().fadeOut(1e4), !1);
}
$(document).on(
  "focus",
  "input:not([readonly],[type=submit],[type=button],[type=reset],button)",
  function () {
    $(this).select();
  }
),
  $.widget("custom.catcomplete", $.ui.autocomplete, {
    _create: function () {
      this._super(),
        this.widget().menu(
          "option",
          "items",
          "> :not(.ui-autocomplete-category)"
        );
    },
    _renderMenu: function (e, t) {
      var a = this,
        r = "";
      $.each(t, function (t, n) {
        var s;
        n.category != r &&
          (e.append(
            "<li class='ui-autocomplete-category'>" + n.category + "</li>"
          ),
          (r = n.category)),
          (s = a._renderItemData(e, n)),
          n.category && s.attr("aria-label", n.category + " : " + n.label);
      });
    },
    _renderItem: function (e, t) {
var a ='';
        if (t.distance != undefined) {
            return $("<li class='custom-auto-complete'>")
              .append(
                '<a class="sub_flt_list">' + '<span class="list_flt_i"><i class="far fa-plane"></i></span>' +
                  t.distance +
                  " KM " +
                  t.label +'<span class="arprt_name"> '+ t.airport + '</span>'+ 
                  '<img class="flag_image" src="' +
                  t.country_code +
                  '">' +
                  "</a>"
              )
              .appendTo(e);
          } else {
            return $("<li class='custom-auto-complete'>")
              .append(
                '<a>' + '<span class="list_flt_i"><i class="far fa-plane"></i></span>' +
                  t.label + '<span class="arprt_name"> '+ t.airport + '</span>'+ 
                  '<img class="flag_image" src="' +
                  t.country_code +
                  '">' + 
                  "</a>"
              )
              .appendTo(e);
          }
    //   return $("<li class='custom-auto-complete'>")
    //     .append(
    //       '<a><img class="flag_image" src="' +
    //         t.country_code +
    //         '">' +
    //         '<span class="list_flt_i"><i class="far fa-plane"></i></span>' +
    //         t.label +
    //         "</a>"
    //     )
    //     .appendTo(e);
    },
  }),
  $(document).on("click", ".btn-offline-pay", function () {
    var e = $("#offline_form").serializeArray();
    $.post(
      app_base_url + "index.php/general/offline_payment",
      { data: e },
      function (e) {
        window.location.href =
          app_base_url + "index.php/general/offline_approve/" + e;
      },
      "json"
    );
  });
let mybutton = document.getElementById("backtotop");
window.onscroll = function () {
  scrollFunction();
};
function scrollFunction() {
  if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 20) {
    $("#backtotop").fadeIn();
  } else {
    $("#backtotop").fadeOut();
  }
}
function topFunction() {
  $("html, body").animate({ scrollTop: 0 }, 700);
}
function toggleIcon(e) {
  $(e.target)
    .prev(".card-header")
    .find(".more-less")
    .toggleClass("glyphicon-plus glyphicon-minus");
}
$(".card-group").on("hidden.bs.collapse", toggleIcon);
$(".card-group").on("shown.bs.collapse", toggleIcon);
$(".promo_crd_wrapper").hide();
$(".view_cpn a").click(function () {
  $(".promo_crd_wrapper").fadeToggle();
});
$(".promo_remove1").hide();
$(".promo_remove2").hide();
$(".promo1").change(function () {
  if ($(this).is(":checked")) {
    $(".promo_remove1").show();
    $(".promo_remove2").hide();
  }
});
$(".promo2").change(function () {
  if ($(this).is(":checked")) {
    $(".promo_remove2").show();
    $(".promo_remove1").hide();
  }
});
$(".promo_remove1").click(function () {
  $(".promo1").prop("checked", false);
  $(this).hide();
});
$(".promo_remove2").click(function () {
  $(".promo2").prop("checked", false);
  $(this).hide();
});


