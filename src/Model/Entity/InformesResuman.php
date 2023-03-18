<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InformesResuman Entity
 *
 * @property int $idinformes_resumen
 * @property \Cake\I18n\FrozenDate $fecha_inicio
 * @property \Cake\I18n\FrozenDate $fecha_fin
 * @property string $categoria
 * @property int $users_idusers
 * @property int $empresas_idempresas
 * @property string|null $name
 * @property string|null $path
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $clasificador
 * @property string $producto
 */
class InformesResuman extends Entity
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
        'fecha_inicio' => true,
        'fecha_fin' => true,
        'categoria' => true,
        'users_idusers' => true,
        'empresas_idempresas' => true,
        'name' => true,
        'path' => true,
        'created' => true,
        'modified' => true,
        'clasificador' => true,
        'producto' => true,
    ];
}
