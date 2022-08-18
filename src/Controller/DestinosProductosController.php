<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Exception;

/**
 * DestinosProductos Controller
 *
 * @property \App\Model\Table\DestinosProductosTable $DestinosProductos
 */
class DestinosProductosController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'addByDestino', 'viewPricesByDestino', 'viewHistory',
                'updatePrice'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'addByDestino', 'viewPricesByDestino', 'viewHistory',
                'updatePrice'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }


    public function index()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'DestinosProductos';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $destinos_model = $this->loadModel('Destinos');

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));

        } else {
            $destinos =  $destinos_model->find('all', [
                'contain' => 'Users'
            ])->where(['Destinos.active' => true, 'Destinos.empresas_idempresas' => $id_empresa]);
            $this->set(compact('destinos'));
        }
    }



    public function addByDestino($id_destino = null)
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'DestinosProductos';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $destino_producto = $this->DestinosProductos->newEntity();
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        //TRaigo los productos filtrador por empresa
        $model_productos = $this->loadModel('Productos');
        $model_destinos = $this->loadModel('Destinos');

        $productos_cargados = $this->DestinosProductos->find()
            ->select(['productos_idproductos'])
            ->distinct(['productos_idproductos'])
            ->where(['destinos_iddestinos' => $id_destino, 'active' => true]);


        $lista_productos =  $model_productos->find('list', [
            'keyField' => 'idproductos',
            'valueField' => 'name',
            'order' => ['name' => 'ASC']
        ])->where(['idproductos NOT IN' => $productos_cargados])
            ->toArray();

        $this->set(compact('lista_productos'));

        try{

            $destino = $model_destinos->get($id_destino);
            $this->set(compact('destino'));


            if ($this->request->is('post')) {
                $data = $this->request->getData();
                $destino_producto = $this->DestinosProductos->patchEntity($destino_producto, $this->request->getData());

                $destino_producto->created = date("Y-m-d");
                $destino_producto->destinos_iddestinos = $id_destino;


                if($this->DestinosProductos->save($destino_producto)){
                    $this->Flash->success(__('El Precio se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }
            }

        }
        catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }




        $this->set(compact('destino_producto'));

    }


    public function viewPricesByDestino($id_destino = null)
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'DestinosProductos';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));

        } else {
            $destinos_productos =  $this->DestinosProductos->find('all', [
                'contain' => ['Destinos', 'Productos']
            ])->where(['DestinosProductos.active' => true, 'DestinosProductos.destinos_iddestinos' => $id_destino]);
            $this->set(compact('destinos_productos'));
        }


    }

    public function updatePrice($id = null)
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'DestinosProductos';
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

            $destino_productos =  $this->DestinosProductos->get($id, [
                'contain' => ['Destinos', 'Productos']
            ]);

            if ($this->request->is(['patch', 'post', 'put'])) {
                //Comparo las dos variables para saber si hubo cambios
                $data = $this->request->getData();

                //Consulto si hay cambios en los datos
                if ($this->compareData($data, $destino_productos->toArray()))
                {

                    //si hay cambios creo un nuevo registro y seteo a false el anterior

                    //al actual lo seteo como inactivo
                    $destino_productos->active = 0;
                    $destino_productos->finished = date("Y-m-d");

                    if($this->DestinosProductos->save($destino_productos)){

                        //setee a false el estado, entonces creo uno nuevo
                        $destino_productos_new = $this->DestinosProductos->newEntity();

                        $destino_productos_new = $this->DestinosProductos->patchEntity($destino_productos_new, $data);
                        $destino_productos_new->destinos_iddestinos = $destino_productos->destinos_iddestinos;
                        $destino_productos_new->productos_idproductos = $destino_productos->productos_idproductos;

                        //AGrego lo datos falantes
                        $destino_productos_new->created = date("Y-m-d");

                        if($this->DestinosProductos->save($destino_productos_new)){
                            $this->Flash->success(__('El Precio se ha actualizado correctamente'));
                            return $this->redirect(['action' => 'viewPricesByDestino', $destino_productos->destinos_iddestinos]);
                        } else {

                            //debug($destino_productos_new->toArray());
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

            //debug($destino_productos->toArray());
            $this->set(compact('destino_productos'));

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

        if($array_data['precio'] != $array_entity['precio'])
        {

            return true;

        }

        return false;

    }


    public function viewHistory($id_destino = null, $id_producto = null)
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'DestinosProductos';
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
            $destinos_productos =  $this->DestinosProductos->find('all', [
                'contain' => ['Destinos', 'Productos']
            ])->where(['DestinosProductos.destinos_iddestinos' => $id_destino,
                'DestinosProductos.productos_idproductos' => $id_producto]);
            $this->set(compact('destinos_productos'));

            //debug($destinos->toArray());

        }
    }


    public function delete($id = null, $id_destino = null, $id_producto = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'DestinosProductos';
            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));
            $destinos_prod =  $this->DestinosProductos->get($id);

            //debo consultar si esta constante se usa en otro lugar antes de eliminar

            if ($this->DestinosProductos->delete($destinos_prod)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'viewHistory', $id_destino, $id_producto]);
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
