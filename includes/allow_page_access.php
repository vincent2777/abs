<?php
session_start();
if (!$_SESSION["cansell"] == 1 && $curPageName == "make_sales.php") {
    header("location: noaccess");
  }
  
  if (!$_SESSION["candoexpenses"] == 1 && $curPageName == "expenditure.php") {
    header("location: noaccess");
  }
  
  if (!$_SESSION["canview_daily_sales"] == 1 && $curPageName == "today_sales.php") {
    header("location: noaccess");
  }
  
  if (!$_SESSION["canview_total_sales"] == 1 && $curPageName == "all_sales.php") {
      header("location: noaccess");
    }
  
  
    if (!$_SESSION["change_app_settings"] == 1 && $curPageName == "settings.php") {
      header("location: noaccess");
    }
  
    if (!$_SESSION["view_customers"] == 1 && $curPageName == "customers.php") {
      header("location: noaccess");
    }
  
    if (!$_SESSION["import_inventory"] == 1 && $curPageName == "import_inventory.php") {
      header("location: noaccess");
    }
  
  
   //inventory
   if (!$_SESSION["inventory"] == 1 && $curPageName == "place_order.php") {
    header("location: noaccess");
  }
  
  if (!$_SESSION["inventory"] == 1 && $curPageName == "list_placed_order.php") {
    header("location: noaccess");
  }
  
  if (!$_SESSION["inventory"] == 1 && $curPageName == "receive_arrivals.php") {
    header("location: noaccess");
  }
  
  if (!$_SESSION["inventory"] == 1 && $curPageName == "list_received_orders.php") {
    header("location: noaccess");
  }
  //inventory end
  
  //report
    if (!$_SESSION["printreport"] == 1 && $curPageName == "endofday_report.php") {
      header("location: noaccess");
    }
  
    if (!$_SESSION["printreport"] == 1 && $curPageName == "report.php") {
      header("location: noaccess");
    }
  
  //report end
  
    if (!$_SESSION["qty_log"] == 1 && $curPageName == "quantity_change_log.php") {
      header("location: noaccess");
    }
  
  
    //returns and reversal
  
    if (!$_SESSION["initiate_returns"] == 1 && $curPageName == "view_returns.php") {
      header("location: noaccess");
    }
  
    if (!$_SESSION["initiate_returns"] == 1 && $curPageName == "return_items.php") {
      header("location: noaccess");
    }
  
    if (!$_SESSION["initiate_returns"] == 1 && $curPageName == "receipt_reversal.php") {
      header("location: noaccess");
    }
  
    if (!$_SESSION["canaccess_heldreceipts"] == 1 && $curPageName == "view_reversed_receipts.php") {
      header("location: noaccess");
    }
  
    if (!$_SESSION["canaccess_heldreceipts"] == 1 && $curPageName == "held_receipts.php") {
      header("location: noaccess");
    }
  
    //returns and reversal end
  
    if (!$_SESSION["create_users"] == 1 && $curPageName == "create_users.php") {
      header("location: noaccess");
    }
  
  
    if (!$_SESSION["view_product"] == 1 && $curPageName == "products.php") {
      header("location: noaccess");
    }
  

    ?>