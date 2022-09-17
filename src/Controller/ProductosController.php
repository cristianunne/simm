<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Productos Controller
 *
 * @property \App\Model\Table\ProductosTable $Productos
 */
class ProductosController extends AppController
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

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Productos';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $productos = $this->Productos->find('all', [
                'contain' => ['Users']
            ])->where(['Productos.active' => true, 'Productos.empresas_idempresas' => $id_empresa]);
            $this->set(compact('productos'));

            //debug($productos->toArray());
        }

    }

    public function showInactive()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Productos';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $productos = $this->Productos->find('all', [
                'contain' => ['Users']
            ])->where(['Productos.active' => false, 'Productos.empresas_idempresas' => $id_empresa]);
            $this->set(compact('productos'));

            //debug($productos->toArray());
        }

    }


    public function add()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Productos';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        $productos = $this->Productos->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $productos = $this->Productos->patchEntity($productos, $this->request->getData());

            //AGrego lo datos falantes
            $productos->created = date("Y-m-d");
            $productos->empresas_idempresas = $id_empresa;
            $productos->users_idusers = $user_id;

            if($this->Productos->save($productos)){
                $this->Flash->success(__('El Producto se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }
        $this->set(compact('productos'));
    }

    public function edit($id = null)
    {

        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Productos';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $productos =  $this->Productos->get($id);


            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $productos = $this->Productos->patchEntity($productos, $this->request->getData());

                if($this->Productos->save($productos)){
                    $this->Flash->success(__('El Producto se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }
            }
            $this->set(compact('productos'));
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

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Productos';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $productos =  $this->Productos->get($id);

            if ($this->Productos->delete($productos)) {
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
