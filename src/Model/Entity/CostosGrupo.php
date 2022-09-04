<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CostosGrupo Entity
 *
 * @property int $idworksgroups
 * @property string $name
 * @property string|null $description
 * @property string $hash_id
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool|null $active
 * @property int $empresas_idempresas
 * @property int $users_idusers
 *
 * @property \App\Model\Entity\Hash $hash
 */
class CostosGrupo extends Entity
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
        'idworksgroups' => true,
        'name' => true,
        'description' => true,
        'hash_id' => true,
        'created' => true,
        'finished' => true,
        'active' => true,
        'empresas_idempresas' => true,
        'users_idusers' => true,
        'hash' => true,
    ];
}
