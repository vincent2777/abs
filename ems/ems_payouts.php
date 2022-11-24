<?php
include "../partials/header.php";
include "../partials/sidebar.php";

?>


<style>
    label {
        font-weight: normal;
        font-family: 'Times New Roman', Times, serif;
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <ol class="breadcrumb">
            <li><a href="../dashboard">Home/ </a></li>
            <li class="active">All Payouts</li>
        </ol>
        <div class="row bg-white shadow-sm card p-3 rounded-0">


            <div class="col-md-12">


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading"> <i class="fas fa-edit"></i> Payout</div>
                    </div> <!-- /panel-heading -->

                    <div class="panel-body">

                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('#user_table').DataTable({
                                    dom: 'lBfrtip',
                                    "lengthMenu": [
                                        [10, 25, 50, -1],
                                        [10, 25, 50, "All"]
                                    ]
                                });

                            });
                        </script>
                        <?php


                        //get unit stakehold from settings
                        $sql_us = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
                        $fetch_us = mysqli_fetch_array($sql_us);
                        $stakehold = $fetch_us["stakehold"];
                        $salary_pay_date = $fetch_us["salary_pay_date"];

                        $amount = trim($_GET["pay"]);
                        $employee = trim($_GET["employee"]);
                        $paid_by = $_SESSION["user"];
                        $store_id = $_SESSION["store_id"];
                        $date = date("Y-m-d");
                        $time = date("h:i:s");

                        if ($_GET["type"] == "salary" &&  !empty($_GET["employee"])) {

                            //compute amount to be collected/paid
                            $amount_paid = doubleval($amount) - doubleval($stakehold);

                            //store data in salary and stakeholds
                            $insertSalary = mysqli_query($con, "INSERT 
                    INTO salary_payments (payment_status,store_id,username,amount_expected,amount_paid,payment_date,payment_time,paid_by) 
                    VALUES('paid','$store_id','$employee','$amount','$amount_paid','$date','$time','$paid_by')") or die(mysqli_error($con));

                            $sql_stakehold = mysqli_query($con, "UPDATE users 
                    SET accumulated_stakehold = accumulated_stakehold + '$stakehold'
                    WHERE username='$employee'") or die(mysqli_error($con));

                            if ($insertSalary && $sql_stakehold) {

                                echo "<div class='alert alert-success'><i class='fas fa-check-circle fa-1x'></i> Transaction Completed Successfully</div>";

                                //refresh page
                        ?>
                                <meta http-equiv="refresh" content="1;url=<?php echo $pageUrl . "ems/ems_payouts"; ?>" />


                            <?php
                            } else {
                                echo "<div class='alert alert-danger'>Oopss! We could not complete this transaction. Try again later.</div>";
                            }
                        } elseif ($_GET["type"] == "stakehold" &&  !empty($_GET["employee"])) {

                            $insertStakehold = mysqli_query($con, "INSERT 
                    INTO salary_stakeholds (store_id,username,amount,payment_date,payment_time,paid_by) 
                    VALUES ('$store_id','$employee','$amount','$date','$time','$paid_by')") or die(mysqli_error($con));


                            if ($insertStakehold) {

                                echo "<div class='alert alert-success'><i class='fas fa-check-circle fa-1x'></i> Transaction Completed Successfully. Stakehold Paid out</div>";

                                //refresh page
                            ?>
                                <meta http-equiv="refresh" content="1;url=<?php echo $pageUrl . "ems/ems_payouts"; ?>" />

                        <?php
                            } else {
                                echo "<div class='alert alert-danger'>Oopss! We could not complete this transaction. Try again later.</div>";
                            }
                        }

                        ?>

                        <div class="alert alert-info">
                            <h4><b class="text-danger">ATTENTION</b></h4>
                            <p>
                            <ul>
                                <li>Go to the settings page to set the Salary Stakehold</li>
                                <li>Go to the settings page to set the Salary Payment date (This will enable the Pay slary Button)</li>
                            </ul>
                            </p>
                        </div>
                        <br>
                        <table class="table table-striped" id="user_table">
                            <thead>
                                <tr>
                                    <th>Full name</th>
                                    <th>Phone Number</th>
                                    <th>Current Salary</th>
                                    <th>Accumulated Stakehold</th>
                                    <th>Expected Salary</th>
                                    <th>Salary Status (<?php echo date("m, Y"); ?>)</th>
                                    <th>Last Payment</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysqli_query($con, "SELECT * FROM users") or die(mysqli_error($con));
                                while ($fetch = mysqli_fetch_array($sql)) {

                                    $username =  $fetch["username"];
                                    //salary to be received
                                    $to_receive = $fetch["salary"] - $fetch_us["stakehold"];

                                    //get last salary pay date
                                    $sql_lp = mysqli_query($con, "SELECT * FROM salary_payments WHERE username='$username' GROUP BY username  ORDER BY payment_date DESC LIMIT 1") or die(mysqli_error($con));
                                    $fetch_lp = mysqli_fetch_array($sql_lp);
                                    $pay_status = $fetch_lp["payment_status"];
                                    $pay_date = $fetch_lp["payment_date"];

                                    echo "<tr>";
                                    echo "<td>" . $fetch["full_name"] . "</td>";
                                    echo "<td>" . $fetch["phone_number"] . "</td>";
                                    echo "<td>" . number_format($fetch["salary"]) . "</td>";
                                    echo "<td>" . number_format($fetch["accumulated_stakehold"]) . "</td>";
                                    echo "<td>" . number_format($to_receive)  . "</td>";
                                ?>

                                    <td>
                                        <?php

                                        if ($pay_status == "paid") {

                                            echo "<span class='text-success'><i class='fas fa-check-circle'> </i> Paid </span>";
                                        } else {
                                            echo "<span class='text-danger'>Pending</span>";
                                        }
                                        ?>
                                    </td>

                                    <td><?php
                                        if (!empty($pay_date)) {
                                            echo date("M d, Y", strtotime($pay_date));
                                        } else {
                                            echo "Payment still pending";
                                        } ?></td>

                                    <td>
                                        <?php
                                        if (date("d") >= $salary_pay_date  && date("d") <= 31) {

                                            if ($pay_status == "paid" && date("m", strtotime($pay_date)) == date("m")) {
                                                //if salary has been paid for the month, hide the button
                                            } else {

                                        ?>
                                                <button type="button" class="btn btn-success" value="<?php echo $fetch["salary"]; ?>" id=" <?php echo $fetch["username"]; ?>" onclick="confirmPaySalary(this.id,this.value)">
                                                    <i class="fas fa-credit-card"></i> Pay Salary</button>
                                        <?php }
                                        } ?>

                                        <button type="button" class="btn btn-info" value="<?php echo $fetch["accumulated_stakehold"]; ?>" id="<?php echo $fetch["username"]; ?>" onclick="confirmPayStakehold(this.id,this.value)">
                                            <i class="fas fa-wallet"></i> Pay Stakehold</button>
                                    </td>
                                    </tr>

                                <?php

                                }
                                ?>

                            </tbody>
                        </table>



                    </div> <!-- /panel-body -->

                </div> <!-- /panel -->

            </div> <!-- /col-md-12 -->


            <script>
                function confirmPaySalary(employee_id, amount) {


                    var x = window.confirm("Are you sure you want to payout this salary?");
                    if (x == true) {
                        location.href = "ems_payouts?type=salary&employee=" + employee_id + "&pay=" + amount;
                        return true;
                    } else {
                        return false;
                    }
                }

                function confirmPayStakehold(employee_id, amount) {

                    var x = window.confirm("Are you sure you want to payout this stakehold?");
                    if (x == true) {
                        location.href = "ems_payouts?type=stakehold&employee=" + employee_id + "&pay=" + amount;
                        return true
                    } else {
                        return false
                    }
                }
            </script>

        </div>
    </div>
</div>
</div>
</div>

<?php require_once '../includes/footer.php'; ?>