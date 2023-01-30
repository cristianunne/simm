<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsoCombLub Entity
 *
 * @property int $iduso_comb_lub
 * @property string $categoria
 * @property string $producto
 * @property float $litros
 * @property int $uso_maquinaria_iduso_maquinaria
 * @property float $precio
 */
class UsoCombLub extends Entity
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
        'categoria' => true,
        'producto' => true,
        'litros' => true,
        'uso_maquinaria_iduso_maquinaria' => true,
        'precio' => true,
    ];
}
