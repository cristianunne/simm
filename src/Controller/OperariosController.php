<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Operarios Controller
 *
 * @property \App\Model\Table\OperariosTable $Operarios
 */
class OperariosController extends AppController
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
        $sub_seccion = 'Operarios';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $operarios = $this->Operarios->find('all', [
                'contain' => ['Users']
            ])->where(['Operarios.active' => true, 'Operarios.empresas_idempresas' => $id_empresa]);
            $this->set(compact('operarios'));

            //debug($operarios->toArray());
        }



    }

    public function showInactive()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Operarios';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $operarios = $this->Operarios->find('all', [
                'contain' => ['Users']
            ])->where(['Operarios.active' => false, 'Operarios.empresas_idempresas' => $id_empresa]);
            $this->set(compact('operarios'));
        }

    }

    public function add()
    {

        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Operarios';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $operarios =  $this->Operarios->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $operarios = $this->Operarios->patchEntity($operarios, $this->request->getData());


            //AGrego lo datos falantes
            $operarios->created = date("Y-m-d");
            $operarios->empresas_idempresas = $id_empresa;
            $operarios->users_idusers = $user_id;

            if($data['file']['name'] != '') {

                //limito a 2bm la subida de las imagenes
                if (($data['file']['size'] / 1024) > 2048) {
                    //Excedi los 2 MB, informo
                    $this->Flash->error(__('Seleccione una imágen con un tamaño inferior a 2MB'));
                } else {
                    //procedo a trabajar porque cumplio las funciones
                    //Llamo al controlador de archivos

                    $filesManagerController = New FilesManagerController();
                    $result_save = $filesManagerController->uploadFiles($data['file'], LOGOS);

                    if (!$result_save)
                    {
                        $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                    } else {

                        $operarios->logo = $result_save;
                        $operarios->folder = LOGOS_SHORT;

                        //GUardo las actualizaciones del usuario
                        if($this->Operarios->save($operarios)){

                            $this->Flash->success(__('El Operario se ha almacenado correctamente'));
                            //traigo los datos nuevamente y actualizo el current user
                            return $this->redirect(['action' => 'index']);
                        }  else {
                            $this->Flash->error(__('Error al almacenar los cambios en nuestras Bases de Datos. Intenta nuevamente'));
                            //debería eliminar la imagen recien subida
                        }
                    }

                }
            }
            else
            {
                //COmo no vino una imagen, guardo igual
                if($this->Operarios->save($operarios)){

                    $this->Flash->success(__('El Operario se ha almacenado correctamente'));
                    //traigo los datos nuevamente y actualizo el current user

                    return $this->redirect(['action' => 'index']);
                }
                else {
                    $this->Flash->error(__('Error al almacenar los cambios en nuestras Bases de Datos. Intenta nuevamente'));
                    //debería eliminar la imagen recien subida
                }
            }

        }

        $this->set(compact('operarios'));
    }


    public function edit($id = null)
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Operarios';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $operarios = $this->Operarios->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();
            $operarios = $this->Operarios->patchEntity($operarios, $this->request->getData());

            if($data['file']['name'] != '') {
                //limito a 2bm la subida de las imagenes
                if (($data['file']['size'] / 1024) > 2048) {
                    //Excedi los 2 MB, informo
                    $this->Flash->error(__('Seleccione una imágen con un tamaño inferior a 2MB'));
                } else {

                    //procedo a trabajar porque cumplio las funciones
                    //Llamo al controlador de archivos
                    $filesManagerController = New FilesManagerController();

                    if($operarios->logo != '')
                    {
                        //ELimino el archivo
                        if($filesManagerController->deleteFile($operarios->logo, LOGOS)) {
                            //La vuelvo a subir

                            $result_save = $filesManagerController->uploadFiles($data['file'], LOGOS);

                            if (!$result_save)
                            {
                                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                            } else {
                                $operarios->logo = $result_save;
                                $operarios->folder = LOGOS_SHORT;

                                //GUardo las actualizaciones del usuario
                                if($this->Operarios->save($operarios)){

                                    $this->Flash->success(__('Los cambios se hans almacenado correctamente'));
                                    //traigo los datos nuevamente y actualizo el current user

                                    return $this->redirect(['action' => 'index']);
                                }
                                else {
                                    $this->Flash->error(__('Error al almacenar los cambios en nuestras Bases de Datos. Intenta nuevamente'));
                                    //debería eliminar la imagen recien subida
                                }
                            }
                        } else {
                            $this->Flash->error(__('El Propietario no pudo ser actualizada. Intente nuevamente.'));
                        }
                    }
                    else {
                        $result_save = $filesManagerController->uploadFiles($data['file'], LOGOS);
                        if (!$result_save)
                        {
                            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                        } else {

                            $operarios->logo = $result_save;
                            $operarios->folder = LOGOS_SHORT;

                            //GUardo las actualizaciones del usuario
                            if($this->Operarios->save($operarios)){

                                $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                                //traigo los datos nuevamente y actualizo el current user

                                return $this->redirect(['action' => 'index']);
                            } else {
                                $this->Flash->error(__('Error al almacenar los cambios en nuestras Bases de Datos. Intenta nuevamente'));
                                //debería eliminar la imagen recien subida
                            }
                        }

                    }

                }
            }
            else {
                //COmo no vino una imagen, guardo igual
                if($this->Operarios->save($operarios)){

                    $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                    //traigo los datos nuevamente y actualizo el current user

                    return $this->redirect(['action' => 'index']);
                }
                else {
                    $this->Flash->error(__('Error al almacenar los cambios en nuestras Bases de Datos. Intenta nuevamente'));
                }
            }
        }
        $this->set(compact('operarios'));

    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        try{

            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'Operarios';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $operarios =  $this->Operarios->get($id);

            //Primero elimino la imagen si es que tiene
            $var_aux = false;
            if($operarios->logo != '')
            {
                $filesManager = new FilesManagerController();
                //ELimino el archivo
                if($filesManager->deleteFile($operarios->logo, LOGOS)){
                    $var_aux = true;
                }
            } else {
                $var_aux = true;
            }
            if(!$var_aux){
                $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
            } else {
                if ($this->Operarios->delete($operarios)) {
                    $this->Flash->success(__('El Registro ha sido eliminado.'));

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
                }
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
