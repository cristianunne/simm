<?php

namespace App\Utility;

use Cake\ORM\TableRegistry;

class AnalisisCostosGrupos
{



    public function analisisDeCostosGrupos($array_options = [], $id_empresa)
    {

        //Instancio la clase GetFUnctions
        $get_functions = new GetFunctions();

        $meses_years = $get_functions->getMonthsAndYears($array_options);

        foreach ($meses_years as $meses_year)
        {
            $mes = $meses_year['mes'];
            $year = $meses_year['year'];
            //devuelve los resultados globales y las constantes para las maquinas
            $maquinas_with_general_constantes = $this->calculateCostosByMonth($mes, $year, $array_options, $id_empresa);
            $data_organized_by_month[] = $maquinas_with_general_constantes;
        }

        //Obtengo las maquinas distinct
        $maquinas_distinct = $get_functions->getMaquinasDistinct($data_organized_by_month);
        $new_lista_maquinas_with_data = null;

        /*SI son varios meses se deben sumar las maquinas que coincidan
        Si es solo un mes, no pasa nada*/
        //SI es un periodo, los costos se suman mes a mes
        $new_lista_maquinas_with_data = $this->resumeResultMaquinasFromMonths($data_organized_by_month, $maquinas_distinct);

        $centros_costos_array = $get_functions->getCentroCostosDistinct($new_lista_maquinas_with_data);

        $centros_costos = $get_functions->getCentrosCostrosByArray($centros_costos_array);

        $maquinas_by_centros_costos['general'] = $this->resumeGeneralData($data_organized_by_month);

        $maquinas_by_centros_costos['centros'] = $this->resumeDataMaquinasByCentroCostos($new_lista_maquinas_with_data, $centros_costos);

        return $maquinas_by_centros_costos;
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

        if($maquina_with_data['constantes']['AME'] == null)
        {
            if(isset($constantes['AME'])){
                $AME = $constantes['AME'];
            }
        } else {
            $AME = $maquina_with_data['constantes']['AME'];
        }


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

        //traigo la clase y aplico la metodologia

        $metodologia_costo = new MetodologiaCostosFormula();

        $interes = $metodologia_costo->calculateInteres($VAD, $TIS, $FCI, $HTU);
        $seguro = $metodologia_costo->calculateSeguros($VAD, $CSE, $HTU);
        $dep_maq = $metodologia_costo->calculateDeprecacionMaquina($VAD, $CVD, $VAN, $HFU, $VUE);
        $dep_neum = $metodologia_costo->calculateDepreciacionNeumatico($VAN, $VUN);
        $arreglos_maq = $metodologia_costo->calculateArreglosMecanicos($VAD, $CVD, $VAN, $HFU, $VUE, $AME);
        $cons_comb = $metodologia_costo->calculateConsumoCombustible($maquina_with_data['gastos']['gasto_combustible'], $HME);
        $cons_lub = $metodologia_costo->calculateConsumoLubricante($maquina_with_data['gastos']['gasto_lubricante'], $HME);
        $operador = $metodologia_costo->calculateOperador($SAL, $HME);
        $mantenimiento = $metodologia_costo->calculateMantenimiento($SAL, $HME, $CMA);
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
        //Calculo los valores a mostrar
        $costo_hora = $maquina_with_data['result_metod']['interes'] + $maquina_with_data['result_metod']['seguro'] +
            $maquina_with_data['result_metod']['dep_maq']  + $maquina_with_data['result_metod']['dep_neum'] +
            $maquina_with_data['result_metod']['arreglos_maq']  + $maquina_with_data['result_metod']['cons_comb']
            + $maquina_with_data['result_metod']['cons_lub'] + $maquina_with_data['result_metod']['operador'] +
            $maquina_with_data['result_metod']['mantenimiento'] + $maquina_with_data['result_metod']['administracion'];

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

    private function calculateVariablesyConstantesByMaquina($maquina_data, $remitos_by_maquina, $costos, $arreglos, $uso_maquinaria,
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

                    $uso_maquinaria = $get_functions_class->getUsoMaquinariaCombustible($uso_maq->iduso_maquinaria);


                    if (count($uso_maquinaria->toArray()) > 0) {
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


    private function resumeDataMaquinasByCentroCostos($new_lista_maquinas_with_data, $centros_costos)
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

                        //COto por toneladas total
                        $costo_total = $costo_total + $maq['costos']['costo_ton'] * $maq['costos']['toneladas'];
                        $horas = $horas + $maq['costos']['horas'];

                    }
                }
            }

            $array['maquinas'] = $array_maquinas;
            //Esta variable nose si se usa
            $array['toneladas_total'] = $total_ton;
            //$array['precio_ton'] = $total_precio / $i;
            $array['costo_total'] = $costo_total;
            $array['horas'] = $horas;


            $array['ton_h'] = $horas != 0 ? ($total_ton / $horas) : null;


            $array_data_by_centros[] = $array;
        }

        return $array_data_by_centros;


    }

    private function resumeGeneralData($data_organized_by_month)
    {
        $general = [
            'total_remitos' => null,
            'total_maquinas' => null,
            'toneladas' => null
        ];

        foreach ($data_organized_by_month as $data)
        {
            $general['total_remitos'] =   $general['total_remitos'] + $data['general']['total_remitos'];
            $general['total_maquinas'] =   $general['total_maquinas'] + $data['general']['total_maquinas'];
            $general['toneladas'] =   $general['toneladas'] + $data['general']['toneladas'];
        }

        return $general;

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
            'horas' => null
        ];

        $toneladas = null;


        foreach ($maquinas_distinct as $maq_disc){

            $new_maquina = null;

            foreach ($data_organized_by_month as $data){
                foreach ($data['maquinas'] as $maq){

                    //EStoy en la maquinas, debo consultar por la igualdad
                    if($maq_disc == $maq['idmaquinas'])
                    {

                        $new_maquina['idmaquina'] = $maq['idmaquinas'];
                        $new_maquina['name'] = $maq['name'];
                        $new_maquina['marca'] = $maq['marca'];

                        $new_maquina['centro_costos'] = $maq['centro_costos'];


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
