<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Verify OTP | CitiCare</title>
  <?php include('header.php'); ?>
</head>

<body class="login-page" style="min-height: 464px;">
  <div class="login-box">

    <div class="card card-outline card-success">
      <div class="card-header text-center">
        <div class="login-logo">
          <img src="assets/img/icon_logo_green.png" width="50%" alt="">
        </div>
        <img src="assets/img/citicare_green.png" width="50%" alt="">
      </div>

      <div class="card-body">
        <p class="login-box-msg mt-2">Enter the One-Time Password (OTP) we sent to your registered contact.</p>

        <form id="otp-form" action="ajax.php?action=verify_otp" method="POST">
          <div class="input-group mb-3">
            <input type="text" id="otp" name="otp" class="form-control" placeholder="Enter 6-digit OTP" maxlength="6" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-key"></span>
              </div>
            </div>
          </div>

          <div class="social-auth-links text-center mt-2 mb-3">
            <button type="submit" class="btn btn-md btn-block btn-flat border-0 btn-success text-white">
              Verify OTP
            </button>
          </div>
          <p class="text-center text-muted small">
            Didn't receive the OTP? <a href="#" id="resend-otp" class="text-success">Resend</a>
          </p>
        </form>

      </div>
    </div>
  </div>

  <?php include('footer.php'); ?>

  <script>
    $('#otp-form').submit(function(e) {
      e.preventDefault();
      start_load();

      $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
          if (resp == 1) {
            alert_toast('OTP Verified Successfully! Redirecting...');
            location.href = 'index.php?page=home';
          } else {
            alert_toast('Invalid OTP. Please try again.', 'error');
          }
          end_load();
        },
        error: function(err) {
          console.error(err);
          end_load();
        }
      });
    });

    $('#resend-otp').click(function(e) {
      e.preventDefault();
      start_load();
      $.ajax({
        url: 'ajax.php?action=resend_otp',
        method: 'POST',
        success: function(resp) {
          if (resp == 1) {
            alert_toast('OTP resent successfully!', 'success');
          } else {
            alert_toast('Error sending OTP. Try again later.', 'error');
          }
          end_load();
        },
        error: function(err) {
          console.error(err);
          end_load();
        }
      });
    });
  </script>
</body>

</html>
