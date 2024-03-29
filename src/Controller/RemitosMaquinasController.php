<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * RemitosMaquinas Controller
 *
 * @property \App\Model\Table\RemitosMaquinasTable $RemitosMaquinas
 */
class RemitosMaquinasController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'addRemitoMaquina', 'addRemitoMaquinaAlquilada'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'addRemitoMaquina'. 'addRemitoMaquinaAlquilada'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }



    public function addRemitoMaquina()
    {
        $this->autoRender = false;

        $remitos_idremitos = $_POST['remitos_idremitos'];
        $operarios_idoperarios = $_POST['operarios_idoperarios'];
        $maquinas_idmaquinas = $_POST['maquinas_idmaquinas'];

        if($this->request->is('ajax')) {

            $remitos_maq = $this->RemitosMaquinas->newEntity();

            $remitos_maq->remitos_idremitos = $remitos_idremitos;
            $remitos_maq->operarios_idoperarios = $operarios_idoperarios;
            $remitos_maq->maquinas_idmaquinas = $maquinas_idmaquinas;

            //Guardo la variable
            if($this->RemitosMaquinas->save($remitos_maq)){

                return $this->json(true);

            } else {
                return $this->json(false);
            }


        }


    }

    public function addRemitoMaquinaAlquilada()
    {
        $this->autoRender = false;

        $remitos_idremitos = $_POST['remitos_idremitos'];
        $maquinas_idmaquinas = $_POST['maquinas_idmaquinas'];

        if($this->request->is('ajax')) {

            $remitos_maq = $this->RemitosMaquinas->newEntity();

            $remitos_maq->remitos_idremitos = $remitos_idremitos;
            $remitos_maq->maquinas_idmaquinas = $maquinas_idmaquinas;

            //Guardo la variable
            if($this->RemitosMaquinas->save($remitos_maq)){

                return $this->json(true);

            } else {
                return $this->json(false);
            }
        }
    }
}
