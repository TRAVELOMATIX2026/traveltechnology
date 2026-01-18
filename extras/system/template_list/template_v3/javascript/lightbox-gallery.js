/**
 * Simple Lightbox Gallery for Image Slider
 * Pure Vanilla JavaScript - No jQuery dependency
 */

(function() {
  'use strict';
  
  // Wait for all deferred scripts and DOM
  function initWhenReady() {
    // Check if slider exists
    const sliderCheck = document.querySelector('#hotel_top');
    if (!sliderCheck) {
      console.log('Slider not found, lightbox not initialized');
      return;
    }
    
    console.log('Initializing lightbox gallery...');
    initLightbox();
  }
  
  // Create lightbox HTML
  function createLightbox() {
    const lightboxHTML = `
      <div id="imageLightbox" class="image-lightbox">
        <div class="lightbox-overlay"></div>
        <div class="lightbox-content">
          <button class="lightbox-close" aria-label="Close">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
          <button class="lightbox-prev" aria-label="Previous">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
          </button>
          <button class="lightbox-next" aria-label="Next">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
          </button>
          <div class="lightbox-image-wrapper">
            <img src="" alt="" class="lightbox-image">
            <div class="lightbox-loader"></div>
          </div>
          <div class="lightbox-counter"></div>
        </div>
      </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', lightboxHTML);
  }
  
  // Initialize lightbox
  function initLightbox() {
    let currentIndex = 0;
    let images = [];
    
    const existingLightbox = document.getElementById('imageLightbox');
    if (!existingLightbox) {
      createLightbox();
    }
    
    const lightboxEl = document.getElementById('imageLightbox');
    const lightboxImg = lightboxEl.querySelector('.lightbox-image');
    const lightboxLoader = lightboxEl.querySelector('.lightbox-loader');
    const lightboxCounter = lightboxEl.querySelector('.lightbox-counter');
    const closeBtn = lightboxEl.querySelector('.lightbox-close');
    const prevBtn = lightboxEl.querySelector('.lightbox-prev');
    const nextBtn = lightboxEl.querySelector('.lightbox-next');
    
    // Collect all images from slider
    function collectImages() {
      images = [];
      const sliderImages = document.querySelectorAll('#hotel_top .item img, #hotel_top .owl-item img');
      sliderImages.forEach(img => {
        // Only add visible/valid images
        if (img.src && !img.src.includes('undefined')) {
          images.push({
            src: img.src,
            alt: img.alt || ''
          });
        }
      });
      console.log('Collected ' + images.length + ' images for lightbox');
    }
    
    // Show image
    function showImage(index) {
      if (images.length === 0) return;
      
      if (index < 0) index = images.length - 1;
      if (index >= images.length) index = 0;
      
      currentIndex = index;
      
      // Show loader
      lightboxLoader.style.display = 'block';
      lightboxImg.style.opacity = '0';
      
      // Load image
      const img = new Image();
      img.onload = function() {
        lightboxImg.src = images[currentIndex].src;
        lightboxImg.alt = images[currentIndex].alt;
        lightboxImg.style.opacity = '1';
        lightboxLoader.style.display = 'none';
      };
      img.onerror = function() {
        console.error('Failed to load image:', images[currentIndex].src);
        lightboxLoader.style.display = 'none';
      };
      img.src = images[currentIndex].src;
      
      // Update counter
      lightboxCounter.textContent = (currentIndex + 1) + ' / ' + images.length;
      
      // Update button states
      prevBtn.style.display = images.length > 1 ? 'flex' : 'none';
      nextBtn.style.display = images.length > 1 ? 'flex' : 'none';
    }
    
    // Open lightbox
    function openLightbox(index) {
      collectImages();
      if (images.length === 0) {
        console.log('No images to display in lightbox');
        return;
      }
      
      document.body.style.overflow = 'hidden';
      lightboxEl.classList.add('active');
      showImage(index);
      console.log('Lightbox opened at index:', index);
    }
    
    // Close lightbox
    function closeLightbox() {
      document.body.style.overflow = '';
      lightboxEl.classList.remove('active');
      console.log('Lightbox closed');
    }
    
    // Event listeners
    closeBtn.addEventListener('click', function(e) {
      e.preventDefault();
      closeLightbox();
    });
    
    lightboxEl.querySelector('.lightbox-overlay').addEventListener('click', function(e) {
      e.preventDefault();
      closeLightbox();
    });
    
    prevBtn.addEventListener('click', function(e) {
      e.preventDefault();
      showImage(currentIndex - 1);
    });
    
    nextBtn.addEventListener('click', function(e) {
      e.preventDefault();
      showImage(currentIndex + 1);
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
      if (!lightboxEl.classList.contains('active')) return;
      
      if (e.key === 'Escape') {
        e.preventDefault();
        closeLightbox();
      }
      if (e.key === 'ArrowLeft') {
        e.preventDefault();
        showImage(currentIndex - 1);
      }
      if (e.key === 'ArrowRight') {
        e.preventDefault();
        showImage(currentIndex + 1);
      }
    });
    
    // Click on slider images to open lightbox
    // Use event delegation to handle dynamically loaded images
    document.addEventListener('click', function(e) {
      // Check if clicked element is a slider image
      const sliderImg = e.target.closest('#hotel_top .item img, #hotel_top .owl-item img');
      if (sliderImg && sliderImg.src && !sliderImg.src.includes('undefined')) {
        e.preventDefault();
        e.stopPropagation();
        
        // Find index of clicked image
        collectImages();
        const clickedSrc = sliderImg.src;
        const clickedIndex = images.findIndex(function(img) {
          return img.src === clickedSrc;
        });
        
        if (clickedIndex !== -1) {
          openLightbox(clickedIndex);
        } else {
          console.log('Could not find clicked image in collection');
        }
      }
    });
    
    console.log('Lightbox gallery initialized successfully');
  }
  
  // Initialize when everything is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      // Small delay to ensure Owl Carousel has initialized
      setTimeout(initWhenReady, 500);
    });
  } else {
    // DOM already loaded
    setTimeout(initWhenReady, 500);
  }
  
})();
