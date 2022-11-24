<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>

<div class="main-panel">
    <div class="content-wrapper">

        <div class="row justify-content-center bg-white">

            <div class="panel panel-default">

                <div class="panel-body">

                    <center>
                        <h3 class="pt-3 pb-0">Import Customer</h3>

                    </center>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow p-3 m-5">

                                <center>

                                    <h3 class="text-dark" style="font-size: 17px !important">
                                        <i class="mdi mdi-import"></i> <br> Import Customers From other Sources
                                    </h3>
                                </center>
                                <div class="alert alert-success" style="text-align: left;">
                                    They can only be 6 columns in your .csv file. It MUST follow the below pattern
                                    <br>
                                    ID,
                                    Last Name,
                                    First Name,
                                    Phone,
                                    Account Limit,
                                    Account Balance
                                    </p>
                                    <br>
                                    <strong>NB: Kindly eliminate all headers in the file</strong>
                                </div>
                                <form id="form" style="padding: 30px;" method="post" name="upload_customer" enctype="multipart/form-data">


                                    <fieldset>
                                        <div class="form-group">
                                            <label for="username" class="control-label">Choose File</label>
                                            <br>
                                            <span style="color:red"><b>File MUST be in Excel (.csv) Format</b></span>
                                            <div class="col-sm-12">
                                                <input type="file" class="form-control" required id="selectedFile" name="selectedFile" />
                                            </div>
                                        </div>

                                        <br>

                                        <div class="form-group">

                                            <center>
                                                <button type="submit" style="width:60%" name="uploadCustomerBtn" class="btn customize-abs-btn rounded btn-block">
                                                    <i class="fas fa-upload"></i> Upload </button>
                                            </center>
                                        </div>

                                        <div id="loader" style="display: none;margin-top: -50px">
                                            <center>
                                                <img src="<?php echo $pageUrl; ?>images/loading.gif" alt="" width="250">
                                                <h5 style="margin-top: -10%;">Please wait... <span style="color: red;">DO NOT REFRESH THIS PAGE</span></h5>
                                            </center>
                                        </div>
                                    </fieldset>
                                </form>


                            </div>

                        </div>
                        <!-- /panel -->
                    </div>
                    <!-- /col-md-4 -->
                </div>
                <!-- /row -->
            </div>
        </div>
        <!-- /row -->
    </div>

    <script src="<?php echo $pageUrl; ?>script/import_customer.js"></script>

    <?php include "../partials/footer.php"; ?>