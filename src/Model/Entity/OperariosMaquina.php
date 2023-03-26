<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OperariosMaquina Entity
 *
 * @property int $idoperarios_maquinas
 * @property float|null $sueldo
 * @property int $operarios_idoperarios
 * @property int $maquinas_idmaquinas
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool $active
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenDate|null $fecha
 *
 * @property \App\Model\Entity\Maquina $maquina
 * @property \App\Model\Entity\Operario $operario
 */
class OperariosMaquina extends Entity
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
        'sueldo' => true,
        'operarios_idoperarios' => true,
        'maquinas_idmaquinas' => true,
        'created' => true,
        'finished' => true,
        'active' => true,
        'modified' => true,
        'fecha' => true,
        'maquina' => true,
        'operario' => true,
    ];
}
