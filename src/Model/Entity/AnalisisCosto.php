<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AnalisisCosto Entity
 *
 * @property int $idanalisis_costos
 * @property string $name
 * @property string $grupo
 * @property \Cake\I18n\FrozenTime $created
 * @property string $path
 */
class AnalisisCosto extends Entity
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
        'grupo' => true,
        'created' => true,
        'path' => true,
    ];
}
