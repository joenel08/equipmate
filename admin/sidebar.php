<style>
  .b {}

  .main-sidebar .brand-link {
    background-color: #056504 !important;
    /* color: #fff; */
    position: relative;

    z-index: 3;
  }

  .main-sidebar {
    position: relative;
    background-image: linear-gradient(#056504, #23cc1b);
    /* Fallback background color */
    background-size: cover;
    background-repeat: no-repeat;
    color: #fff;
    height: 100%;
    /* Adjust as needed */
    overflow: hidden;
    /* Hide overflow content */
  }


  .main-sidebar .side {
    margin-top: -20px;
  }

  .main-sidebar .nav .nav-item .nav-link {
    color: #fff;
    position: relative;
    z-index: 3;
  }

  .main-sidebar .nav .nav-item .nav-link.active {
    background-color: yellow;
    /* border-radius: 5px;
    margin-left: 10px;
    margin-right: 10px; */
    color: #056504;
    border-left: 5px solid #fff;
  }
</style>
<aside class="main-sidebar">


  <a href="./" class="brand-link shadow">
    <img src="./assets/img/eq_logo_white.png" alt="Logo" class="brand-image img-fluid" style="">
    <span class="brand-text h5 font-weight-bold text-white">Admin Panel</span>
  </a>



  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="assets/uploads/<?php echo $_SESSION['login_avatar'] ?>" alt="" class="img-circle elevation-2"
          alt="Img">
        <!-- <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
      </div>
      <div class="info">
        <a href="#"
          class="d-block text-white"><?php echo ucwords($_SESSION['login_firstname']) . ' ' . $_SESSION['login_lastname'] ?></a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu"
        data-accordion="false">
        <li class="nav-item dropdown">
          <a href="./" class="nav-link nav-home">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>

        <li class="nav-item dropdown">
          <a href="./index.php?page=categories_list" class="nav-link nav-categories_list">
            <i class="nav-icon 	fa fa-tag"></i>
            <!-- <img src="assets/img/senior_icon.png" class="nav-icon" alt=""> -->
            <p>
              Categories
            </p>
          </a>
        </li>


        <li class="nav-item dropdown">
          <a href="./index.php?page=materials_list" class="nav-link nav-materials_list">
            <i class="nav-icon 	fa fa-list"></i>
            <!-- <img src="assets/img/senior_icon.png" class="nav-icon" alt=""> -->
            <p>
              Materials List
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="./index.php?page=employee_list" class="nav-link nav-employee_list">
            <i class="nav-icon 	fa fa-users"></i>
            <!-- <img src="assets/img/senior_icon.png" class="nav-icon" alt=""> -->
            <p>
              Employee List
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="./index.php?page=distribution_list" class="nav-link nav-distribution_list">
            <i class="nav-icon 	fas fa-hands-helping"></i>
            <!-- <img src="assets/img/senior_icon.png" class="nav-icon" alt=""> -->
            <p>
              Distribution List
            </p>
          </a>
        </li>


        <li class="nav-item dropdown">
          <a href="./index.php?page=report_list" class="nav-link nav-report_list">
            <i class="nav-icon 	fa fa-chart-bar"></i>
            <!-- <img src="assets/img/senior_icon.png" class="nav-icon" alt=""> -->
            <p>
              Report List
            </p>
          </a>
        </li>




        <!-- <li class="nav-header text-uppercase">Publication</li>

        <li class="nav-item dropdown">

          <a class="nav-link nav-news_list" href="./index.php?page=news_list" id="">
            <i class="nav-icon  fa fa-newspaper"></i>
            <p>News</p>
          </a>
        </li>
        <li class="nav-item dropdown">

          <a class="nav-link nav-announcements" href="./index.php?page=announcements" id=""><i
              class="nav-icon  fa fa-archive"></i>
            <p>Announcements</p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="./index.php?page=assistance_registration" class="nav-link nav-assistance_registration">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>
             Assistance Registration
            </p>
          </a>
        </li> -->
        <li class="nav-header text-uppercase">Settings</li>
        <li class="nav-item">
          <a href="#" class="nav-link nav-edit_user">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>
              Admin Users
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                <i class="fas fa-angle-right nav-icon"></i>
                <p>Add New</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                <i class="fas fa-angle-right nav-icon"></i>
                <p>List</p>
              </a>
            </li>
          </ul>
        </li>



        <li class="nav-header text-uppercase">Action</li>
        <li class="nav-item dropdown">

          <a class="nav-link" href="javascript:void(0)" id="manage_account"><i class="nav-icon fa fa-cog"></i>
            <p>Manage Profile</p>
          </a>
        </li>

        <li class="nav-item dropdown">
          <a href="ajax.php?action=logout" class="nav-link">
            <i class="nav-icon fa fa-sign-out-alt"></i>
            <p>Logout</p>

          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>

<script>
  $('#manage_account').click(function () {
    uni_modal('Manage Account', 'manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
  })
</script>
<script>
  $(document).ready(function () {
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
    if (s != '')
      page = page + '_' + s;
    if ($('.nav-link.nav-' + page).length > 0) {
      $('.nav-link.nav-' + page).addClass('active')
      if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
        // $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
        $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
      }
      if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
        $('.nav-link.nav-' + page).parent().addClass('menu-open')
      }

    }

  })
</script>