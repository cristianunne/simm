


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

            console.log(data);
            loadDataToCostosMaquinaView(data, element);

        },
        error: function (data) {
        }
    });

}

function loadDataToCostosMaquinaView(data, element) {

    let maq_name = $("#maq_name");
    let grupo_name = $("#grupo_name");
    let centro_costo_name = $("#centro_costo_name");
    let met_costo_name = $("#met_costo_name");


    maq_name.empty();
    grupo_name.empty();
    centro_costo_name.empty();
    met_costo_name.empty();


    let val_adq = $("#val_adq");
    let vida_util = $("#vida_util");
    let horas_tot = $("#horas_tot");
    let horas_men = $("#horas_men");
    let tasa_int = $("#tasa_int");
    let cons_lit_h = $("#cons_lit_h");
    let alquiler = $("#alquiler");

    val_adq.empty();
    vida_util.empty();
    horas_tot.empty();
    horas_men.empty();
    tasa_int.empty();
    cons_lit_h.empty();
    alquiler.empty();


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
    centro_costo_name.html(data[0].centros_costo.name.toString());
    met_costo_name.html(data[0].metod_costo.name.toString());

    val_adq.html(data[0].val_adq);
    vida_util.html(data[0].vida_util);
    horas_tot.html(data[0].horas_total_uso);
    horas_men.html(data[0].horas_mens_uso);
    tasa_int.html(data[0].tasa_int_simple);
    cons_lit_h.html(data[0].consumo);
    alquiler.html(data[0].costo_alquiler);

    val_neum.html(data[0].val_neum);
    vida_util_neum.html(data[0].vida_util_neum);
    //ES hora efectivas
    horas_uso_anual.html(data[0].horas_efec_uso);
    horas_uso_dia.html(data[0].horas_dia_uso);
    fat_corr.html(data[0].factor_cor);
    coef_arr_mec.html(data[0].coef_err_mec);
    lubricante.html(data[0].lubricante);


    showModalAll(element)
}

