<?php include "../partials/header.php";
include "../partials/sidebar.php"; ?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <div class="row bg-white p-4">
            <div class="col-md-8">
                <h3 class="ml-2">Customers</h3>
            </div>

            <div class="col-md-4">
                <?php
                if ($_SESSION["role"] == "owner" || $_SESSION["role"] == "manager") {
                ?>
                    <a href="import_customers" class="btn bg-primary text-white"> Import Customers <i class="mdi mdi-upload"></i></a>

                <?php } ?>
            </div>
        </div>
        <hr>

        <div class="row p-5 card bg-white rounded-0 shadow-sm">
            <div class="col-lg-12">
                <div class="panel panel-default">

                    <div class="panel-body">

                        <?php
                        $current_user = $_SESSION["user"];
                        $store_id = $_SESSION["store_id"];

                        if (isset($_POST["saveBtn"])) {

                            $name = $_POST["name"];
                            $phone1 = $_POST["phone1"];
                            $address = $_POST["address"];
                            $category = $_POST["category"];
                            $dob = $_POST["dob"];
                            $limit = $_POST["climit"];
                            $cust_id = mt_rand(20, 99) . mt_rand(100, 999);
                            $store_id = $_SESSION["store_id"];
                            $today = date("Y-m-d");

                            $new_query = $con->query(
                                "INSERT INTO 
        customers (store_id,reg_date,customer_type,cust_id,cust_name,cust_phone,cust_address,cust_dob,cust_credit_limit,cust_owing) 
        VALUES('$store_id','$today','$category','$cust_id','$name','$phone1','$address','$dob','$limit',0)"
                            );

                            if (!empty($limit)) {
                                $queryLog = $con->query(
                                    "INSERT INTO 
            customer_credit_log (store_id,prev_amount,new_amount,cashier_id,change_date,customer_id) 
            VALUES('$store_id','0','$limit','$current_user','$today','$cust_id')"
                                );
                            }


                            if ($new_query) {
                                echo "<div class='alert alert-success'>New Customer Added</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Oops!! An error occured. Please try again</div>";
                            }
                        }

                        if ($_GET["do"] == "update") {

                            $today = date("Y-m-d");

                            $name = $_POST["modal_custname"];
                            $phone1 = $_POST["modal_phone1"];
                            $address = $_POST["modal_address"];
                            $dob = $_POST["modal_dob"];
                            $category = $_POST["modal_category"];
                            $cust_id = $_POST["modal_customer_id"];
                            $limit = $_POST["modal_climit"];
                            $amountB4Change = $_POST["modal_prevamount"];

                            //check if previous credit limit has been changed
                            if ($amountB4Change != $limit) {

                                $updateClimitLog = $con->query(
                                    "INSERT INTO 
                                    customer_credit_log (store_id,prev_amount,new_amount,cashier_id,change_date,customer_id) 
                                    VALUES('$store_id','$amountB4Change','$limit','$current_user','$today','$cust_id')"
                                                        );
                            }


                            $new_query = $con->query(
                                "UPDATE 
                                customers SET 
                                cust_name = '$name',
                                cust_phone = '$phone1',
                                cust_address = '$address',
                                cust_dob='$dob',
                                customer_type='$category',
                                cust_credit_limit='$limit'
                                WHERE cust_id = '$cust_id'"
                            );

                            if ($new_query) {
                                echo "<div class='alert alert-success'>Data Updated </div>
                                <script>window.open('customers','_self');</script>
                                ";
                            } else {
                                echo "<div class='alert alert-danger'>Oops!! An error occured. Please try again</div>";
                            }
                        }

                        $do_action = $_GET["action"];
                        $customer_id = $_GET["cust_id"];
                        $store_id = $_GET["store_id"];

                        //delete customer

                        if ($do_action == "delete" && $customer_id != "") {

                            $deleteSQL = mysqli_query($con, "DELETE FROM customers WHERE cust_id='$customer_id' ") or die(mysqli_error($con));

                            if ($deleteSQL) {
                                echo '<div class="alert alert-success" style="border-left: 5px solid green;">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong><i class="fas fa-ok-sign"></i> Customer have been deleted Successfully</strong>
                                    </div>';
                            }
                        }

                        //delete store

                        if ($do_action == "delete" && $store_id != "") {

                            $deleteSQL = mysqli_query($con, "DELETE FROM stores WHERE id='$store_id' ") or die(mysqli_error($con));

                            if ($deleteSQL) {
                                echo '<div class="alert alert-success" style="border-left: 5px solid green;">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong><i class="fas fa-ok-sign"></i> Store have been deleted Successfully</strong>
                                    </div>';
                            }
                        }

                        if (isset($_POST["payNowBtn"])) {
                            $credit_custname = $_POST["credit_custname"];
                            $credit_amount = $_POST["credit_amount"];
                            $credit_topay = $_POST["credit_topay"];
                            $paymethod = $_POST["credit_paymethod"];
                            $today = date("Y-m-d");
                            $custID = $_POST["credit_customer_id"];
                            $reference = mt_rand(100, 999) . date('s') . mt_rand(1, 9);

                            if (empty($credit_custname)) {
                                echo "<div class='alert alert-danger'>Oops!! An error occured. Please try again</div>";
                            } else {

                                $pay_time = date("h:i:s");

                                //update customer owing with new amount
                                $query1 = mysqli_query($con, "UPDATE customers SET cust_owing=cust_owing-$credit_topay WHERE cust_id='$custID'") or die(mysqli_error($con));
                                //insert to balance the payment the customer just made

                                $sql2 = "INSERT INTO balance_sheet(pay_time,store_id,cashier_id,customer_id,pay_type,amount_paid,payment_date,payment_ref) 
                VALUES('$pay_time','$store_id','$current_user','$custID','$paymethod', '$credit_topay','$today','$reference')";

                                $query2 = mysqli_query($con, $sql2) or die(mysqli_error($con));
                                if ($query1 && $query2) {
                                    echo "<div class='alert alert-success'>Payment Updated </div>
                    <script>window.open('customers','_self');</script>
                    ";
                                }
                            }
                        }
                        ?>

                        <div class="row">
                            <div class="col-md-4">
                                <form action="" id="new_customer" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control" required id="cust_name" name="name">
                                    </div>

                                    <input type="hidden" name="customer_id" />
                                    <div class="form-group">
                                        <label for="">Address</label>
                                        <input type="text" class="form-control" id="address" name="address">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phone Number 1:</label>
                                        <input type="text" class="form-control" id="phone1" name="phone1">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Category</label>
                                        <select name="category" id="" class="form-control">
                                            <option value="">-Select Category</option>
                                            <option value="regular">Regular</option>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Birth Date</label>
                                        <input type="text" class="form-control" id="dob" name="dob">
                                    </div>
                                    <?php
                                    if ($_SESSION["role"] == "owner" || $_SESSION["role"] == "manager") {
                                    ?>
                                        <div class="form-group">
                                            <label for="">Credit Limit</label>
                                            <input type="text" class="form-control" id="climit" name="climit">
                                        </div>

                                    <?php } ?>
                                    <center>

                                        <button class="customize-abs-btn" name="saveBtn" style="width: 100%;max-width:100%">Save</button>

                                    </center>
                                </form>

                            </div>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#customers').DataTable({
                                        dom: 'lBfrtip',
                                        "aaSorting": [],
                                        "lengthMenu": [
                                            [10, 25, 50, -1],
                                            [10, 25, 50, "All"]
                                        ]
                                    });
                                });
                            </script>
                            <div class="col-md-8">

                                <table class="table table-striped" id="customers">

                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Limit</th>
                                            <th>Credit</th>
                                            <th>Contact 1</th>
                                            <th style="width: 200px;"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $customerArr = array();

                                        $sql = "SELECT cust_id,cust_name,cust_credit_limit,cust_phone,cust_owing FROM customers ORDER BY cust_name ASC LIMIT 3000";
                                        $result = $con->query($sql);
                                        while ($value = $result->fetch_assoc()) {

                                            $data = [
                                                "cust_id" => $value["cust_id"],
                                                "cust_name" => $value["cust_name"],
                                                "cust_credit_limit" => $value["cust_credit_limit"],
                                                "cust_phone" => $value["cust_phone"],
                                                "cust_owing" => $value["cust_owing"]
                                            ];

                                            array_push($customerArr, $data);
                                        }

                                        $dataEncode = json_encode($customerArr);
                                        $dataDecode = json_decode($dataEncode, true);

                                        foreach ($dataDecode as $key => $value) {

                                            $cust_id = $value["cust_id"];
                                            $cust_name = $value["cust_name"];
                                            $cust_credit_limit = $value["cust_credit_limit"];
                                            $cust_phone1 = $value["cust_phone"];
                                            $cust_owing = $value["cust_owing"];

                                        ?>

                                            <tr>
                                                <td><?php echo ucwords($cust_name); ?></td>
                                                <td><?php echo number_format($cust_credit_limit, 2); ?></td>
                                                <td>
                                                    <?php
                                                    echo number_format($cust_owing, 2); ?>
                                                </td>
                                                <td><?php echo $cust_phone1; ?></td>
                                                <td>

                                                    <button class="customize-abs-btn " data-toggle="modal" data-target="#payCreditModal" id="<?php echo $cust_id; ?>" onclick="payNow(this.id)">
                                                        <i class="mdi mdi-credit-card"></i></button>
                                                    <button class="customize-abs-btn " data-toggle="modal" data-target="#editCustomerModal" id="<?php echo $cust_id; ?>" onclick="editCustomer(this.id)">
                                                        <i class="mdi mdi-grease-pencil"></i></button>

                                                    <a class="customize-abs-btn" id="reprint_customer_credit?cust_id=<?php echo $cust_id; ?>" onclick="reprintCreditBal(this.id);">
                                                        <i class="mdi mdi-printer"></i>
                                                    </a>

                                                    <?php
                                                    if ($_SESSION["role"] == "owner" || $_SESSION["role"] == "manager") {
                                                    ?>
                                                        <form action="customer_report" method="post" style="display: inline;">
                                                            <input type="hidden" name="customerid" value="<?php echo $cust_id; ?>">
                                                            <button class="customize-abs-btn ml-1" name="reportBtn">
                                                                <i class="mdi mdi-chart-histogram"></i></button>
                                                        </form>
                                                        <a class="customize-abs-btn" onclick="confirmDelete('<?php echo $cust_id; ?>')" href="#"><i class="mdi mdi-trash-can"></i></a>
                                                    <?php } ?>
                                                </td>

                                            </tr>

                                        <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

           
        </div>
    </div>



    <?php include "../partials/footer.php"; ?>