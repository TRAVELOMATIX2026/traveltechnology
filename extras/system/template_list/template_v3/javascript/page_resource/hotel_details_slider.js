
! function($) {
  "use strict";
  var a = {
    accordionOn: ["xs"]
  };
  $.fn.responsiveTabs = function(e) {
    var t = $.extend({}, a, e),
      s = "";
    return $.each(t.accordionOn, function(a, e) {
      s += " accordion-" + e
    }), this.each(function() {
      var a = $(this),
        e = a.find("> li > a"),
        t = $(e.first().attr("href")).parent(".tab-content"),
        i = t.children(".tab-pane");
      a.add(t).wrapAll('<div class="responsive-tabs-container" />');
      var n = a.parent(".responsive-tabs-container");
      n.addClass(s), e.each(function(a) {
        var t = $(this),
          s = t.attr("href"),
          i = "",
          n = "",
          r = "";
        t.parent("li").hasClass("active") && (i = " active"), 0 === a && (n = " first"), a === e.length - 1 && (r = " last"), t.clone(!1).addClass("accordion-link" + i + n + r).insertBefore(s)
      });
      var r = t.children(".accordion-link");
      e.on("click", function(a) {
        a.preventDefault();
        var e = $(this),
          s = e.parent("li"),
          n = s.siblings("li"),
          c = e.attr("href"),
          l = t.children('a[href="' + c + '"]');
        s.hasClass("active") || (s.addClass("active"), n.removeClass("active"), i.removeClass("active"), $(c).addClass("active"), r.removeClass("active"), l.addClass("active"))
      }), r.on("click", function(t) {
        t.preventDefault();
        var s = $(this),
          n = s.attr("href"),
          c = a.find('li > a[href="' + n + '"]').parent("li");
        s.hasClass("active") || (r.removeClass("active"), s.addClass("active"), i.removeClass("active"), $(n).addClass("active"), e.parent("li").removeClass("active"), c.addClass("active"))
      })
    })
  }
}(jQuery);

$('.responsive-tabs').responsiveTabs({
  accordionOn: ['xs']
});

$(document).ready(function() {
  
  console.log('Hotel slider script loaded');
  
  var sync1 = $("#hotel_top");
  console.log('Slider element found:', sync1.length);
  
  // Check if element exists
  if(sync1.length === 0) {
    console.error('Slider element #hotel_top not found!');
    return;
  }
  
  // Check if owlCarousel is available
  if(typeof $.fn.owlCarousel === 'undefined') {
    console.error('Owl Carousel library not loaded!');
    return;
  }
  
  console.log('Initializing Owl Carousel v2...');
  
  // Owl Carousel 2.x initialization (matching sightseeing page exactly)
  try {
    sync1.owlCarousel({
      items: 1,
      loop: false,
      margin: 0,
      nav: true,
      dots: true,
      autoplay: false,
      autoplayTimeout: 3000,
      autoplayHoverPause: true,
      navText: [
        "<i class='bi bi-chevron-left'></i>", 
        "<i class='bi bi-chevron-right'></i>"
      ],
      responsive: {
        0: {
          items: 1
        },
        479: {
          items: 1
        },
        768: {
          items: 1
        },
        979: {
          items: 1
        },
        1199: {
          items: 1
        }
      }
    });
    
    console.log('Owl Carousel initialized successfully');
    
  } catch(error) {
    console.error('Error initializing Owl Carousel:', error);
  }

  // Thumbnail gallery click handler - sync with main slider
  function initThumbnailGallery() {
    console.log('Initializing thumbnail gallery...');
    
    setTimeout(function() {
      $('.thumb-item').off('click').on('click', function(e) {
        e.preventDefault();
        var index = parseInt($(this).data('index')) || 0;
        console.log('Thumbnail clicked, index:', index);
        
        // Navigate main slider to clicked thumbnail (Owl Carousel v2 syntax)
        if(sync1.data('owl.carousel') !== undefined) {
          sync1.trigger('to.owl.carousel', [index, 300]);
        } else if(sync1.data('owlCarousel') !== undefined) {
          sync1.trigger('owl.goTo', index);
        }
      });
      
      console.log('Thumbnail gallery click handlers attached to ' + $('.thumb-item').length + ' items');
    }, 600);
  }
  
  // Initialize thumbnail gallery
  initThumbnailGallery();
  
  // Gallery image click for modal
  $(document).on('click', '#hotel_top .item img, .gallery-image', function() {
    var index = $(this).closest('.item').data('index') || $(this).closest('.item').index();
    if(typeof openModal === 'function') {
      openModal();
      if(typeof currentSlide === 'function') {
        currentSlide(index + 1);
      }
    }
  });
  
  // Thumbnail more button for modal
  $(document).on('click', '.thumb-more', function() {
    var index = $(this).data('index') || 0;
    if(typeof openModal === 'function') {
      openModal();
      if(typeof currentSlide === 'function') {
        currentSlide(index + 1);
      }
    }
  });
  
  // Keep old sync2 code for backward compatibility (if hotel_bottom exists)
  var sync2 = $("#hotel_bottom");
  if(sync2.length > 0 && typeof $.fn.owlCarousel !== 'undefined') {
    sync2.owlCarousel({
      items: 6,
      loop: false,
      margin: 5,
      nav: false,
      dots: false,
      responsive: {
        0: {
          items: 3
        },
        480: {
          items: 3
        },
        768: {
          items: 6
        },
        979: {
          items: 6
        },
        1199: {
          items: 6
        }
      },
      onInitialized: function(el){
        el.find(".owl-item").eq(0).addClass("synced");
      }
    });

    $('#maphtlmapdtls').on('click', function(){
      //load map
      var lat = $("#latitude").val();
      var lon = $("#longitude").val();
      var image_url = $("#api_base_url").val();
      var hotel_name = $("#hotel_name").val();
      /*start**/
      $('#map_viewsld').removeClass('hide');
      $('#maphtlmapimages').removeClass('hide');
      $('#maphtlmapdtls').addClass('hide');
      var myCenter=new google.maps.LatLng(lat,lon);
      var mapProp = {
        center:myCenter,
        zoom:10,
        mapTypeId:google.maps.MapTypeId.ROADMAP
      };

      var map = new google.maps.Map(document.getElementById("Map"), mapProp);
    
      var marker = new google.maps.Marker({
        position:myCenter,
        icon:image_url,
        animation: google.maps.Animation.DROP
      });
    
      marker.setMap(map);
    
      var infowindow = new google.maps.InfoWindow({
        content:hotel_name
      });
      google.maps.event.addListener(marker, "click", function() {
        infowindow.open(map, marker);
      });

      /*end**/
      var sync1 = $("#hotel_top");
      var sync2 = $("#hotel_bottom");
      if(sync1.length > 0) sync1.trigger("to.owl.carousel", [0, 300]);
      if(sync2.length > 0) sync2.trigger("to.owl.carousel", [0, 300]);
    });

    $('#maphtlmapimages').on('click', function(){
      $('#map_viewsld').addClass('hide');
      $('#maphtlmapimages').addClass('hide');
      $('#maphtlmapdtls').removeClass('hide');
      $('#hotel_bottom').css('cursor', 'pointer');
    });
    $('#hotel_bottom').on('click', function(){
      $('#map_viewsld').addClass('hide');
      $('#maphtlmapimages').addClass('hide');
      $('#maphtlmapdtls').removeClass('hide');
    });
 
    // Sync main carousel with thumbnails (Owl Carousel v2)
    sync1.on('changed.owl.carousel', function(el) {
      var current = el.item.index;
      sync2
        .find('.owl-item')
        .removeClass('synced')
        .eq(current)
        .addClass('synced');
      
      if(sync2.data('owl.carousel') !== undefined){
        center(current);
      }
    });

    // Click on thumbnail
    sync2.on('click', '.owl-item', function(e){
      e.preventDefault();
      var number = $(this).index();
      sync1.trigger('to.owl.carousel', [number, 300]);
    });
   
    function center(number){
      var sync2visible = sync2.find('.owl-item.active');
      var num = number;
      var found = false;
      
      sync2visible.each(function(){
        if(num === $(this).index()){
          found = true;
        }
      });

      if(found === false){
        if(num > sync2visible.last().index()){
          sync2.trigger('to.owl.carousel', [num - sync2visible.length + 2, 300]);
        }else{
          if(num - 1 === -1){
            num = 0;
          }
          sync2.trigger('to.owl.carousel', [num, 300]);
        }
      } else if(num === sync2visible.last().index()){
        sync2.trigger('to.owl.carousel', [sync2visible.eq(1).index(), 300]);
      } else if(num === sync2visible.first().index()){
        sync2.trigger('to.owl.carousel', [num-1, 300]);
      }
    }
  }

  $(".show-more a").on("click", function() {
    var $link = $(this);
    var $content = $link.parent().prev("div.lettrfty");
    var linkText = $link.text();
    $content.toggleClass("short-text, full-text");
    $link.text(getShowLinkText(linkText));
    return false;
  });

  $(".show-faclts a").on("click", function() {
    var $link = $(this);
    var $content = $link.parent().prev("div.htlfac_lity");
    var linkText = $link.text();
    $content.toggleClass("short-text, full-text");
    $link.text(getShowLinkFaclty(linkText));
    return false;
  });

  $(".show-rooms a").on("click", function(e) {
    e.preventDefault();
    var $link = $(this);
    // Try to find the room list container - could be romlistnh or romsoutdv
    var $content = $link.closest("#rooms").find(".romlistnh, .romsoutdv").first();
    var linkText = $link.text();
    
    if ($content.length === 0) {
      // Fallback: try parent.prev
      $content = $link.parent().prev("div.romlistnh, div.romsoutdv");
    }
    
    if ($content.hasClass("short-text1")) {
      // Show all rooms
      $content.removeClass("short-text1").addClass("full-text");
      $link.text("Show Less Rooms -");
    } else {
      // Hide rooms beyond first 5
      $content.removeClass("full-text").addClass("short-text1");
      $link.text("Show More Rooms +");
      // Scroll to top of room list
      if ($content.length > 0 && $content.offset()) {
        $('html, body').animate({
          scrollTop: $content.offset().top - 100
        }, 500);
      }
    }
    
    return false;
  });

  // Check if there are more than 5 rooms and show/hide the button accordingly
  // Define globally so it can be called from AJAX callbacks
  window.checkRoomCount = function() {
    var $showMoreLink = $("#show-more-link");
    var roomCount = 0;
    
    // Check in romlistnh first
    var $roomList = $(".romlistnh");
    if ($roomList.length > 0) {
      roomCount = $roomList.find(".romconoutdv").length;
    } else {
      // Check in romsoutdv (the actual container)
      var $roomListAlt = $(".romsoutdv");
      if ($roomListAlt.length > 0) {
        roomCount = $roomListAlt.find(".romconoutdv").length;
      }
    }
    
    if (roomCount > 5) {
      $showMoreLink.removeClass("hide");
    } else {
      $showMoreLink.addClass("hide");
    }
  };

  // Run on page load and after AJAX room load
  // Initial check
  setTimeout(function() {
    if (typeof window.checkRoomCount === 'function') {
      window.checkRoomCount();
    }
  }, 500);
  
  // Check again after room list is loaded via AJAX (with longer delay for AJAX)
  setTimeout(function() {
    if (typeof window.checkRoomCount === 'function') {
      window.checkRoomCount();
    }
  }, 2000);

  $('#unique_room_types, #room_types_category, #cancellation_type').on('change', filterRooms);

  $('#selectroom').on('click', function(){
    $('.nav li.active').removeClass('active');
    $('.tab-content .tab-pane.active').removeClass('active');

    var $parent = $('.roomstab');
    $parent.addClass('active');
    $('#rooms').addClass('active');
    // e.preventDefault();
  });

}); // End of document.ready

// Helper functions defined outside document.ready
function getShowLinkText(currentText) {
  var newText = '';
  if (currentText.toUpperCase() === "SHOW MORE +") {
    newText = "Show Less -";
  } else {
    newText = "Show More +";
  }
  return newText;
}

function getShowLinkFaclty(currentText) {
  var newText = '';
  if (currentText.toUpperCase() === "SHOW MORE +") {
    newText = "Show Less -";
  } else {
    newText = "Show More +";
  }
  return newText;
}

function getShowLinkRooms(currentText) {
  var newText = '';
  if (currentText.toUpperCase() === "SHOW MORE ROOMS +") {
    newText = "Show Less Rooms -";
  } else {
    newText = "Show More Rooms +";
  }
  return newText;
}

function filterRooms() {
  var selectedBed = $('#unique_room_types').val();
  var selectedCategory = $('#room_types_category').val();
  var selectedCancellation = $('#cancellation_type').val();

  $('.romconoutdv').each(function () {
    var match = true;
    var bed = $(this).data('bed');
    var category = $(this).data('category');
    var cancellation = $(this).data('cancellation');

    if (selectedBed && selectedBed !== bed) {
      match = false;
    }
    if (selectedCategory && selectedCategory !== category) {
      match = false;
    }
    if (selectedCancellation && selectedCancellation.toLowerCase() !== cancellation.toLowerCase()) {
      match = false;
    }

    if (match) {
      $(this).show();
    } else {
      $(this).hide();
    }
  });
}
