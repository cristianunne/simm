<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Empresas Controller
 *
 * @property \App\Model\Table\EmpresasTable $Empresas
 */
class EmpresasController extends AppController
{
    public function isAuthorized($user)
    {

        return parent::isAuthorized($user);
    }


    public function index()
    {
        $empresas_active =  $this->Empresas->find('all', [
        ])->where(['active' => true]);

        $empresas_dactive =  $this->Empresas->find('all', [
        ])->where(['active' => false]);

        $this->set(compact('empresas_active'));
        $this->set(compact('empresas_dactive'));

    }
    public function add()
    {
        $empresa = $this->Empresas->newEntity();


        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $empresa = $this->Empresas->patchEntity($empresa, $this->request->getData());
            //AGrego lo datos falantes
            $empresa->created = date("Y-m-d");

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

                    $result_save = $filesManagerController->uploadFiles($data['file'], LOGOS);
                    if (!$result_save)
                    {
                        $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                    } else {

                        $empresa->logo = $result_save;
                        $empresa->folder = LOGOS_SHORT;

                        //GUardo las actualizaciones del usuario
                        if($this->Empresas->save($empresa)){

                            $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                            //traigo los datos nuevamente y actualizo el current user

                            return $this->redirect(['controller' => 'Pages' , 'action' => 'indexAdmin']);
                        }
                    }
                }
            } else
            {
                //COmo no vino una imagen, guardo igual
                if($this->Empresas->save($empresa)){

                    $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                    //traigo los datos nuevamente y actualizo el current user

                    return $this->redirect(['action' => 'index']);
                }
            }
        }

        $this->set(compact('empresa'));
    }

    public function edit($id = null)
    {
        $empresa = $this->Empresas->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();
            $empresa = $this->Empresas->patchEntity($empresa, $this->request->getData());

            if($data['file']['name'] != '')
            {
                //limito a 2bm la subida de las imagenes
                if(($data['file']['size'] / 1024) > 2048)
                {
                    //Excedi los 2 MB, informo
                    $this->Flash->error(__('Seleccione una imágen con un tamaño inferior a 2MB'));
                } else {

                    $filesManager = new FilesManagerController();

                    //Consulto si la empresa tiene datos en el logo
                    if($empresa->logo != '')
                    {
                        //ELimino el archivo
                        if($filesManager->deleteFile($empresa->logo, LOGOS)){
                            //La vuelvo a subir

                            $result_save = $filesManager->uploadFiles($data['file'], LOGOS);


                            if (!$result_save)
                            {
                                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                            } else {

                                $empresa->logo = $result_save;
                                $empresa->folder = LOGOS_SHORT;

                                //GUardo las actualizaciones del usuario
                                if($this->Empresas->save($empresa)){

                                    $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                                    //traigo los datos nuevamente y actualizo el current user

                                    return $this->redirect(['action' => 'index']);
                                }
                            }

                        } else {
                            $this->Flash->error(__('La Empresa no pudo ser actualizada. Intente nuevamente.'));
                        }
                    } else {

                        $result_save = $filesManager->uploadFiles($data['file'], LOGOS);

                        if (!$result_save)
                        {
                            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                        } else {

                            $empresa->logo = $result_save;
                            $empresa->folder = LOGOS_SHORT;

                            //GUardo las actualizaciones del usuario
                            if($this->Empresas->save($empresa)){

                                $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                                //traigo los datos nuevamente y actualizo el current user

                                return $this->redirect(['action' => 'index']);
                            }
                        }

                    }

                }
            } else {
                //COmo no vino una imagen, guardo igual
                if($this->Empresas->save($empresa)){

                    $this->Flash->success(__('Los cambios se han almacenado correctamente'));
                    //traigo los datos nuevamente y actualizo el current user

                    return $this->redirect(['action' => 'index']);
                }
            }

        }



        $this->set(compact('empresa'));
    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $empresas = $this->Empresas->get($id);

        try{

            //Primero elimino la imagen si es que tiene
            $var_aux = false;
            if($empresas->logo != '')
            {
                $filesManager = new FilesManagerController();
                //ELimino el archivo
                if($filesManager->deleteFile($empresas->logo, LOGOS)){
                    $var_aux = true;
                }

            } else {
                $var_aux = true;
            }

            if(!$var_aux){
                $this->Flash->error(__('La Empresa no pudo ser eliminada. Intente nuevamente.'));
            } else {
                if ($this->Empresas->delete($empresas)) {
                    $this->Flash->success(__('El Registro ha sido eliminado.'));

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('La Empresa no pudo ser eliminada. Intente nuevamente.'));
                }
            }

        }catch(\PDOException $e){
            $this->Flash->error(__($e->getMessage()));
            $this->Flash->error(__('La Empresa no pudo ser eliminada. Intente nuevamente.'));
        }


    }


    public function activated($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $empresas = $this->Empresas->get($id);
        $empresas->active = true;

        if ($this->Empresas->save($empresas)) {
            $this->Flash->success(__('La Empresa se ha Activado Correctamente.'));

            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('La Empresa no pudo ser Activada. Intente nuevamente.'));
        }
    }

    public function dactivated($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $empresas = $this->Empresas->get($id);
        $empresas->active = false;

        if ($this->Empresas->save($empresas)) {
            $this->Flash->success(__('La Empresa se ha Desactivada Correctamente.'));

            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('La Empresa no pudo ser Desactivada. Intente nuevamente.'));
        }
    }


    public function view($id = null)
    {

    }


}
