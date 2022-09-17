<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Destino Entity
 *
 * @property int $iddestinos
 * @property string $name
 * @property string|null $address
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $logo
 * @property string|null $folder
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool|null $active
 * @property int $empresas_idempresas
 * @property int $users_idusers
 *
 * @property \App\Model\Entity\Producto[] $productos
 */
class Destino extends Entity
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
        'address' => true,
        'email' => true,
        'phone' => true,
        'logo' => true,
        'folder' => true,
        'created' => true,
        'finished' => true,
        'active' => true,
        'empresas_idempresas' => true,
        'users_idusers' => true,
        'productos' => true,
    ];
}
