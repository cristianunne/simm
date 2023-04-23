
let myChart = null;
$(function (){
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
});

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



function graficar()
{

    /*var ctx = document.getElementById('myChart').getContext('2d');
    if (myChart) {
        myChart.destroy();
    }


    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["14","2","3345","4","5", "6", "7", "8", "95", "10"],
            datasets: [{
                label : 'title',
                data : [65,59,90,841,56,45,30,240,3,37],
                borderColor: '#d70009',
                fill: false,
                tension: 0.3
            }]
        },
        options: {
            plugins: {
                legend: {
                    title: {
                        display: true,
                        text: '',
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

    });*/

    let fecha_inicio_control = $("#fecha_inicio");
    let fecha_inicio = fecha_inicio_control.val();
    console.log(fecha_inicio);

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

    let variables_grupos =
        {'groups' : groups, 'fecha_inicio' : fecha_inicio, 'fecha_final' : fecha_final,
        'lotes' : lotes, 'destinos' : destinos, 'parcelas' : parcelas};

    let variables_maquinas =
        {'maquinas' : maquinas, 'fecha_inicio' : fecha_inicio, 'fecha_final' : fecha_final,
            'lotes' : lotes, 'destinos' : destinos, 'parcelas' : parcelas};



    if (validateGroupsAndMaquina(radio_bton, groups_control, maquinas_control))
    {
        //Verifico los demas campos
        if(validateOtherCompleteFields(fecha_inicio_control, fecha_final_control, lotes_idlotes_control, parcelas_idparcelas_control,
            destinos_iddestinos_control)){


            //Proceso la peticion segun la opcion

            switch (option_selected) {

                case 1:
                    break;

                case 2: getGroupsToneladasExtraidas(variables_grupos);
                    break;

                case 5: getToneladasExtraidasMaquinas(variables_maquinas);
                    break;
                case 6: getHorasTrabajadasMaquinas(variables_maquinas);
                    break;
                default:
                    break;

            }

        }


    }


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
            drawGraphic(data, 'Toneladas')

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
            drawGraphic(data, 'Toneladas')

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
            drawGraphic(data, 'Horas Trabajadas')

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
