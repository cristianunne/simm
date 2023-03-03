<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Remito Entity
 *
 * @property int $idremitos
 * @property int $remito_number
 * @property string $hash_id
 * @property \Cake\I18n\FrozenDate $fecha
 * @property int $worksgroups_idworksgroups
 * @property int|null $parcelas_idparcelas
 * @property int $propietarios_idpropietarios
 * @property int $productos_idproductos
 * @property float $precio_ton
 * @property int $users_idusers
 * @property int $empresas_idempresas
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property bool $active
 * @property int $destinos_iddestinos
 * @property float|null $ton
 * @property int $lotes_idlotes
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Worksgroup $worksgroup
 * @property \App\Model\Entity\Propietario $propietario
 * @property \App\Model\Entity\Lote $lote
 * @property \App\Model\Entity\Parcela $parcela
 * @property \App\Model\Entity\Producto $producto
 * @property \App\Model\Entity\Destino $destino
 * @property \App\Model\Entity\RemitosMaquina[] $remitos_maquinas
 */
class Remito extends Entity
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
        'remito_number' => true,
        'hash_id' => true,
        'fecha' => true,
        'worksgroups_idworksgroups' => true,
        'parcelas_idparcelas' => true,
        'propietarios_idpropietarios' => true,
        'productos_idproductos' => true,
        'precio_ton' => true,
        'users_idusers' => true,
        'empresas_idempresas' => true,
        'created' => true,
        'modified' => true,
        'active' => true,
        'destinos_iddestinos' => true,
        'ton' => true,
        'lotes_idlotes' => true,
        'user' => true,
        'worksgroup' => true,
        'propietario' => true,
        'lote' => true,
        'parcela' => true,
        'producto' => true,
        'destino' => true,
        'remitos_maquinas' => true,
    ];
}
