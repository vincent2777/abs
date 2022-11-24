<div id="print-holder1" style="margin-left:10px;margin-top: 20px">

<center>
    <img src="<?php echo $bimage; ?>" style="width: 70px;height: 70px;margin-left:5px">
    <br>
    <p>
        <span style="font-size: 18px;font-family: Algerian; line-height: 15px !important"><strong><b><?php echo ucwords($bname); ?></b></strong></span>
        <br>
        <span style="font-size: 14px;font-family: Verdana"><strong><b><?php echo $bslogan; ?></b></strong></span>
    </p>

</center>
</div>
<div id="print-holder2" style="margin-left:-35px;">

<p style="font-size: 12px;text-align:left">
    <b>Issued By: </b> <?php echo ucwords($user_id); ?>
    <br>
    <b>Invoice No:</b> <?php echo $order_number; ?>
    <br>
    <b>Date:</b> <?php echo $d =  $today . " " . $order_time; ?>
    <?php

    if (!empty($customer_name) || !empty($customer_phone)) {
        echo "<br><b>Customer:</b> <span>$customer_name</span><br>";
        echo "<b>Mobile:</b> <span>$customer_phone</span><br>";
    }

    ?>
</p>
</div>

<hr style="border: 1px solid rgb(0,0,0,0.6)">

<table class="table" id="invoice-table">
				<thead>
					<tr>
						<th class="desc" style="width: auto !important;text-align:initial">Desc.</th>
						<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th>Qty.</th>
						<th>Price</th>
						<th>Amt.</th>
					</tr>
				</thead>
				<tbody>

                <tr style="width: 100% !important;line-height:5px !important;padding:0px !important">
							<td colspan="3" style="width: auto !important;text-align:left"><?php echo $product_name; ?></td>
							<td class="qty-data"><?php echo $product_qty; ?></td>
							<td class="amt-data"><?php echo number_format($_POST['total_payable'][$index] / $product_qty, 2); ?></td>
							<td class="amt-data"><?php echo number_format($_POST['total_payable'][$index], 2); ?></td>
						</tr>

                        </tbody>
			</table>

            <hr style="border: 1px solid rgb(0,0,0,0.6)">

			<table style="text-align: left;line-height:35px !important;margin-top:-25px;font-family:'Lucida Sans'">
				<tr>
					<td>Total: <?php echo $currency . number_format($total_to_pay, 2);  ?></td>
				</tr>
				<tr>
					<td> Paid: 
						<?php 

					if ($total_to_pay > $paid_amount) {

						//balance
						echo $currency . number_format($paid_amount, 2);   

					}else{

						//change
						$paid = $total_to_pay + $balance_amount; echo $currency . number_format($paid, 2);   
					}
					?></td>
				</tr>

				<tr>
					<td> <?php
							if (isset($_SESSION["returns"])) {
								echo "Change: " . $currency . number_format($return_amt_balance, 2);
							} else {
								if ($transaction_type == "debit") {
									echo "Change.: " . $currency . number_format($balance_amount, 2);
								} else {
									echo "Bal.: " . $currency . number_format($balance_amount, 2);
								}
							}
							?>
				</tr>
				<tr>
					<td> Discount: <?php echo $currency . number_format($totalsale_discount, 2);  ?></td>
				</tr>

				<tr>
					<td> VAT: <?php echo $sold_at_vat;  ?></td>
				</tr>
				<tr>
					<td>
						<?php if ($paymethod == "Cash") {
							echo "Cash: " . $currency . number_format($cash_payment_amt, 2);
						} else if ($paymethod == "Bank/Internet Transfer") {
							echo "Transfer: " . $currency . number_format($bank_payment_amt, 2);
						} else if ($paymethod == "Cash, Bank/Internet Transfer" || $paymethod == "Bank/Internet Transfer, Cash") {
							echo "Cash: " . $currency . number_format($cash_payment_amt, 2);
							echo "<br> Transfer: " . $currency . number_format($bank_payment_amt, 2);
						}

						?>
					</td>
				</tr>

			</table>

			<hr style="color:black;background-color:black">
			<br>
			<br>
			<center>
				<p style="font-size: 11px;text-align: center;margin-top:-50px;">
					<?php echo $binfo; ?>
					<br>
					Customer Care: <?php echo $bphone; ?>
					<br>
					<?php echo $baddress; ?>
					<br>
					<?php echo $bwebsite; ?>

				</p>
