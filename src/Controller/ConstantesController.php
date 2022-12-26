<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Constantes Controller
 *
 * @property \App\Model\Table\ConstantesTable $Constantes
 */
class ConstantesController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'viewHistory', 'view'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'viewHistory', 'view'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    public function index()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Constantes';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $session = $this->request->getSession();
        $user = $session->read('Auth.User');

        if ((isset($user['role']) and $user['role'] === 'user') or (isset($user['role']) and $user['role'] === 'supervisor'))  {
            return $this->redirect(['action' => 'view']);
        }

    }



    public function view()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Constantes';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $constantes =  $this->Constantes->find('all', [
                'contain' => 'Users'
            ])->where(['Constantes.active' => true, 'Constantes.empresas_idempresas' => $id_empresa]);
            $this->set(compact('constantes'));
        }
    }




    public function add()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Constantes';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $constantes = $this->Constantes->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        //traigo la lista de constastes utilizadas por empresa con un distinc
        $constantes_aux = $this->Constantes->find()
            ->select(['name'])
            ->where(['empresas_idempresas' => $id_empresa]);

        //Traigo la Lista de Contantes
        $model_lista_constantes =  $this->loadModel('ListaConstantes');

        $lista_constantes =  $model_lista_constantes->find('list', [
            'keyField' => 'name',
            'valueField' => 'name',
            'order' => ['name' => 'ASC']
        ]) ->where(['(name) NOT IN' => $constantes_aux])
            ->toArray();

        //debo verificar que no este ya usada

        $this->set(compact('lista_constantes'));


        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $constantes = $this->Constantes->patchEntity($constantes, $this->request->getData());

            //AGrego lo datos falantes
            $constantes->created = date("Y-m-d");
            $constantes->empresas_idempresas = $id_empresa;
            $constantes->users_idusers = $user_id;

            if($this->Constantes->save($constantes)){
                $this->Flash->success(__('La Constante se ha almacenado correctamente'));
                return $this->redirect(['action' => 'view']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }
        $this->set(compact('constantes'));

    }

    public function edit($id = null)
    {
        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Constantes';
            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            //Traigo los datos de la sesion
            $session = $this->request->getSession();
            $user_id = $session->read('Auth.User.idusers');
            $user_role = $session->read('Auth.User.role');
            $id_empresa = $session->read('Auth.User.Empresa.idempresas');

            $constantes =  $this->Constantes->get($id);

            //Traigo la Lista de Contantes
            $model_lista_constantes =  $this->loadModel('ListaConstantesFilter');

            $lista_constantes =  $model_lista_constantes->find('list', [
                'keyField' => 'name',
                'valueField' => 'name',
                'order' => ['name' => 'ASC']
            ])->toArray();

            $this->set(compact('lista_constantes'));

            if ($this->request->is(['patch', 'post', 'put'])) {
                //Comparo las dos variables para saber si hubo cambios
                $data = $this->request->getData();

                if($data['description'] != $constantes->description or $data['value'] != $constantes->value){
                    //al actual lo seteo como inactivo
                    $constantes->active = 0;
                    $constantes->finished = date("Y-m-d");

                    if($this->Constantes->save($constantes)){

                        //setee a false el estado, entonces creo uno nuevo
                        $constantes_new = $this->Constantes->newEntity();

                        $constantes_new = $this->Constantes->patchEntity($constantes_new, $data);

                        //AGrego lo datos falantes
                        $constantes_new->created = date("Y-m-d");
                        $constantes_new->empresas_idempresas = $id_empresa;
                        $constantes_new->users_idusers = $user_id;


                        if($this->Constantes->save($constantes_new)){
                            $this->Flash->success(__('La Constante se ha almacenado correctamente'));
                            return $this->redirect(['action' => 'view']);
                        } else {
                            $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                        }

                    } else {
                        $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                    }

                } else {
                    //son iguales no hago nada

                    $this->Flash->error(__('No se han realizado modificaciones. Cambie los valores para proceder.'));
                    return $this->redirect(['action' => 'view']);
                }



                /*$data = $this->request->getData();
                $constantes = $this->Constantes->patchEntity($constantes, $this->request->getData());
                $constantes->active = $data['active'];
                if($this->Constantes->save($constantes)){
                    $this->Flash->success(__('La Constante se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }*/
            }
            $this->set(compact('constantes'));
        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
    }


    public function viewHistory($name = null)
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Constantes';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $constantes =  $this->Constantes->find('all', [
                'contain' => 'Users'
            ])->where(['Constantes.active' => false, 'Constantes.empresas_idempresas' => $id_empresa, 'Constantes.name' => $name])
                ->order(['Constantes.created DESC']);
            $this->set(compact('constantes'));

        }
    }



    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Constantes';
            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $constantes =  $this->Constantes->get($id);

            //debo consultar si esta constante se usa en otro lugar antes de eliminar

            if ($this->Constantes->delete($constantes)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'view']);
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
