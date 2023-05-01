
let myChart = null;
/*$(function (){
    const ctx = document.getElementById('myChart');

    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["1","2","3","4","5", "6", "7", "8", "9", "10"],
            datasets: [{
                label: '# of Votes',
                data : [65,59,90,81,56,45,30,20,3,37],
                borderColor: '#36A2EB',
                fill: false,
                tension: 0.3
            }]
        },
        options: {
            plugins: {
                legend: {
                    title: {
                        display: true,
                        text: 'cualqueir cosa',
                    },
                    position: 'right'
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        },

    });
});*/

function selectTypeOfVariacion(button)
{

    //si es 1 o 2 corresponde a grupos suno son maquinas

    if($(button).val() <= 2 ){

        $("#grupos").prop('disabled', false);
        $("#maquinas").prop('disabled', true);
    } else {

        $("#grupos").prop('disabled', true);
        $("#maquinas").prop('disabled', false);

    }

}




function graficarVariacion()
{
    //Verifico cual de los radio buttons esta seleccionado
    let radio_bton =  $('input[name=evolucion]:checked', '#rb-evolucion div').val()

    //Verifico si tengo seleccionado un grupo o una maquina
    //Primero verifico que me haya pasado los datos
    let groups_control = $("#grupos");
    let maquinas_control = $("#maquinas");
    let fecha_inicio_control = $("#fecha_inicio");
    let fecha_final_control = $("#fecha_final");
    let lotes_idlotes_control = $("#lotes_idlotes");
    let parcelas_idparcelas_control = $("#parcela");
    let destinos_iddestinos_control = $("#destinos_iddestinos");


    //Obtengo los datos
    let groups = groups_control.val();
    let maquinas = maquinas_control.val();
    let fecha_inicio = fecha_inicio_control.val();
    let fecha_final = fecha_final_control.val();
    let lotes = lotes_idlotes_control.val();
    let parcelas = parcelas_idparcelas_control.val();
    let destinos = destinos_iddestinos_control.val();

    let option_selected = Number.parseInt(radio_bton, 10);

    if (validateGroupsAndMaquina(radio_bton, groups_control, maquinas_control)) {
        //Verifico los demas campos
        if (validateOtherCompleteFields(fecha_inicio_control, fecha_final_control, lotes_idlotes_control, parcelas_idparcelas_control,
            destinos_iddestinos_control)) {

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
                                variables_grupos =
                                    {
                                        'groups': groups, 'fecha_inicio': fecha_inicio, 'fecha_final': fecha_final,
                                        'lotes': lotes, 'destinos': destinos, 'parcelas': parcelas, 'informe': true
                                    };

                                variables_maquinas =
                                    {
                                        'maquinas': maquinas, 'fecha_inicio': fecha_inicio, 'fecha_final': fecha_final,
                                        'lotes': lotes, 'destinos': destinos, 'parcelas': parcelas, 'informe': true
                                    };

                                switch (option_selected) {

                                    case 1:
                                        getGroupsCostosExtraidas(variables_grupos);
                                        break;

                                    case 2:
                                        getGroupsToneladasExtraidas(variables_grupos);
                                        break;

                                    case 3:
                                        getCostoToneladasMaquinas(variables_maquinas);
                                        break;

                                    case 4:
                                        getCostoHorasMaquinas(variables_maquinas);
                                        break;

                                    case 5:
                                        getToneladasExtraidasMaquinas(variables_maquinas);
                                        break;
                                    case 6:
                                        getHorasTrabajadasMaquinas(variables_maquinas);
                                        break;
                                    case 7:
                                        getCostoRendimientoMaquinas(variables_maquinas);
                                        break;
                                    default:
                                        break;

                                }
                            }

                        },
                    cancel:
                        {
                            text: 'Cancelar',
                            btnClass: 'btn-red',
                            action: function () {

                                variables_grupos =
                                    {
                                        'groups': groups, 'fecha_inicio': fecha_inicio, 'fecha_final': fecha_final,
                                        'lotes': lotes, 'destinos': destinos, 'parcelas': parcelas, 'informe': false
                                    };

                                variables_maquinas =
                                    {
                                        'maquinas': maquinas, 'fecha_inicio': fecha_inicio, 'fecha_final': fecha_final,
                                        'lotes': lotes, 'destinos': destinos, 'parcelas': parcelas, 'informe': false
                                    };

                                switch (option_selected) {

                                    case 1:
                                        getGroupsCostosExtraidas(variables_grupos);
                                        break;

                                    case 2:
                                        getGroupsToneladasExtraidas(variables_grupos);
                                        break;

                                    case 3:
                                        getCostoToneladasMaquinas(variables_maquinas);
                                        break;

                                    case 4:
                                        getCostoHorasMaquinas(variables_maquinas);
                                        break;

                                    case 5:
                                        getToneladasExtraidasMaquinas(variables_maquinas);
                                        break;
                                    case 6:
                                        getHorasTrabajadasMaquinas(variables_maquinas);
                                        break;
                                    case 7:
                                        getCostoRendimientoMaquinas(variables_maquinas);
                                        break;
                                    default:
                                        break;

                                }
                            }
                        }
                }

            });


        }
    }
}



function graficarVariacion_(result)
{

    //Verifico cual de los radio buttons esta seleccionado
    let radio_bton =  $('input[name=evolucion]:checked', '#rb-evolucion div').val()

    //Verifico si tengo seleccionado un grupo o una maquina
    //Primero verifico que me haya pasado los datos
    let groups_control = $("#grupos");
    let maquinas_control = $("#maquinas");
    let fecha_inicio_control = $("#fecha_inicio");
    let fecha_final_control = $("#fecha_final");
    let lotes_idlotes_control = $("#lotes_idlotes");
    let parcelas_idparcelas_control = $("#parcela");
    let destinos_iddestinos_control = $("#destinos_iddestinos");


    //Obtengo los datos
    let groups = groups_control.val();
    let maquinas = maquinas_control.val();
    let fecha_inicio = fecha_inicio_control.val();
    let fecha_final = fecha_final_control.val();
    let lotes = lotes_idlotes_control.val();
    let parcelas = parcelas_idparcelas_control.val();
    let destinos = destinos_iddestinos_control.val();

    let option_selected = Number.parseInt(radio_bton, 10);

    let variables_grupos = null;
    let variables_maquinas = null;

    //onsulto si quiere informe
    if(result){
        variables_grupos =
            {'groups' : groups, 'fecha_inicio' : fecha_inicio, 'fecha_final' : fecha_final,
                'lotes' : lotes, 'destinos' : destinos, 'parcelas' : parcelas, 'informe' : true};

        variables_maquinas =
            {'maquinas' : maquinas, 'fecha_inicio' : fecha_inicio, 'fecha_final' : fecha_final,
                'lotes' : lotes, 'destinos' : destinos, 'parcelas' : parcelas, 'informe' : true};
    } else {
        variables_grupos =
            {'groups' : groups, 'fecha_inicio' : fecha_inicio, 'fecha_final' : fecha_final,
                'lotes' : lotes, 'destinos' : destinos, 'parcelas' : parcelas, 'informe' : false};

        variables_maquinas =
            {'maquinas' : maquinas, 'fecha_inicio' : fecha_inicio, 'fecha_final' : fecha_final,
                'lotes' : lotes, 'destinos' : destinos, 'parcelas' : parcelas, 'informe' : false};
    }




    if (validateGroupsAndMaquina(radio_bton, groups_control, maquinas_control))
    {
        //Verifico los demas campos
        if(validateOtherCompleteFields(fecha_inicio_control, fecha_final_control, lotes_idlotes_control, parcelas_idparcelas_control,
            destinos_iddestinos_control)){


            //Proceso la peticion segun la opcion

            switch (option_selected) {

                case 1: getGroupsCostosExtraidas(variables_grupos);
                    break;

                case 2: getGroupsToneladasExtraidas(variables_grupos);
                    break;

                case 3: getCostoToneladasMaquinas(variables_maquinas);
                    break;

                case 4: getCostoHorasMaquinas(variables_maquinas);
                    break;

                case 5: getToneladasExtraidasMaquinas(variables_maquinas);
                    break;
                case 6: getHorasTrabajadasMaquinas(variables_maquinas);
                    break;
                case 7: getCostoRendimientoMaquinas(variables_maquinas);
                    break;
                default:
                    break;

            }

        }


    }


}

function generateInforme()
{
    let result = null;
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
                        result = true;
                    }

                }
        },
        cancel:
            {
                text: 'Cancelar',
                btnClass: 'btn-red',
                action: function () {

                    result = false;
                }
            }

    });

    return result;
}

function getGroupsCostosExtraidas(variables_grupos)
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
        url: 'variaciones/getCostosToneladasGrupos',
        data: variables_grupos,

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            //Debo mostrar un loading
            a.open();
        },
        success: function(data, textStatus){

            //controlo que el resultado no sea false
            console.log(data[0].costos);
            //console.log(data[0].informe.informe);
            setTimeout(function (){
                a.close();
                drawGraphic(data[0].costos, '$/t');
                setTitleGraphic('del Costo por Tonelada (Grupo)');

                if(data[0].informe)
                {
                    //DEbo verificar que el infrome este en true
                    if(data[0].informe !== undefined && data[0].informe !== null) {

                        if(data[0].informe.informe === true){
                            let id_informe = data[0].informe.id;
                            $("#down_informe").css({"display": "block"});
                            $("#down_informe").attr('attr', data[0].informe.path);


                        }

                    }
                }

            }, 7000);


        },
        error: function (data, textStatus) {

            a.close();
            console.log(data);
        }
    });

}

function getGroupsToneladasExtraidas(variables_grupos)
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
        url: 'variaciones/getToneladasExtraidasGrupos',
        data: variables_grupos,

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            //Debo mostrar un loading
            a.open();
        },
        success: function(data, textStatus){

            //controlo que el resultado no sea false
            console.log(data);
            setTimeout(function (){
                a.close();
                drawGraphic(data[0].costos, 'Toneladas')
                setTitleGraphic('de la Producción en Toneladas (Grupo)');

                if(data[0].informe)
                {
                    //DEbo verificar que el infrome este en true
                    if(data[0].informe !== undefined && data[0].informe !== null) {

                        if(data[0].informe.informe === true){
                            let id_informe = data[0].informe.id;
                            $("#down_informe").css({"display": "block"});
                            $("#down_informe").attr('attr', data[0].informe.path);


                        }

                    }
                }

            }, 7000);


        },
        error: function (data, textStatus) {

            a.close();
            console.log(data);
        }
    });

}


function getCostoRendimientoMaquinas(maquina)
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
        url: 'variaciones/getCostoMaquinaRendimiento',
        data: maquina,

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            //Debo mostrar un loading
            a.open();
        },
        success: function(data, textStatus){

            //controlo que el resultado no sea false
            console.log(data);

            setTimeout(function (){
                a.close();
                drawGraphic(data[0].costos, 't/h');
                setTitleGraphic('del Rendimiento en Toneladas/horas (Maquina)');

                if(data[0].informe)
                {
                    //DEbo verificar que el infrome este en true
                    if(data[0].informe !== undefined && data[0].informe !== null) {

                        if(data[0].informe.informe === true){
                            let id_informe = data[0].informe.id;
                            $("#down_informe").css({"display": "block"});
                            $("#down_informe").attr('attr', data[0].informe.path);


                        }

                    }
                }

            }, 7000);


        },
        error: function (data, textStatus) {

            a.close();
            console.log(data);
        }
    });

}

function getCostoToneladasMaquinas(maquina)
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
        url: 'variaciones/getCostoMaquinaTonelada',
        data: maquina,

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            //Debo mostrar un loading
            a.open();
        },
        success: function(data, textStatus){

            //controlo que el resultado no sea false
            console.log(data);

            setTimeout(function (){
                a.close();
                drawGraphic(data[0].costos, '$/t');
                setTitleGraphic('del Costo por Toneladas (Maquina)');

                if(data[0].informe)
                {
                    //DEbo verificar que el infrome este en true
                    if(data[0].informe !== undefined && data[0].informe !== null) {

                        if(data[0].informe.informe === true){
                            let id_informe = data[0].informe.id;
                            $("#down_informe").css({"display": "block"});
                            $("#down_informe").attr('attr', data[0].informe.path);


                        }

                    }
                }


            }, 7000);


        },
        error: function (data, textStatus) {

            a.close();
            console.log(data);
        }
    });

}


function getCostoHorasMaquinas(maquina)
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
        url: 'variaciones/getCostoMaquinaHora',
        data: maquina,

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            //Debo mostrar un loading
            a.open();
        },
        success: function(data, textStatus){

            //controlo que el resultado no sea false
            console.log(data);


            setTimeout(function (){
                a.close();
                drawGraphic(data[0].costos, '$/h')
                setTitleGraphic('del Costo por Hora (Maquina)');

                if(data[0].informe)
                {
                    //DEbo verificar que el infrome este en true
                    if(data[0].informe !== undefined && data[0].informe !== null) {

                        if(data[0].informe.informe === true){
                            let id_informe = data[0].informe.id;
                            $("#down_informe").css({"display": "block"});
                            $("#down_informe").attr('attr', data[0].informe.path);


                        }

                    }
                }

            }, 7000);

        },
        error: function (data, textStatus) {

            a.close();
            console.log(data);
        }
    });

}

function getToneladasExtraidasMaquinas(maquina)
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
        url: 'variaciones/getToneladasExtraidasMaquinas',
        data: maquina,

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            //Debo mostrar un loading
            a.open();
        },
        success: function(data, textStatus){

            //controlo que el resultado no sea false
            console.log(data);

            setTimeout(function (){
                a.close();
                drawGraphic(data[0].costos, 'Toneladas')

                setTitleGraphic('de la Producción en Tonelada (Maquina)');


                if(data[0].informe)
                {
                    //DEbo verificar que el infrome este en true
                    if(data[0].informe !== undefined && data[0].informe !== null) {

                        if(data[0].informe.informe === true){
                            let id_informe = data[0].informe.id;
                            $("#down_informe").css({"display": "block"});
                            $("#down_informe").attr('attr', data[0].informe.path);


                        }

                    }
                }


            }, 7000);


        },
        error: function (data, textStatus) {

            a.close();
            console.log(data);
        }
    });

}


function getHorasTrabajadasMaquinas(maquina)
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
        url: 'variaciones/getHorasTrabajadasMaquinas',
        data: maquina,

        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            //Debo mostrar un loading
            a.open();
        },
        success: function(data, textStatus){

            //controlo que el resultado no sea false
            console.log(data);
            setTimeout(function (){
                a.close();
                drawGraphic(data[0].costos, 'Horas Trabajadas')

                setTitleGraphic('de la Horas Trabajadas (Maquina)');

                if(data[0].informe)
                {
                    //DEbo verificar que el infrome este en true
                    if(data[0].informe !== undefined && data[0].informe !== null) {

                        if(data[0].informe.informe === true){
                            let id_informe = data[0].informe.id;
                            $("#down_informe").css({"display": "block"});
                            $("#down_informe").attr('attr', data[0].informe.path);


                        }

                    }
                }


            }, 7000);


        },
        error: function (data, textStatus) {

            a.close();
            console.log(data);
        }
    });
}


function setTitleGraphic(title)
{
    let titulo = 'Variación ' + title.toString();
    $("#title_graphic").text(titulo);
}

function validateGroupsAndMaquina(radio_bton, groups_control, maquinas_control)
{
    if(radio_bton <= 2)
    {
        if(groups_control.val() === '' )
        {
            $.confirm({
                icon: 'fas fa-exclamation-circle',
                title: '¡Advertencia!',
                content: 'Debe seleccionar un Grupo!',
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

            return false;
        }
        return true;

    } else {
        if(maquinas_control.val() === '' )
        {
            $.confirm({
                icon: 'fas fa-exclamation-circle',
                title: '¡Advertencia!',
                content: 'Debe seleccionar una Maquina!',
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
            return false;
        }
        return true;
    }
}


function validateOtherCompleteFields(fecha_inicio_control, fecha_final_control, lotes_idlotes_control, parcelas_idparcelas_control,
                                     destinos_iddestinos_control)
{


    if(fecha_inicio_control.val() === '')
    {
        showMsjError('Fecha de Inicio');

        return false;
    }

    if(fecha_final_control.val() === '')
    {
        showMsjError('Fecha Final');

        return false;
    }


    if(lotes_idlotes_control.val() === '')
    {
        showMsjError('Lotes');

        return false;
    }

    if(parcelas_idparcelas_control.val() === '' || parcelas_idparcelas_control.val() === 'null' || parcelas_idparcelas_control.val() === undefined)
    {
        showMsjError('Parcelas');

        return false;
    }

    if(destinos_iddestinos_control.val() === '')
    {
        showMsjError('Destinos');

        return false;
    }

    return true;


}

function showMsjError(categoria)
{
    $.confirm({
        icon: 'fas fa-exclamation-circle',
        title: '¡Advertencia!',
        content: 'Debe seleccionar ' + categoria + '!',
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


function drawGraphic(data, titulo)
{
    var ctx = document.getElementById('myChart').getContext('2d');
    if (myChart) {
        console.log("entro, tiene que destruir el chart");
        myChart.destroy();
    }

    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data[0].labels,
            datasets: data[0].datasets
        },
        options: {
            plugins: {
                legend: {
                    title: {
                        display: true,
                        //NOmbre de la GRaficacion
                        text: titulo,
                    },
                    position: 'right'
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        },

    });

}


function removeData() {
    chart.data.labels.pop();
    chart.data.datasets.forEach((dataset) => {
        dataset.data.pop();
    });
    chart.update();
}
