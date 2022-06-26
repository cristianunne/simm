


function passwordValidate() {

    var text_2 = $("#password_repeat").val();
    var text_1 = $("#password").val();


    if (text_2 == text_1){

        $('#boton_submit').prop('disabled', false);

    } else {
        $('#boton_submit').prop('disabled', true);
    }

}

