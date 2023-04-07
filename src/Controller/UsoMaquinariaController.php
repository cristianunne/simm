<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * UsoMaquinaria Controller
 *
 * @property \App\Model\Table\UsoMaquinariaTable $UsoMaquinaria
 */
class UsoMaquinariaController extends AppController
{
    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'deleteUsoComb', 'viewCombLub',
                'deleteUsoCombSimple', 'addUsoMaqAjax'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'add', 'edit', 'delete', 'deleteUsoComb', 'viewCombLub',
                'deleteUsoCombSimple', 'addUsoMaqAjax'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    public function index()
    {

        //Variable usada para el sidebar
        $seccion = 'uso_maquinaria';
        $sub_seccion = 'Inicio';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        } else {

            $uso_maquinas = $this->UsoMaquinaria->find('all', [
                'contain' => ['Users', 'Maquinas', 'Parcelas' => 'Lotes', 'UsoCombLub']
            ])->where(['UsoMaquinaria.empresas_idempresas' => $id_empresa]);

            //debug($uso_maquinas->toArray());
            $arreglo = $uso_maquinas->toArray();
            $uso_comb_lub = null;
            if(!isset($arreglo[0]) && !empty($arreglo[0])){
                $uso_comb_lub = $arreglo[0]->uso_comb_lub;
            }



        }

        $this->set(compact('uso_maquinas'));
        $this->set(compact('uso_comb_lub'));




    }

    /**
     * @throws \Exception
     */
    public function add()
    {

        //Variable usada para el sidebar
        $seccion = 'uso_maquinaria';
        $sub_seccion = '';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        $uso_maquina = $this->UsoMaquinaria->newEntity();

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }




        //Traigo las maquinas
        $maquina_model = $this->loadModel('Maquinas');

        $maquinas_data = $maquina_model->find('list',[
            'keyField' => 'idmaquinas',
            'valueField' => ['marca', 'name'],
        ])
        ->where(['empresas_idempresas' => $id_empresa, 'active' => true, 'propia' => true])
            ->toArray();


        $this->set(compact('maquinas_data'));



        $tablaLotes = $this->loadModel('Lotes');

        $lotes =  $tablaLotes->find('all', [
            'contain' => []
        ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
        $this->set(compact('lotes'));

        $this->set(compact('lotes'));

        //Creo un array con las opciones de combustible
        $combustibles = ['Ultra Diesel' => 'Ultra Diesel', 'Infinia Diesel' => 'Infinia Diesel', 'Nafta' => 'Nafta'];
        $this->set(compact('combustibles'));

        $lubricantes = ['Aceite de Motor' => 'Aceite de Motor', 'Aceite de Caja' => 'Aceite de Caja', 'Aceite Hidraulico' => 'Aceite Hidraulico',
            'Aceite de Cadena' => 'Aceite de Cadena', 'Grasa' => 'Grasa'];
        $this->set(compact('lubricantes'));


        if ($this->request->is('post')) {
            $data_ = $this->request->getData();
            $cant_combustible = 0;
            $cant_lubricante = 0;
            if(isset($data_['cant_combustible'])){
                $cant_combustible = $data_['cant_combustible'];
            }
            if(isset($data_['cant_lubricante'])){
                $cant_lubricante = $data_['cant_lubricante'];
            }



            $uso_maquina = $this->UsoMaquinaria->patchEntity($uso_maquina, $this->request->getData());
            $uso_maquina->empresas_idempresas = $id_empresa;
            $uso_maquina->users_idusers = $user_id;

            if ($this->UsoMaquinaria->save($uso_maquina)) {


                //Armo el array de la asociacion y guardo
                $array_data_comb_lub = [];

                for ($i = 1; $i <= $cant_combustible; $i++ ){
                    $array = $data_['Combustible' . $i];

                    //Creo el array con la estructura de la entidad
                    $array_aux = [
                        'categoria' => $array[0],
                        'producto' => $array[1],
                        'litros' => $array[2],
                        'uso_maquinaria_iduso_maquinaria' => $uso_maquina->iduso_maquinaria,
                        'precio' => $array[3],
                    ];


                    $array_data_comb_lub[] = $array_aux;
                }

                //Hago lo mismo para los lubricantes

                for ($j = 1; $j <= $cant_lubricante; $j++ ){
                    $array = $data_['Lubricante' . $j];

                    //Creo el array con la estructura de la entidad
                    $array_aux = [
                        'categoria' => $array[0],
                        'producto' => $array[1],
                        'litros' => $array[2],
                        'uso_maquinaria_iduso_maquinaria' => $uso_maquina->iduso_maquinaria,
                        'precio' => $array[3],
                    ];

                    $array_data_comb_lub[] = $array_aux;
                }
                //Tengo tod armado, creo las entidades
                $tabla_usocomlub = $this->loadModel('UsoCombLub');

                $entities = $tabla_usocomlub->newEntities($array_data_comb_lub);

                if($tabla_usocomlub->saveMany($entities)){
                    $this->Flash->success(__('La Máquina se ha almacenado correctamente'));
                }

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
            }
        }

        $this->set(compact('uso_maquina'));

    }


    public function edit($id = null)
    {
        //Variable usada para el sidebar
        $seccion = 'uso_maquinaria';
        $sub_seccion = '';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        //Proceso usando el try catch

        $uso_maquina = null;
        $parcela_data = null;

        if(is_null($id))
        {
            return $this->redirect(['controller' => 'UsoMaquinaria', 'action' => 'index']);
        } else {

            //Ahora proceso el EDit

            try{

                $uso_maquina =  $this->UsoMaquinaria->get($id, [
                    'contain' => ['Users', 'Maquinas', 'Parcelas' => ['Lotes'], 'UsoCombLub']
                ]);
                //debug($uso_maquina);

                //Creo un array con las categorias y elimino las que estan presente
                $combustible_aux = ['Ultra Diesel' => 'Ultra Diesel', 'Infinia Diesel' => 'Infinia Diesel', 'Nafta' => 'Nafta'];
                $combustibles = [];


                foreach ($combustible_aux as $comb_aux){
                    $is_present = false;
                    foreach ($uso_maquina->uso_comb_lub as $comb){
                        //Primero consuto por la categoria
                        if($comb->categoria == 'Combustible'){

                            if($comb_aux == $comb->producto){
                                    $is_present = true;
                            }
                        }
                    }
                    //Si no esta presente lo agrego a la lista
                    if(!$is_present){
                        $combustibles[$comb_aux] = $comb_aux;
                    }

                }

                $this->set(compact('combustibles'));

                //Proceso el array de Lubricantes ahora
                $lubricante_aux = ['Aceite de Motor' => 'Aceite de Motor', 'Aceite de Caja' => 'Aceite de Caja', 'Aceite Hidraulico' => 'Aceite Hidraulico',
                    'Aceite de Cadena' => 'Aceite de Cadena', 'Grasa' => 'Grasa'];
                $lubricantes = [];


                foreach ($lubricante_aux as $lub_aux){
                    $is_present = false;
                    foreach ($uso_maquina->uso_comb_lub as $comb){
                        //Primero consuto por la categoria
                        if($comb->categoria == 'Lubricante'){

                            if($lub_aux == $comb->producto){
                                $is_present = true;
                            }
                        }
                    }
                    //Si no esta presente lo agrego a la lista
                    if(!$is_present){
                        $lubricantes[$lub_aux] = $lub_aux;
                    }

                }

                $this->set(compact('lubricantes'));



                //TRaigo la lista de maquinas

                $maquina_model = $this->loadModel('Maquinas');

                $maquinas_data = $maquina_model->find('list',[
                    'keyField' => 'idmaquinas',
                    'valueField' => ['marca', 'name'],
                ])
                    ->where(['empresas_idempresas' => $id_empresa, 'active' => true])
                    ->toArray();




                //TRaigo los lotes

                $tablaLotes = $this->loadModel('Lotes');

                $lotes =  $tablaLotes->find('all', [
                    'contain' => []
                ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
                $this->set(compact('lotes'));

                $this->set(compact('lotes'));

                if($uso_maquina->parcela != null){

                    $parcela_entity = new ParcelasController();
                    $parcela_data = $parcela_entity->getParcelaByLoteOther($uso_maquina->parcela->lote->idlotes);
                }

                //debug($this->request->getData());


                if ($this->request->is(['patch', 'post', 'put'])) {


                    $uso_maq = $this->UsoMaquinaria->patchEntity($uso_maquina, $this->request->getData());
                    //$uso_maq->users_idusers = $user_id;

                    if($this->UsoMaquinaria->save($uso_maq)){
                        $this->Flash->success(__('El Registro se ha almacenado correctamente'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));
                    }

                }

            } catch (InvalidPrimaryKeyException $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

            } catch (RecordNotFoundException $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            }
            catch (Exception $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            }

        }
        $this->set(compact('uso_maquina'));
        $this->set(compact('maquinas_data'));
        $this->set(compact('parcela_data'));

    }

    public function viewCombLub($id = null)
    {
        //Variable usada para el sidebar
        $seccion = 'uso_maquinaria';
        $sub_seccion = '';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if (empty($id_empresa)) {
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        if($id == null){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
            return $this->redirect(['action' => 'index']);
        }

        $uso_comb_lub_table = $this->loadModel('UsoCombLub');

        $uso_comb_lub = $uso_comb_lub_table->find('all', [
        ])->where(['uso_maquinaria_iduso_maquinaria' => $id]);

        $this->set(compact('uso_comb_lub'));


    }


    public function delete($id = null)
    {

        $this->request->allowMethod(['post', 'delete']);

        $this->autoRender = false;

        try{

            //Variable usada para el sidebar
            $seccion = 'uso_maquinaria';
            $sub_seccion = '';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $uso_maquinas =  $this->UsoMaquinaria->get($id);

            if ($this->UsoMaquinaria->delete($uso_maquinas)) {
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

    public function deleteUsoCombSimple($id = null, $id_uso = null)
    {

        $this->request->allowMethod(['post', 'delete']);

        $this->autoRender = false;

        try{

            //Variable usada para el sidebar
            $seccion = 'uso_maquinaria';
            $sub_seccion = '';

            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $usocomb_table = $this->loadModel('UsoCombLub');


            $uso_maquinas =  $usocomb_table->get($id);

            if ($usocomb_table->delete($uso_maquinas)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'viewCombLub', $id_uso]);
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

    public function deleteUsoComb()
    {

        $this->autoRender =  false;

        $id_uso_maq = $_POST['id_uso'];


        if(!is_null($id_uso_maq) and $id_uso_maq != ''){

            $usocomb_table = $this->loadModel('UsoCombLub');

            if($this->request->is('ajax')) {

                try{

                    $uso =  $usocomb_table->get($id_uso_maq);

                    if($usocomb_table->delete($uso)){

                        return $this->json(['result' => true]);
                    }

                } catch (InvalidPrimaryKeyException $e){
                    $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

                } catch (RecordNotFoundException $e){
                    $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                }
                catch (Exception $e){
                    $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
                }

            }
        }


    }


    public function addUsoMaqAjax()
    {
        $this->autoRender =  false;

        $categoria = $_POST['categoria'];
        $producto = $_POST['producto'];
        $litros= $_POST['litros'];
        $precio = $_POST['precio'];
        $uso_maquinaria_iduso_maquinaria = $_POST['uso_maquinaria_iduso_maquinaria'];

        if($this->request->is('ajax')) {

            try{

                //Tengo tod armado, creo las entidades
                $tabla_usocomlub = $this->loadModel('UsoCombLub');

                $array_data_comb_lub = [
                    'categoria' => $categoria,
                    'producto' => $producto,
                    'litros' => $litros,
                    'precio' => $precio,
                    'uso_maquinaria_iduso_maquinaria' => $uso_maquinaria_iduso_maquinaria
                ];


                $entity = $tabla_usocomlub->newEntity($array_data_comb_lub);

                if($tabla_usocomlub->save($entity)){

                    return $this->json(['result' => true]);
                }

            } catch (InvalidPrimaryKeyException $e){
                $this->Flash->error(__('Error al almacenar. Intenta nuevamente'));

            } catch (RecordNotFoundException $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            }
            catch (Exception $e){
                $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
            }

        }




    }


}
