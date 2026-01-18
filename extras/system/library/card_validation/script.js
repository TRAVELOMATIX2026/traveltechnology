$(function() {

    // var owner = $('#owner');
    var cardNumber = $('#cardNo');
    var cardNumberField = $('#card-number-field');
    var CVV = $("#cvv");
    // var mastercard = $("#mastercard");
    var confirmButton = $('#continue_booking_button');
    // var visa = $("#visa");
    // var amex = $("#amex");

    // Use the payform library to format and validate
    // the payment fields.

    cardNumber.payform('formatCardNumber');
    CVV.payform('formatCardCVC');


    cardNumber.keyup(function() { console.log('a',cardNumber.val());

        // amex.removeClass('transparent');
        // visa.removeClass('transparent');
        // mastercard.removeClass('transparent');

        if ($.payform.validateCardNumber(cardNumber.val()) == false) {
            cardNumberField.addClass('has-error');
        } else {
            cardNumberField.removeClass('has-error');
            cardNumberField.addClass('has-success');
        }
        console.log("card_type:",$.payform.parseCardType(cardNumber.val()));
        $("#card_type").val($.payform.parseCardType(cardNumber.val()));
        $("#cardScheme").val(cc_brand_id(cardNumber.val()));
        /*if ($.payform.parseCardType(cardNumber.val()) == 'visa') {
            $("#card_type").val($.payform.parseCardType(cardNumber.val()));
            // mastercard.addClass('transparent');
            // amex.addClass('transparent');
        } else if ($.payform.parseCardType(cardNumber.val()) == 'amex') {
            $("#card_type").val($.payform.parseCardType(cardNumber.val()));
            // mastercard.addClass('transparent');
            // visa.addClass('transparent');
        } else if ($.payform.parseCardType(cardNumber.val()) == 'mastercard') {
            $("#card_type").val($.payform.parseCardType(cardNumber.val()));
            // amex.addClass('transparent');
            // visa.addClass('transparent');
        }*/
    });

    confirmButton.click(function(e) {

        e.preventDefault();

        var isCardValid = $.payform.validateCardNumber(cardNumber.val());
        var isCvvValid = $.payform.validateCardCVC(CVV.val());

        if (!isCardValid) {
            alert("Wrong card number");
            return false;
        } else if (!isCvvValid) {
            alert("Wrong CVV");
            return false;
        } else {
            return true;
            // Everything is correct. Add your form submission code here.
            //alert("Everything is correct");
        }
    });

    
});
function cc_brand_id(cur_val) {
    var sel_brand;
  
    // the regular expressions check for possible matches as you type, hence the OR operators based on the number of chars
    // regexp string length {0} provided for soonest detection of beginning of the card numbers this way it could be used for BIN CODE detection also
  
    //JCB
    jcb_regex = new RegExp('^(?:2131|1800|35)[0-9]{0,}$'); //2131, 1800, 35 (3528-3589)
    // American Express
    amex_regex = new RegExp('^3[47][0-9]{0,}$'); //34, 37
    // Diners Club
    diners_regex = new RegExp('^3(?:0[0-59]{1}|[689])[0-9]{0,}$'); //300-305, 309, 36, 38-39
    // Visa
    visa_regex = new RegExp('^4[0-9]{0,}$'); //4
    // MasterCard
    mastercard_regex = new RegExp(
        '^(5[1-5]|222[1-9]|22[3-9]|2[3-6]|27[01]|2720)[0-9]{0,}$'); //2221-2720, 51-55
    // Maestro
    maestro_regex = new RegExp('^(5[06789]|6)[0-9]{0,}$'); //always growing in the range: 60-69, started with / not something else
    //Discover
    discover_regex = new RegExp(
        '^(6011|65|64|62212[6-9]|6221[3-9]|622[2-8]|6229[01]|62292[0-5])[0-9]{0,}$');
    ////6011, 622126-622925, 64, 65
  
    // get rid of anything but numbers
    cur_val = cur_val.replace(/\D/g, '');
  
    // checks per each, as their could be multiple hits
    //fix: ordering matter in detection, otherwise can give false results in rare cases
    if (cur_val.match(jcb_regex)) {
      sel_brand = "JCB";
    } else if (cur_val.match(amex_regex)) {
      sel_brand = "AMEX";
    } else if (cur_val.match(diners_regex)) {
      sel_brand = "Diners";
    } else if (cur_val.match(visa_regex)) {
      sel_brand = "Visa";
    } else if (cur_val.match(mastercard_regex)) {
      sel_brand = "Mastercard";
    } else if (cur_val.match(discover_regex)) {
      sel_brand = "Discover";
    } else if (cur_val.match(maestro_regex)) {
      sel_brand = "Maestro";
    } else {
      sel_brand = "Unknown";
    }
  
    return sel_brand;
  }
  $(window).on('load', function() { 
      $("#challengeWindowSize").val("03");
      $("#browserJavaEnabled").val(true);
      $("#browserScreenHeight").val(screen.height);
      $("#browserScreenWidth").val(screen.width);
      $("#browserLanguage").val(navigator.language);
  });