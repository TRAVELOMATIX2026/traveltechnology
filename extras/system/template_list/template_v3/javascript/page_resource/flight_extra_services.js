$(document).ready(function(){

	// Initialize seat selection - activate first tab and first passenger row
	if($('.seat_segment_map').length > 0) {
		// Activate first tab
		var $firstTab = $('.seat_segment_map').first();
		if($firstTab.length) {
			var targetId = $firstTab.attr('data-bs-target');
			if(targetId && typeof bootstrap !== 'undefined') {
				var $tabPane = $(targetId);
				if($tabPane.length && !$tabPane.hasClass('show')) {
					var tab = new bootstrap.Tab($firstTab[0]);
					tab.show();
				}
			}
			// Activate first segment
			activate_current_seat_segment_map(0);
		}
	}

	//var core_booking_amount = parseFloat($('#total_booking_amount').text());
	var core_booking_amount = parseFloat($('#flight_amount').val());

	// console.log(core_booking_amount);
	//alert(core_booking_amount);

        var booking_amount = parseFloat($('#flight_amount').val());

	var agent_payble_amount = parseFloat($('#agent_payable_amount').text());

        $('.add_extra_service').on('change', function(){

		update_total_booking_amount();

	});

        $('.add_insurance').click(function(){

            $('.add_insurance').val();

             update_total_booking_amount();

	});

        

        

        

	//Remove Baggage

	$('#remove_extra_baggage').on('click', function(){

		$('.choosen_baggage').val('');

		update_total_booking_amount();

	});

	//Remove Meal

	$('#remove_extra_meal').on('click', function(){

		$('.choosen_meal').val('');

		update_total_booking_amount();

	});

	//Remove Seat

	$('#remove_extra_seat').on('click', function(){

		$('.choosen_seat').val('');

		$('.choosen_seat').data('seat_price', '');

		$('.seat_pax_number').empty();

		$('.seat_pax_price').empty();

		

		//change seat icon back to available
		$('.seat_icon_wrapper.seat_selected').removeClass('seat_selected');
		$('.seat_icon_wrapper.seat_selected .seat_icon_available').removeClass('seat_icon_selected');

	

		update_total_booking_amount();

	});

        $('.remove_insurance').on('click', function(){

		$('.add_insurance').val(0);

		update_total_booking_amount();

	});

        

        

        

	/**

	 * Updates Total Booking Amount

	 */

	function update_total_booking_amount()

	{
		//alert('update_total_booking_amount');

		//Baggage

       // var core_booking_amount = parseFloat($('#total_booking_amount').text());
        var core_booking_amount = parseFloat($('#flight_amount').val());
        var agentServicefee = parseFloat($('#agentServicefee').val());
        //alert(core_booking_amount);	    

		var total_baggage_price = 0;

		$('.choosen_baggage').each(function(){

			var baggage_obj = $(this).find('option:selected');

			var baggage_price = parseFloat(baggage_obj.data('choosen-baggage-price'));

			if(isNaN(baggage_price) == true){

				baggage_price = 0;

			}

			total_baggage_price +=baggage_price;

		});

		

		//Meals

		var total_meal_price = 0;

		$('.choosen_meal').each(function(){

			var meal_obj = $(this).find('option:selected');

			var meal_price = parseFloat(meal_obj.data('choosen-meal-price'));

			if(isNaN(meal_price) == true){

				meal_price = 0;

			}

			total_meal_price +=meal_price;

		});

		

		//Seat

		var total_seat_price = 0;

		$('.choosen_seat').each(function(){

			var seat_obj = $(this);

			var seat_price = parseFloat(seat_obj.data('seat_price'));

			if(isNaN(seat_price) == true){

				seat_price = 0;

			}

			total_seat_price +=seat_price;

		});

                

                // Insurance 

              var total_insurance_price = 0;

		// $('.add_insurance').each(function(){

		// 	var insurance_price=parseFloat($(this).val());

  //                       //var insurance_price=parseFloat(100);                      

		// 	if(isNaN(insurance_price) == true){

		// 		insurance_price = 0;

		// 	}

                        

		// 	total_insurance_price +=insurance_price;

  //                     // alert(total_insurance_price);

		// });

		

		total_baggage_price = total_baggage_price.toFixed(2);
		//console.log('total_baggage_price'+total_baggage_price);

		$('#extra_baggage_charge').empty().text(total_baggage_price);
		$('#extra_bagage_fee').val(total_baggage_price);
		

		if(total_baggage_price > 0){

			$('#extra_baggage_charge_label').show();

		} else {

			$('#extra_baggage_charge_label').hide();

		}

		total_meal_price = total_meal_price.toFixed(2);
		//console.log('total_meal_price'+total_meal_price);

		$('#extra_meal_fee').val(total_meal_price);

		$('#extra_meal_charge').empty().text(total_meal_price);

		if(total_meal_price > 0){

			$('#extra_meal_charge_label').show();

		} else {

			$('#extra_meal_charge_label').hide();

		}

		total_seat_price = total_seat_price.toFixed(2);
		

		//console.log('total_seat_price'+total_seat_price);

		$('#extra_seat_charge').empty().text(total_seat_price);
		$('#extra_seat_fee').val(total_seat_price);

		if(total_seat_price > 0){

			$('#extra_seat_charge_label').show();

		} else {

			$('#extra_seat_charge_label').hide();

		}

                

                

                $('.insurance_amount').empty().text(total_insurance_price);

		if(total_insurance_price > 0){

			$('.insurance').show();

		} else {

			$('.insurance').hide();

		}

		// alert(core_booking_amount);

		//Adding extraservice price to booking amount

		//var extra_service_price = total_baggage_price+total_meal_price+total_seat_price+total_insurance_price;

		

		var extra_service_price = parseFloat(total_baggage_price)+parseFloat(total_meal_price)+parseFloat(total_seat_price);

		//console.log('extra_service_price'+extra_service_price);
		//alert(total_meal_price);
		var new_total_booking_amount = (parseFloat(core_booking_amount)+parseFloat(extra_service_price)+parseFloat(agentServicefee));
		
		new_total_booking_amount = parseFloat(new_total_booking_amount);
		
		new_total_booking_amount = new_total_booking_amount.toFixed(2);
		//console.log('new_total_booking_amount'+new_total_booking_amount);

		

		

		var promo_val = $("#promo_code_discount_val").val();

		if($.isNumeric(promo_val)){			

			new_total_booking_amount = new_total_booking_amount - promo_val;		

		}

		var org_convience_fee = $('#org_convience_fee').val();

		//var org_convience_fee = parseFloat($('#convenience_fees_value').text());
		//console.log('org_convience_fee'+org_convience_fee);


		if(org_convience_fee > 0){

			/*new_total_booking_amount = booking_amount+extra_service_price;
			console.log('booking_amount'+booking_amount);*/
			var convience_fee = (new_total_booking_amount*org_convience_fee)/100;
			convience_fee = parseFloat(convience_fee);
			convience_fee = convience_fee.toFixed(2);

			booking_amount = parseFloat(booking_amount);
			booking_amount = booking_amount.toFixed(2);

			new_total_booking_amount = parseFloat(new_total_booking_amount)+parseFloat(convience_fee);
			//console.log('convience_fee'+convience_fee);
			//console.log('new_total_booking_amount+convience_fee'+new_total_booking_amount);
			$('#convenience_fee').val(convience_fee);

			$('.convenience_fees_value').empty().text(convience_fee);

			var grand_total = parseFloat(booking_amount+convience_fee);
			grand_total = grand_total.toFixed(2);

			$('#grandtotal').empty(grand_total).text();

		}
		else
		{
			var convience_fee = $('#convenience_fee').val();;

			new_total_booking_amount = parseFloat(new_total_booking_amount)+parseFloat(convience_fee);

			//var grand_total = parseFloat(booking_amount+convience_fee);
			var grand_total = new_total_booking_amount.toFixed(2);

			$('#grandtotal').empty(grand_total).text();
		}


		new_total_booking_amount = parseFloat(new_total_booking_amount);
		new_total_booking_amount = new_total_booking_amount.toFixed(2);
		//console.log(new_total_booking_amount);

		$('#new_total_booking_amount').val(new_total_booking_amount);

		$('#total_booking_amount').empty().text(new_total_booking_amount);

         $('.total_booking_amount').empty().text(new_total_booking_amount);

               

		

		//in Agent

		

		if($('.grand_total_amount').length){

			$('.grand_total_amount').empty().text(new_total_booking_amount);

		}

		

		if($('#agent_payable_amount').length){

			$('#agent_payable_amount').empty().text(agent_payble_amount+extra_service_price);

		}

	}

	

	$('.extract_pax_name_cls').on('input blur', function(){

		if($(this).val().trim()!= ''){

			var passenegr_index = $(".extract_pax_name_cls").index(this);

			var passenegr_name = '';

			var first_name_index = passenegr_index;

			if($(this).attr('name') == 'last_name[]'){

				first_name_index = (passenegr_index-1);

				passenegr_name += $(".extract_pax_name_cls").eq(passenegr_index-1).val().trim();

				passenegr_name += ' '+$(this).val().trim();

			} else {

				passenegr_name += $(this).val().trim();

				passenegr_name += ' '+$(".extract_pax_name_cls").eq(passenegr_index+1).val().trim();

			}

			first_name_index = (first_name_index/2);

			

			if(passenegr_name !=''){

				$('.bag_pax_name').eq(first_name_index).empty().text(passenegr_name);

				

				$('.meal_pax_name').eq(first_name_index).empty().text(passenegr_name);

				

				$('.meal_pref_pax_name').eq(first_name_index).empty().text(passenegr_name);

				

				$('.seat_pref_pax_name').eq(first_name_index).empty().text(passenegr_name);

				update_seat_pax_name(first_name_index, passenegr_name);

			}

			

		}

	});

	update_first_passenger_name();

	//update passenger name

	function update_first_passenger_name()

	{

		var first_passenger_name = $('.extract_pax_name_cls').eq(0).val().trim()+' '+$('.extract_pax_name_cls').eq(1).val().trim();

		

		if($('.extract_pax_name_cls').eq(0).val().trim() != ''){

			$('.bag_pax_name').eq(0).empty().text(first_passenger_name);

			$('.meal_pax_name').eq(0).empty().text(first_passenger_name);

			$('.meal_pref_pax_name').eq(0).empty().text(first_passenger_name);

			$('.seat_pref_pax_name').eq(0).empty().text(first_passenger_name);

			update_seat_pax_name(0, first_passenger_name)

		}

	}

	//Updates seat passenger name

	function update_seat_pax_name(first_name_index, passenegr_name)

	{

		$('.seat_pax_details').each(function(){

			$('.seat_pax_name', this).eq(first_name_index).empty().text(passenegr_name);

		});

	}

	//Remove later

	$('.extra_services_hide_show').click(function(){

		if($(this).hasClass('fa-angle-double-down')){

			$(this).removeClass('fa-angle-double-down').addClass('fa-angle-double-up');

		} else if($(this).hasClass('fa-angle-double-up')){

			$(this).removeClass('fa-angle-double-up').addClass('fa-angle-double-down');

		}

	});

	

	

	

	

	/*******Seat Map Starts ************/

	var seat_pax_index = 0;

	

	//By defalut activate first seat map

	activate_current_seat_segment_map(0);

	

	// Handle tab switching for seat segments
	$('.seat_segment_map').on('click', function(event){
		event.preventDefault();
		var $tab = $(this);
		var seat_segment_index = $(".seat_segment_map").index(this);
		var targetId = $tab.attr('data-bs-target');
		
		// Trigger Bootstrap tab
		if(targetId && typeof bootstrap !== 'undefined') {
			var $tabPane = $(targetId);
			if($tabPane.length) {
				var tab = new bootstrap.Tab($tab[0]);
				tab.show();
			}
		}
		
		// Activate the seat segment map
		activate_current_seat_segment_map(seat_segment_index);
	});
	
	// Also handle Bootstrap tab events
	$('.seat_segment_map[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
		var seat_segment_index = $(".seat_segment_map").index(this);
		activate_current_seat_segment_map(seat_segment_index);
	});

	$('.seat_segment_pax').click(function(event){

		var seat_segment_index = $(".seat_segment_pax").index(this);

		$('.seat_segment_map').eq(seat_segment_index).trigger('click');

		

	});

	$('.seat_segment_pax_tr').on('click', function(event){
		event.stopPropagation();
		var $currentRow = $(this);
		var $currentSegment = $currentRow.closest('.seat_segment_pax');
		var seat_segment_index = $('.seat_segment_pax').index($currentSegment);
		
		// Remove active class from all rows in all segments
		$('.seat_segment_pax_tr').removeClass('active_seat_segment_pax_tr');
		
		// Add active class to clicked row
		$currentRow.addClass('active_seat_segment_pax_tr');
		
		// Update seat_pax_index within the current segment
		seat_pax_index = $('.seat_segment_pax_tr', $currentSegment).index($currentRow);
		
		// Activate the corresponding tab and map
		var $tab = $('.seat_segment_map').eq(seat_segment_index);
		if($tab.length) {
			// Trigger Bootstrap tab
			var targetId = $tab.attr('data-bs-target');
			if(targetId && typeof bootstrap !== 'undefined') {
				var $tabPane = $(targetId);
				if($tabPane.length) {
					var tab = new bootstrap.Tab($tab[0]);
					tab.show();
				}
			}
		}
		
		// Activate the seat segment map (this will preserve the selected row)
		activate_current_seat_segment_map(seat_segment_index);
	});

	

	//Activate Current Seat Map

	function activate_current_seat_segment_map(seat_segment_index)

	{

		$('.seat_segment_map').removeClass('active_seat_segment_map');

		$('.seat_segment_pax').removeClass('active_seat_segment_pax');

		
		// Only remove active class from rows in other segments, keep current segment's active row if exists
		var $currentSegment = $('.seat_segment_pax').eq(seat_segment_index);
		var $activeRowInCurrentSegment = $('.seat_segment_pax_tr.active_seat_segment_pax_tr', $currentSegment);
		
		// Remove active from all rows
		$('.seat_segment_pax_tr').removeClass('active_seat_segment_pax_tr');
		
		$('.seat_segment_map').eq(seat_segment_index).addClass('active_seat_segment_map');

		$('.seat_segment_pax').eq(seat_segment_index).addClass('active_seat_segment_pax');

		
		// If there was an active row in this segment, keep it active, otherwise activate first row
		if($activeRowInCurrentSegment.length > 0 && $activeRowInCurrentSegment.closest('.seat_segment_pax').index() === seat_segment_index) {
			$activeRowInCurrentSegment.addClass('active_seat_segment_pax_tr');
			seat_pax_index = $('.seat_segment_pax_tr', $currentSegment).index($activeRowInCurrentSegment);
		} else {
			// Activate the first passenger row in the selected segment
			var $firstPaxRow = $('.seat_segment_pax_tr', $currentSegment).first();
			
			if($firstPaxRow.length > 0) {
				$firstPaxRow.addClass('active_seat_segment_pax_tr');
				seat_pax_index = $('.seat_segment_pax_tr', $currentSegment).index($firstPaxRow);
			} else {
				seat_pax_index = 0;
			}
		}

	}

	

	/**

	 * 1) Restrict with Pssenger: Max Seats

	 * 

	 * 

	 * 

	 */

	// Handle seat selection - use event delegation for dynamically loaded content
	$(document).on('click', '.seat_icon_wrapper.choose_seat', function(){
		var $seatWrapper = $(this);
		
		if(is_seat_free($seatWrapper)){
			var opeartion = '';

			if($seatWrapper.hasClass('seat_selected') == true){
				// Change back to available
				$seatWrapper.removeClass('seat_selected');
				$seatWrapper.find('.seat_icon_available').removeClass('seat_icon_selected');
				opeartion = 'remove';
			} else {
				// Change to selected
				$seatWrapper.addClass('seat_selected');
				$seatWrapper.find('.seat_icon_available').addClass('seat_icon_selected');
				opeartion = 'add';
			}

			update_pax_seat_details($seatWrapper, opeartion);
		}

		update_total_booking_amount();
	});

	/**

	 * Update Passenger Details

	 */

	function update_pax_seat_details(current_seat_object, opeartion)

	{

		//make old seat available
		//alert(update_pax_seat_details);
		make_seat_available();

		if(opeartion == 'add'){

			var seat_number = current_seat_object.data('seat_number');

			var seat_price = current_seat_object.data('seat_price');

			var seat_id = current_seat_object.data('seat_id');

		} else {

			var seat_number = '';

			var seat_price = '';

			var seat_id = '';

		}

		//Updating Seat Number
		var $seatNumberCell = $('td.seat_pax_number', '.active_seat_segment_pax_tr');
		if($seatNumberCell.length) {
			$seatNumberCell.find('.seat_number_display').text(seat_number || '-');
		} else {
			$('td', '.active_seat_segment_pax_tr').eq(1).find('.seat_number_display').text(seat_number || '-');
		}

		//Updating Seat Price
		var $seatPriceCell = $('td.seat_pax_price', '.active_seat_segment_pax_tr');
		if($seatPriceCell.length) {
			$seatPriceCell.find('.seat_price_display').text(seat_price || '-');
		} else {
			$('td', '.active_seat_segment_pax_tr').eq(2).find('.seat_price_display').text(seat_price || '-');
		}

		//Updating SeatID and Price

		$('input', '.active_seat_segment_pax_tr').eq(0).val(seat_id);

		$('input', '.active_seat_segment_pax_tr').eq(0).data('seat_price', seat_price);

		

	}

	/**

	 * Checks the seat is free to allocate

	 */

	function is_seat_free(current_seat_object)

	{

		var seat_id = current_seat_object.data('seat_id');
		
		if(!seat_id) {
			return false;
		}

		var selected_seat_name = $("input[value='"+seat_id+"']", '.seat_segment_pax_tr').attr('name');

		

		if(typeof(selected_seat_name) == 'undefined'){

			return true;

		} else {

			// Seat is already selected, allow deselection
			if(current_seat_object.hasClass('seat_selected')) {
				return true;
			}
			
			update_pax_seat_details(current_seat_object, 'remove');

			return false;

		}

	}

	/**

	 * Free the seat

	 */

	function make_seat_available()

	{

		var old_seat_id = $('input', '.active_seat_segment_pax_tr').eq(0).val().trim();

		if(old_seat_id !=''){
			$(".seat_icon_wrapper[data-seat_id='"+old_seat_id +"']").removeClass('seat_selected');
			$(".seat_icon_wrapper[data-seat_id='"+old_seat_id +"'] .seat_icon_available").removeClass('seat_icon_selected');
		}

	}

	/*******Seat Map Ends ************/

	

	

});

