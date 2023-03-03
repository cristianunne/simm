<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AnalisisCostosMaquinas Controller
 *
 */
class AnalisisCostosMaquinasController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'calculateCostosMaquina', 'calculateCostosGrupos', 'delete',
                'createdSheetResumen', 'createdSheetMaquinas', 'calculateCostosGruposCopy'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'calculateCostosMaquina', 'calculateCostosGrupos', 'delete',
                'createdSheetResumen', 'createdSheetMaquinas', 'calculateCostosGruposCopy'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    public function index()
    {

    }


    public function calculateCostosMaquina()
    {
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //AL Igual que los otros, solo se cargaran las maquinas que tengan todos los datos para analizar

    }


    /**
     * Este metodo verifica que la maquina tenga toda la info cargada para analizarse
     * @param $maquina
     * @return false
     */
    private function checkMaquinaIsOkeyToCostos($maquina = null)
    {
        //Checkeo que la maquina tengan los datos necesarios para agregarse a la lista



        return false;
    }

}
