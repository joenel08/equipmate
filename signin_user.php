<?php
session_start();
include('./db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Sign-in | CitiCare </title>
    <?php include('header.php'); ?>
    <?php
    global $message;
    if (isset($_SESSION['login_id'])) {
        header("location:index.php?page=home");
    }
    ?>


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
                    <a href="../../index2.html" class="h3 text-center"><b>Welcome </b>User</a>

                </div>
                <p class="login-box-msg">Sign in to start your session</p>

                <form id="login-form" action="" method="POST">
                    <div class="input-group mb-3">
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email"
                            >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                            >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span id="mata" class="fas fa-lock" onclick="myFunction()"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="social-auth-links text-center mt-2 mb-3">
                    <button type="submit"
                        class="btn btn-md btn-block btn-flat mt-3 border-0 btn-success text-white">Login now</button>

                </div>
                </form>

                
                <!-- /.social-auth-links -->

                <!-- <p class="mb-1 text-center text-muted">
                    <a href="forgot-password.html" class="text-muted">I forgot my password</a>
                </p> -->
                <!-- <p class="mb-0 text-center text-muted">
                    <a href="#" class="text-center text-success">Download mobile app here</a>
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
      

</body>

</html>