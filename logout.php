<?php 
session_start();

include  'includes/db_connect.php';

$owner_id = $_SESSION['user'];
$role = $_SESSION['role'];
$store_id = $_SESSION['store_id'];
//SET LOGOUT TIME
$now = date("Y-m-d h:i:s");
$new_query = $con->query("INSERT INTO login_log (store_id,username,user_role,logout_time) 
VALUES('$store_id','$owner_id','$role','$now')");


// remove all session variables
session_unset(); 
// destroy the session 
session_destroy(); 

header('location: login');

?>

?>