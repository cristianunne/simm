<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Utility\AnalisisMaquinas;
use App\Utility\ExcelProcesssing;
use App\Utility\GetFunctions;
use Cake\I18n\Date;

/**
 * Variaciones Controller
 *
 */
class VariacionesController extends AppController
{

   const TYPE_MAQUINA = 'maquina';
   const TYPE_GROUP = 'grupo';
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'getToneladasExtraidas',
                'getToneladasCopy'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'getToneladasExtraidas',
                'getToneladasCopy'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }


    public function index()
    {

        //Variable usada para el sidebar
        $seccion = 'analisis_costos';
        $sub_seccion = 'Variaciones';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //TRaigo los grupos de la Empresa
        $worksgroups_model = $this->loadModel('Worksgroups');
        $grupos_data = $worksgroups_model->find('list', [
            'keyField' => 'idworksgroups',
            'valueField' => 'name'
        ])
            ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
            ->toArray();

        $insertar = [0 => 'Todos'];
        array_splice($grupos_data, 0, 0, $insertar);

        $this->set(compact('grupos_data'));

        //Radio buttons
        $rb_evolucion = $this->getRaddioButtonsVariaciones();
        $this->set(compact('rb_evolucion'));

        //Traigo los lotes
        $tablaLotes = $this->loadModel('Lotes');
        $lotes =  $tablaLotes->find('all', [
            'contain' => []
        ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
        $this->set(compact('lotes'));


        //Traigo los datos de los propietarios
        $tablaDestinos = $this->loadModel('Destinos');

        $destinos =  $tablaDestinos->find('all', [
            'contain' => 'Users'
        ])->where(['Destinos.active' => true, 'Destinos.empresas_idempresas' => $id_empresa]);
        $this->set(compact('destinos'));

        //traigo la maquina
        $maquinas = $this->getMaquinasList()->toArray();
        $maquinas[0] = 'Todos';


        $this->set(compact('maquinas'));



    }


    public function getCostosToneladasGrupos()
    {

        $this->autoRender = false;
        $array_result = null;

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $user_id = $session->read('Auth.User.idusers');



        //COnsulto que los indices esten definidos
        $grupo = $_POST['groups'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $destinos = $_POST['destinos'];

        $informe = $_POST['informe'];

        $array_options['worksgroup'] = $grupo;
        $array_options['idworksgroups'] = $grupo;
        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;

        $array_options_['worksgroup'] = $grupo;
        $array_options_['fecha_inicio'] = $fecha_inicio;
        $array_options_['fecha_fin'] = $fecha_final;
        $array_options_['lotes_idlotes'] = $lotes;
        $array_options_['parcelas_idparcelas'] = $parcelas;

        $array_options_['destinos_iddestinos'] = $destinos;
        $array_options_['empresas_idempresas'] = $id_empresa;

        /*$worksgroup = 0;
        $fecha_inicio = '2022-01';
        $fecha_final = '2022-12';
        $lotes = 0;
        $parcelas = 0;
        $propietarios = 0;
        $destinos = 0;

        $informe = true;

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
        $array_options_['empresas_idempresas'] = $id_empresa;*/


        if($this->request->is('ajax')) {

            //INstancio la clase GetFunction
            $get_function_class = New GetFunctions();

            //EL recorrido de los meses los hago aqui
            $meses_years = $get_function_class->getMonthsAndYearsWithLast($array_options);



            //costos
            $analisis_costos_grupos = new AnalisisCostosController();


            //TRaigo los worksgroups

            $worksgroups = $get_function_class->getWorksgroups($array_options);



            $array_result = [];
            $array_data = [];

            $datasets = [];

            $labels = [];

            //Reccoro los grupos
            foreach ($meses_years as $mes_year)
            {
                $array_options['mes'] = $mes_year['mes'];
                $array_options['year'] = $mes_year['year'];
                //debug($array_options);

                $array_group_month = [];
                $array_group_month['x'] = $mes_year['mes'] . '-' . $mes_year['year'];

                foreach ($worksgroups as $group)
                {

                    $array_options['idworksgroups'] = $group->idworksgroups;
                    $array_options['worksgroup'] = $group->idworksgroups;
                    $array_group_month[$group->name] =
                        $analisis_costos_grupos->getCostosByWorksgroups($array_options);

                }
                $array_data[] = $array_group_month;

                $labels[] = $mes_year['mes'] . '-' . $mes_year['year'];

            }

            $item_Dataset = [];
            foreach ($worksgroups as $group)
            {
                $color = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                $item_Dataset[] = [
                    'label' => $group->name,
                    'data' => $array_data,
                    'parsing' => ['yAxisKey' => $group->name],
                    'backgroundColor' => $color,
                    'borderColor' => $color
                ];

            }

            $datasets[] = $item_Dataset;
            $array_result[] = [
                'labels' => $labels,
                'datasets' => $datasets[0]

            ];

            $array_result_compete = null;


            $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options_);
            if(!empty($array_result[0]['datasets'][0]['data']))
            {
                $data_for_excel = $array_result[0]['datasets'][0]['data'];

                //cargo los datos al metadatsa
                $metadata['users_idusers'] = $user_id;
                $metadata['tipo'] = self::TYPE_GROUP;

                if($informe == 'true'){
                    $excel_processing_class = new ExcelProcesssing();

                    $informe = $excel_processing_class->createInformeVariaciones($metadata, $data_for_excel);

                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => $informe
                    ];
                } else {
                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => false
                    ];
                }

            }

           return $this->json($array_result_compete);

        }

    }


    public function getToneladasExtraidasGrupos()
    {
        $this->autoRender = false;
        $array_result = null;

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $user_id = $session->read('Auth.User.idusers');


        //COnsulto que los indices esten definidos
        $grupo = $_POST['groups'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $destinos = $_POST['destinos'];
        $informe = $_POST['informe'];

        $array_options['worksgroup'] = $grupo;
        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;

        $array_options_['worksgroup'] = $grupo;
        $array_options_['fecha_inicio'] = $fecha_inicio;
        $array_options_['fecha_fin'] = $fecha_final;
        $array_options_['lotes_idlotes'] = $lotes;
        $array_options_['parcelas_idparcelas'] = $parcelas;
        $array_options_['destinos_iddestinos'] = $destinos;
        $array_options_['empresas_idempresas'] = $id_empresa;

        //INstancio la clase GetFunction
        $get_function_class = New GetFunctions();

        //EL recorrido de los meses los hago aqui
        $meses_years = $get_function_class->getMonthsAndYearsWithLast($array_options);

        //Traigo los grupos distinct de todos los remitos
        $worksgroup_distinct = $get_function_class->getWorksgroupDistinctFromRemitos($array_options);

        $array_result = [];
        $array_data = [];

        $datasets = [];

        $labels = [];

        //Reccoro los grupos
        foreach ($meses_years as $mes_year)
        {
            $options['mes'] = $mes_year['mes'];
            $options['year'] = $mes_year['year'];

            $array_group_month = [];
            $array_group_month['x'] = $mes_year['mes'] . '-' . $mes_year['year'];

            foreach ($worksgroup_distinct as $group)
            {
                $options['worksgroup'] = $group;
                $array_group_month[$get_function_class->getWorksgroupById($group)->name] =
                    $get_function_class->getSumaToneladasByWorksgroups($options);

            }
            $array_data[] = $array_group_month;

            $labels[] = $mes_year['mes'] . '-' . $mes_year['year'];

        }

        $item_Dataset = [];
        foreach ($worksgroup_distinct as $group)
        {
            $item_Dataset[] = [
                'label' => $get_function_class->getWorksgroupById($group)->name,
                'data' => $array_data,
                'parsing' => ['yAxisKey' => $get_function_class->getWorksgroupById($group)->name]
            ];

        }
        $datasets[] = $item_Dataset;
        $array_result[] = [
            'labels' => $labels,
            'datasets' => $datasets[0]

        ];

        $array_result_compete = null;


        $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options_);
        if(!empty($array_result[0]['datasets'][0]['data']))
        {
            $data_for_excel = $array_result[0]['datasets'][0]['data'];

            //cargo los datos al metadatsa
            $metadata['users_idusers'] = $user_id;
            $metadata['tipo'] = self::TYPE_GROUP;

            if($informe == 'true'){
                $excel_processing_class = new ExcelProcesssing();

                $informe = $excel_processing_class->createInformeVariaciones($metadata, $data_for_excel);

                $array_result_compete[] = [
                    'costos' => $array_result,
                    'informe' => $informe
                ];
            } else {
                $array_result_compete[] = [
                    'costos' => $array_result,
                    'informe' => false
                ];
            }

        }




        if($this->request->is('ajax')) {
            return $this->json($array_result_compete);
        }


    }


    public function getToneladasExtraidasMaquinas()
    {
        $this->autoRender = false;
        $array_result = null;

        $get_function_class = New GetFunctions();

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $user_id = $session->read('Auth.User.idusers');

        //COnsulto que los indices esten definidos
        $maquina = $_POST['maquinas'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $destinos = $_POST['destinos'];

        $informe = $_POST['informe'];

        $array_options['maquina'] = $maquina;
        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;

        $array_options_['maquinas'] = $maquina;
        $array_options_['fecha_inicio'] = $fecha_inicio;
        $array_options_['fecha_fin'] = $fecha_final;
        $array_options_['lotes_idlotes'] = $lotes;
        $array_options_['parcelas_idparcelas'] = $parcelas;
        $array_options_['destinos_iddestinos'] = $destinos;
        $array_options_['empresas_idempresas'] = $id_empresa;


        //INstancio la clase GetFunction
        $get_function_class = New GetFunctions();

        //EL recorrido de los meses los hago aqui
        $meses_years = $get_function_class->getMonthsAndYearsWithLast($array_options);


        $maquinas = $get_function_class->getMaquinas($array_options);


        $options['empresas_idempresas'] =  $id_empresa;

        $array_result = [];
        $array_data = [];
        $datasets = [];
        $labels = [];


        foreach ($meses_years as $mes_year) {
            $array_options['mes'] = $mes_year['mes'];
            $array_options['year'] = $mes_year['year'];

            $array_group_month = [];
            $array_group_month['x'] = $mes_year['mes'] . '-' . $mes_year['year'];

            //Recorro las maquinas y traigo los remitos
            foreach ($maquinas as $maq)
            {

                //OPero todas las maquinas para un mes/a;o determinado
                $array_options['maquina'] = $maq->idmaquinas;
                $toneladas = $get_function_class->getSumaToneladasByMaquina($array_options);
                $array_group_month[$maq->name] = $toneladas;

            }

            $array_data[] = $array_group_month;

            $labels[] = $mes_year['mes'] . '-' . $mes_year['year'];

        }

        $item_Dataset = [];
        foreach ($maquinas as $maq)
        {
            $color = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
            $item_Dataset[] = [
                'label' => $maq->name,
                'data' => $array_data,
                'parsing' => ['yAxisKey' => $maq->name],
                'backgroundColor' => $color,
                'borderColor' => $color
            ];

        }

        $datasets[] = $item_Dataset;
        $array_result[] = [
            'labels' => $labels,
            'datasets' => $datasets[0]

        ];

        $array_result_compete = null;

        if($this->request->is('ajax')) {
            $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options_);
            if(!empty($array_result[0]['datasets'][0]['data']))
            {
                $data_for_excel = $array_result[0]['datasets'][0]['data'];

                //debug($data_for_excel);

                //cargo los datos al metadatsa
                $metadata['users_idusers'] = $user_id;
                $metadata['tipo'] = self::TYPE_MAQUINA;

                if($maquina != '0'){
                    $metadata['maquina'] = $get_function_class->getMaquinaById($maquina)->toArray()['name'];
                } else {
                    $metadata['maquina'] = 'Todos';
                }


                if($informe == 'true'){
                    $excel_processing_class = new ExcelProcesssing();

                    $informe = $excel_processing_class->createInformeVariaciones($metadata, $data_for_excel);

                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => $informe
                    ];
                } else {
                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => false
                    ];
                }

            }



            return $this->json($array_result_compete);
        }


    }


    public function getHorasTrabajadasMaquinas()
    {

        $this->autoRender = false;
        $array_result = null;

        $get_function_class = New GetFunctions();

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $user_id = $session->read('Auth.User.idusers');


        //COnsulto que los indices esten definidos
        $maquina = $_POST['maquinas'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $destinos = $_POST['destinos'];
        $informe = $_POST['informe'];

        $array_options['maquina'] = $maquina;
        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;

        $array_options_['maquinas'] = $maquina;
        $array_options_['fecha_inicio'] = $fecha_inicio;
        $array_options_['fecha_fin'] = $fecha_final;
        $array_options_['lotes_idlotes'] = $lotes;
        $array_options_['parcelas_idparcelas'] = $parcelas;
        $array_options_['destinos_iddestinos'] = $destinos;
        $array_options_['empresas_idempresas'] = $id_empresa;

        //INstancio la clase GetFunction
        $get_function_class = New GetFunctions();

        //EL recorrido de los meses los hago aqui
        $meses_years = $get_function_class->getMonthsAndYearsWithLast($array_options);

        $maquinas = $get_function_class->getMaquinas($array_options);

        //TRaigo los usos de combustible
        $array_result = [];
        $array_data = [];
        $datasets = [];
        $labels = [];


        foreach ($meses_years as $mes_year) {
            $array_options['mes'] = $mes_year['mes'];
            $array_options['year'] = $mes_year['year'];

            $array_group_month = [];
            $array_group_month['x'] = $mes_year['mes'] . '-' . $mes_year['year'];

            //Recorro las maquinas y traigo los remitos
            foreach ($maquinas as $maq)
            {

                //OPero todas las maquinas para un mes/a;o determinado
                $array_options['maquina'] = $maq->idmaquinas;
                $horas_trabajadas = $get_function_class->getHorasTrabajadasByMaquina($array_options);
                $array_group_month[$maq->name] = $horas_trabajadas;

            }

            $array_data[] = $array_group_month;

            $labels[] = $mes_year['mes'] . '-' . $mes_year['year'];

        }

        $item_Dataset = [];
        foreach ($maquinas as $maq)
        {
            $color = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
            $item_Dataset[] = [
                'label' => $maq->name,
                'data' => $array_data,
                'parsing' => ['yAxisKey' => $maq->name],
                'backgroundColor' => $color,
                'borderColor' => $color
            ];

        }

        $datasets[] = $item_Dataset;
        $array_result[] = [
            'labels' => $labels,
            'datasets' => $datasets[0]

        ];


        if($this->request->is('ajax')) {


            $array_result_compete = null;


            $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options_);
            if(!empty($array_result[0]['datasets'][0]['data']))
            {
                $data_for_excel = $array_result[0]['datasets'][0]['data'];

                //cargo los datos al metadatsa
                $metadata['users_idusers'] = $user_id;
                $metadata['tipo'] = self::TYPE_MAQUINA;

                if($maquina != '0'){
                    $metadata['maquina'] = $get_function_class->getMaquinaById($maquina)->toArray()['name'];
                } else {
                    $metadata['maquina'] = 'Todos';
                }

                if($informe == 'true'){
                    $excel_processing_class = new ExcelProcesssing();

                    $informe = $excel_processing_class->createInformeVariaciones($metadata, $data_for_excel);

                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => $informe
                    ];
                } else {
                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => false
                    ];
                }

            }

            return $this->json($array_result_compete);
        }



    }



    public function getCostoMaquinaHora()
    {
        $this->autoRender = false;
        $array_result = null;

        $get_function_class = New GetFunctions();

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $user_id = $session->read('Auth.User.idusers');

        //COnsulto que los indices esten definidos
        $maquina = $_POST['maquinas'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $destinos = $_POST['destinos'];
        $informe = $_POST['informe'];


        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;
        $array_options['maquina'] = $maquina;

        $array_options_['maquinas'] = $maquina;
        $array_options_['fecha_inicio'] = $fecha_inicio;
        $array_options_['fecha_fin'] = $fecha_final;
        $array_options_['lotes_idlotes'] = $lotes;
        $array_options_['parcelas_idparcelas'] = $parcelas;
        $array_options_['destinos_iddestinos'] = $destinos;
        $array_options_['empresas_idempresas'] = $id_empresa;


        if($this->request->is('ajax')) {


            $analisis_maquinas = new AnalisisMaquinas();

            //Instancio la clase GetFUnctions
            $get_functions = new GetFunctions();
            $meses_years = $get_functions->getMonthsAndYearsWithLast($array_options);

            //TRaemos las maquinas
            $maquinas = $get_functions->getMaquinas($array_options);

            $array_data = [];
            $datasets = [];
            $labels = [];

            //Reccoro los grupos
            foreach ($meses_years as $mes_year)
            {
                $options['mes'] = $mes_year['mes'];
                $options['year'] = $mes_year['year'];
                $array_group_month = [];
                $array_group_month['x'] = $mes_year['mes'] . '-' . $mes_year['year'];

                foreach ($maquinas as $maq)
                {
                    $array_group_month[$maq->name] =
                        $analisis_maquinas->costosHorasMaquinaByMonths($mes_year['mes'], $mes_year['year'],
                            $array_options, $id_empresa, $maq->idmaquinas);
                }

                $array_data[] = $array_group_month;

                $labels[] = $mes_year['mes'] . '-' . $mes_year['year'];
            }


            $item_Dataset = [];
            foreach ($maquinas as $maq)
            {
                $color = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);

                $item_Dataset[] = [
                    'label' => $maq->name,
                    'data' => $array_data,
                    'parsing' => ['yAxisKey' => $maq->name],
                    'backgroundColor' => $color,
                    'borderColor' => $color
                ];

            }

            $datasets[] = $item_Dataset;
            $array_result[] = [
                'labels' => $labels,
                'datasets' => $datasets[0]

            ];

            $array_result_compete = null;
            $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options_);
            if(!empty($array_result[0]['datasets'][0]['data']))
            {
                $data_for_excel = $array_result[0]['datasets'][0]['data'];

                //cargo los datos al metadatsa
                $metadata['users_idusers'] = $user_id;
                $metadata['tipo'] = self::TYPE_MAQUINA;
                if($maquina != '0'){
                    $metadata['maquina'] = $get_function_class->getMaquinaById($maquina)->toArray()['name'];
                } else {
                    $metadata['maquina'] = 'Todos';
                }

                if($informe == 'true'){
                    $excel_processing_class = new ExcelProcesssing();

                    $informe = $excel_processing_class->createInformeVariaciones($metadata, $data_for_excel);

                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => $informe
                    ];
                } else {
                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => false
                    ];
                }

            }

            return $this->json($array_result_compete);
        }


    }
    public function getCostoMaquinaTonelada()
    {
        $this->autoRender = false;
        $array_result = null;
        //INstancio la clase GetFunction
        $get_function_class = New GetFunctions();


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $user_id = $session->read('Auth.User.idusers');

        //COnsulto que los indices esten definidos
        $maquina = $_POST['maquinas'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $destinos = $_POST['destinos'];

        $informe = $_POST['informe'];


        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;
        $array_options['maquina'] = $maquina;

        $array_options_['maquinas'] = $maquina;
        $array_options_['fecha_inicio'] = $fecha_inicio;
        $array_options_['fecha_fin'] = $fecha_final;
        $array_options_['lotes_idlotes'] = $lotes;
        $array_options_['parcelas_idparcelas'] = $parcelas;
        $array_options_['destinos_iddestinos'] = $destinos;
        $array_options_['empresas_idempresas'] = $id_empresa;

        if($this->request->is('ajax')) {


            $analisis_maquinas = new AnalisisMaquinas();

            //Instancio la clase GetFUnctions
            $get_functions = new GetFunctions();
            $meses_years = $get_functions->getMonthsAndYearsWithLast($array_options);

            //TRaemos las maquinas
            $maquinas = $get_functions->getMaquinas($array_options);

            $array_data = [];
            $datasets = [];
            $labels = [];

            //Reccoro los grupos
            foreach ($meses_years as $mes_year)
            {
                $options['mes'] = $mes_year['mes'];
                $options['year'] = $mes_year['year'];
                $array_group_month = [];
                $array_group_month['x'] = $mes_year['mes'] . '-' . $mes_year['year'];

                foreach ($maquinas as $maq)
                {
                    $array_group_month[$maq->name] =
                        $analisis_maquinas->costosToneladasMaquinaByMonths($mes_year['mes'], $mes_year['year'],
                            $array_options, $id_empresa, $maq->idmaquinas);
                }

                $array_data[] = $array_group_month;

                $labels[] = $mes_year['mes'] . '-' . $mes_year['year'];
            }


            $item_Dataset = [];
            foreach ($maquinas as $maq)
            {
                $color = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);

                $item_Dataset[] = [
                    'label' => $maq->name,
                    'data' => $array_data,
                    'parsing' => ['yAxisKey' => $maq->name],
                    'backgroundColor' => $color,
                    'borderColor' => $color
                ];

            }

            $datasets[] = $item_Dataset;
            $array_result[] = [
                'labels' => $labels,
                'datasets' => $datasets[0]

            ];

            $array_result_compete = null;


            $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options_);
            if(!empty($array_result[0]['datasets'][0]['data']))
            {
                $data_for_excel = $array_result[0]['datasets'][0]['data'];

                //cargo los datos al metadatsa
                $metadata['users_idusers'] = $user_id;
                $metadata['tipo'] = self::TYPE_MAQUINA;
                if($maquina != '0'){
                    $metadata['maquina'] = $get_function_class->getMaquinaById($maquina)->toArray()['name'];
                } else {
                    $metadata['maquina'] = 'Todos';
                }

                if($informe == 'true'){
                    $excel_processing_class = new ExcelProcesssing();

                    $informe = $excel_processing_class->createInformeVariaciones($metadata, $data_for_excel);

                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => $informe
                    ];
                } else {
                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => false
                    ];
                }

            }

            return $this->json($array_result_compete);
        }


    }

    public function getCostoMaquinaRendimiento()
    {
        $this->autoRender = false;
        $array_result = null;

        $get_function_class = New GetFunctions();

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $user_id = $session->read('Auth.User.idusers');

        //COnsulto que los indices esten definidos
        $maquina = $_POST['maquinas'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $destinos = $_POST['destinos'];
        $informe = $_POST['informe'];

        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;
        $array_options['maquina'] = $maquina;

        $array_options_['maquinas'] = $maquina;
        $array_options_['fecha_inicio'] = $fecha_inicio;
        $array_options_['fecha_fin'] = $fecha_final;
        $array_options_['lotes_idlotes'] = $lotes;
        $array_options_['parcelas_idparcelas'] = $parcelas;
        $array_options_['destinos_iddestinos'] = $destinos;
        $array_options_['empresas_idempresas'] = $id_empresa;

        if($this->request->is('ajax')) {


            $analisis_maquinas = new AnalisisMaquinas();

            //Instancio la clase GetFUnctions
            $get_functions = new GetFunctions();
            $meses_years = $get_functions->getMonthsAndYearsWithLast($array_options);

            //TRaemos las maquinas
            $maquinas = $get_functions->getMaquinas($array_options);

            $array_data = [];
            $datasets = [];
            $labels = [];

            //Reccoro los grupos
            foreach ($meses_years as $mes_year)
            {
                $options['mes'] = $mes_year['mes'];
                $options['year'] = $mes_year['year'];
                $array_group_month = [];
                $array_group_month['x'] = $mes_year['mes'] . '-' . $mes_year['year'];

                foreach ($maquinas as $maq)
                {
                    $array_group_month[$maq->name] =
                        $analisis_maquinas->costosRendimientoMaquinaByMonths($mes_year['mes'], $mes_year['year'],
                            $array_options, $id_empresa, $maq->idmaquinas);
                }

                $array_data[] = $array_group_month;

                $labels[] = $mes_year['mes'] . '-' . $mes_year['year'];
            }


            $item_Dataset = [];
            foreach ($maquinas as $maq)
            {
                $color = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);

                $item_Dataset[] = [
                    'label' => $maq->name,
                    'data' => $array_data,
                    'parsing' => ['yAxisKey' => $maq->name],
                    'backgroundColor' => $color,
                    'borderColor' => $color
                ];

            }

            $datasets[] = $item_Dataset;
            $array_result[] = [
                'labels' => $labels,
                'datasets' => $datasets[0]

            ];

            $array_result_compete = null;

            $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options_);
            if(!empty($array_result[0]['datasets'][0]['data']))
            {
                $data_for_excel = $array_result[0]['datasets'][0]['data'];

                //cargo los datos al metadatsa
                $metadata['users_idusers'] = $user_id;
                $metadata['tipo'] = self::TYPE_MAQUINA;
                if($maquina != '0'){
                    $metadata['maquina'] = $get_function_class->getMaquinaById($maquina)->toArray()['name'];
                } else {
                    $metadata['maquina'] = 'Todos';
                }

                if($informe == 'true'){
                    $excel_processing_class = new ExcelProcesssing();

                    $informe = $excel_processing_class->createInformeVariaciones($metadata, $data_for_excel);

                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => $informe
                    ];
                } else {
                    $array_result_compete[] = [
                        'costos' => $array_result,
                        'informe' => false
                    ];
                }

            }

            return $this->json($array_result_compete);
        }


    }




    private function prepareDataForGetToneladasExtraidasGrupos($remitos, $grupos, $mes, $year)
    {
        $array_result = [];
        $toneladas = 0;

        //Tengo que traer los grupos y ordenarlos a partir de ellos

        //Label es el grupo
        //X es la fecha
        // son las toneladas
        //CAntidad



        foreach ($remitos as $rem){

            //sumo las toneladas
            $toneladas = $toneladas + $rem->ton;
        }

        $array_result['x'] = $mes . '-' . $year;
        $array_result['y'] = $toneladas;

        return $array_result;
    }


    private function getMaquinasList()
    {
        $maquinas_model = $this->loadModel('Maquinas');
        $maquinas = $maquinas_model->find('list',[

            'keyField' => 'idmaquinas',
            'valueField' => 'name'
        ])
            ->order(['name ASC']);

        return $maquinas;

    }

    private function getRaddioButtonsVariaciones()
    {

        $array = [
            1 => 'Grupo, costo total por tonelada',
            2 => 'Grupo, toneladas extraídas',
            3 => 'Máquina, costo total por tonelada',
            4 => 'Máquina, costo total por hora',
            5 => 'Máquina, toneladas extraídas',
            6 => 'Máquina, horas trabajadas',
            7 => 'Máquina, rendimiento'
        ];

        return $array;

    }


}
