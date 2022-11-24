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
                            <h3 class="p-3">Product Expiry Reminder</h3>
                        </div>
                    </div> <!-- /panel-heading -->
                    <div class="panel-body">
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('#pexpiry').DataTable({
                                    dom: 'lBfrtip',
                                    "lengthMenu": [
                                        [10, 25, 50, -1],
                                        [10, 25, 50, "All"]
                                    ]
                                });
                            });
                        </script>
                        <table class="table table-striped" id="pexpiry">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Product Number</th>
                                    <th>Product Name</th>
                                    <th>Expiry Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysqli_query($con, "SELECT * FROM product ORDER BY product_name ASC") or die(mysqli_error($con));

                                $total_trnx = 0;
                                $count = 0;
                                while ($fetch = mysqli_fetch_array($sql)) {

                                    $product_id = $fetch["product_id"];
                                    $pexpiry_date = $fetch["pexpiry_date"];

                                    //get countdown to birthday
                                    $now = date("Y-m-d");
                                    $dateNow = new DateTime($now);
                                    $pexpiry_date = new DateTime($pexpiry_date);
                                    $expiry_interval = $dateNow->diff($pexpiry_date);
                                    $expiry_interval = $expiry_interval->format('%R%a');
                                    //get countdown in days
                                    $countdownTo = $expiry_interval;
                                    $count++;


                                    echo "<tr>";
                                    echo "<td>$count</td>";
                                    echo "<td>" . ucwords($product_id) . "</td>";
                                    echo "<td>" . ucwords($fetch["product_name"]) . "</td>";
                                    echo "<td>" . date("d M, Y", strtotime($fetch["pexpiry_date"])) . "</td>";

                                    $msg = "<td><div class='alert alert-danger' style='font-size: 12px !important'>
                            <i class='fas fa-ban'></i> This Product has expired. Kindly discard";

                                    if ($countdownTo == 0) {

                                        echo $msg . "</div></td></tr>";
                                    } elseif (strpos($countdownTo, '-') !== false) {
                                        //date back dated
                                        echo $msg . "</div></td></tr>";
                                    } else {
                                        echo "<td><div class='alert alert-warning'>
							<i class='fas fa-thumbs-up'></i> " . ucwords($fetch["product_name"]) . " will be expiring in  " . $countdownTo . " day(s)</div></td></tr>";
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


    <script src="<?php echo $pageUrl; ?>/script/birthday_msg.js"></script>


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