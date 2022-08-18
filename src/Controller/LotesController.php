<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use PDOException;

/**
 * Lotes Controller
 *
 * @property \App\Model\Table\LotesTable $Lotes
 */
class LotesController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive', 'view'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive', 'view'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }



    public function index()
    {
        $seccion = 'system';
        $sub_seccion = 'Lotes';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $lotes =  $this->Lotes->find('all', [
                'contain' => 'Users'
            ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
            $this->set(compact('lotes'));
        }
    }

    public function showInactive()
    {
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $lotes =  $this->Lotes->find('all', [
                'contain' => 'Users'
            ])->where(['Lotes.active' => false, 'Lotes.empresas_idempresas' => $id_empresa]);
            $this->set(compact('lotes'));
        }
    }


    public function view($id = null)
    {
        try{
            $seccion = 'system';
            $sub_seccion = 'Lotes';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $lotes =  $this->Lotes->get($id);
            $this->set(compact('lotes'));

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }
    }


    public function add()
    {
        $seccion = 'system';
        $sub_seccion = 'Lotes';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $lotes =  $this->Lotes->newEntity();

        $prov_dptos_controller = new ProvDeptosController();
        $provincias = $prov_dptos_controller->getProvincias();
        $this->set(compact('provincias'));


        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if ($this->request->is('post')) {

            $lotes = $this->Lotes->patchEntity($lotes, $this->request->getData());
            //AGrego lo datos falantes
            $lotes->created = date("Y-m-d");
            $lotes->empresas_idempresas = $id_empresa;
            $lotes->users_idusers = $user_id;

            if($this->Lotes->save($lotes)){
                $this->Flash->success(__('El Lote se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }
        $this->set(compact('lotes'));
    }

    public function edit($id = null)
    {
        try{
            $seccion = 'system';
            $sub_seccion = 'Lotes';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $lotes =  $this->Lotes->get($id);

            $prov_dptos_controller = new ProvDeptosController();
            $provincias = $prov_dptos_controller->getProvincias();
            $this->set(compact('provincias'));

            $dptos = $prov_dptos_controller->getDepartamentos2($lotes->provincia);
            $this->set(compact('dptos'));

            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $lotes = $this->Lotes->patchEntity($lotes, $this->request->getData());
                $lotes->active = $data['active'];


                if($this->Lotes->save($lotes)){
                    $this->Flash->success(__('El Lote se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }
            }
            $this->set(compact('lotes'));
        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }

    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try{

            $seccion = 'system';
            $sub_seccion = 'Lotes';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $lotes =  $this->Lotes->get($id);

            if ($this->Lotes->delete($lotes)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
            }

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);
        }
        catch (PDOException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
            return $this->redirect(['action' => 'index']);
        }

    }

}
