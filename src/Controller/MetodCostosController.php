<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * MetodCostos Controller
 *
 * @property \App\Model\Table\MetodCostosTable $MetodCostos
 */
class MetodCostosController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'viewHistory', 'view', 'viewAll'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'viewHistory', 'view', 'viewAll'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }


    public function index()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'MetodCostos';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $metod_costos =  $this->MetodCostos->find('all', [
                'contain' => 'Users'
            ])->where(['MetodCostos.active' => true, 'MetodCostos.empresas_idempresas' => $id_empresa]);
            $this->set(compact('metod_costos'));
        }

    }


    public function view($id = null)
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'MetodCostos';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        if(empty($id))
        {
            return $this->redirect(['action' => 'index']);
        }
        try{
            $metodologia =  $this->MetodCostos->get($id);
            $this->set(compact('metodologia'));

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }

    }


    public function viewAll()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'MetodCostos';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $metod_costos =  $this->MetodCostos->find('all', [
                'contain' => 'Users'
            ])->where(['MetodCostos.empresas_idempresas' => $id_empresa])
                ->order(['MetodCostos.name ASC']);
            $this->set(compact('metod_costos'));
        }

    }


    public function viewHistory()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'MetodCostos';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $name = $this->request->getQuery(['name']);

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $metod_costos =  $this->MetodCostos->find('all', [
                'contain' => 'Users'
            ])->where(['MetodCostos.active' => false, 'MetodCostos.empresas_idempresas' => $id_empresa, 'MetodCostos.name' => $name])
                ->order(['MetodCostos.created DESC']);
            $this->set(compact('metod_costos'));
        }
    }


    public function add()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'MetodCostos';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        $metodologia = $this->MetodCostos->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        //Traigo la Lista de Contantes
        $model_lista_constantes =  $this->loadModel('ListaConstantes');

        $lista_constantes =  $model_lista_constantes->find('list', [
            'keyField' => 'name',
            'valueField' => 'name',
            'order' => ['name' => 'ASC']
        ])->where(['empresas_idempresas' => $id_empresa])->toArray();

        //debo verificar que no este ya usada

        $this->set(compact('lista_constantes'));


        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $metodologia = $this->MetodCostos->patchEntity($metodologia, $this->request->getData());

            //AGrego lo datos falantes
            $metodologia->created = date("Y-m-d");
            $metodologia->empresas_idempresas = $id_empresa;
            $metodologia->users_idusers = $user_id;

            if($this->MetodCostos->save($metodologia)){
                $this->Flash->success(__('La Metodología se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                //debug($metodologia->getErrors());
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }
        $this->set(compact('metodologia'));
    }


    public function edit($id = null)
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'MetodCostos';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        if(empty($id))
        {
            return $this->redirect(['action' => 'index']);
        }
        try{
            //Traigo los datos de la sesion
            $session = $this->request->getSession();
            $user_id = $session->read('Auth.User.idusers');
            $user_role = $session->read('Auth.User.role');
            $id_empresa = $session->read('Auth.User.Empresa.idempresas');

            $metodologia =  $this->MetodCostos->get($id);

            //Traigo la Lista de Contantes
            $model_lista_constantes =  $this->loadModel('ListaConstantes');

            $lista_constantes =  $model_lista_constantes->find('list', [
                'keyField' => 'name',
                'valueField' => 'name',
                'order' => ['name' => 'ASC']
            ])->where(['empresas_idempresas' => $id_empresa])->toArray();

            //debo verificar que no este ya usada

            $this->set(compact('lista_constantes'));


            if ($this->request->is(['patch', 'post', 'put'])) {
                //Comparo las dos variables para saber si hubo cambios
                $data = $this->request->getData();

                //Consulto si hay cambios en los datos
                 if ($this->compareData($data, $metodologia->toArray()))
                 {
                     //si hay cambios creo un nuevo registro y seteo a false el anterior

                     //al actual lo seteo como inactivo
                     $metodologia->active = 0;
                     $metodologia->finished = date("Y-m-d");

                     if($this->MetodCostos->save($metodologia)){

                         //setee a false el estado, entonces creo uno nuevo
                         $metodologia_new = $this->MetodCostos->newEntity();

                         $metodologia_new = $this->MetodCostos->patchEntity($metodologia_new, $data);

                         //AGrego lo datos falantes
                         $metodologia_new->created = date("Y-m-d");
                         $metodologia_new->empresas_idempresas = $id_empresa;
                         $metodologia_new->users_idusers = $user_id;

                         if($this->MetodCostos->save($metodologia_new)){
                             $this->Flash->success(__('La Matodología se ha actualizado correctamente'));
                             return $this->redirect(['action' => 'index']);
                         } else {
                             $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                         }

                     } else {
                         $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                     }


                 }
                 else {
                     //son iguales no hago nada

                     $this->Flash->error(__('No se han realizado modificaciones. Cambie los valores para proceder.'));
                     return $this->redirect(['action' => 'index']);
                 }

            }
            $this->set(compact('metodologia'));
        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }

    }

    private function compareData($array_data, $array_entity)
    {

       if($array_data['interes'] != $array_entity['interes'] or $array_data['seguro'] != $array_entity['seguro'] or
           $array_data['dep_maq'] != $array_entity['dep_maq'] or $array_data['dep_neum'] != $array_entity['dep_neum'] or
           $array_data['arreglos_maq'] != $array_entity['arreglos_maq'] or $array_data['cons_comb'] != $array_entity['cons_comb'] or
           $array_data['cons_lub'] != $array_entity['cons_lub'] or $array_data['operador'] != $array_entity['operador'] or
           $array_data['mantenimiento'] != $array_entity['mantenimiento'] or $array_data['administracion'] != $array_entity['administracion'])
       {

           return true;

       }

       return false;

    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try{
            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'MetodCostos';
            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $metodologia =  $this->MetodCostos->get($id);

            //debo consultar si esta constante se usa en otro lugar antes de eliminar

            if ($this->MetodCostos->delete($metodologia)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'index']);
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




}
