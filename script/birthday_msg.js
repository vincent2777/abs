function composeSms(customer_name, customer_id) {

    document.getElementById("customer").innerHTML = customer_name;
    document.getElementById("customer_id").value = customer_id;

}

function sendBDaySms() {

    var customer_id = document.getElementById("customer_id").value;
    var message = document.getElementById("bday_msg").value;
    var sender_id = document.getElementById("bday_sender").value;


    var formData = {
        customer_id: customer_id,
        sender_id: sender_id,
        message:message
    }

    alert(formData.sender_id);

    $.ajax({
        type: "POST",
        url: "includes/sendBirthdayMsg.php",
        data: formData,
        dataType: "json",
        encode: true,
    }).done(function (data) {
        alert(data);
    });
}