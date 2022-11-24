


$(document).ready(function () {

    // update product quantity in cart
    $(".quantity").change(function () {

        if (this.value < 1) {
            alert("You cant sell a product at Zero quantity!!");
            this.value = 1;
        } else {

            $(".addToCartBtn").val(this.value);

        }
    });


});

function makePriceEditable(pcode) {
    $('.' + pcode).prop('disabled', false);
}

function removeCommasAndDots(str) {
    var regex = /[.,\s]/g;
    var result = str.replace(regex, '');
    return result;
}



function addToCart(product_qty, product_id) {

    $("#submitNow").prop('disabled', true);

    $.ajax({
        url: "../includes/manage_cart.php",
        type: "get",
        dataType: "json",
        data: { "product_qty": product_qty, "product_id": product_id }
    }).done(function (data) {

        //set focus for barcode input

        $("#barcode").val("");
        $("#barcode").focus();
        $("#submitNow").prop('disabled', false);
        toggleBtnState()


        if (data.products == "low_store_stock") {
            alert("Oops! The product you're trying to sell is finished. Please restock.");
        } else if (data.products == "low_store_qty") {
            alert("Oops! The Quantity you are trying to sell is higher than the Quantity remaining in your Store. Please Restock");

        } else if (data.products == "onhold") {
            $("#submitNow").prop('disabled', false);

            alert("Sorry, this Product is ONHOLD and cannot be sold at this time. Contact the System Administrator for more details.");
        } else if (data.products == "lowqty") {
            $("#submitNow").prop('disabled', false);

            alert("Oops! Quantity is low and as such, this product cannot be sold. Contact the System Administrator for more details.");
        } else if (data.products == "reachedmax") {
            $("#submitNow").prop('disabled', false);

            alert("Oops! You cannot sell More than " + data.message + " of this product. If you think this was an error, Contact the System Administrator for more details.");
        } else if (data.products == "highqty") {
            $("#submitNow").prop('disabled', false);
            alert("CAUTION! The Quantity you are trying to sell is higher than the Quantity remaining. If you think this was an error, Contact the System Administrator for more details.");
        } else {

            var cartData = data.data
            var measurementsData = data.measurements
            updateCartTable(cartData, measurementsData)

        }


    })
}


function update_quantity(quantity, pcode) {

    if (quantity == 0 || quantity == "") {
        alert("Oops! You cannot sell less than Zero(0) of this product.");
        $(".change_qty" + pcode).val(1);
        quantity = 1;
    }

    $.ajax({
        url: "../includes/manage_cart.php",
        type: "get",
        dataType: "json",
        data: { "update_quantity": pcode, "quantity": quantity }

    }).done(function (data) {

        if (data.products == "low_store_stock") {
            alert("Oops! The product you're trying to sell is finished. Please restock.");
        } else if (data.products == "low_store_qty") {
            loadCartAfterReload()
            alert("Oops! The Quantity you are trying to sell is higher than the Quantity remaining in your Store. Please Restock");
        } else if (data.products == "reachedmax") {
            loadCartAfterReload()
            alert("CAUTION! You cannot sell More than " + data.message + " of this product. If you think this was an error, Contact the System Administrator for more details.");
        } else if (data.products == "highqty") {
            updateCartErrorHandler(pcode)
            alert("CAUTION! The Quantity you are trying to sell is higher than the Quantity remaining. If you think this was an error, Contact the System Administrator for more details.");
        } else {

            var cartData = data.data
            var measurementsData = data.measurements
            updateCartTable(cartData, measurementsData, quantity)
            toggleBtnState()

        }

    });



}

function updateCartErrorHandler(pcode){
    $("#submitNow").prop('disabled', true);
    $(".change_qty" + pcode).val(1);
    loadCartAfterReload()
}


function removeFromCart(id) {

    $("#submitNow").prop('disabled', true);
    $.ajax({
        url: "../includes/manage_cart.php",
        type: "get",
        dataType: "json",
        data: { "remove_code": id }
    }).done(function (data) {

        var cartData = data.data
        var measurementsData = data.measurements
        updateCartTable(cartData, measurementsData)
    });

}

function updateCartTable(cartData, measurementsData, previousQtyBeforeUnitChange = 1) {

    //set focus for barcode input
    $("#barcode").val("");
    $("#barcode").focus();

    var tr = "";
    var table = $("#cart-items");

    //empty the table
    $("#cart-items > tr").remove();

    //create rows for each item in the cart
    var subTotalAmount = 0;
    var totalAmount = 0;
    var amountPaid = 0;
    var discount = 0;
    var getVat = $("#getVat").val();
    var calcVat = 0;


    for (let i = 0; i < cartData.length; i++) {

        const items = cartData[i];
        let pname = items.pname;
        let pprice = items.pprice;
        let dpqty = items.qtyChanged;
        let pqty = items.pqty
        let pnumber = items.pnumber;
        // let pdiscount = parseInt(items.pdiscount);
        let productHasVariations = items.hasVariations;
        let mUnitChangedStatus = items.mUnitChanged;
        let subtotal, subtotalPayable = 0;
        let plevelDiscount = parseInt(items.plevelDiscount);


        if (mUnitChangedStatus == 1) {

            //a change in unit occured, so remove the multiplication of subtotal
            subtotal = parseInt(pprice) * parseInt(dpqty);
            subtotalPayable = parseInt(pprice) * parseInt(dpqty);

        } else {
            subtotal = parseInt(pprice) * parseInt(pqty);
            subtotalPayable = parseInt(pprice) * parseInt(pqty);
            dpqty = items.pqty
        }

        //calculate discount for each product if discount exist
        // if (pdiscount > 0) {
        //     subtotalPayable = parseInt(subtotalPayable - plevelDiscount);
        //     subtotal = subtotalPayable
        // }

        if (plevelDiscount > 0) {
            subtotalPayable = parseInt(subtotalPayable - plevelDiscount);
        }

        discount += plevelDiscount
        subTotalAmount += subtotal;
        totalAmount = subTotalAmount - discount
        amountPaid = subTotalAmount - discount
        calcVat = getVat / 100 * totalAmount;

        //new total + vat
        totalAmount = totalAmount + calcVat


        tr = "<tr>"
            + "<input type='hidden' value='" + pprice.toFixed(2) + "' id='old_price" + pnumber + "'>"
            + "<td>" + pname + "</td>"
            + "<td>" + pprice + "</td>"
            + "<td>"
            + "<input type='number' onchange='update_quantity(this.value,this.id)' id='" + pnumber + "' class='form-control text-center change_qty" + pnumber + "' style='width: 70px;' value='" + dpqty + "'>"
            + "</td>"

            + "<td>" + pqty + "</td>"
            + "<td>" + subtotalPayable + "</td>";

        if (productHasVariations == 0) {

            tr += "<td></td>";

        } else {

            tr += "<td>"
                + "<select name='item_unit' id='" + pnumber + "' onchange='getProductMeasurementData(this.id,this.value)' class='form-control form-control-sm m-1'>"
                + "<option selected></option>";
            for (let j = 0; j < measurementsData.length; j++) {
                const item = measurementsData[j];


                if (pnumber == item.product_id) {
                    tr += "<option value='" + item.measurement_id + "'>" + item.measurement_unit + "</option>";
                }
            }
            tr += "</select>"
                + "</td>";

        }

        tr += "<td>"
            + "<button style='height: 25px;width: 30px;padding:2px' type='button' class='btn customize-abs-btn remove-item' onclick='removeFromCart(this.value)' value='" + pnumber + "'>"
            + "<i class='mdi mdi-trash-can' style='font-size:12px'></i>"
            + "</button>"
            + "</td></tr>";

        table.append(tr);

        //store the data in local DOM session storage
        // storeCartData(pname,pqty,pnumber,pprice)

    }


    //set total Amount
    $("#currency_holder_subtotal").text(numberWithCommas(subTotalAmount.toFixed(2)))
    $("#calcTotalPayable").text(numberWithCommas(totalAmount.toFixed(2)))
    $("#calcTotalPaid").text(numberWithCommas(amountPaid.toFixed(2)))
    $("#discountTotal").text(numberWithCommas(discount.toFixed(2)))
    $("#cash_payment").val(totalAmount.toFixed(2))
    $("#calcVat").text(numberWithCommas(calcVat.toFixed(2)) + " -> " + getVat + "%")

    //set values of hidden input fields
    $("#sub_total_payable").val(subTotalAmount)
    $("#total_discount").val(discount)
    $("#totalsale_discount").text(amountPaid)
    $("#getTotal").val(totalAmount.toFixed(2));
    $("#vat_amount").val(calcVat.toFixed(2));

}

function getProductMeasurementData(productID, measurementID) {

    $.ajax({
        cache: false,
        url: "../includes/manage_cart.php",
        type: "GET",
        dataType: "json",
        data: { "measurementID": measurementID, "productID": productID }
    }).done(function (data) {

        var cartData = data.data
        var measurementsData = data.measurements

        updateCartTable(cartData, measurementsData)

    })
}


function addToCartFromBarcode(product_qty, product_id) {

    $("#submitNow").prop('disabled', true);

    $.ajax({
        cache: false,
        url: "manage_barcode_cart.php",
        type: "GET",
        dataType: "json",
        data: { "product_qty": product_qty, "product_id": product_id }

    }).done(function (data) {

        //set focus for barcode input
        $("#barcode").val("");
        $("#barcode").focus();

        $("#submitNow").prop('disabled', false);


        if (data.products == "low_store_stock") {
            alert("Oops! The product you're trying to sell is finished. Please restock.");
        } else if (data.products == "low_store_qty") {
            alert("Oops! The Quantity you are trying to sell is higher than the Quantity remaining in your Store. Please Restock");
        } else if (data.products == "onhold") {
            $("#submitNow").prop('disabled', false);

            alert("Sorry, this Product is ONHOLD and cannot be sold at this time. Contact the System Administrator for more details.");
        } else if (data.products == "lowqty") {
            $("#submitNow").prop('disabled', false);

            alert("Oops! Quantity is low and as such, this product cannot be sold. Contact the System Administrator for more details.");
        } else if (data.products == "reachedmax") {
            $("#submitNow").prop('disabled', false);

            alert("Oops! You cannot sell More than " + data.message + " of this product. If you think this was an error, Contact the System Administrator for more details.");
        } else if (data.products == "highqty") {
            $("#submitNow").prop('disabled', false);

            alert("CAUTION! The Quantity you are trying to sell is higher than the Quantity remaining. If you think this was an error, Contact the System Administrator for more details.");
        } else {

            var cartData = data.data
            var measurementsData = data.measurements
            updateCartTable(cartData, measurementsData)

        }


    })
}



function selectPayMethod() {

    let subTotal = document.getElementById("sub_total_payable").value;
    let discountTotal = document.getElementById("total_discount").value;
    let totalPayable = subTotal - discountTotal;
    //auto fill remaining amount for either cash or bank payments
    var fetch_cashAmt = document.getElementById("cash_payment").value;

    if (this.value == "Bank/internet transfer") {
        var computeBankAmt = totalPayable - fetch_cashAmt;
        $("#bank_payment").val(computeBankAmt);
        var sum = Number(computeBankAmt) + Number(fetch_cashAmt);
        $("#balance_amount").val(0);
        $("#calcTotalBalance").text("0.00");
        $("#calcTotalPaid").text(numberWithCommas(sum) + ".00");
        $("#submitNow").css("display", "inline-block");
    }

}


function setCashAmount() {

    var getTotalPayable = document.getElementById("calcTotalPayable").textContent;
    getTotalPayable = Number(getTotalPayable.replace(",", ""));

    var cashAmount = $("#cash_payment").val();
    cashAmount = Number(cashAmount);

    if (cashAmount != 0 && cashAmount != "") {
        var ca = document.getElementById("cash_payment").value;
        var ba = document.getElementById("bank_payment").value;

        //add amount
        var paid = Number(ba) + Number(ca);
        var newBalance = 0;
        if (paid < getTotalPayable) {

            //customer owing
            newBalance = getTotalPayable - paid;
            document.getElementById("change_or_balance").innerText = "Balance";

        } else {
            //system owing
            document.getElementById("change_or_balance").innerText = "Change";
            newBalance = paid - getTotalPayable;
        }



        if (paid != "" && paid > 0) {

            $("#balance_amount").val(newBalance.toFixed(2));
            $("#calcTotalBalance").text(newBalance.toFixed(2));
            $("#calcTotalPaid").text(numberWithCommas(paid.toFixed(2)));

        }

    } else if (cashAmount == 0) {
        $("#balance_amount").val(getTotalPayable);
        $("#calcTotalBalance").text(numberWithCommas(getTotalPayable));
        $("#calcTotalPaid").text("0.00");
    }

}

function setBankAmount() {
    var getTotalPayable = document.getElementById("calcTotalPayable").textContent;
    getTotalPayable = Number(getTotalPayable.replace(",", ""));

    var bankAmount = $("#bank_payment").val();
    bankAmount = Number(bankAmount);

    if (bankAmount != 0 && bankAmount != "") {
        var ca = document.getElementById("cash_payment").value;
        var ba = document.getElementById("bank_payment").value;

        //add amount
        var paid = Number(ba) + Number(ca);
        var newBalance = 0;
        if (paid < getTotalPayable) {

            //customer owing
            newBalance = getTotalPayable - paid;
            document.getElementById("change_or_balance").innerText = "Balance";

        } else {
            //system owing
            document.getElementById("change_or_balance").innerText = "Change";
            newBalance = paid - getTotalPayable;
        }

        if (paid != "" && paid > 0) {

            $("#balance_amount").val(newBalance.toFixed(2));
            $("#calcTotalBalance").text(newBalance.toFixed(2));
            $("#calcTotalPaid").text(numberWithCommas(paid.toFixed(2)));

        }

    } else if (bankAmount == 0) {
        $("#balance_amount").val(getTotalPayable);
        $("#calcTotalBalance").text(numberWithCommas(getTotalPayable));
        $("#calcTotalPaid").text("0.00");
    }
}


function togglePayAmount() {
    selectPayMethod();
    var selected = document.getElementById("paymethod").value;
    if (selected == "Cash") {
        document.getElementById("cash_payment_holder").style.display = "inline-block";
    } else if (selected == "Bank/internet transfer") {
        document.getElementById("bank_payment_holder").style.display = "inline-block";
    }
}


function toggleBtnState() {

    //disable all buttons if cart is empty
    $("#submitNow").prop('disabled', false);
    $("#holdInvoiceBtn").prop('disabled', false);
    $("#chargeToAccount").prop('disabled', false);

}
function submitOrder() {

    //stop submitting the form to see the disabled button effect
    var getCustName = $("#customer_name").val();
    var getBalance = $("#calcTotalBalance").text();
    var tableRowCount = $('#cart_table tr').length;
    var gettotalToPay = $("#getTotal").val();
    var getCustomerID = $("#customer_id").val();
    var canSubmit = false;

    //check if itemms are in cart
    if (tableRowCount <= 1) {

        alert("Kindly add items to Sales Cart. ");
        return false;

    } else {

        if (gettotalToPay == "") {
            alert("System Error. Total amount to pay cannot be 0. FIX: Refresh this page. ");
            return false;
        } else {


            gettotalToPay = gettotalToPay.replace(",", "");

            //check if it is change or balance
            var calcChange = Number(Math.round(gettotalToPay)) + Number(Math.round(getBalance));
            var ca = document.getElementById("cash_payment").value;
            var ba = document.getElementById("bank_payment").value;
            var amountGiven = Number(ba) + Number(ca);

            // a change transaction if amount inputted is equal to change calculated

            if (Number(Math.round(getBalance)) == 0) {
                canSubmit = true;
            } else {

                if (calcChange != amountGiven) {

                    if (getCustName == "") {
                        alert("Customer Details is Required.");
                        return false;
                    } else {

                        if (getCustomerID != "") {

                            var custBalance = $("#customer_balance").val();
                            var getBalance = $("#calcTotalBalance").text();

                            getBalance = getBalance.replace(",", "");
                            getBalance = getBalance.replace(".00", "");

                            if (Number(getBalance) > Number(custBalance)) {
                                alert("Sorry! Credit Limit for this Customer has been exceeded. Remove some items or Increase the Credit Limit of the Customer.");
                                return false;

                            } else {
                                canSubmit = true;
                            }

                        }
                    }
                }
            }
        }
    }

    if (canSubmit) {
        // $("#submitNow").attr("enabled");

        var cash_payment = $("#cash_payment").val();
        var bank_payment = $("#bank_payment").val();

        var paidAmount = parseInt(cash_payment) + parseInt(bank_payment)

        var formData = {
            cash_payment: cash_payment,
            bank_payment: bank_payment,
            paymethod: $("#paymethod").val(),
            customer_phone: $("#customer_phone").val(),
            customer_name: $("#customer_name").val(),
            customer_address: $("#customer_address").val(),
            vat_amount: $("#vat_amount").val(),
            totalsale_discount: $("#totalsale_discount").val(),
            balance_amount: $("#balance_amount").val(),
            total_payable: $("#total_payable").val(),
            total_discount: $("#total_discount").val(),
            paid_amount: paidAmount,
            sub_total_payable: $("#sub_total_payable").val(),
            getTotal: $("#getTotal").val(),
            customer_id: $("#customer_id").val(),
        };

        //submit sales via ajax
        $.ajax({
            cache: false,
            url: "../includes/process_order.php",
            type: "POST",
            dataType: "json",
            encode: true,
            data: formData

        }).done(function (data) {

            var printLoc = "sale_invoice?invoiceno=" + data.invoice + "&status=completed";
            if (data.msg == "done") {

                //open print dialog
                loadCartAfterReload();
                printInvoice(printLoc);

                //clear fields
                $("#calculations-holder").load(location.href + " #calculations-holder>*");
                $("#products-holder").load(location.href + " #products-holder>*");

            } else if (data.msg == "error") {
                alert("Sorry, we could not process the Sales. Trt again. If error persist, contact the System Admin.")
            }

        });

        setTimeout(function () {
            loadDataTable();
        }, 1000);

    }
    event.preventDefault();
    return false;
}

function loadDataTable() {
    $('#list_all_products').DataTable({
        dom: 'lBfrtip',
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        stateSave: true
    });

}


function printInvoice(loc) {
    window.open(loc, 'targetWindow',
        `toolbar=no,
        location=no,
        status=no,
        directories=no,
        menubar=no,
        scrollbars=yes,
        resizable=no,
        width=400,
        height=400`);
    return false;
}


function update_price(new_price, pcode) {


    var getOldPrice = $("#old_price" + pcode).val();
    getOldPrice = getOldPrice.split(".")[0];
    getOldPrice = parseFloat(removeCommasAndDots(getOldPrice));

    new_price = parseFloat(removeCommasAndDots(new_price));

    if (new_price <= 0) {

        alert("Be Warned! You are trying to manipulate the System. The Price field cannot be EMPTY. ");

    } else if (new_price < getOldPrice) {
        alert("Be Warned! You are trying to manipulate the System. The Price is lesser than the Original Price. ");
        $(".toggle" + pcode).val(numberWithCommas(getOldPrice.toFixed(2)));
        $(".toggle" + pcode).prop('disabled', true);

    } else {

        $.getJSON("../includes/manage_cart.php", { "update_price": pcode, "price": new_price },
            function (data) {

                //set focus for barcode input
                $("#barcode").val("");
                $("#barcode").focus();

                if (data.message == "done") {

                    setTimeout(function () {

                        $("#calcTotalPayable").html("<span style='font-weight:bold'>0.00</span>");

                        $('.change_price').prop('disabled', true);
                        $('#cart-holder').fadeIn(500);
                        $("#cart-holder").load(location.href + " #cart-holder>*");

                    }, 500)

                } else {
                    alert("An error occured. Please try again");
                }

            });

    }

}

function loadCartAfterReload() {

    $.ajax({
        cache: false,
        url: "../includes/manage_cart.php",
        type: "GET",
        dataType: "json",
        data: { "page_ready": "yes" }
    }).done(function (data) {

        var cartData = data.data
        var measurementsData = data.measurements
        updateCartTable(cartData, measurementsData)

    })
}



function clearDebt(amt, custid) {

    var getCustName = $("#clearDebt_custname").val();
    $("#custtopay").val(amt);
    $("#custname").val(getCustName);
    $("#custamt").val(amt);
    $("#custid").val(custid);

}

function clearCustomerDebt() {
    var getDebtorId = $("#custid").val();
    var getAmtToPay = $("#custtopay").val();
    var getAmt = $("#custamt").val();
    var debt_paymethod = $("#debt_paymethod").val();
    $("#payDebtBtn").text("Processing..");

    alert()


    if (Number(getAmtToPay) == 0) {
        alert("Error! Enter a valid Amount");
        $("#payDebtBtn").text("Pay Now");
        $("#custtopay").focus();

        return false;
    } else if (Number(getAmtToPay) > Number(getAmt)) {
        alert("Error! Amount to pay is higher than Original Debt");
        $("#payDebtBtn").text("Pay Now");
        $("#custtopay").focus();

        return false;
    } else {

        //do ajax request
        $.ajax({
            cache: false,
            url: "../includes/pay_customer_debt.php",
            type: "GET",
            dataType: "json",
            data: {
                "customer_id": getDebtorId,
                "paying": getAmtToPay,
                "debt_paymethod": debt_paymethod
            }
        }).done(function (res) {

            if (res.cust_id != "") {
                //theres a response
                alert("Payment Processed Succesfully.")
                $("#credit_limit").html("<h5><b>Credit Limit:</b> <?php echo $currency; ?>" + numberWithCommas(res.cust_credit_limit) + "</h5>");
                //show clear debt button if customer owes
                var clearDebtModal = "";
                if (res.cust_owing > 0) {
                    var clearDebtModal = "<button type='button' onclick='clearDebt(this.value,this.id)' value='" + res.cust_owing + "' id='" + res.cust_id + "' class='btn btn-sm btn-primary pl-2' data-target='#clearDebtModal' data-toggle='modal'>Clear Debt</button>" +
                        "<input type='hidden' id='clearDebt_custname' value='" + res.cust_name + "' />";
                }
                $("#credit_owing").html("<h5><b>Current Debt:</b>  <?php echo $currency; ?>" + numberWithCommas(res.cust_owing) + "</h5>" + clearDebtModal);

                var climit = Number(res.cust_credit_limit);
                var debt = res.cust_owing; //string
                var current_debt = debt.replace("-", "");
                var bal = climit - Number(current_debt);
                $("#customer_balance").val(bal);
                $("#credit_balance").html("<h5><b>Balance:</b> <?php echo $currency; ?>" + numberWithCommas(bal) + "</h5>")
                $("#clearDebtModal .close").click()


            } else {
                alert(res.toUpperCase())
            }

            $("#payDebtBtn").text("Pay Now");

        })

    }

    return false;
}

function chargeToCustomerAccount() {

    var customer_id = $("#customer_id").val();
    var bankAmt = $("#bank_payment").val();
    var cashAmt = $("#cash_payment").val();
    var getTotalAmt = $("#getTotal").val();
    var tableRowCount = $('#cart_table tr').length;
    var nowPaid = Number(cashAmt) + Number(bankAmt);
    var amtToCharge = Number(getTotalAmt) - Number(nowPaid);

    var debt = $("#customer_current_debt").val()

    $("#chargeToAccount").text("Please Wait...");

    if (tableRowCount <= 1) {

        alert("Kindly add items to Sales Cart. ");
        $("#chargeToAccount").text("Charge To Account");

    } else {

        getTotalAmt = Number(getTotalAmt);

        //check if customer name is given

        if (customer_id != "") {

            var custBalance = $("#customer_balance").val();
            var getBalance = $("#calcTotalBalance").text();

            getBalance = getBalance.replace(",", "");
            getBalance = getBalance.replace(".00", "");

            if (Number(getBalance) > Number(custBalance)) {
                alert("Sorry! Credit Limit for this Customer has been exceeded. Remove some items or Increase the Credit Limit of the Customer.");

                $("#chargeToAccount").text("Charge To Account");

                return false;

            } else {

                if (nowPaid == getTotalAmt) {
                    //charge full amount using ajax

                    if (window.confirm('Are you sure you want to charge ' + getTotalAmt.toString() + ' to the customer account?') == true) {

                        $.ajax({
                            cache: false,
                            url: "../includes/charge_to_account.php",
                            type: "GET",
                            dataType: "json",
                            data: { "customer_id": customer_id, "getTotalAmt": getTotalAmt }
                        }).done(function (data) {

                            alert(data)
                            $("#chargeToAccount").prop("disabled", true);

                            //check if we are owing customer
                            //check if theres minus in cust_owing object
                            if (debt.includes('-')) {

                                //we owe customer
                                //set balance to zero
                                document.getElementById("change_or_balance").innerText = "Balance";
                                $("#balance_amount").val("0");
                                $("#calcTotalBalance").val("0");

                            } else {

                                $("#calcTotalBalance").text(numberWithCommas(getTotalAmt.toFixed(2)));
                                $("#balance_amount").val(getTotalAmt);

                            }
                            document.getElementById("change_or_balance").innerText = "Balance";
                            $("#calcTotalPaid").text("0.00");
                            $("#bank_payment").val("0");
                            $("#cash_payment").val("0");
                            $("#chargeToAccount").text("Charge To Account");

                        })

                    } else {
                        $("#chargeToAccount").text("Charge To Account");

                        return false;
                    }

                } else {

                    if (window.confirm('Are you sure you want to charge ' + amtToCharge.toString() + ' to the customer account?') == true) {


                        var getBal = $("#balance_amount").val();
                        var tranx_type = "";
                        var debtBool = debt.includes('-');

                        if (nowPaid > getTotalAmt && debtBool === true) {
                            //customer paid more than we are owing him or her
                            //meaning we will give him/her a change
                            //now he/she wants to charge the chnge to account
                            //check if we are owing the customer i.e there is a minus sign 

                            //if old debt is -4000 and new is 5000
                            //the line above evaluates to -4000 - 5000 = -9000
                            getBal = (-getBal);
                            tranx_type = "debit"

                        } else if (nowPaid > getTotalAmt && debtBool === false) {
                            //we are not owing. rather, customer is owing or amt is zero
                            getBal = (-getBal);
                            tranx_type = "credit"
                        } else {
                            getBal = (getBal);
                            // tranx_type = "debit"
                        }


                        $.ajax({
                            cache: false,
                            url: "../includes/charge_to_account.php",
                            type: "GET",
                            dataType: "json",
                            data: { "customer_id": customer_id, "getTotalAmt": getBal }
                        }).done(function (data) {

                            alert(data)
                            $("#chargeToAccount").prop("disabled", true);

                            $("#calcTotalPaid").text(numberWithCommas(nowPaid.toFixed(2)));

                            if (tranx_type == "credit") {
                                document.getElementById("change_or_balance").innerText = "Change";

                            } else {
                                document.getElementById("change_or_balance").innerText = "Balance";

                            }
                            $("#chargeToAccount").text("Charge To Account");
                            $("#submitNow").prop("disabled", false);
                            $("#submitNow").text("Submit");

                        })
                    } else {
                        $("#chargeToAccount").text("Charge To Account");

                        return false;
                    }

                }
            }




        } else {
            $("#customer_name").focus();
            alert("Customer Name is required");
            $("#chargeToAccount").text("Charge To Account");
        }

        $("#chargeToAccount").text("Charge To Account");





    }




}


