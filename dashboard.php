<?php include "partials/header.php";
include "partials/sidebar.php";

$today = date("Y-m-d");

$sql = "SELECT * FROM product";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

$totalSalesToday = 0;
$tsales = mysqli_query($con, "SELECT `paid_amount`,AVG(paid_amount) AS total FROM sold_products 
WHERE order_date='$today' AND paid_amount !=0 GROUP BY invoice_number
") or die(mysqli_error($con));

while ($tsalesData = mysqli_fetch_array($tsales)) {
  $totalSalesToday += $tsalesData["total"];
}
$totalSalesCount = mysqli_num_rows($tsales);

//total expenses
$expSql = "SELECT SUM(exp_amount) as total_expenses FROM expenditures";
$expQuery = $connect->query($expSql);
$totalExpensesCount = $expQuery->num_rows;

$expResult = $expQuery->fetch_assoc();
$totalExpenses =  $expResult['total_expenses'];


//total customers
$custSql = "SELECT * FROM customers";
$custQuery = $connect->query($custSql);
$totalCustomers = $custQuery->num_rows;


$productSql = "SELECT * FROM product";
$productQuery = $connect->query($productSql);
$totalProducts = $productQuery->num_rows;
?>

<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="row">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Welcome <?php echo ucwords($_SESSION["user"]); ?></h3>
            <h6 class="font-weight-normal mb-0">What do you wish to do today? </h6>
          </div>
          <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
              <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                </button>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 grid-margin transparent">
        <div class="row">

          <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-tale">
              <div class="card-body">
                <p class="mb-4">Today Sales</p>
                <p class="fs-30 mb-2"><?php echo '&#8358;' . number_format($totalSalesToday); ?></p>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
              <div class="card-body">
                <p class="mb-4">Total Expenses</p>
                <p class="fs-30 mb-2"><?php echo '&#8358;' . number_format($totalExpenses); ?></p>

              </div>
            </div>
          </div>
        </div>


      </div>
      <div class="col-md-6 grid-margin transparent">

        <div class="row">
          <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-light-blue">
              <div class="card-body">
                <p class="mb-4">Customers</p>
                <p class="fs-30 mb-2"><?php echo $totalCustomers; ?></p>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-light-danger">
              <div class="card-body">
                <p class="mb-4">Total Number of Products</p>
                <p class="fs-30 mb-2"><?php echo number_format($totalProducts); ?></p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <?php

    $year = date("Y");
    //get product purchase price using the order number

    ?>


    <script>
      window.onload = function() {

        var saleschart = new CanvasJS.Chart("totalSalesContainer", {
          animationEnabled: true,
          title: {
            text: "Total Sales Turnover"
          },
          axisY: {
            title: "Revenue (in <?php echo $currency; ?>)",
            includeZero: true,
            prefix: "<?php echo $currency; ?>",
            suffix: ""
          },
          data: [{
            type: "area",
            yValueFormatString: "<?php echo $currency; ?>#,##0",
            indexLabel: "{y}",
            indexLabelPlacement: "outisde",
            indexLabelFontWeight: "normal",
            indexLabelFontColor: "black",
            dataPoints: <?php echo json_encode(yearlySalesChart($con, $year), JSON_NUMERIC_CHECK); ?>
          }]
        });
        saleschart.render();

        var expenseschart = new CanvasJS.Chart("totalExpContainer", {
          theme: "light2",
          animationEnabled: true,
          title: {
            text: "Total Expenditure"
          },
          data: [{
            type: "doughnut",
            indexLabel: "{symbol} - {y}",
            yValueFormatString: "#,##0.0\"%\"",
            showInLegend: true,
            legendText: "{label} : {y}",
            dataPoints: <?php echo json_encode(yearlyExpensesChart($con, $year), JSON_NUMERIC_CHECK); ?>
          }]
        });
        expenseschart.render();

      }
    </script>

    <div class="row">
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title">Expenditure</p>
            <div id="totalExpContainer" style="height: 250px; width: 100%;"></div>

          </div>
        </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <p class="card-title">Sales Report</p>
              <a href="views/all_sales.php" class="text-info">View all</a>
            </div>
            <div id="totalSalesContainer" style="height: 250px; width: 100%;"></div>

          </div>
        </div>
      </div>
    </div>
  </div>



  <?php include "partials/footer.php"; ?>