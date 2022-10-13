<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Query;

/**
 * OperariosMaquinas Controller
 *
 * @property \App\Model\Table\OperariosMaquinasTable $OperariosMaquinas
 */
class OperariosMaquinasController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'updateSalary', 'delete', 'viewHistory', 'viewAll'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'updateSalary', 'delete', 'viewHistory', 'viewAll'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }


    public function index()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'OperariosMaquinas';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $operarios_maquinas = $this->OperariosMaquinas->find('all', [
                'contain' => ['Operarios', 'Maquinas']
            ])->where(['OperariosMaquinas.active' => true, 'Operarios.empresas_idempresas' => $id_empresa]);
            $this->set(compact('operarios_maquinas'));

            //debug($operarios->toArray());
        }

    }

    public function add($id = null)
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'OperariosMaquinas';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        if(empty($id))
        {
            return $this->redirect(['action' => 'index']);
        }

        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');
        $operarios_maq = $this->OperariosMaquinas->newEntity();

        try{

            //Traigo la Lista de Contantes
            $model_maquinas =  $this->loadModel('Maquinas');

            //TRaigo los oeprarios_maquinas
            $operarios_maq_query = $this->OperariosMaquinas->find()
                ->select(['maquinas_idmaquinas'])
                ->where(['operarios_idoperarios' => $id]);


            //Debo filtrar por empresa

            $lista_maquinas =  $model_maquinas->find('list', [
                'keyField' => 'idmaquinas',
                'valueField' => ['marca', 'name'],
                'order' => ['name' => 'ASC']
            ])->where(['idmaquinas NOT IN' => $operarios_maq_query, 'empresas_idempresas' => $id_empresa])
                ->toArray();



            $model_operario =  $this->loadModel('Operarios');
            $operario =  $model_operario->get($id);
            $operario = $operario->lastname . ' ' . $operario->firstname;
            $this->set(compact('operario'));


            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $operarios_maq = $this->OperariosMaquinas->patchEntity($operarios_maq, $this->request->getData());

                $operarios_maq->operarios_idoperarios = $id;
                //debug($operarios_maq);

                if($this->OperariosMaquinas->save($operarios_maq)){
                    $this->Flash->success(__('El Salario se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }
            }
            //debo verificar que no este ya usada

            $this->set(compact('lista_maquinas'));
            $this->set(compact('operarios_maq'));
            $this->set(compact('id'));

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }


    }


    public function updateSalary($id_operario = null, $operarios_idop = null, $id_maq = null)
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'OperariosMaquinas';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        if(empty($id_operario) or empty($id_maq) or empty($operarios_idop))
        {
            return $this->redirect(['action' => 'index']);
        }


        try{
            //Traigo los datos de la sesion
            $session = $this->request->getSession();
            $user_id = $session->read('Auth.User.idusers');
            $user_role = $session->read('Auth.User.role');
            $id_empresa = $session->read('Auth.User.Empresa.idempresas');

            $operarios_maq =  $this->OperariosMaquinas->get($id_operario, [
                'contain' => ['Operarios', 'Maquinas']
            ]);

            //debug($operarios_maq->toArray());

            if ($this->request->is(['patch', 'post', 'put'])) {
                //Comparo las dos variables para saber si hubo cambios
                $data = $this->request->getData();


                //Consulto si hay cambios en los datos
                if ($this->compareData($data, $operarios_maq->toArray()))
                {

                    //si hay cambios creo un nuevo registro y seteo a false el anterior

                    //al actual lo seteo como inactivo
                    $operarios_maq->active = 0;
                    $operarios_maq->finished = date("Y-m-d");


                    if($this->OperariosMaquinas->save($operarios_maq)){

                        //setee a false el estado, entonces creo uno nuevo
                        $operarios_maq_new = $this->OperariosMaquinas->newEntity();

                        $operarios_maq_new = $this->OperariosMaquinas->patchEntity($operarios_maq_new, $data);
                        $operarios_maq_new->operarios_idoperarios = $operarios_idop;
                        $operarios_maq_new->maquinas_idmaquinas = $id_maq;

                        //AGrego lo datos falantes
                        $operarios_maq_new->created = date("Y-m-d");

                        if($this->OperariosMaquinas->save($operarios_maq_new)){
                            $this->Flash->success(__('El Salario se ha actualizado correctamente'));
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
            $this->set(compact('operarios_maq'));

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }
    }


    public function viewHistory($id_operario = null, $id_maquina = null)
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'OperariosMaquinas';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        if(empty($id_operario) or empty($id_maquina))
        {
            return $this->redirect(['action' => 'index']);
        }

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {


            $operarios_maquinas =  $this->OperariosMaquinas->find('all', [
                'contain' => ['Operarios', 'Maquinas']
            ])->where(['operarios_idoperarios' => $id_operario, 'maquinas_idmaquinas' => $id_maquina])
                ->order(['OperariosMaquinas.created DESC']);
            $this->set(compact('operarios_maquinas'));

        }
    }

    public function viewAll()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'OperariosMaquinas';
        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $operarios_maquinas =  $this->OperariosMaquinas->find('all', [
                'contain' => ['Operarios', 'Maquinas']
            ])->where(['Operarios.empresas_idempresas' => $id_empresa])
                ->order(['Operarios.lastname ASC']);
            $this->set(compact('operarios_maquinas'));
        }
    }


    private function compareData($array_data, $array_entity)
    {

        if($array_data['sueldo'] != $array_entity['sueldo'])
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
            $sub_seccion = 'OperariosMaquinas';
            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $operarios_maq =  $this->OperariosMaquinas->get($id);

            //debo consultar si esta constante se usa en otro lugar antes de eliminar

            if ($this->OperariosMaquinas->delete($operarios_maq)) {
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
