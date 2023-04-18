<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DestinosProducto Entity
 *
 * @property int $iddestinos_productos
 * @property int $destinos_iddestinos
 * @property int $productos_idproductos
 * @property float|null $precio
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $finished
 * @property bool|null $active
 *
 * @property \App\Model\Entity\Destino $destino
 * @property \App\Model\Entity\Producto $producto
 */
class DestinosProducto extends Entity
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
        'destinos_iddestinos' => true,
        'productos_idproductos' => true,
        'precio' => true,
        'created' => true,
        'finished' => true,
        'active' => true,
        'destino' => true,
        'producto' => true,
    ];
}
