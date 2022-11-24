
<?php

session_start();
$curPageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
$pageUrl = "";


if (!empty($_SESSION["current_host"])) {
  $pageUrl = "http://" . $_SESSION["current_host"] . "/abs/";
} else {
  $pageUrl = "http://" . $_SERVER["HTTP_HOST"] . "/abs/";
}

//check priviledges and allow or disallow page access
$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
$fetchData = mysqli_fetch_array($getData);
$currency = htmlspecialchars_decode($fetchData["currency"]);


if(!isset($_SESSION["user"])){
	header("location: $pageUrl".'login');
}

?>
