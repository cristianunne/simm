<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Parcela Entity
 *
 * @property int $idparcelas
 * @property string $name
 * @property string|null $description
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property int $lotes_idlotes
 * @property int $propietarios_idpropietarios
 * @property int $users_idusers
 * @property bool $active
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Propietario $propietario
 * @property \App\Model\Entity\Lote $lote
 */
class Parcela extends Entity
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
        'created' => true,
        'finished' => true,
        'lotes_idlotes' => true,
        'propietarios_idpropietarios' => true,
        'users_idusers' => true,
        'active' => true,
        'user' => true,
        'propietario' => true,
        'lote' => true,
    ];
}
