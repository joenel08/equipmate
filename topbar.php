<style>
  .main-header {
    margin-bottom: 0px !important;
    background-color: yellow !important;
  

  }

  .user-img {
    border-radius: 50%;
    height: 25px;
    width: 25px;
    object-fit: cover;
  }
</style>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link text-dark" data-widget="pushmenu" href="" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <!-- <li>
      <a class="nav-link" href="./" role="button"> <img src="assets/img/citicare_green.png" height="20px" alt=""></a>
    </li> -->
  </ul>

  <ul class="navbar-nav ml-auto">

    <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    <!-- <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="far fa-bell"></i>
        <span class="badge badge-danger navbar-badge">1</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        <span class="dropdown-item dropdown-header">1 Notification</span>
        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item">
          <i class="fas fa-check-circle mr-2 text-success"></i> Registration Accepted! <br> You can now get your ID
          card.
          <span class="float-right text-muted text-sm">Just now</span>
        </a>
        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li> -->

    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
        <span>
          <div class="d-flex badge-pill">

            <span><b><?php echo ucwords($_SESSION['login_firstname']).' '.$_SESSION['login_lastname'] ?></b> <?php
            if($_SESSION["login_type"] == 1){
              echo 'Administrator&nbsp;&nbsp; ';
            }elseif($_SESSION["login_type"] == 2)
            {
              echo 'Faculty&nbsp;&nbsp;';
            }elseif($_SESSION["login_type"] ==3){
              echo 'Student&nbsp;&nbsp;';
            }
            
            ?></span>
            <span class=""><img src="assets/uploads/<?php echo $_SESSION['login_avatar'] ?>" alt=""
                class="user-img border "></span>
            <!-- <span class="fa fa-angle-down ml-2"></span> -->
          </div>
        </span>
      </a>
      <!-- <div class="dropdown-menu w-100" aria-labelledby="account_settings" style="">
              <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage Profile</a>
              <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
            </div> -->
    </li>
  </ul>
</nav>
<!-- /.navbar -->
<!-- <script>
     $('#manage_account').click(function(){
        uni_modal('Manage Account','manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
      })
  </script> -->