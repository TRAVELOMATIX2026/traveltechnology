
$(document).ready(function() {
  
  console.log('Slider script loaded');
  
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
  
  // Owl Carousel 2.x initialization
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

  /* Uncomment if you need the bottom thumbnail carousel
  var sync2 = $("#hotel_bottom");
  
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
    }
  });

  // Sync main carousel with thumbnails
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
    }
  }
  */

  // Thumbnail gallery click handler
  function initThumbnailGallery() {
    console.log('Initializing thumbnail gallery...');
    
    // Wait a bit for lightbox to be ready
    setTimeout(function() {
      $('.thumb-item').off('click').on('click', function(e) {
        e.preventDefault();
        var index = parseInt($(this).data('index')) || 0;
        console.log('Thumbnail clicked, index:', index);
        
        // Find the corresponding slider image and trigger click
        var sliderImages = $('#hotel_top .item img');
        if (sliderImages.length > index) {
          // Trigger the lightbox directly
          sliderImages.eq(index).trigger('click');
        } else {
          console.log('Image not found at index:', index);
        }
      });
      
      console.log('Thumbnail gallery click handlers attached to ' + $('.thumb-item').length + ' items');
    }, 600);
  }
  
  // Initialize thumbnail gallery
  initThumbnailGallery();

});
