<?php    //check and set priviledges
				foreach ($priviledges as $key => $value) {

					if($key == "cansell" && $value == 1){

						$_SESSION["cansell"] = 1;
					}

					if($key == "editprice" && $value == 1){

						$_SESSION["editprice"] = 1;
					}

					if($key == "inventory" && $value == 1){

						$_SESSION["inventory"] = 1;
					}

					if($key == "printreport" && $value == 1){

						$_SESSION["printreport"] = 1;
					}

					if($key == "initiate_returns" && $value == 1){

						$_SESSION["initiate_returns"] = 1;
					}

					if($key == "qty_log" && $value == 1){

						$_SESSION["qty_log"] = 1;
					}

					if($key == "view_product" && $value == 1){

						$_SESSION["view_product"] = 1;
					}

					if($key == "create_users" && $value == 1){

						$_SESSION["create_users"] = 1;
					}

                    if($key == "canview_daily_sales" && $value == 1){

						$_SESSION["canview_daily_sales"] = 1;
					}

					if($key == "canview_total_sales" && $value == 1){

						$_SESSION["canview_total_sales"] = 1;
					}

                    if($key == "candoexpenses" && $value == 1){

						$_SESSION["candoexpenses"] = 1;
					}

                    if($key == "canaccess_heldreceipts" && $value == 1){

						$_SESSION["canaccess_heldreceipts"] = 1;
					}

					if($key == "cangive_discount" && $value == 1){

						$_SESSION["cangive_discount"] = 1;
					}

					if($key == "change_app_settings" && $value == 1){

						$_SESSION["change_app_settings"] = 1;
					}

					if($key == "view_customers" && $value == 1){

						$_SESSION["view_customers"] = 1;
					}

					if($key == "import_inventory" && $value == 1){

						$_SESSION["import_inventory"] = 1;
					}

					if($key == "canedit_qty" && $value == 1){

						$_SESSION["canedit_qty"] = 1;
					}

					if($key == "canreceive_direct_orders" && $value == 1){

						$_SESSION["canreceive_direct_orders"] = 1;
					}

                }

                    ?>