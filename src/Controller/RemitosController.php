<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Remitos Controller
 *
 * @property \App\Model\Table\RemitosTable $Remitos
 */
class RemitosController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'removeRemitoMaquina', 'delete', 'remove',
                'addMaquinas', 'view', 'getLotes', 'getPropietarios', 'getProductos', 'getDestinos', 'getDataFromRemitos'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'removeRemitoMaquina', 'delete', 'remove',
                'addMaquinas', 'view', 'getLotes', 'getPropietarios', 'getProductos', 'getDestinos', 'getDataFromRemitos'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    public function index()
    {
        //Variable usada para el sidebar
        $seccion = 'remitos';
        $sub_seccion = 'Inicio';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $this->set(compact('id_empresa'));

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        } else {

            //Debo limitar a un top de 10.000 registros o ultimos 6 meses

            $today = date('Y-m-d');

            $six_back_day = date('Y-m-d',strtotime($today."- 6 month"));

            $conditions = ['Remitos.fecha <=' => $today, 'Remitos.fecha >=' => $six_back_day];


            $remitos = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users'],
                'conditions' => $conditions
            ])->where(['Remitos.empresas_idempresas' => $id_empresa, 'Remitos.active' => true])
                ->order(['Remitos.remito_number ASC']);

            //debug($remitos);

            $this->set(compact('remitos'));

            //debug($remitos->toArray());
        }
    }


    public function add()
    {
        //Variable usada para el sidebar
        $seccion = 'remitos';
        $sub_seccion = '';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');

        $succes_var =   $session->read('success_var');


        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'indexUser']);
        }


        if ($succes_var) {

            //ELimino de la cache
            $session->delete('success_var');

            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        //cuento cuantos number remitos hay y tomo el valor max

        //Variable que indica si esta guardado
        $is_save = false;


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


        //Traigo los datos de los propietarios
        $tablaProductos = $this->loadModel('Productos');

        $productos = $this->Productos->find('all', [
            'contain' => ['Users']
        ])->where(['Productos.active' => true, 'Productos.empresas_idempresas' => $id_empresa]);
        $this->set(compact('productos'));



        $tablaWorksgroups = $this->loadModel('Worksgroups');

        $lista_worksgroups =  $tablaWorksgroups->find('list', [
            'keyField' => 'idworksgroups',
            'valueField' => 'name',
            'order' => ['name' => 'ASC']
        ])->where(['active' => true, 'empresas_idempresas' => $id_empresa])
            ->toArray();

        $this->set(compact('lista_worksgroups'));

        //si es null es porque no trajo valor y asigno 1
        $remito_number_value = null;


        //debug($number_remito_new);
        //debug($number_remito_new->toArray());

        //CREO la ENTITY

        $remitos_entity = $this->Remitos->newEntity();


        if ($this->request->is('post')) {

            $data = $this->request->getData();

            $remitos_entity = $this->Remitos->patchEntity($remitos_entity, $data);

            $remitos_entity->users_idusers = $user_id;
            $remitos_entity->empresas_idempresas = $id_empresa;

            //EL valor del remito numer ser vuelve a cargar
            //TRaigo el posible valor del remito

            $number_remito_new = $this->Remitos->find('all', [

            ])->where(['empresas_idempresas' => $id_empresa])
                ->max('remito_number');

            //debug($number_remito_new);
            //debug($number_remito_new->remito_number);

            if(is_null($number_remito_new)){
                $remito_number_value = 1;
            } else {
                $remito_number_value = $number_remito_new->remito_number + 1;
            }

            $remitos_entity->remito_number = $remito_number_value;

            $remitos_entity->hash_id = hash('sha256' , ($remito_number_value . date("Y-m-d")));

            if($this->Remitos->save($remitos_entity)){

                $succes_var = true;
                $session->write('success_var', $succes_var);

                $this->Flash->success(__('El Registro se ha almacenado correctamente'));

                //Imprimo el resultado
                //debug($remitos_entity);

                return $this->redirect(['action' => 'addMaquinas', $remitos_entity->idremitos]);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                //debug($remitos_entity->getErrors());
            }
        }

        $this->set(compact('remitos_entity'));

        $this->set(compact('remito_number_value'));
        $this->set(compact('is_save'));


    }

    public function addMaquinas($id = null)
    {
        //Variable usada para el sidebar
        $seccion = 'remitos';
        $sub_seccion = '';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');


        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'indexUser']);
        }

        if (is_null($id)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Remitos', 'action' => 'index']);
        }

        try {

            $remitos = $this->Remitos->get($id, [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Productos', 'Destinos']
            ]);

            $this->set(compact('remitos'));


            $remitos_maq_model = $this->loadModel('RemitosMaquinas');

            $remitos_maq_data = $remitos_maq_model->find('all', [
                'contain' => ['Maquinas', 'Operarios']
            ])->where(['remitos_idremitos' => $id]);

            $this->set(compact('remitos_maq_data'));

            $remitos_maq_aux = $remitos_maq_model->find()
                ->select(['maquinas_idmaquinas', 'operarios_idoperarios'])
                ->where(['remitos_idremitos' => $id]);

            $operarios_maq_aux = $remitos_maq_model->find()
                ->select(['operarios_idoperarios', 'operarios_idoperarios'])
                ->where(['remitos_idremitos' => $id]);



            //Para traer las MAquinas verifico que tenga cargado los DATOS TEORICOS

            //Traigo las maquinas de la tabla operariosmaquinas
            $model_operario_maq = $this->loadModel('OperariosMaquinas');
            $oper_maq_data = $model_operario_maq->find('all',[
                'contain' => ['Maquinas' => ['CostosMaquinas'], 'Operarios']
            ])->where(['Maquinas.empresas_idempresas' => $id_empresa, 'OperariosMaquinas.active' => true,
                '(idmaquinas, idoperarios) NOT IN' => $remitos_maq_aux]);

            $this->set(compact('oper_maq_data'));

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }


    }


    public function view($id_remito = null)
    {
        //Variable usada para el sidebar
        $seccion = 'remitos';
        $sub_seccion = '';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');


        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'indexUser']);
        }

        if (is_null($id_remito)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Remitos', 'action' => 'index']);
        }

        try {

            $remitos = $this->Remitos->get($id_remito, [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Productos', 'Destinos', 'Users',
                    'RemitosMaquinas' => ['Maquinas', 'Operarios']]
            ]);

            //debug($remitos->toArray());

            $this->set(compact('remitos'));



        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }




    }


    public function edit($id = null)
    {

        if(is_null($id))
        {
            return $this->redirect(['controller' => 'Remitos', 'action' => 'index']);
        } else {

            $seccion = 'remitos';
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

            try {

                $remito = $this->Remitos->get($id, [
                    'contain' => ['Parcelas' => 'Lotes', 'Propietarios', 'Destinos', 'Productos']
                ]);

                //TRaigo los workgroups
                $tablaWorksgroups = $this->loadModel('Worksgroups');

                $lista_worksgroups =  $tablaWorksgroups->find('list', [
                    'keyField' => 'idworksgroups',
                    'valueField' => 'name',
                    'order' => ['name' => 'ASC']
                ])->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                    ->toArray();

                $this->set(compact('lista_worksgroups'));


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


                //Traigo los datos de los propietarios
                $tablaProductos = $this->loadModel('Productos');

                $productos = $this->Productos->find('all', [
                    'contain' => ['Users']
                ])->where(['Productos.active' => true, 'Productos.empresas_idempresas' => $id_empresa]);
                $this->set(compact('productos'));

                //Cargo los lotes

                $lote = [$remito->parcela->lote->idlotes => $remito->parcela->lote->name];

                $this->set(compact('lote'));

                //Vaores de la Parcela
                $parcela = [$remito->parcela->idparcelas => $remito->parcela->name];
                $this->set(compact('parcela'));

                //Propietarios
                if($remito->propietario->tipo == 'Empresa'){
                    $propietario = [$remito->propietario->idpropietarios => $remito->propietario->name];
                    $this->set(compact('propietario'));
                } else {
                    $name = $remito->propietario->firstname .' '. $remito->propietario->lastname;
                    $propietario = [$remito->propietario->idpropietarios => $name];
                    $this->set(compact('propietario'));
                }

                //Vaores de la Parcela
                $destino = [$remito->destino->iddestinos => $remito->destino->name];
                $this->set(compact('destino'));


                //Vaores de la Parcela
                $producto = [$remito->producto->idproductos => $remito->producto->name];
                $this->set(compact('producto'));

                //debug($remito->toArray());

                if ($this->request->is(['patch', 'post', 'put'])) {
                    //debug($this->request->getData());

                    $remito = $this->Remitos->patchEntity($remito, $this->request->getData());

                    if($this->Remitos->save($remito)){
                        $this->Flash->success(__('El Registro se ha almacenado correctamente'));

                        return $this->redirect(['action' => 'addMaquinas', $remito->idremitos]);

                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                    }


                }


                $this->set(compact('remito'));

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


    public function removeRemitoMaquina($id = null, $id_remitos = null){

        $this->request->allowMethod(['post', 'delete']);

        try{

            //Variable usada para el sidebar
            $seccion = 'remitos';
            $sub_seccion = '';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            //Traigo las maquinas cargadas para este remito
            $remitos_maq_model = $this->loadModel('RemitosMaquinas');

            $remito_maq =  $remitos_maq_model->get($id);

            if ($remitos_maq_model->delete($remito_maq)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'addMaquinas', $id_remitos]);
            } else {
                $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
            }

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }
    }


    public function getLotes()
    {

        $this->autoRender = false;
        $empresa = $_POST['empresa'];
        $array_data = [];

        $lotes_model = $this->loadModel('Lotes');

        if($this->request->is('ajax')) {

            $array_data = $lotes_model->find('all', [

            ])->select(['idlotes' => 'idlotes','name' => 'name'])
                ->where(['empresas_idempresas' => $empresa])
                ->order(['name ASC'])
                ->toArray();
        }

        return $this->json($array_data);
    }

    public function getPropietarios()
    {

        $this->autoRender = false;
        $empresa = $_POST['empresa'];
        $tipo = $_POST['tipo'];

        $array_data = [];

        $lotes_model = $this->loadModel('Propietarios');

        if($this->request->is('ajax')) {

            if($tipo == 'Empresa'){
                $array_data = $lotes_model->find('all', [

                ])->select(['idpropietarios' => 'idpropietarios','name' => 'name'])
                    ->where(['empresas_idempresas' => $empresa, 'tipo' => $tipo])
                    ->order(['name ASC'])
                    ->toArray();
            } else {

                $array_data = $lotes_model->find('all', [

                ])->select(['idpropietarios' => 'idpropietarios', 'firstname' => 'firstname', 'lastname' => 'lastname'])
                    ->where(['empresas_idempresas' => $empresa, 'tipo' => $tipo])
                    ->order(['name ASC'])
                    ->toArray();
            }

        }

        return $this->json($array_data);
    }

    public function getProductos()
    {

        $this->autoRender = false;
        $empresa = $_POST['empresa'];
        $array_data = [];

        $productos_model = $this->loadModel('Productos');

        if($this->request->is('ajax')) {

            $array_data = $productos_model->find('all', [

            ])->select(['idproductos' => 'idproductos','name' => 'name'])
                ->where(['empresas_idempresas' => $empresa])
                ->order(['name ASC'])
                ->toArray();
        }

        return $this->json($array_data);
    }

    public function getDestinos()
    {

        $this->autoRender = false;
        $empresa = $_POST['empresa'];
        $array_data = [];

        $productos_model = $this->loadModel('Destinos');

        if($this->request->is('ajax')) {

            $array_data = $productos_model->find('all', [

            ])->select(['iddestinos' => 'iddestinos','name' => 'name'])
                ->where(['empresas_idempresas' => $empresa])
                ->order(['name ASC'])
                ->toArray();
        }

        return $this->json($array_data);
    }

    public function getDataFromRemitos()
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

            } elseif ($option_select == 'Lote') {
                $value_option = $data[0];

                $array_data = $this->getDataByLote($value_option, $all_date, $id_empresa);

            } elseif ($option_select == 'Propietario') {
                $value_option = $data[0];

                $array_data = $this->getDataByPropietario($value_option, $all_date, $id_empresa);

            } elseif ($option_select == 'Producto') {
                $value_option = $data[0];

                $array_data = $this->getDataByProducto($value_option, $all_date, $id_empresa);

            } elseif ($option_select == 'Destino') {
                $value_option = $data[0];

                $array_data = $this->getDataByDestino($value_option, $all_date, $id_empresa);
            }
        }

        return $this->json($array_data);


    }

    private function getDataByFecha($fecha_desde = null, $fecha_hasta = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //no uso all_date dado que tengo el rango de fechas

        $data = $this->Remitos->find('all', [
            'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users']
        ])->where(['Remitos.fecha >=' => strval($fecha_desde), 'Remitos.fecha <=' => strval($fecha_hasta),
            'Remitos.empresas_idempresas' => $id_empresa])
            ->toArray();

        return $data;


    }

    private function getDataByGrupo($grupo = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //Debo limitar a un top de 10.000 registros o ultimos 6 meses

        $today = date('Y-m-d');

        $six_back_day = date('Y-m-d',strtotime($today."- 6 month"));
        $conditions = ['Remitos.fecha <=' => $today, 'Remitos.fecha >=' => $six_back_day];


        //Uso la tabla que trae el ultimo año de registros
        if($all_date == 'SI')
        {

            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users']
            ])->where(['worksgroups_idworksgroups' => $grupo,
                'Remitos.empresas_idempresas' => $id_empresa])
                ->toArray();

        } else {
            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users'],
                'conditions' => $conditions
            ])->where(['worksgroups_idworksgroups' => $grupo,
                'Remitos.empresas_idempresas' => $id_empresa, 'Remitos.active' => true])
                ->toArray();
        }

        return $data;

    }

    private function getDataByMaquina($maquina = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //Debo limitar a un top de 10.000 registros o ultimos 6 meses

        $today = date('Y-m-d');

        $six_back_day = date('Y-m-d',strtotime($today."- 6 month"));
        $conditions = ['Remitos.fecha <=' => $today, 'Remitos.fecha >=' => $six_back_day];


        $remitos_maq_model = $this->loadModel('RemitosMaquinas');
        $remitos_maq_data = $remitos_maq_model->find('all', [
            'contain' => ['Remitos'],
            'fields' => ['remitos_idremitos']
        ])->distinct(['remitos_idremitos'])
            ->where(['maquinas_idmaquinas' => $maquina]);

        //$data = $remitos_maq_data;
        //Uso la tabla que trae el ultimo año de registros
        if($all_date == 'SI')
        {

            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users']
            ])->where(['Remitos.empresas_idempresas' => $id_empresa, 'idremitos IN' => $remitos_maq_data])
                ->toArray();

        } else {
            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users'],
                'conditions' => $conditions
            ])->where(['Remitos.empresas_idempresas' => $id_empresa, 'Remitos.active' => true, 'idremitos IN' => $remitos_maq_data])
                ->toArray();
        }

        return $data;
    }

    private function getDataByParcela($parcela = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //Debo limitar a un top de 10.000 registros o ultimos 6 meses

        $today = date('Y-m-d');

        $six_back_day = date('Y-m-d',strtotime($today."- 6 month"));
        $conditions = ['Remitos.fecha <=' => $today, 'Remitos.fecha >=' => $six_back_day];


        //Uso la tabla que trae el ultimo año de registros
        if($all_date == 'SI')
        {

            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users']
            ])->where(['parcelas_idparcelas' => $parcela,
                'Remitos.empresas_idempresas' => $id_empresa])
                ->toArray();

        } else {
            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users'],
                'conditions' => $conditions
            ])->where(['parcelas_idparcelas' => $parcela,
                'Remitos.empresas_idempresas' => $id_empresa, 'Remitos.active' => true])
                ->toArray();
        }

        return $data;

    }

    private function getDataByUsuario($usuario = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //Debo limitar a un top de 10.000 registros o ultimos 6 meses

        $today = date('Y-m-d');

        $six_back_day = date('Y-m-d',strtotime($today."- 6 month"));
        $conditions = ['Remitos.fecha <=' => $today, 'Remitos.fecha >=' => $six_back_day];


        //Uso la tabla que trae el ultimo año de registros
        if($all_date == 'SI')
        {

            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users']
            ])->where(['Remitos.users_idusers' => $usuario,
                'Remitos.empresas_idempresas' => $id_empresa])
                ->toArray();

        } else {
            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users'],
                'conditions' => $conditions
            ])->where(['Remitos.users_idusers' => $usuario,
                'Remitos.empresas_idempresas' => $id_empresa, 'Remitos.active' => true])
                ->toArray();
        }

        return $data;

    }

    private function getDataByLote($lote = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //Debo limitar a un top de 10.000 registros o ultimos 6 meses

        $today = date('Y-m-d');

        $six_back_day = date('Y-m-d',strtotime($today."- 6 month"));
        $conditions = ['Remitos.fecha <=' => $today, 'Remitos.fecha >=' => $six_back_day];


        //Uso la tabla que trae el ultimo año de registros
        if($all_date == 'SI')
        {

            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users']
            ])->where(['Parcelas.lotes_idlotes' => $lote,
                'Remitos.empresas_idempresas' => $id_empresa])
                ->toArray();

        } else {
            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users'],
                'conditions' => $conditions
            ])->where(['Parcelas.lotes_idlotes' => $lote,
                'Remitos.empresas_idempresas' => $id_empresa, 'Remitos.active' => true])
                ->toArray();
        }

        return $data;

    }

    private function getDataByPropietario($propietario = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //Debo limitar a un top de 10.000 registros o ultimos 6 meses

        $today = date('Y-m-d');

        $six_back_day = date('Y-m-d',strtotime($today."- 6 month"));
        $conditions = ['Remitos.fecha <=' => $today, 'Remitos.fecha >=' => $six_back_day];


        //Uso la tabla que trae el ultimo año de registros
        if($all_date == 'SI')
        {

            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users']
            ])->where(['Remitos.propietarios_idpropietarios' => $propietario,
                'Remitos.empresas_idempresas' => $id_empresa])
                ->toArray();

        } else {
            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users'],
                'conditions' => $conditions
            ])->where(['Remitos.propietarios_idpropietarios' => $propietario,
                'Remitos.empresas_idempresas' => $id_empresa, 'Remitos.active' => true])
                ->toArray();
        }

        return $data;

    }

    private function getDataByProducto($producto = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //Debo limitar a un top de 10.000 registros o ultimos 6 meses

        $today = date('Y-m-d');

        $six_back_day = date('Y-m-d',strtotime($today."- 6 month"));
        $conditions = ['Remitos.fecha <=' => $today, 'Remitos.fecha >=' => $six_back_day];


        //Uso la tabla que trae el ultimo año de registros
        if($all_date == 'SI')
        {

            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users']
            ])->where(['Remitos.productos_idproductos' => $producto,
                'Remitos.empresas_idempresas' => $id_empresa])
                ->toArray();

        } else {
            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users'],
                'conditions' => $conditions
            ])->where(['Remitos.productos_idproductos' => $producto,
                'Remitos.empresas_idempresas' => $id_empresa, 'Remitos.active' => true])
                ->toArray();
        }

        return $data;

    }

    private function getDataByDestino($destino = null, $all_date = null, $id_empresa = null)
    {
        $this->autoRender =  false;
        $data = [];

        //Debo limitar a un top de 10.000 registros o ultimos 6 meses

        $today = date('Y-m-d');

        $six_back_day = date('Y-m-d',strtotime($today."- 6 month"));
        $conditions = ['Remitos.fecha <=' => $today, 'Remitos.fecha >=' => $six_back_day];


        //Uso la tabla que trae el ultimo año de registros
        if($all_date == 'SI')
        {

            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users']
            ])->where(['Remitos.destinos_iddestinos' => $destino,
                'Remitos.empresas_idempresas' => $id_empresa])
                ->toArray();

        } else {
            $data = $this->Remitos->find('all', [
                'contain' => ['Worksgroups', 'Propietarios', 'Parcelas' => ['Lotes'], 'Destinos', 'Productos', 'Users'],
                'conditions' => $conditions
            ])->where(['Remitos.destinos_iddestinos' => $destino,
                'Remitos.empresas_idempresas' => $id_empresa, 'Remitos.active' => true])
                ->toArray();
        }

        return $data;

    }


    public function delete()
    {
        $this->autoRender =  false;
        $id_remito = $_POST['id'];

        if(!is_null($id_remito) and $id_remito != ''){

            if($this->request->is('ajax')) {

                try{

                    $remito =  $this->Remitos->get($id_remito);

                    if($this->Remitos->delete($remito)){

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

            $remito =  $this->Remitos->get($id);

            if ($this->Remitos->delete($remito)) {
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
