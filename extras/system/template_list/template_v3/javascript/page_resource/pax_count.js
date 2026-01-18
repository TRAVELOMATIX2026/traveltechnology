function activites_total_pax_count_old(t) {
    if ("" != t) {
        var e = $("input.pax_count_value", "form#" + t + " div.pax_count_div").map((function() {
                if ("" != this.value) return parseInt(this.value)
            })).get(),
            o = 0;
        $.each(e, (function() {
            o += this
        })), $("#total_pax_count").val(o)
    }
}

function activites_total_pax_count(t) {
    if ("" != t) {
        var e = $("input.pax_count_value", "form#" + t + " div.pax_count_div").map((function() {
                if ("" != this.value) return parseInt(this.value)
            })).get(),
            o = 0;
        $.each(e, (function() {
            o += this
        }));
        parseInt(o) > 1 && "Travellers", $("#total_pax_count").val(o), $("#total_pax").text(o)
    }
}

function setRoomAdultChidWise(t, e, o) {
    $(".rm_1").addClass("hide"), $(".adults_" + e + "-children_" + o).removeClass("hide");
    document.querySelectorAll(".room_typ_dtls").forEach((t => {
        0 === t.querySelectorAll(".adults_" + e + "-children_" + o).length ? t.classList.add("hide") : t.classList.remove("hide")
    }))
}

function total_pax_count(t) {
    if ("" != t) {
        var e = $("input.pax_count_value", "form#" + t + " div.pax_count_div").map((function() {
                if ("" != this.value) return parseInt(this.value)
            })).get(),
            o = 0;
        $.each(e, (function() {
            o += this
        })), o > 1 ? $("#travel_text").text("Travellers") : $("#travel_text").text("Traveller"), $(".total_pax_count", "form#" + t).empty().text(o)
    }
}

function updateTooltip() {
    const t = document.getElementById("book_now");
    t.hasAttribute("disabled") ? t.classList.add("show-tooltip") : t.classList.remove("show-tooltip")
}

function generateRandomAlphanumericCode(t = 10) {
    const e = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let o = "";
    for (let a = 0; a < t; a++) {
        o += e[Math.floor(62 * Math.random())]
    }
    return o
}

function setRoomAttr(t) {
    var e = document.querySelectorAll(".select_multi_rooms");
    document.querySelectorAll(".mr_1");
    e.length > 0 && e.forEach((function(e) {
        e.setAttribute("data-room", t)
    }))
}
$(document).ready((function() {
    $(".advncebtn").click((function() {
        $(this).parent(".togleadvnce").toggleClass("open")
    })), $(".totlall").click((function() {
        $(".roomcount").toggleClass("fadeinn")
    })), $(".totlall, .roomcount").click((function(t) {
        t.stopPropagation()
    })), $(".done1").click((function() {
        $(".roomcount").removeClass("fadeinn")
    })), $(document).click((function() {
        $(".roomcount").removeClass("fadeinn")
    })), $(".alladvnce").click((function(e) {
        e.stopPropagation();
        var $dropdown = $(this).find(".advncedown, .preferred_airlines_advance_div, .class_advance_div, .special_fares_advance_div");
        $(".advncedown, .preferred_airlines_advance_div, .class_advance_div, .special_fares_advance_div").not($dropdown).removeClass("fadeinn");
        $dropdown.toggleClass("fadeinn");
    })), $(".alladvnce, .advncedown, .preferred_airlines_advance_div, .class_advance_div, .special_fares_advance_div").click((function(t) {
        t.stopPropagation()
    })), $(document).click((function() {
        $(".advncedown, .preferred_airlines_advance_div, .class_advance_div, .special_fares_advance_div").removeClass("fadeinn")
    })), $(".btn-number").click((function(t) {
        t.preventDefault(), fieldName = $(this).attr("data-field"), type = $(this).attr("data-type");
        var e = $(this).closest(".pax-count-wrapper"),
            o = $("input[name='" + fieldName + "']", e),
            a = parseInt(o.val());
        isNaN(a) ? o.val(0) : "minus" == type ? (a > o.attr("min") && o.val(a - 1).change(), parseInt(o.val()), o.attr("min")) : "plus" == type && (a < o.attr("max") && o.val(a + 1).change(), parseInt(o.val()), o.attr("max")), manage_infant_count(fieldName), total_pax_count($(this).closest("form").attr("id"))
    })), $(".activities-btn-number").click((function(t) {
        t.preventDefault(), fieldName = $(this).attr("data-field"), type = $(this).attr("data-type");
        var e = $(this).closest(".pax-count-wrapper"),
            o = $("input[name='" + fieldName + "']", e),
            a = parseInt(o.val());
        isNaN(a) ? o.val(0) : "minus" == type ? (a > o.attr("min") && o.val(a - 1).change(), parseInt(o.val()), o.attr("min")) : "plus" == type && (a < o.attr("max") && o.val(a + 1).change(), parseInt(o.val()), o.attr("max"));
        activites_total_pax_count("check_tourgrade")
    })), $(".input-number").focusin((function() {
        $(this).data("oldValue", $(this).val())
    })), $(".input-number").change((function() {
        minValue = parseInt($(this).attr("min")), maxValue = parseInt($(this).attr("max")), valueCurrent = parseInt($(this).val());
        var t = $(this).closest(".pax-count-wrapper");
        name = $(this).attr("name"), valueCurrent >= minValue ? $(".btn-number[data-type='minus'][data-field='" + name + "']", t).removeAttr("disabled") : (alert("Sorry, the minimum value was reached"), $(this).val($(this).data("oldValue"))), valueCurrent <= maxValue ? $(".btn-number[data-type='plus'][data-field='" + name + "']", t).removeAttr("disabled") : (alert("Sorry, the maximum value was reached"), $(this).val($(this).data("oldValue")))
    })), $(".input-number").keydown((function(t) {
        -1 !== $.inArray(t.keyCode, [46, 8, 9, 27, 13, 190]) || 65 == t.keyCode && !0 === t.ctrlKey || t.keyCode >= 35 && t.keyCode <= 39 || (t.shiftKey || t.keyCode < 48 || t.keyCode > 57) && (t.keyCode < 96 || t.keyCode > 105) && t.preventDefault()
    }))
}));
let user_selected_rooms_list = {};
var total_room_amount = 0;

function room_selection_event(t, e, o, a) {
    setRoomAdultChidWise(t, e, o), setRoomAttr(t);
    validate_rooms_guests(t, e, 0, o, 0, user_selected_rooms_list, a)
}

function convertObjectToArray(t) {
    return Object.entries(t).map((([t, e]) => ({
        key: t,
        ...e
    })))
}

function calculateTotalAdultsByRoom(t) {
    return convertObjectToArray(t).reduce(((t, e) => {
        const o = e.room_no,
            a = parseInt(e.adults, 10);
        return t[o] || (t[o] = 0), t[o] += a, t
    }), {})
}

function calculateTotalChildByRoom(t) {
    return convertObjectToArray(t).reduce(((t, e) => {
        const o = e.room_no,
            a = parseInt(e.children, 10);
        return t[o] || (t[o] = 0), t[o] += a, t
    }), {})
}

function validate_rooms_guests(t, e, o, a, s, r, l = 0) {
    let n = !0;
    var i = 0,
        c = 0;
    if (Object.keys(r).length > 0) {
        const o = calculateTotalAdultsByRoom(r);
        Object.keys(o).length > 0 && (i = "undefined" == o[t] ? 0 : o[t]);
        const s = calculateTotalChildByRoom(r);
        if (Object.keys(s).length > 0 && (c = "undefined" == s[t] ? 0 : s[t]), parseInt(i) == parseInt(e) && parseInt(c) == parseInt(a)) {
            $('button[data-room="' + t + '"]').each((function() {
                $(this).attr("disabled", !0)
            }));
            const e = $("button[selected_index_" + t + "]");
            e.text("Selected"), e.removeAttr("disabled"), n = !1
        } else {
            $('button[data-room="' + t + '"]').each((function() {
                $(this).removeAttr("disabled")
            }));
            $("button[data-room=" + t + "]").text("Select Room"), n = !0
        }
    } else {
        $(".select_multi_rooms").removeAttr("disabled"), $(".all_rooms").addClass("hide"), $(".img-dscptbn-dv.col-md-4").css("top", "50px");
        for (let t = 1; t <= l; t++) {
            const e = $("button[data-room=" + t + "]");
            e.text("Select Room"), e.removeAttr("selected_index_" + t)
        }
        n = !0
    }
    if (parseInt(Object.keys(r).length) == parseInt(l)) {
        setFormData();
        let t = [];
        if (Object.keys(r).length > 0 && Object.entries(r).forEach((function([e, o]) {
                t.push(o.ratekey)
            })), t.length > 0) {
            var d = t.join(",");
            $('input[name="RateKey"]').val(d), $("#book_now").removeAttr("disabled")
        }
    } else $("#book_now").attr("disabled", !0);
    return n
}

function remove_rooms(t) {
    total_room_amount = 0;
    total_tax_amount = 0;
    var e = $(t).attr("data-roomId"),
        o = $(t).attr("data-id");
    if ($("button[selected_index_" + e + "]").each((function() {
            $(this).attr("selected_index_" + e).toString() === o.toString() && (delete user_selected_rooms_list[o], $("#rooms_sec_" + o).remove(), $(this).text("Select Room"), $(this).removeAttr("selected_index_" + e), $(".select_multi_rooms").removeAttr("disabled"), $(".room_count_btn").each((function() {
                parseInt($(this).attr("data-rooms")) == parseInt(e) ? (room_selection_event($(this).attr("data-rooms"), $(this).attr("data-user_adults"), $(this).attr("data-user_child"), $(this).attr("data-totalrooms_count")), $(this).addClass("active")) : $(this).removeClass("active")
            })))
        })), Object.keys(user_selected_rooms_list).length > 0) {
        var a = "",
            s = "";
        Object.entries(user_selected_rooms_list).forEach((function([t, e]) {
            total_room_amount += parseFloat(e.price), total_tax_amount += parseFloat(e.tax), s = e.currency, a += '<div class="rooms_sec" id="rooms_sec_' + t + '"><h4>' + e.board + "</h4><p>" + e.currency + " " + e.price + "</p><span>+ " + e.tax + ' taxes & fees</span><div class="room_sec_clse" onclick="remove_rooms(this)" data-id="' + t + '" data-roomId="' + e.room_no + '"><i class="fal fa-times"></i></div></div>'
        })), a += '<div class="rooms_sec tot_room_amt"><form method="POST" id="selected_rooms_form"><h5>Total Amount:</h5><span>' + s + " " + total_room_amount.toFixed(2) + '</span><p> + '+ s +" "+ total_tax_amount.toFixed(2) +' taxes & fees</p><button class="btn"  disabled="disabled" id="book_now" data-tooltip="Please select all your rooms!" type="submit">Book Now</button></form><div class="room_sec_clse" onclick="remove_rooms_all(this)"><i class="fal fa-times"></i></div></div>', $("#all_selected_rooms").html(a)
    } else $(".all_rooms").addClass("hide"), $(".img-dscptbn-dv.col-md-4").css("top", "50px")
}

function remove_rooms_all(t) {
    const e = $(".room_count_btn.active"),
        o = e.data("rooms");
    var a = e.data("user_adults"),
        s = e.data("user_child"),
        r = e.data("totalrooms_count");
    Object.entries(user_selected_rooms_list).forEach((function([t, e]) {
        delete user_selected_rooms_list[t]
    })), room_selection_event(o, a, s, r)
}

function setFormData() {
    var t = document.getElementById("hidden_rooms_form"),
        e = document.getElementById("selected_rooms_form"),
        o = $("input[name=booking_url]").val();
    "undefind" != o && "" != o || (o = "#"), e.action = o, e.querySelectorAll("input").forEach((function(t) {
        e.removeChild(t)
    }));
    for (var a = 0; a < t.elements.length; a++) {
        var s = t.elements[a];
        if ("input" === s.tagName.toLowerCase()) {
            var r = document.createElement("input");
            r.type = s.type, r.name = s.name, r.value = s.value, e.appendChild(r)
        }
    }
}
$(".room_count_btn").click((function(t) {
    t.preventDefault(), room_selection_event($(this).attr("data-rooms"), $(this).attr("data-user_adults"), $(this).attr("data-user_child"), $(this).attr("data-totalrooms_count"))
})), $(".select_multi_rooms").click((function(t) {
    t.preventDefault();
    const e = $(".room_count_btn.active"),
        o = e.data("rooms");
    $(this).attr("data-room");
    var a = $(this).attr("data-totalrooms"),
        s = $(this).attr("data-guests"),
        r = $(this).attr("data-board"),
        l = $(this).attr("data-price"),
        n = $(this).attr("data-currency"),
        i = $(this).attr("data-tax"),
        c = $(this).attr("data-ratekey"),
        d = $(this).attr("selected_index_" + o),
        m = e.data("user_adults"),
        u = e.data("user_child");
    const _ = s.split("_");
    var h = parseInt(_[1]),
        v = parseInt(_[2]),
        p = $(this).text();
    if (total_room_amount = 0, $(this).text("Select Room" === p ? "Selected" : "Select Room"), "Selected" === p)
        if (delete user_selected_rooms_list[d], $("#rooms_sec_" + d).remove(), $(this).removeAttr("selected_index_" + o), Object.keys(user_selected_rooms_list).length > 0) {
            var f = "";
            var total_tax_amount = 0;
            Object.entries(user_selected_rooms_list).forEach((function([t, e]) {
                total_room_amount += parseFloat(e.price),total_tax_amount += parseFloat(e.tax), f += '<div class="rooms_sec" id="rooms_sec_' + t + '"><h4>' + e.board + "</h4><p>" + e.currency + " " + e.price + "</p><span>+ " + e.tax + ' taxes & fees</span><div class="room_sec_clse" onclick="remove_rooms(this)" data-id="' + t + '" data-roomId="' + e.room_no + '"><i class="fal fa-times"></i></div></div>'
            })), f += '<div class="rooms_sec tot_room_amt"><form method="POST" id="selected_rooms_form"><h5>Total Amount:</h5><span>' + n + " " + total_room_amount.toFixed(2) +'</span><p> + '+ n +" "+ total_tax_amount.toFixed(2) +' taxes & fees</p><button class="btn" disabled="disabled" id="book_now" data-tooltip="Please select all your rooms!" type="submit">Book Now</button></form><div class="room_sec_clse" onclick="remove_rooms_all(this)"><i class="fal fa-times"></i></div></div>', $("#all_selected_rooms").html(f), $('button[data-room="' + o + '"]').each((function() {
                $(this).removeAttr("disabled")
            }))
        } else $(".select_multi_rooms").removeAttr("disabled"), $(".all_rooms").addClass("hide"), $(".img-dscptbn-dv.col-md-4").css("top", "50px");
    else if (!0 === validate_rooms_guests(o, m, h, u, v, user_selected_rooms_list, a)) {
        $("#all_selected_rooms").empty();
        var b = {
                room_no: o,
                adults: h,
                children: v,
                board: r,
                price: l,
                currency: n,
                tax: i,
                ratekey: c
            },
            y = generateRandomAlphanumericCode();
        if (user_selected_rooms_list[y] = b, $(this).attr("selected_index_" + o, y), Object.keys(user_selected_rooms_list).length > 0) {
            $(".all_rooms").removeClass("hide"), $(".img-dscptbn-dv.col-md-4").css("top", "140px");
            f = "";
            var total_tax_amount = 0;
            Object.entries(user_selected_rooms_list).forEach((function([t, e]) {
                total_room_amount += parseFloat(e.price),total_tax_amount += parseFloat(e.tax), f += '<div class="rooms_sec" id="rooms_sec_' + t + '"><h4>' + e.board + "</h4><p>" + e.currency + " " + e.price + "</p><span>+ " + e.tax + ' taxes & fees</span><div class="room_sec_clse" onclick="remove_rooms(this)" data-id="' + t + '" data-roomId="' + e.room_no + '"><i class="fal fa-times"></i></div></div>'
            })), f += '<div class="rooms_sec tot_room_amt"><form method="POST" id="selected_rooms_form"><h5>Total Amount:</h5><span>' + n + " " + total_room_amount.toFixed(2) + '</span><p> + '+ n +" "+ total_tax_amount.toFixed(2) +' taxes & fees</p><button class="btn" disabled="disabled" data-tooltip="Please select all your rooms!" id="book_now" type="submit">Book Now</button></form><div class="room_sec_clse" onclick="remove_rooms_all(this)"><i class="fal fa-times"></i></div></div>', $("#all_selected_rooms").html(f), parseInt(Object.keys(user_selected_rooms_list).length) === parseInt(a) && $(".select_multi_rooms").attr("disabled", !0), validate_rooms_guests(o, m, h, v, v, user_selected_rooms_list, a);
            $("button[selected_index_" + o + "]").removeAttr("disabled");
            var x = parseInt(o) + 1;
            $(".room_count_btn").each((function() {
                (console.log($(this).attr("data-rooms")), parseInt(x) <= parseInt(a)) ? parseInt($(this).attr("data-rooms")) == parseInt(x) ? (room_selection_event($(this).attr("data-rooms"), $(this).attr("data-user_adults"), $(this).attr("data-user_child"), $(this).attr("data-totalrooms_count")), $(this).addClass("active")) : $(this).removeClass("active"): parseInt($(this).attr("data-rooms")) == parseInt(o) ? (room_selection_event($(this).attr("data-rooms"), $(this).attr("data-user_adults"), $(this).attr("data-user_child"), $(this).attr("data-totalrooms_count")), $(this).addClass("active")) : $(this).removeClass("active")
            }))
        } else $(".select_multi_rooms").removeAttr("disabled"), $(".all_rooms").addClass("hide"), $(".img-dscptbn-dv.col-md-4").css("top", "50px")
    }
}));