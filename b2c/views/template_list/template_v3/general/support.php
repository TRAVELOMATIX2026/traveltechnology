<style type="text/css">
    .heading-faq .accrdn-togle:after {
    font-family: 'Font Awesome 5 Pro'; 
    content: "\f077";   
    float: right;        
    color: #f5601a;         
}
.heading-faq .accrdn-togle.collapsed:after {
    content: "\f078"; 
    color: #0681b4;
}

</style>
<section class="custmr-supprt">
    <div class="container">
        <div class="first-dv">
           <div class="support-header">
            <h1>Customer Support</h1>
            <p>Our Team plays a crucial role in creating and maintaining the foundational content for your Online Travel Agency's website. This team is responsible for producing accurate, engaging, and informative static content that showcases destinations, travel tips, guides, and other relevant information to help users plan their trips effectively.</p>

<p>Our highly experienced and knowledgeable professional’s team is empowered to build trust at every step. Our team is capable of providing the best possible service to our clients in a timely and effective manner. We work closely with our clients to ensure all communications and price quotes on the provided service are transparent and clearly addressed. Ensures consistent and accurate information for website visitors.</p>

<p>Enhances the agency's credibility and authority in the travel industry. Improves SEO rankings with card card-body-optimized content. Provides users with valuable resources for trip planning.</p>

<p>By establishing a dedicated Team, Ultravelcare can create a strong foundation of reliable and engaging content, helping users make informed travel decisions and fostering a positive user experience.</p>

<h2><strong>Payment & Booking</strong> Information</h2>
               <?php   if (is_logged_in_user() == false) { ?>
                <button class="sign-in-btn" data-bs-toggle="modal" data-bs-target="#show_log">Sign In</button> <span>to get personalized assistance with your trip</span>
                <?php } ?>
           </div>
        </div>
        <div class="content">
            <div class="wp-div">
                <div class="nopad col-md-6 col-12">
                    <div class="inner-dv">
                        <div class="icn">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="contnt">
                        <h2>WhatsApp</h2>
                        <span>Coming soon</span>
                    </div>
                    </div>
                </div>
                <div class=" col-md-6 col-12 nopad">
                    <div class="inner-dv">
                        <div class="icn icon2">
                            <i class="fas fa-phone-volume"></i>
                        </div>
                        <div class="contnt">
                            <h2>Call</h2>
                            <span>
                                <p>Toll free: <br> <a href="tel:+1 7787816731" class=" phone-number"> +1 778 781 6731</a></p>
                                <!-- <p>Local: <br> <a href="tel:+1  6043634610" class=" phone-number"> +1  604 363 4610</a></p>  -->
                                <p>Address: <br> <a href="" class=" phone-number"> 13049 76 Ave # 103, Surrey, BC V3W 2V7</a></p> 
                            </span>
                        </div>
                        <div class="spprt_mail">
                            <p>Email: <br> <a href="mailto:customercare@kapido.com" class=""> customercare@kapido.com </a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="support-header">
            <h2>FAQ</h2>
        </div>
        <div class="card-group panl-grp" id="accordion">

            <?php
                $this->db->select('*');
                $this->db->from('customer_support');
                $this->db->where('status',1);
                $query = $this->db->get();
                $result = $query->result();
                // debug($result);exit;
                foreach ($result as $value) {
                    
                
            ?>
            <div class="panel card panl-dflt">
                <div class="card-header heading-faq">
                    <div class="faq-icon">
                <i class="fas fa-credit-card"></i>
            </div>
                    <h4 class="card panl-dflt">
            <a class="accordion-toggle accrdn-togle" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#<?php echo $value->origin;?>">
          <?php echo $value->question;?>
            </a>
          </h4>
                </div>
                <div id="<?php echo $value->origin;?>" class="collapse collapse">
                    <div class="card-body panl-bdy">
                      <?php 
                      if (is_logged_in_user() == false) {
                        $an = '<a class="sign-in-btn" data-bs-toggle="modal" data-bs-target="#show_log">My Trips</a>';
                      }else{
                        $an = '<a href="https://www.kapido.com/index.php/report/flights">My Trips</a>';
                      }
                        // $an = '<a href="x.com">Test</a>';
                        // echo strpos($value->answer, 'My Trips') ? str_replace('My Trips', $an, $value->answer) : $value->answer;

                      if (strpos($value->answer, 'My Trips')) {
                          $updatedAnswer = preg_replace('/<a\b[^>]*>(.*?)My Trips(.*?)<\/a>/i', $an, $value->answer);
                          // $updatedAnswer = str_replace('My Trips', $an, $updatedAnswer);
                      } else {
                          $updatedAnswer = $value->answer;
                      }
                      echo $updatedAnswer;
                      
                      ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
</section>