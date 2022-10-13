<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RemitosMaquina Entity
 *
 * @property int $idremitos_maquinas
 * @property int $remitos_idremitos
 * @property float|null $alquiler_ton
 * @property int $operarios_idoperarios
 * @property int $maquinas_idmaquinas
 */
class RemitosMaquina extends Entity
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
        'remitos_idremitos' => true,
        'alquiler_ton' => true,
        'operarios_idoperarios' => true,
        'maquinas_idmaquinas' => true,
    ];
}
