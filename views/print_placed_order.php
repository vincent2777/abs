<?php
include "../partials/header.php";
error_reporting(0); ?>

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

        table,
        tr,
        th,
        td {
            width: auto !important;
        }

    }

    @media print {

        .panel-heading,
        .panel-heading * {
            display: none !important;
        }

    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row bg-white">
            <div class="col-md-12">
                <div class="panel panel-default" style="border:0px !important">

                    <div class="panel-body">
                        <?php
                        if (isset($_GET["orderno"])) {

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

                            $query3 = mysqli_query($con, "SELECT * FROM placed_orders WHERE order_number='" . $_GET['orderno'] . "'") or die(mysqli_error($con));
                            $query3Data = mysqli_fetch_array($query3);

                        ?>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    window.print();
                                })

                                $(document).ready(function() {
                                    document.getElementById("printBtn").addEventListener('click', function() {
                                        $("#copy-type").html("<b>CUSTOMER COPY</b>");
                                        window.print();
                                    })
                                })
                            </script>

                            <span style="float: right;margin-right:50px">Reprint</span>
                            <br>
                            <center>
                                <img class="mb-2" src="<?php echo $bimage; ?>" style="width: 70px;height: 70px">
                                <br>
                                <span class="mt-2 pt-2" style="font-size: 18px;font-family: Algerian; line-height: 15px !important"><strong><b><?php echo ucwords($bname); ?></b></strong></span>
                                <br>
                                <span style="font-size: 14px;font-family: Verdana"><strong><b><?php echo $bslogan; ?></b></strong></span>

                            </center>

                            <br>
                        
                            <div id="print-holder2">

                                <p style="font-size: 12px">
                                    <b>Issued By: </b> <?php echo ucwords($query3Data['cashier']); ?>
                                    <br>
                                    <b>Order No:</b> <?php echo $query3Data['invoice_number']; ?>
                                    <br>
                                    Date: <?php echo $query3Data['order_date'];  ?>
                                    <?php

                                    if (!empty($query3Data['customer_name']) || !empty($query3Data['customer_name'])) {
                                        echo "<br>Customer: <span>" . $query3Data['customer_name'] . "</span><br>";
                                        echo "Mobile: <span>" . $query3Data['customer_phone'] . "</span><br>";
                                    }

                                    ?>
                                </p>

                            </div>
                            <table class="table" style="border:0px;font-size: 12px;font-family:'Times New Roman', Times, serif">
                                <thead>
                                    <tr>
                                        <th class="desc" style="width: auto !important;text-align:initial">Desc.</th>
                                        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th>Unit</th>
                                        <th>Qty.</th>
                                        <th>Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                   
                                    $orderno = $_GET['orderno'];
                                    $totalAmount = 0;
                                    $totalQty = 0;

                                    $queryOrder = mysqli_query($con, "SELECT * FROM placed_orders WHERE order_number='$orderno'") or die(mysqli_error($con));

                                    while ($orderData = mysqli_fetch_array($queryOrder)) {

                                        $totalAmount   += $orderData["totalAmount"];
                                        $totalQty +=   $orderData["quantity"];
                                        $cost =   $orderData["totalAmount"];

                                    ?>

                                        <tr>
                                            <td colspan="3" style="width: auto !important;text-align:left"><?php echo $orderData["product_name"]; ?></td>
                                            <td style="width: auto !important;text-align:initial"><?php echo ucwords($orderData["measurement_unit"]); ?></td>
                                            <td style="width: auto !important;text-align:initial"><?php echo $orderData["quantity"]; ?></td>
                                            <td style="width: auto !important;text-align:initial"><?php echo number_format($cost,2); ?></td>

                                        </tr>

                                    <?php } ?>

                                </tbody>
                            </table>

                            <br>
                            <br>
                            <table>
                                <tr>
                                    <td>Total Amount: <?php echo $currency . number_format($totalAmount, 2);  ?></td>
                                </tr>
                                <tr>
                                    <td>Total Quantity: <?php echo $totalQty;  ?></td>
                                </tr>


                            </table>
                            <hr style="margin-top:15px;color:black;background-color:black">
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
                                <p id="copy-type"></p>
                            </center>

                            <?php
                            if ($sql) {
                                echo "<button class='btn btn-warning hidden-print' id='printBtn'>
							<i class='fas fa-print'></i> Print</button><br>";
                            }
                            ?>


                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

