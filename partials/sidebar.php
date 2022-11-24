      <?php
      if ($_SESSION["role"] == "owner" || $_SESSION["role"] == "manager") {


      ?>

        <nav class="sidebar sidebar-offcanvas shadow-sm hidden-print" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $pageUrl; ?>dashboard">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#inventory" aria-expanded="false" aria-controls="inventory">
                <i class="icon-folder menu-icon"></i>
                <span class="menu-title">Inventory</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="inventory">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/place_order">Purchase Order</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/products">All Products</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/product_measurement">Product Units</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/barcodes">Barcodes</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/list_placed_order">Placed Orders</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/receive_arrivals">Receive Orders</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/list_received_orders">Direct Order</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/import_inventory">Import Inventory</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/low_stock">Low Stock</a></li>

                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#report" aria-expanded="false" aria-controls="report">
                <i class="icon-pie-graph menu-icon"></i>
                <span class="menu-title">Reports</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="report">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/endofday_report">End of Day</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/report">Generate Report</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/credit_history">Credit Payments</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/credit_limit_changes">Credit Limit Log</a></li>

                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#sales" aria-expanded="false" aria-controls="sales">
                <i class="icon-bar-graph menu-icon"></i>
                <span class="menu-title">Sales</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="sales">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/make_sales">Make a Sale</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/today_sales">Today's Sales</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/all_sales">All Sales</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/top_selling_products">Top Selling Products</a></li>

                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#invoice" aria-expanded="false" aria-controls="invoice">
                <i class="icon-printer menu-icon"></i>
                <span class="menu-title">Invoice</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="invoice">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="<?php echo $pageUrl; ?>views/held_receipts">Held Receipts</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo $pageUrl; ?>views/receipt_reversal">Reversal</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo $pageUrl; ?>views/return_items">Returns</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo $pageUrl; ?>views/exchange_items">Exchange</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo $pageUrl; ?>views/view_reversed_receipts">Reversed Receipts</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo $pageUrl; ?>views/view_returned_receipts">Returned Receipts</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo $pageUrl; ?>views/view_exchange_receipts">Exchange Receipts</a></li>

                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#multistore" aria-expanded="false" aria-controls="multistore">
                <i class="icon-arrow-up menu-icon"></i>
                <span class="menu-title">Multistore</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="multistore">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>multistore/add_store">Create Store</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>multistore/multistore">Transfer to Store</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>multistore/multistore_report">Report</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>multistore/multistore_today_transfers">Today Transfers</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>multistore/multistore_all_transfers">All Transfers</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>multistore/multistore_low_stock">Low Stock </a></li>

                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="<?php echo $pageUrl; ?>views/expenditure">
                <i class="icon-tag menu-icon"></i>
                <span class="menu-title">Expenses</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="<?php echo $pageUrl; ?>views/customers">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Customers</span>
              </a>
            </li>


            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#employees" aria-expanded="false" aria-controls="employees">
                <i class="icon-layers menu-icon"></i>
                <span class="menu-title">My Employees</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="employees">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>ems/ems_employees">Employees</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>ems/ems_payouts">Payouts</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>ems/ems_report">Reports</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>ems/ems_best_employee">Best Employee Report</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>ems/ems_time_attendance">Time Attendance</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>ems/ems_settings">Settings</a></li>

                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#more" aria-expanded="false" aria-controls="more">
                <i class="icon-expand menu-icon"></i>
                <span class="menu-title">More</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="more">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/invoice_design">Invoice Design</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/settings">App Settings</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/birthday_reminders">Birthday Reminder</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/product_expiry_reminders">Expiry Reminder</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/best_customer">Best Customer(s)</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/backup">System Backup</a></li>
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="<?php echo $pageUrl; ?>logout">
                <i class="icon-power menu-icon"></i>
                <span class="menu-title">Logout</span>
              </a>
            </li>
          </ul>
        </nav>

      <?php } else if ($_SESSION["role"] == "associate") {
      ?>


<nav class="sidebar sidebar-offcanvas shadow-sm hidden-print" id="sidebar">
          <ul class="nav">
           

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#inventory" aria-expanded="false" aria-controls="inventory">
                <i class="icon-folder menu-icon"></i>
                <span class="menu-title">Inventory</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="inventory">
                <ul class="nav flex-column sub-menu">
                 
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/low_stock">Low Stock</a></li>

                </ul>
              </div>
            </li>


            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#sales" aria-expanded="false" aria-controls="sales">
                <i class="icon-bar-graph menu-icon"></i>
                <span class="menu-title">Sales</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="sales">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/make_sales">Make a Sale</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/today_sales">Today's Sales</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $pageUrl; ?>views/all_sales">All Sales</a></li>
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#invoice" aria-expanded="false" aria-controls="invoice">
                <i class="icon-printer menu-icon"></i>
                <span class="menu-title">Invoice</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="invoice">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="<?php echo $pageUrl; ?>views/held_receipts">Held Receipts</a></li>
                 
                </ul>
              </div>
            </li>

          
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $pageUrl; ?>views/expenditure">
                <i class="icon-tag menu-icon"></i>
                <span class="menu-title">Expenses</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="<?php echo $pageUrl; ?>views/customers">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Customers</span>
              </a>
            </li>


            <li class="nav-item">
              <a class="nav-link" href="<?php echo $pageUrl; ?>logout">
                <i class="icon-power menu-icon"></i>
                <span class="menu-title">Logout</span>
              </a>
            </li>
          </ul>
        </nav>

        <?php } ?>