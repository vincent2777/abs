<?php
include_once("includes/db_connect.php");
include("includes/config.inc.php");
include "includes/check_role_header.php";
error_reporting(0);
?>

<style type="text/css">
    @media print {

        .hidden-print,
        .hidden-print * {
            display: none !important;
        }
    }

    @media print {
        @page {
            padding: 0px 0px 0px 0px;
            margin: 0px 0px 0px 0px;
        }

    }

    @media print {

        .panel-heading,
        .panel-heading * {
            display: none !important;
        }

    }

    .cust_results {
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.1);
        width: inherit;
        background-color: white;
        height: auto;
        padding: 15px;
        color: #0a011a;
        display: none;
    }

    .cust_results a {
        font-size: 13px;
        text-decoration: none;
    }
</style>


<div class="container">
    <div class="col-md-12">
        <div class="panel panel-default" style="border:0px !important">
            <div class="panel-heading"> <i class="fas fa-calendar"></i> Complete Transfer</div>

            <div class="panel-body">

                <?php
                if (isset($status)) {
                    echo "<div class='alert alert-success'> Sales have been processed successfully </div><br>";
                }

                if (!empty($_GET["action"]) && $_GET["action"] == "clear_cart") {
                    unset($_SESSION["ttb_products"]);
                    unset($_SESSION["cart_discounts"]);
                    unset($_SESSION["returns"]);
                    echo "<div class='alert alert-success'> <b>All Products cleared..You can now make new sales<b/> </div><br>";
                }


                $paymethod = array();

                if (isset($_POST["submitNow"])) {

                    //if  return, get return amount
                    $category = $_POST["category"];
                    $user_id = $_SESSION['user'];
                    $today = date('y/m/d');
                    $order_number = "";

                    $getIdSql = mysqli_query($con, "SELECT * FROM stock_transfer ORDER BY invoice_number DESC LIMIT 1") or die(mysqli_error($con));
                    $getIdData = mysqli_fetch_array($getIdSql);
                    $getLastID = $getIdData["invoice_number"];


                    if (empty($getLastID)) {
                        $order_number = 1;
                    } else {
                        $order_number = $getLastID + 1;
                    }

                    //get name of branch or store
                    $sql4 = mysqli_query($con, "SELECT * FROM stores WHERE id='$category'") or die(mysqli_error($con));
                    $query4 = mysqli_fetch_array($sql4);

                    $getStoreName = ucwords($query4["store_name"]);

                    //get invoice design template
                    $invoice_sql = mysqli_query($con, "SELECT * FROM invoice_template") or die(mysqli_error($con));
                    $invoiceRow = mysqli_fetch_array($invoice_sql);

                    $bname = $invoiceRow["business_name"];
                    $bimage = $invoiceRow["business_logo_path"];
                    $bslogan = $invoiceRow["business_slogan"];
                    $binfo = $invoiceRow["additional_info"];
                    $baddress = $invoiceRow["business_address"];
                    $bwebsite = $invoiceRow["business_website"];
                    $bphone = $invoiceRow["business_phone"];


                ?>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            window.print();
                        })

                        $(document).ready(function() {
                            document.getElementById("printBtn").addEventListener('click', function() {
                                if (window.print()) {
                                    window.open("checkout", "_self");
                                }
                            })
                        })
                    </script>

                    <div id="print-holder1" style="margin-left:10px;">

                        <center>
                            <img src="<?php echo $bimage; ?>" style="width: 70px;height: 70px;margin-left:5px">
                            <br>
                            <p>
                                <span style="font-size: 15px;font-family:Arial, Helvetica, sans-serif"><strong><b><?php echo ucwords($bname); ?></b></strong></span>
                                <br>
                                <span style="font-size: 11px;font-family:Arial, Helvetica, sans-serif"><strong><b><?php echo $bslogan; ?></b></strong></span>
                            </p>

                        </center>
                    </div>
                    <div id="print-holder2" style="margin-left:-45px;">

                        <p style="font-size: 12px">
                            <b>Issued By: </b> <?php echo ucwords($user_id); ?>
                            <br>
                            <b>Invoice No:</b> <?php echo $order_number; ?>
                            <br>
                            <b>Date:</b> <?php echo $d =  date('d-m-Y h:i:s'); ?>
                            <?php

                            if (!empty($getStoreName)) {
                                echo "<br><b>Branch/Store:</b> <span>$getStoreName</span><br>";
                            }

                            ?>
                        </p>
                    </div>
                    <table class="table" style="margin-left:-45px;border:0px;font-size: 12px;font-family:'Times New Roman', Times, serif">
                        <thead>
                            <tr>
                                <th class="desc" style="width: auto !important;text-align:initial">Desc.</th>
                                <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>Qty.</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php


                            foreach ($_SESSION["ttb_products"] as $product) {
                                $product_name = $product["product_name"];
                                $product_number = $product["product_id"];
                                $product_qty = $product["product_qty"];

                                ///DO NOT INSERT DATA
                                $sql = mysqli_query($con, "INSERT INTO stock_transfer
		(store_id,cashier,product_id,product_name,quantity,transfer_date,invoice_number,branch_name,branch_id) 
		VALUES('$store_id','$user_id','$product_number','$product_name',$product_qty,'$today','$order_number','$getStoreName','$category')") or die(mysqli_error($con));

                                //update the quantity sold and qty rem
                                $update_productSql = mysqli_query($con, "UPDATE product SET quantity_rem=quantity_rem - '$product_qty' WHERE product_id='$product_number'") or die(mysqli_error($con));

                            ?>
                                <tr style="width: 100% !important;">
                                    <td colspan="3" style="width: auto !important;text-align:left"><?php echo $product_name; ?></td>
                                    <td class="qty-data"><?php echo $product_qty; ?></td>

                                </tr>
                            <?php

                                $index++;
                            }
                            $status = 1;
                            ?>

                        </tbody>
                    </table>

                    <hr style="margin-top:-5px;color:black;background-color:black">
                    <br>
                    <br>
                    <center>
                        <p style="font-size: 11px;text-align: center;margin-top:-17px;">
                            <?php echo $binfo; ?>
                            <br>
                            Customer Care: <?php echo $bphone; ?>
                            <br>
                            <?php echo $baddress; ?>
                            <br>
                            <?php echo $bwebsite; ?>

                        </p>

                        </p>
                        <p id="copy-type"></p>
                    </center>

                    <?php
                    if ($sql) {
                        echo "<button class='btn customize-abs-btn hidden-print' id='printBtn'>
<i class='fas fa-print'></i> Print</button><br>";

                        unset($_SESSION["ttb_products"]);
                        unset($_SESSION["cart_discounts"]);
                        unset($_SESSION["returns"]);
                    }
                    ?>
            </div>

        <?php


                } else {

        ?>
            <?php
                    if (isset($_SESSION["ttb_products"]) && count($_SESSION["ttb_products"]) > 0) {
                        $total = 0;
                        $list_tax = '';
                        $total_discount = 0;
            ?>
                <form method="post">

                    <table class="table table-striped table-responsive" id="shopping-cart-results" style="font-family: 'Oxygen';font-size:14px">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Branch/Store name</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $cart_box = '';
                            $price_level_discount = 0;
                            $sub_total = 0;

                            foreach ($_SESSION["ttb_products"] as $product) {
                                $product_name = $product["product_name"];
                                $product_number = $product["product_id"];
                                $product_qty = $product["product_qty"];

                            ?>
                                <tr>
                                    <td><?php echo $product_name; ?></td>
                                    <td><?php echo $product_qty; ?></td>
                                    <td>&nbsp;</td>
                                </tr>

                            <?php } ?>

                            <tr>
                                <td>
                                </td>
                                <td></td>
                                <td>
                                    <select class="form-control" name="category" id="category" required="">
                                        <option value="">-Select Branch/Store-</option>
                                        <?php

                                        $sql1 = mysqli_query($con, "SELECT * FROM stores") or die(mysqli_error($con));
                                        while ($row = mysqli_fetch_array($sql1)) {
                                        ?>
                                            <option value="<?php echo $row["id"]; ?>"><?php echo strtoupper($row["store_name"]); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td><br><br><a href="make_sales" class="btn customize-abs-btn">
                                        <i class="fas fa-menu-left"></i> Add More Products</a>
                                    <br>

                                </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>

                                <td>
                                    <br>
                                    <br>
                                    <br>
                                    <a href="checkout?action=clear_cart" name="clearCart" id="clearCart" class="btn btn-warning btn-block">Clear All
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <br>
                                    <button type="submit" name="submitNow" id="submitNow" class="btn customize-abs-btn btn-block">Submit
                                        <i class="fas fa-check"></i>
                                    </button>

                                </td>
                            </tr>

                        </tfoot>

                    <?php
                    } else {
                        echo "Your Cart is empty";
                    }
                    ?>

                    </table>
                </form>

            <?php  } ?>
        </div>
    </div>
</div>


</div>


<script src="script/ttb_checkout.js"></script>

</div> <!-- container -->

<script src="<?php echo $pageUrl; ?>assests/plugins/fileinput/js/fileinput.min.js"></script>

<script type="text/javascript" src="<?php echo $pageUrl; ?>DataTables/datatables.min.js"></script>
<script type="text/javascript" src="<?php echo $pageUrl; ?>DataTables/Buttons-1.6.2/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?php echo $pageUrl; ?>DataTables/Buttons-1.6.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo $pageUrl; ?>DataTables/Buttons-1.6.2/js/dataTables.buttons.min.js"></script>

<script src="<?php echo $pageUrl; ?>assests/jquery-ui/jquery-ui.min.js"></script>


</body>

</html>