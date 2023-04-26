<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Utility\AnalisisMaquinas;
use App\Utility\ExcelProcesssing;
use App\Utility\GetFunctions;
use Cake\Database\Exception;
use function Psy\info;

/**
 * AnalisisCostosMaquinas Controller
 *
 */
class AnalisisCostosMaquinasController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'calculateCostosMaquina', 'checkMaquinaIsOkeyToCostos', 'viewCostoMaquina',
                'createdSheetResumen', 'createdSheetMaquinas', 'calculateCostosGruposCopy'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'calculateCostosMaquina', 'checkMaquinaIsOkeyToCostos', 'viewCostoMaquina',
                'createdSheetResumen', 'createdSheetMaquinas', 'calculateCostosGruposCopy'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }


    public function index()
    {

    }

    public function calculateCostosMaquina()
    {
        $seccion = 'analisis_costos';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //AL Igual que los otros, solo se cargaran las maquinas que tengan todos los datos para analizar
        //Primer paso es filtar las maquinas segun los remitos

        $array_options = [];


        $maquinas_model = $this->loadModel('Maquinas');
        $maquinas = $maquinas_model->find('list', [
            'keyField' => 'idmaquinas',
            'valueField' => ['marca', 'name']
        ])->order(['marca' => 'ASC']);


        $this->set(compact('maquinas'));


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

            //Cargo las variables
            $array_options['maquina'] = $this->request->getData()['maquina'];
            $array_options['fecha_inicio'] = $this->request->getData()['fecha_inicio'];
            $array_options['fecha_fin'] = $this->request->getData()['fecha_final'];
            $array_options['lotes_idlotes'] = $this->request->getData()['lotes_idlotes'];
            $array_options['parcelas_idparcelas'] = $this->request->getData()['parcelas_idparcelas'];
            $array_options['propietarios_idpropietarios'] = $this->request->getData()['propietarios_idpropietarios'];
            $array_options['destinos_iddestinos'] = $this->request->getData()['destinos_iddestinos'];
            $array_options['empresas_idempresas'] = $id_empresa;

            $maquina = $array_options['maquina'];

            $get_function_class = new GetFunctions();

            //instancio analisis maquinas
            $analisis_maquinas_class = new AnalisisMaquinas();

            $costo_maquina = $analisis_maquinas_class->analisisMaquina($array_options, $id_empresa, $maquina);
            $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options);

            //debug($costo_maquina[0]);
            if(isset($costo_maquina[0])){

                //cargo los datos al metadatsa
                $metadata['users_idusers'] = $user_id;
                $metadata['maquinas_idmaquinas'] = $maquina;
                //Llamo a excel processing
                $excel_processing_class = new ExcelProcesssing();
                $informe = $excel_processing_class->createInformesMaquinas($metadata, $costo_maquina[0]);


                if($informe != false)
                {
                    $get_function_class = new GetFunctions();
                    $maquina_data = $get_function_class->getMaquinaById($maquina);

                    $path_excel = $informe['path'];

                    $array_options['path_excel'] = $path_excel;
                    $this->prepareDataToShowView($maquina_data, $costo_maquina[0], $session, $array_options);
                }

            }

        }

    }


    private function prepareDataToShowView($maquina_data = null, $data_result = null, $session = null, $array_options = null)
    {

        //Recorro la maquina
        $data_maquina = null;


            $data_maquina['maquina'] = [
                'name' => $maquina_data->name,
                'marca' => $maquina_data->marca
            ];


        //debug($data_result);
        $session->write('maquina', $data_maquina);
        $session->write('options', $array_options);
        $session->write('data', $data_result);
        $session->write('flags_refresh', false);
        return $this->redirect(['action' => 'viewCostoMaquina']);
    }

    public function viewCostoMaquina()
    {

        //Traigo los datos de la sesioN

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $data_result = $session->read('data');
        $maquina_data = $session->read('maquina');
        $array_options = $session->read('options');
        $flags_refresh = $session->read('flags_refresh');

        if(is_null($flags_refresh) or !isset($flags_refresh))
        {
            return $this->redirect(['action' => 'calculateCostosMaquina']);
        }
        //$resumen = $session->read('resumen');




        $maquinas_model = $this->loadModel('Maquinas');
        $maquinas = $maquinas_model->find('list', [
            'keyField' => 'idmaquinas',
            'valueField' => ['marca', 'name']
        ])->order(['marca' => 'ASC']);
        $this->set(compact('maquinas'));

        $this->set(compact('maquinas'));


        $maq = $array_options['maquina'];
        $this->set(compact('maq'));

        $fecha_inicio = $array_options['fecha_inicio'];
        $this->set(compact('fecha_inicio'));

        $fecha_fin = $array_options['fecha_fin'];
        $this->set(compact('fecha_fin'));

        $lotes = $this->getNamesOfLotes($array_options);
        $this->set(compact('lotes'));

        $lote_value = $array_options['lotes_idlotes'];
        $this->set(compact('lote_value'));

        $parcelas = $this->getNamesOfParcelas($array_options);
        $this->set(compact('parcelas'));

        $parcela_value = $array_options['parcelas_idparcelas'];
        $this->set(compact('parcela_value'));


        $propietarios = $this->getNamesOfPropietarios($array_options);
        $this->set(compact('propietarios'));

        $propietarios_value = $array_options['propietarios_idpropietarios'];
        $this->set(compact('propietarios_value'));



        $destinos = $this->getNamesOfDestinos($array_options);
        $this->set(compact('destinos'));

        $destinos_value = $array_options['destinos_iddestinos'];
        $this->set(compact('destinos_value'));


        $this->set(compact('data_result'));


        $path_excel = $array_options['path_excel'];
        $this->set(compact('path_excel'));


        //Elimino todos los datos de la session

        $session->delete('data');
        $session->delete('maquina');
        $session->delete('options');
        $session->delete('flags_refresh');


    }

    private function getNamesOfLotes($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['lotes_idlotes'] != 0){

            //TRaigo el nombre del remito
            $lotes_model = $this->loadModel('Lotes');

            $lotes_data = $lotes_model->find('list', [
                'keyField' => 'idlotes',
                'valueField' => 'name'
            ])
                ->where(['idlotes' => $array_options['lotes_idlotes']])->toArray();

            return $lotes_data;
        }
        $array[0] = 'Todos';
        return $array;
    }

    private function getNameOfLotesOnlyName($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['lotes_idlotes'] != 0){

            //TRaigo el nombre del remito
            $lotes_model = $this->loadModel('Lotes');

            $lotes_data = $lotes_model->find('all', [
            ])
                ->select(['name'])
                ->where(['idlotes' => $array_options['lotes_idlotes']])->toArray();

            return $lotes_data;
        }
        $array[0] = 'Todos';
        return $array;

    }

    private function getNamesOfParcelas($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['parcelas_idparcelas'] != 0){

            //TRaigo el nombre del remito
            $parcelas_model = $this->loadModel('Parcelas');

            $parcela_data = $parcelas_model->find('list', [
                'keyField' => 'idparcelas',
                'valueField' => 'name'
            ])
                ->where(['idparcelas' => $array_options['parcelas_idparcelas']])->toArray();

            return $parcela_data;
        }
        $array[0] = 'Todos';
        return $array;

    }

    private function getNamesOfParcelasOnlyName($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['parcelas_idparcelas'] != 0){

            //TRaigo el nombre del remito
            $parcelas_model = $this->loadModel('Parcelas');

            $parcela_data = $parcelas_model->find('all', [
            ])
                ->select(['name'])
                ->where(['idparcelas' => $array_options['parcelas_idparcelas']])->toArray();

            return $parcela_data;
        }
        $array[0] = 'Todos';
        return $array;
    }

    private function getNamesOfPropietarios($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['propietarios_idpropietarios'] != 0){

            //TRaigo el nombre del remito
            $propietarios_model = $this->loadModel('Propietarios');

            $propietarios_data = $propietarios_model->find('list', [
                'keyField' => 'idpropietarios',
                'valueField' => 'name'
            ])
                ->where(['idpropietarios' => $array_options['propietarios_idpropietarios']])->toArray();

            return $propietarios_data;
        }
        $array[0] = 'Todos';
        return $array;
    }

    private function getNamesOfPropietariosOnlyName($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['propietarios_idpropietarios'] != 0){

            //TRaigo el nombre del remito
            $propietarios_model = $this->loadModel('Propietarios');

            $propietarios_data = $propietarios_model->find('all', [

            ])->select(['name'])
                ->where(['idpropietarios' => $array_options['propietarios_idpropietarios']])->toArray();

            return $propietarios_data;
        }
        $array[0] = 'Todos';
        return $array;
    }



    private function getNamesOfDestinos($array_options = null)
    {

        //NOmbre del Lote
        if($array_options['destinos_iddestinos'] != 0){

            //TRaigo el nombre del remito
            $destinos_model = $this->loadModel('Destinos');

            $destinos_data = $destinos_model->find('list', [
                'keyField' => 'iddestinos',
                'valueField' => 'name'
            ])
                ->where(['iddestinos' => $array_options['destinos_iddestinos']])->toArray();

            return $destinos_data;
        }
        $array[0] = 'Todos';
        return $array;

    }


    private function getNamesOfDestinosOnlyName($array_options = null)
    {
        if($array_options['destinos_iddestinos'] != 0){

            //TRaigo el nombre del remito
            $destinos_model = $this->loadModel('Destinos');

            $destinos_data = $destinos_model->find('all', [
            ])->select(['name'])
                ->where(['iddestinos' => $array_options['destinos_iddestinos']])->toArray();

            return $destinos_data;
        }
        $array[0] = 'Todos';
        return $array;

    }

    public function checkMaquinaIsOkeyToCostos()
    {
        $this->autoRender = false;
        //Recupero los objetos de la post

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        $maquina = $_POST['maquina'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $propietarios = $_POST['propietarios'];
        $destinos = $_POST['destinos'];



        if($this->request->is('ajax')) {

            $array_options['maquina'] = $maquina;
            $array_options['fecha_inicio'] = $fecha_inicio;
            $array_options['fecha_fin'] = $fecha_final;
            $array_options['lotes_idlotes'] = $lotes;
            $array_options['parcelas_idparcelas'] = $parcelas;
            $array_options['propietarios_idpropietarios'] = $propietarios;
            $array_options['destinos_iddestinos'] = $destinos;
            $array_options['empresas_idempresas'] = $id_empresa;


            /*$maquinas_model = $this->loadModel('Maquinas');
            $maquinas = $maquinas_model->find('GetMaquinaEvaluationsOnly', $array_options);*/

            try{

                //instancio analisis maquinas
                $analisis_maquinas_class = new AnalisisMaquinas();

                $costo_maquina = $analisis_maquinas_class->analisisMaquina($array_options, $id_empresa, $maquina);

                return $this->json(['result' => true]);
            } catch (Exception $e)
            {
                return $this->json(['result' => false]);
            }


        }

        return $this->json(['result' => false]);
    }


    public function prueba()
    {

        $seccion = 'analisis_costos';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        $maquina = 1;

        $worksgroup = 'Todos';
        $fecha_inicio = '2022-09';
        $fecha_final = '2022-12';
        //$fecha_inicio = '2020-09-20';
        //$fecha_final = '2020-02-20';
        $lotes = 'Todos';
        $parcelas = 'Todos';
        $propietarios = 'Todos';
        $destinos ='Todos';


        /*$array_options['maquina'] = $maquina;
        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['propietarios_idpropietarios'] = $propietarios;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;*/


        $get_function_class = new GetFunctions();
        $maquina_data = $get_function_class->getMaquinaById($maquina);
        $maq_marca = isset($maquina_data->toArray()['marca']) ? $maquina_data->toArray()['marca'] : null;
        $maq_name =  isset($maquina_data->toArray()['name']) ? $maquina_data->toArray()['name'] : null;

        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['propietarios_idpropietarios'] = $propietarios;
        $array_options['destinos_iddestinos'] = $destinos;
        $array_options['empresas_idempresas'] = $id_empresa;




        //instancio analisis maquinas
        $analisis_maquinas_class = new AnalisisMaquinas();

        $costo_maquina = $analisis_maquinas_class->analisisMaquina($array_options, $id_empresa, $maquina);
        $metadata = $get_function_class->getMetadataResumenCostosGrupos($array_options);

        debug($costo_maquina[0]);
        if(isset($costo_maquina[0])){

            //cargo los datos al metadatsa
            $metadata['users_idusers'] = $user_id;
            $metadata['maquinas_idmaquinas'] = $maquina;
            //Llamo a excel processing
            $excel_processing_class = new ExcelProcesssing();
            $informe = $excel_processing_class->createInformesMaquinas($metadata, $costo_maquina[0]);

            if($informe)
            {
                $this->prepareDataToShowView($maquina_data, $costo_maquina[0], $session, $array_options);
            }

        }



    }


}
