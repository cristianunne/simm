<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProvDeptos Controller
 *
 * @property \App\Model\Table\ProvDeptosTable $ProvDeptos
 */
class ProvDeptosController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['getDepartamentos', 'add', 'edit', 'delete', 'showInactive'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['getDepartamentos', 'add', 'edit', 'delete', 'showInactive'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    public function getProvincias()
    {
        $this->autoRender = false;

        $provincias = $this->ProvDeptos->find('all', [
            'conditions' => ['dpto !=' => '']
        ])->select(['provincia' => 'provincia'])
            ->distinct(['provincia'])
            ->order(['provincia ASC'])
            ->toArray();

        //recorro el arreglo y obtengo los arrays
        $array_provincia = [];

        foreach ($provincias as $data){

            $array_provincia[$data->provincia] = $data->provincia;
        }

        return $array_provincia;

    }


    public function getDepartamentos()
    {
        $this->autoRender = false;
        $array_data = [];
        $provincia = $_POST['provincia'];

        if($this->request->is('ajax')) {

            $dptos = $this->ProvDeptos->find('all', [
                'conditions' => ['dpto !=' => '']
            ])->select(['departamentos' => 'dpto'])
                ->where(['provincia' => $provincia])
                ->distinct(['dpto'])
                ->order(['departamentos ASC'])
                ->toArray();
            $array_data = $dptos;
        }
        return $this->json($array_data);
    }

    public function getDepartamentos2($prov = null)
    {
        $this->autoRender = false;
        $array_data = [];
        $provincia = $prov;

            $dptos = $this->ProvDeptos->find('all', [
                'conditions' => ['dpto !=' => '']
            ])->select(['departamentos' => 'dpto'])
                ->where(['provincia' => $provincia])
                ->distinct(['dpto'])
                ->order(['departamentos ASC'])
                ->toArray();
            $array_data = $dptos;
        //recorro el arreglo y obtengo los arrays
        $array_dpto = [];

        foreach ($array_data as $data){

            $array_dpto[$data->departamentos] = $data->departamentos;
        }

        return $array_dpto;
    }


}
