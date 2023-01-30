<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ListaConstante Entity
 *
 * @property int $idlista_constantes
 * @property string|null $name
 * @property int $users_idusers
 *
 * @property \App\Model\Entity\User $user
 */
class ListaConstante extends Entity
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
        'users_idusers' => true,
        'user' => true,
    ];
}
