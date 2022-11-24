function editUser(user_id) {
    $("#userdb_id").val(user_id);

    $.ajax({
        url: '../includes/fetchUserData.php',
        type: 'post',
        data: {
            userID: user_id
        },
        dataType: 'json',
        success: function (response) {
            $("#eusername").val(response.username);
            $("#erole").val(response.user_role);
            $("#epassword").val(response.user_password);
            $("#efname").val(response.full_name);
            $("#eemail").val(response.email);
            $("#ephone").val(response.phone_number);
            $("#eaddress").val(response.address);
            $("#estore").val(response.store_id);
            $("#edob").val(response.dob);
            $("#eemployment_date").val(response.employment_date);
            $("#esack_date").val(response.sack_date);
            $("#esalary").val(response.salary);
            $("#eaccbname").val(response.bank_name);
            $("#eaccno").val(response.bank_acc_no);
            $("#eaccname").val(response.bank_acc_name);
            
            if(response.photo != ""){
                $("#employee_photo").css("display","block");
                $("#employee_photo").attr("src",response.photo);
            }
        },
        error: function (err) {
            console.log(err);
        }


    });
}

document.getElementById('markall').onclick = function () {
    var checkboxes = document.getElementsByName('selectedPriviledges[]');
    for (var checkbox of checkboxes) {
        checkbox.checked = true;
    }
}

const etogglePassword = document.querySelector('#etogglePassword');
const epassword = document.querySelector('#epassword');

etogglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = epassword.getAttribute('type') === 'password' ? 'text' : 'password';
    epassword.setAttribute('type', type);
    // toggle the eye slash icon
    document.querySelector("#epass-icon").classList.toggle('fa-eye-slash');
});

const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');

togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    document.querySelector("#pass-icon").classList.toggle('fa-eye-slash');
});

function confirmDeleteRole(id) {
    var x = window.confirm("You are about Deleting a Record. Do you wish to proceed?");
    if (x == true) {
        window.open("ems_employees?do=delete&type=role&param=" + id, '_self');
    }else{
        return false
    }
}


function confirmDelete(id) {
   
    var x = window.confirm("You are about Deleting an Employee. Do you wish to proceed?");
    if (x == true) {
        window.open("ems_employees?do=delete&type=user&param=" + id,"_self");
        return true
    }else{
        return false
    }
}