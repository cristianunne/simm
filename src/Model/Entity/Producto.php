<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Producto Entity
 *
 * @property int $idproductos
 * @property string|null $name
 * @property string|null $description
 * @property int|null $orden
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool|null $active
 * @property int $users_idusers
 * @property int $empresas_idempresas
 *
 * @property \App\Model\Entity\Destino[] $destinos
 */
class Producto extends Entity
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
        'orden' => true,
        'created' => true,
        'finished' => true,
        'active' => true,
        'users_idusers' => true,
        'empresas_idempresas' => true,
        'destinos' => true,
    ];
}
