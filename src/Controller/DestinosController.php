<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Exception;

/**
 * Destinos Controller
 *
 * @property \App\Model\Table\DestinosTable $Destinos
 */
class DestinosController extends AppController
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
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));

        } else {
            $destinos =  $this->Destinos->find('all', [
                'contain' => 'Users'
            ])->where(['Destinos.active' => true, 'Destinos.empresas_idempresas' => $id_empresa]);
            $this->set(compact('destinos'));
        }

    }

    public function add()
    {
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $destinos = $this->Destinos->newEntity();

        //debo comprobar que las variables no esten vacias
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if(empty($user_id) or empty($id_empresa)){
                $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            } else {

                $destinos = $this->Destinos->patchEntity($destinos, $this->request->getData());
                //AGrego lo datos falantes
                $destinos->created = date("Y-m-d");
                $destinos->empresas_idempresas = $id_empresa;
                $destinos->users_idusers = $user_id;

                if($data['file']['name'] != '')
                {
                    //limito a 2bm la subida de las imagenes
                    if(($data['file']['size'] / 1024) > 2048)
                    {
                        //Excedi los 2 MB, informo
                        $this->Flash->error(__('Seleccione una imágen con un tamaño inferior a 2MB'));
                    } else {

                        //procedo a trabajar porque cumplio las funciones
                        //Llamo al controlador de archivos
                        $filesManagerController = New FilesManagerController();
                        $result_save = $filesManagerController->uploadFiles($data['file'], OTROS);
                        if (!$result_save)
                        {
                            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                        } else {
                            $destinos->logo = $result_save;
                            $destinos->folder = OTROS_SHORT;
                            //GUardo las actualizaciones del usuario
                            if($this->Destinos->save($destinos)){
                                $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                                //traigo los datos nuevamente y actualizo el current user

                                return $this->redirect(['controller' => 'Destinos' , 'action' => 'index']);
                            } else {
                                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                            }
                        }
                    }
                } else {

                    if($this->Destinos->save($destinos)){
                        $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                        //traigo los datos nuevamente y actualizo el current user

                        return $this->redirect(['controller' => 'Destinos' , 'action' => 'index']);
                    } else {
                        $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                    }
                }
            }
        }

        $this->set(compact('destinos'));
    }


    public function edit($id = null)
    {
        try{

            $destinos = $this->Destinos->get($id);

            if ($this->request->is(['patch', 'post', 'put'])) {

                $data = $this->request->getData();
                $destinos = $this->Destinos->patchEntity($destinos, $this->request->getData());

                $filesManager = new FilesManagerController();

                $result = $filesManager->updateFilesWithEdit($data, OTROS, OTROS_SHORT, $this->Destinos, $destinos);

                if($result){
                    $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                    return $this->redirect(['action' => 'index']);
                }


            }


            $this->set(compact('destinos'));
        }catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (Exception $e){

            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }

    }


    public function delete($id = null)
    {

        $this->request->allowMethod(['post', 'delete']);
        $destino = $this->Destinos->get($id);

        try{

            //Primero elimino la imagen si es que tiene
            $var_aux = false;
            if($destino->logo != '')
            {
                $filesManager = new FilesManagerController();
                //ELimino el archivo
                if($filesManager->deleteFile($destino->logo, OTROS)){
                    $var_aux = true;
                }

            } else {
                $var_aux = true;
            }
            if(!$var_aux){
                $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
            } else {
                if ($this->Destinos->delete($destino)) {
                    $this->Flash->success(__('El Registro ha sido eliminado.'));

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
                }
            }
        }catch(\PDOException $e){
            $this->Flash->error(__($e->getMessage()));
            $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
        }

    }

}
