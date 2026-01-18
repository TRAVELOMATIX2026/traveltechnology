/**
 * Hero Slider Functionality
 * Handles the hero slider with background images, navigation arrows, and thumbnail previews
 */

(function() {
  'use strict';
  
  function initHeroSlider() {
    // Check if jQuery is available
    if (typeof jQuery === 'undefined' || typeof $ === 'undefined') {
      console.error('jQuery is required for hero slider');
      return;
    }
    
    var currentSlide = 0;
    var slides = $('.hero-slide');
    var previews = $('.preview-item');
    var totalSlides = slides.length;
    var autoSlideInterval;
    
    // If no slides exist, still show text
    if (totalSlides === 0) {
      setTimeout(function() {
        $('.hero-text-content').addClass('animate');
      }, 300);
      return;
    }
    
    // Update slide function
    function updateSlide(index) {
      // Remove active class from all slides
      slides.removeClass('active');
      previews.removeClass('active');
      
      // Remove inline styles and reset animation classes
      $('.hero-text-item').removeAttr('style');
      $('.hero-text-content').removeClass('animate animating');
      
      // Add active class to current slide
      if (slides.length > 0) {
        var $currentSlide = slides.eq(index);
        $currentSlide.addClass('active');
        
        // Handle video if exists
        var $video = $currentSlide.find('video');
        if ($video.length > 0) {
          $video[0].play().catch(function(e) {
            console.log('Video autoplay prevented:', e);
          });
        }
        
        // Pause other videos
        slides.not($currentSlide).find('video').each(function() {
          this.pause();
          this.currentTime = 0;
        });
      }
      
      // Update preview active state
      if (previews.length > 0) {
        previews.eq(0).addClass('active');
      }
      
      // Update previews to show current and next slides
      updatePreviews();
      
      // Update text content smoothly - fade out, update, fade in
      var $textContent = $('.hero-text-content');
      
      // Fade out text first
      $textContent.removeClass('animate').addClass('animating');
      
      setTimeout(function() {
        // Update text content while it's hidden to prevent layout shift
        if (typeof tmpl_imgs !== 'undefined' && tmpl_imgs[index]) {
          var slideData = tmpl_imgs[index];
          if (slideData.title) {
            $('#big1').text(slideData.title);
          }
          if (slideData.description) {
            $('#desc').text(slideData.description);
          }
        }
        
        // Immediately fade in new text (no delay to prevent jumping)
        requestAnimationFrame(function() {
          $textContent.removeClass('animating').addClass('animate');
        });
      }, 300); // Wait for fade out transition to complete
    }
    
    // Next slide
    function nextSlide() {
      currentSlide = (currentSlide + 1) % totalSlides;
      updateSlide(currentSlide);
      $(document).trigger('slideChanged');
    }
    
    // Previous slide
    function prevSlide() {
      currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
      updateSlide(currentSlide);
      $(document).trigger('slideChanged');
    }
    
    // Progress bar
    var progressBar = $('#heroProgress');
    var progressInterval;
    
    function startProgress() {
      var progress = 0;
      var duration = 5000; // 5 seconds
      var interval = 50; // Update every 50ms
      var increment = (interval / duration) * 100;
      
      progressBar.css('width', '0%');
      
      progressInterval = setInterval(function() {
        progress += increment;
        if (progress >= 100) {
          progress = 100;
          clearInterval(progressInterval);
        }
        progressBar.css('width', progress + '%');
      }, interval);
    }
    
    function stopProgress() {
      clearInterval(progressInterval);
      progressBar.css('width', '0%');
    }
    
    // Auto slide
    function startAutoSlide() {
      startProgress();
      autoSlideInterval = setInterval(function() {
        nextSlide();
      }, 5000); // Change slide every 5 seconds
    }
    
    function stopAutoSlide() {
      clearInterval(autoSlideInterval);
      stopProgress();
    }
    
    // Navigation arrows
    $('#heroNext').on('click', function() {
      stopAutoSlide();
      nextSlide();
      startAutoSlide();
    });
    
    $('#heroPrev').on('click', function() {
      stopAutoSlide();
      prevSlide();
      startAutoSlide();
    });
    
    // Update previews to show current and next slides
    function updatePreviews() {
      previews.each(function(i) {
        var previewIndex = (currentSlide + i) % totalSlides;
        var $preview = $(this);
        var $img = $preview.find('img');
        
        // Update image source if needed
        if (typeof tmpl_imgs !== 'undefined' && tmpl_imgs[previewIndex]) {
          $img.attr('src', tmpl_imgs[previewIndex].image);
          $preview.attr('data-slide-index', previewIndex);
        }
        
        // Update active state
        if (i === 0) {
          $preview.addClass('active');
        } else {
          $preview.removeClass('active');
        }
      });
    }
    
    // Preview click - use event delegation to ensure it works
    $(document).on('click', '.preview-item', function(e) {
      e.preventDefault();
      e.stopPropagation();
      var index = parseInt($(this).attr('data-slide-index'));
      if (!isNaN(index) && index >= 0 && index < totalSlides) {
        stopAutoSlide();
        currentSlide = index;
        updateSlide(currentSlide);
        updatePreviews();
        startAutoSlide();
      }
    });
    
    // Pause on hover
    $('.hero-slider-wrapper').on('mouseenter', stopAutoSlide);
    $('.hero-slider-wrapper').on('mouseleave', startAutoSlide);
    
    // Parallax effect removed - keeping simple fade only
    
    // Initialize
    // Remove any inline styles that might be hiding text
    $('.hero-text-item').removeAttr('style');
    $('.hero-text-content').removeClass('animating').addClass('animate');
    
    if (totalSlides > 0) {
      updateSlide(0);
      startAutoSlide();
    } else {
      // Even if no slides, ensure text is visible
      $('.hero-text-content').addClass('animate');
    }
  }
  
  // Wait for DOM and jQuery to be ready
  if (typeof jQuery !== 'undefined' && typeof $ !== 'undefined') {
    $(document).ready(function() {
      initHeroSlider();
    });
  } else {
    // Fallback if jQuery loads later
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initHeroSlider, 100);
      });
    } else {
      setTimeout(initHeroSlider, 100);
    }
  }
})();

