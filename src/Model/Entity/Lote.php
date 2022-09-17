<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Lote Entity
 *
 * @property int $idlotes
 * @property string $name
 * @property string|null $description
 * @property string|null $provincia
 * @property string|null $departamento
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property int $empresas_idempresas
 * @property int $users_idusers
 */
class Lote extends Entity
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
        'provincia' => true,
        'departamento' => true,
        'created' => true,
        'finished' => true,
        'empresas_idempresas' => true,
        'users_idusers' => true,
    ];
}
