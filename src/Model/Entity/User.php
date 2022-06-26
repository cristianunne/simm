<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $idusers
 * @property string $email
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 * @property string|null $role
 * @property bool|null $active
 * @property string|null $photo
 * @property string|null $folder
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $empresas_idempresas
 */
class User extends Entity
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
        'email' => true,
        'password' => true,
        'firstname' => true,
        'lastname' => true,
        'role' => true,
        'active' => true,
        'photo' => true,
        'folder' => true,
        'created' => true,
        'modified' => true,
        'empresas_idempresas' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
    ];

    //Encryptación de las contraseñas de los usuarios
    protected function _setPassword($value)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);
    }
}
