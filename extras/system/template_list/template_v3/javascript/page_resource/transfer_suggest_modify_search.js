$(document).ready(function () {

    function set_flight_cookie_data() {
        var s_params = $('#flight_form').serialize().trim();
        setCookie('flight_search', s_params, 100)
    }
    $('#transfer_from, #transfer_to, [name="transfer_type"]').on('change, blur', function () {
        is_domestic_oneway_search()
    });

    function is_domestic_oneway_search() {}


    $('[name="transfer_type"]').on('change', function () {
        handle_active_transfer_type(this.value)
    });

    function handle_active_transfer_type(_active_transfer_type) {
        if (_active_transfer_type == 'oneway') {
            $('#transfer_datepicker2').attr('disabled', true).removeAttr('required').closest('.date-wrapper').animate({
                'opacity': '.3'
            });
            $('#transfer_datepicker2').val('');
            if ($('#onew-trip').parent('label.wament').hasClass('active') == false) {
                $('#onew-trip').parent('label.wament').addClass('active')
            }
            if ($('#rnd-trip').parent('label.wament').hasClass('active') == true) {
                $('#rnd-trip').parent('label.wament').removeClass('active')
            }
        } else if (_active_transfer_type == 'circle') {
            if ($('#rnd-trip').parent('label.wament').hasClass('active') == false) {
                $('#rnd-trip').parent('label.wament').addClass('active')
            }
            if ($('#onew-trip').parent('label.wament').hasClass('active') == true) {
                $('#onew-trip').parent('label.wament').removeClass('active')
            }
            $('#transfer_datepicker2').removeAttr('disabled').attr('required', 'required').closest('.date-wrapper').animate({
                'opacity': '1'

            }).focus()
        } else if (_active_transfer_type == 'multicity') {
        }
    }
    handle_active_transfer_type($('[name="transfer_type"]:checked').val());
    var cache = {};
    var transfer_from = $('#transfer_from').val();
    var transfer_to = $('#transfer_to').val();
    $(".fromtransfer, .departtransfer").catcomplete({
        source: function (request, response) {
            var term = request.term;
            if (term != ' ' && term != '')//remove autocomplete
            {
                if (term in cache) {
                    response(cache[term]);
                    return
                }
                $.getJSON(app_base_url + "index.php/ajax/get_transfer_code_list", request, function (data, status, xhr) {
                    $(".fromtransfer").removeClass('ui-autocomplete-loading');
                    $(".departtransfer").removeClass('ui-autocomplete-loading');
                    cache[term] = data;
                    response(cache[term])

                })
            }
        },
        minLength: 3,
        autoFocus: true,
        select: function (event, ui) {
            var label = ui.item.label;
            var category = ui.item.category;
            if (this.id == 'transfer_to') {
                to_airport = ui.item.value
            } else if (this.id == 'transfer_from') {
                from_airport = ui.item.value
            }
            $(this).siblings('.loc_id_holder').val(ui.item.id);
            $(this).siblings('.transfer_type').val(ui.item.transfer_type);
            auto_focus_input(this.id)
            auto_focus_input(this.transfer_type)
        },
        change: function (ev, ui) {
            if (!ui.item) {
                $(this).val("")
            }
        }
    }).bind('focus', function () {
        $(this).catcomplete("search")
    }).catcomplete("instance")._renderItem = function (ul, item) {
        var auto_suggest_value = highlight_search_text(this.term.trim(), item.value, item.label);
        var top = 'Top Searches';
        return $("<li class='custom-auto-complete'>").append('<a>' + auto_suggest_value + '</a>').appendTo(ul)
    };
    $(".departtransfer").catcomplete("instance")._renderItem = function (ul, item) {
        var auto_suggest_value = highlight_search_text(this.term.trim(), item.value, item.label);
        return $("<li class='custom-auto-complete'>").append('<a>' + auto_suggest_value + '</a>').appendTo(ul)
    };

    //total_pax_count('trasfer');
    

    /*show age drodown on click of plus*/
    $('.plusValue').click(function () {
        var id = $(this).siblings("input").attr('id');
        if (id == "OWT_transfer_child") {
            var idval = '#transfer_child_ageId';
            var classVal = '.transfer_child_ageId';
            $('.child_age_text').removeClass('hide');
        }
        if (id == "OWT_transfer_adult") {
            var idval = '#transfer_adult_ageId';
            var classVal = '.transfer_adult_ageId';
        }
        
        var value = $(this).siblings("input").val();
        value = parseInt(value)+1;
        // added 
        $(idval + value).removeClass('hide');
        $(classVal + value).removeClass('hide');
        $(idval + value).prop("disabled", false);
        //var value = parseInt(value) + 1; // not useful
    });

    /*hide age drodown on click of minus*/
    $('.minusValue').click(function () {
        var value = $(this).siblings("input").val();
        value = parseInt(value); // useful
        if(value == 1){
         $('.child_age_text').addClass('hide');
        }
        var id = $(this).siblings("input").attr('id');
        if (id == "OWT_transfer_child") {
            var idval = '#transfer_child_ageId';
            var classVal = '.transfer_child_ageId';
        }
        if (id == "OWT_transfer_adult") {
            var idval = '#transfer_adult_ageId';
            var classVal = '.transfer_adult_ageId';
        }
       

        $(idval + value).addClass('hide');
        $(classVal + value).addClass('hide');

        $(idval + value).prop("disabled", true);

    });

    /*//datetime picker
     $("#transfer_datepicker1").change(function () {
        //manage date validation
         auto_set_date_time($("#transfer_datepicker1").val(), "transfer_datepicker2", 'minDate');
    });
    // //if second date is already set then dont run
     if ($("#transfer_datepicker2").val() == '') {
       auto_set_date_time($("#transfer_datepicker1").val(), "transfer_datepicker2", 'minDate');
     }*/

});

