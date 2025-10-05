<?php
session_start();
include('./db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Sign-up | CitiCare </title>
    <?php include('header.php'); ?>
    <?php
    global $message;
    if (isset($_SESSION['login_id'])) {
        header("location:index.php?page=home");
    }
    ?>

<style>
    .custom-radio {
		display: none;
	}

	.custom-radio-label {
		position: relative;
		padding-left: 30px;
		cursor: pointer;
		font-weight: 400;
		color: #495057;
		margin-bottom: 10px;
		user-select: none;
	}

	.custom-radio-label::before {
		content: '';
		position: absolute;
		top: 50%;
		left: 0;
		transform: translateY(-50%);
		width: 20px;
		height: 20px;
		border: 2px solid #adb5bd;
		background-color: #fff;
		box-sizing: border-box;
		transition: background-color 0.2s, border-color 0.2s;
	}

	.custom-radio:checked+.custom-radio-label::before {
		background-color: #007bff;
		border-color: #007bff;
	}

	.custom-radio-label::after {
		content: '';
		position: absolute;
		top: 50%;
		left: 8px;
		width: 5px;
		height: 8px;
		border: solid #fff;
		border-width: 0 2px 2px 0;
		transform: translateY(-50%) rotate(45deg) scale(0);
		opacity: 0;
		transition: transform 0.2s ease-in-out, opacity 0.2s;
	}

	.custom-radio:checked+.custom-radio-label::after {
		transform: translateY(-50%) rotate(45deg) scale(1);
		opacity: 1;
	}
</style>
</head>

<body class="login-page" style="min-height: 464px;">
    <div class="login-box">

        <!-- /.login-logo -->
        <div class="card card-outline card-success">
            <div class="card-header text-center">
                <div class="login-logo">
                    <img src="assets/img/icon_logo_green.png" width="50%" alt="">
                </div>
                <!-- <a href="../../index2.html" class="h1"><b>Citi</b>Care</a> -->
                <img src="assets/img/citicare_green.png" width="50%" alt="">

            </div>
            <div class="card-body">
                <span id="status-alert"></span>
                <div class="text-center">
                    <a href="../../index2.html" class="h3 text-center"><b>Welcome </b>To CitiCare</a>
                    <p class="m-0 p-0 text-muted">A Digital QR Code Registration and Real-Time Monitoring System with
                        SMS Notifications for PWDs, Senior Citizens, and Solo Parents</p>

                </div>
                <p class="login-box-msg mt-3">We must first verify that it was you.</p>

                <form id="login-form" action="" method="POST">
                    <label class="mt-2">Are you a:</label>
                    <div class="">
							<div class="form-check mr-3 align-items-center">
								<input type="radio" name="login" id="admin" class="form-check-input custom-radio"
									value="1">
								<label for="admin"
									class="text-secondary font-weight-normal mt-2 form-check-label custom-radio-label">
									Senior Citizen
								</label>
							</div>
							<div class="form-check mr-3">
								<input type="radio" name="login" id="faculty" class="form-check-input custom-radio"
									value="2">
								<label for="faculty"
									class="text-secondary font-weight-normal mt-2 form-check-label custom-radio-label">
									Person With Disability (PWD)
								</label>
							</div>
							<div class="form-check mr-3">
								<input type="radio" name="login" id="student" class="form-check-input custom-radio"
									value="3">
								<label for="student"
									class="text-secondary font-weight-normal mt-2 form-check-label custom-radio-label">
									Solo Parent
								</label>
							</div>
						</div>
                        <label class="mt-2">Input Information:</label>
                    <div class="input-group mb-3">
                        <input type="text" id="contact_info" name="contact_info" class="form-control"
                            placeholder="Contact Number of Email Address">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span id="mata" class="fas fa-lock" onclick="myFunction()"></span>
                            </div>
                        </div>
                    </div>

                    <div class="social-auth-links text-center mt-2 mb-3">
                        <button type="submit"
                            class="btn btn-md btn-block btn-flat mt-3 border-0 btn-success text-white">Register
                            Now</button>
                        <p class="text-danger text-sm mt-2 font-italic">Note: A One-Time-Pin (OTP) will be sent to you
                            prefered contact information.</p>

                    </div>
                </form>


                <!-- /.social-auth-links -->

                <!-- <p class="mb-1 text-center text-muted">
                    <a href="forgot-password.html" class="text-muted">I forgot my password</a>
                </p> -->

            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <?php
    include('footer.php');
    ?>
    <script>
        function myFunction() {
            var x = document.getElementById("password");

            if (x.type === "password") {
                x.type = "text";
                $('#mata').removeClass('fas fa-lock');
                $('#mata').addClass('fas fa-unlock-alt');
            } else {
                x.type = "password";
                $('#mata').removeClass('fas fa-unlock-alt');
                $('#mata').addClass('fas fa-lock');
            }
        }
    </script>

    <script>
        $('#login-form').submit(function (e) {
            e.preventDefault();
            start_load();

            // Reset previous alerts
            $('#status-alert').html('');
            $('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');

            const email = $('#email').val().trim();
            const password = $('#password').val().trim();


            $.ajax({
                url: 'ajax.php?action=login',
                method: 'POST',
                data: $(this).serialize(),
                error: err => {
                    console.log(err);
                    $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
                },
                success: function (resp) {
                    if (resp == 1) {
                        alert_toast('Successfully Logged-in! Redirecting...');
                        location.href = 'index.php?page=home';
                    } else if (resp == 2) {
                        alert_toast("Incorrect Details", 'error');
                        $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
                        end_load();
                    } else if (resp == 3) {
                        $('#status-alert').append('<div class="alert alert-danger">Account not verified by thesis adviser. Please wait, thank you!</div>');
                        end_load();
                    }
                }
            });
        });
    </script>
<script>
    // Allow only one checkbox to be selected
    document.querySelectorAll('.user-type').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                document.querySelectorAll('.user-type').forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            }
        });
    });
</script>


</body>

</html>