$(document).ready(function() {
	
	 // $(".map_click").click(function(){
  //    	$(".resultalls").addClass("fulview");
  //        $('.rowresult').removeClass("col-4");
  //        $('#hotel_search_result .item').removeClass('grid-group-item');
  //       $('.hotel_map').show();
  //       $(".coleft").hide();
  //       $(this).addClass("active");
  //    });

    /* $(".grid_click").click(function(){
     	$('#hotel_search_result .item').addClass('grid-group-item');
        $(".resultalls").removeClass("fulview");
        $('.hotel_map').hide();
        $('.rowresult').addClass("col-4");
        $('.allresult').removeClass("map_open");
         $(".coleft").show();
        $(this).addClass("active");
     });*/

     // $(".list_click").click(function(){
     //    $(".resultalls").removeClass("fulview");
     //    $('#hotel_search_result .item').removeClass('grid-group-item');
     //    $('.hotel_map').hide();
     //    $('.rowresult').removeClass("col-4");
     //    $(".coleft").show();
     //    $(this).addClass("active");
     // });

     

    $(".map_tab").click(function(){
     	$(".map_tab").hide();
     	$(".list_tab").show();
     });

    $(".list_tab").click(function(){
     	$(".map_tab").show();
     	$(".list_tab").hide();
     	$(".allresult").removeClass("map_open");
     	$(".resultalls").removeClass("open");
     });

    /*  Mobile Filter  */
    $('.filter_tab').click(function() {
     //$('.resultalls').stop(true, true).toggleClass('open');
     $('.coleft').slideToggle(500);
    });


    $(".close_fil_box").click(function(){
			$(".coleft").hide();
			$(".resultalls").removeClass("open");
      });
	
});
function showhide(e)
     {
        // Prevent default if event is provided
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Get the element that triggered the click
        var clickedElement = null;
        if (e && e.target) {
            clickedElement = e.target;
        } else if (window.event && window.event.target) {
            clickedElement = window.event.target;
        }
        
        // Traverse up to find the button element
        var buttonElement = clickedElement;
        while (buttonElement && buttonElement !== document.body) {
            if (buttonElement.classList && (buttonElement.classList.contains('view-toggle-btn') || buttonElement.id === 'map_clickid' || buttonElement.id === 'list_clickid')) {
                break;
            }
            buttonElement = buttonElement.parentElement;
        }
        
        // Check which button was clicked
        var isMapClick = false;
        var isListClick = false;
        
        if (buttonElement) {
            isMapClick = buttonElement.classList.contains('map_click') || buttonElement.id === 'map_clickid';
            isListClick = buttonElement.classList.contains('list_click') || buttonElement.id === 'list_clickid';
        }
        
        // If it's a map or list toggle, trigger the jQuery handlers with the correct element
        if (isMapClick) {
            // Prevent recursive triggering
            if ($(buttonElement).data('processing')) {
                return false;
            }
            
            // Set active class immediately
            $('.view-toggle-btn').removeClass('active');
            $('#map_clickid').addClass('active');
            
            // Trigger map click handler on the specific button (without bubbling)
            var clickEvent = new jQuery.Event('click');
            clickEvent.stopPropagation();
            $(buttonElement).trigger(clickEvent);
            return false;
        } else if (isListClick) {
            // Prevent recursive triggering
            if ($(buttonElement).data('processing')) {
                return false;
            }
            
            // Set active class immediately
            $('.view-toggle-btn').removeClass('active');
            $('#list_clickid').addClass('active');
            
            // Trigger list click handler on the specific button (without bubbling)
            var clickEvent = new jQuery.Event('click');
            clickEvent.stopPropagation();
            $(buttonElement).trigger(clickEvent);
            return false;
        }
        
        // Fallback: toggle filter sidebar (original functionality)
        var div = document.getElementById("coleftid");
        if (div) {
            if (div.style.display !== "none") {
                div.style.display = "none";
            } else {
                div.style.display = "block";
            }
        }
        return false;
     }