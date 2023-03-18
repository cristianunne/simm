<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InformesMaquina Entity
 *
 * @property int $idinformes_maquinas
 * @property \Cake\I18n\FrozenDate $fecha_inicio
 * @property \Cake\I18n\FrozenDate $fecha_fin
 * @property int $users_idusers
 * @property int $empresas_idempresas
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $name
 * @property string|null $path
 * @property int $maquinas_idmaquinas
 * @property string|null $lote
 * @property string|null $parcela
 * @property string|null $propietario
 * @property string|null $destino
 */
class InformesMaquina extends Entity
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
        'maquinas_idmaquinas' => true,
        'lote' => true,
        'parcela' => true,
        'propietario' => true,
        'destino' => true,
    ];
}