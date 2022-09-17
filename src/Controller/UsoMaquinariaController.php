<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * UsoMaquinaria Controller
 *
 * @property \App\Model\Table\UsoMaquinariaTable $UsoMaquinaria
 */
class UsoMaquinariaController extends AppController
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
        $seccion = 'uso_maquinaria';
        $sub_seccion = 'Inicio';

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

            $uso_maquinas = $this->UsoMaquinaria->find('all', [
                'contain' => ['Users', 'Maquinas', 'Parcelas' => 'Lotes']
            ])->where(['UsoMaquinaria.empresas_idempresas' => $id_empresa]);

        }

        $this->set(compact('uso_maquinas'));




    }

    public function add()
    {

        //Variable usada para el sidebar
        $seccion = 'uso_maquinaria';
        $sub_seccion = '';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $uso_maquina = $this->UsoMaquinaria->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }
        //Traigo las maquinas
        $maquina_model = $this->loadModel('Maquinas');

        $maquinas_data = $maquina_model->find('list',[
            'keyField' => 'idmaquinas',
            'valueField' => ['marca', 'name'],
        ])
        ->where(['empresas_idempresas' => $id_empresa, 'active' => true])
            ->toArray();


        $this->set(compact('maquinas_data'));

        $tablaLotes = $this->loadModel('Lotes');

        $lotes =  $tablaLotes->find('all', [
            'contain' => []
        ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
        $this->set(compact('lotes'));

        $this->set(compact('lotes'));


        if ($this->request->is('post')) {

            $uso_maquina = $this->UsoMaquinaria->patchEntity($uso_maquina, $this->request->getData());
            $uso_maquina->empresas_idempresas = $id_empresa;
            $uso_maquina->users_idusers = $user_id;

            if ($this->UsoMaquinaria->save($uso_maquina)) {
                $this->Flash->success(__('La Máquina se ha almacenado correctamente'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }

        $this->set(compact('uso_maquina'));

    }


    public function edit($id = null)
    {
        //Variable usada para el sidebar
        $seccion = 'uso_maquinaria';
        $sub_seccion = '';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        //Proceso usando el try catch

        $uso_maquina = null;
        $parcela_data = null;

        if(is_null($id))
        {
            return $this->redirect(['controller' => 'UsoMaquinaria', 'action' => 'index']);
        } else {

            //Ahora proceso el EDit

            try{

                $uso_maquina =  $this->UsoMaquinaria->get($id, [
                    'contain' => ['Users', 'Maquinas', 'Parcelas' => ['Lotes']]
                ]);

                //TRaigo la lista de maquinas

                $maquina_model = $this->loadModel('Maquinas');

                $maquinas_data = $maquina_model->find('list',[
                    'keyField' => 'idmaquinas',
                    'valueField' => ['marca', 'name'],
                ])
                    ->where(['empresas_idempresas' => $id_empresa, 'active' => true])
                    ->toArray();




                //TRaigo los lotes

                $tablaLotes = $this->loadModel('Lotes');

                $lotes =  $tablaLotes->find('all', [
                    'contain' => []
                ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
                $this->set(compact('lotes'));

                $this->set(compact('lotes'));

                if($uso_maquina->parcela != null){

                    $parcela_entity = new ParcelasController();
                    $parcela_data = $parcela_entity->getParcelaByLoteOther($uso_maquina->parcela->lote->idlotes);
                }

                //debug($this->request->getData());


                if ($this->request->is(['patch', 'post', 'put'])) {


                    $uso_maq = $this->UsoMaquinaria->patchEntity($uso_maquina, $this->request->getData());
                    //$uso_maq->users_idusers = $user_id;

                    if($this->UsoMaquinaria->save($uso_maq)){
                        $this->Flash->success(__('El Registro se ha almacenado correctamente'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                    }

                }

            } catch (InvalidPrimaryKeyException $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

            } catch (RecordNotFoundException $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            }
            catch (Exception $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            }

        }
        $this->set(compact('uso_maquina'));
        $this->set(compact('maquinas_data'));
        $this->set(compact('parcela_data'));

    }

    public function delete($id = null)
    {

        $this->request->allowMethod(['post', 'delete']);

        $this->autoRender = false;

        try{

            //Variable usada para el sidebar
            $seccion = 'uso_maquinaria';
            $sub_seccion = '';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $uso_maquinas =  $this->UsoMaquinaria->get($id);

            if ($this->UsoMaquinaria->delete($uso_maquinas)) {
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
