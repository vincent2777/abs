<?php
session_start();
include "db_connect.php";

if (isset($_POST["modal_pnumber"])) {
    //update data
    $product_id = $_POST["modal_pnumber"];
    $barcode_id = $_POST["modal_barcode"];
    $product_price  = $_POST['modal_sprice'];
    $product_cprice  = $_POST['modal_cprice'];
    $product_discount = $_POST["modal_discount"];
    $product_dsales     = $_POST['disable_sale'];
    $product_name = $_POST["modal_pname"];
    $product_old_qty = $_POST["old_qty"];
    $product_new_qty = $_POST["modal_qty"];
    $product_rlevel = $_POST["modal_rlevel"];
    $product_maxto_sell = $_POST["modal_qtyto_sell"];
    $pexpiry_date = $_POST["pexpiry_date"];
    $today = date("Y-m-d");
    $store_id = $_SESSION["store_id"];
    $received_status = 0;
    $product_labove = $_POST["price_level_qty_above"];
    $product_lbelow = $_POST["price_level_qty_below"];
    $product_lamount = $_POST["price_level_amount"];

    //if selling price is set
    //set recieved status to 1

    if ($product_price > 0) {
        $received_status = 1;
    }

    //total qty after change 
    $total_qty = $product_new_qty - $product_old_qty;


    //get measurement unit data and check for updates
    $punit_qty = $_POST["punit_qty"];
    $punit_price = $_POST["punit_price"];
    $punit_id = $_POST["punit_id"];

    //check if already exist
    //true, then, update
    //false insert

    // $sql5 = mysqli_query($con, "SELECT * FROM product_measurement WHERE measurement_id='$punit_id' AND product_id='$product_id'");

    // if (mysqli_num_rows($sql5) > 0) {

    //     $sql4 = "UPDATE product_measurement
    //     SET measurement_qty='$punit_qty', measurement_price='$punit_price' 
    //     WHERE product_id='$product_id'";
    //     $result = $connect->query($sql4) or die(mysqli_error($con));

    // } else {

    //     $sql6 = $connect->query("SELECT * FROM measurement_units WHERE unit_id='$punit_id'") or die(mysqli_error($con));
    //     $row = $sql6->fetch_array($sql6);
    //     $unit_name = ucwords($row["unit_name"]);

    //     $sql7 = $connect->query("INSERT INTO product_measurement (product_id,measurement_qty,measurement_price, measurement_unit, measurement_id) 
    //     VALUES('$product_id','$punit_qty','$punit_price','$unit_name','$punit_id')") or die(mysqli_error($con));

    // }


    if ($product_new_qty != $product_old_qty) {

        //quantity change has occured
        $insert_sql = mysqli_query($con, "INSERT INTO 
        product_qty_change (store_id,product_number,former_qty,new_qty,total_qty,change_date)
        VALUES('$store_id','$product_id','$product_old_qty','$product_new_qty','$total_qty','$today')");
    }

    $updateSQL = mysqli_query($con, "UPDATE product SET 
        price_level_qty_above='$product_labove', 
        price_level_qty_below='$product_lbelow', 
        price_level_amount='$product_lamount', 
        reorder_level='$product_rlevel', 
        quantity='$product_new_qty', 
        barcode_id='$barcode_id',
        product_name='$product_name',
        pvld_restrict_sales='$product_dsales',
        product_discount='$product_discount',
        max_to_sell = '$product_maxto_sell',
        product_price='$product_price',
        pexpiry_date='$pexpiry_date',
        cost_price='$product_cprice',
        received_status='$received_status'
        WHERE product_id='$product_id'") or die(mysqli_error($con));

    $status = "";
    if ($updateSQL) {

        $status .= '<div class="alert alert-success" style="border-left: 5px solid green;">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong><i class="fas fa-ok-sign"></i> Data has been updated Successfully!</strong>
<br> 
<button type="button" class="btn customize-abs-btn" data-dismiss="modal" onclick="refreshAfterEdit()"> Continue</button>
</div> 
';
    } else {
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
