<?php
require_once 'includes/db_connect.php';

session_start();
$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
$fetchData = mysqli_fetch_array($getData);

$pageUrl = "";

if (!empty($_SESSION["current_host"])) {
	$pageUrl = "http://" . $_SESSION["current_host"] . "/abs/";
} else {
	$pageUrl = "http://" . $_SERVER["HTTP_HOST"] . "/abs/";
}

$errors = array();

if ($_POST) {

	$username = $_POST['username'];
	$password = $_POST['password'];

	if (empty($username) || empty($password)) {
		if ($username == "") {
			$errors[] = "Username is required";
		}

		if ($password == "") {
			$errors[] = "Password is required";
		}
	} else {
		$sql = "SELECT * FROM users WHERE username = '$username'";
		$result = $connect->query($sql);

		if ($result->num_rows > 0) {
			$password = md5($password);
			// exists
			$mainSql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
			$mainResult = $connect->query($mainSql);

			if ($mainResult->num_rows == 1) {
				$value = $mainResult->fetch_assoc();
				$user_id = $value['username'];

				// set session
				$_SESSION['userId'] = $user_id;

				header('location: ' . $pageUrl . 'dashboard');
			} else {

				$errors[] = "Incorrect username/password combination";
			} // /else
		} else {
			$errors[] = "Username does not exists";
		} // /else
	} // /else not empty username // password

} // /if $_POST
?>

<!DOCTYPE html>
<html>

<head>
	<title> <?php echo ucwords($fetchData["company_name"]); ?> - ABSPRO</title>
	<!-- font awesome -->
	<link rel="stylesheet" href="font-awesome/css/all.css">

	<!-- custom css -->
	<link rel="stylesheet" href="custom/css/custom.css">

	<link rel="icon" type="image/png" sizes="16x16" href="images/ABS-logo/logo.png">
	<link rel="manifest" href="manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="assests/images/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<link rel="stylesheet" href="style/animate.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese" rel="stylesheet">

	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
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
	</style>

<script src="assests/bootstrap/js/bootstrap.min.js"></script>


</head>

<body style="background: #3e4095;" onload="openFullscreen()">

	<div class="container-fluid">
		<div class="row mb-3 bg-white">
			<div class="col-md-1 mt-3 mb-3" style="margin-left:5%">
				<img src="images/ABS-logo/cover.png" style="width: 100%;height:50px;">
			</div>
			<div class="col-md-7 mt-4">
				<h3 class="text-dark"><small>Professional Edition</small></h3>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row mt-5 g-white p-4 mb-5" style="opacity: 0.983;">

			<h1 class="mb-5 text-white">Select your Role</h1>
			<div class="col-md-3 mb-5 animate__animated animate__fadeInLeft ">
				<a href="owner/" style="text-decoration: none; color:inherit">
					<div class="card shadow align-items-center" style="background-color: white;height:200px">
						<i class="fas fa-person-booth fa-5x mb-4 my-4"></i>
						<h5>Owner</h5>
					</div>
				</a>
			</div>

			<div class="col-md-3 mr-3 mb-5 animate__animated animate__fadeInDown">
				<a href="manager/" style="text-decoration: none; color:inherit">
					<div class="card bg-white shadow align-items-center" style="background-color: white;height:200px">

						<i class="fas fa-user fa-5x mb-4 my-4"></i>
						<h5>Manager/Accountant</h5>
					</div>
				</a>
			</div>

			<div class="col-md-3 mb-5 animate__animated animate__fadeInUp">
				<a href="associates/" style="text-decoration: none; color:inherit">
					<div class="card bg-white shadow  align-items-center" style="background-color: white;height:200px">
						<i class="fas fa-shopping-cart fa-5x mb-4 my-4"></i>
						<h5>Associates</h5>

					</div>

				</a>

			</div>

			<div class="col-md-3 mb-5 animate__animated animate__fadeInLeft">

				<a href="extrausers/" style="text-decoration: none; color:inherit">
					<div class="card bg-white shadow  align-items-center" style="background-color: white;height:200px">
						<i class="fas fa-user-friends fa-5x mb-4 my-4"></i>
						<h5>Others</h5>

					</div>

				</a>

			</div>
			<!-- /col-md-4 -->

			<p class="text-center text-white">&copy; 2021. Designed and Developed by Artificial Intelligence Technologies, Abuja.</p>
		</div>
		<!-- /row -->
	</div>
	<!-- container -->
</body>

</html>