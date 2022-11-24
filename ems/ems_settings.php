<?php
include "../partials/header.php";
include "../partials/sidebar.php";

?>

<div class="main-panel">
	<div class="content-wrapper">
    <ol class="breadcrumb">
            <li><a href="../dashboard">Home/ </a></li>
            <li class="active">EMS Settings</li>
        </ol>
<div class="row bg-white shadow-sm card p-3 rounded-0">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="fas fa-edit"></i> Settings</div>
            </div>

            <div class="panel-body">

                <?php
                $store_id = $_SESSION["store_id"];

                if (isset($_POST["updateEMSInfoBtn"])) {

                    updateEMSInfo($con);
                }


                ?>


                <div class="row justify-content-center">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" style="margin-left: 20px">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#appinfo">EMS Information</a>
                        </li>

                    </ul>

                     <!-- Tab panes -->
                     <div class="tab-content">
                        <div class="tab-pane container active" id="appinfo">
                            <div class="col-md-6">
                                <?php
                                //check if data is already available
                                $sql = "SELECT * FROM settings";
                                $query = $con->query($sql);
                                $results = $query->num_rows;

                                //check if data is in db
                                if ($results > 0) {

                                    $row = $query->fetch_array();
                                    $stakehold = $row["stakehold"];
                                    $salary_pay_date = $row["salary_pay_date"];
                                    $lateness_fee = $row["lateness_fee"];
                                    $work_resumes = $row["work_resumes_time"];
                                    $work_closes = $row["work_closes_time"];

                                ?>
                                    <form action="ems_settings" method="post" style="margin-top: 20px">
                                        <div class="form-group">
                                            <label for="">Monthly Stakehold</label>
                                            <input type="text" class="form-control" value="<?php echo $stakehold; ?>" id="stakehold" name="stakehold">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Lateness Fee</label>
                                            <input type="text" class="form-control" value="<?php echo $lateness_fee; ?>" id="lateness_fee" name="lateness_fee">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Work Resumption Time</label>
                                            <input type="text" class="form-control" placeholder="e.g 08:00:00" value="<?php echo $work_resumes; ?>" id="work_resume_date" name="work_resumes">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Work Closing Time</label>
                                            <input type="text" class="form-control" placeholder="e.g 18:00:00" value="<?php echo $work_closes; ?>" id="work_close_date" name="work_closes">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Salary Payment Date (In each Month) <br> <span class="text-danger"> This will activate the Pay Salary button on the specified date</span></label>
                                            <br>
                                            <select name="salary_pay_date" id="salary_pay_date" class="form-control">
                                                <option selected value="<?php echo $salary_pay_date; ?>">
                                                    <?php echo $salary_pay_date; ?>
                                                </option>
                                                <?php

                                                $count = 0;
                                                while ($count < 31) {
                                                    $count++;
                                                ?>
                                                    <option value="<?php echo $count; ?>">
                                                        <?php echo $count; ?>
                                                    </option>

                                                <?php
                                                }

                                                ?>
                                            </select>
                                        </div>

                                        <center>
                                            <button class="btn btn-success" name="updateEMSInfoBtn" style="width: 100%;max-width:100%">Update Information</button>
                                        </center>
                                    </form>

                                <?php } ?>

                            </div>

                        </div>
                    </div>

                </div>

            </div> <!-- /panel-body -->
        </div> <!-- /panel -->
    </div> <!-- /col-md-12 -->
</div> <!-- /row -->

    </div>
</div>
</div>
                                            </div>


<?php require_once '../partials/footer.php'; ?>