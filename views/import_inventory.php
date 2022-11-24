<?php
include "../partials/header.php";
include "../partials/sidebar.php"; ;
?>

<div class="main-panel">
  <div class="content-wrapper">
<div class="row bg-white shadow-sm rounded-0 p-3">
		<div class="col-md-12">

			<ol class="breadcrumb">
				<li><a href="../dashboard">Home/ </a></li>
				<li class="active">Import Inventory</li>
			</ol>

			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="page-heading"> <i class="fas fa-barcode"></i> Inventory DATA</div>
				</div> <!-- /panel-heading -->

				<div class="panel-body">	
					<div class="row justify-content-center">
						<div class="col-md-6 card shadow-sm rounded-0 mb-5">

								<i class="fas fa-barcode fa-2x" style="margin-top: 30px;"></i>
								<h3 class="text-dark" style="font-size: 17px !important"> Import Inventory From other Sources</h3>
								<div class="alert alert-success" style="text-align: left;">
									They can only be 5 columns in your .csv file. It MUST follow the below pattern
									<br>
									Product Number,
									Product Name,
									Cost Price,
									Selling Price,
									Quantity.

									</p>
									<br>
									<strong>NB: Kindly eliminate all headers in the file</strong>
								</div>
								<form id="form" style="padding: 30px;" method="post" name="upload_inventory" enctype="multipart/form-data">


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
												<button type="submit" style="margin-top: 30px;width:60%" name="uploadInventoryBtn" class="btn customize-abs-btn rounded btn-block">
													<i class="fas fa-upload"></i> Upload </button>
											</center>
										</div>
										<br>
										<br>
										<div id="loader" style="margin-bottom: 20px;margin-left: -10%;display: none">
											<img src="../images/loading.gif" alt="">
											<br>
											<center>
												<h5>Please wait... <span style="color: red;">DO NOT REFRESH THIS PAGE</span></h5>
											</center>
										</div>
									</fieldset>
								</form>


							</div>

						</div>
						</div>
					</div>
					<!-- /col-md-4 -->
				</div>
				<!-- /row -->
			</div>
		</div>
		<!-- /row -->
	</div>

  </div>
</div>

	<script src="../script/import_inventory.js"></script>

	<?php include "../partials/footer.php"; ?>