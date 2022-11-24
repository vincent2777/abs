<?php

include "includes/check_db.php";

$create_db_sql = 'CREATE DATABASE absaccounting';
$result = mysqli_query($first_con, $create_db_sql);
error_reporting(0);
if (isset($_POST["dbInstallation"])) {

    $filepath = "includes/db/absaccounting.sql";

    // Temporary variable, used to store current query
    $templine = '';
    $lines = file($filepath);
    $error = '';
    foreach ($lines as $line) {
        // Skip it if it's a comment
        if (substr($line, 0, 2) == '--' || $line == '') {
            continue;
        }

        $templine .= $line;
        if (substr(trim($line), -1, 1) == ';') {
            // Perform the query
            if (!mysqli_query($first_con, $templine)) {

                $install_status =  "<div class='alert alert-danger'> Database Could not be Installed.</div><meta http-equiv='refresh' content='2; url=completeSetup' />";
            } else {

                $install_status =  "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Database Installed</div>";
            }
            $templine = '';
        }
    }
}



$pageUrl = "";
if (!empty($_SESSION["current_host"])) {
    $pageUrl = "http://" . $_SESSION["current_host"] . "/abs/";
} else {
    $pageUrl = "http://" . $_SERVER["HTTP_HOST"] . "/abs/";
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>ABS First Time Setup</title>

    <!-- font awesome -->
    <link rel="stylesheet" href="font-awesome/css/all.css">

    <!-- custom css -->
    <link rel="stylesheet" href="custom/css/custom.css">
    <link rel="shortcut icon" href="images/ABS-logo/logo.png" type="image/png">
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assests/images/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">


    <!-- jquery -->
    <script src="<?php echo "$pageUrl"; ?>assests/jquery/jquery.min.js"></script>
    <!-- jquery ui -->
    <link rel="stylesheet" href="<?php echo "$pageUrl"; ?>assests/jquery-ui/jquery-ui.min.css">
    <script src="<?php echo "$pageUrl"; ?>assests/jquery-ui/jquery-ui.min.js"></script>

    <!-- bootstrap js -->
    <script src="<?php echo "$pageUrl"; ?>assests/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap js -->
    <script src="assests/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo "$pageUrl"; ?>assests/farbtastic/farbtastic.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">

    <link rel="stylesheet" href="<?php echo "$pageUrl"; ?>assests/farbtastic/farbtastic.css">

    <style type="text/css">
        h3 {
            font-family: 'Poppins';
            font-size: 20px;
            color: black;
        }

        body {
            margin: 0;
            padding: 0;
        }

        label {
            text-align: left !important;
            font-weight: bold;
        }

        .customize-abs-btn {
            background-color: #edb407 !important;
            border: none !important;
            border-radius: 40px !important;
        }
    </style>
</head>

<body style="background: #3e4095;">
    <div class="container-fluid">
        <div class="row mb-3" style="background-color: #ffffff;">
            <div class="col-md-1 mt-3 mb-3" style="margin-left:5%">
                <img src="images/ABS-logo/cover.png" style="width: 100%;height:50px;">
            </div>
            <div class="col-md-7 mt-4">
                <h3><small>Professional Edition</small></h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center mt-1 g-white p-2" style="opacity: 0.983;">
            <div class="col-md-7 mb-3">

                <div class="card shadow mt-2">
                    <i class="fas fa-user-cog fa-2x my-2"></i>
                    <h1 class="text-dark" style="margin-top: -2%;"> Setup Wizard</h1>

                    <form class="p-4" method="post" style="text-align: left;">

                        <?php

                            if ($install_status) {
                                echo $install_status;
                            }
                       
                        ?>
                        <fieldset>

                            <div class="form-group my-1">

                                <h3>Configuring For First Time Use. </h3>
                                <center>

                                    <span id="loader-holder" style="display: none;">
                                        <img src="images/loading.gif" width="200" alt="">
                                        <br>
                                        <h4>Please Wait...</h4>
                                    </span>

                                </center>
                            </div>
                            <br>
                            <div class="form-group">
                                <center>
                                    <button type="submit" onclick="document.getElementById('loader-holder').style.display = 'block';" name="dbInstallation" class="btn mt-1 w-50 text-white" style="background-color: orange">
                                        Install Database <i class="fas fa-download"></i> </button>

                                    <br>
                                    <br>

                                    <a href="completeSetup" class="btn mt-1 w-50 text-white" style="background-color: #3e4095">
                                        Already Installed? Continue <i class="fas fa-chevron-right"></i> </a>
                                </center>
                            </div>


                            <br>

                    </form>

                </div>
                </a>
            </div>

            <p class="text-center text-white">&copy; 2021. Designed and Developed by Artificial Intelligence Technologies, Abuja.</p>
        </div>
        <!-- /row -->
    </div>


</body>

</html>