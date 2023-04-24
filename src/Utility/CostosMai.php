<?php

namespace App\Utility;

class CostosMai
{


    public function getCostoFijoHorario($maquina)
    {

        return $maquina['result_metod']['interes'] + $maquina['result_metod']['seguro'];

    }

    public function getCostoSemifijoHorario($maquina)
    {
        return $maquina['result_metod']['dep_maq'] + $maquina['result_metod']['dep_neum'];
    }

    public function getCostoVariableHorario($maquina)
    {
        return $maquina['result_metod']['arreglos_maq'] + $maquina['result_metod']['cons_comb'] +
            $maquina['result_metod']['cons_lub'] +  $maquina['result_metod']['operador'];
    }

    public function getCostoMantenimientoHorario($maquina)
    {
        return  $maquina['result_metod']['mantenimiento'];
    }

    public function getCostoAdministracioHorario($maquina)
    {
        return $maquina['result_metod']['administracion'];
    }


    /*
     * Devuelve cualqueir tipo de costo/t dado que usa la misma formula
     */
    public function getCostos_Tonelada($maquina, $case)
    {
        $rendimiento = $maquina['costos']['prod_rend_h'];
        if($case == 1)
        {

            $costo = $this->getCostoFijoHorario($maquina);

            return $rendimiento == 0 ? 0 : $costo / $rendimiento;

        } elseif ($case == 2){

            $costo = $this->getCostoSemifijoHorario($maquina);

            return $rendimiento == 0 ? 0 : $costo / $rendimiento;

        } elseif ($case == 3){
            $costo = $this->getCostoVariableHorario($maquina);
            return $rendimiento == 0 ? 0 : $costo / $rendimiento;
        } elseif ($case == 4){
            $costo = $this->getCostoMantenimientoHorario($maquina);
            return $rendimiento == 0 ? 0 : $costo / $rendimiento;
        } elseif ($case == 5){
            $costo = $this->getCostoAdministracioHorario($maquina);
            return $rendimiento == 0 ? 0 : $costo / $rendimiento;
        }


    }


    public function getSumatoriaCostos($maquinas, $case)
    {
        $suma_ = 0;
        if ($case == 1)
        {
            foreach ($maquinas as $maq)
            {

                $suma_ = $suma_ + $maq['costos_fijos_variables']['costo_fijo_tonelada'];


            }
            return $suma_;
        } elseif ($case == 2){

            foreach ($maquinas as $maq)
            {

                $suma_ = $suma_ + $maq['costos_fijos_variables']['costo_semifijo_tonelada'];


            }
            return $suma_;
        }  elseif ($case == 3){

            foreach ($maquinas as $maq)
            {

                $suma_ = $suma_ + $maq['costos_fijos_variables']['costo_variable_tonelada'];


            }
            return $suma_;
        }  elseif ($case == 4){

            foreach ($maquinas as $maq)
            {

                $suma_ = $suma_ + $maq['costos_fijos_variables']['costo_mantenimiento_tonelada'];


            }
            return $suma_;
        }  elseif ($case == 5){

            foreach ($maquinas as $maq)
            {

                $suma_ = $suma_ + $maq['costos_fijos_variables']['costo_administracion_tonelada'];


            }
            return $suma_;
        }

        return  null;

    }


    public function calculateMAIEconomico($costo, $precio_servicio)
    {
        return $precio_servicio - $costo;
    }

    public function calculateMAIFinanciero($precio_Servicio, $costo_variable, $costo_mantenimiento, $costo_administracion)
    {

        return $precio_Servicio - $costo_variable - $costo_mantenimiento - $costo_administracion;

    }


    public function calculateMAIElaboracionTransporte($costo, $precio_servicio)
    {
        return $precio_servicio - $costo;
    }




}
