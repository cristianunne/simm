<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use Cake\Filesystem\File;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * FilesManager Controller
 *
 */
class FilesManagerController extends AppController
{

    public function isAuthorized($user)
    {
        if(isset($user['role']) and $user['role'] === 'user')
        {
            if(in_array($this->request->getParam('action'), ['uploadFiles']))
            {
                return true;
            }
        } else if(isset($user['role']) and $user['role'] === 'supervisor')
        {
            if(in_array($this->request->getParam('action'), ['uploadFiles']))
            {
                return true;
            }
        }


        return parent::isAuthorized($user);
    }


    /**
     * @param $file_request represents the file sending by request
     * @param $destination represents the folder where has saved the file
     * @return string
     */
    public function uploadFiles($file_request = null, $destination = null)
    {
        $this->autoRender = false;

        try{
            $new_name = $file_request['name']. date("F j, Y, g:i a");
            $name_sha = hash('sha256' , $new_name) . '_' . $file_request['name'];
            if (move_uploaded_file($file_request['tmp_name'], $destination.$name_sha)) {
                return $name_sha;
            }
            return false;

        } catch (Exception $e){
            return false;
        }
        return false;
    }

    public function deleteFile($filename = null, $directory = null)
    {
        try{
            $file = new File($directory.$filename);
            if($file->exists()){
                if($file->delete()){
                    $file->close();
                    return true;
                }
                return false;
            }
        } catch (Exception $e){
            return false;
        }
        return false;
    }

    public function updateFilesWithEdit($data_request = null, $directory = null, $directory_short = null, $model = null, $entity = null)
    {


        if ($data_request['file']['name'] != '') {

            //limito a 2bm la subida de las imagenes
            if (($data_request['file']['size'] / 1024) > 2048) {
                //Excedi los 2 MB, informo
                $this->Flash->error(__('Seleccione una imágen con un tamaño inferior a 2MB'));
                return false;
            }
            else {

                //Consulto si la empresa tiene datos en el logo
                if ($entity->logo != '') {
                    //ELimino el archivo
                    if ($this->deleteFile($entity->logo, $directory)) {

                        $result_save = $this->uploadFiles($data_request['file'], $directory);

                        if (!$result_save) {
                            $this->Flash->error(__('El Registro no pudo ser actualizada. Intente nuevamente.'));
                            return false;
                        } else {

                            $entity->logo = $result_save;
                            $entity->folder = $directory_short;

                            //GUardo las actualizaciones del usuario
                            if ($model->save($entity)) {
                                return true;
                            }
                        }
                    } else {
                        $this->Flash->error(__('El Registro no pudo ser actualizada. Intente nuevamente.'));
                        return false;
                    }
                } else {
                    $result_save = $this->uploadFiles($data_request['file'], $directory);
                    if (!$result_save) {
                        $this->Flash->error(__('El Registro no pudo ser actualizada. Intente nuevamente.'));
                        return false;
                    } else {
                        $entity->logo = $result_save;
                        $entity->folder = $directory_short;

                        //GUardo las actualizaciones del usuario
                        if ($model->save($entity)) {
                           return true;
                        }
                    }
                }
            }
        }
        else {

            //GUardo las actualizaciones del usuario
            if ($model->save($entity)) {

                return true;
            } else {
                $this->Flash->error(__('El Registro no pudo ser actualizada. Intente nuevamente.'));
                return false;
            }
        }
    }




}
