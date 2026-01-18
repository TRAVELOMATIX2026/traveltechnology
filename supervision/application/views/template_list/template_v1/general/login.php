<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('admin-login.css');?>" rel="stylesheet" type="text/css" />
<?php


if (isset($login) == false || is_object($login) == false) {
	$login = new Provab_Page_Loader('login');
}
?>
<div class="login_bg">
	<!-- Left Panel - Login Form -->
	<div class="login-box">
		<div class="login-form-container">
			<figure class="login-logo">
				<img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()) ?>"
					alt="logo">
			</figure>
			<div class="login-box-body">
				<h1 class="login-box-msg">Sign In</h1>
				<p class="login-subtitle">Enter your email and password to sign in!</p>
				<?php $sparam = $this->input->cookie('login_cookie', TRUE);
				//	debug($sparam ); exit; 
				if (!empty($sparam)) {
					echo $login->generate_form('login', array('email' => '', 'password' => ''), array('opt_number'));
				} else {
					echo $login->generate_form('login', array('email' => '', 'password' => ''));

				} ?>
			</div>
			<div class="card-footer logn_footr">
				<?php include_once 'forgot-password.php'; ?>
			</div>
		</div>
	</div>

	<!-- Right Panel - Branding -->
	<div class="login-branding">
		<div class="branding-content">
			<h2 class="branding-title">Travel Management Excellence</h2>
			<p class="branding-subtitle">Complete Travel Booking & Management Solution for B2B & B2C</p>
			
			<!-- Dashboard Preview Mockup -->
			<div class="dashboard-preview">
				<div class="preview-header">
					<div class="preview-dots">
						<div class="preview-dot"></div>
						<div class="preview-dot"></div>
						<div class="preview-dot"></div>
					</div>
				</div>
				<div class="preview-stats">
					<div class="stat-card">
						<div class="stat-label">Total Bookings</div>
						<div class="stat-value">12,458</div>
					</div>
					<div class="stat-card">
						<div class="stat-label">Revenue</div>
						<div class="stat-value">$2.4M</div>
					</div>
					<div class="stat-card">
						<div class="stat-label">Active Users</div>
						<div class="stat-value">3,892</div>
					</div>
				</div>
				<div class="preview-chart">
					<div class="chart-bar"></div>
					<div class="chart-bar"></div>
					<div class="chart-bar"></div>
					<div class="chart-bar"></div>
					<div class="chart-bar"></div>
					<div class="chart-bar"></div>
				</div>
			</div>
			
			<!-- Trusted By Section -->
			<div class="trusted-by">
				<p class="trusted-text">Trusted by travel agencies and businesses worldwide</p>
				<div class="company-logos">
					<div class="company-logo">Expedia</div>
					<div class="company-logo">Booking.com</div>
					<div class="company-logo">TripAdvisor</div>
					<div class="company-logo">Amadeus</div>
					<div class="company-logo">Sabre</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).on('click', '.send_otp', function () {
		// $(".email, .password, label[for=email], label[for=password]").hide();


		var email = $('#email').val();

		if (email == '') {
			$("#email").addClass("invalid-ip");
			return false;
		}
		var password = $('#password').val();
		if (password == '') {
			$("#password").addClass("invalid-ip");
			return false;
		}


		toastr.info('Please Wait!!!');
		$.post(app_base_url + "index.php/general/send_otp", { email: email, password: password }, function (result) {
			if (result) {
				if (result == true) {
					$(".email, .password, label[for=email], label[for=password]").hide();
					$('.opt_number').removeClass('hide'); $("label[for=opt_number]").show();
					$("#opt_number").prop('required', true);
					$("#opt_number").prop('readonly', false);
					$("#login_custom").prop("type", "submit");
					$("#login_custom").html("Login");
					$("#login_custom").removeClass("send_otp");
					toastr.info("OTP sent Successfully!!!");
					// window.location.reload();
				}
				if (result == false) {
					toastr.info("Invalid Login Details");
				}


			}
		});

	});


</script>