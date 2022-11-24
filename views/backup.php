<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row bg-white">
            <div class="col-md-12 mx-auto mb-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading">
                            <h3 class="p-3">System Database Backup</h3>
                        </div>
                    </div> <!-- /panel-heading -->

                    <div class="panel-body">
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('#backup').DataTable({
                                    dom: 'lBfrtip',
                                    "lengthMenu": [
                                        [10, 25, 50, -1],
                                        [10, 25, 50, "All"]
                                    ]
                                });
                            });
                        </script>
                        <div class="row justify-content-center mt-1 g-white">
                            <div class="col-md-4 mx-auto mb-5">
                                <div class="card shadow">
                                    <?php

                                    if (isset($_POST["backupDbBtn"])) {
                                        backupDB();
                                    }

                                    ?>
                                    <center>
                                        <i class="fas fa-database fa-2x" style="margin-top: 30px;"></i>

                                    </center>
                                    <h3 class="text-dark mx-auto"> Database Backup</h3>
                                    <div class="alert alert-info" style="border-left: 5px solid blue;" style="margin-left: 10px;margin-right:10px">
                                        <i class="fas fa-info fa-2x"></i>
                                        <p style="font-size:17px;font-family:'Times New Roman';color:black">
                                            Hi, do you know that it is a good practice to consider backing up the System Database occassionally incase of Hardware Failure?</p>
                                    </div>

                                    <form style="padding: 30px;" method="post">
                                        <fieldset>
                                            <div class="form-group">
                                                <center>
                                                    <button type="submit" name="backupDbBtn" class="btn btn-info btn-block p-3">
                                                        <i class="mdi mdi-harddisk"></i> Backup Now </button>
                                                </center>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>

                            </div>
                            <div class="col-md-8 mx-auto mb-5">
                                <?php

                                if (isset($_GET["restoredb"])) {
                                    $getBackupName = $_GET["restoredb"];
                                    $filePath   = $getBackupName;
                                    if (restoreDatabaseTables($con, $filePath)) {
                                        echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> 
                                    Database Restore was successfull.</div>";
                                    }
                                }


                                if (isset($_GET["deletedb"])) {
                                    $file = $_GET["deletedb"]; // get all file names

                                    if (is_file($file)) {
                                        if (unlink($file)) {
                                            echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> 
                                        Database backup has been deleted was successfully.</div>";
                                        } // delete file
                                    }
                                }

                                ?>
                                <table class="table table-striped" id="backup">
                                    <thead>
                                        <tr>
                                            <th>File</th>
                                            <th>Size</th>
                                            <th>Backup Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $fileList = glob('../backup/*.sql');
                                        $pageUrl = "";

                                        if (!empty($_SESSION["current_host"])) {
                                            $pageUrl = "http://" . $_SESSION["current_host"] . "/abs/";
                                        } else {
                                            $pageUrl = "http://" . $_SERVER["HTTP_HOST"] . "/abs/";
                                        }

                                        foreach ($fileList as $filename) {
                                            $size = intval(filesize($filename)) / 1000;
                                            $date = str_replace("../backup/absaccounting_backup_", "", $filename);
                                            $date = substr($date, 0, 10);
                                            $date = date("l M, Y", strtotime($date));

                                            $file = str_replace("../backup/", "", $filename);
                                            $download_link = $file;
                                            if (is_file($filename)) {
                                                echo "<tr>";
                                                echo "<td>" . str_replace(".sql", "", $file) . "</td>";
                                                echo "<td>" . floor($size) . "kb</td>";
                                                echo "<td>" . $date . "</td>";
                                        ?>
                                                <td>
                                                    <a href="<?php echo $download_link; ?>" class='btn p-2 customize-abs-btn'>
                                                        <i class='mdi mdi-download'></i></a>
                                                    <a onclick="confirmBackupRestore('<?php echo $filename; ?>')" class='btn btn-info p-2'>
                                                        <i class='mdi mdi-backup-restore'></i></a>
                                                    <a onclick="confirmBackupDelete('<?php echo $filename; ?>')" class='btn customize-abs-btn p-2'>
                                                        <i class='mdi mdi-trash-can'></i></a>
                                                </td>
                                                </tr>

                                        <?php
                                            }
                                        }

                                        ?>

                                    </tbody>
                                </table>
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
    </div>

    <script>
        function confirmBackupRestore(file_name) {
            var x = window.confirm("CAUTION!!! Are you sure you want to restore this database? NOTE - All data will be erased..");
            if (x == true) {
                window.open("backup?restoredb=" + file_name, '_self');
            }
        }

        function confirmBackupDelete(file_name) {
            var x = window.confirm("CAUTION!!! Are you sure you want to delete this database?");
            if (x == true) {
                window.open("backup?deletedb=" + file_name, '_self');
            }
        }
    </script>

    <?php include "../partials/footer.php"; ?>