<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AnalisisCostos Controller
 *
 * @property \App\Model\Table\AnalisisCostosTable $AnalisisCostos
 */
class AnalisisCostosController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'groupsCostosAnalysis', 'calculateCostosGrupos', 'delete', 'showInactive'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'groupsCostosAnalysis', 'calculateCostosGrupos', 'delete', 'showInactive'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    public function index()
    {

    }

    public function groupsCostosAnalysis()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //CARGO LOS MODELOS A USAR

        //TRaigo los grupos todos sin filtros
        $grupos_model = $this->loadModel('Worksgroups');
        $grupos_data = $grupos_model->find('list', [
            'keyField' => 'idworksgroups',
            'valueField' => 'name'
        ])
            ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
            ->toArray();

        $insertar = [0 => 'Todos'];

        array_splice($grupos_data, 0, 0, $insertar);

        $this->set(compact('grupos_data'));


        //Traigo los lotes
        $tablaLotes = $this->loadModel('Lotes');
        $lotes =  $tablaLotes->find('all', [
            'contain' => []
        ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
        $this->set(compact('lotes'));


        //Traigo los datos de los propietarios
        $tablaPropietarios = $this->loadModel('Propietarios');
        $propietarios =  $tablaPropietarios->find('all', [
            'contain' => []
        ])->where(['Propietarios.active' => true, 'Propietarios.empresas_idempresas' => $id_empresa]);
        $this->set(compact('propietarios'));

        //Traigo los datos de los propietarios
        $tablaDestinos = $this->loadModel('Destinos');

        $destinos =  $tablaDestinos->find('all', [
            'contain' => 'Users'
        ])->where(['Destinos.active' => true, 'Destinos.empresas_idempresas' => $id_empresa]);
        $this->set(compact('destinos'));



    }

    /*
     * Realiza el Análisis de Costos por Grupos de Trabajo
     */
    public function calculateCostosGrupos()
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



        //Tengo que recolectar toda la información primero
        //La Metodologia de Costos se esta definiendo en DATOS TEORICOS de las maquinas
        //DEfino lOS NOMBRES DE LOS DATOS TEORICOS Y/O REALES, DEBEN COINCIDIR CON LOS DEFINIDOS EN LA MET/COST
        $VAD = NULL; $VUM = NULL; $HTU = NULL; $HMU = NULL;


        $FCI = null; $HTU = null; $VAN = null; $HFU = null; $VUE = null;

        //Traigo las constantes
        $constantes_model = $this->loadModel('Constantes');

        $constantes = $constantes_model->find('list', [
            'keyField' => 'name',
            'valueField' => 'value'
        ])
            ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
            ->toArray();

        debug($constantes);
        debug($constantes['AME']);












    }


}
