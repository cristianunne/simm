<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

/**
 * InformesMaquinas Controller
 *
 * @property \App\Model\Table\InformesMaquinasTable $InformesMaquinas
 */
class InformesMaquinasController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index'. 'downloadAsExcel'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'downloadAsExcel'])) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

    public function index()
    {
        //Variable usada para el sidebar
        $seccion = 'Informes';
        $sub_seccion = 'Informes';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $informes = $this->InformesMaquinas->find('all', [
                'contain' => []
            ])->where(['empresas_idempresas' => $id_empresa]);
            $this->set(compact('informes'));

            //debug($informes->toArray());
        }

    }

    public function downloadAsExcel($id = null)
    {

        //TRaigo el informe
        try {

            $informe = $this->InformesMaquinas->get($id);


            $path =  $path = WWW_ROOT.'files/excels/'.$informe->name . '.xlsx';

            $response = $this->response->withFile($path,
                ['download' => true]
            );
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

        return $this->redirect(['action' => 'index']);

    }
}
