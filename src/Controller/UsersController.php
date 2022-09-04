<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\InvalidCsrfTokenException;


require(CONFIG . DS. 'constantes.php');

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function beforeFilter(Event  $event)
    {
        $this->Auth->allow(['register', 'login', 'logout', 'adminRegister']);
    }


    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['editProfile'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['editProfile'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }


    public function login()
    {
        if($this->request->is('post'))
        {
            //vALIDA LOS DATOS DEL USUARIO
            $user = $this->Auth->identify();

            if($user)
            {
                //Si estuvo ok setea al usuario en la session
                $this->Auth->setUser($user);
                return $this->redirect(['controller' => 'Pages', 'action' => 'index']);

            } else {
                //Si no inicio sessión mando el error solo se lana con la autentificacion
                $this->Flash->error(__('Datos ingresados no válidos'));
            }

        }
    }


    public function adminRegister()
    {

        $user = $this->Users->newEntity();

        //consulto si existe algun registro, si existe lo hago Admin
        $query = $this->Users->find();
        $query->select(['count' => $query->func()->count('*')]);
        $res_count_users = $query->toArray();
        $res_count = $res_count_users[0]['count'];

        //SI esl valor es superior a 0, entonces redrigo a LOgin comun

        if ($res_count > 0){
            return $this->redirect(['action' => 'login']);
        } else {
            if ($this->request->is('post')) {

                $user = $this->Users->patchEntity($user, $this->request->getData());
                $user->active = false;
                if ($res_count == 0){
                    $user->active = true;
                    $user->role = ADMIN;
                }

                if ($this->Users->save($user)) {

                    $this->Flash->success(__('El usuario ha sido almacenado correctamente'));

                    return $this->redirect(['action' => 'login']);
                }
                $this->Flash->error(__('Error al almacenar el usuario'));

                debug($user->getErrors());
            }
        }

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);

    }


    public function register()
    {
        $user = $this->Users->newEntity();

        if ($this->request->is('post')) {

            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {

                $this->Flash->success(__('El usuario ha sido almacenado correctamente'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('Error al almacenar el usuario'));

        }

    }

    public function editProfile($id = null)
    {
        $session = $this->request->getSession();

        //Recupero los datos de la URL
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('current_user', $user);


        if ($this->request->is(['patch', 'post', 'put'])) {


            $data = $this->request->getData();

            //Primero compruebo que file este definido y no este vacio
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

                    //Si la base tiene una imagen la elimino y luego la actualizo
                    $delete_result = false;
                    //tenia foto entonces la elimino
                    if($user->photo != ''){

                        $delete_result = $filesManagerController->deleteFile($user->photo, PROFILE);

                        if(!$delete_result)
                        {
                            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                            //hago el redirect
                            //Consulto si es ADMIN u otro y redirecciono
                            if($user->role == 'admin'){

                                return $this->redirect(['controller' => 'Pages' , 'action' => 'indexAdmin']);
                            } else {
                                //Redirecciono los usuarios supervisor y users
                                return $this->redirect(['controller' => 'Pages' , 'action' => 'index']);
                            }
                        } else {
                            //Proceso dado que no hubo problemas
                            //SI paso todo el proceso
                            $result_save = $filesManagerController->uploadFiles($data['file'], PROFILE);
                            if (!$result_save)
                            {
                                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                            } else {

                                $user = $this->Users->patchEntity($user, $this->request->getData());
                                $user->photo = $result_save;
                                $user->folder = PROFILE_SHORT;

                                //GUardo las actualizaciones del usuario
                                if($this->Users->save($user)){

                                    $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                                    //traigo los datos nuevamente y actualizo el current user
                                    //Consulto si es ADMIN u otro y redirecciono
                                    if($user->role == 'admin'){
                                        return $this->redirect(['controller' => 'Pages' , 'action' => 'indexAdmin']);
                                    } else {
                                        return $this->redirect(['controller' => 'Pages' , 'action' => 'index']);

                                    }
                                }
                            }
                        }
                    } else {
                        //SI no hay imagen en la base de datos, proceso
                        //Proceso dado que no hubo problemas
                        //SI paso todo el proceso
                        $result_save = $filesManagerController->uploadFiles($data['file'], PROFILE);
                        if (!$result_save)
                        {
                            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                        } else {

                            $user = $this->Users->patchEntity($user, $this->request->getData());
                            $user->photo = $result_save;
                            $user->folder = PROFILE_SHORT;

                            //GUardo las actualizaciones del usuario
                            if($this->Users->save($user)){

                                $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                                //traigo los datos nuevamente y actualizo el current user

                                //Consulto si es ADMIN u otro y redirecciono
                                if($user->role == 'admin'){
                                    return $this->redirect(['controller' => 'Pages' , 'action' => 'indexAdmin']);
                                } else {
                                    return $this->redirect(['controller' => 'Pages' , 'action' => 'index']);
                                }
                            }

                        }

                    } //else de consulta de imagen vacia en la DB

                }
            } else {
                //NO subio imagen, pero debo modificr igual
                $user = $this->Users->patchEntity($user, $this->request->getData());


                //GUardo las actualizaciones del usuario
                if($this->Users->save($user)){

                    $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                    //traigo los datos nuevamente y actualizo el current user

                    //actualizo el auth.user

                    $this->set('current_user', $user);


                    //Consulto si es ADMIN u otro y redirecciono
                    if($user->role == 'admin'){
                        return $this->redirect(['controller' => 'Pages' , 'action' => 'indexAdmin']);
                    } else {
                        return $this->redirect(['controller' => 'Pages' , 'action' => 'index']);
                    }
                }


            }
        } //end if action



        $this->set(compact('user'));
        $this->set('_serialize', ['user']);

    }


    public function logout()
    {

        return $this->redirect($this->Auth->logout());
    }

    //Seccion de administracion de usuarios





}
