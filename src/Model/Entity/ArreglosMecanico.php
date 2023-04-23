<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ArreglosMecanico Entity
 *
 * @property int $idarreglos_mecanicos
 * @property \Cake\I18n\FrozenDate $fecha
 * @property string|null $num_comprobante
 * @property string|null $concepto
 * @property float|null $mano_obra
 * @property float|null $repuestos
 * @property float|null $total
 * @property int $maquinas_idmaquinas
 * @property int|null $parcelas_idparcelas
 * @property int $empresas_idempresas
 * @property int $users_idusers
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $lotes_idlotes
 */
class ArreglosMecanico extends Entity
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
        'fecha' => true,
        'num_comprobante' => true,
        'concepto' => true,
        'mano_obra' => true,
        'repuestos' => true,
        'total' => true,
        'maquinas_idmaquinas' => true,
        'parcelas_idparcelas' => true,
        'empresas_idempresas' => true,
        'users_idusers' => true,
        'created' => true,
        'modified' => true,
        'lotes_idlotes' => true,
    ];
}
