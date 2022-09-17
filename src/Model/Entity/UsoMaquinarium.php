<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsoMaquinarium Entity
 *
 * @property int $iduso_maquinaria
 * @property int $maquinas_idmaquinas
 * @property int|null $parcelas_idparcelas
 * @property \Cake\I18n\FrozenDate $fecha
 * @property float|null $horas_trabajo
 * @property float|null $combustible
 * @property float|null $lubricante
 * @property int $users_idusers
 * @property int $empresas_idempresas
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class UsoMaquinarium extends Entity
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
        'maquinas_idmaquinas' => true,
        'parcelas_idparcelas' => true,
        'fecha' => true,
        'horas_trabajo' => true,
        'combustible' => true,
        'lubricante' => true,
        'users_idusers' => true,
        'empresas_idempresas' => true,
        'created' => true,
        'modified' => true,
    ];
}
