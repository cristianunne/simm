<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Propietarios Controller
 *
 * @property \App\Model\Table\PropietariosTable $Propietarios
 */
class PropietariosController extends AppController
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
        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $propietarios =  $this->Propietarios->find('all', [
                'contain' => 'Users'
            ])->where(['Propietarios.active' => true, 'Propietarios.empresas_idempresas' => $id_empresa]);
            $this->set(compact('propietarios'));
        }
    }

    public function add()
    {
        $propietarios =  $this->Propietarios->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $propietarios = $this->Propietarios->patchEntity($propietarios, $this->request->getData());


            //AGrego lo datos falantes
            $propietarios->created = date("Y-m-d");
            $propietarios->empresas_idempresas = $id_empresa;
            $propietarios->users_idusers = $user_id;

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

                        $propietarios->logo = $result_save;
                        $propietarios->folder = LOGOS_SHORT;

                        //GUardo las actualizaciones del usuario
                        if($this->Propietarios->save($propietarios)){

                            $this->Flash->success(__('Los cambios se hans almacenado correctamente'));
                            //traigo los datos nuevamente y actualizo el current user

                            return $this->redirect(['action' => 'index']);
                        }
                    }

                }
           }
           else
           {
               //COmo no vino una imagen, guardo igual
               if($this->Propietarios->save($propietarios)){

                   $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                   //traigo los datos nuevamente y actualizo el current user

                   return $this->redirect(['action' => 'index']);
               }
           }

        }

        $this->set(compact('propietarios'));
    }

}
