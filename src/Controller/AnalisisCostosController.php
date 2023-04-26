<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Utility\AnalisisCostosGrupos;
use App\Utility\ExcelProcesssing;
use App\Utility\GetFunctions;
use Cake\I18n\Date;

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



    }


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
        if($this->request->is('ajax')) {


            //COnsulto que los indices esten definidos
            $worksgroup = $_POST['groups'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_final = $_POST['fecha_final'];
            $lotes = $_POST['lotes'];
            $parcelas = $_POST['parcelas'];
            $propietarios = $_POST['propietarios'];
            $destinos = $_POST['destinos'];
            $informe = $_POST['informe'];

            $array_options['worksgroup'] = $worksgroup;
            $array_options['fecha_inicio'] = $fecha_inicio;
            $array_options['fecha_fin'] = $fecha_final;
            $array_options['lotes_idlotes'] = $lotes;
            $array_options['parcelas_idparcelas'] = $parcelas;
            $array_options['propietarios_idpropietarios'] = $propietarios;
            $array_options['destinos_iddestinos'] = $destinos;
            $array_options['empresas_idempresas'] = $id_empresa;


            $array_options_['worksgroup'] = $worksgroup;
            $array_options_['fecha_inicio'] = $fecha_inicio;
            $array_options_['fecha_fin'] = $fecha_final;
            $array_options_['lotes_idlotes'] = $lotes;
            $array_options_['parcelas_idparcelas'] = $parcelas;
            $array_options_['propietarios_idpropietarios'] = $propietarios;
            $array_options_['destinos_iddestinos'] = $destinos;
            $array_options_['empresas_idempresas'] = $id_empresa;


            //INstancio la clase GetFunction
            $get_function_class = New GetFunctions();

            //instancio la clase AalisisCostos
            $analisis_costos_class =  new AnalisisCostosGrupos();

            //EL recorrido de los meses los hago aqui
            $meses_years = $get_function_class->getMonthsAndYearsWithLast($array_options);

            $result = true;


            foreach ($meses_years as $meses_year)
            {
                $mes = $meses_year['mes'];
                $year = $meses_year['year'];

                $result = $analisis_costos_class->verifiedDataByMonth($array_options, $mes, $year);

                //SI uno ya es false, cancelo la operacion
                if(!$result){
                    break;
                }

            }


            if($result) {
                //Utilizo la clase para calcular los costos
                //devuelve un arreglos con los datos de la maquina
                $costos_maquinas = $analisis_costos_class->analisisDeCostosGrupos($array_options, $id_empresa);
                //debug($costos_maquinas);

                //Tengo que calcular los costos backtime
                $date_six_inicio_back = $get_function_class->getDateSixMonthsBack($fecha_inicio);
                $date_six_final_back = $get_function_class->getDateSixMonthsBack($fecha_final);

                //remplazo el array options
                $array_options_['fecha_inicio'] = $date_six_inicio_back;
                $array_options_['fecha_fin'] = $date_six_final_back;

                $costos_maquinas_six_back = null;

                $costos_maquinas_six_back = $analisis_costos_class->analisisDeCostosGrupos($array_options_, $id_empresa);


                $date_year_inicio_back = $get_function_class->getDateOneYearBack($fecha_inicio);
                $date_year_final_back = $get_function_class->getDateOneYearBack($fecha_final);

                //remplazo el array options
                $array_options_['fecha_inicio'] = $date_year_inicio_back;
                $array_options_['fecha_fin'] = $date_year_final_back;

                $costos_one_year_back = null;
                $costos_one_year_back = $analisis_costos_class->analisisDeCostosGrupos($array_options_, $id_empresa);

                $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options);
                $metadata['users_idusers'] = $user_id;
                $array_result = null;



                if($informe == 'true')
                {

                    //Llamo a excel processing
                    $excel_processing_class = new ExcelProcesssing();
                    $informe = $excel_processing_class->createInformeGrupos($metadata, $costos_maquinas, $costos_one_year_back, $costos_maquinas_six_back);

                    $array_result = [
                        'costos' => $costos_maquinas,
                        'informe' => $informe
                    ];
                } else {



                    $array_result = [
                        'costos' => $costos_maquinas,
                        'informe' => false
                    ];
                }



                return $this->json($array_result);

            }


        }
        return $this->json(['result' => false]);
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

        //$fecha_inicio = '2020-09-20';
        //$fecha_final = '2020-02-20';

        $worksgroup = 0;
        $fecha_inicio = '2022-09';
        $fecha_final = '2022-09';
        $lotes = 0;
        $parcelas = 0;
        $propietarios = 0;
        $destinos = 0;

        /*$worksgroup = 1;
        $fecha_inicio = '2022-09-01';
        $fecha_final = '2022-10-31';
        $lotes = 5;
        $parcelas = 1;
        $propietarios = 1;
        $destinos = 2;*/


        $fec_in = new Date($fecha_inicio);

        $informe = true;

        $array_options = [];
        $array_options_ = [];

        $array_options['worksgroup'] = $worksgroup;
        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['propietarios_idpropietarios'] = $propietarios;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;

        $array_options_['worksgroup'] = $worksgroup;
        $array_options_['fecha_inicio'] = $fecha_inicio;
        $array_options_['fecha_fin'] = $fecha_final;
        $array_options_['lotes_idlotes'] = $lotes;
        $array_options_['parcelas_idparcelas'] = $parcelas;
        $array_options_['propietarios_idpropietarios'] = $propietarios;
        $array_options_['destinos_iddestinos'] = $destinos;
        $array_options_['empresas_idempresas'] = $id_empresa;


        //Result costos
        //$data_by_centro_costos = $this->calculateCostos($array_options);
        $result = null;
        $data_organized_by_month = [];

        //INstancio la calse getfunction

        $get_function_class = new GetFunctions();

        //instancio la clase AalisisCostos
        $analisis_costos_class =  new AnalisisCostosGrupos();

        //EL recorrido de los meses los hago aqui
        $meses_years = $get_function_class->getMonthsAndYearsWithLast($array_options);

        $result = true;

        //$metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options);


        foreach ($meses_years as $meses_year)
        {
            $mes = $meses_year['mes'];
            $year = $meses_year['year'];

            $result = $analisis_costos_class->verifiedDataByMonth($array_options, $mes, $year);

            //SI uno ya es false, cancelo la operacion
            if(!$result){
                break;
            }

        }

        //INstancio las clases getfunction y costos
        if($result) {
            //Utilizo la clase para calcular los costos
            //devuelve un arreglos con los datos de la maquina
            $costos_maquinas = $analisis_costos_class->analisisDeCostosGrupos($array_options, $id_empresa);

            //debug($costos_maquinas);



            //$costos_maquinas_six_back = $analisis_costos_class->analisisDeCostosGrupos($array_options, $id_empresa);

            //Calculo los costos seis meses atras

            //Tengo que calcular los costos backtime
            $date_six_inicio_back = $get_function_class->getDateSixMonthsBack($fecha_inicio);
            $date_six_final_back = $get_function_class->getDateSixMonthsBack($fecha_final);

            //remplazo el array options
            $array_options_['fecha_inicio'] = $date_six_inicio_back;
            $array_options_['fecha_fin'] = $date_six_final_back;

            $costos_maquinas_six_back = $analisis_costos_class->analisisDeCostosGrupos($array_options_, $id_empresa);

            //debug($costos_maquinas_six_back);

            $date_year_inicio_back = $get_function_class->getDateOneYearBack($fecha_inicio);
            $date_year_final_back = $get_function_class->getDateOneYearBack($fecha_final);

            //remplazo el array options
            $array_options_['fecha_inicio'] = $date_year_inicio_back;
            $array_options_['fecha_fin'] = $date_year_final_back;

            //debug("UN ANO ATRAS");
            $costos_one_year_back = $analisis_costos_class->analisisDeCostosGrupos($array_options_, $id_empresa);


            $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options);
            $metadata['users_idusers'] = $user_id;


            //Llamo a excel processing
            $excel_processing_class = new ExcelProcesssing();
            $informe = $excel_processing_class->createInformeGrupos($metadata, $costos_maquinas, $costos_one_year_back, $costos_maquinas_six_back);

            $array_result = [
                'costos' => $costos_maquinas,
                'informe' => $informe
            ];
            debug($array_result);

            return $this->json($array_result);

        }


    }

    public function verifiedDataForAnalisysCostos()
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

        if($this->request->is('ajax')) {
            //COnsulto que los indices esten definidos
            $worksgroup = $_POST['groups'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_final = $_POST['fecha_final'];
            $lotes = $_POST['lotes'];
            $parcelas = $_POST['parcelas'];
            $propietarios = $_POST['propietarios'];
            $destinos = $_POST['destinos'];
            $informe = $_POST['informe'];

            $array_options['worksgroup'] = $worksgroup;
            $array_options['fecha_inicio'] = $fecha_inicio;
            $array_options['fecha_fin'] = $fecha_final;
            $array_options['lotes_idlotes'] = $lotes;
            $array_options['parcelas_idparcelas'] = $parcelas;
            $array_options['propietarios_idpropietarios'] = $propietarios;
            $array_options['destinos_iddestinos'] = $destinos;
            $array_options['empresas_idempresas'] = $id_empresa;


            //INstancio la clase GetFunction
            $get_function_class = New GetFunctions();

            //instancio la clase AalisisCostos
            $analisis_costos_class =  new AnalisisCostosGrupos();

            //EL recorrido de los meses los hago aqui
            $meses_years = $get_function_class->getMonthsAndYearsWithLast($array_options);

            $result = true;


            foreach ($meses_years as $meses_year)
            {
                $mes = $meses_year['mes'];
                $year = $meses_year['year'];

                $result = $analisis_costos_class->verifiedDataByMonth($array_options, $mes, $year);

                //SI uno ya es false, cancelo la operacion
                if(!$result){
                    break;
                }

            }



            return $this->json(['result' => $result]);
        }



    }



}
