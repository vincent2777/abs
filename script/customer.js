function confirmDelete(cust_id) {

    var x = window.confirm("You are about Deleting a Record. Do you wish to proceed?");

    if (x == true) {
        window.open("customers?action=delete&cust_id=" + cust_id, '_self');
    }
}

function confirmStoreDelete(store_id) {

    var x = window.confirm("You are about Deleting this Store. Do you wish to proceed?");

    if (x == true) {
        window.open("add_store?action=delete&store_id=" + store_id, '_self');
    }
}


function editCustomer(user_id){
    
        $.ajax({
            url: '../includes/fetchCustomers.php',
            type: 'post',
            data: {
                custID: user_id
            },
            dataType: 'json',
            success: function(response) {
                $("#modal_custname").val(response.cust_name);
                $("#modal_address").val(response.cust_address);
                $("#modal_phone1").val(response.cust_phone);
                $("#modal_phone2").val(response.cust_phone2);
                $("#modal_dob").val(response.cust_dob);
                $("#modal_customer_id").val(response.cust_id);
                $("#modal_category").val(response.customer_type);
                $("#modal_climit").val(response.cust_credit_limit);
                $("#modal_prevamount").val(response.cust_credit_limit);
                
            }

        });

}


   //paycredit
    function payNow(user_id){
        
        
        $.ajax({
            url: '../includes/fetchCustomers.php',
            type: 'post',
            data: {
                custID: user_id
            },
            dataType: 'json',
            success: function(response) {
                $("#credit_custname").val(response.cust_name);
                $("#credit_amount").val(response.cust_owing);
                $("#credit_customer_id").val(response.cust_id); 
                $("#credit_topay").val(response.cust_owing);
                
                //if ready
                $("#payNowBody").css("display","block");
                $("#img-loader").css("display","none");
            }

        });
    }

$(document).ready(function() {
    // order date picker
    $("#dob").datepicker();
    $("#modal_dob").datepicker();
});