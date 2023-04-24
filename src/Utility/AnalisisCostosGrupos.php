<?php

namespace App\Utility;

use Cake\ORM\TableRegistry;

class AnalisisCostosGrupos
{



    public function analisisDeCostosGrupos($array_options, $id_empresa)
    {

        //Instancio la clase GetFUnctions
        $get_functions = new GetFunctions();

        $meses_years = $get_functions->getMonthsAndYearsWithLast($array_options);

        $data_organized_by_month = [];

        foreach ($meses_years as $meses_year)
        {
            $mes = $meses_year['mes'];
            $year = $meses_year['year'];
            //devuelve los resultados globales y las constantes para las maquinas
            $maquinas_with_general_constantes = $this->calculateCostosByMonth($mes, $year, $array_options, $id_empresa);

            //debug($maquinas_with_general_constantes);
            $data_organized_by_month[] = $maquinas_with_general_constantes;

        }

        //debug($data_organized_by_month);

        if(empty($data_organized_by_month[0]['maquinas']) == false)
        {
            //debug($data_organized_by_month);
            //Obtengo las maquinas distinct
            $maquinas_distinct = $get_functions->getMaquinasDistinct($data_organized_by_month);


            //utilizando la media ponderada
            $maquinas_resume = $this->resumeCostosHorariosByMaquina($data_organized_by_month, $maquinas_distinct);
            //debug($maquinas_resume);

            $maquinas_resume_new =  $this->calculateCostosFijosYVariables($maquinas_resume);


            $centros_costos_array = $get_functions->getCentroCostosDistinct($maquinas_resume_new);

            $centros_costos = $get_functions->getCentrosCostrosByArray($centros_costos_array);



            $maquinas_by_centros_costos['general'] = $this->resumeGeneralData($data_organized_by_month, $maquinas_distinct);

            $maquinas_by_centros_costos['general']['precio_servicio'] = $this->precioServicioGeneral($data_organized_by_month);
            //debug($maquinas_by_centros_costos);

            //AGrego la informaci[on de costos a resumen
            $maquinas_by_centros_costos['general']['costos_suma'] = $this->resumeGeneralDataCostos($maquinas_resume_new);



            $maquinas_by_centros_costos['centros'] = $this->resumeDataMaquinasByCentroCostos($maquinas_resume_new, $centros_costos, $maquinas_by_centros_costos['general']);
            //debug($maquinas_by_centros_costos);
            //ACa deberia mandarlo a que calcule el costo total
            $maquinas_by_centros_costos = $this->calculateCostoTotal($maquinas_by_centros_costos);

            $maquinas_by_centros_costos['general']['mai']['economico'] = $this->calculateMAIEconomico($maquinas_by_centros_costos);
            $maquinas_by_centros_costos['general']['mai']['financiero'] = $this->calculateMAIFinanciero($maquinas_by_centros_costos);

            $maquinas_by_centros_costos['general']['categorias']['costos'] = $this->calculateCostosByCategoria($maquinas_by_centros_costos);

            //ordeno los datos por elaboracion y transporte
            $maquinas_by_centros_costos['general']['categorias']['precio']['elaboracion'] = $this->precioServicioByCategory($data_organized_by_month,
            'Elaboracion');

            $maquinas_by_centros_costos['general']['categorias']['precio']['transporte'] = $this->precioServicioByCategory($data_organized_by_month,
                'Transporte');

            $maquinas_by_centros_costos['general']['categorias']['mai']['elaboracion'] = $this->calculateMAIElaboracionTransporte($maquinas_by_centros_costos, 'Elaboracion');

            $maquinas_by_centros_costos['general']['categorias']['mai']['transporte'] = $this->calculateMAIElaboracionTransporte($maquinas_by_centros_costos, 'Transporte');

            return $maquinas_by_centros_costos;
        }

        //Como no hay datos de maquinas devuelvo null
        return null;

    }


    public function appliedCostosMetodologyEval($maquina_with_data, $id_empresa)
    {
        //Traigo las constantes
        $constantes_model = TableRegistry::getTableLocator()->get('Constantes');

        $constantes = $constantes_model->find('list', [
            'keyField' => 'name',
            'valueField' => 'value'
        ])
            ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
            ->toArray();

        //Preparo las constantes a utilizar
        $CSE = NULL; $CVD = NULL; $AME = NULL; $CMA = NULL; $CAD = NULL;

        if(isset($constantes['CSE'])){
            $CSE = $constantes['CSE'];
        }
        if(isset($constantes['CVD'])){
            $CVD = $constantes['CVD'];
        }
        if(isset($constantes['AME'])){
            $AME = $constantes['AME'];
        }
        if(isset($constantes['CMA'])){
            $CMA = $constantes['CMA'];
        }

        if(isset($constantes['CAD'])){
            $CAD = $constantes['CAD'];
        }


        //DEfino nuevamente las variables
        $VAD = null;
        $VUN = null;
        $HTU = null;
        $HME = null;
        $TIS = null;
        $FCI = null;
        $VAN = null;
        $HFU = null;
        $VUE = null;
        $CCT = null;
        $CTL = null;
        $COM = null;
        $COH = null;
        $LUB = null;
        $LUH = null;
        $SAL = null;
        $AME = null;


        //Cargo los valores de las variables
        $VAD = $maquina_with_data['constantes']['VAD'];
        $VUN = $maquina_with_data['constantes']['VUN'];
        $HTU = $maquina_with_data['constantes']['HTU'];
        $HME = $maquina_with_data['constantes']['HME'];
        $TIS = $maquina_with_data['constantes']['TIS'];
        $FCI = $maquina_with_data['constantes']['FCI'];
        $VAN = $maquina_with_data['constantes']['VAN'];
        $HFU = $maquina_with_data['constantes']['HFU'];
        $VUE = $maquina_with_data['constantes']['VUE'];
        $CCT = $maquina_with_data['constantes']['CCT'];
        $CLT = $maquina_with_data['constantes']['CLT'];
        $COM = $maquina_with_data['constantes']['COM'];
        $COH = $maquina_with_data['constantes']['COH'];
        $LUB = $maquina_with_data['constantes']['LUB'];
        $LUH = $maquina_with_data['constantes']['LUH'];
        $SAL = $maquina_with_data['constantes']['SAL'];
        $AME = $maquina_with_data['constantes']['AME'];

        $tabla_metodcostos = TableRegistry::getTableLocator()->get('MetodCostos');

        $metod = $tabla_metodcostos->find('getMetodCostosByHash', ['hash' => $maquina_with_data['metod_costos']])
            ->first();


        $interes_for = $metod['interes'];
        $seguro_for = $metod['seguro'];
        $dep_maq_for = $metod['dep_maq'];
        $dep_neum_for = $metod['dep_neum'];
        $arreglos_maq_for = $metod['arreglos_maq'];
        $cons_comb_for = $metod['cons_comb'];
        $cons_lub_for = $metod['cons_lub'];
        $operador_for = $metod['operador'];
        $mantenimiento_for = $metod['mantenimiento'];
        $administracion_for = $metod['administracion'];



        $interes = 0;
        $seguro = 0;
        $dep_maq = 0;
        $dep_neum = 0;
        $arreglos_maq = 0;
        $cons_comb = 0;
        $cons_lub = 0;
        $operador = 0;
        $mantenimiento = 0;
        $administracion = 0;

        //Evaluo las formulas
        if(@eval('return '. $interes_for. ';') != null and @eval('return '. $interes_for. ';') != ''){
            $interes = @eval('return '.$interes_for.';');
            if(is_nan($interes)){
                $interes = null;
            }
        }

        if(@eval('return '. $seguro_for. ';') != null and @eval('return '. $seguro_for. ';') != ''){
            $seguro = @eval('return '.$seguro_for.';');
            if(is_nan($seguro)){
                $seguro = null;
            }
        }

        if(@eval('return '. $dep_maq_for. ';') != null and @eval('return '. $dep_maq_for. ';') != ''){
            $dep_maq = @eval('return '.$dep_maq_for.';');

            if(is_nan($dep_maq)){
                $dep_maq = null;
            }
        }

        if(@eval('return '. $dep_neum_for. ';') != null and @eval('return '. $dep_neum_for. ';') != ''){
            $dep_neum = @eval('return '.$dep_neum_for.';');
            if(is_nan($dep_neum)){
                $dep_neum = null;
            }
        }

        if(@eval('return '. $arreglos_maq_for. ';') != null and @eval('return '. $arreglos_maq_for. ';') != ''){
            $arreglos_maq = @eval('return '.$arreglos_maq_for.';');

            if(is_nan($arreglos_maq)){
                $arreglos_maq = null;
            }
        }

        if(@eval('return '. $cons_comb_for. ';') != null and @eval('return '. $cons_comb_for. ';') != ''){
            $cons_comb = @eval('return '.$cons_comb_for.';');

            if(is_nan($cons_comb)){
                $cons_comb = null;
            }
        }

        if(@eval('return '. $cons_lub_for. ';') != null and @eval('return '. $cons_lub_for. ';') != ''){
            $cons_lub = @eval('return '.$cons_lub_for.';');
            if(is_nan($cons_lub)){
                $cons_lub = null;
            }

        }

        if(@eval('return '. $operador_for. ';') != null and @eval('return '. $operador_for. ';') != ''){
            $operador = @eval('return '.$operador_for.';');

            if(is_nan($operador)){
                $operador = null;
            }
        }

        if(@eval('return '. $mantenimiento_for. ';') != null and @eval('return '. $mantenimiento_for. ';') != ''){
            $mantenimiento = @eval('return '.$mantenimiento_for.';');
            if(is_nan($mantenimiento)){
                $mantenimiento = null;
            }
        }

        if(@eval('return '. $administracion_for. ';') != null and @eval('return '. $administracion_for. ';') != ''){
            $administracion = @eval('return '.$administracion_for.';');


            if(is_nan($administracion)){
                $administracion = null;
            }
        }

        $maq = [
            "interes" => $interes,
            "seguro" => $seguro,
            "dep_maq" => $dep_maq,
            "dep_neum" => $dep_neum,
            "arreglos_maq" => $arreglos_maq,
            "cons_comb" => $cons_comb,
            "cons_lub" => $cons_lub,
            "operador" => $operador,
            "mantenimiento" => $mantenimiento,
            "administracion" => $administracion
        ];

        return $maq;

    }


    private function appliedCostosMetodology($maquina_with_data, $id_empresa)
    {

        $get_functions_class = new GetFunctions();
        //Traigo las constantes
        $constantes = $get_functions_class->getConstantesByEmpresa($id_empresa);

        //Preparo las constantes a utilizar
        $CSE = NULL; $CVD = NULL; $AME = NULL; $CMA = NULL; $CAD = NULL;

        if(isset($constantes['CSE'])){
            $CSE = $constantes['CSE'];
        }
        if(isset($constantes['CVD'])){
            $CVD = $constantes['CVD'];
        }

        if(isset($constantes['CMA'])){
            $CMA = $constantes['CMA'];
        }

        if(isset($constantes['CAD'])){
            $CAD = $constantes['CAD'];
        }

        //DEfino nuevamente las variables
        $VAD = null;
        $VUN = null;
        $HTU = null;
        $HME = null;
        $TIS = null;
        $FCI = null;
        $VAN = null;
        $HFU = null;
        $VUE = null;
        $CCT = null;
        $CTL = null;
        $COM = null;
        $COH = null;
        $LUB = null;
        $LUH = null;
        $SAL = null;

        $AME = null;

        //COnsulto si AME viene null de la maquina para asigar la constante

        ///REVISAR AME


        //Cargo los valores de las variables
        $VAD = $maquina_with_data['constantes']['VAD'];
        $VUN = $maquina_with_data['constantes']['VUN'];
        $HTU = $maquina_with_data['constantes']['HTU'];
        $HME = $maquina_with_data['constantes']['HME'];
        $TIS = $maquina_with_data['constantes']['TIS'];
        $FCI = $maquina_with_data['constantes']['FCI'];
        $VAN = $maquina_with_data['constantes']['VAN'];
        $HFU = $maquina_with_data['constantes']['HFU'];
        $VUE = $maquina_with_data['constantes']['VUE'];
        $CCT = $maquina_with_data['constantes']['CCT'];
        $CLT = $maquina_with_data['constantes']['CLT'];
        $COM = $maquina_with_data['constantes']['COM'];
        $COH = $maquina_with_data['constantes']['COH'];
        $LUB = $maquina_with_data['constantes']['LUB'];
        $LUH = $maquina_with_data['constantes']['LUH'];
        $SAL = $maquina_with_data['constantes']['SAL'];
        $AME = $maquina_with_data['constantes']['AME'];

        //traigo la clase y aplico la metodologia

        $metodologia_costo = new MetodologiaCostosFormula();

        $interes = $metodologia_costo->calculateInteres($VAD, $TIS, $FCI, $HTU);
        $seguro = $metodologia_costo->calculateSeguros($VAD, $CSE, $HTU);
        $dep_maq = $metodologia_costo->calculateDeprecacionMaquina($VAD, $CVD, $VAN, $HFU, $VUE);
        $dep_neum = $metodologia_costo->calculateDepreciacionNeumatico($VAN, $VUN);
        $arreglos_maq = $metodologia_costo->calculateArreglosMecanicos($AME, $HME);
        $cons_comb = $metodologia_costo->calculateConsumoCombustible($maquina_with_data['gastos']['gasto_combustible'], $HME);
        $cons_lub = $metodologia_costo->calculateConsumoLubricante($maquina_with_data['gastos']['gasto_lubricante'], $HME);
        $operador = $metodologia_costo->calculateOperador($SAL);
        $mantenimiento = $metodologia_costo->calculateMantenimiento($SAL, $CMA);
        $administracion = $metodologia_costo->calculateAdministracion($interes, $seguro, $dep_maq, $dep_neum, $arreglos_maq,
            $cons_comb, $cons_lub, $operador, $mantenimiento, $CAD);


        $maq = [
            "interes" => $interes,
            "seguro" => $seguro,
            "dep_maq" => $dep_maq,
            "dep_neum" => $dep_neum,
            "arreglos_maq" => $arreglos_maq,
            "cons_comb" => $cons_comb,
            "cons_lub" => $cons_lub,
            "operador" => $operador,
            "mantenimiento" => $mantenimiento,
            "administracion" => $administracion
        ];

        return $maq;

    }

    public function calculateCostosByMonth($mes, $year, $array_options, $id_empresa)
    {
        $result_by_month = [];
        $general_data = [];
        $maquinas_result = [];
        $general_total_ton = 0;
        $general_data['mes'] = $mes;
        $general_data['year'] = $year;

        //INstancio la clase getfunction
        $get_functions_class = new GetFunctions();


        //Arreglo no vaio, proceso
        if(count($array_options) > 0) {
            $array_options['mes'] = $mes;
            $array_options['year'] = $year;

            //OBtengo los remitos disponibles
            $array_remitos = $get_functions_class->getRemitosByConditions($array_options);
            $general_data['total_remitos'] = count($array_remitos);




            if(count($array_remitos) > 0){

                //Calculo los resultados globales, TONELADAS
                $tabla_remitos = TableRegistry::getTableLocator()->get('Remitos');
                $general_total_ton =  $tabla_remitos->find('GetTotalToneladas', $array_remitos);

                //TRaigo las maquinas que participan en el analisis
                $tabla_remitosmaq = TableRegistry::getTableLocator()->get('RemitosMaquinas');
                $tabla_maquinas = TableRegistry::getTableLocator()->get('Maquinas');

                //Variable con las maquinas utilizadas en los remitos filtrados
                $maquinas_array =  $tabla_remitosmaq->find('getMaquinasByRemitos', $array_remitos);

                $general_data['total_maquinas'] = count($maquinas_array);
                $general_data['toneladas'] = $general_total_ton;

                //TRaigo las maquinas que participan en el analisis
                $general_data['toneladas_categorias'] = $this->getToneladasByCategories($array_remitos, $mes, $year);

                //sumo las toneladas para cada categoria


                foreach ($maquinas_array as $maquina){

                    //La solicitud de informacion es por maquina
                    $maquina_data = $get_functions_class->getMaquinaById($maquina);

                    //TRaigo para esta maquina en especifico los datos
                    $remitos_by_maquina = $get_functions_class->getRemitosByMaquina($maquina, $array_options);
                    $remitos_array_distinct = $get_functions_class->getRemitosAsArrayDistinct($remitos_by_maquina);

                    //traigo los costos
                    $costos = $get_functions_class->getCostosByMaquina($maquina, $mes, $year)->toArray();

                    //Arreglos
                    $arreglos = $get_functions_class->getArreglosByMaquina($maquina, $array_options);

                    //Usos  EL uso tiene solo la parcela para filtrar
                    $uso_maquinaria = $get_functions_class->getUsoMaquinariaByMaquina($maquina, $array_options);

                    //TRaigo operarios
                    $operario_maq =  $get_functions_class->getOperarioByMaquina($maquina, $remitos_array_distinct);

                    //Traigo los operarios maquinas donde se encuentra los datos de sueldos
                    $operarios_maquina_data =  $get_functions_class->getOperariosMaquinasByOperAndRemito($operario_maq, $mes, $year);

                    $maquina_with_data = $this->calculateVariablesyConstantesByMaquina($maquina_data, $remitos_by_maquina, $costos, $arreglos,
                        $uso_maquinaria, $operarios_maquina_data);



                    //Aplico la metodologia de costos aqui y devuelvo ya con eso
                    $maquina_with_data['result_metod'] = $this->appliedCostosMetodology($maquina_with_data, $id_empresa);


                    $maquina_with_data['costos'] = $this->calculateCostosByHours($maquina_with_data);

                    //debug($maquina_with_data);
                    $maquinas_result[] = $maquina_with_data;

                }  //foreach maquina

            }
        }

        $result_by_month['general'] = $general_data;
        $result_by_month['maquinas'] = $maquinas_result;

        return $result_by_month;

    }

    private function calculateCostosByHours($maquina_with_data)
    {

        $costo_hora = null;
        $prod_rend_h = null;
        $costo_t = null;

        if($maquina_with_data['alquiler'])
        {
            $costo_hora = $maquina_with_data['precio_ton'];
        } else {
            //Calculo los valores a mostrar
            $costo_hora = $maquina_with_data['result_metod']['interes'] + $maquina_with_data['result_metod']['seguro'] +
                $maquina_with_data['result_metod']['dep_maq']  + $maquina_with_data['result_metod']['dep_neum'] +
                $maquina_with_data['result_metod']['arreglos_maq']  + $maquina_with_data['result_metod']['cons_comb']
                + $maquina_with_data['result_metod']['cons_lub'] + $maquina_with_data['result_metod']['operador'] +
                $maquina_with_data['result_metod']['mantenimiento'] + $maquina_with_data['result_metod']['administracion'];

        }


        $HME = $maquina_with_data['constantes']['HME'];



        if($HME > 0){
            $prod_rend_h = $maquina_with_data['toneladas'] / $HME;
        }

        if($prod_rend_h > 0){
            $costo_t = $costo_hora / $prod_rend_h;
        }


        $costos = [
            "costo_h" => $costo_hora,
            "prod_rend_h" => $prod_rend_h,
            "costo_ton" => $costo_t,
            "toneladas" => $maquina_with_data['toneladas'],
            "horas" => $HME
        ];

        return $costos;


    }

    public function calculateCostoTotal($maquinas_by_centros_costos)
    {

        $toneladas_global = $maquinas_by_centros_costos['general']['toneladas'];
        $suma_ponderada = 0;

        //debug($toneladas_global);

        //recorro los centros
        foreach ($maquinas_by_centros_costos['centros'] as $centro)
        {
            $ton_total_centro = $centro['toneladas_total'];
            $costo_total_centro = $centro['costo_total'];

            $suma_ponderada = $suma_ponderada + ($costo_total_centro * $ton_total_centro);

        }

        $res_final = $toneladas_global == 0 ? 0 : ($suma_ponderada / $toneladas_global);

        //((centro_costo * tonelada) + (CCn * t)) / ToneladasTotal


        $maquinas_by_centros_costos['general']['costo_total'] = $res_final;

        return $maquinas_by_centros_costos;

    }

    public function calculateMAIEconomico($maquinas_by_centros_costos)
    {

        $costos_mai_class = new CostosMai();
        $mai = null;
        $costo_total_general = $maquinas_by_centros_costos['general']['costo_total'];
        $precio_servicio = $maquinas_by_centros_costos['general']['precio_servicio'];

        $costo_variable = $maquinas_by_centros_costos['general']['costos_suma']['sum_costo_variable'];
        $costo_mantenimiento = $maquinas_by_centros_costos['general']['costos_suma']['sum_costo_mantenimiento'];
        $costo_administracion = $maquinas_by_centros_costos['general']['costos_suma']['sum_costo_administracion'];


        //obtengo el precio del servicio de elaboracion
        //el total de las toneladas es el global
        $mai = $costos_mai_class->calculateMAIEconomico($costo_total_general, $precio_servicio);


        return $mai;
    }

    public function calculateMAIFinanciero($maquinas_by_centros_costos)
    {
        $costos_mai_class = new CostosMai();
        $mai = null;

        $precio_servicio = $maquinas_by_centros_costos['general']['precio_servicio'];
        $costo_variable = $maquinas_by_centros_costos['general']['costos_suma']['sum_costo_variable'];
        $costo_mantenimiento = $maquinas_by_centros_costos['general']['costos_suma']['sum_costo_mantenimiento'];
        $costo_administracion = $maquinas_by_centros_costos['general']['costos_suma']['sum_costo_administracion'];


        //obtengo el precio del servicio de elaboracion
        //el total de las toneladas es el global
        $mai = $costos_mai_class->calculateMAIFinanciero($precio_servicio, $costo_variable, $costo_mantenimiento, $costo_administracion);


        return $mai;
    }



    /*
     * Resume todos los precios de los servicios
     */

    private function precioServicioGeneral($data_organized_by_month)
    {

        $get_function_class = new GetFunctions();
        //precio del servicio ponderado
        $toneladas = null;
        $precio_servicio = null;

        foreach ($data_organized_by_month as $date)
        {
            $ton = isset($date['general']['toneladas']) ? $date['general']['toneladas'] : 0;

            $toneladas = $toneladas + $ton;
            $mes = $date['general']['mes'];
            $year = $date['general']['year'];

            $precio = $get_function_class->getPrecioServicioByMonth($year, $mes, 'Elaboracion');

            $precio_servicio = $precio_servicio + ($precio * $ton);

        }

        $precio_servicio = $precio_servicio / $toneladas;

        return $precio_servicio;
    }

    private function precioServicioByCategory($data_organized_by_month, $categoria)
    {


        //debug($data_organized_by_month);
        $get_function_class = new GetFunctions();
        //precio del servicio ponderado
        $toneladas = null;
        $precio_servicio = null;

        foreach ($data_organized_by_month as $date)
        {
            $ton = $date['general']['toneladas_categorias']['tonelada_elaboracion'] ?? 0;
            $toneladas = $toneladas + $ton;
            $mes = $date['general']['mes'];
            $year = $date['general']['year'];

            $precio = $get_function_class->getPrecioServicioByMonth($year, $mes, $categoria);

            $precio_servicio = $precio_servicio + ($precio * $ton);

        }

        $precio_servicio = $precio_servicio / $toneladas;



        return $precio_servicio;
    }



    public function calculateCostosByCategoria($maquinas_by_centros_costos)
    {
        $get_functions_class = new GetFunctions();

        $elaboracion = $get_functions_class->getCostosByCategoria($maquinas_by_centros_costos['centros'],
        'Elaboracion');

        $transporte = $get_functions_class->getCostosByCategoria($maquinas_by_centros_costos['centros'],
            'Transporte');

        $result = ['elaboracion' => $elaboracion, 'transporte' => $transporte];

        return $result;

    }



    public function calculateMAIElaboracionTransporte($maquinas_by_centros_costos, $category)
    {

        $costos_mai_class = new CostosMai();
        $mai = null;

        if($category == 'Elaboracion')
        {
            $precio_servicio = $maquinas_by_centros_costos['general']['categorias']['precio']['elaboracion'];
            $costo = $maquinas_by_centros_costos['general']['categorias']['costos']['elaboracion'];
            //obtengo el precio del servicio de elaboracion
            //el total de las toneladas es el global
            $mai = $costos_mai_class->calculateMAIElaboracionTransporte($costo, $precio_servicio);
        } else {
            $precio_servicio = $maquinas_by_centros_costos['general']['categorias']['precio']['transporte'];
            $costo = $maquinas_by_centros_costos['general']['categorias']['costos']['transporte'];
            //obtengo el precio del servicio de elaboracion
            //el total de las toneladas es el global
            $mai = $costos_mai_class->calculateMAIElaboracionTransporte($costo, $precio_servicio);
        }



        return $mai;


    }

    public function getToneladasByCategories($array_remitos, $mes, $year)
    {
        $get_functions_class = new GetFunctions();
        $remitos_elaboracion = [];
        $remitos_transporte = [];

        //debug($array_remitos);

        //TRaigo las maquinas que participan en el analisis
        $tabla_remitosmaq = TableRegistry::getTableLocator()->get('RemitosMaquinas');


        foreach ($array_remitos as $remito)
        {
            $rem = null;
            $rem[$remito] = $remito;

            $maquinas_array = null;
            //TRaigo las maquinas para este remito
            //Variable con las maquinas utilizadas en los remitos filtrados
            $maquinas_array =  $tabla_remitosmaq->find('getMaquinasByRemitos', $rem);

            foreach ($maquinas_array as $maquina)
            {
                $costos = null;

                //traigo los costos
                $costos = $get_functions_class->getCostosByMaquina($maquina, $mes, $year)->toArray();

                if($costos[0]['centros_costos'][0]->categoria == 'Elaboracion'){
                    $remitos_elaboracion[$remito] = $remito;

                }

                if($costos[0]['centros_costos'][0]->categoria == 'Transporte'){
                    $remitos_transporte[$remito] = $remito;
                    //debug('pichilo');

                }

            }


        }

        $tabla_remitos = TableRegistry::getTableLocator()->get('Remitos');

        $toneladas_elaboracion = null;
        $toneladas_transporte = null;


        if(count($remitos_elaboracion) > 0)
        {
            $toneladas_elaboracion =  $tabla_remitos->find('GetTotalToneladas', $remitos_elaboracion);

        }

        if(count($remitos_transporte) > 0)
        {
            $toneladas_transporte =  $tabla_remitos->find('GetTotalToneladas', $remitos_transporte);

        }

        $result = [
            'tonelada_elaboracion' => $toneladas_elaboracion,
            'tonelada_transporte' => $toneladas_transporte
        ];

        return $result;
    }

    private function organizedDataByCategory($data_organized_by_month)
    {
        $data_organized_by_month_category = null;

        $general = null;
        $lista_elaboracion = null;
        $lista_transporte = null;

        foreach ($data_organized_by_month as $data)
        {
            $lista_aux = null;

            $general = $data['general'];

            //recorro las maquinas
            foreach ($data['maquinas'] as $maq)
            {
                if($maq['centro_costos'][0]->categoria == 'Elaboracion')
                {
                    $lista_elaboracion[] = $maq;
                } else {
                    $lista_transporte[] = $maq;
                }
            }

            $lista_aux['general'] = $general;
            $lista_aux['maquinas'] = [
                'Elaboracion' => $lista_elaboracion,
                'Transporte' => $lista_transporte
            ];

            $data_organized_by_month_category[] = $lista_aux;

        }

            return $data_organized_by_month_category;
    }



    private function calculateVariablesyConstantesByMaquina($maquina_data, $remitos_by_maquina, $costos, $arreglos, $uso_maquinaria,
                                                            $operarios_maquina_data)
    {

        //INstancio la clase getFUnctions
        $get_functions_class = new GetFunctions();

        $variablesAndConstantesClass = new VariablesAndConstantes($costos[0], $operarios_maquina_data, $remitos_by_maquina,
        $uso_maquinaria, $arreglos);

        //debug($costos[0]);

        //Falta traer el salario del operario
        //DEfino lOS NOMBRES DE LOS DATOS TEORICOS Y/O REALES, DEBEN COINCIDIR CON LOS DEFINIDOS EN LA MET/COST
        $VAD = NULL; $VUM = NULL; $HTU = NULL; $HME = NULL; $TIS = null;
        $FCI = null; $VAN = null; $HFU = null; $VUE = null;
        $CCT = NULL; $CLT = NULL; $COM = NULL; $COH = NULL;
        $LUB = NULL; $LUH = NULL; $SAL = NULL; $VUN = NULL;
        $AME = NULL;

        //DEfinos los gastos
        $gastos_comb = null;
        $gastos_lub = null;
        $gastos_sueldos = null;
        $gastos_arreglos = null;

        $toneladas = null;
        $precio_ton = null;



        if($maquina_data->propia)
        {
            //Seteo los valores de las var and cons
            $VAD = $variablesAndConstantesClass->getVAD();
            $TIS = $variablesAndConstantesClass->getTIS();
            $FCI = $variablesAndConstantesClass->getFCI();
            $HTU = $variablesAndConstantesClass->getHTU();
            $VAN = $variablesAndConstantesClass->getVAN();
            $HFU = $variablesAndConstantesClass->getHFU();
            $VUE = $variablesAndConstantesClass->getVUE();
            $VUN = $variablesAndConstantesClass->getVUN();

            $HME = $variablesAndConstantesClass->getHME();
            $CCT = $variablesAndConstantesClass->getCCT();
            $CLT = $variablesAndConstantesClass->getCLT();
            $COH = $variablesAndConstantesClass->getCOH();
            $LUH = $variablesAndConstantesClass->getLUH();
            $SAL = $variablesAndConstantesClass->getSAL();

            //seteo los gastos
            $gastos_sueldos = $variablesAndConstantesClass->getGastosSueldo();
            $gastos_comb = $variablesAndConstantesClass->getGastosCombustibles();
            $gastos_lub = $variablesAndConstantesClass->getGastosLubricantes();


            $toneladas = $variablesAndConstantesClass->getToneladas();
            $precio_ton = $variablesAndConstantesClass->getPrecioTonelada();

            $COM = $variablesAndConstantesClass->getCOM();
            $LUB = $variablesAndConstantesClass->getLUB();

        } else {
            //TRaigo el precio por tonelada de los costos
            $toneladas = $variablesAndConstantesClass->getToneladas();
            $precio_ton = $costos[0]['costo_alquiler'];
        }


        $AME = $variablesAndConstantesClass->getAME();
        $gastos_arreglos = $variablesAndConstantesClass->getGastosArreglos();



        $maquina = [
            'idmaquinas' => $maquina_data->idmaquinas,
            'name' => $maquina_data->name,
            'marca' => $maquina_data->marca,
            'centro_costos' => $costos[0]->centros_costos,
            'metod_costos' => $costos[0]->metod_costos_hashmetod_costos,
            'toneladas' => $toneladas,
            'precio_ton' => $precio_ton,
            'alquiler' =>  $costos[0]->alquilada,
            'constantes' => [
                'VAD' => $VAD,
                'VUN' => $VUN,
                'HTU' => $HTU,
                'HME' => $HME,
                'TIS' => $TIS,
                'FCI' => $FCI,
                'VAN' => $VAN,
                'HFU' => $HFU,
                'VUE' => $VUE,
                'CCT' => $CCT,
                'CLT' => $CLT,
                'COM' => $COM,
                'COH' => $COH,
                'LUB' => $LUB,
                'LUH' => $LUH,
                'SAL' => $SAL,
                'AME' => $AME
            ],
            'gastos'=> [
                'gasto_combustible' => $gastos_comb,
                'gasto_lubricante' => $gastos_lub,
                'gasto_sueldo' => $gastos_sueldos,
                'gastos_arreglos' => $gastos_arreglos
            ]
        ];

        return $maquina;


    }

    private function calculateVariablesyConstantesByMaquina_($maquina_data, $remitos_by_maquina, $costos, $arreglos, $uso_maquinaria,
                                                      $operarios_maquina_data)
    {
        //INstancio la clase getFUnctions
        $get_functions_class = new GetFunctions();


        //Falta traer el salario del operario
        //DEfino lOS NOMBRES DE LOS DATOS TEORICOS Y/O REALES, DEBEN COINCIDIR CON LOS DEFINIDOS EN LA MET/COST
        $VAD = NULL; $VUM = NULL; $HTU = NULL; $HME = NULL; $TIS = null;
        $FCI = null; $VAN = null; $HFU = null; $VUE = null;
        $CCT = NULL; $CLT = NULL; $COM = NULL; $COH = NULL;
        $LUB = NULL; $LUH = NULL; $SAL = NULL; $VUN = NULL;
        $AME = NULL;

        $gastos_sueldos = 0;
        $precio_ton_aux = null;
        $toneladas =  null;
        $precio_ton = null;
        $i = 0;

        if($maquina_data->propia)
        {
            $VAD = $costos[0]->val_adq;
            $TIS = $costos[0]->tasa_int_simple;
            $FCI = $costos[0]->factor_cor;
            $HTU = $costos[0]->horas_total_uso;
            $VAN = $costos[0]->val_neum;
            $HFU = $costos[0]->horas_efec_uso;
            $VUE = $costos[0]->vida_util;

            //DEpreciacion de los neumativos
            $VUN = $costos[0]->vida_util_neum;

            //Proceso operarios
            foreach ($operarios_maquina_data as $oper)
            {
                $gastos_sueldos = $gastos_sueldos + $oper->sueldo;
            }

            //El precio por tonelada se calcula de forma diferente, si es alquilada esta en costos, sino esta en el remito
            //EL precio por tonelada es ponderado

            foreach ($remitos_by_maquina as $remito) {
                $toneladas = $toneladas + $remito->ton;
                $precio_ton_aux = $precio_ton_aux + $remito->precio_ton;
                $i++;

            }

            if($i > 0){
                $precio_ton = $precio_ton_aux / $i;
            }

            //SI LA MAQUINA NO ES PROPIA NO SE CARGAN LOS USOS Y ARREGLOS (Segun patricio)
            //Tengo que reccorer USO_MAQUINARIA y sumar los valores de combustibles y horas

            $COH = 0;
            $gastos_comb = 0;
            $gastos_lub = 0;

            //Proceso uso de maquinaria
            if(count($uso_maquinaria->toArray()) > 0) {

                $horas_tol = 0;
                $litros_comb_tol = 0;
                $litros_lub_tot = 0;


                foreach ($uso_maquinaria as $uso_maq) {

                    $uso_maquinaria_ = $get_functions_class->getUsoMaquinariaCombustible($uso_maq->iduso_maquinaria);


                    if (count($uso_maquinaria_->toArray()) > 0) {
                        $horas_tol = $horas_tol + $uso_maq->horas_trabajo;

                        foreach ($uso_maq->uso_comb_lub as $uso_comb) {
                            //COnsulto por la categoria


                            if ($uso_comb->categoria == 'Combustible') {
                                $litros_comb_tol = $litros_comb_tol + $uso_comb->litros;
                                $gastos_comb = $gastos_comb + ($uso_comb->litros * $uso_comb->precio);


                            }
                            if ($uso_comb->categoria == 'Lubricante') {
                                $litros_lub_tot = $litros_lub_tot + $uso_comb->litros;
                                $gastos_lub = $gastos_lub + ($uso_comb->litros * $uso_comb->precio);
                            }
                        }
                    }
                }

                //HME son las horas de trabajo de la maquina sacadas de USO maquinaria
                $HME = $horas_tol;
                $CCT = $litros_comb_tol;
                $CLT = $litros_lub_tot;

                //COH puede dar error de division por cero
                if($HME > 0){
                    $COH = $CCT / $HME;
                    $LUH = $CLT / $HME;
                } else {
                    $COH = NULL;
                    $LUH = NULL;
                }

            }


            //Proceso Operarios
            $suma_sal = null;

            foreach ($operarios_maquina_data as $op_maq){
                $suma_sal = $suma_sal + $op_maq->sueldo;
            }

            if($HME > 0){
                $SAL = $suma_sal / $HME;
            } else {
                $SAL = null;
            }


        } else {
            //La maquina es alquilada, por lo tanto no tendra operarios
            //EL precio por tonelada lo traigo de los costos teoricos
            $precio_ton = $costos[0]->costo_alquiler;
        }


        /* CONSULTAR A PATRICIO SI UNA MAQUINA ALQUILADA TIENE ARREGLOS MECANICOS */

        //Proceso los arreglos mecanicos
        $gastos_arreglos = null;
        //verifico los arreglos mecanicos, sino tomo la constante
        if(count($arreglos->toArray()) == 0)
        {
            $AME = null;
        } else {
            //Recorro los arreglos y los sumo
            foreach ($arreglos as $arr){
                $AME = $AME + $arr->total;
                $gastos_arreglos = $gastos_arreglos + $arr->total;
            }

        }

        //Agrego los elementos al array return
        //AGrego gastos en combustibles, arreglos, sueldos operador
        $maquina = [
            'idmaquinas' => $maquina_data->idmaquinas,
            'name' => $maquina_data->name,
            'marca' => $maquina_data->marca,
            'centro_costos' => $costos[0]->centros_costos,
            'metod_costos' => $costos[0]->metod_costos_hashmetod_costos,
            'toneladas' => $toneladas,
            'precio_ton' => $precio_ton,
            'alquiler' =>  $costos[0]->alquilada,
            'constantes' => [
                'VAD' => $VAD,
                'VUN' => $VUN,
                'HTU' => $HTU,
                'HME' => $HME,
                'TIS' => $TIS,
                'FCI' => $FCI,
                'VAN' => $VAN,
                'HFU' => $HFU,
                'VUE' => $VUE,
                'CCT' => $CCT,
                'CLT' => $CLT,
                'COM' => $COM,
                'COH' => $COH,
                'LUB' => $LUB,
                'LUH' => $LUH,
                'SAL' => $SAL,
                'AME' => $AME
            ],
            'gastos'=> [
                'gasto_combustible' => $gastos_comb,
                'gasto_lubricante' => $gastos_lub,
                'gasto_sueldo' => $gastos_sueldos,
                'gastos_arreglos' => $gastos_arreglos
            ]
        ];

        return $maquina;

    }

    /*
     $VAD = NULL; $VUM = NULL; $HTU = NULL; $HME = NULL; $TIS = null;
        $FCI = null; $VAN = null; $HFU = null; $VUE = null;
        $CCT = NULL; $CLT = NULL; $COM = NULL; $COH = NULL;
        $LUB = NULL; $LUH = NULL; $SAL = NULL; $VUN = NULL;
        $AME = NULL;
     */




    public function verifiedDataByMonth($array_options, $mes, $year)
    {
        $result_by_month = [];
        $general_data = [];
        $maquinas_result = [];
        $general_total_ton = 0;
        $general_data['mes'] = $mes;
        $general_data['year'] = $year;

        //Instancio la clase GetFUnction
        $get_functions = new GetFunctions();


        //Arreglo no vaio, proceso
        if(count($array_options) > 0) {

            $array_options['mes'] = $mes;
            $array_options['year'] = $year;

            //OBtengo los remitos disponibles
            $array_remitos = $get_functions->getRemitosByConditions($array_options);
            $general_data['total_remitos'] = count($array_remitos);



            //COntrolo que leguen remitos
            if(count($array_remitos) > 0){
                //TRaigo las maquinas que participan en el analisis
                $tabla_remitosmaq = TableRegistry::getTableLocator()->get('RemitosMaquinas');

                //Variable con las maquinas utilizadas en los remitos filtrados
                $maquinas_array =  $tabla_remitosmaq->find('getMaquinasByRemitos', $array_remitos);

                if(count($maquinas_array) > 0){
                    foreach ($maquinas_array as $maquina) {


                        $maquina_data = $get_functions->getMaquinaById($maquina);

                        //TRaigo para esta maquina en especifico los datos
                        $remitos_by_maquina = $get_functions->getRemitosByMaquina($maquina, $array_options);

                        $remitos_array_distinc = $get_functions->getRemitosAsArrayDistinct($remitos_by_maquina);

                        if(count($remitos_array_distinc) > 0){

                            //traigo los costos
                            $costos = $get_functions->getCostosByMaquina($maquina, $mes, $year)->toArray();

                            if(count($costos) > 0){

                                //Usos  EL uso tiene solo la parcela para filtrar
                                $uso_maquinaria = $get_functions->getUsoMaquinariaByMaquina($maquina, $array_options);

                                if(count($uso_maquinaria->toArray()) > 0)
                                {

                                    //TRaigo operarios
                                    $operario_maq =  $get_functions->getOperarioByMaquina($maquina, $remitos_array_distinc);
                                    //Traigo los operarios maquinas donde se encuentra los datos de sueldos
                                    $operarios_maquina_data =  $get_functions->getOperariosMaquinasByOperAndRemito($operario_maq, $mes, $year);


                                    if(count($operarios_maquina_data) > 0)
                                    {
                                        return true;
                                    } else {
                                        //debug("La Maquina no tiene Operarios con datos");
                                        return false;

                                    }

                                } else {
                                    //debug("La Maquina no tiene Usos");
                                    return false;
                                }

                            } else {
                                //debug("La Maquina no tiene costos");
                                return false;

                            }

                        } else {
                            //debug("La Maquina no tiene remitos distinct");
                            return false;

                        }
                    }

                } else {
                    //debug("No hay Maquinas");
                    return false;

                }

            } else {
                //debug("Verifique el rango de fecha seleccionado");
                //debug("No hay Remitos");
                return false;

            }

        } else {
            return false;
            //debug("Revisar las opciones cargadas");
        }

    }


    private function resumeDataMaquinasByCentroCostos($new_lista_maquinas_with_data, $centros_costos, $general_data)
    {
        $this->autoRender = false;

        //Recorro las maquinas y flitro los centros de costos
        $array_data_by_centros = [];
        //debug($new_lista_maquinas_with_data);

        //OBtengo los totales por centro de costos
        $total_ton = null;
        $total_precio = null;

        foreach ($centros_costos as $centro){
            $total_ton = null;
            $total_precio = null;
            $costo_total = null;

            $horas = null;

            $array = [
                'idcentros_costos' => $centro->idcentros_costos,
                'name' => $centro->name,
                'categoria' => $centro->categoria
            ];


            $array_maquinas = [];
            $i = 0;
            foreach ($new_lista_maquinas_with_data as $maq){
                //debug($maq->costos_maquinas);
                //Datos del centro no null
                if(!empty($maq['centro_costos'])){
                    //debug($maq);
                    if($maq['centro_costos'][0]->idcentros_costos == $centro->idcentros_costos){
                        //Almaceno la maquina
                        $array_maquinas[] = $maq;
                        //Sumo las toneladas
                        $total_ton = $total_ton + $maq['costos']['toneladas'];
                        //$total_precio = $total_precio + $maq['precio_ton'];

                        if(!empty($general_data['toneladas'])){

                            if($general_data['toneladas'] > 0){
                                //COto por toneladas total
                                $costo_total = $costo_total + (($maq['costos']['costo_ton'] * $maq['costos']['toneladas']));

                            }

                        }
                        $horas = $horas + $maq['costos']['horas'];

                    }
                }
            }

            $array['maquinas'] = $array_maquinas;
            //Esta variable nose si se usa
            $array['toneladas_total'] = $total_ton;
            //$array['precio_ton'] = $total_precio / $i;
            $array['costo_total'] = $costo_total / $total_ton;
            $array['horas'] = $horas;


            $array['ton_h'] = $horas != 0 ? ($total_ton / $horas) : null;


            $array_data_by_centros[] = $array;
        }

        return $array_data_by_centros;


    }

    private function resumeGeneralData($data_organized_by_month, $maquinas_distinct)
    {
       //debug($data_organized_by_month);
        $general = [
            'total_remitos' => null,
            'total_maquinas' => null,
            'toneladas' => null
        ];

        foreach ($data_organized_by_month as $data)
        {
            $general['total_remitos'] =   $general['total_remitos'] + $data['general']['total_remitos'];
           // $general['total_maquinas'] =   $general['total_maquinas'] + $data['general']['total_maquinas'];
            $general['toneladas'] = isset($data['general']['toneladas']) ? $general['toneladas'] + $data['general']['toneladas'] : 0;
        }

        $tot_maq = empty($maquinas_distinct) ? 0 : count($maquinas_distinct);
        $general['total_maquinas'] = $tot_maq;
        return $general;

    }

    /*
     * Acomoda la informacion resumen de los costos generales
     */
    private function resumeGeneralDataCostos($new_lista_maquinas_with_data)
    {
        //debug($new_lista_maquinas_with_data);
        $costos_mai_class = new CostosMai();

        $sum_costo_fijo = $costos_mai_class->getSumatoriaCostos($new_lista_maquinas_with_data, 1);
        $sum_costo_semifijo = $costos_mai_class->getSumatoriaCostos($new_lista_maquinas_with_data, 2);
        $sum_costo_variable = $costos_mai_class->getSumatoriaCostos($new_lista_maquinas_with_data, 3);
        $sum_costo_mantenimiento = $costos_mai_class->getSumatoriaCostos($new_lista_maquinas_with_data, 4);
        $sum_costo_administracion = $costos_mai_class->getSumatoriaCostos($new_lista_maquinas_with_data, 5);


        $array = ['sum_costos_fijos' => $sum_costo_fijo,
            'sum_costo_semifijo' => $sum_costo_semifijo,
            'sum_costo_variable' => $sum_costo_variable,
            'sum_costo_mantenimiento' => $sum_costo_mantenimiento,
            'sum_costo_administracion' => $sum_costo_administracion
            ];


        return $array;


    }


    private function calculateResultMaquinasByMonths($data_organized_by_month)
    {
        $new_lista_maquina_by_month = [];
        $new_lista_maquinas = [];
        $lista_return = [];

        $gastos = [
            'gasto_combustible' => null,
            'gasto_lubricante' => null,
            'gasto_sueldo' => null,
            'gastos_arreglos' => null
        ];
        $result_metod = [
            'interes' => null,
            'seguro' => null,
            'dep_maq' => null,
            'dep_neum' => null,
            'arreglos_maq' => null,
            'cons_comb' => null,
            'cons_lub' => null,
            'operador' => null,
            'mantenimiento' => null,
            'administracion' => null

        ];
        $costos = [
            'costo_h' => null,
            'prod_rend_h' => null,
            'costo_ton' => null,
            'toneladas' => null,
            'horas' => null,
            'costo_fijo_horario' => null,
            'costo_semifijo_horario' => null,
            'costo_variable_horario' => null,
            'costo_mantenimiento_horario' => null,
            'costo_administracion_horario' => null,
            'costo_fijo_tonelada' => null,
            'costo_semifijo_tonelada' => null,
            'costo_variable_tonelada' => null,
            'costo_mantenimiento_tonelada' => null,
            'costo_administracion_tonelada' => null
        ];


        $general = null;

        $toneladas = null;

        foreach ($data_organized_by_month as $data)
        {
            //Recupero los metadatos
            $new_lista_maquina_by_month['general'] = $data['general'];

            //Recorro las maquinas
            foreach ($data['maquinas'] as $maq)
            {

                //Recorro maquina por maquina y sumo estos parametros
                $new_maquina['idmaquina'] = $maq['idmaquinas'];
                $new_maquina['name'] = $maq['name'];
                $new_maquina['marca'] = $maq['marca'];
                $new_maquina['centro_costos'] = $maq['centro_costos'];
                $new_maquina['alquiler'] = $maq['alquiler'];
                $new_maquina['constantes'] = $maq['constantes'];

                //$toneladas = $toneladas + $maq['toneladas'];

                $gastos['gasto_combustible'] =  $gastos['gasto_combustible'] + $maq['gastos']['gasto_combustible'];
                $gastos['gasto_lubricante'] =  $gastos['gasto_lubricante'] + $maq['gastos']['gasto_lubricante'];
                $gastos['gasto_sueldo'] =  $gastos['gasto_sueldo'] + $maq['gastos']['gasto_sueldo'];
                $gastos['gastos_arreglos'] =  $gastos['gastos_arreglos'] + $maq['gastos']['gastos_arreglos'];

                $result_metod['interes'] =  $result_metod['interes'] + $maq['result_metod']['interes'];
                $result_metod['seguro'] =  $result_metod['seguro'] + $maq['result_metod']['seguro'];
                $result_metod['dep_maq'] =  $result_metod['dep_maq'] + $maq['result_metod']['dep_maq'];
                $result_metod['dep_neum'] =  $result_metod['dep_neum'] + $maq['result_metod']['dep_neum'];
                $result_metod['arreglos_maq'] =  $result_metod['arreglos_maq'] + $maq['result_metod']['arreglos_maq'];
                $result_metod['cons_comb'] =  $result_metod['cons_comb'] + $maq['result_metod']['cons_comb'];
                $result_metod['cons_lub'] =  $result_metod['cons_lub'] + $maq['result_metod']['cons_lub'];
                $result_metod['operador'] =  $result_metod['operador'] + $maq['result_metod']['operador'];
                $result_metod['mantenimiento'] =  $result_metod['mantenimiento'] + $maq['result_metod']['mantenimiento'];
                $result_metod['administracion'] =  $result_metod['administracion'] + $maq['result_metod']['administracion'];


                //$costos['costo_h'] =  $costos['costo_h'] + $maq['costos']['costo_h'];
                //$costos['prod_rend_h'] =  $costos['prod_rend_h'] + $maq['costos']['prod_rend_h']; //Se suma??
                //$costos['costo_ton'] =  $costos['costo_ton'] + $maq['costos']['costo_ton'];
                $costos['toneladas'] =  $costos['toneladas'] + $maq['costos']['toneladas'];
                $costos['horas'] =  $costos['horas'] + $maq['costos']['horas'];

                $costo_mai_class = new CostosMai();


                $costos['costo_fijo_horario'] =  $costo_mai_class->getCostoFijoHorario($maq);
                $costos['costo_semifijo_horario'] =  $costo_mai_class->getCostoSemifijoHorario($maq);
                $costos['costo_variable_horario'] =  $costo_mai_class->getCostoVariableHorario($maq);
                $costos['costo_mantenimiento_horario'] = $costo_mai_class->getCostoMantenimientoHorario($maq);
                $costos['costo_administracion_horario'] =  $costo_mai_class->getCostoAdministracioHorario($maq);

                $costos['costo_fijo_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 1);
                $costos['costo_semifijo_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 2);
                $costos['costo_variable_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 3);
                $costos['costo_mantenimiento_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 4);
                $costos['costo_administracion_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 5);


                //AGrego a la nueva lista
                $new_maquina['gastos'] = $gastos;
                $new_maquina['result_metod'] = $result_metod;
                $new_maquina['costos'] = $costos;

                $new_lista_maquinas[] = $new_maquina;

                $gastos = [
                    'gasto_combustible' => null,
                    'gasto_lubricante' => null,
                    'gasto_sueldo' => null,
                    'gastos_arreglos' => null
                ];
                $result_metod = [
                    'interes' => null,
                    'seguro' => null,
                    'dep_maq' => null,
                    'dep_neum' => null,
                    'arreglos_maq' => null,
                    'cons_comb' => null,
                    'cons_lub' => null,
                    'operador' => null,
                    'mantenimiento' => null,
                    'administracion' => null

                ];
                $costos = [
                    'costo_h' => null,
                    'prod_rend_h' => null,
                    'costo_ton' => null,
                    'toneladas' => null,
                    'horas' => null,
                    'costo_fijo_horario' => null,
                    'costo_semifijo_horario' => null,
                    'costo_variable_horario' => null,
                    'costo_mantenimiento_horario' => null,
                    'costo_administracion_horario' => null,
                    'costo_fijo_tonelada' => null,
                    'costo_semifijo_tonelada' => null,
                    'costo_variable_tonelada' => null,
                    'costo_mantenimiento_tonelada' => null,
                    'costo_administracion_tonelada' => null
                ];

                $new_maquina = null;
            }

            $new_lista_maquina_by_month['maquinas'] = $new_lista_maquinas;
            $lista_return[] = $new_lista_maquina_by_month;

            $new_lista_maquinas = null;
            $new_lista_maquina_by_month = null;
        }
        return $lista_return;

    }

    /*
     * COmo las maquinas pueden repetirse mes a mes, utilizo este metodo para resumir
     * utilizo las horas del mes HME como elemento de ponderacion
     */
    private function resumeCostosHorariosByMaquina($new_lista_maquinas_with_data, $maquinas_distinct)
    {

        $maquinas_result_new = null;

        $get_function_class = new GetFunctions();

        foreach ($maquinas_distinct as $maquina_distinct)
        {

            $maquina_ = $get_function_class->getMaquinaById($maquina_distinct);

            $result = null;
            $maquina = null;

            $HME = 0;
            $toneladas = 0;

            $interes = 0;
            $seguro = 0;
            $dep_maq = 0;
            $dep_neum = 0;
            $arreglos_maq = 0;
            $cons_comb = 0;
            $cons_lub = 0;
            $operador = 0;
            $mantenimiento = 0;
            $administracion = 0;
            $index_ = 0;


            if($maquina_->propia)
            {
                foreach ($new_lista_maquinas_with_data as $data_month)
                {

                    foreach ($data_month['maquinas'] as $maq)
                    {
                        // debug($maq);

                        //COnsulto si las maquinas son las mismas
                        if($maq['idmaquinas'] == $maquina_distinct)
                        {
                            if($index_ == 0)
                            {
                                $maquina['idmaquinas'] = $maq['idmaquinas'];
                                $maquina['name'] = $maq['name'];
                                $maquina['marca'] = $maq['marca'];
                                $maquina['centro_costos'] = $maq['centro_costos'];
                                $maquina['alquiler'] = $maq['alquiler'];
                                $index_++;

                            }

                            $HME = $HME + $maq['constantes']['HME'];
                            $toneladas = $toneladas + $maq['costos']['toneladas'];

                            $interes = $interes + ($maq['result_metod']['interes'] * $maq['constantes']['HME']);
                            $seguro = $seguro + ($maq['result_metod']['seguro'] * $maq['constantes']['HME']);
                            $dep_maq = $dep_maq + ($maq['result_metod']['dep_maq'] * $maq['constantes']['HME']);

                            $dep_neum = $dep_neum + ($maq['result_metod']['dep_neum'] * $maq['constantes']['HME']);
                            $cons_comb = $cons_comb + ($maq['result_metod']['cons_comb'] * $maq['constantes']['HME']);
                            $cons_lub = $cons_lub + ($maq['result_metod']['cons_lub'] * $maq['constantes']['HME']);

                            $arreglos_maq = $arreglos_maq +  ($maq['result_metod']['arreglos_maq'] * $maq['constantes']['HME']);

                            $operador = $operador + ($maq['result_metod']['operador'] * $maq['constantes']['HME']);
                            $mantenimiento = $mantenimiento + ($maq['result_metod']['mantenimiento'] * $maq['constantes']['HME']);
                            $administracion = $administracion + ($maq['result_metod']['administracion'] * $maq['constantes']['HME']);

                        }

                    }
                }
                //DIvido las sumas productos por el hme total

                $interes = $HME == 0 ? 0 : $interes / $HME;
                $seguro = $HME == 0 ? 0 : $seguro / $HME;
                $dep_maq = $HME == 0 ? 0 : $dep_maq / $HME;
                $dep_neum = $HME == 0 ? 0 : $dep_neum / $HME;
                $cons_comb = $HME == 0 ? 0 : $cons_comb / $HME;
                $cons_lub =  $HME == 0 ? 0 : $cons_lub / $HME;
                $arreglos_maq = $HME == 0 ? 0 : $arreglos_maq / $HME;
                $operador = $HME == 0 ? 0 : $operador / $HME;
                $mantenimiento =$HME == 0 ? 0 :  $mantenimiento / $HME;
                $administracion = $HME == 0 ? 0 : $administracion / $HME;

                //creo el arreglo para devolver
                $maquina['result_metod'] = [
                    'interes' => $interes,
                    'seguro' => $seguro,
                    'dep_maq' => $dep_maq,
                    'dep_neum' => $dep_neum,
                    'arreglos_maq' => $arreglos_maq,
                    'cons_comb' => $cons_comb,
                    'cons_lub' => $cons_lub,
                    'operador' => $operador,
                    'mantenimiento' => $mantenimiento,
                    'administracion' => $administracion
                ];

                $costo_h = $interes + $seguro + $dep_maq + $dep_neum + $arreglos_maq + $cons_comb + $cons_lub +
                    $operador + $mantenimiento + $administracion;

                $prod_rend_h = $HME == 0 ? 0 : $toneladas / $HME;

                $costo_ton = $prod_rend_h == 0 ? 0 : $costo_h / $prod_rend_h;

                $maquina['costos'] = [
                    'horas' => $HME,
                    'toneladas' => $toneladas,
                    'costo_h' => $costo_h,
                    'prod_rend_h' => $prod_rend_h,
                    'costo_ton' => $costo_ton,
                ];

            } else {
                //Maquina alquilada, recorro los meses y realizo suma producto
                $suma_producto = 0;

                //debug($maquina_);

                foreach ($new_lista_maquinas_with_data as $data_month)
                {
                    foreach ($data_month['maquinas'] as $maq)
                    {
                        if($maq['idmaquinas'] == $maquina_distinct)
                        {
                            if($index_ == 0)
                            {
                                $maquina['idmaquinas'] = $maq['idmaquinas'];
                                $maquina['name'] = $maq['name'];
                                $maquina['marca'] = $maq['marca'];
                                $maquina['centro_costos'] = $maq['centro_costos'];
                                $maquina['alquiler'] = $maq['alquiler'];
                                $index_++;

                            }

                            $toneladas = $toneladas + $maq['costos']['toneladas'];
                            $suma_producto = $suma_producto + ($maq['costos']['toneladas'] * $maq['precio_ton']);

                        }

                    }
                }

                $costo_ton = $suma_producto / $toneladas;

                //creo el arreglo para devolver
                $maquina['result_metod'] = [
                    'interes' => null,
                    'seguro' => null,
                    'dep_maq' => null,
                    'dep_neum' => null,
                    'arreglos_maq' => null,
                    'cons_comb' => null,
                    'cons_lub' => null,
                    'operador' => null,
                    'mantenimiento' => null,
                    'administracion' => null
                ];

                $maquina['costos'] = [
                    'horas' => null,
                    'toneladas' => $toneladas,
                    'costo_h' => null,
                    'prod_rend_h' => null,
                    'costo_ton' => $costo_ton
                ];


            }



            $maquinas_result_new[] = $maquina;



        }


        return $maquinas_result_new;

    }




    private function calculateCostosFijosYVariables($maquinas_resume)
    {

        $maquinas_list = [];
        $get_function_class = new GetFunctions();

        foreach ($maquinas_resume as $maq)
        {
            $res = $get_function_class->getCostosFijosyVariablesByMaquina($maq);
            $maq['costos_fijos_variables'] = $res;

            $maquinas_list[] = $maq;
        }

        return $maquinas_list;

    }



    private function resumeResultMaquinasFromMonths($data_organized_by_month, $maquinas_distinct)
    {




        $new_lista_maquina = [];

        $new_maquina = null;

        //Recorro maquina por maquina y sumo estos parametros
        $gastos = [
            'gasto_combustible' => null,
            'gasto_lubricante' => null,
            'gasto_sueldo' => null,
            'gastos_arreglos' => null
        ];
        $result_metod = [
            'interes' => null,
            'seguro' => null,
            'dep_maq' => null,
            'dep_neum' => null,
            'arreglos_maq' => null,
            'cons_comb' => null,
            'cons_lub' => null,
            'operador' => null,
            'mantenimiento' => null,
            'administracion' => null

        ];
        $costos = [
            'costo_h' => null,
            'prod_rend_h' => null,
            'costo_ton' => null,
            'toneladas' => null,
            'horas' => null,
            'costo_fijo_horario' => null,
            'costo_semifijo_horario' => null,
            'costo_variable_horario' => null,
            'costo_mantenimiento_horario' => null,
            'costo_administracion_horario' => null,
            'costo_fijo_tonelada' => null,
            'costo_semifijo_tonelada' => null,
            'costo_variable_tonelada' => null,
            'costo_mantenimiento_tonelada' => null,
            'costo_administracion_tonelada' => null
        ];

        $toneladas = null;



        foreach ($maquinas_distinct as $maq_disc){

            $new_maquina = null;

            //INdice utilizado para cargar los metadatos de la maquina
            $index_ = 0;
            //ACa revisa diferentes meses, debo verificar eso

            foreach ($data_organized_by_month as $data)
            {
                foreach ($data['maquinas'] as $maq){

                    //EStoy en la maquinas, debo consultar por la igualdad
                    if($maq_disc == $maq['idmaquinas'])
                    {
                        if($index_ == 0)
                        {
                            $new_maquina['idmaquina'] = $maq['idmaquinas'];
                            $new_maquina['name'] = $maq['name'];
                            $new_maquina['marca'] = $maq['marca'];

                            $new_maquina['centro_costos'] = $maq['centro_costos'];
                            $index_++;
                        }


                        $toneladas = $toneladas + $maq['toneladas'];

                        $gastos['gasto_combustible'] =  $gastos['gasto_combustible'] + $maq['gastos']['gasto_combustible'];
                        $gastos['gasto_lubricante'] =  $gastos['gasto_lubricante'] + $maq['gastos']['gasto_lubricante'];
                        $gastos['gasto_sueldo'] =  $gastos['gasto_sueldo'] + $maq['gastos']['gasto_sueldo'];
                        $gastos['gastos_arreglos'] =  $gastos['gastos_arreglos'] + $maq['gastos']['gastos_arreglos'];

                        $result_metod['interes'] =  $result_metod['interes'] + $maq['result_metod']['interes'];
                        $result_metod['seguro'] =  $result_metod['seguro'] + $maq['result_metod']['seguro'];
                        $result_metod['dep_maq'] =  $result_metod['dep_maq'] + $maq['result_metod']['dep_maq'];
                        $result_metod['dep_neum'] =  $result_metod['dep_neum'] + $maq['result_metod']['dep_neum'];
                        $result_metod['arreglos_maq'] =  $result_metod['arreglos_maq'] + $maq['result_metod']['arreglos_maq'];
                        $result_metod['cons_comb'] =  $result_metod['cons_comb'] + $maq['result_metod']['cons_comb'];
                        $result_metod['cons_lub'] =  $result_metod['cons_lub'] + $maq['result_metod']['cons_lub'];
                        $result_metod['operador'] =  $result_metod['operador'] + $maq['result_metod']['operador'];
                        $result_metod['mantenimiento'] =  $result_metod['mantenimiento'] + $maq['result_metod']['mantenimiento'];
                        $result_metod['administracion'] =  $result_metod['administracion'] + $maq['result_metod']['administracion'];


                        $costos['costo_h'] =  $costos['costo_h'] + $maq['costos']['costo_h'];
                        $costos['prod_rend_h'] =  $costos['prod_rend_h'] + $maq['costos']['prod_rend_h']; //Se suma??
                        $costos['costo_ton'] =  $costos['costo_ton'] + $maq['costos']['costo_ton'];
                        $costos['toneladas'] =  $costos['toneladas'] + $maq['costos']['toneladas'];
                        $costos['horas'] =  $costos['horas'] + $maq['costos']['horas'];


                        //CAlculo el costo fijo

                        $costo_mai_class = new CostosMai();


                        $costos['costo_fijo_horario'] =  $costo_mai_class->getCostoFijoHorario($maq);
                        $costos['costo_semifijo_horario'] =  $costo_mai_class->getCostoSemifijoHorario($maq);
                        $costos['costo_variable_horario'] =  $costo_mai_class->getCostoVariableHorario($maq);
                        $costos['costo_mantenimiento_horario'] = $costo_mai_class->getCostoMantenimientoHorario($maq);
                        $costos['costo_administracion_horario'] =  $costo_mai_class->getCostoAdministracioHorario($maq);

                        $costos['costo_fijo_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 1);
                        $costos['costo_semifijo_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 2);
                        $costos['costo_variable_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 3);
                        $costos['costo_mantenimiento_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 4);
                        $costos['costo_administracion_tonelada'] =  $costo_mai_class->getCostos_Tonelada($maq, 5);


                    }

                }
            }




            $new_maquina['gastos'] = $gastos;
            $new_maquina['result_metod'] = $result_metod;
            $new_maquina['costos'] = $costos;

            $new_lista_maquina[] = $new_maquina;

            //Recorro maquina por maquina y sumo estos parametros
            $gastos = [
                'gasto_combustible' => null,
                'gasto_lubricante' => null,
                'gasto_sueldo' => null,
                'gastos_arreglos' => null
            ];
            $result_metod = [
                'interes' => null,
                'seguro' => null,
                'dep_maq' => null,
                'dep_neum' => null,
                'arreglos_maq' => null,
                'cons_comb' => null,
                'cons_lub' => null,
                'operador' => null,
                'mantenimiento' => null,
                'administracion' => null

            ];
            $costos = [
                'costo_h' => null,
                'prod_rend_h' => null,
                'costo_ton' => null,
                'toneladas' => null,
                'horas' => null
            ];

            $toneladas = null;


        }

        return $new_lista_maquina;

    }


}
