<?php
include "../partials/header.php";
include "../partials/sidebar.php";

?>

<script src="<?php echo $pageUrl; ?>script/exchange.js"></script>

<div class="main-panel">
    <div class="content-wrapper">
        <ol class="breadcrumb">
            <li><a href="../dashboard">Home/ </a></li>
            <li class="active">Exchange Items</li>
        </ol>

        <div class="row justify-content-center mt-1 bg-white p-3 shadow-sm">
            <div class="col-md-12 mx-auto mb-5">

                <div class="panel panel-default">

                    <div class="panel-body">

                        <center>
                            <?php


                            if (!empty($_GET["action"]) && $_GET["action"] == "clear_rcart") {
                                unset($_SESSION["exproducts"]);
                                echo "<div class='alert alert-success'> <b>All Products cleared..You can now make new Exchange<b/> </div><br>";
                                echo "<script>window.open('exchange_items','_self'); </script>";
                            }

                            if (isset($_POST["exchangeItemsBtn"])) {

                                unset($_SESSION["exproducts"]);
                                $inv_no = $_POST["inv_number"];

                                //check eligiblility of return
                                $sql4 = mysqli_query($connect, "SELECT * FROM exchanged_receipts WHERE invoice_number='$inv_no'");
                                if (mysqli_num_rows($sql4) > 0) {
                                    //returns is prohibited on this invoice

                                    echo "<div class='alert alert-danger'>
									<i class='fas fa-info'></i> Oopsss! Item Exchange has been Disabled on this Invoice.</div>";
                                } else {

                                    # add products in cart 
                                    if (isset($_POST["inv_number"])) {

                                        foreach ($_POST as $key => $value) {
                                            $exproduct[$key] = filter_var($value, FILTER_SANITIZE_STRING);
                                        }

                                        $statement = $con->prepare("SELECT product_id,product_name,sold_at_price,quantity,product_discount FROM sold_products WHERE invoice_number=?");
                                        $statement->bind_param('s', $_POST["inv_number"]);
                                        $statement->execute();
                                        $statement->store_result();
                                        $statement->bind_result($product_id, $product_name, $product_price, $product_qty, $product_discount);
                                        $num_of_rows = $statement->num_rows;

                                        if ($num_of_rows > 0) {

                                            while ($statement->fetch()) {

                                                $exproduct["product_name"] = $product_name;
                                                $exproduct["product_qty"] = $product_qty;
                                                $exproduct['product_id'] = $product_id;
                                                $exproduct['product_discount'] = $product_discount;
                                                $exproduct["product_price"]  = $product_price;
                                                $exproduct["invoice_number"] = $inv_no;

                                                if (isset($_SESSION["exproducts"])) {

                                                    if (isset($_SESSION["exproducts"][$exproduct['product_id']])) {
                                                        $_SESSION["exproducts"][$exproduct['product_id']]["product_qty"] = $product_qty;
                                                    } else {
                                                        $_SESSION["exproducts"][$exproduct['product_id']] = $exproduct;
                                                    }
                                                } else {
                                                    $_SESSION["exproducts"][$exproduct['product_id']] = $exproduct;
                                                }
                                            }

                                            $_SESSION["exchangeInvoice"] = $inv_no;

                                            echo "<div class='alert alert-success' style='font-size:17px;'>
										<i class='fas fa-check-circle'> </i> <b>Invoice Found</b> <a class='btn customize-abs-btn' href='#exchange-items-holder'>Click here </a> to Continue the Return process </div>";
                                        } else {
                                            echo "<div class='btn btn-danger'>Invoice Number not found</div>";
                                        }
                                    }
                                }
                            }

                            ?>
                        </center>

                        <div class="row justify-content-center">

                            <div class="col-md-5">

                                <div class="alert alert-info">
                                    <p>
                                        <b>Kindly note that Item Exchange can only be Initiated once for a Particular Invoice.</b>
                                    </p>
                                </div>
                                <div class="card shadow p-3 rounded-0">

                                    <form style="padding: 30px;" method="post">

                                        <fieldset>
                                            <div class="form-group">
                                                <label for="inv_number" class="control-label">Receipt/Invoice Number</label>
                                                <div class="col-sm-12">
                                                    <input type="text" autocomplete="off" class="form-control" required id="inv_number" name="inv_number" placeholder="Invoice Number" />
                                                </div>
                                            </div>

                                            <br>

                                            <div class="form-group">

                                                <center>
                                                    <button type="submit" style="margin-top: 10px;" name="exchangeItemsBtn" class="btn btn-info btn-lg btn-block">
                                                        <i class="fas fa-log-in"></i> Retrieve </button>
                                                </center>
                                            </div>
                                        </fieldset>
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <?php

        if (isset($_POST["submitNow"])) {

            $new_price = $_POST["new_price"];
            $invoice_no = $_POST["invoice_no"];

            //COPY THE OLD DATA TO the returned receipt TBL
            $sql3 = "INSERT INTO exchanged_receipts (vat_amount,store_id,customer_type,cashpayment_amt,bankpayment_amt,
			cashier,customer_id,customer_name,customer_phone,customer_address,order_date,order_time,invoice_number,
			product_name,product_id,barcode_id,quantity,sold_at_price,expected_sale_price,total_amount,paid_amount,
			balance_amount,product_discount,payment_type,payment_method)
			SELECT vat_amount,store_id,customer_type,cashpayment_amt,bankpayment_amt,
					cashier,customer_id,customer_name,customer_phone,customer_address,order_date,order_time,invoice_number,
					product_name,product_id,barcode_id,quantity,sold_at_price,expected_sale_price,total_amount,paid_amount,
					balance_amount,product_discount,payment_type,payment_method 
			FROM sold_products
			WHERE invoice_number = '$invoice_no'";
            $query3 = mysqli_query($connect, $sql3);

            //SET Exchanged date/time
            $exchangeDate = date("Y-m-d");
            $exchangeTime = date("h:i:s");
            $sql10 = mysqli_query($connect, "UPDATE exchanged_receipts SET exchange_date='$exchangeDate', exchange_time='$exchangeTime' WHERE invoice_number = '$invoice_no'");


            $tempInvNumber = mt_rand(400, 900) . date('ms');
            $totalAmount = 0;

            foreach ($_SESSION["exproducts"] as $exproduct) {

                $product_name = $exproduct["product_name"];
                $product_price = $exproduct["product_price"];
                $product_number = $exproduct["product_id"];
                $product_qty = $exproduct["product_qty"];
                $invoice_no = $exproduct["invoice_number"];
                $product_discount = $exproduct["product_discount"];

                //get expected price
                $ex_sql = mysqli_query($con, "SELECT * FROM product WHERE product_id='$product_number'") or die(mysqli_error($con));
                $exrow = mysqli_fetch_array($ex_sql);
                $expected_sale_price = $exrow["product_price"] * $product_qty;

                //select all data from sold_products
                //that corresponds to the invoice number
                $sql = mysqli_query($con, "SELECT * FROM sold_products WHERE invoice_number='$invoice_no'") or die(mysqli_error($con));
                $row = mysqli_fetch_array($sql);
                $vat_amount = floatval($row["vat_amount"]);
                $store_id = $row["store_id"];
                $customer_type = $row["customer_type"];
                $cashpayment_amt = $row["cashpayment_amt"];
                $bankpayment_amt = $row["bankpayment_amt"];
                $cashier = $row["cashier"];
                $customer_id = $row["customer_id"];
                $customer_name = $row["customer_name"];
                $customer_phone = $row["customer_phone"];
                $customer_address = $row["customer_address"];
                $order_date = $row["order_date"];
                $order_time = $row["order_time"];
                $product_id = $row["product_id"];
                $barcode_id = $row["barcode_id"];
                $sold_at_price = $row["sold_at_price"];
                $balance_amount = $row["balance_amount"];
                $payment_type = $row["payment_type"];
                $payment_method = $row["payment_method"];
                $old_quantity = $row["quantity"];

                $sold_at_price = $expected_sale_price / $product_qty;

                if ($product_discount != 0) {
                    $sold_at_price = $sold_at_price - $product_discount;
                }


                // INSERT THE NEW PRODUCTS INTO TABLE
                $sql1 = mysqli_query($con, "INSERT INTO sold_products 
					(vat_amount,store_id,customer_type,cashpayment_amt,bankpayment_amt,cashier,customer_id,
					customer_name,customer_phone,customer_address,order_date,order_time,invoice_number,
					product_name,product_id,barcode_id,quantity,sold_at_price,expected_sale_price,total_amount,
					paid_amount,balance_amount,product_discount,payment_type,payment_method) 
                VALUES('$vat_amount','$store_id','$customer_type','$new_price','$bankpayment_amt','$cashier',
                '$customer_id','$customer_name','$customer_phone','$customer_address','$order_date','$order_time','$tempInvNumber',
					'$product_name','$product_number','$product_number','$product_qty','$sold_at_price',
                    '$expected_sale_price','$new_price','$new_price','$balance_amount','$product_discount','$payment_type','$payment_method')") or die(mysqli_error($con));

                $newQtyRem = 0;
            
                if ($product_qty > $old_quantity) {
                    $newQtyRem = $product_qty - $old_quantity;
                    $sql6 = mysqli_query($connect, "UPDATE product SET quantity_rem=quantity_rem - '$newQtyRem' WHERE product_id='$product_number'");
                }
                
                if ($product_qty < $old_quantity) {
                    $newQtyRem = $old_quantity - $product_qty;
                    $sql6 = mysqli_query($connect, "UPDATE product SET quantity_rem=quantity_rem + '$newQtyRem' WHERE product_id='$product_number'");
                }
            }

            if ($sql1) {

                $sql5 = mysqli_query($connect, "DELETE FROM sold_products WHERE invoice_number='$invoice_no'");
                $sql6 = mysqli_query($connect, "UPDATE sold_products SET invoice_number='$invoice_no' WHERE invoice_number='$tempInvNumber'");
                // $sql7 = mysqli_query($con, "SELECT * FROM sold_products WHERE invoice_number='$invoice_no' ORDER BY id DESC LIMIT 1") or die(mysqli_error($con));
                // $row7 = mysqli_fetch_array($sql7);
                // $totalPayable = $row7["total_amount"];

                // $sql8 = mysqli_query($connect, "UPDATE sold_products 
                // SET paid_amount='$totalPayable',
                // total_amount='$totalPayable' WHERE invoice_number='$tempInvNumber'");

                unset($_SESSION["exproducts"]);

                echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Item Exchange has been completed and Sales History has been updated. </div>";
            } else {
                echo "<div class='alert alert-danger'><i class='fas fa-info'></i> Oopsss! An error occured..Please try again later.</div>";
            }
        }
        if (isset($_SESSION["exproducts"])) { ?>

            <div class="row justify-content-center mt-5 bg-white p-3 shadow-sm">

                <div class="col-md-5">

                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" autofocus>


                    <div style="overflow-x:auto" class="mt-5">
                        <table class="table table-striped " id="list_all_products" style="font-size: 10px;">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty. Remaining</th>
                                    <th>Unit Price</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_query = "SELECT * FROM product";
                                $resultset = mysqli_query($connect, $sql_query) or die("database error:" . mysqli_error($conn));
                                $count = 0;
                                $rows_count = mysqli_num_rows($resultset);
                                while ($row = mysqli_fetch_assoc($resultset)) {
                                    $count++;
                                ?>
                                    <tr onclick="addExchangeItemsToCart(1,this.id)" class="add-to-sales" style="text-align: left;font-family: 'Verdana';font-size:12px;cursor:pointer" id="<?php echo $row["product_id"]; ?>">
                                        <td>
                                            <h6><?php echo $row["product_name"]; ?></h6>
                                        </td>

                                        <td>
                                            <h6><?php echo $row["quantity_rem"]; ?></h6>
                                        </td>

                                        <td><?php echo $currency;
                                            echo number_format($row["product_price"], 2); ?>
                                        </td>


                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-7 mx-auto mb-5">

                    <div class="panel panel-default">

                        <div class="panel-body" id="exchange-items-holder">

                            <form method="POST" id="exchangeSalesForm" novalidate>

                                <table class="table table-striped table-sm" id="exchangecart-table" style="font-size:11px !important">
                                    <thead id="table-head">
                                        <tr>
                                            <th>Item</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="exchange-cart-items">

                                    </tbody>
                                </table>

                                <input type="hidden" name="price_paid" id="price_paid">
                                <input type="hidden" name="new_price" id="new_price">

                                <div style="margin-top: 70px;margin-left: 15px" id="calculations-holder">

                                    <div id="customer_info" style="text-align: left;">

                                        <div class="row">

                                            <div class="d-flex justify-content-start">

                                                <div class="form-group mr-2">
                                                    <input type="text" id="customer_name" disabled name="customer_name" class="form-control" placeholder="Customer Name">
                                                </div>

                                                <div class="form-group mr-2">
                                                    <input type="text" id="customer_phone" disabled name="customer_phone" class="form-control" placeholder="Customer Phone" />
                                                </div>

                                                <div class="form-group mr-2">
                                                    <input type="text" id="customer_address" disabled name="customer_address" class="form-control" placeholder="Customer Address">
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row" style="text-align: left;font-size:14px">
                                        <div class="col-md-9">
                                            <strong class="mr-auto">Paid: </strong>
                                        </div>
                                        <div class="col">
                                            <span id="showSubTotal"><?php echo $currency; ?>
                                                <span id="currency_holder_subtotal">0.00</span>
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row" style="text-align: left;font-size:14px">
                                        <div class="col-md-9">
                                            <strong>Discount: </strong>
                                        </div>
                                        <div class="col">
                                            <?php echo $currency; ?>
                                            <span id="discountTotal">0.00</span>
                                        </div>
                                    </div>

                                    <div class="row" style="text-align: left;font-size:14px">
                                        <div class="col-md-9">
                                            <strong>New Total: </strong>
                                        </div>
                                        <div class="col">

                                            <span id="currency_holder_total"><?php echo $currency; ?></span>
                                            <span id="calcTotalPayable">
                                                0.00
                                            </span>

                                        </div>
                                    </div>

                                    <div class="row" style="text-align: left;font-size:14px">
                                        <div class="col-md-9">

                                            <strong id="change_or_balance">Change:</strong>
                                        </div>
                                        <div class="col">
                                            <?php echo $currency; ?>

                                            <strong><span id="calcTotalChange">0.00</span>
                                            </strong>
                                        </div>
                                    </div>


                                    <div class="row mt-5 ml-1">
                                        <div class="d-flex justify-content-end">

                                            <div class="form-group">
                                                <a href="exchange_items?action=clear_rcart" name="clearCart" id="clearCart" class="btn btn-warning mr-5 btn-block">
                                                    <i class="fas fa-trash"></i> Clear
                                                </a>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" name="submitNow" id="submitNow" style="width: 200px;" class="btn btn-success">Submit
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    loadExchangeAfterReload();
                })
            </script>

        <?php } ?>


    </div>
</div>
</div>
</div>
</div>




<?php include "../partials/footer.php"; ?>