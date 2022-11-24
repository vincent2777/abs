<?php
include "../partials/header.php";
include "../partials/sidebar.php";

?>


<div class="main-panel">
    <div class="content-wrapper">
        <ol class="breadcrumb">
            <li><a href="../dashboard">Home/ </a></li>
            <li class="active">Time Attendance / Activity Monitoring</li>
        </ol>
        <div class="row bg-white shadow-sm card p-3 rounded-0">
            <div class="col-md-12">

                <?php

                $username = $_POST["username"];
                $nowDate = date("Y-m-d");
                $nowTime = date("H:i:s");
                $note = $_POST["note"];

                if (isset($_POST["clockInBtn"])) {
                    //check if already clocked in
                    $check = mysqli_query($con, "SELECT * FROM attendance WHERE clock_in_date='$nowDate' AND username='$username'");
                    $note = "(Clock In - " . $note . ") ";

                    if (mysqli_num_rows($check) > 0) {

                        echo "<div class='alert alert-danger'>Sorry, you have already Clocked In for today.</div>";
                    } else {

                        $ci_sql = mysqli_query($con, "INSERT INTO attendance(current_lateness_fee,clock_in_date,clock_in_time,username,user_note) 
                    VALUES('$lateness_fee','$nowDate','$nowTime','$username','$note')");


                        if ($ci_sql) {
                            //user clocked in
                            echo "<div class='alert alert-success'>" . ucwords($username) . " Just Clocked in successfully. Date: " . $nowDate . " Time: " . $nowTime . " </div>";
                        } else {
                            echo "<div class='alert alert-danger'>Ooops! An error occured. Please try again.</div>";
                        }
                    }
                }



                if (isset($_POST["clockOutBtn"])) {

                    //check if already clocked in
                    $check = mysqli_query($con, "SELECT * FROM attendance WHERE clock_out_date='$nowDate' AND username='$username'");

                    if (mysqli_num_rows($check) > 0) {
                        echo "<div class='alert alert-danger'>Sorry, you have already Clocked Out for today.</div>";
                    } else {
                        //add sapce to notes
                        $note = " (Clock Out  - " . $note . ")";

                        $co_sql = mysqli_query($con, "UPDATE attendance 
                        SET clock_out_date='$nowDate', current_lateness_fee='$lateness_fee',
                        clock_out_time='$nowTime',user_note=CONCAT(user_note,'$note') WHERE username='$username' AND clock_in_date='$nowDate'");

                        if ($co_sql) {
                            //user clocked out
                            echo "<div class='alert alert-success'>" . ucwords($username) . " Just Clocked out successfully. Date: " . $nowDate . " Time: " . $nowTime . " </div>";
                        } else {
                            echo "<div class='alert alert-danger'>Ooops! An error occured. Please try again.</div>";
                        }
                    }
                }
                ?>

                <form action="" method="post" autocomplete="off">

                    <div class="row justify-content-center p-3">
                        <div class="col-md-3">
                            <label for="">Employee Username</label>
                            <input list="username" name="username" class="form-control" required>
                            <datalist id="username">
                                <?php
                                $userssql = mysqli_query($con, "SELECT * FROM users");

                                while ($fetchUsers = mysqli_fetch_array($userssql)) {
                                ?>
                                    <option value="<?php echo ucwords($fetchUsers["username"]); ?>" id="<?php echo $fetchUsers["full_name"]; ?>">

                                    <?php
                                }
                                    ?>
                            </datalist>


                        </div>

                        <div class="col-md-4">
                            <label for="">Consent Note (<i>fill only if necessary</i>)</label>
                            <textarea class="form-control" name="note" cols="10" rows="10"></textarea>
                        </div>

                        <div class="col" style="margin-top: 20px">
                            <button type="submit" class="btn btn-lg bg-success text-white" name="clockInBtn">Clock In</button>
                            <button type="submit" class="btn btn-lg bg-danger text-white" name="clockOutBtn">Clock Out</button>
                        </div>
                    </div>

                </form>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading mt-5 mb-3"> <i class="fas fa-edit"></i> Time Attendance</div>
                    </div>


                    <div class="panel-body">

                        <div class="remove-messages"></div>



                        <?php

                        $do_action = $_GET["do"];
                        $logid = $_GET["logid"];

                        if ($do_action == "delete" && $logid != "") {

                            $deleteSQL = mysqli_query($con, "DELETE FROM login_log WHERE id='$logid' ") or die(mysqli_error($con));

                            if ($deleteSQL) {
                                echo '<div class="alert alert-success" style="border-left: 5px solid green;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong><i class="fas fa-ok-sign"></i> Data have been deleted Successfully</strong>
  </div>';
                            }
                        }

                        ?>

                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('#time_table').DataTable({
                                    dom: 'lBfrtip',
                                    "aaSorting": [],
                                    "lengthMenu": [
                                        [10, 25, 50, -1],
                                        [10, 25, 50, "All"]
                                    ]
                                });

                            });
                        </script>
                        <table class="display table table-flush" id="time_table">

                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Fullname</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th style="width: 35%">Remark</th>
                                    <th>Consent Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $sql = mysqli_query($con, "SELECT * FROM attendance ORDER BY id DESC") or die(mysqli_error($con));

                                while ($fetch = mysqli_fetch_array($sql)) {

                                    $username = $fetch["username"];

                                    $sql2 = mysqli_query($con, "SELECT * FROM users WHERE username='$username'") or die(mysqli_error($con));
                                    $fetch2 = mysqli_fetch_array($sql2);

                                    echo "<td>" . $fetch["username"] . "</td>";
                                    echo "<td>" . $fetch2["full_name"] . "</td>";
                                    echo "<td>" . $fetch["clock_in_date"] . " " . $fetch["clock_in_time"] . "</td>";
                                    echo "<td>" . $fetch["clock_out_date"] . " " . $fetch["clock_out_time"] . "</td>";

                                ?>

                                <?php

                                    //calculate difference between resume time and clock in time to give remark
                                    $clock_in_date = $fetch["clock_in_date"];
                                    $clock_in_time = $fetch["clock_in_time"];

                                    $today = date("Y-m-d");
                                    $workResumeDateTime = $today . " " . $work_resumes;
                                    $userResumedDateTime = $clock_in_date . " " . $clock_in_time;

                                    $d1 = new DateTime($workResumeDateTime);
                                    $d2 = new DateTime($userResumedDateTime);
                                    $interval = $d1->diff($d2);
                                    $diffInHours   = $interval->h;
                                    $diffInMinutes = $interval->i;
                                    $diffInSeconds = $interval->s;


                                    //calculate difference between close time and clock out time to give remark
                                    $clock_out_date = $fetch["clock_out_date"];
                                    $clock_out_time = $fetch["clock_out_time"];

                                    $today = date("Y-m-d");
                                    $workClosesDateTime = $today . " " . $work_closes;
                                    $userClosedDateTime = $clock_out_date . " " . $clock_out_time;

                                    $d3 = new DateTime($workClosesDateTime);
                                    $d4 = new DateTime($userClosedDateTime);
                                    $intervalOut = $d3->diff($d4);
                                    $inHours   = $intervalOut->h;
                                    $inMinutes = $intervalOut->i;
                                    $inSeconds = $intervalOut->s;

                                    //check if user came in early
                                    // clock in time is greater than resume time

                                    $resumeRemark = "";
                                    if ($d2 == $d1) {
                                        //at resumption time

                                        $resumeRemark = "early";
                                    } else if ($d2 < $d1) {
                                        //before resumption time

                                        $resumeRemark = "earlier";
                                    } elseif ($d2 > $d1) {
                                        //after resumption time

                                        $resumeRemark = "late";
                                    }


                                    $closeRemark = "";
                                    if ($d4 == $d3) {
                                        //at closing time

                                        $closeRemark = "on time";
                                    } else if ($d4 < $d3) {
                                        //before closing time

                                        $closeRemark = "earlier";
                                    } elseif ($d4 > $d3) {
                                        //after closing time

                                        $closeRemark = "late";
                                    }

                                    if ($today == $clock_in_date) {
                                        echo "<td>
                <div class='alert alert-info'><h6>You came in <b>" . $diffInHours . "hrs, " . $diffInMinutes . "min and " . $diffInSeconds . " sec " . $resumeRemark . "</b> today ";
                                        if (!empty($clock_out_time)) {
                                            echo  " and closed <b>" . $inHours . "hrs, " . $inMinutes . "min and " . $inSeconds . " sec " . $closeRemark . "</b>  </h6> </div>";
                                        }
                                        echo "</td>";
                                    } else {
                                        echo "<td></td>";
                                    }



                                    echo "<td><h6>" . $fetch["user_note"] . "</h6></td>";
                                    echo "</tr>";
                                }

                                ?>

                            </tbody>
                        </table>
                        <!-- /table -->

                    </div> <!-- /panel-body -->
                </div> <!-- /panel -->
            </div> <!-- /col-md-12 -->
        </div> <!-- /row -->

        <script type="text/javascript">
            function confirmDelete(id) {

                var x = window.confirm("You are about Deleting a Record. Do you wish to proceed?");

                if (x == true) {
                    window.open("time_attendance?do=delete&logid=" + id, '_self');
                }
            }
        </script>

    </div>
</div>
</div>
</div>

<?php require_once '../includes/footer.php'; ?>