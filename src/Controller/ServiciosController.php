<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Servicios Controller
 *
 * @property \App\Model\Table\ServiciosTableCopy $Servicios
 */
class ServiciosController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'edit', 'delete'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'edit', 'delete'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }


    public function index()
    {

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Destinos';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));

        } else {
            $servicio =  $this->Servicios->find('all', [
                'contain' => ['Users']
            ])->where(['Servicios.empresas_idempresas' => $id_empresa]);
            $this->set(compact('servicio'));


        }

    }

    public function add()
    {
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Destinos';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        $servicios = $this->Servicios->newEntity();

        //Traigo la lista de centro de costo
        $categorias = ['Elaboracion' => 'Elaboracion', 'Transporte' => 'Transporte'];

        $this->set(compact('categorias'));



        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if(empty($id_empresa)){
                $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));

            } else {

                //Consulto si por el mes y el tipo de servicio ya esta cargado
                $data = $this->request->getData();

                if ($this->evaluateDataExist($data))
                {
                    $this->Flash->error(__('El Servicio ya fue cargado para la Fecha y Categoria seleccionada.'));
                } else {
                    $servicios = $this->Servicios->patchEntity($servicios, $this->request->getData());
                    //AGrego lo datos falantes
                    $servicios->created = date("Y-m-d");
                    $servicios->empresas_idempresas = $id_empresa;
                    $servicios->users_idusers = $user_id;

                    if($this->Servicios->save($servicios)){
                        $this->Flash->success(__('El servicio se ha almacenado correctamente'));
                        //traigo los datos nuevamente y actualizo el current user

                        return $this->redirect(['controller' => 'Servicios' , 'action' => 'index']);
                    } else {
                        $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                    }
                }

            }
        }
        $this->set(compact('servicios'));
    }


    private function evaluateDataExist($data)
    {

        $fecha = $data['fecha'];
        $categoria = $data['categoria'];

        $time = strtotime($fecha);

        $mes = date('m',$time);
        $year = date('Y',$time);

        $conditions['MONTH(fecha) ='] = $mes;
        $conditions['YEAR(fecha) ='] = $year;

        $conditions['categoria LIKE'] = $categoria;



        $result = $this->Servicios->find('all', [])
            ->where($conditions);

        if(count($result->toArray()) > 0){
            return true;
        } else {
            return false;
        }


    }


    public function edit($id = null)
    {
        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Destinos';

            $session = $this->request->getSession();
            $user_id = $session->read('Auth.User.idusers');
            $user_role = $session->read('Auth.User.role');
            $id_empresa = $session->read('Auth.User.Empresa.idempresas');

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $servicios = $this->Servicios->get($id);

            //Traigo la lista de centro de costo
            //Traigo la lista de centro de costo
            $categorias = ['Elaboracion' => 'Elaboracion', 'Transporte' => 'Transporte'];

            $this->set(compact('categorias'));

            if ($this->request->is(['patch', 'post', 'put'])) {

                $data = $this->request->getData();
                $servicios = $this->Servicios->patchEntity($servicios, $this->request->getData());

                if($this->Servicios->save($servicios)){
                    $this->Flash->success(__('El Servicio se ha almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                }

            }


            $this->set(compact('servicios'));
        }catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (Exception $e){

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

            $servicios =  $this->Servicios->get($id);

            if ($this->Servicios->delete($servicios)) {
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

}
