<?php


require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
session_start();
$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
$fetchData = mysqli_fetch_array($getData);

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
	<title><?php echo ucwords($fetchData["company_name"]); ?> - Setup</title>

	<!-- font awesome -->
	<link rel="stylesheet" href="font-awesome/css/all.css">

	<!-- custom css -->
	<link rel="stylesheet" href="custom/css/custom.css">
	<link rel="shortcut icon" href="images/ABS-logo/logo.png" type="image/png">
	<link rel="manifest" href="manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="assests/images/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<!--color picker -->


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

					<form action="" class="p-4" method="post" style="text-align: left;">

						<?php
						if (isset($_POST['checkFirstSetup'])) {

							$connection_ip = $_POST['connection_ip'];

							//open and create DB config file
							// $dbfile = fopen("angelinfo.txt", "w");
							// $data = $_POST["db_host"]."\n".$_POST["db_user"]."\n".$_POST["db_name"]."\n".$_POST["db_password"]."\n";
							// fwrite($dbfile, $data);
							// fclose($dbfile);


							if (strpos($connection_ip, "htt") !== false) {
								echo "<div class='alert alert-danger'>Please remove the http protocol from the remote IP.</div>";
							} else {
								$_SESSION["current_host"] = $connection_ip;
								header("location: " . $pageUrl . "role_select_menu");
							}

							//collect customization data
							$btn_bgcolor1 = $_POST["btn_bgcolor1"];
							$btn_bgcolor2 = $_POST["btn_bgcolor2"];
							$btn_textcolor = $_POST["btn_textcolor"];
							$bgcolor = $_POST["bgcolor"];
							$navbar_color = $_POST["navbar_color"];
							$login_bgcolor = $_POST["login_bgcolor"];

							$update = "UPDATE settings SET 
							btn_color1 = '$btn_bgcolor1', 
							btn_color2 = '$btn_bgcolor2', 
							btn_txtcolor = '$btn_textcolor', 
							bodybg_color = '$bgcolor', 
							header_bg_color = '$navbar_color', 
							loginbg_color = '$login_bgcolor'";
							$query = mysqli_query($con,$update) or die(mysqli_error($con));


						}

						?>

						<fieldset>
							<!-- <div class="form-group" style="margin-top: -15%;">
								<label for="one_time_license" class="col-sm-12 control-label">Software License</label>
								<input type="text" class="form-control" id="one_time_license" name="one_time_license" placeholder="License Key" autocomplete="off" />
							</div> -->
							<div class="form-group my-1">

								<label for="connection_ip" class="col-sm-12 control-label">Remote or Host IP (e.g 192.168.0.1) </label>
								<input type="text" class="form-control" id="connection_ip" name="connection_ip" placeholder="0.0.0.0" />
							</div>
							<p style="color: red;">IF THIS IS YOUR SERVER-PC, LEAVE THIS FIELD EMPTY AND CLICK CONTINUE.</p>
							<div class="alert alert-info" style="border-left: 5px solid blue;">
								<p>1. Enter your <b>HOST IP</b> if you are connecting to a SERVER-PC as a CLIENT on your Network.</p>
							</div>

							<!-- <div class="form-group">
								<button type="submit" name="skipFirstSetup" class="btn btn-default border-bottom btn-block mt-3 w-100"> 
								<i class="fas fa-check"></i> Skip</button>
							</div> -->

							<hr>

							<!-- <h4>Database Configuration 
									<span class="ml-5">
									<button type="button" class="border-0  text-white p-2 rounded text-underline bg-success instructions" style="font-size: 15px;margin-left:10%">Read Instructions</button></span></h4>
							
									<div class="alert alert-success" style="border-left: 5px solid green;" id="view_instructions" style="display: none;">
								<p>
								TO GET STARTED
								<br><br>
								
								1. Open up <b>http://localhost/phpmyadmin</b> on your web browser or from your Web Host Control Panel.<br>
								2. Click on <b>New</b> and give a name to your Database.<br>
								3. Once Database has been created, select the database and click on <b>PRIVILEDGE</b> at the top-right SIDE of PhpMyAdmin.
								<br>
								4. Click on <b>Add New Account</b> to configure your Database connection and security
								<br>
								5. Look out for Global privileges, and click the <b>Check all</b> button close to it. 
								<br>
								6. Once all priviledges are checked, scroll down and click on <b>GO</b>
								<br>
								7. Return back to the ABS Configuration screen and fill up the necessary Informations.
								</p>
							</div>

									<hr>

									<div class="form-group my-3">

										<label for="connection_ip" class="col-sm-12 control-label">Database Host</label>
										<input type="text" class="form-control" id="db_host" name="db_host" placeholder="DB Host" autocomplete="off" />
									</div>

									<div class="form-group my-3">

										<label for="connection_ip" class="col-sm-12 control-label">Database User</label>
										<input type="text" class="form-control" id="db_user" name="db_user" placeholder="DB User" autocomplete="off" />
									</div>

									<div class="form-group my-3">

										<label for="connection_ip" class="col-sm-12 control-label">Database Name</label> 
										<input type="text" class="form-control" id="db_name" name="db_name" placeholder="DB Name" autocomplete="off" />
									</div>

									<div class="form-group my-3">

										<label for="connection_ip" class="col-sm-12 control-label">Database Password</label>
										<input type="password" class="form-control" id="db_password" name="db_password" placeholder="DB Password" autocomplete="off" />
									</div>
									<div class="flex-row">
									<div class="mr-2 d-flex justify-content-end">
									<div class="form-group">
										<button type="submit" name="checkFirstSetup" class="btn customize-abs-btn btn-block mt-3 w-100">
											Finish <i class="fas fa-chevron-right"></i> </button>
									</div>

								</div>
							</div> -->
							<br>
							<p>
								<h3>App Customization</h3>
								<br>

								You can now customize the appearance of the ABS Software.
								Simply select the element you wish to customize add a color effect to it.
								<br>
								</p>

								<div class="alert alert-success">
									Click on each box to open the Color Picker and Select your preferred color.
								</div><br>
								<div class="row">
									<div class="col">
										<label for="">Button Background Colour 1</label>
										<input type="text" id="btn_bgcolor1" name="btn_bgcolor1" value="<?php echo $btn_color1; ?>" placeholder="click here to select" class="form-control">
									</div>

									<div class="col">
										<label for="">Button Background Colour 2</label>
										<input type="text" id="btn_bgcolor2" name="btn_bgcolor2" value="<?php echo $btn_color2; ?>" placeholder="click here to select" class="form-control">
									</div>

									<div class="col">
										<label for="">Button Text Colour</label>
										<input type="text" id="btn_textcolor" name="btn_textcolor" value="<?php echo $btn_txtcolor; ?>" placeholder="click here to select" class="form-control">
									</div>

								
								</div>

								<br>

								<div class="row">
									<div class="col">
										<label for="">App Background Color</label>
										<input type="text" id="bgcolor" name="bgcolor" value="<?php echo $bg_color; ?>" placeholder="click here to select" class="form-control">
									</div>

									<div class="col">
										<label for="">Navigation Bar Colour</label>
										<input type="text" id="navbar_color" name="navbar_color" value="<?php echo $headerbg_color; ?>" placeholder="click here to select" class="form-control">
									</div>

									<div class="col">
										<label for="">Login Page Background Colour</label>
										<input type="text" id="login_bgcolor" name="login_bgcolor" value="<?php echo $loginbg_color; ?>" placeholder="click here to select" class="form-control">
									</div>
								</div>

					<div class="mx-auto justify-content-center">
					<center>
						<div id="colorpicker"></div>

						</center>
					</div>
					<div class="form-group">
								<center>
								<button type="submit" name="checkFirstSetup" class="btn mt-1 w-75 text-white" style="background-color: <?php echo randomizeBtnColor($btn_color1,$btn_color2); ?>">
									Save and Proceed<i class="fas fa-chevron-right"></i> </button>
								</center>
							</div>

					</form>

				</div>
				</a>
			</div>

			<p class="text-center text-white">&copy; 2021. Designed and Developed by Artificial Intelligence Technologies, Abuja.</p>
		</div>
		<!-- /row -->
	</div>

	<script>
		$(document).ready(function() {

			$('#btn_bgcolor1').on('focus',function(){
				jQuery.farbtastic('#colorpicker').linkTo('#btn_bgcolor1');
			})

			$('#btn_bgcolor2').on('focus',function(){
				jQuery.farbtastic('#colorpicker').linkTo('#btn_bgcolor2');
			})

			$('#btn_textcolor').on('focus',function(){
				jQuery.farbtastic('#colorpicker').linkTo('#btn_textcolor');
			})

			
			$('#bgcolor').on('focus',function(){
				jQuery.farbtastic('#colorpicker').linkTo('#bgcolor');
			})

			
			$('#navbar_color').on('focus',function(){
				jQuery.farbtastic('#colorpicker').linkTo('#navbar_color');
			})

			
			$('#login_bgcolor').on('focus',function(){
				jQuery.farbtastic('#colorpicker').linkTo('#login_bgcolor');
			})

			
			$('.instructions').on('click', function() {
				$('#view_instructions').toggle();
			});
		})
	</script>

</body>

</html>