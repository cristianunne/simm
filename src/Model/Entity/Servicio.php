<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Servicio Entity
 *
 * @property int $idservicios
 * @property float $precio
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $users_idusers
 * @property int $empresas_idempresas
 * @property \Cake\I18n\FrozenDate $fecha
 * @property string $categoria
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Empresa $empresa
 */
class Servicio extends Entity
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
        'precio' => true,
        'created' => true,
        'modified' => true,
        'users_idusers' => true,
        'empresas_idempresas' => true,
        'fecha' => true,
        'categoria' => true,
        'user' => true,
        'empresa' => true,
    ];
}
