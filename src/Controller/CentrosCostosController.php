<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * CentrosCostos Controller
 *
 * @property \App\Model\Table\CentrosCostosTable $CentrosCostos
 */
class CentrosCostosController extends AppController
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
        $sub_seccion = 'Centros_costos';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));

        } else {
            $centros_costos =  $this->CentrosCostos->find('all', [
                'contain' => 'Users'
            ])->where(['CentrosCostos.active' => true, 'CentrosCostos.empresas_idempresas' => $id_empresa]);
            $this->set(compact('centros_costos'));
        }

    }

    public function add()
    {
        $centros_costos =  $this->CentrosCostos->newEntity();

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Centros_costos';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //CAtegorias
        $categorias = ['Elaboracion' => 'Elaboracion', 'Transporte' => 'Transporte'];




        if ($this->request->is('post')) {

            $centros_costos = $this->CentrosCostos->patchEntity($centros_costos, $this->request->getData());
            //AGrego lo datos falantes
            $centros_costos->created = date("Y-m-d");
            $centros_costos->empresas_idempresas = $id_empresa;
            $centros_costos->users_idusers = $user_id;

            if($this->CentrosCostos->save($centros_costos)){
                $this->Flash->success(__('El Centro de Costo se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }

        $this->set(compact('categorias'));
        $this->set(compact('centros_costos'));
    }



    public function edit($id = null)
    {

        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Centros_costos';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $centros_costos =  $this->CentrosCostos->get($id);

            //CAtegorias
            $categorias = ['Elaboracion' => 'Elaboracion', 'Transporte' => 'Transporte'];
            $this->set(compact('categorias'));


            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $centros_costos = $this->CentrosCostos->patchEntity($centros_costos, $this->request->getData());

                if($this->CentrosCostos->save($centros_costos)){
                    $this->Flash->success(__('EL Centro de Costos se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }
            }
            $this->set(compact('centros_costos'));
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
            $centros_costos =  $this->CentrosCostos->get($id);

            if ($this->CentrosCostos->delete($centros_costos)) {
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
