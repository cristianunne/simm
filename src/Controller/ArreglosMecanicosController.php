<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Query;

/**
 * ArreglosMecanicos Controller
 *
 * @property \App\Model\Table\ArreglosMecanicosTable $ArreglosMecanicos
 */
class ArreglosMecanicosController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive',
                'view', 'getGroups', 'getMaquinas', 'getParcelas', 'getUsuarios', 'getDataFromArreglosMecanicos', 'remove',
                'resumenArreglosByMaquina'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive',
                'view', 'getGroups', 'getMaquinas', 'getParcelas', 'getUsuarios', 'getDataFromArreglosMecanicos', 'remove',
                'resumenArreglosByMaquina'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }



    public function index()
    {

        //Variable usada para el sidebar
        $seccion = 'arreglos_mecanicos';
        $sub_seccion = 'Inicio';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        if(empty($id_empresa))
        {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        //AGregar el index comun

        $arreglos = $this->ArreglosMecanicos->find('all', [
            'contain' => ['Users', 'Maquinas', 'Lotes']
            ])->where(['ArreglosMecanicos.empresas_idempresas' => $id_empresa]);


        /*$arregos_model = $this->loadModel('arreglos_mecanicos_year');

        $arreglos =  $arregos_model->find('all', [
            'contain' => ['Users', 'Worksgroups', 'Maquinas']
        ])->where(['arreglos_mecanicos_year.empresas_idempresas' => $id_empresa]);
        $this->set(compact('arreglos'));*/
        $this->set(compact('arreglos'));
        $this->set(compact('id_empresa'));
        //debug($arreglos->toArray());

    }

    public function add()
    {

        //Variable usada para el sidebar
        //Variable usada para el sidebar
        $seccion = 'arreglos_mecanicos';
        $sub_seccion = '';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa))
        {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }


        $arreglos = $this->ArreglosMecanicos->newEntity();


        //Traigo los datos de la maquina filtrado por empresa
        $maquinas_model = $this->loadModel('Maquinas');
        $maquinas_data = $maquinas_model->find('list',
            [
                'keyField' => 'idmaquinas',
                'valueField' => 'name',
                'order' => ['name' => 'ASC']
            ])->where(['empresas_idempresas' => $id_empresa, 'active' => true])
            ->toArray();

        $this->set(compact('maquinas_data'));


        //Traigo los datos de los LOtes y utilizo para filtrar las parcelas
        $lotes_model = $this->loadModel('Lotes');
        $lotes_data = $lotes_model->find('list',
            [
                'keyField' => 'idlotes',
                'valueField' => 'name',
                'order' => ['name' => 'ASC']
            ])->where(['empresas_idempresas' => $id_empresa, 'active' => true])
            ->toArray();

        $this->set(compact('lotes_data'));


        if ($this->request->is('post')) {

            $data = $this->request->getData();

            debug($data);


            $arreglos = $this->ArreglosMecanicos->patchEntity($arreglos, $this->request->getData());

            $arreglos->empresas_idempresas = $id_empresa;
            $arreglos->users_idusers = $user_id;

            //debug($arreglos);

            if($this->ArreglosMecanicos->save($arreglos)){
                $this->Flash->success(__('El Registro se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }
        $this->set(compact('arreglos'));
    }


    public function view($id = null)
    {

        if(is_null($id))
        {
            return $this->redirect(['controller' => 'ArreglosMecanicos', 'action' => 'index']);
        } else {

            try{

                $seccion = 'arreglos_mecanicos';
                $sub_seccion = '';

                $this->set(compact('seccion'));
                $this->set(compact('sub_seccion'));

                //deberia controlar que parcelas no sea null

                $arreglos =  $this->ArreglosMecanicos->get($id,
                    ['contain' => ['Users', 'Maquinas']]);


                if($arreglos->parcelas_idparcelas != null){
                    $arreglos =  $this->ArreglosMecanicos->get($id,
                        ['contain' => ['Users', 'Maquinas', 'Parcelas' => ['Lotes']]]);
                }

                $this->set(compact('arreglos'));


            }
            catch (InvalidPrimaryKeyException $e){
                $this->Flash->error(__('Error. Intenta nuevamente'));

            } catch (RecordNotFoundException $e){
                $this->Flash->error(__('Error. Intenta nuevamente'));
            }
            catch (Exception $e){
                $this->Flash->error(__('Error. Intenta nuevamente'));
            }

        }
    }

    public function edit($id = null)
    {


        if(is_null($id))
        {
            return $this->redirect(['controller' => 'ArreglosMecanicos', 'action' => 'index']);
        } else {


            $seccion = 'arreglos_mecanicos';
            $sub_seccion = '';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));


            //Traigo los datos de la sesion
            $session = $this->request->getSession();
            $user_id = $session->read('Auth.User.idusers');
            $user_role = $session->read('Auth.User.role');
            $id_empresa = $session->read('Auth.User.Empresa.idempresas');

            if(empty($id_empresa))
            {
                $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
                return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
            }


            $bool_has_lote = false;

            $arreglos =  $this->ArreglosMecanicos->get($id);


            if($arreglos->parcelas_idparcelas != null){
                $bool_has_lote = true;
                $arreglos =  $this->ArreglosMecanicos->get($id,
                    ['contain' => ['Users', 'Maquinas', 'Parcelas' => ['Lotes']]]);
            }



            //Traigo los datos de la maquina filtrado por empresa
            $maquinas_model = $this->loadModel('Maquinas');
            $maquinas_data = $maquinas_model->find('list',
                [
                    'keyField' => 'idmaquinas',
                    'valueField' => 'name',
                    'order' => ['name' => 'ASC']
                ])->where(['empresas_idempresas' => $id_empresa, 'active' => true])
                ->toArray();

            $this->set(compact('maquinas_data'));

            //Traigo los datos de los LOtes y utilizo para filtrar las parcelas

            if($bool_has_lote)
            {
                //Traigo los datos de los LOtes y utilizo para filtrar las parcelas
                $lotes_model = $this->loadModel('Lotes');
                $lotes_data = $lotes_model->find('list',
                    [
                        'keyField' => 'idlotes',
                        'valueField' => 'name',
                        'order' => ['name' => 'ASC']
                    ])->where(['empresas_idempresas' => $id_empresa, 'active' => true])
                    ->toArray();

                $this->set(compact('lotes_data'));


                $parcela_entity = new ParcelasController();

                $parcela_data = $parcela_entity->getParcelaByLoteOther($arreglos->parcela->lote->idlotes);


                $this->set(compact('parcela_data'));
                $this->set(compact('lotes_data'));

            }

            //Ahora proceso el EDit

            try{

                if ($this->request->is(['patch', 'post', 'put'])) {

                    $data = $this->request->getData();
                    $arreglos = $this->ArreglosMecanicos->patchEntity($arreglos, $this->request->getData());
                    $arreglos->users_idusers = $user_id;

                    if($this->ArreglosMecanicos->save($arreglos)){
                        $this->Flash->success(__('El Registro se ha almacenado correctamente'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                    }

                }

            } catch (InvalidPrimaryKeyException $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

            } catch (RecordNotFoundException $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            }
            catch (Exception $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            }



         $this->set(compact('arreglos'));
        }
    }


    public function resumenArreglosByMaquina()
    {
        $seccion = 'arreglos_mecanicos';
        $sub_seccion = 'Resumen';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $maquinas_model = $this->loadModel('Maquinas');
        $maquinas_data = $maquinas_model->find('list',
            [
                'keyField' => 'idmaquinas',
                'valueField' => 'name',
                'order' => ['name' => 'ASC']
            ])->where(['empresas_idempresas' => $id_empresa, 'active' => true])
            ->toArray();

        $this->set(compact('maquinas_data'));

        if ($this->request->is('post')) {


            $data = $this->request->getData();
            debug($this->request->getData());

            $maquina = $data['maquinas_idmaquinas'];
            $fecha_inicio = $data['fecha_inicio'];
            $fecha_fin = $data['fecha_fin'];

            $fechai = strtotime($fecha_inicio);
            $fechai2 = strtotime($fecha_fin);

            $mes_inicio = date('m',$fechai);
            $year_inicio = date('Y',$fechai);

            $mes_final = date('m',$fechai2);
            $year_final = date('Y',$fechai2);

            $fech_ini = date("Y-m-t", $fechai);
            $fech_fin = date("Y-m-t", $fechai2);


            $cond1['fecha >='] = $fech_ini;
            $cond1['fecha <='] = $fech_fin;

            $arr1[] = $cond1;

            //$cond2['MONTH(fecha) <='] = $mes_final;
            //$cond2['YEAR(fecha) <='] = $year_final;

            //$arr1[] = $cond2;
            $arr1['maquinas_idmaquinas'] = $maquina;

            $sumas = $this->ArreglosMecanicos->find('all',
            [
                'fields' => [
                    'maquinas_idmaquinas' => 'maquinas_idmaquinas',
                    'total_mano_obra' => 'SUM(mano_obra)',
                    'total_repuestos' => 'SUM(repuestos)',
                    'total_total' => 'SUM(total)'
                ]
            ])->where($arr1);

            $array_result = null;

            if(isset($sumas->toArray()[0]))
            {
                $array_result = [
                    'mano_obra' => $sumas->toArray()[0]['total_mano_obra'],
                    'total_repuestos' => $sumas->toArray()[0]['total_repuestos'],
                    'total' => $sumas->toArray()[0]['total_total'],
                ];

                 debug($this->json(['result' => $array_result]));
            }

            debug($this->json(['result' => false]));

        }



        //hago la prueba de traida de datos



    }

    public function getResumenByMaquina()
    {

        $this->autoRender = false;

        $maquina = $_POST['maquina'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];


        if($this->request->is('ajax'))
        {

            $fechai = strtotime($fecha_inicio);
            $fechai2 = strtotime($fecha_fin);

            $fech_ini = date("Y-m-01", $fechai);
            $fech_fin = date("Y-m-t", $fechai2);


            $cond1['fecha >='] = $fech_ini;
            $cond1['fecha <='] = $fech_fin;

            $arr1[] = $cond1;

            $arr1['maquinas_idmaquinas'] = $maquina;

            $sumas = $this->ArreglosMecanicos->find('all',
                [
                    'fields' => [
                        'maquinas_idmaquinas' => 'maquinas_idmaquinas',
                        'total_mano_obra' => 'SUM(mano_obra)',
                        'total_repuestos' => 'SUM(repuestos)',
                        'total_total' => 'SUM(total)'
                    ]
                ])->where($arr1);
            $array_result = null;

            if(isset($sumas->toArray()[0]))
            {
                $mano_obra = $sumas->toArray()[0]['total_mano_obra'];
                $total_repuestos = $sumas->toArray()[0]['total_repuestos'];
                $total = $sumas->toArray()[0]['total_total'];
                $array_result = [
                    'mano_obra' => $mano_obra == null ? 0 : $mano_obra,
                    'total_repuestos' => $total_repuestos == null ? 0 : $total_repuestos,
                    'total' => $total == null ? 0 : $total
                ];

                return $this->json(['result' => $array_result]);
            }

            return $this->json(['result' => false]);

        }


    }


    public function getGroups()
    {
        $this->autoRender = false;

        $empresa = $_POST['empresa'];
        $array_data = [];

        $groups_model = $this->loadModel('Worksgroups');

        if($this->request->is('ajax')) {

            $array_data = $groups_model->find('all', [

            ])->select(['idworksgroups' => 'idworksgroups','name' => 'name'])
                ->where(['empresas_idempresas' => $empresa])
                ->toArray();
        }

        return $this->json($array_data);
    }

    public function getMaquinas()
    {
        $this->autoRender = false;
        $empresa = $_POST['empresa'];
        $array_data = [];

        $maquinas_model = $this->loadModel('Maquinas');

        if($this->request->is('ajax')) {

            $array_data = $maquinas_model->find('all', [

            ])->select(['idmaquinas' => 'idmaquinas','name' => 'name'])
                ->where(['empresas_idempresas' => $empresa])
                ->order(['name ASC'])
                ->toArray();
        }

        return $this->json($array_data);

    }

    public function getParcelas()
    {
        $this->autoRender = false;
        $empresa = $_POST['empresa'];
        $array_data = [];

        $parcelas_model = $this->loadModel('Parcelas');

        if($this->request->is('ajax')) {

            $array_data = $parcelas_model->find('all', [
                'contain' => ['Lotes']

            ])->select(['idparcelas' => 'idparcelas','name' => 'Parcelas.name'])
                ->where(['Lotes.empresas_idempresas' => $empresa])
                ->order(['name ASC'])
                ->toArray();
        }

        return $this->json($array_data);

    }

    public function getUsuarios()
    {
        $this->autoRender = false;
        $empresa = $_POST['empresa'];
        $array_data = [];

        $users_model = $this->loadModel('Users');

        if($this->request->is('ajax')) {

            $array_data = $users_model->find('all', [

            ])->select(['idusers' => 'idusers','firstname' => 'firstname', 'lastname' => 'lastname'])
                ->where(['empresas_idempresas' => $empresa])
                ->order(['lastname ASC'])
                ->toArray();
        }

        return $this->json($array_data);

    }


    public function getDataFromArreglosMecanicos()
    {

        //Variable usada para el sidebar
        $seccion = 'Arreglos_Mecanicos';
        $sub_seccion = 'Agregar';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');



        $option_select = $_POST['option_select'];
        $all_date = $_POST['all_date'];
        $data = $_POST['data'];
        $array_data = [];

        if($this->request->is('ajax')) {

            if($option_select == 'Fecha')
            {
                $fecha_desde = $data[0];
                $fecha_hasta = $data[1];
                $array_data = $this->getDataByFecha($fecha_desde, $fecha_hasta, $id_empresa);

            } elseif ($option_select == 'Grupo') {

                $value_option = $data[0];
                $array_data = $this->getDataByGrupo($value_option, $all_date, $id_empresa);

            } elseif ($option_select == 'Maquina') {

                $value_option = $data[0];
                $array_data = $this->getDataByMaquina($value_option, $all_date, $id_empresa);

            } elseif ($option_select == 'Parcela') {
                $value_option = $data[0];

                $array_data = $this->getDataByParcela($value_option, $all_date, $id_empresa);

            } elseif ($option_select == 'Usuario') {

                $value_option = $data[0];
                $array_data = $this->getDataByUsuario($value_option, $all_date, $id_empresa);
            }

        }

        return $this->json($array_data);


    }


    private function getDataByFecha($fecha_desde = null, $fecha_hasta = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //return [$fecha_desde, $fecha_hasta];

        //no uso all_date dado que tengo el rango de fechas

        $data = $this->ArreglosMecanicos->find('all', [
            'contain' => ['Users', 'Maquinas']
        ])->where(['ArreglosMecanicos.fecha >=' => strval($fecha_desde), 'ArreglosMecanicos.fecha <=' => strval($fecha_hasta),
            'ArreglosMecanicos.empresas_idempresas' => $id_empresa])
            ->toArray();

        return $data;
    }

    private function getDataByGrupo($grupo = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //Uso la tabla que trae el ultimo año de registros
        //Para los grupos puedo utilizar las maquinas

        if($all_date == 'SI')
        {
            $data = $this->ArreglosMecanicos->find('all', [
                'contain' => ['Users', 'Maquinas'=> ['CostosMaquinas' =>
                ['Worksgroups' => function(Query $q) use ($grupo){
                    return $q->where(['idworksgroups' => $grupo]);
                }]]]
                ])->where(['ArreglosMecanicos.empresas_idempresas' => $id_empresa]);


        } else {
            //Uso la Vista arreglos mecanicos year
            $arreglos_model = $this->loadModel('ArreglosMecanicosYear');
            $data = $arreglos_model->find('all', [
                'contain' => ['Users', 'Maquinas'=> ['CostosMaquinas' =>
                    ['Worksgroups' => function(Query $q) use ($grupo){
                    return $q->where(['idworksgroups' => $grupo]);
                }]]]
            ])->where(['ArreglosMecanicosYear.empresas_idempresas' => $id_empresa]);

        }

        //recorro y devuelvo los que coinciden con el id
        $data_new = [];
        foreach ($data as $arreglo)
        {
            if(!empty($arreglo->maquina->costos_maquinas)){

                $data_new[] = $arreglo;
            }
        }


        return $data_new;

    }

    private function getDataByMaquina($maquina = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        if($all_date == 'SI')
        {
            //Uso la tabla arreglos mecanicos
            $data = $this->ArreglosMecanicos->find('all', [
                'contain' => ['Users', 'Maquinas']
            ])->where(['maquinas_idmaquinas' => $maquina, 'ArreglosMecanicos.empresas_idempresas' => $id_empresa])
                ->toArray();



        } else {
            //Uso la Vista arreglos mecanicos year
            $arreglos_model = $this->loadModel('ArreglosMecanicosYear');

            //Uso la tabla arreglos mecanicos
            $data = $arreglos_model->find('all', [
                'contain' => ['Users', 'Maquinas']
            ])->where(['maquinas_idmaquinas' => $maquina, 'ArreglosMecanicosYear.empresas_idempresas' => $id_empresa])
                ->toArray();


        }

        return $data;
    }

    private function getDataByParcela($parcela = null, $all_date = null, $id_empresa = null)
    {

        $this->autoRender =  false;
        $data = [];

        if($all_date == 'SI')
        {
            //Uso la tabla arreglos mecanicos
            $data = $this->ArreglosMecanicos->find('all', [
                'contain' => ['Users', 'Maquinas']
            ])->where(['parcelas_idparcelas' => $parcela, 'ArreglosMecanicos.empresas_idempresas' => $id_empresa])
                ->toArray();

        } else {
            //Uso la Vista arreglos mecanicos year
            $arreglos_model = $this->loadModel('ArreglosMecanicosYear');

            //Uso la tabla arreglos mecanicos
            $data = $arreglos_model->find('all', [
                'contain' => ['Users', 'Maquinas']
            ])->where(['parcelas_idparcelas' => $parcela, 'ArreglosMecanicosYear.empresas_idempresas' => $id_empresa])
                ->toArray();
        }


        return $data;

    }

    private function getDataByUsuario($usuario = null, $all_date = null, $id_empresa = null)
    {

        $this->autoRender =  false;
        $data = [];

        if($all_date == 'SI')
        {
            //Uso la tabla arreglos mecanicos
            $data = $this->ArreglosMecanicos->find('all', [
                'contain' => ['Users', 'Maquinas']
            ])->where(['ArreglosMecanicos.users_idusers' => $usuario, 'ArreglosMecanicos.empresas_idempresas' => $id_empresa])
                ->toArray();

        } else {
            //Uso la Vista arreglos mecanicos year
            $arreglos_model = $this->loadModel('ArreglosMecanicosYear');

            //Uso la tabla arreglos mecanicos
            $data = $arreglos_model->find('all', [
                'contain' => ['Users', 'Maquinas']
            ])->where(['ArreglosMecanicosYear.users_idusers' => $usuario, 'ArreglosMecanicosYear.empresas_idempresas' => $id_empresa])
                ->toArray();
        }


        return $data;

    }

    public function delete()
    {
        $this->autoRender =  false;
        $id_arreglo = $_POST['id'];

        if(!is_null($id_arreglo) and $id_arreglo != ''){

            if($this->request->is('ajax')) {

                try{

                    $arreglos =  $this->ArreglosMecanicos->get($id_arreglo);

                    if($this->ArreglosMecanicos->delete($arreglos)){

                        return $this->json(['result' => true]);
                    }

                } catch (InvalidPrimaryKeyException $e){
                    $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

                } catch (RecordNotFoundException $e){
                    $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                }
                catch (Exception $e){
                    $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                }

            }

        }

        return $this->json(['result' => false]);
    }

    public function remove($id = null){
        $this->request->allowMethod(['post', 'delete']);
        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Parcelas';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $arreglos =  $this->ArreglosMecanicos->get($id);

            if ($this->ArreglosMecanicos->delete($arreglos)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
            }
        }
        catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }
    }


}
