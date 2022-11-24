<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row bg-white">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading">
                            <h3 class="p-3">Reminders</h3>
                        </div>
                    </div> <!-- /panel-heading -->
                    <div class="panel-body">
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('#bday').DataTable({
                                    dom: 'lBfrtip',
                                    "lengthMenu": [
                                        [10, 25, 50, -1],
                                        [10, 25, 50, "All"]
                                    ]
                                });
                            });
                        </script>
                        <table class="table table-striped" id="bday">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Birthday</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysqli_query($con, "SELECT * FROM customers ORDER BY cust_name ASC") or die(mysqli_error($con));

                                $total_trnx = 0;
                                $count = 0;
                                while ($fetch = mysqli_fetch_array($sql)) {

                                    $cust_id = $fetch["cust_id"];
                                    $sql2 = mysqli_query($con, "SELECT AVG(total_amount) as total FROM sold_products 
                            WHERE customer_id='$cust_id' GROUP BY invoice_number");

                                    while ($fetch2 = mysqli_fetch_array($sql2)) {
                                        $total_trnx += $fetch2["total"];
                                    }

                                    //get countdown to birthday
                                    $cust_bdate = $fetch["cust_dob"];
                                    $now = date("Y-m-d");
                                    $dateNow = new DateTime($now);
                                    $cust_bdate = new DateTime($cust_bdate);
                                    $bday_interval = $dateNow->diff($cust_bdate);

                                    $bday_interval = $bday_interval->format('%R%a');

                                    //get countdown in days
                                    $countdownTo = $bday_interval;
                                    $count++;

                                    echo "<tr>";
                                    echo "<td>$count</td>";
                                    echo "<td>" . ucwords($fetch["cust_name"]) . "</td>";
                                    echo "<td>" . $fetch["cust_phone"] . "</td>";
                                    echo "<td>" . date("d M, Y", strtotime($fetch["cust_dob"])) . "</td>";

                                    $msg = "<td><div class='alert alert-success'>
						<i class='fas fa-birthday-cake'></i> Today is " . ucwords($fetch["cust_name"]) . " Birthday";

                                    if ($countdownTo == 0) {

                                        //show send SMS button if birthday remains a day or on the exact date
                                        if ($countdownTo <= 1) {

                                            //show send sms button if the customer phone number is present

                                            if (!empty($fetch["cust_phone"])) {
                                                echo $msg . " <a id='" . $fetch["cust_id"] . "' href='#sendBdayMsgModal' data-toggle='modal' class='" . $fetch["cust_name"] . "' onclick='composeSms(this.className,this.id)' > 
                                    <span class='badge text-white' style='background-color: #4CAF50'>Send SMS</span></a>
                                    </div></td>";
                                            }
                                        } else {
                                            echo $msg . "</div></td>";
                                        }
                                    } elseif (strpos($countdownTo, '-') !== false) {
                                        //date back dated
                                        echo "<td> <div class='alert alert-danger text-white'><i class='fas fa-info'></i> Birthday has been Celebrated for this year</div></td>";
                                    } else {
                                        echo "<td>
                                <div class='alert alert-warning'>
							<i class='fas fa-thumbs-up'></i> " . ucwords($fetch["cust_name"]) . " is celebrating in  " . $countdownTo . "day(s)</div></td>";
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                        <!-- /table -->

                    </div> <!-- /panel-body -->
                </div> <!-- /panel -->
            </div> <!-- /col-md-12 -->
        </div> <!-- /row -->
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="sendBdayMsgModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fas fa-star"></i> Send Birthday Message to <span id="customer"></span></h4>
                </div>
                <div class="modal-body">

                    <?php

                    //get settings
                    $setSql = "SELECT * FROM settings";
                    $setQuery = $connect->query($setSql);
                    $setRow = $setQuery->fetch_array();
                    $sender = $setRow["bday_message_sender"];
                    $message = $setRow["bday_message"];

                    ?>

                    <input type="hidden" id="customer_id">

                    <div class="form-group">
                        <label for="">Message Sender</label>
                        <input type="text" maxlength="11" id="bday_sender" class="form-control" value="<?php echo ucwords($sender); ?>">
                    </div>

                    <textarea name="" id="bday_msg" class="form-control" cols="30" rows="5"><?php echo ucwords($message); ?></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>
                    <button type="button" class="btn customize-abs-btn" id="sendMsg" onclick="sendBDaySms()"> <i class="fas fa-ok-sign"></i> Send Message</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <?php include "../partials/footer.php"; ?>