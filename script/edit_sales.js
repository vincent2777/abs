function editSales(value_id){
    var inv_id = value_id;
    $("#modal_invoice_id").val(inv_id);
    
    $.ajax({
        url: 'includes/fetchSalesData.php',
        type: 'post',
        data: {
            invoiceID: inv_id
        },
        dataType: 'json',
        success: function (response) {
            $("#modal_pname").val(response.product_name);
            $("#modal_qty").val(response.quantity);
            $("#modal_apaid").val(response.paid_amount);
            $("#modal_balance").val(response.balance_amount);
            $("#modal_total").val(response.total_amount);
            $("#modal_paymethod").val(response.payment_method);
        },
        error: function (err) {
            console.log(err);
        }


    });
}