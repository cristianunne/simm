


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
}selectTypeFilter


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
        $("#div_tabladata_prop_1").show();

        $("#div_tabladata_prop_2").hide();


    } else if (option_select === 'Empresa'){
        $("#div_tabladata_prop_1").hide();

        $("#div_tabladata_prop_2").show();
    }
}

function selectPropAll() {
    let variable = 'Todos';
    let id = 0;

    //aca debo cambiar

    //$("#input_lotes").val(variable);
    //$("#lotes_idlotes").val(id);

    $('#propietarios_idpropietarios').empty();

    $('#propietarios_idpropietarios').append($('<option>', {
        value: id,
        text: variable
    }));

    $("#propietarios_idpropietarios").val(id);

    closeModalById('modal_propietarios');
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


function selectLoteUsoMaquinaria(object) {

    let id_function = $(object).attr('id').toString();


    let variable = $(object).attr('attr').toString();
    let id = $(object).attr('attr2').toString();

    $("#input_lotes").val(variable);
    $("#lotes_idlotes").val(id);

    //Si el id es usomaquinaria_add traigo las parcelas
    //Le paso el control lotes parcelas

    if (id_function === undefined || id_function === '' || id_function == null){
           console.log("error");
    } else {

        //Verifico si es el EDIT
        if (id_function === 'usomaquinaria_edit'){

            getParcelaByLoteEdit($("#lotes_idlotes"));
        } else {
            getParcelaByLote($("#lotes_idlotes"));
        }


    }
    closeModalById('modal_lotes');



}

function selectDestAll() {
    let variable = 'Todos';
    let id = 0;

    //aca debo cambiar

    //$("#input_lotes").val(variable);
    //$("#lotes_idlotes").val(id);

    $('#destinos_iddestinos').empty();

    $('#destinos_iddestinos').append($('<option>', {
        value: id,
        text: variable
    }));

    $("#destinos_iddestinos").val(id);

    closeModalById('modal_destinos');
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
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + 'Grupos de Trabajo')
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
            else if(subseccion === 'Centros_costos')
            {
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + 'Centros de Costos')
            }

            else if(subseccion === 'Grupos_costos')
            {
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + 'Análisis Grupos')
            }

            else if(subseccion === 'Grupos_costos')
            {
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + 'Análisis Grupos')
            }

            else {
                sub_item_Active.html( '<i class="fas fa-circle nav-icon" style="color: navy;"></i>' + subseccion)
            }

        }



    }
});

/* CONTROL DE SIDEBAR */


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

    //Verificar si viene de un edit la peticion


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

function filterArreglos(id_user)
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
            let value_fecha_1 = $("#fecha_desde").val().toString();
            let value_fecha_2 = $("#fecha_hasta").val().toString();
            all_date = null;
            values.push(value_fecha_1);
            values.push(value_fecha_2);

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

               console.log(data);
               loadDataFromArreglos(table, data, id_user);


            },
            error: function (data) {
                console.log('errorrrr');
            }
        });
    }

}

function loadDataFromArreglos(table, data, id_user)
{
    for (let i = 0; i < data.length; i++){

        let url = "/simm/arreglos-mecanicos/view/" + data[i].idarreglos_mecanicos.toString();
        let icon_eye = '<a href='+ url + ' class="btn bg-navy" escape="false" target= "_blank" ><span class="fas fa-eye" aria-hidden="true"></span></a>';

        let url_edit = "/simm/arreglos-mecanicos/edit/" + data[i].idarreglos_mecanicos.toString();
        let icon_edit = '<a href='+ url_edit + ' class="btn bg-purple" escape="false" style="margin-right: 4px;"><span class="fas fa-edit" aria-hidden="true"></span></a>';

        let icon_delete = '';

        if(id_user !== undefined && data[i].user.idusers !== undefined){
            if(id_user === data[i].user.idusers){
                icon_delete = '<a href="#" class="btn btn-danger" escape="false"  attr="'+ data[i].idarreglos_mecanicos + '" onclick="deleteRowFilter(this)">' +
                    '<span class="fas fa-trash-alt" aria-hidden="true"></span></a>';
            }
        }

        //  <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
        // ['action' => 'view', $arreglo->idarreglos_mecanicos], ['class' => 'btn bg-navy', 'escape' => false, 'target' => '_blank']) ?>

        var trDOM = table.row.add([icon_eye, data[i].idarreglos_mecanicos,
        data[i].fecha.toString().substr(0,10), data[i].num_comprobante, data[i].maquina.name, data[i].mano_obra, data[i].repuestos, data[i].total,
            data[i].user.firstname + ' ' + data[i].user.lastname,
            icon_edit + icon_delete] ).draw().node();

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



/***** FUNCIONES PARA ADDCOSTOS MAQUINAS**************/

function selectGroups(option){

    //OBtengo el value del select
    let option_select = $(option).val().toString();

    //OBtengo el id de la maquina
    let id_maquina = $("#name_maquina").attr('attr').toString();
    //alert(option_select);

    if ((option_select === undefined || option_select === '' || option_select == null) ||
        (id_maquina === undefined || id_maquina === '' || id_maquina == null))
    {
        //Informo el error
        alert("Tenemos poblemas al procesar la solicitud. Intente nuevamente");
    } else {

        getCentroCostos(id_maquina, option_select);

    }
}

function getCentroCostos(id_maquina = null, id_group = null)
{

    $.ajax({
        type: "POST",
        async: true,
        url: '../getCentroCostosByMaquinaAndGroups',
        data: {'worksgroup' : id_group, 'maquina' : id_maquina},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            console.log(data);
            loadCentroCostosToSelect(data);

        },
        error: function (data) {
        }
    });

}
function loadCentroCostosToSelect(data) {

    let control = $("#select_centro_costos");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una opción)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].name;
        let optionValue = data[i].idcentros_costos;
        control.append(new Option(optionText, optionValue));
    }
}

/*GET COSTO MAQUINAS BY ID **/

function getCostosMaquinaById(element) {

    let id_costo = $(element).attr('attr2');
    let id_modal = $(element).attr('attr');

    let history_costos = $(element).attr('attr3');

    if (id_costo === undefined || id_costo === '' || id_costo == null)
    {
        //Informo el error
        alert("Tenemos poblemas al procesar la solicitud. Intente nuevamente");
    } else {

        getCostosMaquinaByIdDb(id_costo, element, history_costos);
    }

}

function getCostosMaquinaByIdDb(id_costo, element, history_costos) {

    //Reviso de donde viene la peticion y elijo la url en virtud
    let url = null;

    if(history_costos === 'history_costos'){
        url = '../../viewCostosMaq';
    } else {
        url = '../viewCostosMaq';
    }


    $.ajax({
        type: "POST",
        async: true,
        url: url,
        data: {'id_costo_maq' : id_costo},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            loadDataToCostosMaquinaView(data, element, history_costos);

        },
        error: function (data) {
        }
    });

}

function loadDataToCostosMaquinaView(data, element, history_costos) {

    let maq_name = $("#maq_name");
    let grupo_name = $("#grupo_name");
    let centro_costo_name = $("#centro_costo_name");
    let met_costo_name = $("#met_costo_name");


    maq_name.empty();
    grupo_name.empty();
    centro_costo_name.empty();
    met_costo_name.empty();

    let alquilada = $("#alquilada");
    let val_adq = $("#val_adq");
    let vida_util = $("#vida_util");
    let horas_tot = $("#horas_tot");
    let horas_men = $("#horas_men");
    let tasa_int = $("#tasa_int");
    let cons_lit_h = $("#cons_lit_h");
    let alquiler = $("#alquiler");
    let credito = $("#credito");

    alquilada.empty();
    val_adq.empty();
    vida_util.empty();
    horas_tot.empty();
    horas_men.empty();
    tasa_int.empty();
    cons_lit_h.empty();
    alquiler.empty();
    credito.empty();


    let val_neum = $("#val_neum");
    let vida_util_neum = $("#vida_util_neum");
    let horas_uso_anual = $("#horas_uso_anual");
    let horas_uso_dia = $("#horas_uso_dia");
    let fat_corr = $("#fat_corr");
    let coef_arr_mec = $("#coef_arr_mec");
    let lubricante = $("#lubricante");

    val_neum.empty();
    vida_util_neum.empty();
    horas_uso_anual.empty();
    horas_uso_dia.empty();
    fat_corr.empty();
    coef_arr_mec.empty();
    lubricante.empty();


    maq_name.html(data[0].maquina.marca.toString() + ": " + data[0].maquina.name.toString());
    grupo_name.html(data[0].worksgroup.name.toString());
    centro_costo_name.html(data[0].centros_costos[0].name.toString());

    if(data[0].alquilada){
        alquilada.html('SI');
    } else {
        alquilada.html('NO');
    }

    val_adq.html(data[0].val_adq);
    vida_util.html(data[0].vida_util);
    horas_tot.html(data[0].horas_total_uso);
    horas_men.html(data[0].horas_mens_uso);
    tasa_int.html(data[0].tasa_int_simple);
    cons_lit_h.html(data[0].consumo);
    alquiler.html(data[0].costo_alquiler);

    if(data[0].credito){
        credito.html('SI');
    } else {
        credito.html('NO');
    }



    val_neum.html(data[0].val_neum);
    vida_util_neum.html(data[0].vida_util_neum);
    //ES hora efectivas
    horas_uso_anual.html(data[0].horas_efec_uso);
    horas_uso_dia.html(data[0].horas_dia_uso);
    fat_corr.html(data[0].factor_cor);
    coef_arr_mec.html(data[0].coef_err_mec);
    lubricante.html(data[0].lubricante);

    //Hago una consulta para metodologia de costos en particular
    getMetodologiaCostos(data[0].metod_costos_hashmetod_costos, met_costo_name, history_costos);


    showModalAll(element)
}


function getMetodologiaCostos(hash_id, met_costo_name, history_costos) {

    if(history_costos === 'history_costos'){
        url = '../../getMetodologiaCostosByHashId';
    } else {
        url = '../getMetodologiaCostosByHashId';
    }


    $.ajax({
        type: "POST",
        async: true,
        url: url,
        data: {'hash_id' : hash_id},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            //return data;
            met_costo_name.html(data[0].name.toString());
            //loadDataToCostosMaquinaView(data, element);

        },
        error: function (data) {
        }
    });
}


function selectConditionsMaquina(object)
{
    let value_ = $(object).val();
    //Si el valor es 1 apago todos los input menos el de costo, si es 0 los prendo

    if(value_ == 0)
    {
        $("[name='costo_alquiler']").prop('disabled', true);

        //Apago todos los controles
        $("[name='val_adq']").prop('disabled', false);
        $("[name='vida_util']").prop('disabled', false);
        $("[name='horas_total_uso']").prop('disabled', false);
        $("[name='horas_mens_uso']").prop('disabled', false);
        $("[name='tasa_int_simple']").prop('disabled', false);
        $("[name='consumo']").prop('disabled', false);
        $("[name='val_neum']").prop('disabled', false);
        $("[name='vida_util_neum']").prop('disabled', false);
        $("[name='horas_efec_uso']").prop('disabled', false);
        $("[name='horas_dia_uso']").prop('disabled', false);
        $("[name='factor_cor']").prop('disabled', false);
        $("[name='coef_err_mec']").prop('disabled', false);
        $("[name='lubricante']").prop('disabled', false);

        $("[name='credito']").prop('disabled', false);

        //desactivo el required

        $("[name='val_adq']").attr('required', 'required');
        $("[name='vida_util']").attr('required', 'required');
        $("[name='horas_total_uso']").attr('required', 'required');
        $("[name='horas_mens_uso']").attr('required', 'required');
        $("[name='tasa_int_simple']").attr('required', 'required');
        $("[name='consumo']").attr('required', 'required');
        $("[name='val_neum']").attr('required', 'required');
        $("[name='vida_util_neum']").attr('required', 'required');
        $("[name='horas_efec_uso']").attr('required', 'required');
        $("[name='horas_dia_uso']").attr('required', 'required');
        $("[name='factor_cor']").attr('required', 'required');
        $("[name='coef_err_mec']").attr('required', 'required');
        $("[name='lubricante']").attr('required', 'required');

        $("[name='credito']").attr('required', 'required');


        $("[name='costo_alquiler']").removeAttr('required');
        $("[name='costo_alquiler']").val('');

    } else {

        $("[name='costo_alquiler']").prop('disabled', false);
        $("[name='costo_alquiler']").attr('required', 'required');

        //Apago todos los controles
        $("[name='val_adq']").prop('disabled', true);
        $("[name='vida_util']").prop('disabled', true);
        $("[name='horas_total_uso']").prop('disabled', true);
        $("[name='horas_mens_uso']").prop('disabled', true);
        $("[name='tasa_int_simple']").prop('disabled', true);
        $("[name='consumo']").prop('disabled', true);
        $("[name='val_neum']").prop('disabled', true);
        $("[name='vida_util_neum']").prop('disabled', true);
        $("[name='horas_efec_uso']").prop('disabled', true);
        $("[name='horas_dia_uso']").prop('disabled', true);
        $("[name='factor_cor']").prop('disabled', true);
        $("[name='coef_err_mec']").prop('disabled', true);
        $("[name='lubricante']").prop('disabled', true);
        $("[name='credito']").prop('disabled', true);

        //Activo el required


        $("[name='val_adq']").removeAttr('required');
        $("[name='vida_util']").removeAttr('required');
        $("[name='horas_total_uso']").removeAttr('required');
        $("[name='horas_mens_uso']").removeAttr('required');
        $("[name='tasa_int_simple']").removeAttr('required');
        $("[name='consumo']").removeAttr('required');
        $("[name='val_neum']").removeAttr('required');
        $("[name='vida_util_neum']").removeAttr('required');
        $("[name='horas_efec_uso']").removeAttr('required');
        $("[name='horas_dia_uso']").removeAttr('required');
        $("[name='factor_cor']").removeAttr('required');
        $("[name='coef_err_mec']").removeAttr('required');
        $("[name='lubricante']").removeAttr('required');
        $("[name='credito']").removeAttr('required');


        //limpio las celdas

        $("[name='val_adq']").val('');
        $("[name='vida_util']").val('');
        $("[name='horas_total_uso']").val('');
        $("[name='horas_mens_uso']").val('');
        $("[name='tasa_int_simple']").val('');
        $("[name='consumo']").val('');
        $("[name='val_neum']").val('');
        $("[name='vida_util_neum']").val('');
        $("[name='horas_efec_uso']").val('');
        $("[name='horas_dia_uso']").val('');
        $("[name='factor_cor']").val('');
        $("[name='coef_err_mec']").val('');
        $("[name='lubricante']").val('');

    }

}


/*REMITOS */

function showModalMaquinas() {
    $("#modal_maquinas").modal("show");
}


//Cargo la maquina selecionada en la tabla principal l
function loadMaquinaSelectToTable(element)
{
    //Accedo a los atributos

    let option = $(element);
    let maquina = option.attr('maquina').toString();
    let operario = option.attr('operario').toString();
    let id_maq_op = option.attr('id_maq_op').toString();

    let table = $('#tabladata').DataTable();

    let number_count = table.rows().count() + 1;

    //selecciono el valor del input
    let alqTon = $("#input_" + id_maq_op).val();


    let icon_delete = '<a href="#" class="btn btn-danger" escape="false"  maquina="'+ maquina + '" ' + ' operario="' + operario + '" '
        + ' id_maq_op="' + id_maq_op + '"' + ' onclick="deleteRowRemitos(this)">' +
        '<span class="fas fa-trash-alt" aria-hidden="true"></span></a>';


    let trDOM = table.row.add([number_count,
        maquina, operario, alqTon, icon_delete] ).draw().node();

    $( trDOM ).addClass('dt-center');

    let table_maq_modal = $('#tabladata_maq_modal').DataTable();

    // table_maq_modal.row(':eq( ' + idrow + ')').remove().draw();
    table_maq_modal.row( option.parents('tr') )
        .remove()
        .draw();

}

function addMaquinaToRemito(element)
{
    //Accedo a los atributos

    let option = $(element);

    //Debo obtener los IDS

    let id_maquina = option.attr('id_maquina').toString();
    let id_operario = option.attr('id_operario').toString();
    let id_maq_op = option.attr('id_maq_op').toString();

    let remito_id = $("#remito_number").attr('attr');



    let url = '../../RemitosMaquinas/addRemitoMaquina';

    $.ajax({
        type: "POST",
        async: true,
        url: url,
        data: {'remitos_idremitos' : remito_id, 'operarios_idoperarios': id_operario, 'maquinas_idmaquinas': id_maquina},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            console.log(data);
            //Si esta todo okey hagamos el add a la tabla

            //loadMaquinaSelectToTable(element);

            location.reload(true);

        },
        error: function (data) {

            console.log(data);

        }
    });
}

function addMaquinaAlquiladaToRemito(element)
{
    //Accedo a los atributos

    let option = $(element);

    //Debo obtener los IDS

    let id_maquina = option.attr('id_maquina').toString();
    let remito_id = $("#remito_number").attr('attr');

    let url = '../../RemitosMaquinas/addRemitoMaquinaAlquilada';

    $.ajax({
        type: "POST",
        async: true,
        url: url,
        data: {'remitos_idremitos' : remito_id, 'maquinas_idmaquinas': id_maquina},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){


            console.log(data);
            //loadMaquinaSelectToTable(element);

            location.reload(true);

        },
        error: function (data) {

            console.log(data);

        }
    });

}




function deleteRowRemitos(element)
{
    let table = $('#tabladata').DataTable();
    let option = $(element);

    // table_maq_modal.row(':eq( ' + idrow + ')').remove().draw();

    let res = table.row( option.parents('tr') )
        .remove();
    table.draw();


    addRowToTableMaquinasOperarios(element)


}

function addRowToTableMaquinasOperarios(element)
{
    //Agrego el dato a la
    let table_maq_modal = $('#tabladata_maq_modal').DataTable();
    let option = $(element);

    let maquina = option.attr('maquina').toString();
    let operario = option.attr('operario').toString();
    let id_maq_op = option.attr('id_maq_op').toString();

    let input_ = '<td class="dt-center"><input type="number" value="0" id="input_"' + id_maq_op + '></input></td>';

    let actions = '<td></td>';


    let trDOM = table_maq_modal.row.add([maquina, operario, input_, actions] ).draw().node();
    $(trDOM).addClass('dt-center');


}



function selectLoteRemito(object) {

    let id_function = $(object).attr('id').toString();


    let variable = $(object).attr('attr').toString();
    let id = $(object).attr('attr2').toString();

    //aca debo cambiar

    //$("#input_lotes").val(variable);
    //$("#lotes_idlotes").val(id);

    $('#lotes_idlotes').empty();

    $('#lotes_idlotes').append($('<option>', {
        value: id,
        text: variable
    }));

    $("#lotes_idlotes").val(id);


    //Si el id es usomaquinaria_add traigo las parcelas
    //Le paso el control lotes parcelas

    if (id_function === undefined || id_function === '' || id_function == null){
        console.log("error");
    } else {

        //Verifico si es el EDIT
        if (id_function === 'remitos_edit'){

            getParcelaByLoteEdit($("#lotes_idlotes"));
        } else {
            getParcelaByLote($("#lotes_idlotes"));
        }

    }
    closeModalById('modal_lotes');

}

function selectLotesAll()
{


    let variable = 'Todos';
    let id = 0;

    //aca debo cambiar

    //$("#input_lotes").val(variable);
    //$("#lotes_idlotes").val(id);

    $('#lotes_idlotes').empty();

    $('#lotes_idlotes').append($('<option>', {
        value: id,
        text: variable
    }));

    $("#lotes_idlotes").val(id);

    let control = $("#parcela");
    //Limpio el select primero
    control.empty();
    control.append(new Option('Todos', 0));


    closeModalById('modal_lotes');
}

function selectPropRemito(object)
{
    let variable = $(object).attr('attr').toString(); //input_prop
    let id = $(object).attr('attr2').toString(); //input_prop propietarios_idpropietarios


    //$("#input_prop").val(variable);
    //$("#propietarios_idpropietarios").val(id);


    $('#propietarios_idpropietarios').empty();

    $('#propietarios_idpropietarios').append($('<option>', {
        value: id,
        text: variable
    }));

    $("#propietarios_idpropietarios").val(id);

    closeModalById('modal_propietarios');
}



function selectDestinoRemito(object) {

    let id_function = $(object).attr('id').toString();

    let variable = $(object).attr('attr').toString();
    let id = $(object).attr('attr2').toString();

    //$("#input_dest").val(variable);
    //$("#destinos_iddestinos").val(id);



    $('#destinos_iddestinos').empty();

    $('#destinos_iddestinos').append($('<option>', {
        value: id,
        text: variable
    }));

    $("#destinos_iddestinos").val(id);


    //Si el id es usomaquinaria_add traigo las parcelas
    //Le paso el control lotes parcelas

    closeModalById('modal_destinos');

}

function selectProductosRemito(object) {

    let id_function = $(object).attr('id').toString();

    let variable = $(object).attr('attr').toString();
    let id = $(object).attr('attr2').toString();



    $('#productos_idproductos').empty();

    $('#productos_idproductos').append($('<option>', {
        value: id,
        text: variable
    }));

    $("#productos_idproductos").val(id);

    //Si el id es usomaquinaria_add traigo las parcelas
    //Le paso el control lotes parcelas selectProductosRemito

    closeModalById('modal_productos');

}


/** Filtro Remitos **

 */

let option_filter_remitos = null;
let value_filter_remitos = null;
let all_date_remitos = null;

function selectTypeFilterRemitos(option){

    let option_select = $(option).val().toString();
    let empresa = $(option).attr('attr_emp').toString();

    //seteo la variable global
    option_filter_remitos = option_select;

    if (option_select === undefined || option_select === '' || option_select == null)
    {
        //no hago nada
    } else if (option_select === 'Fecha')
    {

        //Tengo que cambiar el visibiliti de todos los demas
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");
        $("#div-filter-usuarios").css("display", "none");
        $("#div-filter-lote").css("display", "none");
        $("#div-filter-propietarios").css("display", "none");
        $("#div-filter-productos").css("display", "none");
        $("#div-filter-destinos").css("display", "none");

        $("#div-filter-fecha").css("display", "flex");

    } else if (option_select === 'Grupo'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");
        $("#div-filter-usuarios").css("display", "none");
        $("#div-filter-lote").css("display", "none");
        $("#div-filter-propietarios").css("display", "none");
        $("#div-filter-productos").css("display", "none");
        $("#div-filter-destinos").css("display", "none");

        getGroups(empresa);


        $("#div-filter-grupo").css("display", "block");

    } else if (option_select === 'Maquina'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");
        $("#div-filter-usuarios").css("display", "none");
        $("#div-filter-lote").css("display", "none");
        $("#div-filter-propietarios").css("display", "none");
        $("#div-filter-productos").css("display", "none");
        $("#div-filter-destinos").css("display", "none");

        getMaquinas(empresa);


        $("#div-filter-maquinas").css("display", "block");

    } else if (option_select === 'Lote'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-usuarios").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");
        $("#div-filter-propietarios").css("display", "none");
        $("#div-filter-productos").css("display", "none");
        $("#div-filter-destinos").css("display", "none");

        getLotes(empresa);
        $("#div-filter-lote").css("display", "block");

    } else if (option_select === 'Parcela'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-usuarios").css("display", "none");
        $("#div-filter-lote").css("display", "none");
        $("#div-filter-propietarios").css("display", "none");
        $("#div-filter-productos").css("display", "none");
        $("#div-filter-destinos").css("display", "none");

        getParcelas(empresa);


        $("#div-filter-parcelas").css("display", "block");

    } else if (option_select === 'Propietario'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-usuarios").css("display", "none");
        $("#div-filter-lote").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");
        $("#div-filter-productos").css("display", "none");
        $("#div-filter-destinos").css("display", "none");


        $("#div-filter-propietarios").css("display", "block");

    } else if (option_select === 'Producto'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-usuarios").css("display", "none");
        $("#div-filter-lote").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");
        $("#div-filter-propietarios").css("display", "none");
        $("#div-filter-destinos").css("display", "none");


        getProductos(empresa);

        $("#div-filter-productos").css("display", "block");

    } else if (option_select === 'Destino'){
        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-usuarios").css("display", "none");
        $("#div-filter-lote").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");
        $("#div-filter-propietarios").css("display", "none");
        $("#div-filter-productos").css("display", "none");

        getDestinos(empresa);
        $("#div-filter-destinos").css("display", "block");

    } else if (option_select === 'Usuario'){

        $("#div-filter-fecha").css("display", "none");
        $("#div-filter-grupo").css("display", "none");
        $("#div-filter-maquinas").css("display", "none");
        $("#div-filter-parcelas").css("display", "none");
        $("#div-filter-lote").css("display", "none");
        $("#div-filter-propietarios").css("display", "none");
        $("#div-filter-productos").css("display", "none");
        $("#div-filter-destinos").css("display", "none");

        getUsuarios(empresa);

        $("#div-filter-usuarios").css("display", "block");

    }
}

function pruebaSesion()
{
    let name = sessionStorage.getItem("idusers")

    console.log(name);
}


function filterRemitos(iduser)
{

    var table = $('#tabladata').DataTable();

    table.clear();
    table.draw();


    //AHora hago el pedido de datos basado en el tipo de filtro
    if(option_filter_remitos !== false)
    {
        const values = [];
        //COnsutlo la opcion elegida para obtener el valor
        if(option_filter_remitos === 'Fecha'){
            //Obtengo las fechas de los inputs
            let value_fecha_1 = $("#fecha_desde").val().toString();
            let value_fecha_2 = $("#fecha_hasta").val().toString();
            all_date_remitos = null;
            values.push(value_fecha_1);
            values.push(value_fecha_2);

        } else if (option_filter_remitos === 'Grupo') {
            value_filter_remitos = $("#groups_modal").val();
            all_date_remitos = $("#groups_alldata_modal").val();
            values.push(value_filter_remitos);

        } else if (option_filter_remitos === 'Maquina') {
            value_filter_remitos = $("#maquinas_modal").val();
            all_date_remitos = $("#maquinas_alldata_modal").val();
            values.push(value_filter_remitos);

        } else if (option_filter_remitos === 'Parcela') {
            value_filter_remitos = $("#parcelas_modal").val();
            all_date_remitos = $("#parcelas_alldata_modal").val();
            values.push(value_filter_remitos);

        } else if (option_filter_remitos === 'Usuario') {
            value_filter_remitos = $("#usuarios_modal").val();
            all_date_remitos = $("#usuarios_alldata_modal").val();
            values.push(value_filter_remitos);

        } else if (option_filter_remitos === 'Lote'){
            value_filter_remitos = $("#lotes_modal").val();
            all_date_remitos = $("#lotes_alldata_modal").val();
            values.push(value_filter_remitos);

        } else if (option_filter_remitos === 'Propietario'){
            value_filter_remitos = $("#prop_modal").val();
            all_date_remitos = $("#propietarios_alldata_modal").val();
            values.push(value_filter_remitos);
        } else if (option_filter_remitos === 'Producto'){
            value_filter_remitos = $("#productos_modal").val();
            all_date_remitos = $("#productos_alldata_modal").val();
            values.push(value_filter_remitos);

        } else if (option_filter_remitos === 'Destino'){
            value_filter_remitos = $("#destinos_modal").val();
            all_date_remitos = $("#destinos_alldata_modal").val();
            values.push(value_filter_remitos);
        }
        //console.log(all_date);

        $.ajax({
            type: "POST",
            async: true,
            url: 'Remitos/getDataFromRemitos',
            data: {'option_select' : option_filter_remitos, 'data' : values, 'all_date' : all_date_remitos},

            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                $("#content_loading").show();
            },
            success: function(data, textStatus){

                //console.log(data);
                setTimeout(function (){
                    loadDataToTableRemitos(table, data, iduser);
                    $("#content_loading").hide();
                }, 2000);

            },
            error: function (data) {
                console.log('errorrrr');
            }
        });
    }
}



function loadDataToTableRemitos(table, data, iduser)
{
    for (let i = 0; i < data.length; i++) {

        let url = "/simm/remitos/view/" + data[i].idremitos.toString();
        let icon_eye = '<a href=' + url + ' class="btn bg-navy" escape="false" target= "_blank" ><span class="fas fa-eye" aria-hidden="true"></span></a>';

        let url_edit = "/simm/remitos/edit/" + data[i].idremitos.toString();
        let icon_edit = '<a href=' + url_edit + ' class="btn bg-purple" escape="false" style="margin-right: 4px;"><span class="fas fa-edit" aria-hidden="true"></span></a>';

        let icon_delete = '';

        if(data[i].users_idusers === iduser){
            icon_delete = '<a href="#" class="btn btn-danger" escape="false"  attr="' + data[i].idremitos + '" onclick="deleteRowFilterRemitos(this)">' +
                '<span class="fas fa-trash-alt" aria-hidden="true"></span></a>';
        }

        let url_maq = "/simm/remitos/add-maquinas/" + data[i].idremitos.toString();
        let icon_maq = '<a href=' + url_edit + ' class="btn bg-green" escape="false" style="margin-right: 4px;"><span class="fas fa-truck" aria-hidden="true"></span></a>';


        //Compruebo si el propietario es empresa o persona
        let tipo_prop = null;

        if (data[i].propietario.tipo === 'Empresa') {
            tipo_prop = data[i].propietario.name;
        } else {
            tipo_prop = data[i].propietario.firstname + " " + data[i].propietario.lastname;
        }



        var trDOM = table.row.add([icon_eye,
            data[i].remito_number,
            data[i].fecha.toString().substr(0,10),
            data[i].worksgroup.name,
            data[i].lote == null ? '' : data[i].lote.name,
            data[i].parcela == null ? '' : data[i].parcela.name,
            tipo_prop,
            data[i].producto.name,
            data[i].ton,
            data[i].precio_ton,
            data[i].destino.name,
            data[i].user.firstname + ' ' + data[i].user.lastname,
            icon_maq,
            icon_edit + icon_delete]
        ).draw().node();
        $( trDOM ).addClass('dt-center');

    }

    closeModalById('modal_filter');
    changeBtnFilterRemitos();

}

function changeBtnFilterRemitos() {
    $("#btn-filter").removeClass('bg-yellow');
    $("#btn-filter").addClass('bg-green');

    let text = '<span class="fas fa-eye" aria-hidden="true"> ' + option_filter_remitos.toString() + '</span>'

    $("#btn-filter").html(text);
}


function deleteRowFilterRemitos(id){

    let vlue = $(id).attr('attr');

    let row = $(id).parent().parent();


    alertConfirmRemitos(vlue, row);

}

function alertConfirmRemitos(vlue, row) {
    let text = "Eliminar " + vlue.toString() + "?";
    if (confirm(text) == true) {
        //procedo a eliminar
        $.ajax({
            type: "POST",
            async: true,
            url: 'Remitos/delete',
            data: {'id' : vlue},

            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            },
            success: function(data, textStatus){

                //console.log(data);

                if(data.result == true){
                    deleteRow(row);
                }

            },
            error: function (data) {
            }
        });

    } else {
        console.log('fue false');
    }

}



function getLotes(empresa)
{
    $.ajax({
        type: "POST",
        async: true,
        url: 'remitos/getLotes',
        data: {'empresa' : empresa},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            loadLotesToSelectModal(data);
        },
        error: function (data) {
        }
    });

}

function loadLotesToSelectModal(data){
    let control = $("#lotes_modal");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una Opción)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].name;
        let optionValue = data[i].idlotes;
        control.append(new Option(optionText, optionValue));

    }
}


function getPropietarios(element)
{
    //Obtengo el tipo de propietario
    let control = $(element);
    let option_select = control.val().toString();

    let empresa = control.attr('attr_emp').toString();

    $.ajax({
        type: "POST",
        async: true,
        url: 'remitos/getPropietarios',
        data: {'empresa' : empresa, 'tipo' : option_select},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            loadPropietariosToSelectModal(data, option_select);
        },
        error: function (data) {
        }
    });

}

function loadPropietariosToSelectModal(data, tipo){
    let control = $("#prop_modal");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una opción)', null));

    //console.log(tipo);

    if(tipo === 'Empresa'){

        for (let i = 0; i < data.length; i++){
            let optionText = data[i].name;
            let optionValue = data[i].idpropietarios;
            control.append(new Option(optionText, optionValue));

        }

    } else {
        for (let i = 0; i < data.length; i++){
            let optionText = data[i].firstname + " " + data[i].lastname;
            let optionValue = data[i].idpropietarios;
            control.append(new Option(optionText, optionValue));

        }
    }


}


function getProductos(empresa)
{
    $.ajax({
        type: "POST",
        async: true,
        url: 'remitos/getProductos',
        data: {'empresa' : empresa},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            loadProductosToSelectModal(data);
        },
        error: function (data) {
        }
    });

}

function loadProductosToSelectModal(data){
    let control = $("#productos_modal");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una Opción)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].name;
        let optionValue = data[i].idproductos;
        control.append(new Option(optionText, optionValue));

    }
}




function getDestinos(empresa)
{
    $.ajax({
        type: "POST",
        async: true,
        url: 'remitos/getDestinos',
        data: {'empresa' : empresa},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            //console.log(data);
            loadDestinosToSelectModal(data);
        },
        error: function (data) {
        }
    });

}


function loadDestinosToSelectModal(data){
    let control = $("#destinos_modal");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una Opción)', null));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].name;
        let optionValue = data[i].iddestinos;
        control.append(new Option(optionText, optionValue));

    }
}


/***  METODOS PARA USO DE MAQUINARIA****/

function addCombustibleToTable()
{
    //Accedo a los valores de los inputs
    let categoria = "Combustible";
    let producto = $("#category_comb").val();
    let litros = $("#litros_comb").val();
    let precio = $("#precio_comb").val();


    let table = $('#tabladata').DataTable();

    let number_count = table.rows().count() + 1;


    let icon_delete =   '<button type="button" class="btn btn-danger" aria-label="Left Align" onClick="deleteRowUsos(this)"' +
                        'attr="' + producto + '">' +
                        '<span class="fas fa-trash" aria-hidden="true"></span></button>';


    let name = categoria.toString() + number_count.toString() + '[]';

    let cat_input = '<input type="hidden" name="' + name +'" value="' + categoria + '">';
    let producto_input = '<input type="hidden" name="' + name +'" value="' + producto + '">';
    let litros_input = '<input type="hidden" name="' + name +'" value="' + litros + '">';
    let precio_input = '<input type="hidden" name="' + name +'" value="' + precio + '">';

    //esta variable la uso para procesar en el php el arreglo de combustibes
    if(number_count > 1){
        //elimino el que existe y creo el nuevo
        $("#cant_combustible").remove();
    }

    let cant_combustible = '<input type="hidden" name="cant_combustible"  id="cant_combustible" value="' + number_count.toString() + '">';


    let trDOM = table.row.add([number_count,
        categoria, producto, litros, precio, icon_delete] ).draw().node();

    $( trDOM ).addClass('dt-center');

    let div_ = '<div>' + cat_input + producto_input + litros_input + precio_input + cant_combustible + '</div>';
    $('#combustible_hidden').append(div_);


    $("#litros_comb").val('');
    $("#precio_comb").val('');

    //ELimino la opcion del input
    $("#category_comb").find("option[value='"+producto.toString() +"']").remove();


}

function deleteRowUsos(element)
{
    let table = $('#tabladata').DataTable();
    let option = $(element);

   // console.log();
    // table_maq_modal.row(':eq( ' + idrow + ')').remove().draw();
    let producto = option.attr('attr');


    let res = table.row( option.parents('tr') )
        .remove();
    table.draw();

    if((res !== null) && (res !== undefined)){

        //Devolvio un objeto, restauro el input

        $('#category_comb').append($('<option>', {
            value: producto,
            text: producto
        }));

    }

    //AL eliminar tengo que restaurar

    //addRowToTableMaquinasOperarios(element)
}

function deleteRowUsosLub(element)
{
    let table = $('#tabladata_2').DataTable();
    let option = $(element);

    // console.log();
    // table_maq_modal.row(':eq( ' + idrow + ')').remove().draw();
    let producto = option.attr('attr');


    let res = table.row( option.parents('tr') )
        .remove();
    table.draw();

    if((res !== null) && (res !== undefined)){

        //Devolvio un objeto, restauro el input

        $('#category_lub').append($('<option>', {
            value: producto,
            text: producto
        }));

    }

    //AL eliminar tengo que restaurar

    //addRowToTableMaquinasOperarios(element)
}

function addLubricanteToTable(element) {

    //Accedo a los valores de los inputs
    let categoria = "Lubricante";
    let producto = $("#category_lub");
    let litros = $("#litros_lub");
    let precio = $("#precio_lub");

    let table = $('#tabladata_2').DataTable();

    let number_count = table.rows().count() + 1;

    let icon_delete =   '<button type="button" class="btn btn-danger" aria-label="Left Align" onClick="deleteRowUsosLub(this)"' +
        'attr="' + producto.val() + '">' +
        '<span class="fas fa-trash" aria-hidden="true"></span></button>';

    let name = categoria + number_count.toString() + '[]';

    let cat_input = '<input type="hidden" name="' + name +'" value="' + categoria + '">';
    let producto_input = '<input type="hidden" name="' + name +'" value="' + producto.val() + '">';
    let litros_input = '<input type="hidden" name="' + name +'" value="' + litros.val() + '">';
    let precio_input = '<input type="hidden" name="' + name +'" value="' + precio.val() + '">';

    //esta variable la uso para procesar en el php el arreglo de combustibes
    if(number_count > 1){
        //elimino el que existe y creo el nuevo
        $("#cant_lubricante").remove();
    }

    let cant_lubricante = '<input type="hidden" name="cant_lubricante"  id="cant_lubricante" value="' + number_count.toString() + '">';

    let trDOM = table.row.add([number_count,
        categoria, producto.val(), litros.val(), precio.val(), icon_delete] ).draw().node();

    $( trDOM ).addClass('dt-center');

    let div_ = '<div>' + cat_input + producto_input + litros_input + precio_input + cant_lubricante + '</div>';
    $('#lubricante_hidden').append(div_);


    litros.val('');
    precio.val('');

    //ELimino la opcion del input
    producto.find("option[value='"+producto.val().toString() +"']").remove();

}

/**  EL EDIT DE USO_MAQUINARIA LO PROCESO A PARTIR DE JQUERY, SOLAMENTE LOS PRODUCTOS**/



function deleteProductUsoMaqComb(element) {

    let url = '../deleteUsoComb';

    let id_ = $(element).attr('id');
    let producto = $(element).attr('producto');

    let table_uso_maq = $('#tabladata').DataTable();

    $.confirm({
        title: 'Confirmar!',
        content: '¿Desea Eliminar el Combustible?',
        buttons: {
            aceptar: function () {
                //Si confirma proceso

                $.ajax({
                    type: "POST",
                    async: true,
                    url: url,
                    data: {id_uso : id_},

                    beforeSend: function (xhr) { // Add this line
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    },
                    success: function(data, textStatus){

                      //Actualizo el row
                     location.reload();
                    },
                    error: function (data) {
                        console.log('error ' + data);
                    }
                });

            },
            cancelar: function () {

            },
        }
    });

}

function editCombustible()
{
    //Accedo a los valores de los inputs
    let categoria = "Combustible";
    let producto = $("#category_comb").val();
    let litros = $("#litros_comb").val();
    let precio = $("#precio_comb").val();
    let uso_maquinaria_iduso_maquinaria = $("#btn-editcomb").attr('attr');


    let table = $('#tabladata').DataTable();



    //Proceso el agregado
    //COmpruebo que todos los campos esten completos
    if(categoria !== '' && producto !== '' && litros !== '' && precio !== '' && uso_maquinaria_iduso_maquinaria !== ''){
        let url = '../addUsoMaqAjax';
        $.ajax({
            type: "POST",
            async: true,
            url: url,
            data: {categoria : categoria, producto: producto, litros: litros, precio: precio, uso_maquinaria_iduso_maquinaria: uso_maquinaria_iduso_maquinaria},

           beforeSend: function (xhr) { // Add this line
               xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
           },
           success: function(data, textStatus){
                location.reload();
           },
           error: function (data) {
               console.log('error ' + data);
           }
        });

    }


}



function editLubricante()
{

    //Accedo a los valores de los inputs
    let categoria = "Lubricante";
    let producto = $("#category_lub").val();
    let litros = $("#litros_lub").val();
    let precio = $("#precio_lub").val();
    let uso_maquinaria_iduso_maquinaria = $("#btn-editlub").attr('attr');


    //Proceso el agregado
    //COmpruebo que todos los campos esten completos
    if(categoria !== '' && producto !== '' && litros !== '' && precio !== '' && uso_maquinaria_iduso_maquinaria !== ''){
        let url = '../addUsoMaqAjax';
        $.ajax({
            type: "POST",
            async: true,
            url: url,
            data: {categoria : categoria, producto: producto, litros: litros, precio: precio,
                uso_maquinaria_iduso_maquinaria: uso_maquinaria_iduso_maquinaria},

            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            },
            success: function(data, textStatus){
                location.reload();
            },
            error: function (data) {
                console.log('error ' + data);
            }
        });

    }


}

function deleteProductUsoMaqLub(element) {

    let url = '../deleteUsoComb';

    let id_ = $(element).attr('id');
    let producto = $(element).attr('producto');

    let table_uso_maq = $('#tabladata_2').DataTable();

    $.confirm({
        title: 'Confirmar!',
        content: '¿Desea Eliminar el Lubricante?',
        buttons: {
            aceptar: function () {
                //Si confirma proceso

                $.ajax({
                    type: "POST",
                    async: true,
                    url: url,
                    data: {id_uso: id_},

                    beforeSend: function (xhr) { // Add this line
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    },
                    success: function (data, textStatus) {

                        //Actualizo el row
                        location.reload();
                    },
                    error: function (data) {
                        console.log('error ' + data);
                    }
                });

            },
            cancelar: function () {

            },
        }
    });

}

    /************ Analisis de costos****/

    function selectLoteCostos(object) {

        let id_function = $(object).attr('id').toString();


        let variable = $(object).attr('attr').toString();
        let id = $(object).attr('attr2').toString();

        let origen = $(object).attr('attr3') === undefined ? null : $(object).attr('attr3').toString();

        //aca debo cambiar

        //$("#input_lotes").val(variable);
        //$("#lotes_idlotes").val(id);

        $('#lotes_idlotes').empty();

        $('#lotes_idlotes').append($('<option>', {
            value: id,
            text: variable
        }));

        $("#lotes_idlotes").val(id);


        //Si el id es usomaquinaria_add traigo las parcelas
        //Le paso el control lotes parcelas

        if (id_function === undefined || id_function === '' || id_function == null){
            console.log("error");
        } else {

            //Verifico si es el EDIT

            getParcelaByLoteCostos($("#lotes_idlotes"), origen);

        }
        closeModalById('modal_lotes');

    }

function getParcelaByLoteCostos(lote, origen) {

    let option_select = $(lote).val().toString();


    if (option_select === undefined || option_select === '' || option_select == null){
        $("#parcela").prop('disabled', true);
    } else {
        $("#parcela").prop('disabled', false);
        //alert(option_select);
        //Llamo al metodo getDptos
        getParcelaByLoteFromDbCostos(option_select, origen);
    }

}



function getParcelaByLoteFromDbCostos(lote, origen) {

    let url = null;
    //Verificar si viene de un edit la peticion
    if(origen === 'variaciones'){
        url = 'Parcelas/getParcelaByLote';

    } else {
        url = '../Parcelas/getParcelaByLote';

    }


    $.ajax({
        type: "POST",
        async: true,
        url: url,
        data: {'lote' : lote},

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){

            ///console.log(data);
            loadParcelasToSelectCostos(data);

        },
        error: function (data) {
        }
    });
}



function loadParcelasToSelectCostos(data){
    let control = $("#parcela");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija una Parcela)', null));
    control.append(new Option('Todos', 0));

    for (let i = 0; i < data.length; i++){
        let optionText = data[i].name;
        let optionValue = data[i].idparcelas;
        control.append(new Option(optionText, optionValue));

    }
}

function loadPropietarioToSelectCostos(propietario)
{
    let control = $("#propietarios_idpropietarios");
    //Limpio el select primero
    control.empty();
    control.append(new Option('(Elija un Propietario)', null));
    control.append(new Option('Todos', 0));


    let optionText = propietario.name;
    let optionValue = propietario.idpropietarios;
    control.append(new Option(optionText, optionValue));

    control.val(optionValue).change();


}



function parcelaInputChanged(input)
{
    let val = $(input).val();

    let context = $(input).attr('attr');

    getPropietarioByParcela(val, context);

}


function getPropietarioByParcela(id_parcela, context)
{

    if(id_parcela !== undefined || id_parcela !== '')
    {
        url = null;
        if(context === 'edit')
        {
            url = '../../Parcelas/getPropietarioByParcela';
        } else {
            url = '../Parcelas/getPropietarioByParcela';
        }

        $.ajax({
            type: "POST",
            async: true,
            url: url,
            data: {id_parcela: id_parcela},

            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            },
            success: function (data, textStatus) {

                if(data.propietario !== null){
                    loadPropietarioToSelectCostos(data.propietario);
                }

            },
            error: function (data) {
                console.log(data);
            }
        });


    }


}



/*** CAlculo de costos ***/

function getConfirmRed(title, content)
{
    $.confirm({
        icon: 'fas fa-exclamation-circle',
        title: title,
        content: content,
        type: 'red',
        typeAnimated: true,
        buttons: {
            close:
                {
                    text: 'Aceptar',
                    btnClass: 'btn-red',
                    function () {
                    }}
        }
    });
}


var result_verified = null;

function verifiedverifiedDataByMonth(variables)
{
    let url = 'verifiedDataForAnalisysCostos';
    $.ajax({
        type: "POST",
        async: false,
        url: url,
        data: variables,

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        },
        success: function(data, textStatus){
            //console.log(data);

            if(data.result === false || data.result === 'false')
            {
                result_verified = false;
                return false;
            }

            result_verified = true;
            return true;

        },
        error: function (data) {
        }
    });

}



var data_info = null;

function groupsCostosCalc(button) {

    //Primero verifico que me haya pasado los datos
    let groups_control = $("#worksgroups_idworksgroups");
    let fecha_inicio_control = $("#fecha_inicio");
    let fecha_final_control = $("#fecha_final");
    let lotes_idlotes_control = $("#lotes_idlotes");
    let parcelas_idparcelas_control = $("#parcela");
    let propietarios_idpropietarios_control = $("#propietarios_idpropietarios");
    let destinos_iddestinos_control = $("#destinos_iddestinos");


    if (groups_control.val() === '' || fecha_inicio_control.val() === '' || fecha_final_control.val() === '' ||
        lotes_idlotes_control.val() === '' || parcelas_idparcelas_control.val() === '' || parcelas_idparcelas_control.val() === undefined
        || propietarios_idpropietarios_control === '' ||
        destinos_iddestinos_control === '') {

        getConfirmRed('¡Advertencia!', 'Debe completar todos los campos para proceder!')

    } else {

        //SI paso el proceso, proceso la info :selected
        //Creo las variables
        let groups = groups_control.val();
        let fecha_inicio = fecha_inicio_control.val();
        let fecha_final = fecha_final_control.val();
        let lotes = lotes_idlotes_control.val();
        let parcelas = parcelas_idparcelas_control.val();
        let propietarios = propietarios_idpropietarios_control.val();
        let destinos = destinos_iddestinos_control.val();

        //PUedo pasarle el texto tmb
        let group_name = $("#worksgroups_idworksgroups :selected").text();
        let lote_name = $("#lotes_idlotes :selected").text();
        let parcelas_name = $("#parcela :selected").text();
        let propietarios_name = $("#propietarios_idpropietarios :selected").text();
        let destinos_name = $("#destinos_iddestinos :selected").text();

        var gen_informe = null;


        //Verifico si los filtros de Lotes, parcelas, propietarios y destinos estan activos, si lo estan
        //notifico que el calculo puede fallar

        if (lote_name !== 'Todos' || parcelas_name !== 'Todos' || propietarios_name !== 'Todos' || destinos_name !== 'Todos') {
            let lot_n = lote_name !== 'Todos' ? '<p style="color: #117427; display: contents;">SI</p>' : '<p style="color: red; display: contents;">NO</p>';
            let par_n = parcelas_name !== 'Todos' ? '<p style="color: #117427; display: contents;">SI</p>' : '<p style="color: red; display: contents;">NO</p>';
            let prop_n = propietarios_name !== 'Todos' ? '<p style="color: #117427; display: contents;">SI</p>' : '<p style="color: red; display: contents;">NO</p>';
            let dest_n = destinos_name !== 'Todos' ? '<p style="color: #117427; display: contents;">SI</p>' : '<p style="color: red; display: contents;">NO</p>';


            let text = 'Usted ha seleccionado Filtros por: <br>' +
                'Lote: ' + lot_n +
                '<br>Parcela: ' + par_n +
                '<br>Propietario: ' + prop_n +
                '<br>Destino: ' + dest_n +
                '<br> <br> Para realizar este tipo de procesamiento la información debe estar completeamente cargada! <br><br>'
                + '<h5>¿Desea continuar con el procesamiento?</h5>';

            $.confirm({
                icon: 'fas fa-exclamation-circle',
                title: 'Advertencia de aplicación de Filtros',
                content: text,
                type: 'orange',
                typeAnimated: true,
                buttons: {
                    confirm:
                        {
                            text: 'Aceptar',
                            btnClass: 'btn-green',
                            action: function () {

                                //Creo un confirm para saber si almacenar el informe o no
                                $.confirm({
                                    icon: 'fas fa-question-circle',
                                    title: '¿Consulta?',
                                    content: '¿Desea generar un informe?',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        confirm:
                                            {
                                                text: 'Aceptar',
                                                btnClass: 'btn-green',
                                                action: function () {
                                                    let variables = {
                                                        'groups': groups,
                                                        'fecha_inicio': fecha_inicio,
                                                        'fecha_final': fecha_final,
                                                        'lotes': lotes,
                                                        'propietarios': propietarios,
                                                        'destinos': destinos,
                                                        'parcelas': parcelas,
                                                        'informe': true,
                                                        'group_name': group_name,
                                                        'lote_name': lote_name,
                                                        'parcelas_name': parcelas_name,
                                                        'propietarios_name': propietarios_name,
                                                        'destinos_name': destinos_name
                                                    };


                                                    result_verified = null;
                                                    $.when(verifiedverifiedDataByMonth(variables)).done(
                                                        function () {
                                                            console.log(result_verified);
                                                            if(result_verified){
                                                                processCalcCostosGroups(variables);
                                                            } else {
                                                                let title = 'Error';
                                                                let content = 'Es posible que no esten completos los datos ' +
                                                                    'para el periodo seleccionado!'
                                                                getConfirmRed(title, content);
                                                            }

                                                        }
                                                    );



                                                }
                                            },
                                        cancel:
                                            {
                                                text: 'Cancelar',
                                                btnClass: 'btn-red',
                                                action: function () {
                                                    let variables = {
                                                        'groups': groups,
                                                        'fecha_inicio': fecha_inicio,
                                                        'fecha_final': fecha_final,
                                                        'lotes': lotes,
                                                        'propietarios': propietarios,
                                                        'destinos': destinos,
                                                        'parcelas': parcelas,
                                                        'informe': false
                                                    };


                                                    $.when(verifiedverifiedDataByMonth(variables)).done(
                                                        function () {


                                                            if(result_verified){
                                                                processCalcCostosGroups(variables);
                                                            } else {
                                                                let title = 'Error';
                                                                let content = 'Es posible que no esten completos los datos ' +
                                                                    'para el periodo seleccionado'
                                                                getConfirmRed(title, content);
                                                            }

                                                        }
                                                    );



                                                }
                                            }
                                    }
                                });

                            }
                        },
                    cancel:
                        {
                            text: 'Cancelar',
                            btnClass: 'btn-red',
                            action: function () {

                            }
                        }
                }
            });
        } else {
            //NO se aplicaron filtros
            //Creo un confirm para saber si almacenar el informe o no
            $.confirm({
                icon: 'fas fa-question-circle',
                title: '¿Consulta?',
                content: '¿Desea generar un informe?',
                type: 'green',
                typeAnimated: true,
                buttons: {
                    confirm:
                        {
                            text: 'Aceptar',
                            btnClass: 'btn-green',
                            action: function () {
                                let variables = {
                                    'groups': groups,
                                    'fecha_inicio': fecha_inicio,
                                    'fecha_final': fecha_final,
                                    'lotes': lotes,
                                    'propietarios': propietarios,
                                    'destinos': destinos,
                                    'parcelas': parcelas,
                                    'informe': true,
                                    'group_name': group_name,
                                    'lote_name': lote_name,
                                    'parcelas_name': parcelas_name,
                                    'propietarios_name': propietarios_name,
                                    'destinos_name': destinos_name
                                };


                                $.when(verifiedverifiedDataByMonth(variables)).done(
                                    function () {

                                        if(result_verified){


                                            processCalcCostosGroups(variables);


                                        } else {
                                            let title = 'Error';
                                            let content = 'Es posible que no esten completos los datos ' +
                                                'para el periodo seleccionado'
                                            getConfirmRed(title, content);
                                        }

                                    }
                                );



                            }
                        },
                    cancel:
                        {
                            text: 'Cancelar',
                            btnClass: 'btn-red',
                            action: function () {
                                let variables = {
                                    'groups': groups,
                                    'fecha_inicio': fecha_inicio,
                                    'fecha_final': fecha_final,
                                    'lotes': lotes,
                                    'propietarios': propietarios,
                                    'destinos': destinos,
                                    'parcelas': parcelas,
                                    'informe': false
                                };


                                $.when(verifiedverifiedDataByMonth(variables)).then(
                                    function () {


                                        if(result_verified){
                                            processCalcCostosGroups(variables);
                                        } else {
                                            let title = 'Error';
                                            let content = 'Es posible que no esten completos los datos ' +
                                                'para el periodo seleccionado'
                                            getConfirmRed(title, content);
                                        }

                                    }
                                );


                            }
                        }
                }
            });


        }
    }
}

function processCalcCostosGroups(variables)
{

    var a = $.confirm({
        theme: 'supervan',
        lazyOpen: true,
        closeIcon: false,
        type: 'blue',
        typeAnimated: true,
        icon: 'fa fa-spinner fa-spin',
        title: 'Procesando!',
        content: 'Estamos realizando el cálculo de Costos, por favor espere!',
        buttons:false

    });

    $.ajax({
        type: "POST",
        async: true,
        url: 'calculateCostosGruposAjax',
        data: variables,

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            //Debo mostrar un loading

            a.open();

        },
        success: function(data, textStatus){

            //controlo que el resultado no sea false

          if(data.result === undefined)
          {

              data_info = data.costos;
              console.log(data);

              $.when(showDataCostosToDisplayLeft(data)).then(
                  function () {

                      //DEbo verificar que el infrome este en true
                      if(data.informe !== undefined && data.informe !== null) {
                          if(data.informe.informe === true){
                              let id_informe = data.informe.id;
                              $("#down_informe").css({"display": "block"});
                              $("#down_informe").attr('attr', data.informe.path);

                          }

                      }
                  }
              );

          } else {

              a.close();
              //SIn datos
              $.confirm({
                  icon: 'fas fa-exclamation-circle',
                  title: '¡Error!',
                  content: '<strong>' + data.msg.toString() + '</strong>' + '<br>' + 'Verifique que los datos de Remitos, ' +
                      'Uso de Maquinaria, Operarios y ' +
                      'Costos de Maquinas esten cargados!',
                  type: 'red',
                  typeAnimated: true,
                  buttons: {
                      close:
                          {
                              text: 'Aceptar',
                              btnClass: 'btn-red',
                              function () {
                              }}
                  }
              });
          }


            //COnsulto si el informe es ok, y en virtud de ello consulto

        },
        error: function (data, textStatus) {


            console.log(data);
        },
        complete: function (data) {

            setInterval(function (){
                a.close();
            }, 7000);

        }
    });


}


function showDataCostosToDisplayLeft(data) {

    data = data.costos;

    let costo_total = 0;
    let toneladas = 0;
    let info_header = $("#info-header-left");

    toneladas = data.general.toneladas;
    data = data.centros;

    for (let i = 0; i < data.length; i++) {
        costo_total = costo_total + data[i].costo_total;
    }



    info_header.text("Costo Total: " + costo_total.toFixed(2) + " $/t");

    let ul_left = $("#left_tree_items");
    ul_left.html("");

    for (let i = 0; i < data.length; i++) {
        let li = createdLiLeft(data[i], costo_total);
        ul_left.append(li);
    }

    //Tengo que procesar la informacion
    let gen_costo = $("#gen_costo");
    let gen_toneladas = $("#gen_toneladas");
    gen_costo.val(costo_total.toFixed(2) + " $/t");
    gen_toneladas.val(toneladas.toFixed(2));




}


function openCentroCostos(button)
{

    let id = $(button).attr('attr');
    let centro_costo = $(button).attr('attr3');
    let costo = $(button).attr('attr2');

    let div_maq_cont = $("#maquinas_informacion_costos");
    let header_div_cont = $("#info-header-maquinas");

    //Recupero los controles superiores
    let centro_costo_input = $("#centro_top_name");
    let costo_input = $("#centro_top_costo");


    //Seteo la cabecera
    let detalles_maq_input = $("#detalles_maq");
    let costoh_maq_input = $("#costoh_maq");
    let prod_maq_input = $("#prod_maq");
    let costot_maq_input = $("#costot_maq");

    detalles_maq_input.val("");
    costoh_maq_input.val("");
    prod_maq_input.val("");
    costot_maq_input.val("");

    div_maq_cont.empty();

    let div_info_maq =  $("#div-info-maquinas");
    div_info_maq.empty();



    if(data_info === undefined || data_info === null){

        $.confirm({
            icon: 'fas fa-exclamation-circle',
            title: '¡Advertencia!',
            content: 'Error. Realice el cálculo nuevamente!',
            type: 'red',
            typeAnimated: true,
            buttons: {
                close:
                    {
                        text: 'Aceptar',
                        btnClass: 'btn-red',
                        function () {
                        }}
            }
        });

    } else {

        header_div_cont.text(centro_costo +  ": " + costo);
        centro_costo_input.val(centro_costo);
        costo_input.val(costo);

        //data_info = data_info.costos;
        //console.log(data_info);

        for (let i = 0; i < data_info['centros'].length; i++){

            if(id.toString() === data_info['centros'][i].idcentros_costos.toString()){

                //console.log(data_info['centros'][i]);

                for (let j = 0; j < data_info['centros'][i].maquinas.length; j++){

                    //Llamo al constructor del item
                    let item = createdItemAcordionMaquinas(data_info['centros'][i].maquinas[j], j, data_info['centros'][i]);
                    div_maq_cont.append(item);
                }

            }
        }

    }

}

/** Crea el elemento mas chico**/
function createdLiLeft(data, costo_total)
{


    let txt = '<strong>' + data.name + ": " + '</strong>';
    let id_ = 'cc_' + data.idcentros_costos;

    let costo = null;

    if(data.costo_total != null){
        costo = data.costo_total.toFixed(2) + " $/t";
    }


    let porcentaje = (data.costo_total * 100) / costo_total;

    let porc_txt = null;

    if(porcentaje != null){
        porc_txt = ' (' + porcentaje.toFixed(2) + '% costo/t)';
    }


    let li = '<li id="' + id_ + '" onclick="openCentroCostos(this)" attr="' + data.idcentros_costos +
        '" attr2="' + costo + '" attr3="' + data.name +
         '">' +
        '<i class="fas fa-cog"></i> <span class="span-margin-left">' + txt + costo + porc_txt + '</span>' +
        ' </li>';

    return li;
}


function createdItemAcordionMaquinas(maquina, id, centro_costo)
{
    /***Debo modifical el nombre del collapsed segun la cantidad de maquinas ***/

    let name_maquina = maquina.name;
    let name_id = "panelsStayOpen-" + id.toString();
    let name_heading = "panelsStayOpen-heading-" + id.toString();

    //Controlo que el contenido nea null
    let costo_ton = null;
    let toneladas = null;
    let costo_h = null;
    let horas = null;
    let rendimiento = null;

    if(maquina.costos.costo_ton === null){
        costo_ton = 'Sin Datos';
    } else {
        costo_ton =   maquina.costos.costo_ton.toFixed(2);
    }

    if(maquina.costos.toneladas === null){
        toneladas = 'Sin Datos';
    } else {
        toneladas =  maquina.costos.toneladas.toFixed(2);
    }

    if(maquina.costos.costo_h === null){
        costo_h = 'Sin Datos';
    } else {
        costo_h =   maquina.costos.costo_h.toFixed(2);
    }

    if(maquina.costos.horas === null){
        horas = 'Sin Datos';
    } else {
        horas =  maquina.costos.horas.toFixed(2);
    }

    if(maquina.costos.prod_rend_h === null){
        rendimiento = 'Sin Datos';
    } else {
        rendimiento =   maquina.costos.prod_rend_h.toFixed(2);
    }


    let interes = maquina.result_metod.interes === null ? 'Sin Datos' : maquina.result_metod.interes.toFixed(2);
    let seguro = maquina.result_metod.seguro === null ? 'Sin Datos' : maquina.result_metod.seguro.toFixed(2);;
    let dep_maq = maquina.result_metod.dep_maq === null ? 'Sin Datos' : maquina.result_metod.dep_maq.toFixed(2);;
    let dep_neum = maquina.result_metod.dep_neum === null ? 'Sin Datos' : maquina.result_metod.dep_neum.toFixed(2);;
    let arreglos = maquina.result_metod.arreglos_maq === null ? 'Sin Datos' : maquina.result_metod.arreglos_maq.toFixed(2);;
    let combustibles = maquina.result_metod.cons_comb === null ? 'Sin Datos' : maquina.result_metod.cons_comb.toFixed(2);;
    let lubricantes = maquina.result_metod.cons_lub === null ? 'Sin Datos' : maquina.result_metod.cons_lub.toFixed(2);;
    let operador = maquina.result_metod.operador === null ? 'Sin Datos' : maquina.result_metod.operador.toFixed(2);;
    let mantenimiento = maquina.result_metod.mantenimiento === null ? 'Sin Datos' : maquina.result_metod.mantenimiento.toFixed(2);;
    let administracion = maquina.result_metod.administracion === null ? 'Sin Datos' : maquina.result_metod.administracion.toFixed(2);
    let alquiler = maquina.alquiler;


    let item = '<div class="accordion-item">' +
            '<h2 class="accordion-header" id="' + name_heading + '">' +

                '<button class="accordion-button" type="button" data-bs-toggle="collapse" ' +
                    'data-bs-target="#' + name_id + '" aria-expanded="true" ' +
                    'aria-controls="' + name_id + '" onclick="showInfoMaquina(this)"'
                        + 'attr=" ' + centro_costo.idcentros_costos + '" attr2="' + centro_costo.name + '" attr3="' + maquina.name  +
                        '" attr4="' + maquina.idmaquinas  + '" costo="' + costo_ton + '"' +
                                ' costo_h="' + costo_h + '"' +
                                ' rendimiento="' + rendimiento + '"' +

                                ' interes="' + interes + '"' +
                                ' seguro="' + seguro + '"' +
                                ' dep_maq="' + dep_maq + '"' +
                                ' dep_neum="' + dep_neum + '"' +
                                ' arreglos="' + arreglos + '"' +
                                ' combustibles="' +combustibles+ '"' +
                                ' lubricantes="' + lubricantes + '"' +
                                ' operador="' + operador+ '"' +
                                ' mantenimiento="' + mantenimiento + '"' +
                                ' administracion="' + administracion + '"' +
                                ' alquiler="' + alquiler + '"' +
                        '>' +
                        '<span><i class="fas fa-tractor" style="margin-right: 7px;"></i></span>' +
                        name_maquina +
                '</button>' +
            '</h2>' +
            '<div id="' + name_id  + '" class="accordion-collapse collapse" aria-labelledby="' + name_heading + '">' +
                '<div class="accordion-body">' +
                    //COntenido de la maquina
                        '<ul>' +
                           '<li> <strong>Costo/t: </strong>' + costo_ton + ' $/t' + '</li>' +
                            '<li><strong> Toneladas: </strong>' + toneladas + '</li>' +
                            '<li><strong> Costo/h: </strong>' + costo_h + ' $/h' + '</li>' +
                            '<li> <strong>Horas: </strong>' + horas + '</li>' +
                            '<li><strong> Rendimiento: </strong>' + rendimiento + ' t/h' + '</li>' +
                        '</ul>' +
                '</div>' +
            '</div>' +
        '</div>';
    return item;

}

function showInfoMaquina(element) {

    let centro_costo_id = $(element).attr('attr');
    let centro_costo_name = $(element).attr('attr2');
    let maquina_name = $(element).attr('attr3');
    let maquina_id = $(element).attr('attr4');

    let costo_t = $(element).attr('costo');
    let costo_h = $(element).attr('costo_h');
    let produccion = $(element).attr('rendimiento');

    //Seteo la cabecera
    let detalles_maq_input = $("#detalles_maq");
    let costoh_maq_input = $("#costoh_maq");
    let prod_maq_input = $("#prod_maq");
    let costot_maq_input = $("#costot_maq");

    detalles_maq_input.val(maquina_name);
    costoh_maq_input.val(costo_h + " $/h");
    prod_maq_input.val(produccion + " t/h");
    costot_maq_input.val(costo_t + " $/h");

    //TRaigo lo detalles de la maquina
    let interes = $(element).attr('interes');
    let seguro = $(element).attr('seguro');
    let dep_maq = $(element).attr('dep_maq');
    let dep_neum = $(element).attr('dep_neum');

    let arreglos = $(element).attr('arreglos');
    let combustibles = $(element).attr('combustibles');
    let lubricantes = $(element).attr('lubricantes');
    let operador = $(element).attr('operador');

    let mantenimiento = $(element).attr('mantenimiento');
    let administracion = $(element).attr('administracion');

    let alquiler = $(element).attr('alquiler').toString();
    let alquiler_ = alquiler === 'true' ? 'No' : 'Si';
    let alquiler_bool = alquiler === 'true' ? false : true;


    //cargo el ul con el detalle
    $("#div-info-maquinas").empty();
    $("#div-info-maquinas").append('<ul id="ul-detalles-maquina"></ul>');

    let ul_detalles = $("#ul-detalles-maquina");


    let alquiler_li = '<li><strong>¿Máquina Propia?: </strong>' + alquiler_ + '</li>';

    ul_detalles.append(alquiler_li);
    ul_detalles.append('<br/>');

    //consulto si es alquilada
    if(alquiler_bool == false)
    {
        let interes_li = '<li><strong>Alquiler: </strong>' + costo_t + ' $/t' + '</li>';

        ul_detalles.append(interes_li);
        ul_detalles.append('<br/>');

    } else {
        let interes_li = '<li><strong>Interés: </strong>' + interes + ' $/t' + '</li>';
        let seguro_li = '<li><strong>Seguro: </strong>' + seguro + ' $/t' + '</li>';

        let dep_maq_li = '<li><strong>Dep. Máquina: </strong>' + dep_maq + ' $/t' + '</li>';
        let dep_neum_li = '<li><strong>Dep. Neumáticos: </strong>' + dep_neum + ' $/t' + '</li>';
        let arreglos_li = '<li><strong>Arreglos Mecánicos: </strong>' + arreglos + ' $/t' + '</li>';

        let combustibles_li = '<li><strong>Combustible: </strong>' + combustibles + ' $/t' + '</li>';
        let lubricantes_li = '<li><strong>Lubricante: </strong>' + lubricantes + ' $/t' + '</li>';

        let operador_li = '<li><strong>Operador: </strong>' + operador + ' $/t' + '</li>';
        let mantenimiento_li = '<li><strong>Mantenimiento: </strong>' + mantenimiento + ' $/t' + '</li>';
        let administracion_li = '<li><strong>Administración: </strong>' + administracion + ' $/t' + '</li>';


        ul_detalles.append(interes_li);
        ul_detalles.append(seguro_li);
        ul_detalles.append('<br/>');
        ul_detalles.append(dep_maq_li);
        ul_detalles.append(dep_neum_li);
        ul_detalles.append(arreglos_li);
        ul_detalles.append('<br/>');
        ul_detalles.append(combustibles_li);
        ul_detalles.append(lubricantes_li);
        ul_detalles.append('<br/>');
        ul_detalles.append(operador_li);
        ul_detalles.append(mantenimiento_li);
        ul_detalles.append(administracion_li);

    }





}


function createHeader(element, tipe_icon)
{


    let header_li = '<div class="sui-treeview-item-content">' +
        '<span class="sui-treeview-item-toggle" style="visibility: visible;">' +
        '<span class="sui-treeview-item-toggle-collapsed"></span>' +
        '</span>' +
        '<span class="sui-treeview-item-text" id="shielddw">' +
        '<span class="sui-treeview-item-icon fas ' + tipe_icon + '"></span>'+ element +
        '</span></div>';

    return header_li;

}


function downloadInforme(button)
{

    let path = $(button).attr('attr');

    if(path !== undefined && path !== null && path !== ''){
        window.open("../" + path , '_blank');
    } else {

        //Mensaje de error
        $.confirm({
            icon: 'fas fa-exclamation-circle',
            title: '¡Error!',
            content: 'El Informe que desea descargar no existe!',
            type: 'red',
            typeAnimated: true,
            buttons: {
                close:
                    {
                        text: 'Aceptar',
                        btnClass: 'btn-red',
                        function () {
                        }}
            }
        });

    }

}

function downloadInformeVariacion(button)
{

    let path = $(button).attr('attr');

    if(path !== undefined && path !== null && path !== ''){
        window.open(path , '_blank');
    } else {

        //Mensaje de error
        $.confirm({
            icon: 'fas fa-exclamation-circle',
            title: '¡Error!',
            content: 'El Informe que desea descargar no existe!',
            type: 'red',
            typeAnimated: true,
            buttons: {
                close:
                    {
                        text: 'Aceptar',
                        btnClass: 'btn-red',
                        function () {
                        }}
            }
        });

    }

}


/*
    FUncion que checkea si la maquina tiene los datos para ser procesado
 */
function checkMaquinaIsOk()
{
    //Primero verifico que me haya pasado los datos
    let maquina_control = $("#maquina");

    //et groups_control = $("#worksgroups_idworksgroups");
    let fecha_inicio_control = $("#fecha_inicio");
    let fecha_final_control = $("#fecha_final");
    let lotes_idlotes_control = $("#lotes_idlotes");
    let parcelas_idparcelas_control = $("#parcela");
    let propietarios_idpropietarios_control = $("#propietarios_idpropietarios");
    let destinos_iddestinos_control = $("#destinos_iddestinos");

    //OBtengo los valores
    //Creo las variables
    let maquina = maquina_control.val();
    let fecha_inicio = fecha_inicio_control.val();
    let fecha_final = fecha_final_control.val();
    let lotes = lotes_idlotes_control.val();
    let parcelas = parcelas_idparcelas_control.val();
    let propietarios = propietarios_idpropietarios_control.val();
    let destinos = destinos_iddestinos_control.val();


    let variables = {'maquina' : maquina, 'fecha_inicio' : fecha_inicio, 'fecha_final' : fecha_final,
        'lotes' : lotes, 'propietarios' : propietarios, 'destinos' : destinos, 'parcelas' : parcelas};


    var a = $.confirm({
        theme: 'supervan',
        lazyOpen: true,
        closeIcon: false,
        type: 'blue',
        typeAnimated: true,
        icon: 'fa fa-spinner fa-spin',
        title: 'Procesando!',
        content: 'Estamos verificando los datos de la Máquina, por favor espere!',
        buttons:false

    });


    //Verifico que todos los campos esten completos
    if(!checkAllInpustOk(maquina, fecha_inicio, fecha_final, lotes, parcelas, propietarios, destinos)){

        $.confirm({
            icon: 'fas fa-exclamation-circle',
            title: '¡Advertencia!',
            content: 'Debe completar todos los campos para proceder!',
            type: 'red',
            typeAnimated: true,
            buttons: {
                close:
                    {
                        text: 'Aceptar',
                        btnClass: 'btn-red',
                        actions: function () {
                        }}
            }
        });


    } else {
        $.ajax({
            type: "POST",
            async: true,
            url: 'checkMaquinaIsOkeyToCostos',
            data: variables,

            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                //Debo mostrar un loading

                a.open();

            },
            success: function(data, textStatus){

                //controlo que el resultado no sea false
                //COnsulto si el informe es ok, y en virtud de ello consulto
                console.log(data);

                if(data.result){
                    let l = setTimeout(function (){
                        let res = a.close();

                        if(res) {
                            let is_not_ok = $.confirm({
                                icon: 'fas fa-exclamation-circle',
                                title: '¡Análisis completo!',
                                content: 'La Máquina puede ser analizada.',
                                type: 'green',
                                typeAnimated: true,
                                buttons: {
                                    close:
                                        {
                                            text: 'Aceptar',
                                            btnClass: 'btn-green',
                                            action: function() {

                                                $("#div_accept_btn").css("display", "block");
                                                $("#div_btn_check").css("display", "none");
                                            }
                                        }

                                }
                            });
                        }

                    }, 7000);
                } else {

                    let l = setTimeout(function (){
                        let res = a.close();

                        let is_not_ok = $.confirm({
                            icon: 'fas fa-exclamation-circle',
                            title: '¡Error!',
                            content: 'La Máquina no puede ser analizada.',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                close:
                                    {
                                        text: 'Aceptar',
                                        btnClass: 'btn-red',
                                        function() {
                                        }
                                    }

                            }
                        });
                    }, 7000);



                }


            },
            error: function (data, textStatus) {

                let l = setTimeout(function (){
                    let res = a.close();

                    if(res) {
                        let is_not_ok = $.confirm({
                            icon: 'fas fa-exclamation-circle',
                            title: '¡Error!',
                            content: 'La Máquina no puede ser analizada.',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                close:
                                    {
                                        text: 'Aceptar',
                                        btnClass: 'btn-red',
                                        function() {
                                        }
                                    }

                            }
                        });
                    }

                }, 7000);
            },
            complete: function (data) {

            }


        });
    }



}


function checkAllInpustOk(maquina, fecha_inicio, fecha_final, lotes, parcelas, propietarios, destinos)
{

    if(maquina === '' || fecha_inicio === '' || fecha_final === '' ||
        lotes === '' || parcelas === '' || propietarios === '' || destinos === '' ||
        maquina == null || fecha_inicio == null || fecha_final == null ||
        lotes == null || parcelas == null || propietarios == null || destinos == null ||
        maquina === 'null' || fecha_inicio === 'null' || fecha_final === 'null' ||
        lotes === 'null' || parcelas === 'null' || propietarios === 'null' || destinos === 'null' ||
        maquina === 'undefined' || fecha_inicio === 'undefined' || fecha_final === 'undefined' ||
        lotes === 'undefined' || parcelas === 'undefined' || propietarios === 'undefined' || destinos === 'undefined'){

        return false;
    }
    return true;
}

function checkAllInpustOkResumen(maquina, fecha_inicio, fecha_final)
{

    if(maquina === '' || fecha_inicio === '' || fecha_final === '' ||
        maquina == null || fecha_inicio == null || fecha_final == null ||
        maquina === 'null' || fecha_inicio === 'null' || fecha_final === 'null' ||
        maquina === 'undefined' || fecha_inicio === 'undefined' || fecha_final === 'undefined'){

        return false;
    }
    return true;
}

function changeStyleBtns() {
    //Habilito el boton POST
    $("#div_accept_btn").css("display", "block");
    $("#div_btn_check").css("display", "none");
}



function resumeArreglos()
{
    let fecha_inicio_control = $("#fecha_inicio");
    let fecha_final_control = $("#fecha_final");
    let maquinas_idmaquinas_control = $("#maquinas_idmaquinas");




    //OBtengo los valores
    //Creo las variables
    let maquina = maquinas_idmaquinas_control.val();
    let fecha_inicio = fecha_inicio_control.val();
    let fecha_final = fecha_final_control.val();


    if(!checkAllInpustOkResumen(maquina, fecha_inicio, fecha_final))
    {
        $.confirm({
            icon: 'fas fa-exclamation-circle',
            title: '¡Advertencia!',
            content: 'Debe completar todos los campos para proceder!',
            type: 'red',
            typeAnimated: true,
            buttons: {
                close:
                    {
                        text: 'Aceptar',
                        btnClass: 'btn-red',
                        actions: function () {
                        }}
            }
        });
    } else {

        var a = $.confirm({
            theme: 'supervan',
            lazyOpen: true,
            closeIcon: false,
            type: 'blue',
            typeAnimated: true,
            icon: 'fa fa-spinner fa-spin',
            title: 'Procesando!',
            content: 'Obteniendo resumen de arreglos mecanicos!',
            buttons:false

        });


        //Proceso a informacion
        let variables = {
          "maquina" : maquina,
          "fecha_inicio" : fecha_inicio,
          "fecha_fin" : fecha_final
        };

        $.ajax({
            type: "POST",
            async: true,
            url: 'getResumenByMaquina',
            data: variables,

            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                //Debo mostrar un loading

                a.open();

            },
            success: function(data, textStatus){

                //console.log(data);

                if(data.result != false)
                {

                    let l = setTimeout(function () {
                        let res = a.close();
                        addElementoToTableResumen(data.result.mano_obra, data.result.total_repuestos,
                            data.result.total);
                    },  5000);


                }

            },
            error: function (data, textStatus) {

                let l = setTimeout(function (){
                    let res = a.close();

                    if(res) {
                        let is_not_ok = $.confirm({
                            icon: 'fas fa-exclamation-circle',
                            title: '¡Error!',
                            content: 'La Máquina no puede ser analizada.',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                close:
                                    {
                                        text: 'Aceptar',
                                        btnClass: 'btn-red',
                                        function() {
                                        }
                                    }

                            }
                        });
                    }

                }, 7000);
            },
            complete: function (data) {
            }

        });

    }

}

function addElementoToTableResumen(mano_obra, repuesto, total)
{
    let table = $('#tabladata').DataTable();
    let number_count = table.rows().count();
    //console.log(number_count);

    table.row(0)
        .remove()
        .draw();
    /*if(number_count > 0){
        table.row(1)
            .remove()
            .draw();
    }*/

    let trDOM = table.row.add([mano_obra, repuesto, total] ).draw().node();
    $( trDOM ).addClass('dt-center');

}

