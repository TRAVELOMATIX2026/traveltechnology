<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('agent.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('agent_index.css'), 'media' => 'screen');
if (empty(validation_errors()) == false) {
  $view_tab = '';
  $edit_tab = ' active ';
} else {
  $view_tab = ' active ';
  $edit_tab = '';
}
if (empty(validation_errors()) == false) {
  $message = 'hide';
}
//$message = 'hide';//Remove it in Soorya Travel
?>
<!-- Icon Libraries -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style type="text/css">
  .pop-info {
    font-family: monospace;
    font-size: 15px;
    margin-left: 19%;
    opacity: 0.9;
    position: fixed;
    z-index: 1000;
  }
</style>

<div class="b2b_agent_profile agent_regpage agentmyn">
  <!-- Banner Section with Text Slider (Left 50%) -->
  <div class="registration-banner">
    <div class="banner-content-wrapper">
      <div class="content-slider-wrapper">
        <div class="content-slider">
          <div class="slider-item active">
            <div class="slider-icon">
              <i class="material-icons">person_add</i>
            </div>
            <h2 class="slider-title">Start Your Travel Business</h2>
            <p class="slider-description">Join thousands of successful travel agents. Get access to global inventory, competitive rates, and powerful booking tools.</p>
          </div>
          <div class="slider-item">
            <div class="slider-icon">
              <i class="material-icons">trending_up</i>
            </div>
            <h2 class="slider-title">Maximize Your Earnings</h2>
            <p class="slider-description">Competitive commission structures and flexible markup options. Build a profitable travel business with our B2B platform.</p>
          </div>
          <div class="slider-item">
            <div class="slider-icon">
              <i class="material-icons">dashboard</i>
            </div>
            <h2 class="slider-title">Powerful Dashboard</h2>
            <p class="slider-description">Manage all your bookings, markups, and commissions from one centralized dashboard. Real-time updates and comprehensive reporting.</p>
          </div>
          <div class="slider-item">
            <div class="slider-icon">
              <i class="material-icons">support_agent</i>
            </div>
            <h2 class="slider-title">24/7 Partner Support</h2>
            <p class="slider-description">Personalized service with dedicated support. Our expert team is always ready to help you grow your travel business.</p>
          </div>
          <div class="slider-item">
            <div class="slider-icon">
              <i class="material-icons">flight</i>
            </div>
            <h2 class="slider-title">Global Travel Inventory</h2>
            <p class="slider-description">Access flights, hotels, transfers, and activities from verified suppliers worldwide. Get the best prices for your customers.</p>
          </div>
        </div>
        <!-- Slider Navigation Dots -->
        <div class="slider-dots">
          <span class="dot active" data-slide="0"></span>
          <span class="dot" data-slide="1"></span>
          <span class="dot" data-slide="2"></span>
          <span class="dot" data-slide="3"></span>
          <span class="dot" data-slide="4"></span>
        </div>
      </div>
    </div>
  </div>
  
  <div class="container">
    <?php if (!empty($this->session->flashdata('message'))) { ?>
      <div class="alert alert-success"> <a href="#" class="close" data-bs-dismiss="alert" aria-label="close">&times;</a> <strong><?php echo $this->session->flashdata('message'); ?></strong> </div>
    <?php } ?>
    <div class="tab-content sidewise_tab">
      <div data-role="tabpanel" class="tab-pane active clearfix" id="profile">
        <div class="agent_regtr">
          <div class="agentreg_heading"> Become an Agent
            <a href="<?= base_url() ?>" class="gobacklink">Back to Login</a>
          </div>
          <div class="clearfix"></div>
          
          <!-- Step Progress Indicator -->
          <div class="step-progress-container">
            <div class="step-progress">
              <div class="step-item active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">Personal Info</div>
              </div>
              <div class="step-connector"></div>
              <div class="step-item" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">Company Details</div>
              </div>
              <div class="step-connector"></div>
              <div class="step-item" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">Login Info</div>
              </div>
            </div>
          </div>
          
          <!-- Edit User Profile starts-->
          <div class="tab-content">
            <div data-role="tabpanel filldiv" class="tab-pane active" id="show_user_profile">
              <form action="<?= base_url() . 'index.php/user/agentRegister'; ?>" method="post" name="edit_user_form" id="register_user_form" enctype="multipart/form-data" autocomplete="off">
                <!-- Step 1: Personal Info -->
                <div class="each_sections step-section active" data-step="1">
                  <div class="sec_heading">
                    <div class="step-header">
                      <div class="step-header-icon">
                        <i class="material-icons">person</i>
                      </div>
                      <div class="step-header-content">
                        <div>
                          <h3 class="step-title">Personal Info</h3>
                          <p class="step-subtitle">Enter your personal information</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="inside_regwrp">
                    <div class="col-sm-12 nopad row flex-wrap gap-0 ">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space">
                          <div class="label_form">First Name <span class="text-danger">*</span></div>
                          <div class="d-flex flex-row">
                          <div class="col-4 nopad">
                            <div class="select_wrap">
                              <select name="title" class="select_form noborderit smaltext" required>
                                <?=
                                generate_options(get_enum_list('title'), (array)@$title) ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-8 nopad">
                            <div class="div_wrap">
                              <input type="text" name="first_name" placeholder="first name" value="<?php echo set_value('first_name'); ?>" class="input_form alpha_space _guest_validate_field" required />
                            </div>
                          </div>
                          </div>
                          <?php if (!empty(form_error('first_name'))) { ?>
                            <div class="agent_error"><?php echo form_error('first_name'); ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <!-- <span>This Field is mandatory</span> -->
                      <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Last Name <span class="text-danger">*</span></div>
                          <div class="div_wrap">
                            <input type="text" name="last_name" placeholder="last name" value="<?php echo set_value('last_name'); ?>" class="input_form alpha_space _guest_validate_field" required="required" />
                          </div>
                          <?php if (!empty(form_error('last_name'))) { ?>
                            <div class="agent_error"><?php echo form_error('last_name'); ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="col-sm-12 nopad">
                      <div class="label_form">Mobile Number <span class="text-danger">*</span></div>
                        <div class="wrap_space d-flex flex-row">
                         
                          <div class="col-4 nopad">
                            <div class="select_wrap">
                              <select name="country_code" class="select_form noborderit smaltext" required>
                                <?= generate_options($phone_code_array, (array)@$country_code) ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-8 nopad">
                            <div class="div_wrap">
                              <input type="text" name="phone" placeholder="mobile number" value="<?php echo set_value('phone'); ?>" class="input_form numeric _guest_validate_field" required="required" maxlength="10">
                            </div>
                          </div>
                          <?php if (!empty(form_error('phone'))) { ?>
                            <div class="agent_error"><?php echo form_error('phone'); ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Email <span class="text-danger">*</span></div>
                          <div class="div_wrap">
                            <input type="email" id="user_email" name="email" maxlength="80" placeholder="email" value="<?php echo set_value('email'); ?>" class="input_form email" required="required" />
                          </div>
                          <?php if (!empty(form_error('email'))) { ?>
                            <div class="agent_error"><?php echo form_error('email'); ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <!-- <div class="col-sm-12 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Currency <span class="text-danger">*</span></div>
                          <div class="div_wrap">
                            <select name="currency" class="select_form noborderit smaltext" required>
                              <?= generate_options($currency_list, ['45']) ?>
                            </select>
                          </div>
                        </div>
                      </div> -->
                    </div>
                    <!--<div class="col-sm-4 nopad">
                    <div class="tnlepasport_b2b upload_wrap wrap_space">
                      <div class="label_form">Profile Image</div>
                      <div class="uplod_image"  style="background-image:url(<?= $GLOBALS['CI']->template->template_images('agent_demo.png') ?>)">
                        <input type="file" id="profile_img_id" name="image" accept="image/*" class="hideupload" />
                        
                      </div>
                      
                    </div>
                  </div>-->
                  </div>
                  <div class="step-navigation">
                    <div></div>
                    <button type="button" class="btn-step-next">Next <i class="material-icons">arrow_forward</i></button>
                  </div>
                </div>
                
                <!-- Step 2: Company Details -->
                <div class="each_sections step-section" data-step="2">
                  <div class="sec_heading">
                    <div class="step-header">
                      <div class="step-header-icon">
                        <i class="material-icons">business</i>
                      </div>
                      <div class="step-header-content">
                        <div>
                          <h3 class="step-title">Company Details</h3>
                          <p class="step-subtitle">Provide your company information</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="inside_regwrp row flex-wrap gap-0">
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company Name <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="company_name" placeholder="Company name" value="<?php echo set_value('company_name'); ?>" class="input_form _guest_validate_field alpha_space" maxlength="45" required="required" />
                        </div>
                        <?php if (!empty(form_error('company_name'))) { ?>
                          <div class="agent_error"><?php echo form_error('company_name'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <?php //if($active_data['api_country_list_fk'] == 92) { 
                    ?>
                    <?php //} 
                    ?>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Address <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <textarea class="input_textarea _guest_validate_field" name="address" placeholder="Address" required><?php echo set_value('address'); ?></textarea>
                        </div>
                        <?php if (!empty(form_error('address'))) { ?>
                          <div class="agent_error"><?php echo form_error('address'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger">*</span></div>
                        <div class="select_wrap">
                          <?php
                          if (empty(set_value('country')) == false) {
                            $default_country = set_value('country');
                          } else {
                            $default_country = $active_data['api_country_list_fk'];
                          }
                          if (empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            $default_city = $active_data['api_city_list_fk'];
                          }
                          ?>
                          <select name="country" id="country_id" class="select_form" required>
                            <option value="">Select Country</option>
                            <?= generate_options($country_list, array($default_country)); ?>
                          </select>
                        </div>
                        <?php if (!empty(form_error('country'))) { ?>
                          <div class="agent_error"><?php echo form_error('country'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">City <span class="text-danger">*</span></div>
                        <div class="select_wrap">
                          <select name="city" id="city_id" class="select_form" required>
                            <option value='' selected="">Select City</option>
                          </select>
                        </div>
                        <?php if (!empty(form_error('city'))) { ?>
                          <div class="agent_error"><?php echo form_error('city'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Pin Code <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="pin_code" placeholder="Pin" value="<?php echo set_value('pin_code'); ?>" class="input_form _guest_validate_field numeric" maxlength="10" required />
                        </div>
                        <?php if (!empty(form_error('pin_code'))) { ?>
                          <div class="agent_error"><?php echo form_error('pin_code'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Office Phone <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="text" name="office_phone" placeholder="Office Number" value="<?php echo set_value('office_phone'); ?>" class="input_form numeric _guest_validate_field" required="required" maxlength="15">
                        </div>
                        <?php if (!empty(form_error('office_phone'))) { ?>
                          <div class="agent_error"><?php echo form_error('office_phone'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company Incorporation Certificate <span class="text-danger">*</span></div>
                        <div class="file-upload-wrapper">
                          <input type="file" name="c_certificate" id="c_certificate" class="file-upload-input" accept=".pdf,.jpg,.jpeg,.png" required>
                          <label for="c_certificate" class="file-upload-label">
                            <div class="file-upload-icon">
                              <i class="material-icons">cloud_upload</i>
                            </div>
                            <div class="file-upload-content">
                              <span class="file-upload-text">Choose file or drag & drop</span>
                              <span class="file-upload-hint">PDF, JPG, PNG (Max 5MB)</span>
                            </div>
                            <span class="file-upload-button">Browse</span>
                          </label>
                          <div class="file-name-display"></div>
                        </div>
                        <?php if (!empty(form_error('c_certificate'))) { ?>
                          <div class="agent_error"><?php echo form_error('c_certificate'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company VAT Certificate <span class="text-danger">*</span></div>
                        <div class="file-upload-wrapper">
                          <input type="file" name="c_gst" id="c_gst" class="file-upload-input" accept=".pdf,.jpg,.jpeg,.png" required>
                          <label for="c_gst" class="file-upload-label">
                            <div class="file-upload-icon">
                              <i class="material-icons">cloud_upload</i>
                            </div>
                            <div class="file-upload-content">
                              <span class="file-upload-text">Choose file or drag & drop</span>
                              <span class="file-upload-hint">PDF, JPG, PNG (Max 5MB)</span>
                            </div>
                            <span class="file-upload-button">Browse</span>
                          </label>
                          <div class="file-name-display"></div>
                        </div>
                        <?php if (!empty(form_error('c_gst'))) { ?>
                          <div class="agent_error"><?php echo form_error('c_gst'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company Owner Proof <span class="text-danger">*</span></div>
                        <div class="file-upload-wrapper">
                          <input type="file" name="c_owner_proof" id="c_owner_proof" class="file-upload-input" accept=".pdf,.jpg,.jpeg,.png" required>
                          <label for="c_owner_proof" class="file-upload-label">
                            <div class="file-upload-icon">
                              <i class="material-icons">cloud_upload</i>
                            </div>
                            <div class="file-upload-content">
                              <span class="file-upload-text">Choose file or drag & drop</span>
                              <span class="file-upload-hint">PDF, JPG, PNG (Max 5MB)</span>
                            </div>
                            <span class="file-upload-button">Browse</span>
                          </label>
                          <div class="file-name-display"></div>
                        </div>
                        <?php if (!empty(form_error('c_owner_proof'))) { ?>
                          <div class="agent_error"><?php echo form_error('c_owner_proof'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Company Address Proof <span class="text-danger">*</span></div>
                        <div class="file-upload-wrapper">
                          <input type="file" name="c_address_proof" id="c_address_proof" class="file-upload-input" accept=".pdf,.jpg,.jpeg,.png" required>
                          <label for="c_address_proof" class="file-upload-label">
                            <div class="file-upload-icon">
                              <i class="material-icons">cloud_upload</i>
                            </div>
                            <div class="file-upload-content">
                              <span class="file-upload-text">Choose file or drag & drop</span>
                              <span class="file-upload-hint">PDF, JPG, PNG (Max 5MB)</span>
                            </div>
                            <span class="file-upload-button">Browse</span>
                          </label>
                          <div class="file-name-display"></div>
                        </div>
                        <?php if (!empty(form_error('c_address_proof'))) { ?>
                          <div class="agent_error"><?php echo form_error('c_address_proof'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="step-navigation">
                    <button type="button" class="btn-step-prev"><i class="material-icons">arrow_back</i> Previous</button>
                    <button type="button" class="btn-step-next">Next <i class="material-icons">arrow_forward</i></button>
                  </div>
                </div>
                
                <!-- Step 3: Login Info -->
                <div class="each_sections step-section" data-step="3">
                  <div class="sec_heading">
                    <div class="step-header">
                      <div class="step-header-icon">
                        <i class="material-icons">lock</i>
                      </div>
                      <div class="step-header-content">
                        <div>
                          <h3 class="step-title">Login Info</h3>
                          <p class="step-subtitle">Create your login credentials</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="inside_regwrp">
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">User Name <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="email" id="user_name" name="user_name" placeholder="email" maxlength="80" value="<?php echo set_value('email'); ?>" class="input_form email" required="required" />
                        </div>
                        <?php if (!empty(form_error('user_name'))) { ?>
                          <div class="agent_error"><?php echo form_error('user_name'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Password <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="password" name="password" placeholder="password" value="<?php echo set_value('password'); ?>" class="input_form pass _guest_validate_field" required="required" />
                        </div>
                        <?php if (!empty(form_error('password'))) { ?>
                          <div class="agent_error"><?php echo form_error('password'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-12 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Confirm Password <span class="text-danger">*</span></div>
                        <div class="div_wrap">
                          <input type="password" name="password_c" placeholder="retype password" value="<?php echo set_value('password_c'); ?>" class="input_form pass _guest_validate_field" required="required" />
                        </div>
                        <?php if (!empty(form_error('password_c'))) { ?>
                          <div class="agent_error"><?php echo form_error('password_c'); ?></div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="step-navigation step-navigation-final">
                    <button type="button" class="btn-step-prev"><i class="material-icons">arrow_back</i> Previous</button>
                    <div class="submitsection">
                      <div class="acceptrms d-none">
                        <div class="squaredThree">
                          <input type="checkbox" name="term_condition" class="airlinecheckbox validate_user_register" id="term_condition" value="<?php echo set_value('term_condition'); ?>" required="">
                          <label for="term_condition"></label>
                        </div>
                        <label for="term_condition" class="lbllbl">I accept the <a target="_balnk" href="<?= APP_ROOT_DIR ?>/terms-conditions">agency terms and conditons</a></label>
                      </div>
                      <div class="clearfix"></div>
                      <button type="submit" class="btnreg_agent">Register</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div data-role="tabpanel" class="tab-pane clearfix" id="edit_user_profile"> </div>
          </div>
          <!-- Edit User Profile Ends-->
        </div>
      </div>
    </div>
  </div>
  <?php
  $datepicker = array(array('date_of_birth', PAST_DATE));
  $GLOBALS['CI']->current_page->set_datepicker($datepicker);
  ?>
  <script type="text/javascript">
    var default_city = '<?= $default_city; ?>';
    var currentStep = 1;
    var totalSteps = 3;
    
    $(document).ready(function() {
      // Initialize stepwise form
      initStepwiseForm();
      
      get_city_list();
      //get the state
      $('#country_id').on('change', function() {
        country_origion = $(this).val();
        if (country_origion == '92') {
          $("#pan_data").css("display", "block");
          $("#pan_number").addClass('_guest_validate_field');
        } else {
          $("#pan_data").css("display", "none");
          $("#pan_number").removeClass('_guest_validate_field');
        }
        get_city_list();
      });
      function get_city_list(country_id) {
        var country_id = $('#country_id').val();
        if (country_id == '') {
          $("#city_id").empty().html('<option value = "" selected="">Select City</option>');
          return false;
        }
        $.post(app_base_url + 'index.php/ajax/get_city_lists', {
          country_id: country_id
        }, function(data) {
          $("#city_id").empty().html(data);
          $('#city_id').val(default_city)
        });
      }
      //Auto populate the user email to the user name
      $('#user_email').on('blur', function() {
        var user_email = $(this).val().trim();
        if (user_email != '') {
          $('#user_name').val(user_email);
        }
      });
      $(".btnreg_agent").on('click', function() {
        var count = 0;
        $('._guest_validate_field').each(function() {
          if (this.value.trim() == '') {
            count++;
            $(this).addClass('invalid-ip').parent().append(
              "<span id='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>");
          }
        });
        if (count > 0) {
          return false;
        }
      })
      $('._guest_validate_field').focus(function() {
        $(this).removeClass('invalid-ip');
        $(this).parent().find(".formerror").hide();
      });
      $("#term_condition").on('click', function() {
        if ($('#term_condition').is(':checked')) {
          $('#term_condition').val('1');
        } else {
          $('#term_condition').val('0');
        }
      })
    });
    
    // Stepwise Form Functions
    function initStepwiseForm() {
      // Show only first step
      $('.step-section').not('[data-step="1"]').hide();
      updateStepProgress();
      
      // Next button handlers
      $('.btn-step-next').on('click', function() {
        var currentSection = $(this).closest('.step-section');
        var currentStepNum = parseInt(currentSection.data('step'));
        
        // Validate current step
        if (validateStep(currentStepNum)) {
          if (currentStepNum < totalSteps) {
            goToStep(currentStepNum + 1);
          }
        }
      });
      
      // Previous button handlers
      $('.btn-step-prev').on('click', function() {
        var currentSection = $(this).closest('.step-section');
        var currentStepNum = parseInt(currentSection.data('step'));
        
        if (currentStepNum > 1) {
          goToStep(currentStepNum - 1);
        }
      });
    }
    
    function goToStep(step) {
      // Hide all steps
      $('.step-section').hide();
      
      // Show target step
      $('.step-section[data-step="' + step + '"]').fadeIn(300);
      
      currentStep = step;
      updateStepProgress();
      
      // Scroll to top of form
      $('html, body').animate({
        scrollTop: $('.step-progress-container').offset().top - 100
      }, 300);
    }
    
    function updateStepProgress() {
      $('.step-item').each(function() {
        var stepNum = parseInt($(this).data('step'));
        $(this).removeClass('active completed');
        
        if (stepNum < currentStep) {
          // Completed steps - show checkmark
          $(this).addClass('completed');
        } else if (stepNum === currentStep) {
          // Active step - show number
          $(this).addClass('active');
        }
      });
    }
    
    function validateStep(step) {
      var isValid = true;
      var $stepSection = $('.step-section[data-step="' + step + '"]');
      
      // Check required fields in current step
      $stepSection.find('input[required], select[required], textarea[required]').each(function() {
        if ($(this).val().trim() === '') {
          $(this).addClass('invalid-ip');
          isValid = false;
        } else {
          $(this).removeClass('invalid-ip');
        }
      });
      
      if (!isValid) {
        alert('Please fill all required fields in this step.');
      }
      
      return isValid;
    }
    //image preview
    $(function() {
      $("#profile_img_id").on("change", function() {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
        if (/^image/.test(files[0].type)) { // only image file
          var reader = new FileReader(); // instance of the FileReader
          reader.readAsDataURL(files[0]); // read the local file
          reader.onloadend = function() { // set image data as background of div
            $(".uplod_image").css("background-image", "url(" + this.result + ")");
          }
        } else {
          $("#profile_img_id").val('');
        }
      });
    });
    
    // File upload UI enhancement
    $(document).ready(function() {
      // Handle file input changes
      $('.file-upload-input').on('change', function() {
        var input = $(this);
        var fileName = input[0].files[0] ? input[0].files[0].name : '';
        var fileDisplay = input.closest('.file-upload-wrapper').find('.file-name-display');
        
        if (fileName) {
          var fileSize = (input[0].files[0].size / 1024 / 1024).toFixed(2);
          fileDisplay.html(
            '<i class="material-icons file-icon">description</i>' +
            '<span class="file-name">' + fileName + ' (' + fileSize + ' MB)</span>' +
            '<i class="material-icons file-remove" title="Remove file">close</i>'
          ).addClass('has-file');
          
          // Update label text
          input.closest('.file-upload-wrapper').find('.file-upload-text').text('File selected');
        } else {
          fileDisplay.removeClass('has-file').html('');
          input.closest('.file-upload-wrapper').find('.file-upload-text').text('Choose file or drag & drop');
        }
      });
      
      // Remove file handler
      $(document).on('click', '.file-remove', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var wrapper = $(this).closest('.file-upload-wrapper');
        var input = wrapper.find('.file-upload-input');
        input.val('');
        wrapper.find('.file-name-display').removeClass('has-file').html('');
        wrapper.find('.file-upload-text').text('Choose file or drag & drop');
      });
      
      // Drag and drop functionality
      $('.file-upload-label').on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
      });
      
      $('.file-upload-label').on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
      });
      
      $('.file-upload-label').on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
        
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
          var input = $(this).closest('.file-upload-wrapper').find('.file-upload-input');
          input[0].files = files;
          input.trigger('change');
        }
      });
    });
    
    // Content Slider Functionality for Registration Banner
    var currentSlide = 0;
    var slides = $('.registration-banner .slider-item');
    var dots = $('.registration-banner .slider-dots .dot');
    var totalSlides = slides.length;
    var slideInterval;

    function showSlide(index) {
      // Remove active class from all slides and dots
      slides.removeClass('active');
      dots.removeClass('active');
      
      // Add active class to current slide and dot
      $(slides[index]).addClass('active');
      $(dots[index]).addClass('active');
      
      currentSlide = index;
    }

    function nextSlide() {
      var next = (currentSlide + 1) % totalSlides;
      showSlide(next);
    }

    function startSlider() {
      slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }

    function stopSlider() {
      clearInterval(slideInterval);
    }

    // Dot click handler
    dots.on('click', function() {
      var slideIndex = $(this).data('slide');
      showSlide(slideIndex);
      stopSlider();
      startSlider(); // Restart auto-slide
    });

    // Pause on hover
    $('.registration-banner .content-slider-wrapper').on('mouseenter', function() {
      stopSlider();
    }).on('mouseleave', function() {
      startSlider();
    });

    // Initialize slider
    if (totalSlides > 0) {
      showSlide(0);
      startSlider();
    }
  </script>