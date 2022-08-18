


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


//MOdal administration
function showModal() {
    $("#modal_propietarios").modal("show");
}

function showModalLotes() {
    $("#modal_lotes").modal("show");
}

function closeModal(modal){
    let modal_name = $(modal).attr('attr').toString();
    $("#" + modal_name).modal("hide");
}

function closeModalById(modal_name){
    $("#" + modal_name).modal("hide");
}

function selectTypePropietarioModal(element)
{
    let option_select = $(element).val().toString();

    if (option_select === undefined || option_select === '' || option_select == null){
        //no hago nada
    } else if (option_select === 'Persona') { //tabla_persona

        //$("[name='tabladata_wrapper']").hide();
        $("#div_tabladata").show();

        $("#div_tabladata_2").hide();


    } else if (option_select === 'Empresa'){
        $("#div_tabladata").hide();

        $("#div_tabladata_2").show();
    }
}

function selectProp(object)
{
    let variable = $(object).attr('attr').toString(); //input_prop
    let id = $(object).attr('attr2').toString(); //input_prop propietarios_idpropietarios
    $("#input_prop").val(variable);
    $("#propietarios_idpropietarios").val(id);

    closeModalById('modal_propietarios');
}

function selectLote(object)
{
    let variable = $(object).attr('attr').toString();
    let id = $(object).attr('attr2').toString();

    $("#input_lotes").val(variable);
    $("#lotes_idlotes").val(id);

    closeModalById('modal_lotes');
}



function showModalAll(object) {
    let variable = $(object).attr('attr').toString();
    $("#" + variable).modal("show");
}



function showModalFilter() {
    $("#modal_filter").modal("show");
}



/* CONTROL DE SIDEBAR */

$(function (){

    let seccion = $("#seccion").attr('attr');
    let subseccion = $("#subseccion").attr('attr');

    if(seccion !== undefined && seccion != null && seccion !== ''){

        seccion = $.trim(seccion.toString());
        subseccion = $.trim(subseccion.toString());
        let item = "nav-icon-" + seccion;
        let item_Active = $("#" + item);
        item_Active.addClass('active');

        //console.log(seccion);
        //console.log(subseccion);

        if(seccion.toString() !== 'inicio')
        {

            let sub_item = "nav-icon-" + seccion + '-' + subseccion;

            let sub_item_Active = $("#" + sub_item);

            //Agrego la clase que colorea el texto
            sub_item_Active.addClass('nav-link-active');

            //obtengo el ul padre
            //let padre = item_Active.parent().parent().attr('id');
            let padre_principal = sub_item_Active.parent().parent().parent();
            $(padre_principal).addClass('menu-open');

            let titulo = "title-" + seccion.toString();
            let title_seccion = $("#" + titulo).addClass('active');

            sub_item_Active.empty();
            if(subseccion === 'Worksgroups')
            {
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + 'Grupos de Taabajo')
            } else if(subseccion === 'MetodCostos')
            {
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + 'Metodología de Costos')

            } else if (subseccion === 'OperariosMaquinas')

            {
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + 'Salarios')
            } else if(subseccion === 'DestinosProductos')
            {
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + 'Precios por Destino')
            }
            else {
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + subseccion)
            }

        }



    }
});


function removeActiveClass()
{

}



/* JS QUE TRAE DATOS */

function getParcelaByLote(lote) {

    let option_select = $(lote).val().toString();

    if (option_select === undefined || option_select === '' || option_select == null){
        $("#parcela").prop('disabled', true);
    } else {
        $("#parcela").prop('disabled', false);
        //alert(option_select);
        //Llamo al metodo getDptos
        getParcelaByLoteFromDb(option_select);
    }

}

function getParcelaByLoteEdit(lote) {

    let option_select = $(lote).val().toString();

    if (option_select === undefined || option_select === '' || option_select == null){
        $("#parcela").prop('disabled', true);
    } else {
        $("#parcela").prop('disabled', false);
        //alert(option_select);
        //Llamo al metodo getDptos
        getParcelaByLoteFromDbEdit(option_select);
    }

}


function getParcelaByLoteFromDb(lote) {

    $.ajax({
        type: "POST",
        async: true,
        url: '../Parcelas/getParcelaByLote',
        data: {'lote' : lote},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            ///console.log(data);
            loadParcelasToSelect(data);

        },
        error: function (data) {
        }
    });
}


function getParcelaByLoteFromDbEdit(lote) {

    $.ajax({
        type: "POST",
        async: true,
        url: '../../Parcelas/getParcelaByLote',
        data: {'lote' : lote},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            ///console.log(data);
            loadParcelasToSelect(data);

        },
        error: function (data) {
        }
    });
}

function loadParcelasToSelect(data){
    let control = $("#parcela");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una Parcela)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].name;
        let optionValue = data[i].idparcelas;
        control.append(new Option(optionText, optionValue));

    }
}

/******** FILTRO DE ARREGLOS MECANICOS***********/

var option_filter_arreglos = null;
var value_filter_arreglos = null;
var all_date = null;

function selectTypeFilter(option){

    let option_select = $(option).val().toString();
    let empresa = $(option).attr('attr_emp').toString();

    //seteo la variable global
    option_filter_arreglos = option_select;

    if (option_select === undefined || option_select === '' || option_select == null)
    {
        //no hago nada
    } else if (option_select === 'Fecha')
    {

        //Tengo que cambiar el visibiliti de todos los demas
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");

        $("#div-filter-fecha").css("display", "flex");

    } else if (option_select === 'Grupo'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");

        getGroups(empresa);


        $("#div-filter-grupo").css("display", "block");

    } else if (option_select === 'Maquina'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");

        getMaquinas(empresa);


        $("#div-filter-maquinas").css("display", "block");

    } else if (option_select === 'Parcela'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");

        getParcelas(empresa);


        $("#div-filter-parcelas").css("display", "block");

    } else if (option_select === 'Usuario'){

        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");

        getUsuarios(empresa);


        $("#div-filter-usuarios").css("display", "block");

    }



}


//JS load data in divs
function getGroups(empresa) {

    $.ajax({
        type: "POST",
        async: true,
        url: 'ArreglosMecanicos/getGroups',
        data: {'empresa' : empresa},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            loadGroupsToSelectModal(data);

        },
        error: function (data) {
        }
    });

}

function loadGroupsToSelectModal(data){
    let control = $("#groups_modal");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una Opción)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].name;
        let optionValue = data[i].idworksgroups;
        control.append(new Option(optionText, optionValue));

    }
}

function getMaquinas(empresa) {

    $.ajax({
        type: "POST",
        async: true,
        url: 'ArreglosMecanicos/getMaquinas',
        data: {'empresa' : empresa},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            loadMaquinasToSelectModal(data);

        },
        error: function (data) {
        }
    });

}
function loadMaquinasToSelectModal(data){
    let control = $("#maquinas_modal");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una Opción)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].name;
        let optionValue = data[i].idmaquinas;
        control.append(new Option(optionText, optionValue));

    }
}

function getParcelas(empresa) {

    $.ajax({
        type: "POST",
        async: true,
        url: 'ArreglosMecanicos/getParcelas',
        data: {'empresa' : empresa},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            loadParcelasToSelectModal(data);

        },
        error: function (data) {
        }
    });
}
function loadParcelasToSelectModal(data){
    let control = $("#parcelas_modal");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una Opción)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].name;
        let optionValue = data[i].idparcelas;
        control.append(new Option(optionText, optionValue));

    }
}

function getUsuarios(empresa) {

    $.ajax({
        type: "POST",
        async: true,
        url: 'ArreglosMecanicos/getUsuarios',
        data: {'empresa' : empresa},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            loadUsuariosToSelectModal(data);

        },
        error: function (data) {
        }
    });
}

function loadUsuariosToSelectModal(data){
    let control = $("#usuarios_modal");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una Opción)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].firstname + " " + data[i].lastname;
        let optionValue = data[i].idusers;
        control.append(new Option(optionText, optionValue));

    }
}

function filterArreglos()
{

    var table = $('#tabladata').DataTable();

    table.clear();
    table.draw();


    //AHora hago el pedido de datos basado en el tipo de filtro
    if(option_filter_arreglos !== false)
    {
        const values = [];
        //COnsutlo la opcion elegida para obtener el valor
        if(option_filter_arreglos === 'Fecha'){
            //Obtengo las fechas de los inputs

        } else if (option_filter_arreglos === 'Grupo') {
            value_filter_arreglos = $("#groups_modal").val();
            all_date = $("#groups_alldata_modal").val();
            values.push(value_filter_arreglos);

        } else if (option_filter_arreglos === 'Maquina') {
            value_filter_arreglos = $("#maquinas_modal").val();
            all_date = $("#maquinas_alldata_modal").val();
            values.push(value_filter_arreglos);

        } else if (option_filter_arreglos === 'Parcela') {
            value_filter_arreglos = $("#parcelas_modal").val();
            all_date = $("#parcelas_alldata_modal").val();
            values.push(value_filter_arreglos);

        } else if (option_filter_arreglos === 'Usuario') {
            value_filter_arreglos = $("#usuarios_modal").val();
            all_date = $("#usuarios_alldata_modal").val();
            values.push(value_filter_arreglos);

        }
        //console.log(all_date);

        $.ajax({
            type: "POST",
            async: true,
            url: 'ArreglosMecanicos/getDataFromArreglosMecanicos',
            data: {'option_select' : option_filter_arreglos, 'data' : values, 'all_date' : all_date},

            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            },
            success: function(data, textStatus){

               //console.log(data);
                loadDataFromArreglos(table, data);


            },
            error: function (data) {
                console.log('errorrrr');
            }
        });
    }

}

function loadDataFromArreglos(table, data)
{
    for (let i = 0; i < data.length; i++){

        let url = "/simm/arreglos-mecanicos/view/" + data[i].idarreglos_mecanicos.toString();
        let icon_eye = '<a href='+ url + ' class="btn bg-navy" escape="false" target= "_blank" ><span class="fas fa-eye" aria-hidden="true"></span></a>';

        let url_edit = "/simm/arreglos-mecanicos/edit/" + data[i].idarreglos_mecanicos.toString();
        let icon_edit = '<a href='+ url_edit + ' class="btn bg-purple" escape="false" style="margin-right: 4px;"><span class="fas fa-edit" aria-hidden="true"></span></a>';

        let icon_delete = '<a href="#" class="btn btn-danger" escape="false"  attr="'+ data[i].idarreglos_mecanicos + '" onclick="deleteRowFilter(this)">' +
            '<span class="fas fa-trash-alt" aria-hidden="true"></span></a>';

        //  <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
        // ['action' => 'view', $arreglo->idarreglos_mecanicos], ['class' => 'btn bg-navy', 'escape' => false, 'target' => '_blank']) ?>

        var trDOM = table.row.add([icon_eye, data[i].idarreglos_mecanicos,
        data[i].fecha.toString().substr(0,10), data[i].num_comprobante, data[i].maquina.name, data[i].mano_obra, data[i].repuestos, data[i].total,
            data[i].worksgroup.name, data[i].user.firstname + ' ' + data[i].user.lastname,  icon_edit + icon_delete] ).draw().node();

        $( trDOM ).addClass('dt-center');
    }

    closeModalById('modal_filter');
    changeBtnFilter();


}


function changeBtnFilter() {
    $("#btn-filter").removeClass('bg-yellow');
    $("#btn-filter").addClass('bg-green');

    let text = '<span class="fas fa-eye" aria-hidden="true"> ' + option_filter_arreglos.toString() + '</span>'

    $("#btn-filter").html(text);
}


function deleteRowFilter(id){

    let vlue = $(id).attr('attr');

    let row = $(id).parent().parent();


    alertConfirm(vlue, row);

}

function alertConfirm(vlue, row) {
    let text = "Eliminar " + vlue.toString() + "?";
    if (confirm(text) == true) {
        //procedo a eliminar
        $.ajax({
            type: "POST",
            async: true,
            url: 'ArreglosMecanicos/delete',
            data: {'id' : vlue},

            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            },
            success: function(data, textStatus){

                //console.log(data);

                if(data.result == true){
                    deleteRow(row);
                }

                //loadDataFromArreglos(table, data);


            },
            error: function (data) {
            }
        });




    } else {
        console.log('fue false');
    }

}

function deleteRow(row)
{
    let table = $('#tabladata').DataTable();
    table
        .row($(row))
        .remove()
        .draw();
}
