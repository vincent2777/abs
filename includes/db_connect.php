<?php 	

$dbhost = "localhost";
$dbuser = "root";
$dbpass = '';
$dbname = "absaccounting";
// db connection
$connect = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// check connection
if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  // echo "Successfully connected";
}



$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die(mysqli_error($connect));
error_reporting(0);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
date_default_timezone_set("Africa/Lagos");


//get settings
$settingSql = "SELECT * FROM settings";
$settingQuery = mysqli_query($con,$settingSql) or die(mysqli_error($con));
$settingRow = mysqli_fetch_array($settingQuery);
$vat = htmlspecialchars_decode($settingRow["company_vat"]);
$bg_color = $settingRow["bodybg_color"];
$headerbg_color = $settingRow["header_bg_color"];
$btn_color1 = $settingRow["btn_color1"];
$btn_color2 = $settingRow["btn_color2"];
$btn_txtcolor = $settingRow["btn_txtcolor"];
$loginbg_color = $settingRow["loginbg_color"];
$work_resumes = $settingRow["work_resumes_time"];
$work_closes = $settingRow["work_closes_time"];
$lateness_fee = $settingRow["lateness_fee"];
$currency = $settingRow["currency"];

// SET PASSWORD FOR 'root'@'localhost' = PASSWORD('your_root_password');
// $cfg['Servers'][$i]['auth_type'] = 'cookie';

?>



