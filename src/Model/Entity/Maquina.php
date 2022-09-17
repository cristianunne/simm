<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Maquina Entity
 *
 * @property int $idmaquinas
 * @property string $name
 * @property string|null $marca
 * @property bool $propia
 * @property bool $active
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property int $empresas_idempresas
 * @property int $users_idusers
 *
 * @property \App\Model\Entity\Operario[] $operarios
 */
class Maquina extends Entity
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
        'marca' => true,
        'propia' => true,
        'active' => true,
        'created' => true,
        'finished' => true,
        'empresas_idempresas' => true,
        'users_idusers' => true,
        'operarios' => true,
    ];
}
