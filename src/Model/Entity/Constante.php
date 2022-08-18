<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Constante Entity
 *
 * @property int $idconstantes
 * @property string|null $name
 * @property string|null $description
 * @property float $value
 * @property int $empresas_idempresas
 * @property int $users_idusers
 * @property bool|null $active
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Empresa $empresa
 */
class Constante extends Entity
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
        'description' => true,
        'value' => true,
        'empresas_idempresas' => true,
        'users_idusers' => true,
        'active' => true,
        'created' => true,
        'finished' => true,
        'user' => true,
        'empresa' => true,
    ];
}
