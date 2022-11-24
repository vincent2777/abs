$(document).ready(function(){


    $("#business_logo").change(function(){
        readURL(this);
    });

    $("#business_name").keyup(function(){
        var bname = $("#business_name").val();
       document.getElementById("inv_bname").innerHTML = bname;
    });

    $("#business_slogan").keyup(function(){
        var bslogan = $("#business_slogan").val();
       document.getElementById("inv_slogan").innerHTML = bslogan;
    });

    $("#additional_msg").keyup(function(){
        var baddmsg = $("#additional_msg").val();
       document.getElementById("inv_additional_msg").innerHTML = baddmsg;
    });

    $("#business_address").keyup(function(){
        var baddress = $("#business_address").val();
       document.getElementById("inv_address").innerHTML = baddress;
    });

    $("#business_website").keyup(function(){
        var bwebsite = $("#business_website").val();
       document.getElementById("inv_website").innerHTML = bwebsite;
    });

    $("#business_phone").keyup(function(){
        var bphone = $("#business_phone").val();
       document.getElementById("inv_phone").innerHTML = bphone;
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#inv_logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
