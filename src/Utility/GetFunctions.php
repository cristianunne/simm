<?php

namespace App\Utility;

use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Exception;

class GetFunctions
{




    public function getArreglosByMaquina($maquina, $array_options)
    {
        $array_options['maquina'] = $maquina;
        $arreglos_model = TableRegistry::getTableLocator()->get('ArreglosMecanicos');

        $arreglos = $arreglos_model->find('GetArreglosByConditions', $array_options);

        return $arreglos;
    }

    public function getCentrosCostrosByArray($centros_costos_array)
    {
        //Tabla centro de costos
        $tabla_centro_costos = TableRegistry::getTableLocator()->get('CentrosCostos');

        //TRae los centros de utilizando el array de centro de costos filtrados
        $centros_costos = $tabla_centro_costos->find('all', [
        ])->where(['idcentros_costos IN' => $centros_costos_array])->toArray();

        return $centros_costos;
    }

    public function getCentroCostosDistinct($new_lista_maquinas_with_data)
    {
        $this->autoRender = false;
        //Recorro las maquinas y flitro los centros de costos
        $array_centros = [];

        foreach ($new_lista_maquinas_with_data as $maq){


            if(!empty($maq['centro_costos'])){

                $array_centros[$maq['centro_costos'][0]->idcentros_costos] = $maq['centro_costos'][0]->idcentros_costos;

            }

        }
        return $array_centros;
    }



    public function getConstantesByEmpresa($id_empresa = null)
    {

        if($id_empresa != null){
            //Traigo las constantes
            $constantes_model = TableRegistry::getTableLocator()->get('Constantes');
            $constantes = $constantes_model->find('list', [
                'keyField' => 'name',
                'valueField' => 'value'
            ])
                ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
                ->toArray();

            return $constantes;
        }


        return null;
    }


    public function getCostosByMaquina($maquina, $mes, $year)
    {
        //LOs costos trae el activo
        $costos_maquina_model = TableRegistry::getTableLocator()->get('CostosMaquinas');

        $costos = $costos_maquina_model->find('all', [
            'contain' => ['CentrosCostos']
        ])
            ->where(['maquinas_idmaquinas' => $maquina, 'active' => true]);

        return $costos;

    }

    public function getDestinosById($id_destinos)
    {
        try{
            $destinos_model = TableRegistry::getTableLocator()->get('Destinos');
            $destinos = $destinos_model->get($id_destinos);

            return $destinos;

        } catch (InvalidPrimaryKeyException $e){
            return null;

        } catch (RecordNotFoundException $e){
            return null;
        }
        catch (Exception $e){
            return null;
        }



    }



    public function getLoteById($id_lote)
    {
        try{

            $lotes_model = TableRegistry::getTableLocator()->get('Lotes');
            $lotes = $lotes_model->get($id_lote);

            return $lotes;

        } catch (InvalidPrimaryKeyException $e){
            return null;

        } catch (RecordNotFoundException $e){
            return null;
        }
        catch (Exception $e){
            return null;
        }

    }


    public function getMaquinaById($maquina)
    {

        try{

            $maquinas_model = TableRegistry::getTableLocator()->get('Maquinas');
            $maquina = $maquinas_model->get($maquina);

            return $maquina;

        } catch (InvalidPrimaryKeyException $e){
            return null;

        } catch (RecordNotFoundException $e){
            return null;
        }
        catch (Exception $e){
            return null;
        }

    }


    public function getMaquinasDistinct($data_organized_by_month)
    {
        $array_maquinas_distinct = [];

        foreach ($data_organized_by_month as $data){
            foreach ($data['maquinas'] as $maq){

                $array_maquinas_distinct[$maq['idmaquinas']] = $maq['idmaquinas'];

            }
        }

        return $array_maquinas_distinct;

    }


    public function getMetadataResumenCostosGrupos($array_options)
    {

        $array_result = [];
        //Recorro las opciones

        $array_result['fecha_inicio'] = $array_options['fecha_inicio'];
        $array_result['fecha_fin'] = $array_options['fecha_fin'];
        $array_result['empresas_idempresas'] = $array_options['empresas_idempresas'];


        //ACa deberia pasar los nombres de los lotes y demas atributos



        if(isset($array_options['worksgroup'])){
            $array_result['worksgroup'] = $array_options['worksgroup'] == 0 ? 'Todos' :
                $this->getWorksgroupById($array_options['worksgroup'])->name;
        }

        if(isset($array_options['lotes_idlotes'])){
            $array_result['lote'] = $array_options['lotes_idlotes'] == 0 ? 'Todos' :
                $this->getLoteById($array_options['lotes_idlotes'])->name;
        }

        if(isset($array_options['parcelas_idparcelas'])){
            $array_result['parcela'] = $array_options['parcelas_idparcelas'] == 0 ? 'Todos' :
                $this->getParcelaById($array_options['parcelas_idparcelas'])->name;
        }

        if(isset($array_options['propietarios_idpropietarios'])){

            // Tengo que saber si el propietario es empresa o persona
            $name = null;

            //Consulto si no es null
            $prop_object = $this->getPropietariosById($array_options['propietarios_idpropietarios']);

            if(!is_null($prop_object)){
                if($prop_object->tipo == 'Persona'){
                    $name = $prop_object->firstname . ' ' .
                        $prop_object->lastname;

                } else if($prop_object->tipo == 'Empresa'){
                    $name = $prop_object->name;
                }
            }


            $array_result['propietario'] = $array_options['propietarios_idpropietarios'] == 0 ? 'Todos' :
                $name;
        }

        if(isset($array_options['destinos_iddestinos'])){

            $array_result['destino'] = $array_options['destinos_iddestinos'] == 0 ? 'Todos' :
                $this->getDestinosById($array_options['destinos_iddestinos'])->name;
        }




        return $array_result;
    }


    public function getMonthsAndYears($array_options = null)
    {
        $array_result = [];

        $fechai=strtotime($array_options['fecha_inicio']);

        $array_result[] = ['mes' => date('m',$fechai),
            'year' => date('Y',$fechai)];

        //$array_result[] = strtotime ('month',$fechai);
        while($fechai < strtotime($array_options['fecha_fin'])){
            $fechai = strtotime ('+1 month',$fechai) ;

            $array_result[] = ['mes' => date('m',$fechai),
                'year' => date('Y',$fechai)];

        }
        array_pop($array_result);
        return $array_result;

    }


    public function getOperarioByMaquina($maquina, $remitos_distinc)
    {

        $rem_maq_model = TableRegistry::getTableLocator()->get('RemitosMaquinas');

        $operarios = $rem_maq_model->find('all', [
        ])
            ->where(['remitos_idremitos IN ' => $remitos_distinc, 'maquinas_idmaquinas' => $maquina]);

        return $operarios;

    }

    public function getOperariosMaquinasByOperAndRemito($operario_maq, $mes, $year)
    {

        $options = [];
        $operarios_maquinas_model = TableRegistry::getTableLocator()->get('OperariosMaquinas');

        $array_result = [];

        foreach ($operario_maq as $op_maq){

            $options['operarios_idoperarios'] = $op_maq->operarios_idoperarios;
            $options['maquinas_idmaquinas'] = $op_maq->maquinas_idmaquinas;
            $options['mes'] = $mes;
            $options['year'] = $year;

            $array_result[] = $operarios_maquinas_model->find('GetOperariosMaquinasByConditions', $options)->toArray();

        }


        //Recorro y guardo un nuevo arreglo con sin los repetidos
        $array_result_new = [];

        foreach ($array_result as $arr){
            foreach ($arr as $op_maq){

                if(count($array_result_new) == 0){
                    $array_result_new[] = $op_maq;
                } else {
                    $exists_op = false;
                    foreach ($array_result_new as $new_arr){
                        if($new_arr->idoperarios_maquinas == $op_maq->idoperarios_maquinas){
                            $exists_op = true;
                        }
                    }

                    if(!$exists_op){
                        $array_result_new[] = $op_maq;
                    }

                }
            }
        }
        //debug($array_result_new);

        return $array_result_new;
    }



    public function getParcelaById($id_parcela)
    {

        try{

            $parcelas_model = TableRegistry::getTableLocator()->get('Parcelas');
            $parcelas_model =  $parcelas_model->get($id_parcela);

            return $parcelas_model;


        } catch (InvalidPrimaryKeyException $e){
            return null;

        } catch (RecordNotFoundException $e){
            return null;
        }
        catch (Exception $e){
            return null;
        }

    }


    public function getPropietariosById($id_propietario)
    {
        try{


            $propietarios_model = TableRegistry::getTableLocator()->get('Propietarios');
            $propietarios =  $propietarios_model->get($id_propietario);

            return $propietarios;

        } catch (InvalidPrimaryKeyException $e){
            return null;

        } catch (RecordNotFoundException $e){
            return null;
        }
        catch (Exception $e){
            return null;
        }
    }


    public function getRemitosByConditions($array_options)
    {
        $this->autoRender = false;


        $remitos_table = TableRegistry::getTableLocator()->get('Remitos');
        //$this->loadModel('Remitos');

        $remitos = $remitos_table->find('RemitosByConditionsQuery',
            $array_options);

        $array_result = [];

        foreach ($remitos as $rem){

            $array_result[$rem->idremitos] = $rem->idremitos;
        }

        return $array_result;
    }

    public function getRemitosByMaquina($maquina, $array_options)
    {

        $array_options['maquina'] = $maquina;


        $remitos_model = TableRegistry::getTableLocator()->get('Remitos');

        $remitos = $remitos_model->find('RemitosByConditionsQueryMaquina', $array_options);

        return $remitos;
    }

    public function getRemitosAsArrayDistinct($remitos)
    {
        $array_result = [];

        foreach ($remitos as $rem){
            $array_result[$rem->idremitos] = $rem->idremitos;
        }

        return $array_result;
    }


    public function getUsoMaquinariaByMaquina($maquina, $array_options)
    {
        $array_options['maquina'] = $maquina;
        $usos_model = TableRegistry::getTableLocator()->get('UsoMaquinaria');
        $arreglos = $usos_model->find('GetUsoMaquinariaByConditions', $array_options);

        return $arreglos;
    }

    public function getUsoMaquinariaCombustible($idusomaquinaria)
    {
        //CArgo la tabla uso de maquinaria
        $uso_maquinaria_model = TableRegistry::getTableLocator()->get('UsoCombLub');

        $uso_maq_comb = $uso_maquinaria_model->find('all', [])
            ->where(['uso_maquinaria_iduso_maquinaria' => $idusomaquinaria]);

        return $uso_maq_comb;
    }


    public function getWorksgroupById($id_worksgroup)
    {

        try{

            $worksgroup_model = TableRegistry::getTableLocator()->get('Worksgroups');
            $worksgroup =  $worksgroup_model->get($id_worksgroup);

            return $worksgroup;


        } catch (InvalidPrimaryKeyException $e){
          return null;

        } catch (RecordNotFoundException $e){
            return null;
        }
        catch (Exception $e){
            return null;
        }


    }



}
