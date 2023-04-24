<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Informe Entity
 *
 * @property int $idinformes
 * @property \Cake\I18n\FrozenTime $created
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $worksgroups
 * @property string|null $lote
 * @property string|null $parcela
 * @property string|null $propietario
 * @property string|null $destino
 * @property int $users_idusers
 * @property int $empresas_idempresas
 * @property string $name
 * @property string|null $path_file
 */
class Informe extends Entity
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
        'created' => true,
        'fecha_inicio' => true,
        'fecha_fin' => true,
        'worksgroups' => true,
        'lote' => true,
        'parcela' => true,
        'propietario' => true,
        'destino' => true,
        'users_idusers' => true,
        'empresas_idempresas' => true,
        'name' => true,
        'path_file' => true,
    ];
}
