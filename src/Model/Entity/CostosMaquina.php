<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CostosMaquina Entity
 *
 * @property int $idcostos_maquinas
 * @property float|null $val_adq
 * @property float|null $val_neum
 * @property float|null $vida_util
 * @property float|null $vida_util_neum
 * @property float|null $horas_total_uso
 * @property float|null $horas_efec_uso
 * @property float|null $horas_mens_uso
 * @property float|null $horas_dia_uso
 * @property float|null $tasa_int_simple
 * @property float|null $factor_cor
 * @property float|null $coef_err_mec
 * @property float|null $consumo
 * @property float|null $lubricante
 * @property float|null $costo_alquiler
 * @property int $maquinas_idmaquinas
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool $active
 * @property int $users_idusers
 * @property int $worksgroups_idworksgroups
 * @property int $centros_costos_idcentros_costos
 * @property int $metod_costos_idmetod_costos
 * @property string $hash_id
 *
 * @property \App\Model\Entity\Maquina $maquina
 * @property \App\Model\Entity\Worksgroup $worksgroup
 * @property \App\Model\Entity\CentrosCosto $centros_costo
 * @property \App\Model\Entity\MetodCosto $metod_costo
 * @property \App\Model\Entity\User $user
 */
class CostosMaquina extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'val_adq' => true,
        'val_neum' => true,
        'vida_util' => true,
        'vida_util_neum' => true,
        'horas_total_uso' => true,
        'horas_efec_uso' => true,
        'horas_mens_uso' => true,
        'horas_dia_uso' => true,
        'tasa_int_simple' => true,
        'factor_cor' => true,
        'coef_err_mec' => true,
        'consumo' => true,
        'lubricante' => true,
        'costo_alquiler' => true,
        'maquinas_idmaquinas' => true,
        'created' => true,
        'finished' => true,
        'active' => true,
        'users_idusers' => true,
        'worksgroups_idworksgroups' => true,
        'centros_costos_idcentros_costos' => true,
        'metod_costos_idmetod_costos' => true,
        'hash_id' => true,
        'maquina' => true,
        'worksgroup' => true,
        'centros_costo' => true,
        'metod_costo' => true,
        'user' => true,
    ];
}
