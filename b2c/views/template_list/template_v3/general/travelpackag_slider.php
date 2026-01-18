<!-- Materialize CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">

<!-- Materialize JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<div class="curated_trvl_pckg">
   <div class="container">
      <div class="hdng">
         <h3>Curated Travel Packages <br>
         for your <span>Next Adventure.</span></h3>
      </div>
      <div class="clearfix"></div>
      <div class="carousel-container">
         <div class="carousel-arrow left" id="prev"><i class="far fa-angle-left"></i></div>
         <div class="carousel">
            <div class="carousel-item">
               <img src="<?php echo $GLOBALS['CI']->template->template_images('curated_travel_1.png'); ?>">
               <div class="curated_abslt_txt">
                  <h3>Amalfi</h3>
                  <div class="curated_cntry"><img src="<?php echo $GLOBALS['CI']->template->template_images('italy_flag.png'); ?>">Italy</div>
                  <a href="">Explore Destinations <i class="far fa-angle-right"></i></a>
               </div>
            </div>
            
            <div class="carousel-item">
               <img src="<?php echo $GLOBALS['CI']->template->template_images('curated_travel_2.png'); ?>">
               <div class="curated_abslt_txt">
                  <h3>Amalfi</h3>
                  <div class="curated_cntry"><img src="<?php echo $GLOBALS['CI']->template->template_images('italy_flag.png'); ?>">Italy</div>
                  <a href="">Explore Destinations <i class="far fa-angle-right"></i></a>
               </div>
            </div>
            
            <div class="carousel-item">
               <img src="<?php echo $GLOBALS['CI']->template->template_images('curated_travel_3.png'); ?>">
               <div class="curated_abslt_txt">
                  <h3>Amalfi</h3>
                  <div class="curated_cntry"><img src="<?php echo $GLOBALS['CI']->template->template_images('italy_flag.png'); ?>">Italy</div>
                  <a href="">Explore Destinations <i class="far fa-angle-right"></i></a>
               </div>
            </div>
            
            <div class="carousel-item">
               <img src="<?php echo $GLOBALS['CI']->template->template_images('curated_travel_4.png'); ?>">
               <div class="curated_abslt_txt">
                  <h3>Amalfi</h3>
                  <div class="curated_cntry"><img src="<?php echo $GLOBALS['CI']->template->template_images('italy_flag.png'); ?>">Italy</div>
                  <a href="">Explore Destinations <i class="far fa-angle-right"></i></a>
               </div>
            </div>
            
            <div class="carousel-item">
               <img src="<?php echo $GLOBALS['CI']->template->template_images('curated_travel_5.png'); ?>">
               <div class="curated_abslt_txt">
                  <h3>Amalfi</h3>
                  <div class="curated_cntry"><img src="<?php echo $GLOBALS['CI']->template->template_images('italy_flag.png'); ?>">Italy</div>
                  <a href="">Explore Destinations <i class="far fa-angle-right"></i></a>
               </div>
            </div>
            
            <div class="carousel-item">
               <img src="<?php echo $GLOBALS['CI']->template->template_images('curated_travel_1.png'); ?>">
               <div class="curated_abslt_txt">
                  <h3>Amalfi</h3>
                  <div class="curated_cntry"><img src="<?php echo $GLOBALS['CI']->template->template_images('italy_flag.png'); ?>">Italy</div>
                  <a href="">Explore Destinations <i class="far fa-angle-right"></i></a>
               </div>
            </div>
            
            <div class="carousel-item">
               <img src="<?php echo $GLOBALS['CI']->template->template_images('curated_travel_2.png'); ?>">
               <div class="curated_abslt_txt">
                  <h3>Amalfi</h3>
                  <div class="curated_cntry"><img src="<?php echo $GLOBALS['CI']->template->template_images('italy_flag.png'); ?>">Italy</div>
                  <a href="">Explore Destinations <i class="far fa-angle-right"></i></a>
               </div>
            </div>
            
            <div class="carousel-item">
               <img src="<?php echo $GLOBALS['CI']->template->template_images('curated_travel_3.png'); ?>">
               <div class="curated_abslt_txt">
                  <h3>Amalfi</h3>
                  <div class="curated_cntry"><img src="<?php echo $GLOBALS['CI']->template->template_images('italy_flag.png'); ?>">Italy</div>
                  <a href="">Explore Destinations <i class="far fa-angle-right"></i></a>
               </div>
            </div>
         </div>
         <div class="carousel-arrow right" id="next"><i class="far fa-angle-right"></i></div>
      </div>
      <div class="curated_trip_btm">
         <h5>Top destinations curated for you</h5>
         <a href="">Explore all <i class="far fa-angle-right"></i></a>
      </div>
   </div>
</div>

<script>
   let instance;
   document.addEventListener('DOMContentLoaded', function () {
      const elem = document.querySelector('.carousel');
      instance = M.Carousel.init(elem, {
         dist: -50,
         shift: 0,
         padding: 20,
         numVisible: 5,
         indicators: false
      });

      document.getElementById('next').addEventListener('click', () => {
         instance.next();
      });

      document.getElementById('prev').addEventListener('click', () => {
         instance.prev();
      });
   });
</script>