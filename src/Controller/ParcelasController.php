<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Parcelas Controller
 *
 * @property \App\Model\Table\ParcelasTable $Parcelas
 */
class ParcelasController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive', 'getParcelaByLote'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'showInactive', 'getParcelaByLote'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    public function index()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Parcelas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $parcelas =  $this->Parcelas->find('all', [
                'contain' => ['Users', 'Propietarios', 'Lotes']
            ])->where(['Parcelas.active' => true, 'Propietarios.empresas_idempresas' => $id_empresa]);
            $this->set(compact('parcelas'));

            //'Parcelas.empresas_idempresas' => $id_empresa
            //debug($parcelas);
            //debug($parcelas->toArray());
        }
    }


    public function showInactive()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Parcelas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $parcelas =  $this->Parcelas->find('all', [
                'contain' => ['Users', 'Propietarios', 'Lotes']
            ])->where(['Parcelas.active' => false, 'Propietarios.empresas_idempresas' => $id_empresa]);
            $this->set(compact('parcelas'));

            //'Parcelas.empresas_idempresas' => $id_empresa
            //debug($parcelas);
            //debug($parcelas->toArray());
        }


    }

    public function add()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Parcelas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        $parcelas = $this->Parcelas->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //TRaigo los lotes y los propietarios

        $tablaPropietarios = $this->loadModel('Propietarios');
        $tablaLotes = $this->loadModel('Lotes');

        $propietarios =  $tablaPropietarios->find('all', [
            'contain' => []
        ])->where(['Propietarios.active' => true, 'Propietarios.empresas_idempresas' => $id_empresa]);
        $this->set(compact('propietarios'));

        $lotes =  $tablaLotes->find('all', [
            'contain' => []
        ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
        $this->set(compact('lotes'));


        if ($this->request->is('post')) {

            $parcelas = $this->Parcelas->patchEntity($parcelas, $this->request->getData());
            $parcelas->created = date("Y-m-d");
            $parcelas->users_idusers = $user_id;

            if($this->Parcelas->save($parcelas)){
                $this->Flash->success(__('La Parcela se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }

        }

        /*$propietarios = $tablaPropietarios->find('list', [
            'keyField' => 'idpropietarios',
            'valueField' => ['firstname', 'name'],
            'order' => ['idpropietarios' => 'ASC']
        ])->where(['Propietarios.active' => true, 'Propietarios.empresas_idempresas' => $id_empresa])
            ->toArray();

        $propietarios_new = [];

        foreach ($propietarios as $prop){
            $prop = str_replace ( ';', '', $prop);
            array_push($propietarios_new, $prop);
        }

        $this->set('propietarios', $propietarios_new);*/

        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $parcelas = $this->Parcelas->patchEntity($parcelas, $this->request->getData());

        }

        $this->set(compact('parcelas'));

    }


    public function edit($id = null)
    {

        try{
            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Parcelas';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            //Traigo los datos de la sesion
            $session = $this->request->getSession();
            $user_id = $session->read('Auth.User.idusers');
            $user_role = $session->read('Auth.User.role');
            $id_empresa = $session->read('Auth.User.Empresa.idempresas');


            $parcelas =  $this->Parcelas->get($id, [
                'contain' => ['Propietarios', 'Lotes']
            ]);


            //TRaigo los lotes y los propietarios

            $tablaPropietarios = $this->loadModel('Propietarios');
            $tablaLotes = $this->loadModel('Lotes');

            $propietarios =  $tablaPropietarios->find('all', [
                'contain' => []
            ])->where(['Propietarios.active' => true, 'Propietarios.empresas_idempresas' => $id_empresa]);
            $this->set(compact('propietarios'));

            $lotes =  $tablaLotes->find('all', [
                'contain' => []
            ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
            $this->set(compact('lotes'));



            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $parcelas = $this->Parcelas->patchEntity($parcelas, $this->request->getData());

                if($this->Parcelas->save($parcelas)){
                    $this->Flash->success(__('La Parcela se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }
            }
            $this->set(compact('parcelas'));

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
            $sub_seccion = 'Parcelas';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $parcelas =  $this->Parcelas->get($id);

            if ($this->Parcelas->delete($parcelas)) {
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


    public function getParcelaByLoteOther($lote = null)
    {
        $this->autoRender = false;

        $array_localidad = ['texto' => 'cristian'];


        $array_data = [];

        $parcelas = $this->Parcelas->find('list', [
            'keyField' => 'idparcelas',
            'valueField' => 'name'

        ])->select(['idparcelas' => 'idparcelas','name' => 'name'])
            ->where(['lotes_idlotes' => $lote])
            ->toArray();
        $array_data = $parcelas;

        return $array_data;

    }

    public function getParcelaByLote()
    {
        $this->autoRender = false;

        $array_localidad = ['texto' => 'cristian'];


        $lote = $_POST['lote'];
        $array_data = [];

        if($this->request->is('ajax')) {

            $parcelas = $this->Parcelas->find('all', [

            ])->select(['idparcelas' => 'idparcelas','name' => 'name'])
                ->where(['lotes_idlotes' => $lote])
                ->toArray();
            $array_data = $parcelas;

        }

        return $this->json($array_data);

    }

}
