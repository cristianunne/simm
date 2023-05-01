<?php

namespace App\Utility;

class AnalisisMaquinas
{


    public function analisisMaquina($array_options, $id_empresa, $id_maquina)
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
            $maquinas_with_general_constantes = $this->calculateCostosByMonth($mes, $year, $array_options, $id_empresa, $id_maquina);


            $data_organized_by_month[] = $maquinas_with_general_constantes;

        }

        $maquinas_distinct = [$id_maquina => $id_maquina];
        //debug($data_organized_by_month);

        $analisis_costos_grupos = new AnalisisCostosGrupos();

        //utilizando la media ponderada
        $maquinas_resume = $this->resumeCostosHorariosByMaquina($data_organized_by_month, $maquinas_distinct);




        //Calculo los costos fijos etc etc
        if(isset($maquinas_resume[0]))
        {
            //cargo los resumenes generales
            $maquinas_resume[0]['costos']['horas'] = $get_functions->getHorasTrabajadas($data_organized_by_month);

            $maquinas_resume[0]['costos']['toneladas_total_preriodo'] = $get_functions->getTotalToneladasPeriodo($array_options);

            $ton_per = $maquinas_resume[0]['costos']['toneladas_total_preriodo'];

            $maquinas_resume[0]['costos']['porc_ton'] = $ton_per == 0 ? 0 : ($maquinas_resume[0]['costos']['toneladas'] * 100 / $ton_per);

            //Cargo las horas totales del periodo que se basa en los remitos del periodo
            $maquinas_resume[0]['costos']['porc_horas'] = 100;


            $maquinas_resume[0]['costos_groups'] = $this->calculateCostosFijosYVariables($maquinas_resume[0]);

            //CARGO LOS VALORES de %



        }



        return $maquinas_resume;


    }

    public function calculateCostosByMonth($mes, $year, $array_options, $id_empresa, $maquina)
    {

        $result_by_month = [];
        $general_data = [];
        $maquinas_result = [];

        $general_total_ton = 0;
        $general_data['mes'] = $mes;
        $general_data['year'] = $year;

        $array_options['mes'] = $mes;
        $array_options['year'] = $year;

        //INstancio la clase getfunction
        $get_functions_class = new GetFunctions();


        $maquina_data = $get_functions_class->getMaquinaById($maquina);

        //TRaigo para esta maquina en especifico los datos

        //aca ya puedo patear si no encuentra remitos
        $remitos_by_maquina = $get_functions_class->getRemitosByMaquina($maquina, $array_options);
        $remitos_array_distinct = $get_functions_class->getRemitosAsArrayDistinct($remitos_by_maquina);

        if(count($remitos_array_distinct) > 0)
        {
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


            $analisis_costos_grupos_class = new AnalisisCostosGrupos();


            $maquina_with_data = $analisis_costos_grupos_class->calculateVariablesyConstantesByMaquina($maquina_data, $remitos_by_maquina, $costos, $arreglos,
                $uso_maquinaria, $operarios_maquina_data);


            //Aplico la metodologia de costos aqui y devuelvo ya con eso
            $maquina_with_data['result_metod'] = $analisis_costos_grupos_class->appliedCostosMetodology($maquina_with_data, $id_empresa);
            $maquina_with_data['costos'] = $analisis_costos_grupos_class->calculateCostosByHours($maquina_with_data);



            $result_by_month['general'] = $general_data;
            $result_by_month['maquinas'] = $maquina_with_data;
            return $result_by_month;
        }




        return null;

    }


    public function resumeCostosHorariosByMaquina($new_lista_maquinas_with_data, $maquinas_distinct)
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



                        //COnsulto si las maquinas son las mismas
                    if($data_month['maquinas']['idmaquinas'] == $maquina_distinct)
                        {
                            if($index_ == 0)
                            {
                                $maquina['idmaquinas'] = $data_month['maquinas']['idmaquinas'];
                                $maquina['name'] = $data_month['maquinas']['name'];
                                $maquina['marca'] = $data_month['maquinas']['marca'];
                                $maquina['centro_costos'] = $data_month['maquinas']['centro_costos'];
                                $maquina['alquiler'] = $data_month['maquinas']['alquiler'];
                                $index_++;

                            }

                            $HME = $HME + $data_month['maquinas']['constantes']['HME'];
                            $toneladas = $toneladas + $data_month['maquinas']['costos']['toneladas'];

                            $interes = $interes + ($data_month['maquinas']['result_metod']['interes'] * $data_month['maquinas']['constantes']['HME']);
                            $seguro = $seguro + ($data_month['maquinas']['result_metod']['seguro'] * $data_month['maquinas']['constantes']['HME']);
                            $dep_maq = $dep_maq + ($data_month['maquinas']['result_metod']['dep_maq'] * $data_month['maquinas']['constantes']['HME']);

                            $dep_neum = $dep_neum + ($data_month['maquinas']['result_metod']['dep_neum'] * $data_month['maquinas']['constantes']['HME']);
                            $cons_comb = $cons_comb + ($data_month['maquinas']['result_metod']['cons_comb'] * $data_month['maquinas']['constantes']['HME']);
                            $cons_lub = $cons_lub + ($data_month['maquinas']['result_metod']['cons_lub'] * $data_month['maquinas']['constantes']['HME']);

                            $arreglos_maq = $arreglos_maq +  ($data_month['maquinas']['result_metod']['arreglos_maq'] * $data_month['maquinas']['constantes']['HME']);

                            $operador = $operador + ($data_month['maquinas']['result_metod']['operador'] * $data_month['maquinas']['constantes']['HME']);
                            $mantenimiento = $mantenimiento + ($data_month['maquinas']['result_metod']['mantenimiento'] * $data_month['maquinas']['constantes']['HME']);
                            $administracion = $administracion + ($data_month['maquinas']['result_metod']['administracion'] * $data_month['maquinas']['constantes']['HME']);

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

                    if($data_month['maquinas']['idmaquinas'] == $maquina_distinct)
                        {
                            if($index_ == 0)
                            {
                                $maquina['idmaquinas'] = $data_month['maquinas']['idmaquinas'];
                                $maquina['name'] = $data_month['maquinas']['name'];
                                $maquina['marca'] = $data_month['maquinas']['marca'];
                                $maquina['centro_costos'] = $data_month['maquinas']['centro_costos'];
                                $maquina['alquiler'] = $data_month['maquinas']['alquiler'];
                                $index_++;

                            }

                            $toneladas = $toneladas + $data_month['maquinas']['costos']['toneladas'];
                            $suma_producto = $suma_producto + ($data_month['maquinas']['costos']['toneladas'] * $data_month['maquinas']['precio_ton']);

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


    public function calculateCostosFijosYVariables($maquinas_resume)
    {


        $costo_maquina = null;
        $costos_fijos = null;
        $costos_semifijos = null;
        $costos_variables = null;
        $costo_horario_personal = null;
        $costo_administracion = null;

        $costos_fijos = $maquinas_resume['result_metod']['interes'] + $maquinas_resume['result_metod']['seguro'];
        $costos_semifijos = $maquinas_resume['result_metod']['dep_maq'] + $maquinas_resume['result_metod']['dep_neum'] +
            $maquinas_resume['result_metod']['arreglos_maq'];

        $costos_variables = $maquinas_resume['result_metod']['cons_comb'] + $maquinas_resume['result_metod']['cons_lub'];

        $costo_horario_personal = $maquinas_resume['result_metod']['operador'] + $maquinas_resume['result_metod']['mantenimiento'];

        $costo_administracion = $maquinas_resume['result_metod']['administracion'];

        $costo_maquina = $costos_fijos + $costos_semifijos + $costos_variables;

        $costos_gruoup = [
            'costo_maquina' => $costo_maquina,
            'costos_fijos' => $costos_fijos,
            'costos_semifijos' => $costos_semifijos,
            'costos_variables' => $costos_variables,
            'costo_horario_personal' => $costo_horario_personal,
            'costo_administracion' => $costo_administracion
        ];


        return $costos_gruoup;

    }


    public function costosToneladasMaquinaByMonths($mes, $year, $array_options, $id_empresa, $maquina)
    {

        $result = $this->calculateCostosByMonth($mes, $year, $array_options, $id_empresa, $maquina);
        $costos_ton = 0;
        //debug($result['maquinas']['costos']['costo_ton']);
        if($result != null)
        {

           return $result['maquinas']['costos']['costo_ton'];

        }
        return 0;


    }

    public function costosHorasMaquinaByMonths($mes, $year, $array_options, $id_empresa, $maquina)
    {

        $result = $this->calculateCostosByMonth($mes, $year, $array_options, $id_empresa, $maquina);
        $costos_ton = 0;
        //debug($result['maquinas']['costos']['costo_ton']);
        if($result != null)
        {

            return $result['maquinas']['costos']['costo_h'];

        }
        return 0;


    }

    public function costosRendimientoMaquinaByMonths($mes, $year, $array_options, $id_empresa, $maquina)
    {

        $result = $this->calculateCostosByMonth($mes, $year, $array_options, $id_empresa, $maquina);
        $costos_ton = 0;
        //debug($result['maquinas']['costos']['costo_ton']);
        if($result != null)
        {

            return $result['maquinas']['costos']['prod_rend_h'];

        }
        return 0;


    }

}
