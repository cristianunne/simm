<?php
namespace App\Controller;

use App\Utility\MetodologiaCostosFormula;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Filesystem\File;
use Cake\I18n\Date;
use DateTime;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * AnalisisCostos Controller
 *
 * @property \App\Model\Table\AnalisisCostosTable $AnalisisCostos
 */
class AnalisisCostosController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'groupsCostosAnalysis', 'calculateCostosGrupos', 'delete',
                'createdSheetResumen', 'createdSheetMaquinas', 'calculateCostosGruposCopy'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'groupsCostosAnalysis', 'calculateCostosGrupos', 'delete',
                'createdSheetResumen', 'createdSheetMaquinas', 'calculateCostosGruposCopy'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    public function index()
    {
        //Variable usada para el sidebar
        $seccion = 'analisis_costos';
        $sub_seccion = 'Inicio';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

    }

    public function groupsCostosAnalysis()
    {
        //Variable usada para el sidebar
        $seccion = 'analisis_costos';
        $sub_seccion = 'Grupos_costos';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //CARGO LOS MODELOS A USAR

        //TRaigo los grupos todos sin filtros
        $grupos_model = $this->loadModel('Worksgroups');
        $grupos_data = $grupos_model->find('list', [
            'keyField' => 'idworksgroups',
            'valueField' => 'name'
        ])
            ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
            ->toArray();

        $insertar = [0 => 'Todos'];

        array_splice($grupos_data, 0, 0, $insertar);

        $this->set(compact('grupos_data'));


        //Traigo los lotes
        $tablaLotes = $this->loadModel('Lotes');
        $lotes =  $tablaLotes->find('all', [
            'contain' => []
        ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
        $this->set(compact('lotes'));


        //Traigo los datos de los propietarios
        $tablaPropietarios = $this->loadModel('Propietarios');
        $propietarios =  $tablaPropietarios->find('all', [
            'contain' => []
        ])->where(['Propietarios.active' => true, 'Propietarios.empresas_idempresas' => $id_empresa]);
        $this->set(compact('propietarios'));

        //Traigo los datos de los propietarios
        $tablaDestinos = $this->loadModel('Destinos');

        $destinos =  $tablaDestinos->find('all', [
            'contain' => 'Users'
        ])->where(['Destinos.active' => true, 'Destinos.empresas_idempresas' => $id_empresa]);
        $this->set(compact('destinos'));

        if ($this->request->is('post')) {

            $array_options = [];
            $worksgroup = $this->request->getData()['worksgroups_idworksgroups'];


            $array_options['worksgroup'] = $worksgroup;
            $array_options['fecha_inicio'] = $this->request->getData()['fecha_inicio'];
            $array_options['fecha_fin'] = $this->request->getData()['fecha_final'];
            $array_options['lotes_idlotes'] = $this->request->getData()['lotes_idlotes'];
            $array_options['parcelas_idparcelas'] = $this->request->getData()['parcelas_idparcelas'];
            $array_options['propietarios_idpropietarios'] = $this->request->getData()['propietarios_idpropietarios'];
            $array_options['destinos_iddestinos'] = $this->request->getData()['destinos_iddestinos'];

            $array_remitos = $this->getRemitosByConditions($array_options);


            //Uso estos remitos para traer los totales

            $maquinas_filter = $this->preparedDataByMaquina($array_remitos, $array_options);
            //TRaigo todas las maquinas involucradas en los remitos
            //debug($maquinas_filter);
            $centro_costos_array = $this->filterCentroDeCostos($maquinas_filter);



            //Tabla centro de costos
            $tabla_centro_costos = $this->loadModel('CentrosCostos');
            $centros_costos = $tabla_centro_costos->find('all', [
            ])->where(['idcentros_costos IN' => $centro_costos_array])->toArray();
            $maquinas_with_data = $this->calculatedVariablesAndConstantes($maquinas_filter);
            $maquinas_with_result = $this->calculateGrupos($maquinas_with_data);

            //Filtro la información por centro de costos
            $data_by_centro_costos = $this->calculatedByCentroCostos($maquinas_with_result, $centros_costos);



        }



    }





    /*
     * Realiza el Análisis de Costos por Grupos de Trabajo y retonra un AJAX
     */
    public function calculateCostosGruposAjax()
    {

        $this->autoRender = false;

        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


            //COnsulto que los indices esten definidos
        $worksgroup = $_POST['groups'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $propietarios = $_POST['propietarios'];
        $destinos = $_POST['destinos'];
        $informe = $_POST['informe'];

        $array_options = [];

        if($this->request->is('ajax')) {

            $array_options['worksgroup'] = $worksgroup;
            $array_options['fecha_inicio'] = $fecha_inicio;
            $array_options['fecha_fin'] = $fecha_final;
            $array_options['lotes_idlotes'] = $lotes;
            $array_options['parcelas_idparcelas'] = $parcelas;
            $array_options['propietarios_idpropietarios'] = $propietarios;
            $array_options['destinos_iddestinos'] = $destinos;
            $array_options['empresas_idempresas'] = $id_empresa;

            $result = null;
            $data_organized_by_month = [];
            //EL recorrido de los meses los hago aqui
            $meses_years = $this->getMonthsAndYears($array_options);
            foreach ($meses_years as $meses_year)
            {
                $mes = $meses_year['mes'];
                $year = $meses_year['year'];

                $result = $this->verifiedDataByMonth($array_options, $mes, $year);


            }
            //Primero verifico que este all ok
            if($result){

                //EL recorrido de los meses los hago aqui
                $meses_years = $this->getMonthsAndYears($array_options);

                foreach ($meses_years as $meses_year)
                {
                    $mes = $meses_year['mes'];
                    $year = $meses_year['year'];

                    //devuelve los resultados globales y las constantes para las maquinas
                    $maquinas_with_general_constantes = $this->calculateCostosByMonth($mes, $year, $array_options);

                    $data_organized_by_month[] = $maquinas_with_general_constantes;
                }

                //Obtengo las maquinas distinc
                $maquinas_distinct = $this->getMaquinasDistinct($data_organized_by_month);


                $new_lista_maquinas_with_data = null;
                //SI son varios meses se deben sumar las maquinas que coincidan
                //Si es solo un mes, no pasa nada

                //SI es un periodo, los costos se suman mes a mes

                $new_lista_maquinas_with_data = $this->resumeResultMaquinasFromMonths($data_organized_by_month, $maquinas_distinct);


                $centros_costos_array = $this->getCentroCostosDistinct($new_lista_maquinas_with_data);


                //Tabla centro de costos
                $tabla_centro_costos = $this->loadModel('CentrosCostos');

                //TRae los centros de utilizando el array de centro de costos filtrados
                $centros_costos = $tabla_centro_costos->find('all', [
                ])->where(['idcentros_costos IN' => $centros_costos_array])->toArray();

                $maquinas_by_centros_costos['general'] = $this->resumeGeneralData($data_organized_by_month);
                $maquinas_by_centros_costos['centros'] = $this->resumeDataMaquinasByCentroCostos($new_lista_maquinas_with_data, $centros_costos);


                //Proceso el informe



                return $this->json($maquinas_by_centros_costos);

            } else {
                return $this->json(['result' => false,
                    'msg' => 'No se puede procesar la operación por falta de datos!']);
            }



            //Result costos
            //$data_by_centro_costos = $this->calculateCostosByMonth($array_options);

            //debug($data_by_centro_costos);

            /*if($data_by_centro_costos != false){

                //Tengo que calcular los costos backtime
                //Realizo el calculo 1 ano atras
                $fec_in = new Date($fecha_inicio);
                $date_year_inicio_back = date("Y-m-d",strtotime($fec_in."- 1 year"));

                $fec_fin = new Date($fecha_final);
                $date_year_final_back = date("Y-m-d",strtotime($fecha_final."- 1 year"));

                //remplazo el array options
                $array_options['fecha_inicio'] = $date_year_inicio_back;
                $array_options['fecha_fin'] = $date_year_final_back;

                $costos_one_year_back = $this->calculateCostos($array_options);

                //COmpruebo que los resultados no sean null de



                //Proceso el informe
                /*if($informe == 'true'){
                    //Obtengo las variabes a almacenar
                    $group_name= $_POST['group_name'];
                    $lote_name = $_POST['lote_name'];
                    $parcelas_name = $_POST['parcelas_name'];
                    $propietarios_name = $_POST['propietarios_name'];
                    $destinos_name = $_POST['destinos_name'];

                    $array_informe = [];

                    $array_informe['worksgroups'] = $group_name;
                    $array_informe['lote'] = $lote_name;
                    $array_informe['parcela'] = $parcelas_name;
                    $array_informe['propietario'] = $propietarios_name;
                    $array_informe['destino'] = $destinos_name;
                    $array_informe['fecha_inicio'] = $fecha_inicio;
                    $array_informe['fecha_fin'] = $fecha_final;
                    $array_informe['users_idusers'] = $user_id;
                    $array_informe['empresas_idempresas'] = $id_empresa;

                    //Agrego al ma resultado final los metadatos del informe
                    $data_by_centro_costos['metadata'] = $array_informe;

                    //Aca se define el informe
                    $data_by_centro_costos['informe'] = $this->processInforme($array_informe, $data_by_centro_costos,
                        $costos_one_year_back, null);



                } else {
                    $data_by_centro_costos['informe'] = ['id' => '', 'informe' => false];
                }*/
            /*} else {
                return $this->json(['result' => false]);
            }*/

            //Calculo los costos de elaboracion y transporte
            //Realizo el calculo 1 ano atras
            //$fec_in = new Date($fecha_inicio);
            //$date_year_back = date("d-m-Y",strtotime($fec_in."- 1 year"));

                //ANtes de hacer el return, creamos el informe
            //return $this->json($data_by_centro_costos);
        }


    }

    private function processInforme($array_informe = [], $data_by_centro_costos = [], $data_year_back = [], $data_six_month_back = [])
    {
        $this->autoRender = false;

        //Creo el informe
        $result_informe = $this->createdInforme($data_by_centro_costos, $array_informe, $data_year_back, $data_six_month_back);

        return $result_informe;
    }

    function getMonthsAndYears($array_options){

        $array_result = [];

        $fechai=strtotime($array_options['fecha_inicio']);

        $array_result[] = ['mes' => date('m',$fechai),
            'year' => date('Y',$fechai)];

        //$array_result[] = strtotime ('month',$fechai);
        while($fechai < strtotime($array_options['fecha_fin'])){
            $fechai = strtotime ('+1 month',$fechai) ;

            $array_result[] = ['mes' => date('m',$fechai),
                'year' => date('Y',$fechai)];

        }
        array_pop($array_result);
        return $array_result;
    }

    private function verifiedDataByMonth($array_options, $mes, $year)
    {
        $this->autoRender = false;

        $result_by_month = [];

        $general_data = [];

        $maquinas_result = [];

        $general_total_ton = 0;

        $general_data['mes'] = $mes;
        $general_data['year'] = $year;

        //Arreglo no vaio, proceso
        if(count($array_options) > 0) {

            $array_options['mes'] = $mes;
            $array_options['year'] = $year;

            //OBtengo los remitos disponibles
            $array_remitos = $this->getRemitosByConditions($array_options);
            $general_data['total_remitos'] = count($array_remitos);


            //COntrolo que leguen remitos
            if(count($array_remitos) > 0){

                //TRaigo las maquinas que participan en el analisis
                $tabla_remitosmaq = $this->loadModel('RemitosMaquinas');
                //Variable con las maquinas utilizadas en los remitos filtrados
                $maquinas_array =  $tabla_remitosmaq->find('getMaquinasByRemitos', $array_remitos);


                if(count($maquinas_array) > 0){
                    foreach ($maquinas_array as $maquina){


                        $maquina_data = $this->getMaquinaById($maquina);

                        //TRaigo para esta maquina en especifico los datos
                        $remitos_by_maquina = $this->getRemitosByMaquina($maquina, $array_options);

                        $remitos_array_distinc = $this->getRemitosAsArrayDistinc($remitos_by_maquina);

                        if(count($remitos_array_distinc) > 0){

                            //traigo los costos
                            $costos = $this->getCostosByMaquina($maquina, $mes, $year)->toArray();

                            if(count($costos) > 0){

                                //Usos  EL uso tiene solo la parcela para filtrar
                                $uso_maquinaria = $this->getUsoMaquinariaByMaquina($maquina, $array_options);

                                if(count($uso_maquinaria->toArray()) > 0)
                                {
                                    //TRaigo operarios
                                    $operario_maq =  $this->getOperarioByMaquina($maquina, $remitos_array_distinc);
                                    //Traigo los operarios maquinas donde se encuentra los datos de sueldos

                                    $operarios_maquina_data =  $this->getOperariosMaquinasByOperAndRemito($operario_maq, $mes, $year);

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
                            } else{
                                //debug("La Maquina no tiene costos");
                                return false;

                            }
                        } else {
                            //debug("La Maquina no tiene remitos distinct");
                            return false;

                        }

                    } //foreach maquina

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



    private function getUsoMaquinaria($maquina, $mes, $year)
    {

        //CArgo la tabla uso de maquinaria
        $uso_maquinaria_model = $this->loadModel('UsoMaquinaria');

        $uso_maq = $uso_maquinaria_model->find('all', [])
        ->where(['maquinas_idmaquinas' => $maquina, 'MONTH(fecha)' => $mes, 'YEAR(fecha)' => $year]);


        return $uso_maq;


    }

    private function getUsoMaquinariaCombustible($idusomaquinaria)
    {
        //CArgo la tabla uso de maquinaria
        $uso_maquinaria_model = $this->loadModel('UsoCombLub');

        $uso_maq_comb = $uso_maquinaria_model->find('all', [])
            ->where(['uso_maquinaria_iduso_maquinaria' => $idusomaquinaria]);

        return $uso_maq_comb;
    }

    private function verifiedUsoMaquinariaCombustible($uso_maquinaria_comb_result)
    {



        if(count($uso_maquinaria_comb_result->toArray()) > 0)
        {
            foreach ($uso_maquinaria_comb_result as $uso_comb){

                if($uso_comb->categoria == 'Combustible'){
                    return true;
                }
            }
        }

        return false;

    }


    private function getOperario($maquina, $mes, $year)
    {

        $operarios_maquinas_model = $this->loadModel('OperariosMaquinas');

        $operarios_maq = $operarios_maquinas_model->find('all', [])
        ->where(['maquinas_idmaquinas' => $maquina, 'MONTH(fecha)' => $mes, 'YEAR(fecha)' => $year]);

        return $operarios_maq;

    }

    private function verifiedOperario($operarios_maq)
    {

        if(count($operarios_maq->toArray()) > 0)
        {

           return true;
        }
        return false;

    }



    private function verifiedUsoMaquinaria($uso_maquinaria_result)
    {

      if(count($uso_maquinaria_result) > 0)
      {
          return true;
      }

        return false;

    }



    private function calculateCostosByMonth($mes, $year, $array_options)
    {

        $this->autoRender = false;

        $result_by_month = [];

        $general_data = [];

        $maquinas_result = [];

        $general_total_ton = 0;

        $general_data['mes'] = $mes;
        $general_data['year'] = $year;

        //Arreglo no vaio, proceso
        if(count($array_options) > 0) {

            $array_options['mes'] = $mes;
            $array_options['year'] = $year;

            //OBtengo los remitos disponibles
            $array_remitos = $this->getRemitosByConditions($array_options);
            $general_data['total_remitos'] = count($array_remitos);

            if(count($array_remitos) > 0){

                //Calculo los resultados globales, TONELADAS
                $tabla_remitos = $this->loadModel('Remitos');
                $general_total_ton =  $tabla_remitos->find('GetTotalToneladas', $array_remitos);

                //TRaigo las maquinas que participan en el analisis
                $tabla_remitosmaq = $this->loadModel('RemitosMaquinas');
                $tabla_maquinas = $this->loadModel('Maquinas');

                //Variable con las maquinas utilizadas en los remitos filtrados
                $maquinas_array =  $tabla_remitosmaq->find('getMaquinasByRemitos', $array_remitos);

                $general_data['total_maquinas'] = count($maquinas_array);
                $general_data['toneladas'] = $general_total_ton;

                //TRaigo las maquinas que participan en el analisis

                foreach ($maquinas_array as $maquina){

                    $maquina_data = $this->getMaquinaById($maquina);

                    //TRaigo para esta maquina en especifico los datos
                    $remitos_by_maquina = $this->getRemitosByMaquina($maquina, $array_options);

                    $remitos_array_distinc = $this->getRemitosAsArrayDistinc($remitos_by_maquina);

                    //traigo los costos
                    $costos = $this->getCostosByMaquina($maquina, $mes, $year)->toArray();


                    //ARreglos
                    $arreglos = $this->getArreglosByMaquina($maquina, $array_options);
                    //debug($arreglos->toArray());

                    //Usos  EL uso tiene solo la parcela para filtrar
                    $uso_maquinaria = $this->getUsoMaquinariaByMaquina($maquina, $array_options);

                    //TRaigo operarios
                    $operario_maq =  $this->getOperarioByMaquina($maquina, $remitos_array_distinc);
                    //Traigo los operarios maquinas donde se encuentra los datos de sueldos

                    $operarios_maquina_data =  $this->getOperariosMaquinasByOperAndRemito($operario_maq, $mes, $year);

                    $maquina_with_data = $this->calculateVariablesyConstantesNew($maquina_data, $remitos_by_maquina, $costos, $arreglos,
                        $uso_maquinaria, $operarios_maquina_data);


                    //Aplico la metodologia de costos aqui y devuelvo ya con eso
                    $maquina_with_data['result_metod'] = $this->appliedCostosMetodology($maquina_with_data);

                    $maquina_with_data['costos'] = $this->calculateCostosByHours($maquina_with_data);



                    $maquinas_result[] = $maquina_with_data;

                } //foreach maquina

            }

        }

        $result_by_month['general'] = $general_data;
        $result_by_month['maquinas'] = $maquinas_result;

        return $result_by_month;


    }


    private function appliedCostosMetodology($maquina_with_data)
    {

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        //Traigo las constantes
        $constantes_model = $this->loadModel('Constantes');
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

    private function calculateVariablesyConstantesNew($maquina_data, $remitos_by_maquina, $costos, $arreglos, $uso_maquinaria,
                                                      $operarios_maquina_data)
    {
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

        //COnsulto si la maquina es alquilada
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

        } else {
            //La maquina es alquilada, por lo tanto no tendra operarios
            //EL precio por tonelada lo traigo de los costos teoricos
            $precio_ton = $costos[0]->costo_alquiler;

        }

        //LOs gastos de combustibles y lubricantes se calculan igual, independendiente de si es propia o no
        //Tengo que reccorer USO_MAQUINARIA y sumar los valores de combustibles y horas
        $COH = 0;

        $gastos_comb = 0;
        $gastos_lub = 0;

        if(count($uso_maquinaria->toArray()) > 0) {

            $horas_tol = 0;
            $litros_comb_tol = 0;
            $litros_lub_tot = 0;


            foreach ($uso_maquinaria as $uso_maq) {

                $uso_maquinaria = $this->getUsoMaquinariaCombustible($uso_maq->iduso_maquinaria);


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

        //Calculo el Salario, es la SUMA $maq->operarios_maquinas
        //Recorro el Arreglo de sueldos

        $suma_sal = null;

        foreach ($operarios_maquina_data as $op_maq){
            $suma_sal = $suma_sal + $op_maq->sueldo;
        }

        if($HME > 0){
            $SAL = $suma_sal / $HME;
        } else {
            $SAL = null;
        }

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


    private function getMaquinaById($maquina)
    {
        $maquinas_model = $this->loadModel('Maquinas');

        $maquina = $maquinas_model->get($maquina);

        return $maquina;

    }


    private function getOperarioByMaquina($maquina, $remitos_distinc)
    {

        $rem_maq_model = $this->loadModel('RemitosMaquinas');

        $operarios = $rem_maq_model->find('all', [
        ])
        ->where(['remitos_idremitos IN ' => $remitos_distinc, 'maquinas_idmaquinas' => $maquina]);

        return $operarios;

    }

    private function getOperariosMaquinasByOperAndRemito($operario_maq, $mes, $year)
    {

        $options = [];
        $operarios_maquinas_model = $this->loadModel('OperariosMaquinas');
        $array_result = [];

        foreach ($operario_maq as $op_maq){

            $options['operarios_idoperarios'] = $op_maq->operarios_idoperarios;
            $options['maquinas_idmaquinas'] = $op_maq->maquinas_idmaquinas;
            $options['mes'] = $mes;
            $options['year'] = $year;

            $array_result[] = $operarios_maquinas_model->find('GetOperariosMaquinasByConditions', $options)->toArray();

        }


        //Recorro y guardo un nuevo arreglo con sin los repetidos
        $array_result_new = [];

        foreach ($array_result as $arr){
           foreach ($arr as $op_maq){

                if(count($array_result_new) == 0){
                    $array_result_new[] = $op_maq;
                } else {
                    $exists_op = false;
                    foreach ($array_result_new as $new_arr){
                        if($new_arr->idoperarios_maquinas == $op_maq->idoperarios_maquinas){
                            $exists_op = true;
                        }
                    }

                    if(!$exists_op){
                        $array_result_new[] = $op_maq;
                    }

                }
           }
        }
        //debug($array_result_new);

        return $array_result_new;
    }


    private function getUsoMaquinariaByMaquina($maquina, $array_options)
    {
        $array_options['maquina'] = $maquina;
        $usos_model = $this->loadModel('UsoMaquinaria');
        $arreglos = $usos_model->find('GetUsoMaquinariaByConditions', $array_options);

        return $arreglos;
    }


    private function getArreglosByMaquina($maquina, $array_options)
    {
        $array_options['maquina'] = $maquina;
        $arreglos_model = $this->loadModel('ArreglosMecanicos');
        $arreglos = $arreglos_model->find('GetArreglosByConditions', $array_options);

        return $arreglos;
    }

    private function getCostosByMaquina($maquina, $mes, $year)
    {

        //LOs costos trae el activo

        $costos_maquina_model = $this->loadModel('CostosMaquinas');

        $costos = $costos_maquina_model->find('all', [
            'contain' => ['CentrosCostos']
        ])
        ->where(['maquinas_idmaquinas' => $maquina, 'active' => true]);

        return $costos;

    }


    private function getRemitosByMaquina($maquina, $array_options)
    {

        $array_options['maquina'] = $maquina;


        $remitos_model = $this->loadModel('Remitos');

        $remitos = $remitos_model->find('RemitosByConditionsQueryMaquina', $array_options);

        return $remitos;
    }


    private function getRemitosAsArrayDistinc($remitos)
    {
        $array_result = [];

        foreach ($remitos as $rem){
            $array_result[$rem->idremitos] = $rem->idremitos;
        }

        return $array_result;
    }

    public function calculateCostosGruposCopy()
    {

        $this->autoRender = false;

        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        //COnsulto que los indices esten definidos
        /* $worksgroup = $_POST['groups'];
         $fecha_inicio = $_POST['fecha_inicio'];
         $fecha_final = $_POST['fecha_final'];
         $lotes = $_POST['lotes'];
         $parcelas = $_POST['parcelas'];
         $propietarios = $_POST['propietarios'];
         $destinos = $_POST['destinos'];*/

        $worksgroup = 'Todos';
        $fecha_inicio = '2022-09-01';
        $fecha_final = '2022-10-31';
        //$fecha_inicio = '2020-09-20';
        //$fecha_final = '2020-02-20';
        $lotes = 'Todos';
        $parcelas = 'Todos';
        $propietarios = 'Todos';
        $destinos = 'Todos';

        $fec_in = new Date($fecha_inicio);

        $informe = true;

        $array_options = [];

        $array_options['worksgroup'] = $worksgroup;
        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['propietarios_idpropietarios'] = $propietarios;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;




        //Result costos
        //$data_by_centro_costos = $this->calculateCostos($array_options);
        $result = null;
        $data_organized_by_month = [];
        //EL recorrido de los meses los hago aqui
        $meses_years = $this->getMonthsAndYears($array_options);

        foreach ($meses_years as $meses_year)
        {
            $mes = $meses_year['mes'];
            $year = $meses_year['year'];

            $result = $this->verifiedDataByMonth($array_options, $mes, $year);

        }



        if($result){
            //Puedo empezar a calcular los costos

            //EL recorrido de los meses los hago aqui
            $meses_years = $this->getMonthsAndYears($array_options);

            foreach ($meses_years as $meses_year)
            {
                $mes = $meses_year['mes'];
                $year = $meses_year['year'];

                //devuelve los resultados globales y las constantes para las maquinas
                $maquinas_with_general_constantes = $this->calculateCostosByMonth($mes, $year, $array_options);
                $data_organized_by_month[] = $maquinas_with_general_constantes;
            }



            //Obtengo las maquinas distinc
            $maquinas_distinct = $this->getMaquinasDistinct($data_organized_by_month);


            $new_lista_maquinas_with_data = null;
            //SI son varios meses se deben sumar las maquinas que coincidan
            //Si es solo un mes, no pasa nada

            //SI es un periodo, los costos se suman mes a mes

            $new_lista_maquinas_with_data = $this->resumeResultMaquinasFromMonths($data_organized_by_month, $maquinas_distinct);


            $centros_costos_array = $this->getCentroCostosDistinct($new_lista_maquinas_with_data);


            //Tabla centro de costos
            $tabla_centro_costos = $this->loadModel('CentrosCostos');

            //TRae los centros de utilizando el array de centro de costos filtrados
            $centros_costos = $tabla_centro_costos->find('all', [
            ])->where(['idcentros_costos IN' => $centros_costos_array])->toArray();

            $maquinas_by_centros_costos['general'] = $this->resumeGeneralData($data_organized_by_month);
            $maquinas_by_centros_costos['centros'] = $this->resumeDataMaquinasByCentroCostos($new_lista_maquinas_with_data, $centros_costos);


            //Proceso el informe


            debug($maquinas_by_centros_costos);

            //Cargo los resultados generales


        }






        //OBtengo los remitos disponibles
        //$array_remitos = $this->getRemitosByConditions($array_options);


        //debug($array_remitos);

        //Metodo nuevo

        //$total_ton_by_cat_centro_costo = $this->getTotalTonByCategoryCentroCostos($array_remitos);



        //A las options le restare los datos de las fechas
        $options['fecha_inicio'] = date("Y-m-d", strtotime($fec_in . "- 3 month"));
        $options['fecha_fin'] = $fecha_final;
        //$facturacion_by_item = $this->getFacturacionByItem($total_ton_by_cat_centro_costo, $options);
        //Tengo
        //$fact_pond_by_cat = $this->getFacturacionPonderadaByCategory($facturacion_by_item);

        //debug($facturacion_by_item)
        //debug($fact_pond_by_cat);


        //CAlculo del mai
        //$result_mai = $this->calculateMAIEconomico($facturacion_by_item, null);
        //debug($result_mai);

        //Result costos
        //$data_by_centro_costos = $this->calculateCostos($array_options);


        //$total_toneladas = $this->getTotalTonRemitos($array_remitos);

        //$aaray_res_globales = [];
        //AGrego las toneladas en resgloab
        $aaray_res_globales['total_toneladas'] = $total_toneladas;
       // $costo_total = $this->calculateCostoTotal($data_by_centro_costos);
        //$aaray_res_globales['costo_total'] = $costo_total;



        //$data_by_centro_costos['res_globales'] = $aaray_res_globales;

       // debug($data_by_centro_costos);

        if ($data_by_centro_costos != false) {


            //Tengo que calcular los costos backtime
            //Realizo el calculo 1 ano atras
            $fec_in = new Date($fecha_inicio);
            $date_year_inicio_back = date("Y-m-d", strtotime($fec_in . "- 1 year"));

            $fec_fin = new Date($fecha_final);
            $date_year_final_back = date("Y-m-d", strtotime($fec_fin . "- 1 year"));

            //remplazo el array options
            $array_options['fecha_inicio'] = $date_year_inicio_back;
            $array_options['fecha_fin'] = $date_year_final_back;

            $costos_one_year_back = $this->calculateCostos($array_options);


            //Calculo los 6 meses antes del inicio, la fecha final es 1 dia antes del inicio

            $fec_in = new Date($fecha_inicio);
            $date_six_inicio_back = date("Y-m-d", strtotime($fec_in . "- 6 month"));
            $date_six_final_back = $fecha_inicio;


            //remplazo el array options
            $array_options['fecha_inicio'] = $date_six_inicio_back;
            $array_options['fecha_fin'] = $date_six_final_back;

            $costos_six_back = $this->calculateCostos($array_options);



            //Proceso el informe
            if ($informe == 'true') {
                //Obtengo las variabes a almacenar
                $group_name = 'Todos';
                $lote_name = 'Todos';
                $parcelas_name = 'Todos';
                $propietarios_name = 'Todos';
                $destinos_name = 'Todos';

                $array_informe = [];

                $array_informe['worksgroups'] = $group_name;
                $array_informe['lote'] = $lote_name;
                $array_informe['parcela'] = $parcelas_name;
                $array_informe['propietario'] = $propietarios_name;
                $array_informe['destino'] = $destinos_name;
                $array_informe['fecha_inicio'] = $fecha_inicio;
                $array_informe['fecha_fin'] = $fecha_final;
                $array_informe['users_idusers'] = $user_id;
                $array_informe['empresas_idempresas'] = $id_empresa;

                //Agrego al ma resultado final los metadatos del informe
                $data_by_centro_costos['metadata'] = $array_informe;

                debug($data_by_centro_costos);
                //Llamo al metod de costos fijos  y varlables
                $data_costos_fijos_var = $this->calculateCostosFijosAndVariables($data_by_centro_costos, $array_informe, $array_remitos, $array_options);

                debug($data_costos_fijos_var);

                //Aca se define el informe
                //Compruebo que el resultado no sea false de $data_by_centro_costos
                if ($data_by_centro_costos != false) {
                    $data_by_centro_costos['informe'] = $this->processInforme($array_informe, $data_by_centro_costos, $costos_one_year_back, $costos_six_back);
                }


            } else {
                $data_by_centro_costos['informe'] = ['id' => '', 'informe' => false];
            }

        }
    }



    private function resumeGeneralData($data_organized_by_month, $maquinas_distinct)
    {
        $general = [
        'total_remitos' => null,
        'total_maquinas' => null,
        'toneladas' => null
        ];

        foreach ($data_organized_by_month as $data)
        {
            $general['total_remitos'] =   $general['total_remitos'] + $data['general']['total_remitos'];
            //$general['total_maquinas'] =   $general['total_maquinas'] + $data['general']['total_maquinas'];
            $general['toneladas'] =   $general['toneladas'] + $data['general']['toneladas'];
        }

        debug($general);

       // $general['total_maquinas'] = count($maquinas_distinct);
        return $general;

    }

    private function resumeDataMaquinasByCentroCostos($new_lista_maquinas_with_data, $centros_costos)
    {
        $this->autoRender = false;
        //debug($maquinas);
        //debug($maquinas[0]['centro_costos']);

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


    private function getCentroCostosDistinct($new_lista_maquinas_with_data)
    {
        $this->autoRender = false;
        //Recorro las maquinas y flitro los centros de costos
        $array_centros = [];

        foreach ($new_lista_maquinas_with_data as $maq){


            if(!empty($maq['centro_costos'])){

                $array_centros[$maq['centro_costos'][0]->idcentros_costos] = $maq['centro_costos'][0]->idcentros_costos;

            }

        }
        return $array_centros;
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


    private function getMaquinasDistinct($data_organized_by_month)
    {
        $array_maquinas_distinct = [];

        foreach ($data_organized_by_month as $data){
            foreach ($data['maquinas'] as $maq){

                $array_maquinas_distinct[$maq['idmaquinas']] = $maq['idmaquinas'];

            }
        }

        return $array_maquinas_distinct;

    }

    private function calculateGrupos($maquinas = null)
    {
        $this->autoRender = false;

        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        //Traigo las constantes
        $constantes_model = $this->loadModel('Constantes');

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


        $tabla_metodcostos = $this->loadModel('MetodCostos');

        $array_result = null;


        foreach ($maquinas as $maq){
            //Traigo la metodologia de costos

            //debug($maq);
            $metod = $tabla_metodcostos->find('getMetodCostosByHash', ['hash' => $maq['metod_costos']])
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

            //Cargo los valores de las variables
            $VAD = $maq['data']['VAD'];
            $VUN = $maq['data']['VUN'];
            $HTU = $maq['data']['HTU'];
            $HME = $maq['data']['HME'];
            $TIS = $maq['data']['TIS'];
            $FCI = $maq['data']['FCI'];
            $VAN = $maq['data']['VAN'];
            $HFU = $maq['data']['HFU'];
            $VUE = $maq['data']['VUE'];
            $CCT = $maq['data']['CCT'];
            $CLT = $maq['data']['CLT'];
            $COM = $maq['data']['COM'];
            $COH = $maq['data']['COH'];
            $LUB = $maq['data']['LUB'];
            $LUH = $maq['data']['LUH'];
            $SAL = $maq['data']['SAL'];

            $texto = 'DivisionByZeroError $e';



            $suma = "$CSE + $CVD";


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



            //Almaceno los resultados en un arreglo y los guardo como parte de la maquina


            $maq["result_metod"] = [
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

            //debug($maq_aux_);



            //Calculo los valores a mostrar
            $costo_hora = $interes + $seguro + $dep_maq + $dep_neum + $arreglos_maq + $cons_comb + $cons_lub + $operador +
                $mantenimiento + $administracion;
            if($HME > 0){
                $prod_rend_h = $maq['toneladas'] / $HME;
            } else {
                $prod_rend_h = null;
            }

            if($prod_rend_h > 0){
                $costo_t = $costo_hora / $prod_rend_h;
            } else {
                $costo_t = null;
            }



            $maq["costos"] = [
              "costo_h" => $costo_hora,
              "prod_rend_h" => $prod_rend_h,
              "costo_ton" => $costo_t,
              "toneladas" => $maq['toneladas'],
              "horas" => $HME
            ];

            $array_result[] = $maq;


        } //FOreach maquina

        return $array_result;

    }


    private function getRemitosByConditions($array_options)
    {
        $this->autoRender = false;


        $remitos_table = $this->loadModel('Remitos');

        $remitos = $remitos_table->find('RemitosByConditionsQuery',
            $array_options);

        $array_result = [];

        foreach ($remitos as $rem){

            $array_result[$rem->idremitos] = $rem->idremitos;
        }

        return $array_result;
    }


    private function getTotalTonRemitos($array_remitos = null)
    {

        $remitos_model = $this->loadModel('Remitos');
        $toneladas = $remitos_model->find('getTotalToneladas', $array_remitos);

        //debug($toneladas->toArray()[0]->sum);

        return $toneladas->toArray()[0]->sum;

    }

    private function getRemitosByArray($array_remitos = [])
    {
        $this->autoRender = false;

        $remitos_table = $this->loadModel('Remitos');

        $remitos = $remitos_table->find('RemitosByRemitos', $array_remitos);

        return $remitos;

    }


    /**
     * @return array devuelve las fechas por mes y a;o de los remitos
     */
    private function getResumenRemitosByMonth($remitos)
    {
        //resumen toneladas
        $toneladas_sum = null;
        $index_ = 0;
        $array_tons = [];

        //debug($remitos->toArray());
        foreach ($remitos as $rem){

            $month = date('m', strtotime($rem->fecha));
            $year = date('y', strtotime($rem->fecha));

            //Creo un arreglo con los meses y a;os
            if($index_ == 0){
                $array_tons[] = [
                    'month' => $month,
                    'year' => $year
                ];
                $index_++;

            } else {
                $flags_exist = false;
                foreach ($array_tons as $arr_t){

                    if(intval($arr_t['month']) == intval($month) && intval($arr_t['year']) == intval($year)){
                        //nothing
                        $flags_exist = false;

                    } else {
                        $flags_exist = true;
                    }
                }

                if($flags_exist){
                    //proceso
                    $array_tons[] = [
                        'month' => $month,
                        'year' => $year
                    ];

                }
            }

        }

        return $array_tons;
    }

    /**
     * @return array devuelve el resumen de las toneladas mensuales
     */
    private function getResumenTonsRemitosByMonths($remitos = null, $months = null)
    {
        $array_result = [];




        foreach ($months as $date){

            $month_date = $date['month'];
            $year_date = $date['year'];

            $tons_sum = null;
            //recorro los remitos

            foreach ($remitos as $rem){
                $month = date('m', strtotime($rem->fecha));
                $year = date('y', strtotime($rem->fecha));

                if($month_date == $month && $year_date == $year){
                    $tons_sum = $tons_sum + $rem->ton;
                }

            }

            $array_result[] = [
                'month' => $month_date,
                'year' => $year_date,
                'ton' => $tons_sum
            ];

        }

        return $array_result;
    }

    /**
     * @param $remitos
     * @param $servicios
     * @return array devuelve el arreglo con la facturacion por mes
     */
    private function getFacturacionByItem($total_ton_by_cat_centro_costo = null, $options = null)
    {
        $array_result = [];

        $servicios_model = $this->loadModel('Servicios');
        $servicios = $servicios_model->find('ServiciosByDate', $options);

        //ebug($servicios);

        ///debug($servicios->toArray());
        foreach ($total_ton_by_cat_centro_costo as $item) {

            //trigo el precio del servicio del mes o el mas cercano
            $precio_serv_by_rem = $this->getServicioForTonRemito($item, $servicios);


            $facturacion = $item['toneladas'] * $precio_serv_by_rem;
            $item['servicio'] = $precio_serv_by_rem;
            $item['facturacion'] = $facturacion;
            $array_result[] = $item;
        }


        return $array_result;
    }

    private function getFacturacionPonderadaByCategory($facturacion_by_item = null)
    {

        $array_result = [];
        $facturacion_total = null;
        $toneladas_total = null;

        //REcorro el arreglo
        foreach ($facturacion_by_item as $item){

            //Necesito diferenciar por tipo de centro
            if($item['Categoria'] == 'Elaboracion'){
                $facturacion_total = $facturacion_total + $item['facturacion'];
                $toneladas_total = $toneladas_total + $item['toneladas'];

            }


        }
        $res_result = null;
        if($toneladas_total > 0)
        {
            $res_result = $facturacion_total / $toneladas_total;
        }


        $array_result[] = [
            'categoria' => 'Elaboracion',
            'toneladas' => $toneladas_total,
            'facturacion' => $facturacion_total,
            'psmp' => $res_result
        ];

        $facturacion_total_trans = null;
        $toneladas_total_trans = null;

        //REcorro el arreglo
        foreach ($facturacion_by_item as $item){

            //Necesito diferenciar por tipo de centro
            if($item['Categoria'] == 'Transporte'){
                $facturacion_total_trans = $facturacion_total_trans + $item['facturacion'];
                $toneladas_total_trans = $toneladas_total_trans + $item['toneladas'];

            }


        }
        $res_result = null;
        if($toneladas_total_trans > 0)
        {
            $res_result = $facturacion_total_trans / $toneladas_total_trans;
        }

        $array_result[] = [
            'categoria' => 'Transporte',
            'toneladas' => $toneladas_total_trans,
            'facturacion' => $facturacion_total_trans,
            'psmp' => $res_result
        ];

        return $array_result;

    }


    /**
     * @param $remito_item es un elemento del array remito resumido
     * @param $servicios
     * @return float de precio del servicio
     */
    private function getServicioForTonRemito($remito_item = null, $servicios = null)
    {
        $price_servicio = null;

        $fecha_rem_ = date('Y-m-d', strtotime($remito_item['fecha']));
        $fecha_rem = new DateTime($fecha_rem_);


        //$fecha_rem = date_create($remito_item->fecha);;
        $array_rank = [];
        $index_ = 0;
        $day_back = null;


        foreach ($servicios as $serv){
            $fecha_serv_ = date('Y-m-d', strtotime($serv->fecha));
            $fecha_serv = new DateTime($fecha_serv_);
            //$fecha_serv = date_create($serv->fecha);

            if($fecha_serv_ <= $fecha_rem_){

                //Rankeo las fechas con el minimo numero de dias

                $dias = $fecha_rem->diff($fecha_serv)->days;


                if($index_ == 0){

                    $price_servicio = $serv;
                    $day_back = $dias;
                    $index_++;
                } else {

                    //COmparo dayback con dias
                    if($dias < $day_back){
                        $price_servicio = $serv;
                        $day_back = $dias;
                    }
                }

            }
        }

        return $price_servicio->precio;

    }


    private function getTotalTonByCategoryCentroCostos($remitos = null)
    {
        //$maq->costos_maquinas[0]['centros_costos'][0]['categoria']
        $arrar_result = [];

        $remitos_model = $this->loadModel('Remitos');
        $remitos_data = $remitos_model->find('RemitosByRemitos', $remitos);


        //Recorro las maquinas y si pertenecen al centro de costo, sumo y break


        foreach ($remitos_data as $rem)
        {
            foreach ($rem->remitos_maquinas as $rem_maq){

                if(isset($rem_maq->maquina->costos_maquinas[0]['centros_costos'][0]->categoria))
                {
                    if($rem_maq->maquina->costos_maquinas[0]['centros_costos'][0]->categoria == 'Elaboracion'){
                        //debug($rem_maq->maquina->costos_maquinas[0]['centros_costos'][0]);

                        //Si Encuentra agrega el remito a la lista
                        $arrar_result[] = [
                            'fecha' => $rem->fecha,
                            'toneladas' => $rem->ton,
                            'Categoria' => 'Elaboracion'
                        ];

                        break;
                    }
                }


            }
        }



        foreach ($remitos_data as $rem)
        {
            foreach ($rem->remitos_maquinas as $rem_maq){

                if($rem_maq->maquina->costos_maquinas[0]['centros_costos'][0]->categoria == 'Transporte'){
                    //debug($rem_maq->maquina->costos_maquinas[0]['centros_costos'][0]);

                    //Si Encuentra agrega el remito a la lista
                    $arrar_result[] = [
                        'fecha' => $rem->fecha,
                        'toneladas' => $rem->ton,
                        'Categoria' => 'Transporte'
                    ];

                    break;
                }
            }
        }

        return $arrar_result;
    }

    private function preparedDataByMaquina($remitos = null, $options = null)
    {
        //Tengo que recolectar toda la información primero
        //La Metodologia de Costos se esta definiendo en DATOS TEORICOS de las maquinas

        //Puedo Crear un arreglo con los datos calculados por maquina

        $tabla_remitosmaq = $this->loadModel('RemitosMaquinas');
        $tabla_maquinas = $this->loadModel('Maquinas');

        //Variable con las maquinas utilizadas en los remitos filtrados
        $maquinas_array =  $tabla_remitosmaq->find('getMaquinasByRemitos', $remitos);


        $date_start = $options['fecha_inicio'];
        $date_end = $options['fecha_fin'];

        $conditions['fecha >='] = date($date_start);
        $conditions['fecha <='] = date($date_end);

        $conditions_usos['fecha >='] = date($date_start);
        $conditions_usos['fecha <='] = date($date_end);

        $conditions_op['created >='] = date($date_start);
        $conditions_op['created <='] = date($date_end);



        if($maquinas_array != false)
        {

            //debug($conditions);
            //Tamb le paso la condicion a los remitos

            $maquinas_filter = $tabla_maquinas->find('all', [
                'contain' => ['Remitos' =>
                    function ($q) use ($remitos) {
                        return $q->where(['idremitos IN' => $remitos]);
                    },
                    'ArreglosMecanicos' =>
                        function ($q) use ($conditions) {
                            return $q->where($conditions);
                        }
                    , 'UsoMaquinaria' =>
                        function ($q) use ($conditions_usos){
                            return $q->where($conditions_usos)
                                ->contain('UsoCombLub');
                        },
                    'OperariosMaquinas' =>
                        function ($q) use ($conditions_op){
                            return $q->where($conditions_op)
                                ->contain('Operarios');
                        }, 'CostosMaquinas' =>
                        function ($q) {
                            return $q->where(['CostosMaquinas.active' => true])
                                ->contain('CentrosCostos');
                        }]])
                ->where(['idmaquinas IN' => $maquinas_array])
                ->toArray();

            return $maquinas_filter;
        }

        return false;
    }





    private function calculateCostosFijosAndVariables($data_by_centro_costos, $array_informe, $array_remitos, $options)
    {
        //Preparo los datos generales
        $costo_total = null;
        $toneladas = null;
        $costo_variable = null;
        $costo_fijo = null;
        $costo_semi_fijos = null;
        $servicio_elaboracion = null;
        $servicio_transporte = null;
        $mai_economico = null;
        $mai_financiero = null;
        $facturacion = null;

        //debug($data_by_centro_costos['res_globales']['total_toneladas']);
        $toneladas = $data_by_centro_costos['res_globales']['total_toneladas'];

        foreach ($data_by_centro_costos['centros'] as $centros){

            //debug($centros);
            $costo_total = $costo_total + $centros['costo_total'];


            foreach ($centros['maquinas'] as $maq){
                //Calculo de costo FIjo
                $costo_fijo = $costo_fijo + $maq['result_metod']['interes'] + $maq['result_metod']['seguro'];
            }
        }

        //Cargoa de las variables
        $costo_variable = $this->calculateCostosVariables($data_by_centro_costos['centros']);


        //Calculo el Costo de ELaboracion y el Costo de Transporte

        $costo_transporte = $this->calculateCostosByCategory($costo_total, $data_by_centro_costos['centros'], 'Transporte');
        $costo_produccion = $this->calculateCostosByCategory($costo_total, $data_by_centro_costos['centros'], 'Elaboracion');

        //CAlculo el precio del servicio ponderado

        $total_ton_by_cat_centro_costo = $this->getTotalTonByCategoryCentroCostos($array_remitos);
        //A las options le restare los datos de las fechas
        $options['fecha_inicio'] = date("Y-m-d", strtotime( $options['fecha_inicio'] . "- 3 month"));

        $facturacion_by_item = $this->getFacturacionByItem($total_ton_by_cat_centro_costo, $options);
        $fact_pond_by_cat = $this->getFacturacionPonderadaByCategory($facturacion_by_item);

        //Recorro con foreach y vargo los valores de servicio


        foreach ($fact_pond_by_cat as $item){

            if($item['categoria'] == 'Elaboracion')
            {
                $servicio_elaboracion = $item;
            }
            if($item['categoria'] == 'Transporte')
            {
                $servicio_transporte = $item;
            }

        }

        $data_resumen = [
            'costo_total' => $costo_total,
            'costo_fijo' => $costo_fijo,
            'toneladas' => $toneladas,
            'servicio_elaboracion' => $servicio_elaboracion,
            'servicio_transporte' => $servicio_transporte,
            'costo_variable'=> $costo_variable,
            'mai_economico' => $mai_economico,
            'mai_financiero' => $mai_financiero,
            'costo_transporte' => $costo_transporte,
            'costo_produccion' => $costo_produccion
        ];

        return $data_resumen;
    }


    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function createdInforme($data = null, $array_informe = null, $data_year_back = [], $data_six_month_back = [])
    {
        //debug($array_informe);

        $informes_model = $this->loadModel('Informes');

        $entity_informe = $informes_model->newEntity();

        //Primero creo el informe, lo almaceno y luego lo paso a la base de datos
        $result = $this->createdExcel($data, $array_informe, $data_year_back, $data_six_month_back);


        //GUARDO EL INFORME EN LA TABLA
        if($result != false){

            $array_informe['path_file'] = $result['path'];
            $array_informe['name'] = $result['name'];
            $entity_informe_ = $informes_model->patchEntity($entity_informe, $array_informe);

            //DEvuelvo un arreglo con la operacion y el id

            if ($informes_model->save($entity_informe_)) {
                return ['id' => $entity_informe_->idinformes, 'informe' => true, 'path' => $result['path']];
            }

            return ['id' => '', 'informe' => false];
        } else {
            return ['id' => '', 'informe' => false];
        }
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * Data corresponde a los datos del analisis principal
     * Necesito un data2 y un data 3 para obtener el reumen del ultimo a;o y los ultimos 6 meses
     * data_resumen es el resultado de vcostos fijos y variables
     **/
    private function  createdExcel($data = null, $data_resumen = null, $data_year_back = [],
                                   $data_six_month_back = [])
    {

        $this->viewBuilder()->setLayout(null);
        $this->autoRender = false;



        /*debug('costo total: ' .  $costo_total);
        debug('toneladas: ' . $toneladas);
        debug('servicio: ' .$servicio);
        debug('facturacion: ' .$facturacion);
        debug('mai economico: ' .$mai_economico);
        debug('mai financiero: ' .$mai_financiero);
        debug('Costo var: ' .$costo_variable);*/

        /*foreach ($data['centros'] as $centros)
        {
            $costo_total = $costo_total + $centros['costo_total'];
            $toneladas = $toneladas + $centros['toneladas_total'];

            //Recorro las maquinas de los centros de costos

            foreach ($data['maquinas'] as $maq){

                //Calculo de costo FIjo
                $costo_fijo = $costo_fijo + $maq['result_metod']['interes'] + $maq['result_metod']['interes'];

                //Calculo de los costos semifijos
                //REVISAR LOS PUNTOS MARCADOS EN EL PDF RESPECTO DE ESTA SUMA
                $costo_semi_fijos = $costo_semi_fijos + $maq['result_metod']['dep_maq'] + $maq['result_metod']['dep_neum'] +
                    $maq['result_metod']['arreglos_maq'];


                //CALCULO DE COSTOS VARIABLES
                //Rearmar la lista de variables para poder calcular
                $costo_variable = $costo_variable + $maq['result_metod']['dep_maq'] + $maq['result_metod']['dep_neum'] +
                    $maq['result_metod']['dep_maq'] + $maq['result_metod']['dep_neum'] +
                    $maq['result_metod']['dep_maq'] + $maq['result_metod']['dep_neum'];

            }

        }*/

        //Falta agregar la discriminacion por CEntro de costos

        $spreadsheet = new Spreadsheet();

        $myWorkSheet_res =  $this->createdSheetResumen($data, $spreadsheet, $data_resumen, $data_year_back, $data_six_month_back);
        $myWorkSheet_maq =  $this->createdSheetMaquinas($data['centros'], $spreadsheet);

        //$spreadsheet->removeSheetByIndex(2);

        //utilizo el now, es mejor
        $nombre = "informe_" .hash('sha256' , (date("Y-m-d H:i:s")));

        $path = EXCELS . $nombre .'.xlsx';
        $path_short = EXCELS_SHORT . $nombre .'.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        //consulto por el fileexist
        $file_test = new File($path);
        if($file_test->exists()){

            return ['name' => $nombre, 'path' => $path_short];
        } else {
            return false;
        }

    }


    private function createdSheetResumen($data = null, $spreadsheet = null, $data_resumen = [], $data_year_back = [], $data_six_month_back = [])
    {
        $this->viewBuilder()->setLayout(null);
        $this->autoRender = false;


        $font_bold = [
            'font' => [
                'bold' => true
            ]
        ];

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //TRaigo el informe
        try {
            //TRigo el logo de la empresa
            $empresa_model = $this->loadModel('Empresas');
            $empresas_data = $empresa_model->get($id_empresa);

            //configuro el path y el file
            $path = null;

            if($empresas_data->logo == null or empty($empresas_data->logo))
            {
                //logo default
                $path = LOGOS . 'edificio.png';
            }  else {
                $path = LOGOS . $empresas_data->logo;
            }



            $myWorkSheet_res = new Worksheet($spreadsheet, 'Resumen');

            $spreadsheet->addSheet($myWorkSheet_res, 0);
            $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');

            //Combino la primer celda para porner el titulo y configuro una altura aceptable
            $myWorkSheet_res->mergeCells('B1:J1');
            $myWorkSheet_res->getRowDimension('1')->setRowHeight(75);

            //EL titulo tiene que decir Informe de Costo - NOmbre de empresa
            $empresa_name = $empresas_data->name;
            $titulo = 'Informe de Costos - ' . $empresa_name;

            $myWorkSheet_res->setCellValue('B1', $titulo);

            $myWorkSheet_res->getStyle('B1')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('B1')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('B1')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getStyle('B1')->getFont()->setBold(true)->setName('Arial')
                ->setSize(14);

            //DIBUJO EL LOGO

            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath( $path);
            $drawing->setHeight(75);
            $drawing->setWidth(75);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(45);
            $drawing->setOffsetY(15);
            $drawing->setWorksheet($myWorkSheet_res);


            //Avanzo con el primer box de información
            //DEjo un row de distancia, empiezo desde el row 3
            //Tiene 6 columnas de dimension

            $myWorkSheet_res->mergeCells('A2:J2');
            $myWorkSheet_res->getRowDimension('2')->setRowHeight(45);

            $myWorkSheet_res->mergeCells('A3:F3');
            $myWorkSheet_res->setCellValue('A3', 'Datos considerados en el análisis');

            $myWorkSheet_res->getStyle('A3')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setVertical('center');

            $myWorkSheet_res->setCellValue('A4', 'Grupo:');
            $myWorkSheet_res->setCellValue('B4', $data['metadata']['worksgroups']);
            $myWorkSheet_res->setCellValue('C4', 'Período:');
            $myWorkSheet_res->setCellValue('D4', 'de: '. $data['metadata']['fecha_inicio']. ' a '. $data['metadata']['fecha_fin']);

            $myWorkSheet_res->setCellValue('A5', 'Lote:');
            $myWorkSheet_res->setCellValue('B5', $data['metadata']['lote']);
            $myWorkSheet_res->setCellValue('C5', 'Parcela:');
            $myWorkSheet_res->setCellValue('D5', $data['metadata']['parcela']);
            $myWorkSheet_res->setCellValue('E5', 'Propietario:');
            $myWorkSheet_res->setCellValue('F5', $data['metadata']['propietario']);

            $myWorkSheet_res->setCellValue('A6', 'Industria destino:');
            $myWorkSheet_res->setCellValue('B6', $data['metadata']['destino']);

            $myWorkSheet_res->getRowDimension('3')->setRowHeight(25);
            $myWorkSheet_res->getRowDimension('4')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('5')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('6')->setRowHeight(17);


            $myWorkSheet_res->getStyle('A4')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A4')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A4')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('C4')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('C4')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('c4')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('A5')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A5')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A5')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('C5')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('C5')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('C5')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('E5')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('E5')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('E5')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('A6')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A6')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A6')->getAlignment()->setVertical('center');



            $myWorkSheet_res->getStyle('B4')->getAlignment()->setIndent(1);
            $myWorkSheet_res->getStyle('F5')->getAlignment()->setIndent(1);


            foreach (range('A4:J4', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }
            foreach (range('A5:J5', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }
            foreach (range('A6:J6', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////
            //Segundo BOX, LO HAGO CON BORDES, Empiezo desde A8
            $myWorkSheet_res->mergeCells('A7:J7');
            $myWorkSheet_res->getRowDimension('7')->setRowHeight(45);

            $myWorkSheet_res->getRowDimension('9')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('10')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('11')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('12')->setRowHeight(17);


            $myWorkSheet_res->mergeCells('A8:D8');
            $myWorkSheet_res->setCellValue('A8', 'Resumen de resultados');

            $myWorkSheet_res->getStyle('A8')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A8')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A8')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getRowDimension('8')->setRowHeight(25);
            $myWorkSheet_res->getStyle('A8:D8')->applyFromArray($styleArray);

            $myWorkSheet_res->setCellValue('A9', 'Toneladas producidas (t):');
            $myWorkSheet_res->setCellValue('A10', 'Costo total por t ($/t):');
            $myWorkSheet_res->setCellValue('A11', 'Costo variable ($/t):');
            $myWorkSheet_res->setCellValue('A12', 'Costo fijo ($/t):');

            $myWorkSheet_res->setCellValue('C10', 'MAI económico ($/t):');
            $myWorkSheet_res->setCellValue('C11', 'MAI transporte ($/t):');


            //Convertir todos estos valores a enteros

            $ton = intval($data_resumen['toneladas']);
            $myWorkSheet_res->getStyle('B9')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B9', $ton, DataType::TYPE_NUMERIC);

            $costo_tot = intval($data_resumen['costo_total']);
            $myWorkSheet_res->getStyle('B10')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B10', $costo_tot, DataType::TYPE_NUMERIC);


            $costo_var = intval($data_resumen['costo_variable']);

            $myWorkSheet_res->getStyle('B11')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B11', $costo_var, DataType::TYPE_NUMERIC);


            $costo_fijo = intval($data_resumen['costo_fijo']);
            $myWorkSheet_res->getStyle('B12')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B12', $costo_fijo, DataType::TYPE_NUMERIC);


            $mai_ = intval($data_resumen['mai_economico']);
            $myWorkSheet_res->getStyle('D10')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('D10', $mai_, DataType::TYPE_NUMERIC);

            $mai_fin = intval($data_resumen['mai_financiero']);
            $myWorkSheet_res->getStyle('D11')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('D11', $mai_fin, DataType::TYPE_NUMERIC);

            $myWorkSheet_res->getStyle('A9:D12')->applyFromArray($styleArray);


            $myWorkSheet_res->getStyle('A9')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A9')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A9')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('A10')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A10')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A10')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('A11')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A11')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A11')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('A12')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A12')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A12')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('C10')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('C10')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('C10')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getStyle('C10')->getAlignment()->setIndent(1);

            $myWorkSheet_res->getStyle('C11')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('C11')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('C11')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getStyle('C11')->getAlignment()->setIndent(1);


            $myWorkSheet_res->getStyle('B9')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('B9')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('B10')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('B10')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('B11')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('B11')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('B12')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('B12')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('D10')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('D10')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('D11')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('D11')->getAlignment()->setVertical('center');

            foreach (range('A9:D9', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }
            foreach (range('A10:D10', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }
            foreach (range('A11:D11', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }

            foreach (range('A12:D12', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////
            //Tercer BOX, LO HAGO CON BORDES, Empiezo desde A16


            $myWorkSheet_res->getRowDimension('16')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('17')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('18')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('19')->setRowHeight(17);


            $myWorkSheet_res->mergeCells('A16:D16');
            $myWorkSheet_res->setCellValue('A16', 'Costos y márgenes de Elaboración y Transporte');

            $myWorkSheet_res->getStyle('A16')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A16')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A16')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getRowDimension('16')->setRowHeight(25);
            $myWorkSheet_res->getStyle('A16:D16')->applyFromArray($styleArray);

            $myWorkSheet_res->setCellValue('A17', 'Costo de Elaboración ($/t):');
            $myWorkSheet_res->setCellValue('A18', 'Costo de Transporte ($/t):');


            $cos_prod = intval($data_resumen['costo_produccion']);
            $myWorkSheet_res->getStyle('B17')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B17', $cos_prod, DataType::TYPE_NUMERIC);

            $cos_trns = intval($data_resumen['costo_transporte']);
            $myWorkSheet_res->getStyle('B18')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B18', $cos_trns, DataType::TYPE_NUMERIC);


            $myWorkSheet_res->setCellValue('C17', 'MAI económico ($/t):');
            $myWorkSheet_res->setCellValue('C18', 'MAI transporte ($/t):');


            $myWorkSheet_res->getStyle('B17')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('B17')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getStyle('B18')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('B18')->getAlignment()->setVertical('center');




            ////////////////////////////////////////////////////////////////////////////////////////////////
            //Cuarto BOX - RESUMEN 1 A;O ATRAS - COMIENZA EN LA CELDA 21


            $myWorkSheet_res->getRowDimension('23')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('24')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('25')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('26')->setRowHeight(17);


            //titulo
            $myWorkSheet_res->mergeCells('A23:B23');
            $myWorkSheet_res->setCellValue('A23', 'Resumen de Toneladas por Centro de Costos');

            $myWorkSheet_res->getStyle('A23')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A23')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A23')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getRowDimension('23')->setRowHeight(25);
            $myWorkSheet_res->getStyle('A23:B23')->applyFromArray($styleArray);

            $myWorkSheet_res->setCellValue('A24', 'Toneladas extraídas:');

            //Las demas celdas se general segun los Centros de costos
            //Recoro $data['centros'] y alli obtengo las toneladas //toneladas_total name

            $index_row = 24;

            foreach ($data['centros'] as $centro)
            {
                $celda = 'A' . $index_row;
                $myWorkSheet_res->setCellValue($celda, $centro['name']);

                //Completo los valores

                $celda_value = 'B' . $index_row;
                $toneladas = intval($centro['toneladas_total']);
                $myWorkSheet_res->getStyle($celda_value)->getNumberFormat()->setFormatCode('#,##0');
                $myWorkSheet_res->setCellValueExplicit($celda_value, $toneladas, DataType::TYPE_NUMERIC);
                $index_row++;
            }

            //sumo 4 posiciones al index
            $index_row = $index_row + 4;

            $cell_a = 'A' . $index_row;
            $cell_b = 'B' . $index_row;



            ///RESUMEN DE UN A;O ATRAS

            //titulo
            $myWorkSheet_res->mergeCells($cell_a. ':' . $cell_b);
            $myWorkSheet_res->setCellValue($cell_a, 'Resumen de Resultados / 1 año');

            $myWorkSheet_res->getStyle($cell_a)->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle($cell_a)->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle($cell_a)->getAlignment()->setVertical('center');
            $myWorkSheet_res->getRowDimension($index_row)->setRowHeight(25);
            $myWorkSheet_res->getStyle($cell_a. ':' . $cell_b)->applyFromArray($styleArray);

            $index_row++;
            //escribo el texto de la primer celda de resultados

            $myWorkSheet_res->setCellValue('A' . $index_row, 'Toneladas extraídas:');

            //el Primer resultado es la sumatoria de las toneladas
            $toneladas_total_1year = null;

            foreach ($data_year_back['centros'] as $centro)
            {

                $toneladas_total_1year = $toneladas_total_1year + $centro['toneladas_total'];

            }

            $celda_value = 'B' . $index_row;
            $toneladas_total_1year = intval($toneladas_total_1year);
            $myWorkSheet_res->getStyle($celda_value)->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit($celda_value, $toneladas_total_1year, DataType::TYPE_NUMERIC);

            $index_row++;

            $costo_total_1year = null;

            //Cargo los valores de los centros de costos
            foreach ($data_year_back['centros'] as $centro)
            {

                $celda = 'A' . $index_row;
                $myWorkSheet_res->setCellValue($celda, $centro['name']);

                //Completo los valores
                $celda_value = 'B' . $index_row;
                $toneladas = intval($centro['toneladas_total']);
                $myWorkSheet_res->getStyle($celda_value)->getNumberFormat()->setFormatCode('#,##0');
                $myWorkSheet_res->setCellValueExplicit($celda_value, $toneladas, DataType::TYPE_NUMERIC);

                $costo_total_1year = $costo_total_1year + $centro['costo_total'];

                $index_row++;

            }
            $myWorkSheet_res->setCellValue('A' . $index_row, 'Costo total');

            $myWorkSheet_res->getStyle('B' . $index_row)->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B' . $index_row, $costo_total_1year, DataType::TYPE_NUMERIC);


            return $myWorkSheet_res;

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }


    }



    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function createdSheetMaquinas($data = null, $spreadsheet = null)
    {

        $this->viewBuilder()->setLayout(null);
        $this->autoRender = false;

        //CAculo el costo total para obtener el porcentaje

        $costo_total = 0;

        foreach ($data as $centro){

            $costo_total = $costo_total + $centro['costo_total'];
        }



        $font_bold = [
            'font' => [
                'bold' => true
            ]
        ];

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];


        $myWorkSheet_maq = new Worksheet($spreadsheet, 'Maquinas');

        $spreadsheet->addSheet($myWorkSheet_maq, 1);

        $myWorkSheet_maq->setCellValue('A1', 'Distribución por Centro de Costos');
        $myWorkSheet_maq->mergeCells('A1:G1');

        $myWorkSheet_maq->getStyle('A1')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('A1')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('A1')->getAlignment()->setVertical('center');
        $myWorkSheet_maq->getRowDimension('1')->setRowHeight(35);
        $myWorkSheet_maq->getStyle('A1:G1')->applyFromArray($styleArray);


        $i = 3;

       foreach ($data as $centro){

           //SI es cero estoy en el inicio, seteo la cabecera
           if($i == 3)
           {

               $porc =  $costo_total != 0 ? ($centro['costo_total'] * 100 / $costo_total) : 0;
               $porc = number_format($porc, 2, ',', '.');

               $myWorkSheet_maq->setCellValue('A2', $centro['name']);
               $myWorkSheet_maq->setCellValue('B2', 'Toneladas');
               $myWorkSheet_maq->setCellValue('C2', 'Costo/t');
               $myWorkSheet_maq->setCellValue('D2', 'Horas');
               $myWorkSheet_maq->setCellValue('E2', 't/h');
               $myWorkSheet_maq->setCellValue('F2', 'Costo/h');
               $myWorkSheet_maq->setCellValue('G2', $porc. '% costo total');


               $myWorkSheet_maq->getStyle('A2')->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('A2')->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('A2')->getAlignment()->setVertical('center');
               $myWorkSheet_maq->getRowDimension('2')->setRowHeight(25);

               $myWorkSheet_maq->getStyle('B2')->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('B2')->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('B2')->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('C2')->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('C2')->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('C2')->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('D2')->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('D2')->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('D2')->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('E2')->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('E2')->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('E2')->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('F2')->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('F2')->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('F2')->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('G2')->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('G2')->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('G2')->getAlignment()->setVertical('center');


               foreach (range('A1:G1', $myWorkSheet_maq->getHighestColumn()) as $col) {
                   $myWorkSheet_maq->getColumnDimension($col)->setAutoSize(true);
               }


               //cArgo la info de cabecera, PUedo especificar el row directamente


               $myWorkSheet_maq->setCellValue('B3', number_format($centro['toneladas_total'], 2, ',', '.'));
               $myWorkSheet_maq->setCellValue('C3',  number_format($centro['costo_total'], 2, ',', '.'));

               $myWorkSheet_maq->getStyle('B3')->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('B3')->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('C3')->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('C3')->getAlignment()->setVertical('center');


               $myWorkSheet_maq->getRowDimension('3')->setRowHeight(20);


               /*$myWorkSheet_maq->setCellValue('D3',  $centro['horas']);
               $myWorkSheet_maq->setCellValue('E3', $centro['ton_h']);
               $myWorkSheet_maq->setCellValue('F3', 'Costo/h');
               $myWorkSheet_maq->setCellValue('G3', '% costo total');*/

               $i++;

           } else {
               //SUmo para seguir con el siguiente centro
               $i++;
               $i++;

               $porc =  $costo_total != 0 ? ($centro['costo_total'] * 100 / $costo_total) : 0;
               $porc = number_format($porc, 2, ',', '.');

               $myWorkSheet_maq->setCellValue('A'.$i, $centro['name']);
               $myWorkSheet_maq->setCellValue('B'.$i, 'Toneladas');
               $myWorkSheet_maq->setCellValue('C'.$i, 'Costo/t');
               $myWorkSheet_maq->setCellValue('D'.$i, 'Horas');
               $myWorkSheet_maq->setCellValue('E'.$i, 't/h');
               $myWorkSheet_maq->setCellValue('F'.$i, 'Costo/h');
               $myWorkSheet_maq->setCellValue('G'.$i, $porc. '% costo total');

               $myWorkSheet_maq->getStyle('A'.$i)->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('A'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('A'.$i)->getAlignment()->setVertical('center');
               $myWorkSheet_maq->getRowDimension($i)->setRowHeight(25);

               $myWorkSheet_maq->getStyle('B'.$i)->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('C'.$i)->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('D'.$i)->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('D'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('D'.$i)->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('E'.$i)->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('E'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('E'.$i)->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('F'.$i)->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('F'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('F'.$i)->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('G'.$i)->applyFromArray($font_bold);
               $myWorkSheet_maq->getStyle('G'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('G'.$i)->getAlignment()->setVertical('center');

               $i++;

               //cArgo la info de cabecera, PUedo especificar el row directamente

               $myWorkSheet_maq->setCellValue('B'.$i, number_format($centro['toneladas_total'], 2, ',', '.'));
               $myWorkSheet_maq->setCellValue('C'.$i,  number_format($centro['costo_total'], 2, ',', '.'));


               $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setVertical('center');

               $myWorkSheet_maq->getRowDimension($i)->setRowHeight(20);

               /*$myWorkSheet_maq->setCellValue('D'.$i,  $centro['horas']);
               $myWorkSheet_maq->setCellValue('E'.$i,  $centro['ton_h']);
               $myWorkSheet_maq->setCellValue('F'.$i, 'Costo/h');
               $myWorkSheet_maq->setCellValue('G'.$i, '% costo total');*/
               $i++;

           }

           //Recorro las maquinas
           /*foreach ($centro['maquinas'] as $maq){
               $i++;
               $myWorkSheet_maq->setCellValue('A'.$i, $maq['name']);
               $myWorkSheet_maq->setCellValue('B'.$i, number_format($maq['costos']['toneladas'], 2, ',', '.'));
               $myWorkSheet_maq->setCellValue('C'.$i,   number_format($maq['costos']['costo_ton'], 2, ',', '.'));
               $myWorkSheet_maq->setCellValue('D'.$i, $maq['costos']['horas']);
               $myWorkSheet_maq->setCellValue('E'.$i,  number_format($maq['costos']['prod_rend_h'], 2, ',', '.'));
               $myWorkSheet_maq->setCellValue('F'.$i,  number_format($maq['costos']['costo_h'], 2, ',', '.'));

               //Si la maquina es alquilada lo digo o no
               if($maq['alquiler']){
                   $myWorkSheet_maq->setCellValue('G'.$i, 'Servicio rentado');
               } else {
                   $myWorkSheet_maq->setCellValue('G'.$i, 'Máquina propia');
               }

               $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setVertical('center');


               $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setVertical('center');


               $myWorkSheet_maq->getStyle('D'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('D'.$i)->getAlignment()->setVertical('center');


               $myWorkSheet_maq->getStyle('E'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('E'.$i)->getAlignment()->setVertical('center');


               $myWorkSheet_maq->getStyle('F'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('F'.$i)->getAlignment()->setVertical('center');


               $myWorkSheet_maq->getStyle('G'.$i)->getAlignment()->setHorizontal('center');
               $myWorkSheet_maq->getStyle('G'.$i)->getAlignment()->setVertical('center');


           }*/

       }
        return $myWorkSheet_maq;

    }


    private function calculateCostoTotal($data_by_centro_costos = [])
    {

        $costo_result = null;

        //Recorro cada uno de los centros y multiplico COSTO/t * Toneladas + COSTO/t n * TOneladas

        foreach ($data_by_centro_costos['centros'] as $centro)
        {
            $costo_result = $costo_result + ($centro['costo_total'] * $centro['toneladas_total']);

        }


        return $costo_result;
    }

    private function calculateCostosByCategory($costo_total = null, $array_data = [], $option = null)
    {

        //debug($array_data);

        $costo_transporte = null;
        //OBtengo el costo de transporte
        foreach ($array_data as $centro){

            if($centro['categoria'] == 'Transporte'){
                $costo_transporte = $costo_transporte + $centro['costo_total'];
            }
        }

        if($option == 'Elaboracion'){

            return $costo_total - $costo_transporte;

        } elseif ($option == 'Transporte')
        {
            return $costo_transporte;
        }
        return null;
    }


    private function calculateMAIEconomico($facturacion_by_item = null)
    {

        //Segun la planilla necesito usar HME

        $precio_medio_ponderado = null;
        $toneladas_total = null;
        $sum_total = null;

        //Sumo la facturacion en Produccion y facturacion en transporte y divido por costo total
        foreach ($facturacion_by_item as $item)
        {
            //(tn1 * serv + tonn * servn) / (tn1 + tn2 + tnn)
            $sum_total = $sum_total + $item['facturacion'];
            $toneladas_total = $toneladas_total + $item['toneladas'];

        }

        $psmp = $toneladas_total == 0 ? null : ($sum_total / $toneladas_total);

        //le resto el costo total



        return null;
    }

    //Recibe el arreglo con los datos de gastos
    private function calculateCostosVariables($data = [])
    {

        $operario_costo = null;
        $combustible_costo = null;
        $lubricante_costo = null;


        foreach ($data as $centro)
        {
            foreach ($centro['maquinas'] as $maq)
            {
                $operario_costo = $operario_costo + $maq['gastos']['gasto_sueldo'];
                $combustible_costo = $combustible_costo + $maq['gastos']['gasto_combustible'];
                $lubricante_costo = $lubricante_costo + $maq['gastos']['gasto_lubricante'];
            }

        }
        //TRaigo las constantes de mantenimiento y administracion
        $cad = 'CAD';
        $cma = 'CMA';

        $cad_value = null;
        $cma_value = null;

        $constantes_model = $this->loadModel('Constantes');
        $constantes_data = $constantes_model->find('all', [
            'fields' => ['name', 'value'],
            'conditions' => [
                'OR' => [
                    ['name' => $cad],
                    ['name' => $cma]
                ]
            ]
        ])->where(['active' => true])->toArray();


        if(count($constantes_data) > 0){

            foreach ($constantes_data as $cons)
            {
                if($cons['name'] == $cad){
                    $cad_value = $cons['value'];
                }
                if($cons['name'] == $cma){
                    $cma_value = $cons['value'];
                }
            }
            //Calculo el costo variable como la suma de todos los elementos
            $costo_variable = $combustible_costo + $lubricante_costo + $operario_costo + $cad_value + $cma_value;
            return $costo_variable;
        }

        return null;

    }




}
