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
<div class="container">
    <div class="col-md-12">
        <div class="panel panel-default" style="border:0px !important">
            <div class="panel-heading"> <i class="fas fa-calendar"></i> Reprint Payment Slip</div>

            <div class="panel-body">
                <?php
                if (isset($_GET["ref"])) {

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

                    $query3 = mysqli_query($con, "SELECT * FROM balance_sheet WHERE payment_ref='" . $_GET['ref'] . "'") or die(mysqli_error($con));
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

                             <center>
                            <img src="<?php echo $bimage; ?>" style="width: 70px;height: 70px">
                            <br>
                             <span style="line-height: 1;font-size: 18px;font-family:Arial, Helvetica, sans-serif;text-align:left"><strong><b><?php echo ucwords($bname); ?></b></strong></span>
                        </center>
                        
                                <br>
                    <div id="print-holder1" style="margin-left:10px;margin-top: -37px">

                            <br>
                            <p style="line-height: 1;text-align:center">
                               
                                <span style="line-height: 1;font-size: 11px;font-family:Arial, Helvetica, sans-serif"><strong><b><?php echo $bslogan; ?></b></strong></span>
                            </p>

                       
                    </div>
                    <div id="print-holder2" style="margin-left:-45px;">

                        <p style="font-size: 12px">
                            <b>Issued By: </b> <?php echo ucwords($query3Data['cashier_id']); ?>
                            <br>
                            <b>Transaction No:</b> <?php echo $query3Data['payment_ref']; ?>
                            <br>
                            <b>Date:</b> <?php $d = date('d M, Y',strtotime($query3Data['payment_date'])); echo $d; ?>
                            <?php

                            $cust_id = $query3Data["customer_id"];
                            $sql2 = mysqli_query($con, "SELECT * FROM customers WHERE cust_id='$cust_id'") or die(mysqli_error($con));
                            $row = mysqli_fetch_array($sql2);

                                echo "<br><b>Customer:</b> <span>" . $row['cust_name'] . "</span><br>";
                                if(!empty($row['cust_phone'])){
                                echo "<b>Mobile:</b> <span>" . $row['cust_phone'] . "</span><br>";
                            }

                            if(!empty($row['cust_address'])){
                                echo "<b>Address:</b> <span>" . $row['cust_address'] . "</span><br>";
                            }
                          

                            ?>
                        </p>

                    </div>
                    <br>
                        <center>
                        <p><b>PREVIOUS BALANCE:</b> <?php echo $currency.number_format($query3Data['amount_paid'] + $row['cust_owing'],2); ?></p>
                        <p><b>AMOUNT PAID:</b> <?php echo $currency.number_format($query3Data['amount_paid'],2); ?></p>
                        <p><b>PAID WITH:</b> <?php echo $query3Data['pay_type']; ?></p>
                        <p><b>CURRENT BALANCE:</b> <?php echo $currency.number_format($row['cust_owing'],2); ?></p>
                        </center>                    
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
                        <p id="copy-type"></p>
                        </center>

                        <?php
                        if ($sql) {
                            echo "<button class='btn customize-abs-btn hidden-print' id='printBtn'>
							<i class='fas fa-print'></i> Print</button><br>";
                        }
                        ?>

                <?php } ?>
            </div>
        </div>
    </div>
</div>