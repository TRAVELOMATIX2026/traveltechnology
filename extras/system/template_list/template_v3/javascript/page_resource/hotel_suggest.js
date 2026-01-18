$(document).ready((function() {
    function e(e) {
        e >= 3 ? $(".add_rooms").hide() : $(".add_rooms").show(), e <= 1 ? $(".remove_rooms").hide() : $(".remove_rooms").show()
    }

    function t(e) {
        for (var t = parseInt(e) + 1; t <= 3; t++) $("input, select", $("#room-wrapper-" + t)).attr("disabled", "disabled");
        for (t = parseInt(e); t >= 1; t--) $("input, select", $("#room-wrapper-" + t)).removeAttr("disabled")
    }
    $(".add_rooms").on("click", (function(o) {
        o.preventDefault();
        var n = parseInt($("#room-count").val());
        if (e(n += 1), n <= 3) {
            $("#room-count").val(n);
            for (var r = 1; r <= n; r++) $("#room-wrapper-" + r).show();
            t(n)
        }
        a()
    })), $(".remove_rooms").on("click", (function(o) {
        o.preventDefault();
        var n = parseInt($("#room-count").val());
        e(n - 1), n > 1 && ($("#room-wrapper-" + n).hide(), n -= 1, $("#room-count").val(n), t(n)), a()
    }));
    var o = $("#pri_visible_room").val();

    function a() {
        for (var e = $("#room-count").val(), t = 0, o = 0; o < parseInt(e); o++) {
            var a = $("#adult_text_" + o).val();
            0 == parseInt(a) && $("#adult_text_" + o).val(1)
        }
        $('#hotel_search [name="adult[]"]').not(":disabled").each((function() {
            t += parseInt(this.value)
        }));
        var n = 0;
        $('#hotel_search [name="child[]"]').not(":disabled").each((function() {
            n += parseInt(this.value)
        }));
        var r = "";
        r += t, r += t > 1 ? " Adults," : " Adult,", n > 0 && (r += n, r += n > 1 ? " Children," : " Child,"), r += e, r += e > 1 ? " Rooms" : " Room", $("#hotel-pax-summary").text(r)
    }
    e(o), t(o), a(), $("#hotel_search .input-number").on("change blur", (function() {
        a()
    })), $('#hotel_search input[name="child[]"]').on("change", (function() {
        var e = $(this).closest(".oneroom"),
            t = parseInt(this.value);
        if (t < 1) $(".chilagediv", e).hide();
        else {
            $(".chilagediv", e).show();
            for (var o = 1; o <= t; o++) $(".child-age-wrapper-" + o, e).show();
            for (o = t + 1; o <= 2; o++) $(".child-age-wrapper-" + o, e).hide()
        }
    })), 
    
    // Toggle guest dropdown with smooth animation
    $(".hotel_total").on("click", function(e) {
        e.stopPropagation();
        var $roomcount = $(this).find(".roomcount");
        var $trigger = $(this);
        
        $roomcount.toggleClass("active");
        $trigger.toggleClass("active");
    }),
    
    // Close dropdown when clicking Done
    $(".done1").on("click", function(e) {
        e.preventDefault();
        $(".roomcount").removeClass("active");
        $(".hotel_total").removeClass("active");
    }),
    
    // Close dropdown when clicking outside
    $(document).on("click", function(e) {
        if (!$(e.target).closest(".hotel_total").length && !$(e.target).closest(".roomcount").length) {
            $(".roomcount").removeClass("active");
            $(".hotel_total").removeClass("active");
        }
    })
})), $(document).ready((function() {
    var e = {};

    function t(e, t) {
        e = e.split("-");
        var o = new Date(e[2], parseInt(e[1]) - 1, parseInt(e[0]) + t);
        return month = "" + (o.getMonth() + 1), day = "" + o.getDate(), year = o.getFullYear(), month.length < 2 && (month = "0" + month), day.length < 2 && (day = "0" + day), [day, month, year].join("-")
    }
    $(".hotel_city").catcomplete({
        open: function(e, t) {
            $(".ui-autocomplete").off("menufocus hover mouseover mouseenter")
        },
        source: function(t, o) {
            var a = t.term;
            a in e ? o(e[a]) : ($.getJSON(app_base_url + "ajax/get_hotel_city_list", t, (function(t, n, r) {
                e[a] = t, o(t)
            })))
        },
        minLength: 0,
        autoFocus: !1,
        select: function(e, t) {
            t.item.label, t.item.category;
            $(this).siblings(".loc_id_holder").val(t.item.id),$(this).siblings(".hotel_loc_id_holder").val(t.item.tbo_city_id), $("#hotel_checkin").focus()
        },
        change: function(e, t) {
            t.item || $(this).val("")
        }
    }).bind("focus", (function() {
        $(this).catcomplete("search")
    })).catcomplete("instance")._renderItem = function(e, t) {
        var o = highlight_search_text(this.term.trim(), t.value, t.label),
            a = "",
            n = parseInt(t.count);
        if (n > 0) {
            var r = "";
            r = n > 1 ? "Hotels" : "Hotel", a = '<span class="hotel_cnt">(' + parseInt(t.count) + " " + r + ")</span>"
        }
        return $("<li class='custom-auto-complete'>").append('<a> <span class="fal fa-map-marker-alt"></span> ' + o + " " + a + "</a>").appendTo(e)
    }, $("#hotel_checkin, #hotel_checkout").on("change", (function(e) {
        e.preventDefault();
        var o = $("#hotel_checkin").val(),
            a = $("#hotel_checkout").val();
        if ("" != o && "" != a) {
            var n = parseInt(get_day_difference($("#hotel_checkin").datepicker("getDate"), $("#hotel_checkout").datepicker("getDate")));
            parseInt(n) > 10 || n < -10 ? (n = 10, $("#hotel_checkout").val(t(o, n))) : n < 0 ? (n *= -1, $("#hotel_checkout").val(t(o, n))) : 0 == n && (n = 1, $("#hotel_checkout").val(t(o, n))), $("#no_of_nights").val(n).trigger("change");
        }
    })), $("#no_of_nights").on("change", (function() {
        var e = $("#hotel_checkin").val(),
            o = parseInt(this.value);
        if ("" != e) {
            var a = t(e, o);
            $("#hotel_checkout").val(a)
        }
    }))
}));