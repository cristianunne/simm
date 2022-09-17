<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SystemsConfigurations Controller
 *
 */
class SystemsConfigurationsController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index'])) {
                return true;
            }
        }


        return parent::isAuthorized($user);
    }

    public function index()
    {
        //Variable usada para el sidebar
        $seccion = 'system';
        $sub_seccion = 'inicio';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

    }
}
