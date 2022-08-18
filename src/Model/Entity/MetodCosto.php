<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MetodCosto Entity
 *
 * @property int $idmetod_costos
 * @property string $name
 * @property string|null $interes
 * @property string|null $seguro
 * @property string|null $dep_maq
 * @property string|null $dep_neum
 * @property string|null $arreglos_maq
 * @property string|null $cons_comb
 * @property string|null $cons_lub
 * @property string|null $operador
 * @property string|null $mantenimiento
 * @property string|null $administracion
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool $active
 * @property int $users_idusers
 * @property int $empresas_idempresas
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Empresa $empresa
 */
class MetodCosto extends Entity
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
        'interes' => true,
        'seguro' => true,
        'dep_maq' => true,
        'dep_neum' => true,
        'arreglos_maq' => true,
        'cons_comb' => true,
        'cons_lub' => true,
        'operador' => true,
        'mantenimiento' => true,
        'administracion' => true,
        'created' => true,
        'finished' => true,
        'active' => true,
        'users_idusers' => true,
        'empresas_idempresas' => true,
        'user' => true,
        'empresa' => true,
    ];
}
