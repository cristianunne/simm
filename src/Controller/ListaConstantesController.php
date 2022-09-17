<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * ListaConstantes Controller
 *
 * @property \App\Model\Table\ListaConstantesTable $ListaConstantes
 */
class ListaConstantesController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete'])) {
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


        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $lista_constantes_ent =  $this->ListaConstantes->newEntity();

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $lista_constantes =  $this->ListaConstantes->find('all', [
                'contain' => 'Users'
            ])->where(['ListaConstantes.empresas_idempresas' => $id_empresa]);
            $this->set(compact('lista_constantes'));
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $lista_constantes_ent = $this->ListaConstantes->patchEntity($lista_constantes_ent, $this->request->getData());

            //AGrego lo datos falantes
            $lista_constantes_ent->empresas_idempresas = $id_empresa;
            $lista_constantes_ent->users_idusers = $user_id;

            if($this->ListaConstantes->save($lista_constantes_ent)){
                $this->Flash->success(__('La Constante se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $mensaje = $lista_constantes_ent->getError('name')['_isUnique'];
                //debug($lista_constantes_ent->getError('name')['_isUnique']);

                $this->Flash->error(__($mensaje));
            }
        }

        $this->set(compact('lista_constantes_ent'));

    }


    /*public function add()
    {
        $items_const = $this->ListaConstantes->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $items_const = $this->ListaConstantes->patchEntity($items_const, $this->request->getData());

            //AGrego lo datos falantes
            $items_const->empresas_idempresas = $id_empresa;
            $items_const->users_idusers = $user_id;

            if($this->ListaConstantes->save($items_const)){
                $this->Flash->success(__('La Constante se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }
        $this->set(compact('items_const'));
    }*/



    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Constantes';
            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $lotes =  $this->ListaConstantes->get($id);

            if ($this->ListaConstantes->delete($lotes)) {
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
