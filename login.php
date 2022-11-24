<?php
$curPageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
$pageUrl = "";


if (!empty($_SESSION["current_host"])) {
  $pageUrl = "http://" . $_SESSION["current_host"] . "/abs/";
} else {
  $pageUrl = "http://" . $_SERVER["HTTP_HOST"] . "/abs/";
}

$path = $_SERVER['DOCUMENT_ROOT'] . "/abs/includes/";
$path1 = $path . "db_connect.php";
$path2 = $path . "functions.php";
$path3 = $path . "allow_page_access.php";



include($path1);
include($path2);
$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
$fetchData = mysqli_fetch_array($getData);

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
  <link rel="stylesheet" href="<?php echo $pageUrl; ?>css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="<?php echo $pageUrl; ?>images/abs/logo.png" />
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese" rel="stylesheet">


  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins';
      font-size: 16px;
      color: black;
    }

    .customize-abs-btn {
      background-color: <?php echo randomizeBtnColor($btn_color1, $btn_color2) ?>;
      border: none;
      color: white;
      border-radius: 4px !important;
    }

    select {
      color: black;
    }

    select option {
      color: black;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="<?php echo $pageUrl; ?>images/abs/profile.png" alt="logo" style="width: 25% !important;">
                <span style="font-size: 20px;">Premium</span>
              </div>
              <h6 class="font-weight-light">Sign in to continue.</h6>

              <form class="p-3" method="post">

                <?php

                if (isset($_POST['loginBtn'])) {

                  $username = trim($_POST['user_id']);
                  $password = $_POST['password'];
                  $role = $_POST["role"];

                  $sql = "SELECT * FROM users WHERE username = '$username' AND user_password = '$password' AND user_role='$role'";
                  $result = $con->query($sql);

                  if ($result->num_rows > 0) {

                    $value = $result->fetch_array();
                    $user_id = $value['username'];
                    $login_status = $value['login_status'];
                    $store_id = $value['store_id'];

                    if ($login_status == 1) {

                      $_SESSION["user"] = $user_id;
                      $_SESSION["role"] = $role;
                      $_SESSION["store_id"] = $store_id;

                      $priviledges = checkPriviledge($connect, $user_id);

                      include "includes/priviledge_controller.php";

                      //SET LOGIN TIME
                      $now = date("Y-m-d h:i:s");
                      $new_query = $con->query("INSERT INTO login_log (store_id,username,user_role,login_time) 
                         VALUES('$store_id','$username','$role','$now')");

                      if($role == "associate"){
                        header("location: views/make_sales");
                      }else{
                        header("location: dashboard");

                      }
                    } else {
                      $errors = "Login has been Disabled temporarily.";
                    }
                  } else {

                    $errors = "Incorrect Credentials! Please cross-check and try again.";
                  } // /else

                } // /else not empty username // password


                if (isset($errors)) {
                  echo "<div class='alert alert-danger'>Oopsss! Wrong Login Credentials..Try again</div><br><br>";
                }
                ?>
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="user_id" id="user_id" placeholder="Login ID">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Password">
                </div>

                <div class="form-group">
                  <select class="form-control form-control-lg" name="role">
                    <option selected="selected">-- Select your Role -- </option>
                    <option value="owner">Owner</option>
                    <option value="manager">Manager</option>
                    <option value="accountant">Accountant</option>
                    <option value="associate">Associate</option>
                  </select>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block customize-abs-btn btn-lg" name="loginBtn">LOGIN</button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="<?php echo $pageUrl; ?>vendors/js/vendor.bundle.base.js"></script>
  <script src="<?php echo $pageUrl; ?>js/off-canvas.js"></script>
  <script src="<?php echo $pageUrl; ?>js/hoverable-collapse.js"></script>
  <script src="<?php echo $pageUrl; ?>js/template.js"></script>
  <script src="<?php echo $pageUrl; ?>js/settings.js"></script>
  <script src="<?php echo $pageUrl; ?>js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>