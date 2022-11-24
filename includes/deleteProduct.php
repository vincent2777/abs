<?php
include 'db_connect.php';

$do_action = $_POST["do_action"];
$product_number = $_POST["pid"];


if ($do_action == "delete" && $product_number != "") {

    $deleteSQL = mysqli_query($con, "DELETE FROM product WHERE product_id='$product_number' ") or die(mysqli_error($con));

    $status = "";
    if ($deleteSQL) {
        $status .= '<div class="alert alert-success" style="border-left: 5px solid green;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><i class="fas fa-ok-sign"></i> Product have been deleted Successfully</strong>
        </div>';

    }else {
        $status .= '<div class="alert alert-danger" style="border-left: 5px solid green;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><i class="fas fa-ok-sign"></i> An error occured. Please try again later!</strong>
        <br> 
        <a href="products" class="btn customize-abs-btn"> Try again</a>
        </div> 
        ';
    }

    echo json_encode($status);

}
?>
