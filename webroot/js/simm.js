


function showPopover (){
    $('#btn_remito').popover({title: "Header", content: "Text in popover body",});
};




/*FUNCIONES DESTINADA A TRAER LOS DEPARTAMENTOS Y LOCALIDADES */

function loadDptosFromDb(variable) {
    $.ajax({
        type: "POST",
        async: true,
        //url:"<?php echo \Cake\Routing\Router::url(array('controller'=>'Listas','action'=>'addCategory()', 'ext' => 'json'));?>",
        url: '../ProvDeptos/getDepartamentos',
        //url: "<?php echo Router::Url(['controller' =>'Listas','action' => 'getDptos'])?>",
        data: {'provincia' : variable},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="csrfToken"]').val());
        },
        success: function(data, textStatus){

            loadDataToSelectControlDptos(data);
        },
        error: function (data) {
        }
    });
}


function loadDptosFromDbEdit(variable) {
    $.ajax({
        type: "POST",
        async: true,
        //url:"<?php echo \Cake\Routing\Router::url(array('controller'=>'Listas','action'=>'addCategory()', 'ext' => 'json'));?>",
        url: '../../ProvDeptos/getDepartamentos',
        //url: "<?php echo Router::Url(['controller' =>'Listas','action' => 'getDptos'])?>",
        data: {'provincia' : variable},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="csrfToken"]').val());
        },
        success: function(data, textStatus){

            loadDataToSelectControlDptos(data);
        },
        error: function (data) {
        }
    });
}

function loadDptos(element) {

    let option_select = $(element).val().toString();
    if (option_select === undefined || option_select === '' || option_select == null){
        $("#dptos").prop('disabled', true);
    } else {
        $("#dptos").prop('disabled', false);
        //alert(option_select);
        //Llamo al metodo getDptos
        loadDptosFromDb(option_select);
    }

}

function loadDptosEdit(element) {

    let option_select = $(element).val().toString();
    if (option_select === undefined || option_select === '' || option_select == null){
        $("#dptos").prop('disabled', true);
    } else {
        $("#dptos").prop('disabled', false);
        //alert(option_select);
        //Llamo al metodo getDptos
        loadDptosFromDbEdit(option_select);
    }

}

function loadDataToSelectControlDptos(data) {

    let control = $("#dptos");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija un Departamento)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].departamentos;
        let optionValue = data[i].departamentos;
        control.append(new Option(optionText, optionValue));

    }

}


function selectTypePropietario(element)
{
    let option_select = $(element).val().toString();

    if (option_select === undefined || option_select === '' || option_select == null){
       //no hago nada
    } else if (option_select === 'Persona') {
        $("#group-name").hide();

        $("#group-lastname").show();
        $("#group-firstname").show();
        $("#group-dni").show();

    } else if (option_select === 'Empresa'){
        $("#group-lastname").hide();
        $("#group-firstname").hide();
        $("#group-dni").hide();

        $("#group-name").show();
    }
}

