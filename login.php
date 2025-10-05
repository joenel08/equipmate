<?php
session_start();
include('./db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Login | Equipmate </title>
	<?php include('header.php'); ?>
	<?php
	global $message;
	if (isset($_SESSION['login_id'])) {
		header("location:index.php?page=home");
	}
	?>
</head>
<style>
	.login-form-bx .box-skew1 {
		z-index: 1 !important;
		background-color: #fbfbfb !important;
	}

	.login-form-bx .box-skew1 {
		background-image: url('assets/img/bg-logo.png');
		content: "";
		background-size: cover;
		background-position: bottom;
		height: 100%;
		position: absolute;
		width: 140% !important;
		right: -28px;
		top: 0;
		z-index: 11 !important;
		-webkit-transform: skew(-5deg);
		transform: skew(-5deg);
	}


	.login-form-bx {
		width: 90% !important;
	}

	.cards .authincation-content {
		box-shadow: 0 12px 23px 0 rgba(62, 73, 84, .08) !important;
		background-color: #fff !important;
		content: "";
		height: 100% !important;
		width: 90% !important;
		position: relative !important;
		left: 210px;
		top: 0;
		margin-top: 50px;
		padding-top: 50px;
		padding-bottom: 50px;
		padding-left: 50px;

		padding-right: 100px !important;
	}



	.login-logo img {
		margin-top: -13px !important;
	}

</style>

<body class="" style="overflow-x: hidden;">
	<div class="header text-center mb-5">
		<div class="container-fluid">
			<div class="login-form-bx">
				<div class="row">
					<div class="col-md-6 cards">
						<div class="authincation-content">

							<a class="login-logo " href="">
								<img src="assets/img/eq_logo.png" alt="" height="200px" width="auto">
							</a>
							<hr>
							<label class="m-0 p-0 text-muted text-sm">An inventory system for Deflin Albano Stand Alone
								Senior High School</label>
							<hr>
							<span id="status-alert"></span>
							<form id="login-form" action="" method="POST"
								class="offer-txt justify-content-center align-self-center">
								<h5 class="text-success text-left font-weight-bold">Input Credentials Here</h5>

							
								<div class="form-group mt-3">
									<input type="text" id="email" name="email" class=" form-control"
										placeholder="Enter Email">
									<div class="input-group input-focus mt-3">
										<input type="password" class="border-right-0 form-control"
											placeholder="Enter Password" name="password" id="password">
										<div class="input-group-prepend passs">
											<span class="input-group-text border-left-0 bg-white">
												<i id="mata" class="text-secondary fa fa-eye-slash"
													onclick="myFunction()"></i>
											</span>
										</div>
									</div>
									
									<button type="submit"
										class="btn btn-md btn-block btn-flat mt-3 border-0 btn-success text-white">Login
										now</button>
								</div>
							</form>


						</div>
					</div>
					<div class="col-lg-6 col-md-5 d-flex box-skew1">

					</div>
				</div>
			</div>


		</div>
	</div>


	<?php
	include('footer.php');
	?>


	<script>
		function myFunction() {
			var x = document.getElementById("password");

			if (x.type === "password") {
				x.type = "text";
				$('#mata').removeClass('fa fa-eye-slash');
				$('#mata').addClass('fa fa-eye');
			} else {
				x.type = "password";
				$('#mata').removeClass('fa fa-eye');
				$('#mata').addClass('fa fa-eye-slash');
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
			
			// Validation checks
			if (!email || !password ) {
				let alertMessage = '<div class="alert alert-danger">';
				if (!email) alertMessage += '<p>Email is required.</p>';
				if (!password) alertMessage += '<p>Password is required.</p>';
			

				$('#status-alert').html(alertMessage);
				$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				end_load();
				return; // Stop further execution
			}

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


</body>

</html>