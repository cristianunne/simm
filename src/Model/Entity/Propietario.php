<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Propietario Entity
 *
 * @property int $idpropietarios
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $name
 * @property string|null $tipo
 * @property string|null $dni
 * @property string|null $address
 * @property string|null $email
 * @property string|null $logo
 * @property string|null $folder
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool $active
 * @property int $users_idusers
 * @property int $empresas_idempresas
 * @property string|null $phone
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Empresa $empresa
 */
class Propietario extends Entity
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
        'name' => true,
        'tipo' => true,
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
        'phone' => true,
        'user' => true,
        'empresa' => true,
    ];
}
