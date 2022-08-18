<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Operario Entity
 *
 * @property int $idoperarios
 * @property string $firstname
 * @property string $lastname
 * @property int|null $dni
 * @property string|null $address
 * @property string|null $email
 * @property string|null $logo
 * @property string|null $folder
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool|null $active
 * @property int $users_idusers
 * @property int $empresas_idempresas
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Empresa $empresa
 */
class Operario extends Entity
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
        'firstname' => true,
        'lastname' => true,
        'dni' => true,
        'address' => true,
        'email' => true,
        'logo' => true,
        'folder' => true,
        'created' => true,
        'finished' => true,
        'active' => true,
        'users_idusers' => true,
        'empresas_idempresas' => true,
        'user' => true,
        'empresa' => true,
    ];
}
