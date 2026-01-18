<?php
$social1 = is_active_social_login('facebook');
$social2 = is_active_social_login('twitter');
$social3 = is_active_social_login('googleplus');

$addr = $this->custom_db->single_table_records('api_country_list', '*');
$addr = $addr['data'];

$login_auth_loading_image	 = '<div class="text-center loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="please wait"/></div>';

if ($social1 == true) {
	$GLOBALS['CI']->load->library('social_network/facebook');
}

if ($social2 == true) {
	//Not Yet Active
}

if ($social3 == true) {
	$GLOBALS['CI']->load->library('social_network/google');
}

if (isset($login) == false || is_object($login) == false) {
	$login = new Provab_Page_Loader('login');
}
if (is_logged_in_user() == true) {
	if ($social1 == true) {
		echo '<div class="hide">' . $GLOBALS['CI']->facebook->login_button() . '</div>';
	}
?>

<?php } else { ?>
	<div class="my_account_dropdown mysign exploreul">
		<button type="button" class="close log_close" data-bs-dismiss="modal">&times;</button>
		<div class="signdiv">
			<div class="insigndiv for_sign_in">
				<!-- Left Side Image Section -->
				<div class="login-left-image">
					<div class="login-image-overlay">
						<div class="login-image-content">
							<div class="login-logo-wrapper">
								
								
							</div>
							<h3 class="login-image-title">Welcome to <div class="login-logo-text"><?php echo domain_name(); ?></div></h3>
							<p class="login-image-subtitle">Start your journey with us</p>
						</div>
					</div>
				</div>
				<div class="login-right-content">
					<div class="login-form-header">
						<h2 class="login-form-title">Sign In</h2>
						<p class="login-form-subtitle">Enter your credentials to access your account</p>
					</div>
				<div class="leftpul">
					<?php
					if ($social1) {

						echo $GLOBALS['CI']->facebook->login_button();
					}

					if ($social2) {
					?>
						<a class="logspecify tweetcolor">
							<span class="fa fa-twitter"></span>
							<div class="mensionsoc">Login with Twitter</div>
						</a>
					<?php
					}

					if ($social3) {
					?>
						<?php
						echo $GLOBALS['CI']->google->login_button(); ?>
					<?php } ?>
				</div>
				<?php $no_social = no_social();
				if ($no_social != 0) { ?>
					<div class="centerpul">
						<div class="orbar"> <strong>Or</strong> </div>
					</div>
				<?php } ?>
				<div class="ritpul">
					<form role="form" id="login" action="" autocomplete="off"
						name="login">
						<div class="rowput">
							<i class="material-icons input-icon-left">person</i>
							<input type="email"
								data-content="Username Ex: john@gmail.com"
								data-trigger="hover focus" data-placement="bottom"
								data-original-title="Here To Help" data-bs-toggle="popover"
								data-container="body" id="email"
								class="email form-control logpadding" placeholder="Username"
								required="required" name="email">
						</div>
						<div class="rowput">
							<i class="material-icons input-icon-left">lock</i>
							<input type="password"
								id="password" class="password form-control logpadding"
								placeholder="Password" required="required" name="password"
								value="">
							<button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
								<i class="material-icons">visibility</i>
							</button>
						</div>
						<div class="clearfix"></div>
						<div id="login-status-wrapper" class="alert alert-danger"
							style="display: none">
							<p> <i class="fal fa-warning"></i> </p>
						</div>
						<div class="clearfix"></div>
						<div id="login_auth_loading_image" style="display: none">
							<?= $login_auth_loading_image ?>
						</div>
						<div class="clearfix"></div>

						<div class="clearfix"></div>
						<button class="submitlogin" id="login_submit">
							<span>Login</span>
						</button>
						<div class="clear"></div>
						<div class="newAccountCredential">
						<div class="dntacnt"> New User? <a class="hand-cursor open_register">Sign Up</a> </div>
						<div class="misclog"> <a class="hand-cursor forgtpsw forgot_pasword" id="forgot-password">Forgot Password ? </a> </div>
						</div>
					</form>
				</div>
				</div>
			</div>
			<div class="newacount_div for_sign_up">
				<div class="slpophd_new">Register with <?php echo domain_name(); ?></div>
				<div class="othesend_regstr">
					<div class="ritpul">
						<form autocomplete="off" method="post" id="register_user_form">
							<div class="rowput has-feedback hide">
								<i class="material-icons input-icon-left">person</i>
								<input type="text" class="validate_user_register form-control logpadding" value="Customer" placeholder="Name" name="first_name" required="" />
							</div>
							<div class="rowput has-feedback">
								<i class="material-icons input-icon-left">email</i>
								<input type="email" class="validate_user_register form-control logpadding" placeholder="Email-Id" value="" name="email" required="" />
								<span class="err_msg"> Email Field is mandatory</span>
							</div>
							<div class="rowput has-feedback phone-input-group">
								<div class="phone-input-wrapper">
									<div class="country-code-section">
										<i class="material-icons country-code-icon">phone</i>
										<select name="country_code" class="validate_user_register country-code-select" required="">
											<option value=''>Code</option>
											<?php
											foreach ($addr as $key => $value) {
												echo "<option value = '" . $value['country_code'] . "' ".((INDIA_COUNTRY_CODE == $value['country_code'])?'selected': '') .">" . $value['country_code'] . "</option>";
											}
											?>
										</select>
										<i class="material-icons select-arrow-icon">keyboard_arrow_down</i>
									</div>
									<div class="mobile-number-section">
										<i class="material-icons mobile-icon">phone_android</i>
										<input type="phone" class="validate_user_register numeric mobile-number-input" maxlength="10" placeholder="Mobile Number" value="" name="phone" required="" />
									</div>
								</div>
								<span class="err_msg"> Mobile number is mandatory</span>
							</div>
							<div class="rowput has-feedback">
								<i class="material-icons input-icon-left">lock</i>
								<input type="password" class="validate_user_register form-control logpadding" placeholder="New Password" value="" name="password" required="">
								<button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
									<i class="material-icons">visibility</i>
								</button>
								<span class="err_msg"> password Field is mandatory</span>
							</div>
							<div class="rowput has-feedback">
								<i class="material-icons input-icon-left">lock_outline</i>
								<input type="password" class="validate_user_register form-control logpadding" placeholder="Retype Password" value="" name="confirm_password" required="" />
								<button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
									<i class="material-icons">visibility</i>
								</button>
								<span class="err_msg">confirm password Field is mandatory</span>
							</div>

							<div class="clearfix"></div>
							<div class="row_submit">
								<div class="col-12 nopad">
									<div class="agree_terms-wrapper">
									<div class="agree_terms">
										<div class="modern-checkbox-wrapper">
											<input type="checkbox" id="register_tc" class="modern-checkbox validate_user_register" name="tc" required="">
											<label for="register_tc" class="modern-checkbox-label">
												<span class="checkbox-custom">
													<i class="material-icons checkbox-check">check</i>
												</span>
												<span class="checkbox-text">By signing up you accept our <a target="_blank" href="<?= base_url() ?>index.php/terms-conditions">terms of use and privacy policy</a></span>
											</label>
											<span class="err_msg"> T&C Field is mandatory</span>
										</div>
									</div>
									</div>
								</div>
								<div class="col-12 nopad">
									<button type="submit" id="register_user_button" class="submitlogin">
										<span>Register</span>
									</button>
								</div>
							</div>
							<div class="loading d-none" id="loading"><img src="<?php echo $GLOBALS['CI']->template->template_images('loader_v3.gif') ?>" alt="loader"></div>
							<div class="clearfix"></div>
							<!-- <div class="text_info">(You will receive an e-mail containing the account verification link.)</div> -->
						</form>
						<a class="open_sign_in">I already have an Account</a>
					</div>
				</div>
			</div>
			<div class="actual_forgot for_forgot d-none">
				<div class="slpophd_new">Forgot Password?</div>
				<div class="othesend_regstr">
					<div class="rowput">
						<span class="fal fa-envelope"></span>
						<input type="text" name="forgot_pwd_email" id="recover_email" class="logpadding form-control" placeholder="Enter Email-Id" />
						<span>This Field is mandatory</span>
					</div>
					<div class="rowput">
						<span class="fal fa-mobile"></span>
						<input type="text" name="forgot_pwd_phone" id="recover_phone" class="logpadding form-control" placeholder="Registered Mobile Number " />
						<span>This Field is mandatory</span>
					</div>
					<div class="clearfix"></div>
					<div id="recover-title-wrapper" class="alert alert-success"
						style="display: none">
						<p> <i class="fal fa-warning"></i> <span id="recover-title"></span> </p>
					</div>
					<div class="clearfix"></div>
					<button class="submitlogin" id="reset-password-trigger">Send EMail</button>
					<div class="clearfix"></div>
					<a class="open_sign_in">I am an Existing User</a>
				</div>
			</div>
		</div>
	</div>
	<!-- New Forgot Password Modal -->
	<div id="forgotpaswrdpop" class="altpopup d-none">
		<div class="comn_close_pop fa fa-times closepopup"></div>
		<div class="insideforgot">
			<div class="slpophd">Forgot Password?</div>
			<div class="othesend">
				<div class="rowput">
					<span class="fa fa-envelope"></span>
					<input type="text" name="forgot_pwd_email" id="recover_email_book" class="logpadding form-control" placeholder="Enter Email" required="required" />
					<span>This Field is mandatory</span>
				</div>
				<div class="rowput">
					<span class="fa fa-mobile"></span>
					<input type="text" name="forgot_pwd_phone" id="recover_phone_book" class="logpadding form-control" placeholder="Enter Mobile Number" required="required" />
					<span>This Field is mandatory</span>
				</div>
				<div class="clearfix"></div>
				<div id="recover-title-wrapper-book" class="alert alert-success"
					style="display: none">
					<p> <i class="fa fa-warning"></i> <span id="recover-title-book"></span> </p>
				</div>
				<div class="centerdprcd">
					<button class="bookcont" id="reset-password-trigger-book">Send Mail</button>
				</div>
			</div>
		</div>
	</div>
<?php }
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/login.js'), 'defer' => 'defer');
?>
<style>
	.login-left-image::before {
		background-image: url('<?php echo $GLOBALS['CI']->template->template_images('matix.png'); ?>');
	}
	
	#register_user_form .err_msg.invalid-ip {
		color: var(--color-danger-primary);
		display: block;
		border: 0 !important;
		animation: slideDown 0.3s ease-out;
	}

	#register_user_form .err_msg {
		display: none;
	}
	
	#register_user_form .rowput input.invalid-ip,
	#register_user_form .rowput select.invalid-ip {
		border-color: var(--color-danger-primary) !important;
		box-shadow: 0 0 0 3px rgba(235, 71, 54, 0.1) !important;
	}
</style>

<script>
	function togglePassword(btn) {
		const input = btn.previousElementSibling;
		const icon = btn.querySelector('.material-icons');
		if (input.type === 'password') {
			input.type = 'text';
			icon.textContent = 'visibility_off';
		} else {
			input.type = 'password';
			icon.textContent = 'visibility';
		}
	}

	// Enable continue button when mobile number is valid
	$(document).ready(function() {
		$('#login_mobile').on('input', function() {
			const mobile = $(this).val();
			const continueBtn = $('#continue_with_mobile');
			if (mobile.length >= 10) {
				continueBtn.prop('disabled', false);
			} else {
				continueBtn.prop('disabled', true);
			}
		});

		// Tab switching
		$('.login-tab').on('click', function() {
			$('.login-tab').removeClass('active');
			$(this).addClass('active');
			const tab = $(this).data('tab');
			// Handle tab switching logic here if needed
		});
	});
	
	// Toast/Snackbar function for registration messages
	function showRegisterToast(message, type) {
		type = type || 'error'; // 'success' or 'error'
		var $toast = $('#register-toast-wrapper');
		var $toastBar = $('#register-toast');
		var $icon = $toastBar.find('.toast-icon');
		var $message = $toastBar.find('.toast-message');
		
		// Remove all type classes
		$toastBar.removeClass('toast-snackbar-success toast-snackbar-error toast-snackbar-warning toast-snackbar-info');
		
		// Add appropriate type class
		if (type === 'success') {
			$toastBar.addClass('toast-snackbar-success');
			$icon.text('check_circle');
		} else {
			$toastBar.addClass('toast-snackbar-error');
			$icon.text('error_outline');
		}
		
		// Set message
		$message.html(message);
		
		// Show toast
		$toast.removeClass('d-none hide').addClass('show');
		$toast.css({
			'display': 'block !important',
			'visibility': 'visible !important',
			'opacity': '1 !important',
			'pointer-events': 'auto !important'
		});
		
		// Auto-hide after 5 seconds
		clearTimeout(window.registerToastTimeout);
		window.registerToastTimeout = setTimeout(function() {
			hideRegisterToast();
		}, 5000);
	}
	
	function hideRegisterToast() {
		var $toast = $('#register-toast-wrapper');
		$toast.removeClass('show').addClass('d-none hide');
		$toast.css({
			'display': 'none !important',
			'visibility': 'hidden !important',
			'opacity': '0 !important'
		});
		$('#register-toast .toast-message').html('');
	}
	
	// Close button handler
	$(document).on('click', '#register-toast-wrapper .toast-close', function() {
		hideRegisterToast();
	});
	
	// Override registration form submission to use toast
	$(document).ready(function() {
		// Intercept the existing registration handler
		$('form#register_user_form').on('submit', function(e) {
			// Let the original handler run, but we'll override the success/error display
		});
		
		// Override the register button click handler after original script loads
		setTimeout(function() {
			$('form#register_user_form #register_user_button').off('click').on('click', function(e) {
				e.preventDefault();
				hideRegisterToast();
				
				var isValid = true;
				var firstInvalid = null;
				var errorMessage = '';
				
				$('form#register_user_form .validate_user_register').each(function() {
					if ($(this).val() === '') {
						$(this).addClass('invalid-ip');
						$(this).parent().find('.err_msg').addClass('invalid-ip');
						if (isValid) {
							isValid = false;
							firstInvalid = this;
							errorMessage = $(this).parent().find('.err_msg').text() || 'Please fill in all required fields';
						}
					} else {
						$(this).removeClass('invalid-ip').parent().find('.err_msg').removeClass('invalid-ip');
					}
				});
				
				if (!$('#register_tc').prop('checked')) {
					isValid = false;
					$('#register_tc').addClass('invalid-ip');
					if (!errorMessage) {
						errorMessage = 'Please accept the terms and conditions';
					}
				} else {
					$('#register_tc').removeClass('invalid-ip');
				}
				
				if (!isValid) {
					if (firstInvalid) {
						$(firstInvalid).focus();
					}
					showRegisterToast(errorMessage || 'Please fill in all required fields', 'error');
					return false;
				}
				
				$('#loading').removeClass('d-none');
				
				$.post(app_base_url + 'index.php/auth/register_on_light_box', $('form#register_user_form').serialize(), function(response) {
					$('#loading').addClass('d-none');
					
					if (response.status == 1) {
						showRegisterToast(response.data, 'success');
						$('#register_tc').prop('checked', false);
						$('input[name="email"], input[name="password"], input[name="confirm_password"]', 'form#register_user_form').val('');
					} else {
						showRegisterToast(response.data, 'error');
					}
				}).fail(function() {
					$('#loading').addClass('d-none');
					showRegisterToast('An error occurred. Please try again.', 'error');
				});
				
				return false;
			});
		}, 100);
	});
</script>

<!-- Toast/Snackbar for Registration Messages -->
<div class="toast-snackbar-wrapper d-none" id="register-toast-wrapper">
	<div role="alert" class="toast-snackbar" id="register-toast">
		<i class="material-icons toast-icon"></i>
		<span class="toast-message"></span>
		<button type="button" class="toast-close" aria-label="Close">
			<i class="material-icons">close</i>
		</button>
	</div>
</div>