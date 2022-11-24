<?php
$path = $_SERVER['DOCUMENT_ROOT'] . "/abs/includes/";
$path1 = $path . "db_connect.php";
$path2 = $path . "functions.php";
$path3 = $path . "allow_page_access.php";

include($path1);
include($path2);

include "route.php";

include($path3);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo ucwords($fetchData["company_name"]); ?> - <?php echo ucwords(str_replace("_", " ", substr($curPageName, 0, strrpos($curPageName, "."))));  ?></title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>vendors/feather/feather.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="<?php echo $pageUrl; ?>js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>font-awesome/css/all.css">

  <!-- endinject -->
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>assests/plugins/datatables/jquery.dataTables.min.css">
  <!-- file input -->
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>assests/plugins/fileinput/css/fileinput.min.css">
  <link href="<?php echo $pageUrl; ?>datetimepicker/jquery.datetimepicker.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="<?php echo $pageUrl; ?>images/abs/logo.png" />
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>assests/css/cart.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>assests/jquery-ui/jquery-ui.min.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>libraries/uikit-3.7.6/css/uikit.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>css/abs.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese" rel="stylesheet">


  <script src="<?php echo $pageUrl; ?>vendors/js/vendor.bundle.base.js"></script>

  <script src="<?php echo $pageUrl; ?>Datatables/datatables.min.js"></script>
  <script src="<?php echo $pageUrl; ?>vendors/datatables.net/jquery.dataTables.js"></script>

  <script src="<?php echo $pageUrl; ?>assests/jquery-ui/jquery-ui.min.js"></script>
  <script src="<?php echo $pageUrl; ?>assests/canvasjs/canvasjs.min.js"></script>
  <script src="<?php echo $pageUrl; ?>js/template.js"></script>
  <script src="<?php echo $pageUrl; ?>js/off-canvas.js"></script>
  <script src="<?php echo $pageUrl; ?>libraries/uikit-3.7.6/js/uikit.min.js"></script>

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins';
      font-size: 14px;
      color: black;
    }

    .customize-abs-btn {
      background-color: <?php echo randomizeBtnColor($btn_color1, $btn_color2) ?>;
      border: none;
      color: white;
      padding: 7px 10px;
      border-radius: 4px !important;
    }

    .blink {
      width: 200px;
      height: 50px;
      background-color: magenta;
      padding: 15px;
      text-align: center;
      line-height: 50px;
    }

    .start {
      font-size: 14px;
      font-family: consolas;
      color: white;
      animation: blink 1s linear infinite;
    }

    @keyframes blink {
      0% {
        opacity: 0;
      }

      50% {
        opacity: .5;
      }

      100% {
        opacity: 1;
      }
    }
  </style>

</head>

<body>


  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row hidden-print">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" style="color:maroon !important" href="<?php echo $pageUrl; ?>dashboard"><img src="<?php echo $pageUrl; ?>images/abs/logo.png" class="mr-2 ml-4" alt="logo" /> ABS PREMIUM</a>
        <a class="navbar-brand brand-logo-mini" href="<?php echo $pageUrl; ?>dashboard"><img src="<?php echo $pageUrl; ?>images/abs/logo.png" alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown" style="margin-top: -20px;">
              <i class="icon-ellipsis fa-2x"></i>

            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <!-- <a class="dropdown-item">
                <i class="ti-book text-primary"></i>
                User Manual
              </a> -->

              <?php

              $url = $_SERVER['SERVER_NAME'];

              if (!strpos($url, 'graceandmercy')) {
              ?>

                <!-- <a class="dropdown-item">
                  <i class="fas fa-globe text-primary"></i>
                  Work Online
                </a> -->
              <?php
              } else {
              ?>
                <!-- <a class="dropdown-item">
                  <i class="fas fa-server text-primary"></i>
                  Work Offline
                </a> -->
              <?php

              }

              ?>

              <a class="dropdown-item" href="<?php echo $pageUrl . "logout"; ?>">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>

        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>



    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->