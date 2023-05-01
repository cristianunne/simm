<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

/**
 * InformesVariaciones Controller
 *
 * @property \App\Model\Table\InformesVariacionesTable $InformesVariaciones
 */
class InformesVariacionesController extends AppController
{
    const TYPE_MAQUINA = 'maquina';
    const TYPE_GROUP = 'grupo';
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index'. 'downloadAsExcel', 'delete'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'downloadAsExcel', 'delete'])) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

    public function index()
    {
        $seccion = 'Informes';
        $sub_seccion = 'Informes';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $informes_var_grupo = $this->InformesVariaciones->find('all', [])
            ->where(['empresas_idempresas' => $id_empresa, 'tipo LIKE' => self::TYPE_GROUP]);

        $this->set(compact('informes_var_grupo'));

        $informes_var_maquina = $this->InformesVariaciones->find('all', [])
            ->where(['empresas_idempresas' => $id_empresa, 'tipo LIKE' => self::TYPE_MAQUINA]);
        $this->set(compact('informes_var_maquina'));

    }

    public function downloadAsExcel($id = null)
    {
        $this->autoRender = false;

        //TRaigo el informe
        try {

            $informe_resumen = $this->InformesVariaciones->get($id);

            $path =  $path = WWW_ROOT.'files/excels/'.$informe_resumen->name . '.xlsx';


            $response = $this->response->withFile($path,
                ['download' => true]
            );

            //debug($path);

            return $response;

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));
        }
        catch (NotFoundException $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));
        }
        catch (Exception $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));
        }

        return $this->redirect(['action' => 'destinosReportIndex']);

    }

    public function delete($id = null)
    {

        $this->autoRender = false;

        $this->request->allowMethod(['post', 'delete']);

        try{
            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'MetodCostos';
            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $informe =  $this->InformesVariaciones->get($id);

            //debo consultar si esta constante se usa en otro lugar antes de eliminar

            if ($this->InformesVariaciones->delete($informe)) {
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
