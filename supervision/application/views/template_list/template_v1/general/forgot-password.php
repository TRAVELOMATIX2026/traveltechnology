<?php
if (isset($login) == false || is_object($login) == false) {
  $login = new Provab_Page_Loader('login');
}
?>
<style>
  /* Forgot Password Link Styling */
  #forgot-password {
    color: #00a8e8;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    display: inline-block;
  }

  #forgot-password::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #00a8e8, #0077b6);
    transition: width 0.3s ease;
  }

  #forgot-password:hover {
    color: #0077b6;
  }

  #forgot-password:hover::after {
    width: 100%;
  }

  /* Modal Styling */
  .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
  }

  .modal-header.bg-primary {
    background: linear-gradient(135deg, #00a8e8 0%, #0077b6 100%) !important;
    padding: 20px 25px;
    border: none;
  }

  .modal-header .modal-title {
    color: #ffffff;
    font-weight: 600;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .modal-header .modal-title i {
    font-size: 22px;
  }

  .modal-header .close {
    color: #ffffff;
    opacity: 0.9;
    text-shadow: none;
    font-size: 28px;
    font-weight: 300;
    transition: all 0.3s ease;
  }

  .modal-header .close:hover {
    opacity: 1;
    transform: rotate(90deg);
  }

  .modal-body {
    padding: 30px 25px;
    background: #ffffff;
  }

  .modal-body .alert {
    border-radius: 12px;
    padding: 15px 18px;
    border: none;
    background: #f7fafc;
    color: #4a5568;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .modal-body .alert i {
    color: #00a8e8;
    font-size: 18px;
  }

  .modal-body .form-group {
    margin-bottom: 20px;
  }

  .modal-body label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
    font-size: 14px;
  }

  .modal-body input[type="text"],
  .modal-body input[type="email"],
  .modal-body input[type="tel"] {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 15px;
    transition: all 0.3s ease;
    font-family: 'Inter', sans-serif;
  }

  .modal-body input[type="text"]:focus,
  .modal-body input[type="email"]:focus,
  .modal-body input[type="tel"]:focus {
    border-color: #00a8e8;
    box-shadow: 0 0 0 4px rgba(0, 168, 232, 0.1);
    outline: none;
  }

  .modal-footer {
    padding: 20px 25px;
    background: #f7fafc;
    border: none;
    display: flex;
    gap: 10px;
  }

  .modal-footer .btn {
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.3s ease;
    border: none;
  }

  .modal-footer .btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
  }

  .modal-footer .btn-secondary:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
  }

  .modal-footer .btn-success {
    background: linear-gradient(135deg, #00a8e8 0%, #0077b6 100%);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(0, 168, 232, 0.4);
  }

  .modal-footer .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 168, 232, 0.5);
  }

  .modal-body .alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
  }

  .modal-body .alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
  }
</style>
<a class="handCursor " data-bs-toggle="modal" data-bs-target="#myModal" href="#" id="forgot-password">Forgot Password ?
</a>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-desktop"></i> Reset Password</h4>
      </div>
      <div class="modal-body">
        <div class="alert ">
          <p><i class="fa fa-lock"></i> Please Provide Us Your Details To Reset Your Password</p>
        </div>
        <?php echo get_default_image_loader();//data-utility-loader ?>
        <?php
        echo $login->generate_form('forgot_password');
        ?>
        <div id="recover-title-wrapper" class="alert alert-success" style="display:none">
          <p><i class="fa fa-warning"></i> <span id="recover-title"></span></p>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="reset-password-trigger">Reset Password Now</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#reset-password-trigger').on('click', function () {
      $('#recover-title-wrapper').hide();
      $('#data-utility-loader').show();
      $.post(app_base_url + "index.php/ajax/forgot_password/", { email: $('#recover_email').val(), 'phone': $('#recover_phone').val() }, function (response) {
        if (response.status) {
          $('#recover-title-wrapper').removeClass('alert-danger').addClass('alert-success');
        } else {
          $('#recover-title-wrapper').removeClass('alert-success').addClass('alert-danger');
        }
        $('#recover-title').text(response.data);
        $('#recover-title-wrapper').show();
        $('#data-utility-loader').hide();
      });
    });
  });
</script>