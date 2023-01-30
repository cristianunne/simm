<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Informe Entity
 *
 * @property int $idinformes
 * @property string $name
 * @property \Cake\I18n\FrozenTime $fecha
 * @property string $path
 * @property string $grupo
 * @property int $empresas_idempresas
 * @property int $users_idusers
 */
class Informe extends Entity
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
        'name' => true,
        'fecha' => true,
        'path' => true,
        'grupo' => true,
        'empresas_idempresas' => true,
        'users_idusers' => true,
    ];
}
