<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProvDepto Entity
 *
 * @property string|null $provincia
 * @property string|null $dpto
 * @property int $id
 */
class ProvDepto extends Entity
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
        'provincia' => true,
        'dpto' => true,
    ];
}
