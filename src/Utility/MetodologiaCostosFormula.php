<?php
namespace App\Utility;

class MetodologiaCostosFormula
{


    public function calculateInteres($VAD, $TIS, $FCI, $HTU)
    {
        //$VAD*($TIS/100)*$FCI/$HTU

        return $HTU == 0 ? 0 : ($VAD*($TIS/100)*$FCI/$HTU);

    }

    public function calculateSeguros($VAD, $CSE, $HTU)
    {
        //$VAD*$CSE/$HTU

        return $HTU == 0 ? 0 : ($VAD*$CSE/$HTU);

    }

    public function calculateDeprecacionMaquina($VAD, $CVD, $VAN, $HFU, $VUE)
    {

        //($VAD*$CVD-$VAN)/($HFU*$VUE)

        return ($HFU*$VUE) == 0 ? 0 : ($VAD*$CVD-$VAN)/($HFU*$VUE);
    }


    public function calculateDepreciacionNeumatico($VAN, $VUN)
    {

        //$VAN/$VUN
        return $VUN == 0 ? 0 : ($VAN / $VUN);


    }

    public function calculateArreglosMecanicos($VAD, $CVD, $VAN, $HFU, $VUE, $AME)
    {
        //(($VAD*$CVD-$VAN)/($HFU*$VUE))*$AME

        return ($HFU*$VUE) == 0 ? 0 : ((($VAD*$CVD-$VAN)/($HFU*$VUE))*$AME);


    }

    /**
     * @param $gastos_comb
     * @param $HME  // son las horas mensuales de uso
     */
    public function calculateConsumoCombustible($gastos_comb, $HME)
    {
        //$COH*$COM
        //Gastos de combustible ya tiene calculado COH y COM
        return $HME == 0 ? 0 : ($gastos_comb / $HME);
    }


    public function calculateConsumoLubricante($gasto_lubricante, $HME)
    {
        //$LUH*$LUB
        return $HME == 0 ? 0 : ($gasto_lubricante / $HME);
    }

    public function calculateOperador($SAL, $HME)
    {
        //$SAL/$HME

        return $HME == 0 ? 0 : ($SAL / $HME);

    }

    public function calculateMantenimiento($SAL, $HME, $CMA)
    {
        //($SAL/$HME)*$CMA

        return $HME == 0 ? 0 : (($SAL / $HME) * $CMA);

    }

    public function calculateAdministracion($interes, $seguro, $dep_maquina, $dep_lubricante, $arreglos, $cons_comb, $cons_lub,
    $operador, $mantenimiento, $CAD)
    {
        //($VAD*($TIS/100)*$FCI/$HTU + $VAD*$CSE/$HTU + ($VAD*$CVD-$VAN)/($HFU*$VUE)
        // + ($VAN/$VUN) + (($VAD*$CVD-$VAN)/($HFU*$VUE))*$AME + $COH*$COM + $LUH*$LUB + $SAL/$HME + ($SAL/$HME)*$CMA) * $CAD

        $suma_result = $interes + $seguro + $dep_maquina + $dep_lubricante + $arreglos+ $cons_comb + $cons_lub + $operador + $mantenimiento;
        $result = $suma_result * $CAD;

        return $result;

    }


}

?>