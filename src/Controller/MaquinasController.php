<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Maquinas Controller
 *
 * @property \App\Model\Table\MaquinasTable $Maquinas
 */
class MaquinasController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive', 'getCentroCostosByMaquinaAndGroups',
                'indexCostos', 'addCostos', 'updateCostos', 'viewCostosMaq', 'viewCostosMaquinaHistory', 'viewAllCostosMaquinas',
                'deleteCostosMaquina', 'getCentroCostosByMaquinaAndGroups', 'editCostosMaquina'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive', 'getCentroCostosByMaquinaAndGroups',
                'indexCostos', 'addCostos', 'updateCostos', 'viewCostosMaq', 'viewCostosMaquinaHistory', 'viewAllCostosMaquinas',
                'deleteCostosMaquina', 'getCentroCostosByMaquinaAndGroups', 'editCostosMaquina'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    public function index()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $maquinas = null;

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        } else {
            $maquinas = $this->Maquinas->find('all', [
                'contain' => ['Users']
            ])->where(['Maquinas.active' => true, 'Maquinas.empresas_idempresas' => $id_empresa]);

        }

        $this->set(compact('maquinas'));
    }

    public function showInactive()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        } else {
            $maquinas = $this->Maquinas->find('all', [
                'contain' => ['Users']
            ])->where(['Maquinas.active' => false, 'Maquinas.empresas_idempresas' => $id_empresa]);
            $this->set(compact('maquinas'));
        }
    }


    public function add()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $maquinas = $this->Maquinas->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $maquinas = $this->Maquinas->patchEntity($maquinas, $this->request->getData());

            //AGrego lo datos falantes
            $maquinas->created = date("Y-m-d");
            $maquinas->empresas_idempresas = $id_empresa;
            $maquinas->users_idusers = $user_id;

            if ($this->Maquinas->save($maquinas)) {
                $this->Flash->success(__('La Máquina se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }
        $this->set(compact('maquinas'));
    }

    public function edit($id = null)
    {


        try {

            //de donde sale la empresa???

            //Traigo los datos de la sesion
            $session = $this->request->getSession();
            $user_id = $session->read('Auth.User.idusers');
            $user_role = $session->read('Auth.User.role');
            $id_empresa = $session->read('Auth.User.Empresa.idempresas');

            if (empty($id_empresa)) {
                $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
                return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
            }

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Maquinas';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));
            $maquinas = $this->Maquinas->get($id);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $maquinas = $this->Maquinas->patchEntity($maquinas, $this->request->getData());

                if ($this->Maquinas->save($maquinas)) {
                    $this->Flash->success(__('La Máquina se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }
            }
            $this->set(compact('maquinas'));
        } catch (InvalidPrimaryKeyException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        } catch (Exception $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }

    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try {

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Maquinas';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $maquinas = $this->Maquinas->get($id);

            if ($this->Maquinas->delete($maquinas)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
            }

        } catch (InvalidPrimaryKeyException $e) {
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        } catch (Exception $e) {
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }

    }


    public function indexCostos($id = null)
    {
        $costos_maq_model = $this->loadModel('CostosMaquinas');

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $this->set(compact('id'));

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        try {

            $maquinas_costos = $costos_maq_model->find('all', [
                'contain' => ['Maquinas', 'Worksgroups', 'CentrosCostos', 'MetodCostos', 'Users']
            ])
                ->where(['maquinas_idmaquinas' => $id, 'CostosMaquinas.active' => true]);


            $this->set(compact('maquinas_costos'));

        } catch (InvalidPrimaryKeyException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        } catch (Exception $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }


    }

    public function addCostos($id = null)
    {
        $costos_maq_model = $this->loadModel('CostosMaquinas');

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $maquinas_costos = $costos_maq_model->newEntity();


        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        if (empty($id)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Maquinas', 'action' => 'index']);
        }

        try {

            $maquinas = $this->Maquinas->get($id);

            //TRaigo los grupos todos sin filtros
            $grupos_model = $this->loadModel('Worksgroups');
            $grupos_data = $grupos_model->find('list', [
                'keyField' => 'idworksgroups',
                'valueField' => 'name'
            ])
                ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                ->toArray();


            //TRaigo los valores de la metodología de costos
            $metod_costos_model = $this->loadModel('MetodCostos');

            $metod_costos_data = $metod_costos_model->find('list', [
                'keyField' => 'idmetod_costos',
                'valueField' => 'name'
            ])
                ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                ->toArray();

            //Guardo la informacion
            if ($this->request->is('post')) {

                $data = $this->request->getData();

                $maquinas_costos = $costos_maq_model->patchEntity($maquinas_costos, $data);

                $maquinas_costos->maquinas_idmaquinas = $id;
                $maquinas_costos->users_idusers = $user_id;
                $maquinas_costos->active = true;
                $maquinas_costos->hash_id = hash('sha256', ($maquinas->name . date("Y-m-d H:i:s")));

                if ($costos_maq_model->save($maquinas_costos)) {
                    $this->Flash->success(__('El Registro se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'indexCostos', $id]);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }

            }

            $this->set(compact('grupos_data'));
            $this->set(compact('metod_costos_data'));
            $this->set(compact('maquinas'));
            $this->set(compact('maquinas_costos'));
            $this->set(compact('id'));

        } catch (InvalidPrimaryKeyException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        } catch (Exception $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }

    }

    public function editCostosMaquina($id = null, $id_maquina = null)
    {

        $costos_maq_model = $this->loadModel('CostosMaquinas');

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        try {


            //agregare un where si o si USARE UN FIND
            $maquinas_costos = $costos_maq_model->get($id,
                ['contain' => ['Maquinas', 'Worksgroups', 'CentrosCostos', 'MetodCostos'],
                    'conditions' => ['CostosMaquinas.active' => true]]);


            //COnsulto si esta undefinido y vuelvo
            if (!isset($maquinas_costos)) {

                return $this->redirect(['action' => 'index']);
            }


            //debug($maquinas_costos->toArray());


            //TRaigo los grupos todos sin filtros
            $grupos_model = $this->loadModel('Worksgroups');
            $grupos_data = $grupos_model->find('list', [
                'keyField' => 'idworksgroups',
                'valueField' => 'name'
            ])
                ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                ->toArray();


            //traigo el centro de costos
            $centro_costos_model = $this->loadModel('CentrosCostos');
            $centro_costo_data = $centro_costos_model->find('list', [

                'keyField' => 'idcentros_costos',
                'valueField' => 'name'
            ])
                ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                ->toArray();


            //TRaigo los valores de la metodología de costos
            $metod_costos_model = $this->loadModel('MetodCostos');

            $metod_costos_data = $metod_costos_model->find('list', [
                'keyField' => 'idmetod_costos',
                'valueField' => 'name'
            ])
                ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                ->toArray();



            if ($this->request->is(['patch', 'post', 'put'])) {

                $maquinas_costos = $costos_maq_model->patchEntity($maquinas_costos, $this->request->getData());

                if ($costos_maq_model->save($maquinas_costos)) {
                    $this->Flash->success(__('La Máquina se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'indexCostos', $id_maquina]);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }
            }




            $this->set(compact('id_maquina'));
            $this->set(compact('grupos_data'));
            $this->set(compact('centro_costo_data'));
            $this->set(compact('metod_costos_data'));
            $this->set(compact('maquinas_costos'));

        } catch (InvalidPrimaryKeyException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);

        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);
        } catch (Exception $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);
        }



    }

    public function updateCostos($id = null, $id_maquina = null)
    {
        //CONTROLAR QUE SE PRODUZCAN CAMBIOS EN LOS DATOS


        $costos_maq_model = $this->loadModel('CostosMaquinas');

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        try {


            //agregare un where si o si USARE UN FIND
            $maquinas_costos = $costos_maq_model->get($id,
                ['contain' => ['Maquinas', 'Worksgroups', 'CentrosCostos', 'MetodCostos'],
                    'conditions' => ['CostosMaquinas.active' => true]]);


            //COnsulto si esta undefinido y vuelvo
            if (!isset($maquinas_costos)) {

                return $this->redirect(['action' => 'index']);
            }


            //debug($maquinas_costos->toArray());


            //TRaigo los grupos todos sin filtros
            $grupos_model = $this->loadModel('Worksgroups');
            $grupos_data = $grupos_model->find('list', [
                'keyField' => 'idworksgroups',
                'valueField' => 'name'
            ])
                ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                ->toArray();


            //traigo el centro de costos
            $centro_costos_model = $this->loadModel('CentrosCostos');
            $centro_costo_data = $centro_costos_model->find('list', [

                'keyField' => 'idcentros_costos',
                'valueField' => 'name'
            ])
                ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                ->toArray();


            //TRaigo los valores de la metodología de costos
            $metod_costos_model = $this->loadModel('MetodCostos');

            $metod_costos_data = $metod_costos_model->find('list', [
                'keyField' => 'idmetod_costos',
                'valueField' => 'name'
            ])
                ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                ->toArray();


            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();

                $new_costos_maquina = $costos_maq_model->newEntity();

                $new_costos_maquina = $costos_maq_model->patchEntity($new_costos_maquina, $this->request->getData());

                //COpio los datos restantes de los costos maquinas que filtre

                $new_costos_maquina->maquinas_idmaquinas = $maquinas_costos->maquina->idmaquinas;
                $new_costos_maquina->worksgroups_idworksgroups = $maquinas_costos->worksgroup->idworksgroups;
                $new_costos_maquina->centros_costos_idcentros_costos = $maquinas_costos->centros_costo->idcentros_costos;
                $new_costos_maquina->metod_costos_idmetod_costos = $maquinas_costos->metod_costo->idmetod_costos;

                $new_costos_maquina->active = true;
                $new_costos_maquina->users_idusers = $user_id;

                $new_costos_maquina->hash_id = $maquinas_costos->hash_id;


                //Primero comparo el array

                if ($this->checkChangesInUpdate($maquinas_costos, $new_costos_maquina)) {

                    if ($costos_maq_model->save($new_costos_maquina)) {

                        //Seteo a false el anterior
                        $maquinas_costos->active = false;
                        $maquinas_costos->finished = date('Y-m-d H:i:s');

                        if ($costos_maq_model->save($maquinas_costos)) {
                            $this->Flash->success(__('La Máquina se ha almacenado correctamente'));
                            return $this->redirect(['action' => 'indexCostos', $maquinas_costos->maquinas_idmaquinas]);

                        } else {
                            $this->Flash->error(__('Error al setear el ultimo registro como inactivo. Modofique manualmente'));
                        }

                    } else {
                        $this->Flash->error(__('Error al almacenar. Intenta nuevamente 44'));
                        return $this->redirect(['action' => 'indexCostos', $maquinas_costos->maquinas_idmaquinas]);
                    }

                } else {
                    $this->Flash->error(__('Error. No sé han realizado cambios. ¡Modifique los valores para actualizar!'));
                    return $this->redirect(['action' => 'indexCostos', $maquinas_costos->maquinas_idmaquinas]);
                }
            }

            $this->set(compact('id_maquina'));
            $this->set(compact('grupos_data'));
            $this->set(compact('centro_costo_data'));
            $this->set(compact('metod_costos_data'));
            $this->set(compact('maquinas_costos'));

        } catch (InvalidPrimaryKeyException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);

        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);
        } catch (Exception $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);
        }

    }

    private function checkChangesInUpdate($array_current = null, $array_new = null)
    {
        $this->autoRender = false;

        //Verifico los cambios

        if ($array_current->val_adq != $array_new->val_adq or $array_current->val_neum != $array_new->val_neum or
            $array_current->vida_util != $array_new->vida_util or $array_current->vida_util_neum != $array_new->vida_util_neum or
            $array_current->horas_total_uso != $array_new->horas_total_uso or $array_current->horas_efec_uso != $array_new->horas_efec_uso or
            $array_current->horas_mens_uso != $array_new->horas_mens_uso or $array_current->horas_dia_uso != $array_new->horas_dia_uso or
            $array_current->tasa_int_simple != $array_new->tasa_int_simple or $array_current->factor_cor != $array_new->factor_cor or
            $array_current->coef_err_mec != $array_new->coef_err_mec or $array_current->consumo != $array_new->consumo or
            $array_current->lubricante != $array_new->lubricante or $array_current->costo_alquiler != $array_new->costo_alquiler) {
            return true;
        }

        return false;

    }

    public function viewCostosMaq()
    {
        $this->autoRender = false;
        $id_costo_maq = $_POST['id_costo_maq'];

        $costos_maq_model = $this->loadModel('CostosMaquinas');
        $centro_costos_model = $this->loadModel('CentrosCostos');

        $array_data = [];

        if($this->request->is('ajax')) {

            //Utilizo el subquery para traer los datos
            $array_data = $costos_maq_model->find('all', [
                'contain' => ['Maquinas', 'Worksgroups', 'CentrosCostos', 'MetodCostos']
            ])
                ->where(['idcostos_maquinas' => $id_costo_maq]) ->toArray();
        }

        return $this->json($array_data);
    }

    public function viewCostosMaquinaHistory($hash_id = null, $id_maquina = null)
    {

        $costos_maq_model = $this->loadModel('CostosMaquinas');

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

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

        try{

            $maquinas_costos =  $costos_maq_model->find('all', [
                'contain' => ['Maquinas', 'Worksgroups', 'CentrosCostos', 'MetodCostos', 'Users']
            ])
                ->where(['CostosMaquinas.hash_id' => $hash_id]);



            $this->set(compact('maquinas_costos'));
            $this->set(compact('hash_id'));
            $this->set(compact('id_maquina'));

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }

    }

    public function viewAllCostosMaquinas($id = null)
    {

        //Traigo todos los registros sin distringuir su estado

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $this->set(compact('id'));

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        $costos_maq_model = $this->loadModel('CostosMaquinas');


        try {

            $maquinas_costos = $costos_maq_model->find('all', [
                'contain' => ['Maquinas', 'Worksgroups', 'CentrosCostos', 'MetodCostos', 'Users']
            ])
                ->where(['maquinas_idmaquinas' => $id]);

            //debug($maquinas_costos-> toArray());

            $this->set(compact('maquinas_costos'));

        } catch (InvalidPrimaryKeyException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);

        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);
        } catch (Exception $e) {
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);
        }


    }


    public function deleteCostosMaquina($id = null, $id_maquina = null, $hash_id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Maquinas';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $costos_maq_model = $this->loadModel('CostosMaquinas');



            $costos_maq =  $costos_maq_model->get($id);

            if ($costos_maq_model->delete($costos_maq)) {

                //Si elimino el active debo setear el ultimo como default


                $this->Flash->success(__('El Registro ha sido eliminado.'));

                //Verifico de donde viene la solicitud y renvio segun eso
                if(is_null($hash_id)){

                    return $this->redirect(['action' => 'indexCostos', $id_maquina]);

                } else {
                    return $this->redirect(['action' => 'viewCostosMaquinaHistory', $hash_id]);
                }


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



    /** MANEJO VIA JQUERY LOS DEMAS DATOS */

    public function getCentroCostosByMaquinaAndGroups()
    {
        $this->autoRender = false;

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        $worksgroup = $_POST['worksgroup'];
        $maquina = $_POST['maquina'];

        $costos_maq_model = $this->loadModel('CostosMaquinas');
        $centro_costos_model = $this->loadModel('CentrosCostos');

        $array_data = [];

        if($this->request->is('ajax')) {


            //Utilizo el subquery para traer los datos
            $costos_maq = $costos_maq_model->find('all', [])
                ->select(['centros_costos_idcentros_costos'])
                ->where(['active' => true, 'maquinas_idmaquinas' => $maquina, 'worksgroups_idworksgroups' => $worksgroup]);

            $array_data = $centro_costos_model->find('all', [
            ])
                ->where(['idcentros_costos NOT IN' => $costos_maq, 'empresas_idempresas' => $id_empresa])
                ->toArray();

        }

        return $this->json($array_data);

    }






}
