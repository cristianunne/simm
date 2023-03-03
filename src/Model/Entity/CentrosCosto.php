<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CentrosCosto Entity
 *
 * @property int $idcentros_costos
 * @property string $name
 * @property string $orden
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool $active
 * @property int $empresas_idempresas
 * @property int $users_idusers
 * @property string $categoria
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Empresa $empresa
 */
class CentrosCosto extends Entity
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
        'orden' => true,
        'created' => true,
        'finished' => true,
        'active' => true,
        'empresas_idempresas' => true,
        'users_idusers' => true,
        'categoria' => true,
        'user' => true,
        'empresa' => true,
    ];
}
