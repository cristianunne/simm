<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InformesVariacione Entity
 *
 * @property int $idinformes_variaciones
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property int $users_idusers
 * @property int $empresas_idempresas
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $name
 * @property string $path
 * @property string|null $tipo
 * @property string|null $maquina
 * @property string|null $worksgroups
 * @property string|null $lote
 * @property string|null $parcela
 * @property string|null $propietario
 * @property string|null $destino
 */
class InformesVariacione extends Entity
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
        'users_idusers' => true,
        'empresas_idempresas' => true,
        'created' => true,
        'modified' => true,
        'name' => true,
        'path' => true,
        'tipo' => true,
        'maquina' => true,
        'worksgroups' => true,
        'lote' => true,
        'parcela' => true,
        'propietario' => true,
        'destino' => true,
    ];
}
