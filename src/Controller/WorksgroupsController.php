<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Worksgroups Controller
 *
 * @property \App\Model\Table\WorksgroupsTable $Worksgroups
 */
class WorksgroupsController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive'])) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

    public function index()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Worksgroups';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));

        } else {
            $worksgroup =  $this->Worksgroups->find('all', [
                'contain' => 'Users'
            ])->where(['Worksgroups.active' => true, 'Worksgroups.empresas_idempresas' => $id_empresa]);
            $this->set(compact('worksgroup'));
        }

    }

    public function showInactive()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Worksgroups';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));

        } else {
            $worksgroup =  $this->Worksgroups->find('all', [
                'contain' => 'Users'
            ])->where(['Worksgroups.active' => false, 'Worksgroups.empresas_idempresas' => $id_empresa]);
            $this->set(compact('worksgroup'));
        }


    }

    public function add()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Worksgroups';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $worksgroup =  $this->Worksgroups->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if ($this->request->is('post')) {

            $worksgroup = $this->Worksgroups->patchEntity($worksgroup, $this->request->getData());
            //AGrego lo datos falantes
            $worksgroup->created = date("Y-m-d");
            $worksgroup->empresas_idempresas = $id_empresa;
            $worksgroup->users_idusers = $user_id;
            $worksgroup->hash_id = hash('sha256' , ($worksgroup->name . date("Y-m-d")));

            if($this->Worksgroups->save($worksgroup)){
                $this->Flash->success(__('El Grupo de Trabajo se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }

        $this->set(compact('worksgroup'));
    }

    public function edit($id = null)
    {
        try{
            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Worksgroups';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));
            $worksgroup =  $this->Worksgroups->get($id);
            if ($this->request->is(['patch', 'post', 'put'])) {

                $data = $this->request->getData();
                $worksgroup = $this->Worksgroups->patchEntity($worksgroup, $this->request->getData());

                if($this->Worksgroups->save($worksgroup)){
                    $this->Flash->success(__('EL Grupo de Trabajo se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }

            }
            $this->set(compact('worksgroup'));
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
