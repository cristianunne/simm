<?php

namespace App\Utility;

class VariablesAndConstantes
{

        /*$VAD = NULL; $VUM = NULL; $HTU = NULL; $HME = NULL; $TIS = null;
        $FCI = null; $VAN = null; $HFU = null; $VUE = null;
        $CCT = NULL; $CLT = NULL; $COM = NULL; $COH = NULL;
        $LUB = NULL; $LUH = NULL; $SAL = NULL; $VUN = NULL;
        $AME = NULL;*/
    private $VAD, $VUM, $HTU, $HME, $TIS, $FCI, $VAN, $HFU, $VUE, $CCT, $CLT, $COM, $COH, $LUB, $LUH, $SAL, $VUN, $AME;

    private $operarios_data, $remitos, $uso_maquinaria, $arreglos;
    private $gastos_sueldo, $gastos_combustibles, $gastos_lubricantes, $gastos_arreglos;
    private $litros_combustible, $litros_lubricante;
    private $toneladas, $precio_tonelada;

    function __construct($costos, $operarios_data, $remitos, $uso_maquinaria, $arreglos)
    {
        //Seteo las varables primero
        $this->setOperariosData($operarios_data);
        $this->setRemitos($remitos);
        $this->setUsoMaquinaria($uso_maquinaria);
        $this->setArreglos($arreglos);

        $this->setVAD($costos->val_adq);
        $this->setTIS($costos->tasa_int_simple);
        $this->setFCI($costos->factor_cor);
        $this->setHTU($costos->horas_total_uso);
        $this->setVAN($costos->val_neum);
        $this->setHFU($costos->horas_efec_uso);
        $this->setVUE($costos->vida_util);
        $this->setVUN($costos->vida_util_neum);


        $this->setGastosSueldo();

        $this->setToneladas();
        $this->setPrecioTonelada();

        //setero uso de maquinaria
        $this->setHME();
        $this->setCCT();
        $this->setCLT();
        $this->setCOH();
        $this->setLUH();
        $this->setSAL();

        $this->setGastosCombustibles();
        $this->setGastosLubricantes();
        $this->setGastosArreglos();
        $this->setLitrosCombustible();
        $this->setLitrosLubricante();

        $this->setAME();
        $this->setCOM();
        $this->setLUB();


    }



    public function setLUB()
    {

        $this->LUB = $this->getHME() == 0 ? null : ($this->getGastosLubricantes() / $this->getHME());

    }

    /**
     * @return mixed
     */
    public function getLUB()
    {
        return $this->LUB;
    }

    public function setCOM()
    {
        //CAlcula el precio ponderado de los combustibles
        $this->COM = $this->getHME() == 0 ? null : ($this->getGastosCombustibles() / $this->getHME());

    }

    /**
     * @return mixed
     */
    public function getCOM()
    {
        return $this->COM;
    }


    public function setGastosArreglos()
    {
        $gastos_arreglos_ = null;
        foreach ($this->getArreglos() as $arr){
            $gastos_arreglos_ = $gastos_arreglos_ + $arr->total;

        }

        $this->gastos_arreglos = $gastos_arreglos_;
    }


    /**
     * @return mixed
     */
    public function getGastosArreglos()
    {
        return $this->gastos_arreglos;
    }

    public function setAME()
    {
        $AME_ = null;
        foreach ($this->getArreglos() as $arr){
            $AME_ = $AME_ + $arr->total;

        }

        $this->AME = $AME_;
    }


    /**
     * @return mixed
     */
    public function getAME()
    {
        return $this->AME;
    }



    public function setSAL()
    {
        $suma_sal = null;

        foreach ($this->getOperariosData() as $op_maq){
            $suma_sal = $suma_sal + $op_maq->sueldo;
        }

        $this->SAL = $this->getHME() == 0 ? null : ($suma_sal / $this->getHME());

    }

    /**
     * @return mixed
     */
    public function getSAL()
    {
        return $this->SAL;
    }


    public function setLUH()
    {
        $this->LUH = $this->getHME() == 0 ? null : ($this->getCLT() / $this->getHME());
    }

    /**
     * @return mixed
     */
    public function getLUH()
    {
        return $this->LUH;
    }


    public function setCOH()
    {
        $this->COH = $this->getHME() == 0 ? null : ($this->getCCT() / $this->getHME());
    }

    /**
     * @return mixed
     */
    public function getCOH()
    {
        return $this->COH;
    }


    public function setCLT()
    {
        $litros_lub_tot = 0;

        foreach ($this->getUsoMaquinaria() as $uso_maq)
        {

            if (count($uso_maq->uso_comb_lub) > 0) {

                foreach ($uso_maq->uso_comb_lub as $uso_comb) {
                    if ($uso_comb->categoria == 'Lubricante') {

                        $litros_lub_tot = $litros_lub_tot + $uso_comb->litros;

                    }
                }
            }
        }

        $this->CLT = $litros_lub_tot;
    }


    /**
     * @return mixed
     */
    public function getCLT()
    {
        return $this->CLT;
    }

    /**
     * @param mixed $litros_lubricante
     */
    public function setLitrosLubricante()
    {

        $litros = 0;

        foreach ($this->getUsoMaquinaria() as $uso_maq)
        {

            if (count($uso_maq->uso_comb_lub) > 0) {

                foreach ($uso_maq->uso_comb_lub as $uso_comb) {
                    if ($uso_comb->categoria == 'Lubricante') {

                        $litros = $litros + $uso_comb->litros;

                    }
                }
            }
        }


        $this->litros_lubricante = $litros;
    }

    /**
     * @return mixed
     */
    public function getLitrosLubricante()
    {
        return $this->litros_lubricante;
    }



    public function setGastosLubricantes()
    {
        $gastos_lub = 0;

        foreach ($this->getUsoMaquinaria() as $uso_maq)
        {

            if (count($uso_maq->uso_comb_lub) > 0) {

                foreach ($uso_maq->uso_comb_lub as $uso_comb) {
                    if ($uso_comb->categoria == 'Lubricante') {

                        $gastos_lub = $gastos_lub + ($uso_comb->litros * $uso_comb->precio);

                    }
                }
            }
        }

        $this->gastos_lubricantes = $gastos_lub;

    }


    /**
     * @return mixed
     */
    public function getGastosLubricantes()
    {
        return $this->gastos_lubricantes;
    }



    /**
     * @return mixed
     */
    public function getCCT()
    {
        return $this->CCT;
    }

    public function setCCT()
    {

        $litros_comb_tol_ = 0;


        foreach ($this->getUsoMaquinaria() as $uso_maq)
        {

            if (count($uso_maq->uso_comb_lub) > 0) {

                foreach ($uso_maq->uso_comb_lub as $uso_comb) {
                    if ($uso_comb->categoria == 'Combustible') {

                        $litros_comb_tol_ = $litros_comb_tol_ + $uso_comb->litros;

                    }
                }
            }
        }

        $this->CCT = $litros_comb_tol_;

    }

    /**
     * @param mixed $litros_combustible
     */
    public function setLitrosCombustible()
    {

        $litros = 0;


        foreach ($this->getUsoMaquinaria() as $uso_maq)
        {

            if (count($uso_maq->uso_comb_lub) > 0) {

                foreach ($uso_maq->uso_comb_lub as $uso_comb) {
                    if ($uso_comb->categoria == 'Combustible') {

                        $litros = $litros + $uso_comb->litros;

                    }
                }
            }
        }

        $this->litros_combustible = $litros;

    }

    /**
     * @return mixed
     */
    public function getLitrosCombustible()
    {
        return $this->litros_combustible;
    }



    public function setGastosCombustibles()
    {
        $gastos_comb = 0;


        foreach ($this->getUsoMaquinaria() as $uso_maq)
        {

            if (count($uso_maq->uso_comb_lub) > 0) {

                foreach ($uso_maq->uso_comb_lub as $uso_comb) {
                    if ($uso_comb->categoria == 'Combustible') {

                        $gastos_comb = $gastos_comb + ($uso_comb->litros * $uso_comb->precio);

                    }
                }
            }
        }

        $this->gastos_combustibles = $gastos_comb;


    }

    /**
     * @return mixed
     */
    public function getGastosCombustibles()
    {
        return $this->gastos_combustibles;
    }


    public function setHME()
    {
        $horas_tol = 0;
        foreach ($this->getUsoMaquinaria() as $uso_maq)
        {
            $horas_tol = $horas_tol + $uso_maq->horas_trabajo;
        }

        $this->HME = $horas_tol;
    }


    /**
     * @return mixed
     */
    public function getHME()
    {
        return $this->HME;
    }

    /**
     * @return mixed
     */
    public function getGastosSueldo()
    {
        return $this->gastos_sueldo;
    }

    public function setGastosSueldo()
    {
        $gastos_sueldos_ = 0;
        foreach ($this->getOperariosData() as $oper)
        {
            $gastos_sueldos_ = $gastos_sueldos_ + $oper->sueldo;
        }

        $this->gastos_sueldo = $gastos_sueldos_;

    }

    /**
     * @return mixed
     */
    public function getToneladas()
    {
        return $this->toneladas;
    }

    public function setToneladas()
    {
        $toneladas_ = 0;
        foreach ($this->getRemitos() as $rem) {
            $toneladas_ = $toneladas_ + $rem->ton;
        }

        $this->toneladas = $toneladas_;
    }

    /**
     * @return mixed
     */
    public function getPrecioTonelada()
    {
        return $this->precio_tonelada;
    }


    public function setPrecioTonelada()
    {
        //el precio es ponderado
        $precio_= 0;
        foreach ($this->getRemitos() as $rem)
        {
            $precio_ = $precio_ + ($rem->precio_ton * $this->getToneladas());
        }

        $result = $this->getToneladas() > 0 ? $precio_ / $this->getToneladas() : 0;

        $this->precio_tonelada = $result;

    }



    /**
     * @return mixed
     */
    public function getVAD()
    {
        return $this->VAD;
    }

    /**
     * @param mixed $VAD
     */
    public function setVAD($VAD)
    {
        $this->VAD = $VAD;
    }

    /**
     * @return mixed
     */
    public function getTIS()
    {
        return $this->TIS;
    }

    /**
     * @param mixed $TIS
     */
    public function setTIS($TIS)
    {
        $this->TIS = $TIS;
    }

    /**
     * @return mixed
     */
    public function getFCI()
    {
        return $this->FCI;
    }

    /**
     * @param mixed $FCI
     */
    public function setFCI($FCI)
    {
        $this->FCI = $FCI;
    }

    /**
     * @return mixed
     */
    public function getHTU()
    {
        return $this->HTU;
    }

    /**
     * @param mixed $HTU
     */
    public function setHTU($HTU)
    {
        $this->HTU = $HTU;
    }

    /**
     * @return mixed
     */
    public function getVAN()
    {
        return $this->VAN;
    }

    /**
     * @param mixed $VAN
     */
    public function setVAN($VAN)
    {
        $this->VAN = $VAN;
    }

    /**
     * @return mixed
     */
    public function getHFU()
    {
        return $this->HFU;
    }

    /**
     * @param mixed $HFU
     */
    public function setHFU($HFU)
    {
        $this->HFU = $HFU;
    }

    /**
     * @return mixed
     */
    public function getVUE()
    {
        return $this->VUE;
    }

    /**
     * @param mixed $VUE
     */
    public function setVUE($VUE)
    {
        $this->VUE = $VUE;
    }

    /**
     * @return mixed
     */
    public function getVUN()
    {
        return $this->VUN;
    }

    /**
     * @param mixed $VUN
     */
    public function setVUN($VUN)
    {
        $this->VUN = $VUN;
    }

    /**
     * @return mixed
     */
    public function getOperariosData()
    {
        return $this->operarios_data;
    }

    /**
     * @param mixed $operarios_data
     */
    public function setOperariosData($operarios_data)
    {
        $this->operarios_data = $operarios_data;
    }

    /**
     * @return mixed
     */
    public function getRemitos()
    {
        return $this->remitos;
    }

    /**
     * @param mixed $remitos
     */
    public function setRemitos($remitos)
    {
        $this->remitos = $remitos;
    }

    /**
     * @return mixed
     */
    public function getUsoMaquinaria()
    {
        return $this->uso_maquinaria;
    }

    /**
     * @param mixed $uso_maquinaria
     */
    public function setUsoMaquinaria($uso_maquinaria)
    {
        $this->uso_maquinaria = $uso_maquinaria;
    }

    /**
     * @return mixed
     */
    public function getArreglos()
    {
        return $this->arreglos;
    }

    /**
     * @param mixed $arreglos
     */
    public function setArreglos($arreglos)
    {
        $this->arreglos = $arreglos;
    }







}
