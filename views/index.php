<?php
//DB connection
$first_con = mysqli_connect("localhost", "root", "") or die(mysqli_error($connect));
$db_selected = mysqli_select_db($first_con, 'absaccounting');

if (!$db_selected) {
	//if no DB
	header("location: isFirstTime");
}else{
    header("location: completeSetup");
}
?>