<?php

include '../includes/db_connect.php';
include "../includes/functions.php";

?>

<?php

session_start();

$curPageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
$pageUrl = "";



if (!empty($_SESSION["current_host"])) {
  $pageUrl = "http://" . $_SESSION["current_host"] . "/abs/";
} else {
  $pageUrl = "http://" . $_SERVER["HTTP_HOST"] . "/abs/";
}

if(strpos($_SERVER["SCRIPT_NAME"],"ems") !== false && $_SESSION["role"] != "owner" && $_SESSION["role"] != "owner" && $_SESSION["role"] != "manager"){
  header("location:" .$pageUrl."noaccess");
}



$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
$fetchData = mysqli_fetch_array($getData);

//check priviledges and allow or disallow page access
include "../includes/allow_page_access.php";

$currency = htmlspecialchars_decode($fetchData["currency"]);


?>
<!DOCTYPE html>
<html>

<head>

  <title><?php echo ucwords($fetchData["company_name"]); ?> - <?php echo ucwords(str_replace("_", " ", substr($curPageName, 0, strrpos($curPageName, "."))));  ?></title>
  <!-- datatabless cs and js -->
  <link rel="stylesheet" type="text/css" href="<?php echo $pageUrl; ?>Datatables/datatables.min.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>Datatables/AutoFill-2.3.7/css/autoFill.dataTables.min.css">
  <!-- custom css -->
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>custom/css/custom.css">
  <!-- font awesome -->
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>font-awesome/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>assests/plugins/datatables/jquery.dataTables.min.css">
  <!-- file input -->
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>assests/plugins/fileinput/css/fileinput.min.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>style/animate.css">
  <link rel="icon" type="image/png" href="<?php echo $pageUrl; ?>images/ABS-logo/logo.png">
  <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>style/style.css">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>style/bootstrap.min.css">

  <link href="<?php echo $pageUrl; ?>datetimepicker/jquery.datetimepicker.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>assests/jquery-ui/jquery-ui.min.css">

  <script src="<?php echo $pageUrl; ?>Datatables/jQuery-3.3.1/jquery-3.3.1.min.js"></script>
  <script src="<?php echo $pageUrl; ?>canvasjs/canvasjs.min.js"></script>
  <script src="<?php echo $pageUrl; ?>datetimepicker/jquery.datetimepicker.js"></script>
  <script src="<?php echo $pageUrl; ?>assests/bootstrap/js/bootstrap.min.js"></script>

  <style type="text/css">
   body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins';
      font-size: 16px;
      color: black;
    }

    .customize-abs-btn{
    background-color: <?php echo randomizeBtnColor($btn_color1 ,$btn_color2) ?>;
    border: none;
    color: <?php echo $btn_txtcolor; ?>;
  }
  </style>
</head>

<body style="font-family: Leelawadee;background-color: <?php echo $bg_color; ?>">
  <nav class="navbar navbar-static-top" style="background-color: <?php echo $headerbg_color; ?>;color:white;">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed bg-white" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar" style="background-color: white !important;"></span>
          <span class="icon-bar" style="background-color: white !important;"></span>
          <span class="icon-bar" style="background-color: white !important;"></span>
        </button>
        <a href="#" class="navbar-brand">
          <img src="<?php echo $pageUrl; ?>images/ABS-logo/logo_white_bg.png" style="width: 8%;">

        </a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right" style="margin-top: -3%;">

          <li id="navDashboard"><a href="<?php if($_SESSION["role"] == "owner"){echo $pageUrl."dashboard";}elseif($_SESSION["role"] == "manager"){echo $pageUrl."dashboard";} ?>" style="color: white;"><i class="fas fa-arrow-left"></i> Back to POS</a></li>

          <li id="navDashboard"><a href="<?php echo $pageUrl; ?>ems/ems_employees" style="color: white;">
          <i class="fas fa-user"></i> All Employees</a></li>

          <li id="navDashboard"><a href="<?php echo $pageUrl; ?>ems/ems_payouts" style="color: white;">
          <i class="fas fa-credit-card"></i> Payouts</a></li>

          <li id="navReport" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" style="color: white;"> <i class="fas fas fa-info"></i> Report <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li ><a href="<?php echo $pageUrl; ?>ems/ems_report"> <i class="fas fa-calendar"></i> Report</a></li>
              <li id=""><a href="<?php echo $pageUrl; ?>ems/ems_best_employee" class="nav-link"><i class="fas fa-user"></i> Best Employee </a></li>
            </ul>
          </li>

          <li class="dropdown" id="navOrder">
            <a href="#" class="dropdown-toggle" style="color: white;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-th"></i> More <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li ><a href="<?php echo $pageUrl; ?>ems/ems_time_attendance"> <i class="fas fa-clock"></i> Time Attendance</a></li>
              <li ><a href="<?php echo $pageUrl; ?>ems/ems_settings"> <i class="fas fa-cog"></i> EMS Settings</a></li>
            </ul>
          </li>

          <li class="dropdown" id="navSetting">
            <a href="#" class="dropdown-toggle" style="color: white;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-user"></i> <?php echo "Hi " . ucwords($_SESSION["user"]); ?> <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li id="topNavLogout"><a href="<?php if($_SESSION["role"] == "owner"){echo $pageUrl."owner/logout";}elseif($_SESSION["role"] == "manager"){echo $pageUrl."manager/logout";} ?>"> <i class="fas fa-log-out"></i> Logout</a></li>
            </ul>
          </li>


        </ul>
      </div><!-- /.navbar-collapse -->

      
    </div><!-- /.container-fluid -->
  </nav>

  <div class="container-fluid">