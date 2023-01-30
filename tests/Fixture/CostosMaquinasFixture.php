<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CostosMaquinasFixture
 */
class CostosMaquinasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'idcostos_maquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'val_adq' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'val_neum' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'vida_util' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'vida_util_neum' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'horas_total_uso' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'horas_efec_uso' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'horas_mens_uso' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'horas_dia_uso' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'tasa_int_simple' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'factor_cor' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'coef_err_mec' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'consumo' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'lubricante' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'costo_alquiler' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'maquinas_idmaquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'finished' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'users_idusers' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'worksgroups_idworksgroups' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'centros_costos_idcentros_costos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'metod_costos_hashmetod_costos' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'hash_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'alquilada' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'credito' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_costos_maquinas_maquinas1_idx' => ['type' => 'index', 'columns' => ['maquinas_idmaquinas'], 'length' => []],
            'fk_costos_maquinas_users1_idx' => ['type' => 'index', 'columns' => ['users_idusers'], 'length' => []],
            'fk_costos_maquinas_worksgroups1_idx' => ['type' => 'index', 'columns' => ['worksgroups_idworksgroups'], 'length' => []],
            'fk_costos_maquinas_centros_costos1_idx' => ['type' => 'index', 'columns' => ['centros_costos_idcentros_costos'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['idcostos_maquinas'], 'length' => []],
            'idcostos_maquinas_UNIQUE' => ['type' => 'unique', 'columns' => ['idcostos_maquinas'], 'length' => []],
            'fk_costos_maquinas_centros_costos1' => ['type' => 'foreign', 'columns' => ['centros_costos_idcentros_costos'], 'references' => ['centros_costos', 'idcentros_costos'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_costos_maquinas_maquinas1' => ['type' => 'foreign', 'columns' => ['maquinas_idmaquinas'], 'references' => ['maquinas', 'idmaquinas'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
            'fk_costos_maquinas_users1' => ['type' => 'foreign', 'columns' => ['users_idusers'], 'references' => ['users', 'idusers'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_costos_maquinas_worksgroups1' => ['type' => 'foreign', 'columns' => ['worksgroups_idworksgroups'], 'references' => ['worksgroups', 'idworksgroups'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'idcostos_maquinas' => 1,
                'val_adq' => 1,
                'val_neum' => 1,
                'vida_util' => 1,
                'vida_util_neum' => 1,
                'horas_total_uso' => 1,
                'horas_efec_uso' => 1,
                'horas_mens_uso' => 1,
                'horas_dia_uso' => 1,
                'tasa_int_simple' => 1,
                'factor_cor' => 1,
                'coef_err_mec' => 1,
                'consumo' => 1,
                'lubricante' => 1,
                'costo_alquiler' => 1,
                'maquinas_idmaquinas' => 1,
                'created' => 1674574688,
                'finished' => '2023-01-24',
                'active' => 1,
                'users_idusers' => 1,
                'worksgroups_idworksgroups' => 1,
                'centros_costos_idcentros_costos' => 1,
                'metod_costos_hashmetod_costos' => 'Lorem ipsum dolor sit amet',
                'hash_id' => 'Lorem ipsum dolor sit amet',
                'alquilada' => 1,
                'credito' => 1,
            ],
        ];
        parent::init();
    }
}
